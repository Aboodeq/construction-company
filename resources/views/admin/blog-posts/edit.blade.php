@php
    $statusLabels = ['published' => 'منشورة', 'draft' => 'مسودة'];
    $statusClasses = [
        'published' => 'border-forest/15 bg-forest/5 text-forest',
        'draft' => 'border-line bg-paper text-ink-soft',
    ];
@endphp

<x-admin.layouts.app title="تعديل مقالة">
    <div class="mx-auto w-full max-w-6xl">

        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <a href="{{ route('admin.blog.index') }}"
                    class="inline-flex items-center gap-1.5 text-sm text-ink-soft transition hover:text-brass">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                            clip-rule="evenodd" />
                    </svg>
                    العودة إلى المدونة
                </a>
                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <h2 class="font-display text-2xl font-semibold text-ink">{{ $post->title }}</h2>
                    <span
                        class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusClasses[$post->status] ?? '' }}">
                        {{ $statusLabels[$post->status] ?? $post->status }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:items-start">

            {{-- Main column --}}
            <div class="space-y-6 lg:col-span-2">
                <form id="blog-post-edit-form" method="POST" action="{{ route('admin.blog.update', $post) }}"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">محتوى المقالة</h3>
                        <div class="mt-5">
                            @include('admin.blog-posts.partials.fields', ['post' => $post])
                        </div>
                    </section>

                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">صورة الغلاف</h3>
                        <p class="mt-1 text-sm text-ink-soft">تظهر هذه الصورة في قائمة المدونة وأعلى المقالة.</p>

                        <div class="mt-5 flex flex-wrap items-center gap-5">
                            @if ($post->featured_image)
                                <img src="{{ asset('storage/'.$post->featured_image) }}" alt=""
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

                                @if ($post->featured_image)
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
                        <h3 class="font-display text-lg font-semibold text-ink">تحسين محركات البحث (SEO)</h3>
                        <p class="mt-1 text-sm text-ink-soft">
                            اختياري — إن تُركت فارغة سيُستخدم عنوان المقالة ومقتطفها تلقائيًا.
                        </p>

                        <div class="mt-5 space-y-5">
                            <div>
                                <x-input-label for="meta_title" value="عنوان الميتا" />
                                <x-text-input id="meta_title" name="meta_title" type="text"
                                    :value="old('meta_title', $post->seoMeta?->meta_title)" />
                                <x-input-error :messages="$errors->get('meta_title')" />
                            </div>
                            <div>
                                <x-input-label for="meta_description" value="وصف الميتا" />
                                <textarea id="meta_description" name="meta_description" rows="2"
                                    class="w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">{{ old('meta_description', $post->seoMeta?->meta_description) }}</textarea>
                                <x-input-error :messages="$errors->get('meta_description')" />
                            </div>
                        </div>
                    </section>
                </form>
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6 lg:sticky lg:top-24">
                <div class="rounded-lg border border-line bg-surface p-6">
                    <h3 class="font-display text-base font-semibold text-ink">معلومات المقالة</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">الرابط المختصر</dt>
                            <dd class="font-medium text-ink">{{ $post->slug }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">المشاهدات</dt>
                            <dd class="font-medium text-ink">{{ number_format($post->views_count) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">تاريخ النشر</dt>
                            <dd class="font-medium text-ink">{{ $post->published_at?->format('Y-m-d') ?? 'غير محدد' }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-soft">آخر تحديث</dt>
                            <dd class="font-medium text-ink">{{ $post->updated_at?->diffForHumans() }}</dd>
                        </div>
                    </dl>

                    <button type="submit" form="blog-post-edit-form"
                        class="mt-6 inline-flex h-11 w-full items-center justify-center rounded-lg bg-ink text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                        حفظ التغييرات
                    </button>

                    <form method="POST" action="{{ route('admin.blog.toggle-published', $post) }}" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex h-10 w-full items-center justify-center rounded-lg border border-line text-xs font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                            {{ $post->status === 'published' ? 'تحويل إلى مسودة' : 'نشر المقالة الآن' }}
                        </button>
                    </form>
                </div>

                @can('blog.delete')
                    <div class="rounded-lg border border-red-100 bg-red-50/40 p-6">
                        <h3 class="font-display text-base font-semibold text-ink">منطقة الخطر</h3>
                        <p class="mt-1 text-sm text-ink-soft">حذف المقالة يخفيها من الموقع ويمكن استعادتها لاحقًا من قاعدة البيانات.</p>
                        <x-admin.confirm-form
                            :action="route('admin.blog.destroy', $post)"
                            title="حذف المقالة"
                            :message="'سيتم حذف «'.$post->title.'» من الموقع. هل تريد المتابعة؟'"
                            class="mt-4 h-11 w-full px-5 text-sm font-semibold">
                            حذف المقالة
                        </x-admin.confirm-form>
                    </div>
                @endcan
            </aside>
        </div>
    </div>
</x-admin.layouts.app>
