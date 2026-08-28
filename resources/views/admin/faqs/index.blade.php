@php
    $statusLabels = ['published' => 'منشور', 'draft' => 'مسودة'];
    $statusClasses = [
        'published' => 'border-forest/15 bg-forest/5 text-forest',
        'draft' => 'border-line bg-paper text-ink-soft',
    ];
@endphp

<x-admin.layouts.app title="الأسئلة الشائعة">
    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <h2 class="font-display text-2xl font-semibold text-ink">الأسئلة الشائعة</h2>
                <div class="mt-2 flex items-center gap-1.5 text-ink-soft" aria-hidden="true">
                    @for ($i = 0; $i < 24; $i++)
                        <span class="h-2 w-px bg-line"></span>
                    @endfor
                </div>
                <p class="mt-3 max-w-3xl text-sm leading-6 text-ink-soft">
                    الأسئلة الشائعة العامة المعروضة بالموقع، منظّمة حسب التصنيف.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="rounded-lg border border-line bg-surface px-4 py-3 text-sm text-ink-soft">
                    <span class="font-medium text-ink">{{ $faqs->total() }}</span>
                    سؤال ضمن النتائج الحالية
                </div>
                @can('faqs.create')
                    <a href="{{ route('admin.faqs.create') }}"
                        class="inline-flex h-11 items-center justify-center gap-1.5 rounded-lg bg-ink px-4 text-sm font-medium text-brass-soft transition hover:bg-brass hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        إضافة سؤال
                    </a>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">إجمالي الأسئلة</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-ink">{{ number_format($stats['total']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-ink"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">منشورة</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-forest">{{ number_format($stats['published']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-forest"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">مسودات</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-ink">{{ number_format($stats['drafts']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-line"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">تصنيفات مستخدمة</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-brass">{{ number_format($stats['categories']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-brass"></span>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.faqs.index') }}"
            class="mt-6 rounded-lg border border-line bg-surface p-5">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-5">
                    <label for="faqs-search" class="mb-2 block text-xs font-medium text-ink-soft">بحث</label>
                    <input id="faqs-search" name="search" value="{{ request('search') }}" type="search"
                        placeholder="نص السؤال"
                        class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">
                </div>
                <div class="lg:col-span-3">
                    <label for="faqs-category" class="mb-2 block text-xs font-medium text-ink-soft">التصنيف</label>
                    <select id="faqs-category" name="category"
                        class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
                        <option value="">كل التصنيفات</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label for="faqs-status" class="mb-2 block text-xs font-medium text-ink-soft">الحالة</label>
                    <select id="faqs-status" name="status"
                        class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
                        <option value="">كل الحالات</option>
                        <option value="published" @selected(request('status') === 'published')>منشور</option>
                        <option value="draft" @selected(request('status') === 'draft')>مسودة</option>
                    </select>
                </div>
                <div class="flex gap-2 lg:col-span-2">
                    <button type="submit"
                        class="inline-flex h-11 flex-1 items-center justify-center rounded-lg bg-ink px-4 text-sm font-medium text-brass-soft transition hover:bg-brass hover:text-white">
                        تصفية
                    </button>
                    @if (request()->hasAny(['search', 'category', 'status']))
                        <a href="{{ route('admin.faqs.index') }}"
                            class="inline-flex h-11 items-center justify-center rounded-lg border border-line px-4 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                            مسح
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <section class="mt-6 overflow-hidden rounded-lg border border-line bg-surface">
            <div class="divide-y divide-line">
                @forelse ($faqs as $faq)
                    <article class="flex flex-wrap items-center gap-4 p-5 transition hover:bg-paper/45">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brass-soft text-sm font-semibold text-brass">
                            {{ $faq->order }}
                        </span>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                @can('faqs.edit')
                                    <a href="{{ route('admin.faqs.edit', $faq) }}"
                                        class="font-semibold text-ink transition hover:text-brass">
                                        {{ $faq->question }}
                                    </a>
                                @else
                                    <p class="font-semibold text-ink">{{ $faq->question }}</p>
                                @endcan
                                @if ($faq->category)
                                    <span class="inline-flex rounded-full border border-line bg-paper px-2.5 py-1 text-xs text-ink-soft">
                                        {{ $faq->category }}
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1 line-clamp-1 max-w-2xl text-sm text-ink-soft">{{ $faq->answer }}</p>
                        </div>

                        <span
                            class="inline-flex shrink-0 rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusClasses[$faq->status] ?? 'border-line bg-paper text-ink-soft' }}">
                            {{ $statusLabels[$faq->status] ?? $faq->status }}
                        </span>

                        <div class="flex shrink-0 items-center gap-2">
                            @can('faqs.edit')
                                <a href="{{ route('admin.faqs.edit', $faq) }}"
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
                            @endcan
                            @can('faqs.delete')
                                <x-admin.confirm-form
                                    :action="route('admin.faqs.destroy', $faq)"
                                    title="حذف السؤال"
                                    message="سيتم حذف هذا السؤال نهائيًا. هل تريد المتابعة؟"
                                    class="h-9 w-9"
                                    triggerLabel="حذف">
                                    <span class="sr-only">حذف</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </x-admin.confirm-form>
                            @endcan
                        </div>
                    </article>
                @empty
                    <div class="px-5 py-14 text-center">
                        <p class="font-medium text-ink">لا توجد أسئلة مطابقة</p>
                        <p class="mt-2 text-sm text-ink-soft">جرّب تعديل البحث أو الفلاتر الحالية.</p>
                    </div>
                @endforelse
            </div>
        </section>

        @if ($faqs->hasPages())
            <div class="mt-6 flex flex-col gap-3 border-t border-line pt-5 text-sm text-ink-soft sm:flex-row sm:items-center sm:justify-between">
                <p>
                    عرض {{ $faqs->firstItem() }} - {{ $faqs->lastItem() }} من {{ $faqs->total() }}
                </p>
                <div class="flex items-center gap-2">
                    @if ($faqs->onFirstPage())
                        <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">السابق</span>
                    @else
                        <a href="{{ $faqs->previousPageUrl() }}"
                            class="rounded-lg border border-line px-4 py-2 transition hover:bg-surface hover:text-ink">
                            السابق
                        </a>
                    @endif

                    @if ($faqs->hasMorePages())
                        <a href="{{ $faqs->nextPageUrl() }}"
                            class="rounded-lg border border-line px-4 py-2 transition hover:bg-surface hover:text-ink">
                            التالي
                        </a>
                    @else
                        <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">التالي</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-admin.layouts.app>
