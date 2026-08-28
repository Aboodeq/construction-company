@php
    $statusLabels = ['published' => 'منشور', 'draft' => 'مسودة'];
    $statusClasses = [
        'published' => 'border-forest/15 bg-forest/5 text-forest',
        'draft' => 'border-line bg-paper text-ink-soft',
    ];
    $imageSections = [
        'gallery_images' => ['label' => 'صور المعرض', 'type' => 'gallery', 'hint' => 'صور عامة لتفاصيل المشروع.'],
        'before_images' => ['label' => 'صور قبل التنفيذ', 'type' => 'before', 'hint' => 'صور الموقع قبل بدء العمل.'],
        'after_images' => ['label' => 'صور بعد التنفيذ', 'type' => 'after', 'hint' => 'صور الموقع بعد الانتهاء من العمل.'],
    ];
@endphp

<x-admin.layouts.app title="تعديل مشروع">
    <div class="mx-auto w-full max-w-6xl">

        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <a href="{{ route('admin.projects.index') }}"
                    class="inline-flex items-center gap-1.5 text-sm text-ink-soft transition hover:text-brass">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                            clip-rule="evenodd" />
                    </svg>
                    العودة إلى المشاريع
                </a>
                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <h2 class="font-display text-2xl font-semibold text-ink">{{ $project->title }}</h2>
                    <span
                        class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusClasses[$project->status] ?? '' }}">
                        {{ $statusLabels[$project->status] ?? $project->status }}
                    </span>
                    @if ($project->is_featured)
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
                <form id="project-edit-form" method="POST" action="{{ route('admin.projects.update', $project) }}"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">البيانات الأساسية</h3>
                        <div class="mt-5">
                            @include('admin.projects.partials.fields', ['project' => $project])
                        </div>
                    </section>

                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">صورة الغلاف</h3>
                        <p class="mt-1 text-sm text-ink-soft">تظهر هذه الصورة كغلاف للمشروع في معرض الأعمال.</p>

                        <div class="mt-5 flex flex-wrap items-center gap-5">
                            @if ($project->cover_image)
                                <img src="{{ asset('storage/'.$project->cover_image) }}" alt=""
                                    class="h-28 w-28 shrink-0 rounded-lg border border-line object-cover">
                            @else
                                <div class="flex h-28 w-28 shrink-0 items-center justify-center rounded-lg border border-dashed border-line bg-paper text-xs text-ink-soft">
                                    لا توجد صورة
                                </div>
                            @endif

                            <div class="min-w-0 flex-1 space-y-3">
                                <input type="file" name="cover_image" accept="image/*"
                                    class="block w-full text-sm text-ink-soft file:me-3 file:rounded-lg file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-medium file:text-brass-soft hover:file:bg-brass">
                                <x-input-error :messages="$errors->get('cover_image')" />

                                @if ($project->cover_image)
                                    <label class="inline-flex items-center gap-2 text-sm text-ink-soft">
                                        <input type="checkbox" name="remove_cover_image" value="1"
                                            class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
                                        إزالة صورة الغلاف الحالية
                                    </label>
                                @endif
                            </div>
                        </div>
                    </section>

                    @foreach ($imageSections as $field => $section)
                        @php $sectionImages = $project->images->where('type', $section['type']); @endphp
                        <section class="rounded-lg border border-line bg-surface p-6">
                            <h3 class="font-display text-lg font-semibold text-ink">{{ $section['label'] }}</h3>
                            <p class="mt-1 text-sm text-ink-soft">{{ $section['hint'] }}</p>

                            @if ($sectionImages->isNotEmpty())
                                <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                                    @foreach ($sectionImages as $image)
                                        <div class="group relative overflow-hidden rounded-lg border border-line">
                                            <img src="{{ asset('storage/'.$image->image_path) }}" alt=""
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
                                            action="{{ route('admin.projects.images.destroy', [$project, $image]) }}" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-5">
                                <input type="file" name="{{ $field }}[]" accept="image/*" multiple
                                    class="block w-full text-sm text-ink-soft file:me-3 file:rounded-lg file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-medium file:text-brass-soft hover:file:bg-brass">
                                <x-input-error :messages="$errors->get($field)" />
                            </div>
                        </section>
                    @endforeach

                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">الخدمات المستخدمة في المشروع</h3>
                        <p class="mt-1 text-sm text-ink-soft">تُستخدم هذه الروابط لعرض المشروع ضمن أمثلة كل خدمة.</p>

                        @if ($allServices->isEmpty())
                            <p class="mt-4 text-sm text-ink-soft">لا توجد خدمات مضافة بعد.</p>
                        @else
                            <div class="mt-5 grid max-h-64 grid-cols-1 gap-2 overflow-y-auto rounded-lg border border-line bg-paper p-3 sm:grid-cols-2">
                                @foreach ($allServices as $service)
                                    <label class="flex items-center gap-2.5 rounded-lg px-2 py-1.5 text-sm text-ink transition hover:bg-surface">
                                        <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                            @checked($project->services->contains('id', $service->id))
                                            class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
                                        {{ $service->title }}
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">تحسين محركات البحث (SEO)</h3>
                        <p class="mt-1 text-sm text-ink-soft">
                            اختياري — إن تُركت فارغة سيُستخدم اسم المشروع ووصفه تلقائيًا.
                        </p>

                        <div class="mt-5 space-y-5">
                            <div>
                                <x-input-label for="meta_title" value="عنوان الميتا" />
                                <x-text-input id="meta_title" name="meta_title" type="text"
                                    :value="old('meta_title', $project->seoMeta?->meta_title)" />
                                <x-input-error :messages="$errors->get('meta_title')" />
                            </div>
                            <div>
                                <x-input-label for="meta_description" value="وصف الميتا" />
                                <textarea id="meta_description" name="meta_description" rows="2"
                                    class="w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">{{ old('meta_description', $project->seoMeta?->meta_description) }}</textarea>
                                <x-input-error :messages="$errors->get('meta_description')" />
                            </div>
                        </div>
                    </section>
                </form>
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6 lg:sticky lg:top-24">
                <div class="rounded-lg border border-line bg-surface p-6">
                    <h3 class="font-display text-base font-semibold text-ink">معلومات المشروع</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">الرابط المختصر</dt>
                            <dd class="font-medium text-ink">{{ $project->slug }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">تاريخ الإنشاء</dt>
                            <dd class="font-medium text-ink">{{ $project->created_at?->format('Y-m-d') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">آخر تحديث</dt>
                            <dd class="font-medium text-ink">{{ $project->updated_at?->diffForHumans() }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">آراء العملاء المرتبطة</dt>
                            <dd class="font-medium text-ink">{{ $project->testimonials()->count() }}</dd>
                        </div>
                    </dl>

                    <button type="submit" form="project-edit-form"
                        class="mt-6 inline-flex h-11 w-full items-center justify-center rounded-lg bg-ink text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                        حفظ التغييرات
                    </button>

                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <form method="POST" action="{{ route('admin.projects.toggle-published', $project) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex h-10 w-full items-center justify-center rounded-lg border border-line text-xs font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                                {{ $project->status === 'published' ? 'تحويل إلى مسودة' : 'نشر المشروع' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.projects.toggle-featured', $project) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex h-10 w-full items-center justify-center rounded-lg border border-line text-xs font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                                {{ $project->is_featured ? 'إلغاء التمييز' : 'تمييز المشروع' }}
                            </button>
                        </form>
                    </div>
                </div>

                @can('projects.delete')
                    <div class="rounded-lg border border-red-100 bg-red-50/40 p-6">
                        <h3 class="font-display text-base font-semibold text-ink">منطقة الخطر</h3>
                        <p class="mt-1 text-sm text-ink-soft">حذف المشروع يخفيه من الموقع ويمكن استعادته لاحقًا من قاعدة البيانات.</p>
                        <x-admin.confirm-form
                            :action="route('admin.projects.destroy', $project)"
                            title="حذف المشروع"
                            :message="'سيتم حذف «'.$project->title.'» من الموقع. هل تريد المتابعة؟'"
                            class="mt-4 h-11 w-full px-5 text-sm font-semibold">
                            حذف المشروع
                        </x-admin.confirm-form>
                    </div>
                @endcan
            </aside>
        </div>
    </div>
</x-admin.layouts.app>
