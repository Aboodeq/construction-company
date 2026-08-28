<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('faqs.view');

        $categories = Faq::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $faqsQuery = Faq::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where('question', 'like', "%{$search}%");
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->input('category'));
            });

        $faqs = $faqsQuery
            ->orderBy('order')
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Faq::count(),
            'published' => Faq::where('status', 'published')->count(),
            'drafts' => Faq::where('status', 'draft')->count(),
            'categories' => $categories->count(),
        ];

        return view('admin.faqs.index', compact('faqs', 'categories', 'stats'));
    }

    public function create()
    {
        $this->authorize('faqs.create');

        $existingCategories = Faq::whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('admin.faqs.create', compact('existingCategories'));
    }

    public function store(StoreFaqRequest $request)
    {
        Faq::create($request->validated());

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'تمت إضافة السؤال بنجاح.');
    }

    public function edit(Faq $faq)
    {
        $this->authorize('faqs.edit');

        $existingCategories = Faq::whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('admin.faqs.edit', compact('faq', 'existingCategories'));
    }

    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $faq->update($request->validated());

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'تم حفظ التغييرات بنجاح.');
    }

    public function destroy(Faq $faq)
    {
        $this->authorize('faqs.delete');

        $faq->delete();

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'تم حذف السؤال.');
    }

    public function togglePublished(Faq $faq)
    {
        $this->authorize('faqs.edit');

        $faq->update(['status' => $faq->status === 'published' ? 'draft' : 'published']);

        return back()->with('success', $faq->status === 'published' ? 'تم نشر السؤال.' : 'تم تحويل السؤال إلى مسودة.');
    }
}
