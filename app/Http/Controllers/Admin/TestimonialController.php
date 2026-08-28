<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestimonialRequest;
use App\Http\Requests\Admin\UpdateTestimonialRequest;
use App\Models\Project;
use App\Models\Testimonial;
use App\Services\ImageUploader;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function __construct(private readonly ImageUploader $images)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('testimonials.view');

        $testimonialsQuery = Testimonial::query()
            ->with('project')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where('client_name', 'like', "%{$search}%");
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            });

        $testimonials = $testimonialsQuery
            ->orderBy('order')
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Testimonial::count(),
            'published' => Testimonial::where('status', 'published')->count(),
            'pending' => Testimonial::where('status', 'pending')->count(),
            'featured' => Testimonial::where('is_featured', true)->count(),
        ];

        return view('admin.testimonials.index', compact('testimonials', 'stats'));
    }

    public function create()
    {
        $this->authorize('testimonials.create');

        $projects = Project::orderBy('title')->get(['id', 'title']);

        return view('admin.testimonials.create', compact('projects'));
    }

    public function store(StoreTestimonialRequest $request)
    {
        $data = $request->validated();
        $data['is_featured'] = $request->boolean('is_featured');

        $testimonial = Testimonial::create($data);

        return redirect()
            ->route('admin.testimonials.edit', $testimonial)
            ->with('success', 'تمت إضافة رأي العميل بنجاح. يمكنك الآن إضافة صورته.');
    }

    public function edit(Testimonial $testimonial)
    {
        $this->authorize('testimonials.edit');

        $testimonial->load('project');
        $projects = Project::orderBy('title')->get(['id', 'title']);

        return view('admin.testimonials.edit', compact('testimonial', 'projects'));
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        $data = $request->safe()->only(['project_id', 'client_name', 'rating', 'review', 'status', 'order']);
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->boolean('remove_client_image') && $testimonial->client_image) {
            $this->images->delete($testimonial->client_image);
            $data['client_image'] = null;
        }

        if ($request->hasFile('client_image')) {
            $this->images->delete($testimonial->client_image);
            $data['client_image'] = $this->images->store($request->file('client_image'), 'testimonials');
        }

        $testimonial->update($data);

        return redirect()
            ->route('admin.testimonials.edit', $testimonial)
            ->with('success', 'تم حفظ التغييرات بنجاح.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $this->authorize('testimonials.delete');

        $this->images->delete($testimonial->client_image);
        $testimonial->delete();

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'تم حذف رأي العميل.');
    }

    public function toggleFeatured(Testimonial $testimonial)
    {
        $this->authorize('testimonials.edit');

        $testimonial->update(['is_featured' => ! $testimonial->is_featured]);

        return back()->with('success', $testimonial->is_featured ? 'تم تمييز الرأي.' : 'تم إلغاء تمييز الرأي.');
    }

    public function togglePublished(Testimonial $testimonial)
    {
        $this->authorize('testimonials.edit');

        $testimonial->update(['status' => $testimonial->status === 'published' ? 'pending' : 'published']);

        return back()->with('success', $testimonial->status === 'published' ? 'تم نشر الرأي.' : 'تم إخفاء الرأي مؤقتًا.');
    }
}
