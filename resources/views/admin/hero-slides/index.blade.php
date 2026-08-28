@php
    $statusLabels = ['published' => 'منشور', 'draft' => 'مسودة'];
    $statusClasses = [
        'published' => 'border-forest/15 bg-forest/5 text-forest',
        'draft' => 'border-line bg-paper text-ink-soft',
    ];
@endphp

<x-admin.layouts.app title="شرائح البانر">
    <div class="mx-auto w-full max-w-5xl">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <h2 class="font-display text-2xl font-semibold text-ink">شرائح البانر الرئيسي</h2>
                <div class="mt-2 flex items-center gap-1.5 text-ink-soft" aria-hidden="true">
                    @for ($i = 0; $i < 24; $i++)
                        <span class="h-2 w-px bg-line"></span>
                    @endfor
                </div>
                <p class="mt-3 max-w-3xl text-sm leading-6 text-ink-soft">
                    الشرائح المتحركة التي تظهر أعلى الصفحة الرئيسية بالموقع.
                </p>
            </div>

            <a href="{{ route('admin.hero-slides.create') }}"
                class="inline-flex h-11 items-center justify-center gap-1.5 rounded-lg bg-ink px-4 text-sm font-medium text-brass-soft transition hover:bg-brass hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                إضافة شريحة
            </a>
        </div>

        <section class="overflow-hidden rounded-lg border border-line bg-surface">
            <div class="divide-y divide-line">
                @forelse ($heroSlides as $heroSlide)
                    <article class="flex flex-wrap items-center gap-4 p-5 transition hover:bg-paper/45">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brass-soft text-sm font-semibold text-brass">
                            {{ $heroSlide->order }}
                        </span>

                        @if ($heroSlide->image)
                            <img src="{{ asset('storage/'.$heroSlide->image) }}" alt=""
                                class="h-12 w-20 shrink-0 rounded-lg border border-line object-cover">
                        @else
                            <div class="flex h-12 w-20 shrink-0 items-center justify-center rounded-lg border border-dashed border-line bg-paper text-[11px] text-ink-soft">
                                بدون صورة
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <a href="{{ route('admin.hero-slides.edit', $heroSlide) }}"
                                class="font-semibold text-ink transition hover:text-brass">
                                {{ $heroSlide->title }}
                            </a>
                            @if ($heroSlide->subtitle)
                                <p class="mt-1 line-clamp-1 max-w-md text-sm text-ink-soft">{{ $heroSlide->subtitle }}</p>
                            @endif
                        </div>

                        <span class="inline-flex shrink-0 rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusClasses[$heroSlide->status] ?? '' }}">
                            {{ $statusLabels[$heroSlide->status] ?? $heroSlide->status }}
                        </span>

                        <div class="flex shrink-0 items-center gap-2">
                            <a href="{{ route('admin.hero-slides.edit', $heroSlide) }}"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-line text-ink-soft transition hover:border-brass/40 hover:text-brass"
                                title="تعديل">
                                <span class="sr-only">تعديل</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 21h-15a2.25 2.25 0 01-2.25-2.25v-15A2.25 2.25 0 014.5 3.75h6" />
                                </svg>
                            </a>
                            <x-admin.confirm-form
                                :action="route('admin.hero-slides.destroy', $heroSlide)"
                                title="حذف الشريحة"
                                :message="'سيتم حذف شريحة «'.$heroSlide->title.'» نهائيًا. هل تريد المتابعة؟'"
                                class="h-9 w-9"
                                triggerLabel="حذف">
                                <span class="sr-only">حذف</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </x-admin.confirm-form>
                        </div>
                    </article>
                @empty
                    <div class="px-5 py-14 text-center">
                        <p class="font-medium text-ink">لا توجد شرائح بعد</p>
                        <p class="mt-2 text-sm text-ink-soft">أضف أول شريحة لتظهر أعلى الصفحة الرئيسية.</p>
                    </div>
                @endforelse
            </div>
        </section>

        @if ($heroSlides->hasPages())
            <div class="mt-6 flex justify-center gap-2 text-sm text-ink-soft">
                @if ($heroSlides->onFirstPage())
                    <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">السابق</span>
                @else
                    <a href="{{ $heroSlides->previousPageUrl() }}" class="rounded-lg border border-line px-4 py-2 hover:bg-surface hover:text-ink">السابق</a>
                @endif
                @if ($heroSlides->hasMorePages())
                    <a href="{{ $heroSlides->nextPageUrl() }}" class="rounded-lg border border-line px-4 py-2 hover:bg-surface hover:text-ink">التالي</a>
                @else
                    <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">التالي</span>
                @endif
            </div>
        @endif
    </div>
</x-admin.layouts.app>
