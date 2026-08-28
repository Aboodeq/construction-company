<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePageSectionRequest;
use App\Models\PageSection;
use App\Services\ImageUploader;

class PageSectionController extends Controller
{
    public function __construct(private readonly ImageUploader $images)
    {
    }

    public function index()
    {
        $this->authorize('homepage.edit');

        $pageSections = PageSection::orderBy('key')->get();

        return view('admin.page-sections.index', compact('pageSections'));
    }

    public function edit(PageSection $pageSection)
    {
        $this->authorize('homepage.edit');

        return view('admin.page-sections.edit', compact('pageSection'));
    }

    public function update(UpdatePageSectionRequest $request, PageSection $pageSection)
    {
        $data = $request->safe()->only(['title', 'subtitle', 'content']);

        if ($request->has('points')) {
            $data['extra_data'] = ['points' => array_values($request->input('points'))];
        }

        if ($request->boolean('remove_image') && $pageSection->image) {
            $this->images->delete($pageSection->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            $this->images->delete($pageSection->image);
            $data['image'] = $this->images->store($request->file('image'), 'page-sections');
        }

        $pageSection->update($data);

        return redirect()
            ->route('admin.page-sections.edit', $pageSection)
            ->with('success', 'تم حفظ التغييرات بنجاح.');
    }
}
