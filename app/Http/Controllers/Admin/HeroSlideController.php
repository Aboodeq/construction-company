<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHeroSlideRequest;
use App\Http\Requests\Admin\UpdateHeroSlideRequest;
use App\Models\HeroSlide;
use App\Services\ImageUploader;

class HeroSlideController extends Controller
{
    public function __construct(private readonly ImageUploader $images)
    {
    }

    public function index()
    {
        $this->authorize('homepage.edit');

        $heroSlides = HeroSlide::orderBy('order')->paginate(15);

        return view('admin.hero-slides.index', compact('heroSlides'));
    }

    public function create()
    {
        $this->authorize('homepage.edit');

        return view('admin.hero-slides.create');
    }

    public function store(StoreHeroSlideRequest $request)
    {
        $heroSlide = HeroSlide::create($request->validated());

        return redirect()
            ->route('admin.hero-slides.edit', $heroSlide)
            ->with('success', 'تمت إضافة الشريحة بنجاح. يمكنك الآن إضافة صورتها.');
    }

    public function edit(HeroSlide $heroSlide)
    {
        $this->authorize('homepage.edit');

        return view('admin.hero-slides.edit', compact('heroSlide'));
    }

    public function update(UpdateHeroSlideRequest $request, HeroSlide $heroSlide)
    {
        $data = $request->safe()->except(['image', 'remove_image']);

        if ($request->boolean('remove_image') && $heroSlide->image) {
            $this->images->delete($heroSlide->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            $this->images->delete($heroSlide->image);
            $data['image'] = $this->images->store($request->file('image'), 'hero-slides');
        }

        $heroSlide->update($data);

        return redirect()
            ->route('admin.hero-slides.edit', $heroSlide)
            ->with('success', 'تم حفظ التغييرات بنجاح.');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        $this->authorize('homepage.edit');

        $this->images->delete($heroSlide->image);
        $heroSlide->delete();

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'تم حذف الشريحة.');
    }

    public function togglePublished(HeroSlide $heroSlide)
    {
        $this->authorize('homepage.edit');

        $heroSlide->update(['status' => $heroSlide->status === 'published' ? 'draft' : 'published']);

        return back()->with('success', $heroSlide->status === 'published' ? 'تم نشر الشريحة.' : 'تم تحويل الشريحة إلى مسودة.');
    }
}
