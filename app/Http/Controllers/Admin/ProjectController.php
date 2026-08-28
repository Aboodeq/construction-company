<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectImage;
use App\Models\Service;
use App\Services\ImageUploader;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(private readonly ImageUploader $images)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('projects.view');

        $categories = ProjectCategory::query()
            ->withCount('projects')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $projectsQuery = Project::query()
            ->with('category')
            ->withCount(['images', 'services', 'testimonials'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('client_name', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status')->toString());
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('project_category_id', $request->integer('category'));
            });

        $projects = $projectsQuery
            ->orderBy('order')
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Project::count(),
            'published' => Project::where('status', 'published')->count(),
            'drafts' => Project::where('status', 'draft')->count(),
            'featured' => Project::where('is_featured', true)->count(),
        ];

        return view('admin.projects.index', compact('categories', 'projects', 'stats'));
    }

    public function create()
    {
        $this->authorize('projects.create');

        $categories = ProjectCategory::orderBy('order')->orderBy('name')->get();

        return view('admin.projects.create', compact('categories'));
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $data['is_featured'] = $request->boolean('is_featured');

        $project = Project::create($data);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'تم إنشاء المشروع بنجاح. يمكنك الآن إضافة الصور والخدمات المرتبطة.');
    }

    public function edit(Project $project)
    {
        $this->authorize('projects.edit');

        $project->load(['category', 'images', 'services', 'seoMeta']);
        $categories = ProjectCategory::orderBy('order')->orderBy('name')->get();
        $allServices = Service::orderBy('title')->get(['id', 'title']);

        return view('admin.projects.edit', compact('project', 'categories', 'allServices'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = $request->safe()->only([
            'title', 'project_category_id', 'client_name', 'location', 'area',
            'completion_date', 'duration', 'description', 'status', 'order',
        ]);
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->boolean('remove_cover_image') && $project->cover_image) {
            $this->images->delete($project->cover_image);
            $data['cover_image'] = null;
        }

        if ($request->hasFile('cover_image')) {
            $this->images->delete($project->cover_image);
            $data['cover_image'] = $this->images->store($request->file('cover_image'), 'projects');
        }

        $project->update($data);

        foreach (['gallery_images' => 'gallery', 'before_images' => 'before', 'after_images' => 'after'] as $field => $type) {
            foreach ($request->file($field, []) as $file) {
                ProjectImage::create([
                    'project_id' => $project->id,
                    'image_path' => $this->images->store($file, "projects/{$type}"),
                    'type' => $type,
                    'order' => (int) $project->images()->where('type', $type)->max('order') + 1,
                ]);
            }
        }

        $project->services()->sync($request->input('services', []));

        if ($request->filled('meta_title') || $request->filled('meta_description')) {
            $project->seoMeta()->updateOrCreate([], [
                'meta_title' => $request->input('meta_title'),
                'meta_description' => $request->input('meta_description'),
            ]);
        }

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'تم حفظ التغييرات بنجاح.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('projects.delete');

        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'تم حذف المشروع.');
    }

    public function toggleFeatured(Project $project)
    {
        $this->authorize('projects.edit');

        $project->update(['is_featured' => ! $project->is_featured]);

        return back()->with('success', $project->is_featured ? 'تم تمييز المشروع.' : 'تم إلغاء تمييز المشروع.');
    }

    public function togglePublished(Project $project)
    {
        $this->authorize('projects.edit');

        $project->update(['status' => $project->status === 'published' ? 'draft' : 'published']);

        return back()->with('success', $project->status === 'published' ? 'تم نشر المشروع.' : 'تم تحويل المشروع إلى مسودة.');
    }
}
