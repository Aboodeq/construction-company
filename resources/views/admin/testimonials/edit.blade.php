@php
    $statusLabels = ['published' => 'منشور', 'pending' => 'قيد المراجعة'];
    $statusClasses = [
        'published' => 'border-forest/15 bg-forest/5 text-forest',
        'pending' => 'border-line bg-paper text-ink-soft',
    ];
@endphp

<x-admin.layouts.app title="تعديل رأي عميل">
    <div class="mx-auto w-full max-w-5xl">

        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <a href="{{ route('admin.testimonials.index') }}"
                    class="inline-flex items-center gap-1.5 text-sm text-ink-soft transition hover:text-brass">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                            clip-rule="evenodd" />
                    </svg>
                    العودة إلى آراء العملاء
                </a>
                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <h2 class="font-display text-2xl font-semibold text-ink">{{ $testimonial->client_name }}</h2>
                    <span
                        class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusClasses[$testimonial->status] ?? '' }}">
                        {{ $statusLabels[$testimonial->status] ?? $testimonial->status }}
                    </span>
                    @if ($testimonial->is_featured)
                        <span class="inline-flex rounded-full border border-brass/15 bg-brass-soft px-2.5 py-1 text-xs font-medium text-brass">
                            مميز
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:items-start">

            {{-- Main column --}}
            <div class="space-y-6 lg:col-span-2">
                <form id="testimonial-edit-form" method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">بيانات الرأي</h3>
                        <div class="mt-5">
                            @include('admin.testimonials.partials.fields', ['testimonial' => $testimonial])
                        </div>
                    </section>

                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">صورة العميل</h3>
                        <p class="mt-1 text-sm text-ink-soft">صورة اختيارية تظهر بجانب اسم العميل ورأيه.</p>

                        <div class="mt-5 flex flex-wrap items-center gap-5">
                            @if ($testimonial->client_image)
                                <img src="{{ asset('storage/'.$testimonial->client_image) }}" alt=""
                                    class="h-20 w-20 shrink-0 rounded-full border border-line object-cover">
                            @else
                                <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full border border-dashed border-line bg-paper font-display text-xl font-bold text-ink-soft">
                                    {{ mb_substr($testimonial->client_name, 0, 1) }}
                                </div>
                            @endif

                            <div class="min-w-0 flex-1 space-y-3">
                                <input type="file" name="client_image" accept="image/*"
                                    class="block w-full text-sm text-ink-soft file:me-3 file:rounded-lg file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-medium file:text-brass-soft hover:file:bg-brass">
                                <x-input-error :messages="$errors->get('client_image')" />

                                @if ($testimonial->client_image)
                                    <label class="inline-flex items-center gap-2 text-sm text-ink-soft">
                                        <input type="checkbox" name="remove_client_image" value="1"
                                            class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
                                        إزالة الصورة الحالية
                                    </label>
                                @endif
                            </div>
                        </div>
                    </section>
                </form>
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6 lg:sticky lg:top-24">
                <div class="rounded-lg border border-line bg-surface p-6">
                    <h3 class="font-display text-base font-semibold text-ink">معلومات</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">تاريخ الإضافة</dt>
                            <dd class="font-medium text-ink">{{ $testimonial->created_at?->format('Y-m-d') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">آخر تحديث</dt>
                            <dd class="font-medium text-ink">{{ $testimonial->updated_at?->diffForHumans() }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">المشروع المرتبط</dt>
                            <dd class="font-medium text-ink">{{ $testimonial->project?->title ?? 'بدون ربط' }}</dd>
                        </div>
                    </dl>

                    <button type="submit" form="testimonial-edit-form"
                        class="mt-6 inline-flex h-11 w-full items-center justify-center rounded-lg bg-ink text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                        حفظ التغييرات
                    </button>

                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <form method="POST" action="{{ route('admin.testimonials.toggle-published', $testimonial) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex h-10 w-full items-center justify-center rounded-lg border border-line text-xs font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                                {{ $testimonial->status === 'published' ? 'إخفاء مؤقتًا' : 'نشر الآن' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.testimonials.toggle-featured', $testimonial) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex h-10 w-full items-center justify-center rounded-lg border border-line text-xs font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                                {{ $testimonial->is_featured ? 'إلغاء التمييز' : 'تمييز' }}
                            </button>
                        </form>
                    </div>
                </div>

                @can('testimonials.delete')
                    <div class="rounded-lg border border-red-100 bg-red-50/40 p-6">
                        <h3 class="font-display text-base font-semibold text-ink">منطقة الخطر</h3>
                        <p class="mt-1 text-sm text-ink-soft">حذف رأي العميل نهائي ولا يمكن التراجع عنه.</p>
                        <x-admin.confirm-form
                            :action="route('admin.testimonials.destroy', $testimonial)"
                            title="حذف رأي العميل"
                            :message="'سيتم حذف رأي «'.$testimonial->client_name.'» نهائيًا. هل تريد المتابعة؟'"
                            class="mt-4 h-11 w-full px-5 text-sm font-semibold">
                            حذف الرأي
                        </x-admin.confirm-form>
                    </div>
                @endcan
            </aside>
        </div>
    </div>
</x-admin.layouts.app>
