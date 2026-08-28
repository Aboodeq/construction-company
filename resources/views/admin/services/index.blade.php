@php
    $statusLabels = [
        'published' => 'منشورة',
        'draft' => 'مسودة',
    ];

    $statusClasses = [
        'published' => 'border-forest/15 bg-forest/5 text-forest',
        'draft' => 'border-line bg-paper text-ink-soft',
    ];
@endphp

<x-admin.layouts.app title="الخدمات">
    <div class="mx-auto w-full max-w-7xl">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <h2 class="font-display text-2xl font-semibold text-ink">الخدمات</h2>
                <div class="mt-2 flex items-center gap-1.5 text-ink-soft" aria-hidden="true">
                    @for ($i = 0; $i < 24; $i++)
                        <span class="h-2 w-px bg-line"></span>
                    @endfor
                </div>
                <p class="mt-3 max-w-3xl text-sm leading-6 text-ink-soft">
                    إدارة خدمات الشركة المعروضة في الموقع، ومراجعة حالتها وترتيبها والتصنيفات المرتبطة بها من مكان واحد.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="rounded-lg border border-line bg-surface px-4 py-3 text-sm text-ink-soft">
                    <span class="font-medium text-ink">{{ $services->total() }}</span>
                    خدمة ضمن النتائج الحالية
                </div>
                @can('services.create')
                    <a href="{{ route('admin.services.create') }}"
                        class="inline-flex h-11 items-center justify-center gap-1.5 rounded-lg bg-ink px-4 text-sm font-medium text-brass-soft transition hover:bg-brass hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        إضافة خدمة
                    </a>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">إجمالي الخدمات</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-ink">{{ number_format($stats['total']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-ink"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">الخدمات المنشورة</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-forest">{{ number_format($stats['published']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-forest"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">المسودات</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-ink">{{ number_format($stats['drafts']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-line"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">خدمات مميزة</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-brass">{{ number_format($stats['featured']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-brass"></span>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.services.index') }}"
            class="mt-6 rounded-lg border border-line bg-surface p-5">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-5">
                    <label for="services-search" class="mb-2 block text-xs font-medium text-ink-soft">بحث</label>
                    <div class="relative">
                        <input id="services-search" name="search" value="{{ request('search') }}" type="search"
                            placeholder="اسم الخدمة أو الرابط"
                            class="h-11 w-full rounded-lg border-line bg-paper pr-10 text-sm text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-soft"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <label for="services-category" class="mb-2 block text-xs font-medium text-ink-soft">التصنيف</label>
                    <select id="services-category" name="category"
                        class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
                        <option value="">كل التصنيفات</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>
                                {{ $category->name }} ({{ $category->services_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <label for="services-status" class="mb-2 block text-xs font-medium text-ink-soft">الحالة</label>
                    <select id="services-status" name="status"
                        class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
                        <option value="">كل الحالات</option>
                        <option value="published" @selected(request('status') === 'published')>منشورة</option>
                        <option value="draft" @selected(request('status') === 'draft')>مسودة</option>
                    </select>
                </div>

                <div class="flex gap-2 lg:col-span-2">
                    <button type="submit"
                        class="inline-flex h-11 flex-1 items-center justify-center rounded-lg bg-ink px-4 text-sm font-medium text-brass-soft transition hover:bg-brass hover:text-white">
                        تصفية
                    </button>
                    @if (request()->hasAny(['search', 'category', 'status']))
                        <a href="{{ route('admin.services.index') }}"
                            class="inline-flex h-11 items-center justify-center rounded-lg border border-line px-4 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                            مسح
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <section class="mt-6 overflow-hidden rounded-lg border border-line bg-surface">
            <div class="flex flex-col gap-3 border-b border-line px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-display text-lg font-semibold text-ink">قائمة الخدمات</h3>
                    <p class="mt-1 text-sm text-ink-soft">عرض مرتب ومقروء للخدمات الحالية ومؤشرات المحتوى المرتبطة بها.</p>
                </div>
                <span class="text-sm text-ink-soft">
                    {{ $services->firstItem() ?? 0 }} - {{ $services->lastItem() ?? 0 }} من {{ $services->total() }}
                </span>
            </div>

            <div class="divide-y divide-line">
                @forelse ($services as $service)
                    <article class="p-5 transition hover:bg-paper/45">
                        <div class="grid grid-cols-1 gap-5 xl:grid-cols-12 xl:items-center">
                            <div class="min-w-0 xl:col-span-4">
                                <div class="flex min-w-0 gap-4">
                                    @if ($service->featured_image)
                                        <img src="{{ asset('storage/' . ltrim($service->featured_image, '/')) }}"
                                            alt="" class="h-14 w-14 shrink-0 rounded-lg object-cover">
                                    @else
                                        <div
                                            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg bg-brass-soft font-display text-xl font-bold text-brass">
                                            {{ mb_substr($service->title, 0, 1) }}
                                        </div>
                                    @endif

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            @can('services.edit')
                                                <a href="{{ route('admin.services.edit', $service) }}"
                                                    class="font-semibold text-ink transition hover:text-brass">
                                                    {{ $service->title }}
                                                </a>
                                            @else
                                                <h4 class="font-semibold text-ink">{{ $service->title }}</h4>
                                            @endcan
                                            @if ($service->is_featured)
                                                <span
                                                    class="inline-flex rounded-full border border-brass/15 bg-brass-soft px-2.5 py-1 text-xs font-medium text-brass">
                                                    مميزة
                                                </span>
                                            @endif
                                        </div>
                                        <p class="mt-1 truncate text-xs text-ink-soft">{{ $service->slug }}</p>
                                        @if ($service->short_description)
                                            <p class="mt-3 line-clamp-2 max-w-2xl text-sm leading-6 text-ink-soft">
                                                {{ $service->short_description }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-sm xl:col-span-3">
                                <div>
                                    <p class="text-xs font-medium text-ink-soft">التصنيف</p>
                                    <p class="mt-1 text-ink">{{ $service->category?->name ?? 'بدون تصنيف' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-ink-soft">الحالة</p>
                                    <span
                                        class="mt-1 inline-flex rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusClasses[$service->status] ?? 'border-line bg-paper text-ink-soft' }}">
                                        {{ $statusLabels[$service->status] ?? $service->status }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-ink-soft">الترتيب</p>
                                    <p class="mt-1 font-semibold text-ink">{{ $service->order }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-ink-soft">آخر تحديث</p>
                                    <p class="mt-1 text-ink">{{ $service->updated_at?->format('Y-m-d') }}</p>
                                </div>
                            </div>

                            <div class="xl:col-span-2">
                                <p class="text-xs font-medium text-ink-soft">المحتوى المرتبط</p>
                                <div class="mt-3 flex flex-wrap gap-x-4 gap-y-2 text-sm text-ink">
                                    <span>{{ $service->images_count }} صور</span>
                                    <span>{{ $service->faqs_count }} أسئلة</span>
                                    <span>{{ $service->projects_count }} مشاريع</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 xl:col-span-3 xl:justify-end">
                                @can('services.edit')
                                    <a href="{{ route('admin.services.edit', $service) }}"
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
                                @can('services.delete')
                                    <x-admin.confirm-form
                                        :action="route('admin.services.destroy', $service)"
                                        title="حذف الخدمة"
                                        :message="'سيتم حذف «'.$service->title.'» من الموقع. هل تريد المتابعة؟'"
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
                        </div>
                    </article>
                @empty
                    <div class="px-5 py-14 text-center">
                        <p class="font-medium text-ink">لا توجد خدمات مطابقة</p>
                        <p class="mt-2 text-sm text-ink-soft">جرّب تعديل البحث أو الفلاتر الحالية.</p>
                    </div>
                @endforelse
            </div>
        </section>

        @if ($services->hasPages())
            <div class="mt-6 flex flex-col gap-3 border-t border-line pt-5 text-sm text-ink-soft sm:flex-row sm:items-center sm:justify-between">
                <p>
                    عرض {{ $services->firstItem() }} - {{ $services->lastItem() }} من {{ $services->total() }}
                </p>
                <div class="flex items-center gap-2">
                    @if ($services->onFirstPage())
                        <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">السابق</span>
                    @else
                        <a href="{{ $services->previousPageUrl() }}"
                            class="rounded-lg border border-line px-4 py-2 transition hover:bg-surface hover:text-ink">
                            السابق
                        </a>
                    @endif

                    @if ($services->hasMorePages())
                        <a href="{{ $services->nextPageUrl() }}"
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
