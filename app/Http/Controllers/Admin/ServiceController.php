<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceImage;
use App\Services\ImageUploader;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(private readonly ImageUploader $images)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('services.view');

        $categories = ServiceCategory::query()
            ->withCount('services')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $servicesQuery = Service::query()
            ->with('category')
            ->withCount(['images', 'faqs', 'projects'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status')->toString());
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('service_category_id', $request->integer('category'));
            });

        $services = $servicesQuery
            ->orderBy('order')
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Service::count(),
            'published' => Service::where('status', 'published')->count(),
            'drafts' => Service::where('status', 'draft')->count(),
            'featured' => Service::where('is_featured', true)->count(),
        ];

        return view('admin.services.index', compact('categories', 'services', 'stats'));
    }

    public function create()
    {
        $this->authorize('services.create');

        $categories = ServiceCategory::orderBy('order')->orderBy('name')->get();

        return view('admin.services.create', compact('categories'));
    }

    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();
        $data['is_featured'] = $request->boolean('is_featured');

        $service = Service::create($data);

        return redirect()
            ->route('admin.services.edit', $service)
            ->with('success', 'تم إنشاء الخدمة بنجاح. يمكنك الآن إضافة الصور والأسئلة الشائعة.');
    }

    public function edit(Service $service)
    {
        $this->authorize('services.edit');

        $service->load(['category', 'images', 'faqs', 'seoMeta']);
        $categories = ServiceCategory::orderBy('order')->orderBy('name')->get();

        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $data = $request->safe()->only([
            'title', 'service_category_id', 'short_description', 'description', 'status', 'order',
        ]);
        $data['is_featured'] = $request->boolean('is_featured');

        $data['process_steps'] = collect($request->input('process_steps', []))
            ->filter(fn (array $step) => filled($step['title'] ?? null))
            ->values()
            ->map(fn (array $step, int $index) => [
                'step' => $index + 1,
                'title' => $step['title'],
                'description' => $step['description'] ?? '',
            ])
            ->all();

        if ($request->boolean('remove_featured_image') && $service->featured_image) {
            $this->images->delete($service->featured_image);
            $data['featured_image'] = null;
        }

        if ($request->hasFile('featured_image')) {
            $this->images->delete($service->featured_image);
            $data['featured_image'] = $this->images->store($request->file('featured_image'), 'services');
        }

        $service->update($data);

        foreach ($request->file('new_images', []) as $file) {
            ServiceImage::create([
                'service_id' => $service->id,
                'image_path' => $this->images->store($file, 'services/gallery'),
                'order' => (int) $service->images()->max('order') + 1,
            ]);
        }

        $keptFaqIds = [];
        foreach ($request->input('faqs', []) as $index => $faq) {
            if (blank($faq['question'] ?? null)) {
                continue;
            }

            $row = $service->faqs()->updateOrCreate(
                ['id' => $faq['id'] ?? null],
                [
                    'question' => $faq['question'],
                    'answer' => $faq['answer'] ?? '',
                    'order' => $index,
                ]
            );
            $keptFaqIds[] = $row->id;
        }
        $service->faqs()->whereNotIn('id', $keptFaqIds)->delete();

        if ($request->filled('meta_title') || $request->filled('meta_description')) {
            $service->seoMeta()->updateOrCreate([], [
                'meta_title' => $request->input('meta_title'),
                'meta_description' => $request->input('meta_description'),
            ]);
        }

        return redirect()
            ->route('admin.services.edit', $service)
            ->with('success', 'تم حفظ التغييرات بنجاح.');
    }

    public function destroy(Service $service)
    {
        $this->authorize('services.delete');

        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'تم حذف الخدمة.');
    }

    public function toggleFeatured(Service $service)
    {
        $this->authorize('services.edit');

        $service->update(['is_featured' => ! $service->is_featured]);

        return back()->with('success', $service->is_featured ? 'تم تمييز الخدمة.' : 'تم إلغاء تمييز الخدمة.');
    }

    public function togglePublished(Service $service)
    {
        $this->authorize('services.edit');

        $service->update(['status' => $service->status === 'published' ? 'draft' : 'published']);

        return back()->with('success', $service->status === 'published' ? 'تم نشر الخدمة.' : 'تم تحويل الخدمة إلى مسودة.');
    }
}
