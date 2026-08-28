<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use App\Services\ImageUploader;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function __construct(private readonly ImageUploader $images)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('blog.view');

        $categories = BlogCategory::query()
            ->withCount('posts')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $postsQuery = BlogPost::query()
            ->with(['category', 'author'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('blog_category_id', $request->integer('category'));
            });

        $posts = $postsQuery
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => BlogPost::count(),
            'published' => BlogPost::where('status', 'published')->count(),
            'drafts' => BlogPost::where('status', 'draft')->count(),
            'views' => BlogPost::sum('views_count'),
        ];

        return view('admin.blog-posts.index', compact('categories', 'posts', 'stats'));
    }

    public function create()
    {
        $this->authorize('blog.create');

        $categories = BlogCategory::orderBy('order')->orderBy('name')->get();
        $authors = User::orderBy('name')->get(['id', 'name']);

        return view('admin.blog-posts.create', compact('categories', 'authors'));
    }

    public function store(StoreBlogPostRequest $request)
    {
        $data = $request->validated();
        $data['author_id'] ??= $request->user()->id;

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $post = BlogPost::create($data);

        return redirect()
            ->route('admin.blog.edit', $post)
            ->with('success', 'تم إنشاء المقالة بنجاح. يمكنك الآن إضافة صورة الغلاف.');
    }

    public function edit(BlogPost $blogPost)
    {
        $this->authorize('blog.edit');

        $blogPost->load(['category', 'author', 'seoMeta']);
        $categories = BlogCategory::orderBy('order')->orderBy('name')->get();
        $authors = User::orderBy('name')->get(['id', 'name']);

        return view('admin.blog-posts.edit', ['post' => $blogPost, 'categories' => $categories, 'authors' => $authors]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost)
    {
        $data = $request->safe()->only([
            'title', 'blog_category_id', 'author_id', 'excerpt', 'content', 'status', 'published_at',
        ]);

        if ($data['status'] === 'published' && empty($data['published_at']) && ! $blogPost->published_at) {
            $data['published_at'] = now();
        }

        if ($request->boolean('remove_featured_image') && $blogPost->featured_image) {
            $this->images->delete($blogPost->featured_image);
            $data['featured_image'] = null;
        }

        if ($request->hasFile('featured_image')) {
            $this->images->delete($blogPost->featured_image);
            $data['featured_image'] = $this->images->store($request->file('featured_image'), 'blog');
        }

        $blogPost->update($data);

        if ($request->filled('meta_title') || $request->filled('meta_description')) {
            $blogPost->seoMeta()->updateOrCreate([], [
                'meta_title' => $request->input('meta_title'),
                'meta_description' => $request->input('meta_description'),
            ]);
        }

        return redirect()
            ->route('admin.blog.edit', $blogPost)
            ->with('success', 'تم حفظ التغييرات بنجاح.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $this->authorize('blog.delete');

        $blogPost->delete();

        return redirect()
            ->route('admin.blog.index')
            ->with('success', 'تم حذف المقالة.');
    }

    public function togglePublished(BlogPost $blogPost)
    {
        $this->authorize('blog.edit');

        $newStatus = $blogPost->status === 'published' ? 'draft' : 'published';
        $blogPost->status = $newStatus;

        if ($newStatus === 'published' && ! $blogPost->published_at) {
            $blogPost->published_at = now();
        }

        $blogPost->save();

        return back()->with('success', $newStatus === 'published' ? 'تم نشر المقالة.' : 'تم تحويل المقالة إلى مسودة.');
    }
}
