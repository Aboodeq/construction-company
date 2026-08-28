@php
    $statusLabels = ['published' => 'منشورة', 'draft' => 'مسودة'];
    $statusClasses = [
        'published' => 'border-forest/15 bg-forest/5 text-forest',
        'draft' => 'border-line bg-paper text-ink-soft',
    ];
@endphp

<x-admin.layouts.app title="تعديل خدمة">
    <div class="mx-auto w-full max-w-6xl">

        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <a href="{{ route('admin.services.index') }}"
                    class="inline-flex items-center gap-1.5 text-sm text-ink-soft transition hover:text-brass">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                            clip-rule="evenodd" />
                    </svg>
                    العودة إلى الخدمات
                </a>
                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <h2 class="font-display text-2xl font-semibold text-ink">{{ $service->title }}</h2>
                    <span
                        class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusClasses[$service->status] ?? '' }}">
                        {{ $statusLabels[$service->status] ?? $service->status }}
                    </span>
                    @if ($service->is_featured)
                        <span class="inline-flex rounded-full border border-brass/15 bg-brass-soft px-2.5 py-1 text-xs font-medium text-brass">
                            مميزة
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:items-start">

            {{-- Main column --}}
            <div class="space-y-6 lg:col-span-2">
                <form id="service-edit-form" method="POST" action="{{ route('admin.services.update', $service) }}"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">البيانات الأساسية</h3>
                        <div class="mt-5">
                            @include('admin.services.partials.fields', ['service' => $service])
                        </div>
                    </section>

                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">الصورة الرئيسية</h3>
                        <p class="mt-1 text-sm text-ink-soft">تظهر هذه الصورة كغلاف للخدمة في قوائم العرض.</p>

                        <div class="mt-5 flex flex-wrap items-center gap-5">
                            @if ($service->featured_image)
                                <img src="{{ asset('storage/'.$service->featured_image) }}" alt=""
                                    class="h-28 w-28 shrink-0 rounded-lg border border-line object-cover">
                            @else
                                <div class="flex h-28 w-28 shrink-0 items-center justify-center rounded-lg border border-dashed border-line bg-paper text-xs text-ink-soft">
                                    لا توجد صورة
                                </div>
                            @endif

                            <div class="min-w-0 flex-1 space-y-3">
                                <input type="file" name="featured_image" accept="image/*"
                                    class="block w-full text-sm text-ink-soft file:me-3 file:rounded-lg file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-medium file:text-brass-soft hover:file:bg-brass">
                                <x-input-error :messages="$errors->get('featured_image')" />

                                @if ($service->featured_image)
                                    <label class="inline-flex items-center gap-2 text-sm text-ink-soft">
                                        <input type="checkbox" name="remove_featured_image" value="1"
                                            class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
                                        إزالة الصورة الحالية
                                    </label>
                                @endif
                            </div>
                        </div>
                    </section>

                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">معرض الصور</h3>
                        <p class="mt-1 text-sm text-ink-soft">صور إضافية تظهر في معرض تفاصيل الخدمة.</p>

                        @if ($service->images->isNotEmpty())
                            <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                                @foreach ($service->images as $image)
                                    <div class="group relative overflow-hidden rounded-lg border border-line">
                                        <img src="{{ asset('storage/'.$image->image_path) }}" alt="{{ $image->alt_text }}"
                                            class="h-28 w-full object-cover">
                                        <button type="submit" form="remove-image-{{ $image->id }}"
                                            class="absolute left-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-ink/70 text-white opacity-0 transition hover:bg-red-600 group-hover:opacity-100"
                                            title="حذف الصورة">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                    <form id="remove-image-{{ $image->id }}" method="POST"
                                        action="{{ route('admin.services.images.destroy', [$service, $image]) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-5">
                            <input type="file" name="new_images[]" accept="image/*" multiple
                                class="block w-full text-sm text-ink-soft file:me-3 file:rounded-lg file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-medium file:text-brass-soft hover:file:bg-brass">
                            <x-input-error :messages="$errors->get('new_images')" />
                        </div>
                    </section>

                    <section class="rounded-lg border border-line bg-surface p-6" x-data="{ faqCounter: 0 }">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-display text-lg font-semibold text-ink">الأسئلة الشائعة</h3>
                                <p class="mt-1 text-sm text-ink-soft">أسئلة يتكرر طرحها من العملاء حول هذه الخدمة.</p>
                            </div>
                            <button type="button"
                                @click="
                                    faqCounter++;
                                    const clone = $refs.faqTemplate.content.cloneNode(true);
                                    clone.querySelectorAll('[name]').forEach(el => el.name = el.name.replace('__KEY__', 'new' + faqCounter));
                                    $refs.faqRows.appendChild(clone);
                                "
                                class="inline-flex h-9 items-center justify-center rounded-lg border border-line px-3 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                                + إضافة سؤال
                            </button>
                        </div>

                        <div class="mt-5 space-y-3" x-ref="faqRows">
                            @foreach ($service->faqs as $faq)
                                <div data-faq-row class="rounded-lg border border-line bg-paper p-4">
                                    <input type="hidden" name="faqs[{{ $faq->id }}][id]" value="{{ $faq->id }}">
                                    <div class="flex items-start gap-3">
                                        <div class="min-w-0 flex-1 space-y-2">
                                            <input type="text" name="faqs[{{ $faq->id }}][question]" value="{{ $faq->question }}"
                                                placeholder="السؤال"
                                                class="h-10 w-full rounded-lg border-line bg-surface text-sm text-ink focus:border-brass focus:ring-brass">
                                            <textarea name="faqs[{{ $faq->id }}][answer]" rows="2" placeholder="الإجابة"
                                                class="w-full rounded-lg border-line bg-surface text-sm text-ink focus:border-brass focus:ring-brass">{{ $faq->answer }}</textarea>
                                        </div>
                                        <button type="button" @click="$el.closest('[data-faq-row]').remove()"
                                            class="shrink-0 rounded-lg p-2 text-ink-soft transition hover:bg-red-50 hover:text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($service->faqs->isEmpty())
                            <p class="mt-3 text-sm text-ink-soft" x-show="$refs.faqRows.children.length === 0">
                                لا توجد أسئلة بعد. اضغط "إضافة سؤال" للبدء.
                            </p>
                        @endif

                        <template x-ref="faqTemplate">
                            <div data-faq-row class="mt-3 rounded-lg border border-line bg-paper p-4">
                                <div class="flex items-start gap-3">
                                    <div class="min-w-0 flex-1 space-y-2">
                                        <input type="text" name="faqs[__KEY__][question]" placeholder="السؤال"
                                            class="h-10 w-full rounded-lg border-line bg-surface text-sm text-ink focus:border-brass focus:ring-brass">
                                        <textarea name="faqs[__KEY__][answer]" rows="2" placeholder="الإجابة"
                                            class="w-full rounded-lg border-line bg-surface text-sm text-ink focus:border-brass focus:ring-brass"></textarea>
                                    </div>
                                    <button type="button" @click="$el.closest('[data-faq-row]').remove()"
                                        class="shrink-0 rounded-lg p-2 text-ink-soft transition hover:bg-red-50 hover:text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </section>

                    <section class="rounded-lg border border-line bg-surface p-6" x-data="{ stepCounter: 0 }">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-display text-lg font-semibold text-ink">مراحل العمل</h3>
                                <p class="mt-1 text-sm text-ink-soft">الخطوات التي يمر بها تنفيذ هذه الخدمة، بالترتيب.</p>
                            </div>
                            <button type="button"
                                @click="
                                    stepCounter++;
                                    const clone = $refs.stepTemplate.content.cloneNode(true);
                                    clone.querySelectorAll('[name]').forEach(el => el.name = el.name.replace('__KEY__', 'new' + stepCounter));
                                    $refs.stepRows.appendChild(clone);
                                "
                                class="inline-flex h-9 items-center justify-center rounded-lg border border-line px-3 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                                + إضافة مرحلة
                            </button>
                        </div>

                        <div class="mt-5 space-y-3" x-ref="stepRows">
                            @foreach ($service->process_steps ?? [] as $index => $step)
                                <div data-step-row class="flex items-start gap-3 rounded-lg border border-line bg-paper p-4">
                                    <span class="mt-2 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brass-soft text-xs font-semibold text-brass">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="min-w-0 flex-1 space-y-2">
                                        <input type="text" name="process_steps[{{ $index }}][title]" value="{{ $step['title'] ?? '' }}"
                                            placeholder="عنوان المرحلة"
                                            class="h-10 w-full rounded-lg border-line bg-surface text-sm text-ink focus:border-brass focus:ring-brass">
                                        <textarea name="process_steps[{{ $index }}][description]" rows="2" placeholder="وصف مختصر"
                                            class="w-full rounded-lg border-line bg-surface text-sm text-ink focus:border-brass focus:ring-brass">{{ $step['description'] ?? '' }}</textarea>
                                    </div>
                                    <button type="button" @click="$el.closest('[data-step-row]').remove()"
                                        class="shrink-0 rounded-lg p-2 text-ink-soft transition hover:bg-red-50 hover:text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <template x-ref="stepTemplate">
                            <div data-step-row class="mt-3 flex items-start gap-3 rounded-lg border border-line bg-paper p-4">
                                <span class="mt-2 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brass-soft text-xs font-semibold text-brass">•</span>
                                <div class="min-w-0 flex-1 space-y-2">
                                    <input type="text" name="process_steps[__KEY__][title]" placeholder="عنوان المرحلة"
                                        class="h-10 w-full rounded-lg border-line bg-surface text-sm text-ink focus:border-brass focus:ring-brass">
                                    <textarea name="process_steps[__KEY__][description]" rows="2" placeholder="وصف مختصر"
                                        class="w-full rounded-lg border-line bg-surface text-sm text-ink focus:border-brass focus:ring-brass"></textarea>
                                </div>
                                <button type="button" @click="$el.closest('[data-step-row]').remove()"
                                    class="shrink-0 rounded-lg p-2 text-ink-soft transition hover:bg-red-50 hover:text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </section>

                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">تحسين محركات البحث (SEO)</h3>
                        <p class="mt-1 text-sm text-ink-soft">
                            اختياري — إن تُركت فارغة سيُستخدم عنوان الخدمة ووصفها المختصر تلقائيًا.
                        </p>

                        <div class="mt-5 space-y-5">
                            <div>
                                <x-input-label for="meta_title" value="عنوان الميتا" />
                                <x-text-input id="meta_title" name="meta_title" type="text"
                                    :value="old('meta_title', $service->seoMeta?->meta_title)" />
                                <x-input-error :messages="$errors->get('meta_title')" />
                            </div>
                            <div>
                                <x-input-label for="meta_description" value="وصف الميتا" />
                                <textarea id="meta_description" name="meta_description" rows="2"
                                    class="w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">{{ old('meta_description', $service->seoMeta?->meta_description) }}</textarea>
                                <x-input-error :messages="$errors->get('meta_description')" />
                            </div>
                        </div>
                    </section>
                </form>
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6 lg:sticky lg:top-24">
                <div class="rounded-lg border border-line bg-surface p-6">
                    <h3 class="font-display text-base font-semibold text-ink">معلومات الخدمة</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">الرابط المختصر</dt>
                            <dd class="font-medium text-ink">{{ $service->slug }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">تاريخ الإنشاء</dt>
                            <dd class="font-medium text-ink">{{ $service->created_at?->format('Y-m-d') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">آخر تحديث</dt>
                            <dd class="font-medium text-ink">{{ $service->updated_at?->diffForHumans() }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">مشاريع مرتبطة</dt>
                            <dd class="font-medium text-ink">{{ $service->projects()->count() }}</dd>
                        </div>
                    </dl>

                    <button type="submit" form="service-edit-form"
                        class="mt-6 inline-flex h-11 w-full items-center justify-center rounded-lg bg-ink text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                        حفظ التغييرات
                    </button>

                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <form method="POST" action="{{ route('admin.services.toggle-published', $service) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex h-10 w-full items-center justify-center rounded-lg border border-line text-xs font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                                {{ $service->status === 'published' ? 'تحويل إلى مسودة' : 'نشر الخدمة' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.services.toggle-featured', $service) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex h-10 w-full items-center justify-center rounded-lg border border-line text-xs font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                                {{ $service->is_featured ? 'إلغاء التمييز' : 'تمييز الخدمة' }}
                            </button>
                        </form>
                    </div>
                </div>

                @can('services.delete')
                    <div class="rounded-lg border border-red-100 bg-red-50/40 p-6">
                        <h3 class="font-display text-base font-semibold text-ink">منطقة الخطر</h3>
                        <p class="mt-1 text-sm text-ink-soft">حذف الخدمة يخفيها من الموقع ويمكن استعادتها لاحقًا من قاعدة البيانات.</p>
                        <x-admin.confirm-form
                            :action="route('admin.services.destroy', $service)"
                            title="حذف الخدمة"
                            :message="'سيتم حذف «'.$service->title.'» من الموقع. هل تريد المتابعة؟'"
                            class="mt-4 h-11 w-full px-5 text-sm font-semibold">
                            حذف الخدمة
                        </x-admin.confirm-form>
                    </div>
                @endcan
            </aside>
        </div>
    </div>
</x-admin.layouts.app>
