<x-admin.layouts.app title="تعديل قسم">
    <div class="mx-auto w-full max-w-3xl">
        <div class="mb-8">
            <a href="{{ route('admin.page-sections.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-ink-soft transition hover:text-brass">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                        clip-rule="evenodd" />
                </svg>
                العودة إلى أقسام الصفحات
            </a>
            <h2 class="mt-4 font-display text-2xl font-semibold text-ink">{{ $pageSection->title ?? $pageSection->key }}</h2>
            <p class="mt-1 font-mono text-xs text-ink-soft">{{ $pageSection->key }}</p>
        </div>

        <form method="POST" action="{{ route('admin.page-sections.update', $pageSection) }}"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <section class="rounded-lg border border-line bg-surface p-6">
                <h3 class="font-display text-lg font-semibold text-ink">المحتوى</h3>

                <div class="mt-5 grid grid-cols-1 gap-5">
                    <div>
                        <x-input-label for="title" value="العنوان" />
                        <x-text-input id="title" name="title" type="text" autofocus
                            :value="old('title', $pageSection->title)" />
                        <x-input-error :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="subtitle" value="العنوان الفرعي" />
                        <x-text-input id="subtitle" name="subtitle" type="text"
                            :value="old('subtitle', $pageSection->subtitle)" />
                        <x-input-error :messages="$errors->get('subtitle')" />
                    </div>

                    <div>
                        <x-input-label for="content" value="النص" />
                        <textarea id="content" name="content" rows="5"
                            class="w-full rounded-lg border-line bg-paper text-sm leading-7 text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">{{ old('content', $pageSection->content) }}</textarea>
                        <x-input-error :messages="$errors->get('content')" />
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-line bg-surface p-6">
                <h3 class="font-display text-lg font-semibold text-ink">صورة القسم</h3>
                <p class="mt-1 text-sm text-ink-soft">صورة اختيارية تظهر مع هذا القسم بالموقع.</p>

                <div class="mt-5 flex flex-wrap items-center gap-5">
                    @if ($pageSection->image)
                        <img src="{{ asset('storage/'.$pageSection->image) }}" alt=""
                            class="h-20 w-32 shrink-0 rounded-lg border border-line object-cover">
                    @else
                        <div class="flex h-20 w-32 shrink-0 items-center justify-center rounded-lg border border-dashed border-line bg-paper text-xs text-ink-soft">
                            بدون صورة
                        </div>
                    @endif

                    <div class="min-w-0 flex-1 space-y-3">
                        <input type="file" name="image" accept="image/*"
                            class="block w-full text-sm text-ink-soft file:me-3 file:rounded-lg file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-medium file:text-brass-soft hover:file:bg-brass">
                        <x-input-error :messages="$errors->get('image')" />

                        @if ($pageSection->image)
                            <label class="inline-flex items-center gap-2 text-sm text-ink-soft">
                                <input type="checkbox" name="remove_image" value="1"
                                    class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
                                إزالة الصورة الحالية
                            </label>
                        @endif
                    </div>
                </div>
            </section>

            @if ($pageSection->key === 'why_choose_us')
                <section x-data="{
                        points: @js(old('points', $pageSection->extra_data['points'] ?? [])),
                    }"
                    class="rounded-lg border border-line bg-surface p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-display text-lg font-semibold text-ink">نقاط التميّز</h3>
                            <p class="mt-1 text-sm text-ink-soft">العناصر المعروضة ضمن قسم «لماذا تختارنا».</p>
                        </div>
                        <button type="button" @click="points.push({ icon: '', title: '', description: '' })"
                            class="inline-flex h-10 items-center justify-center gap-1.5 rounded-lg border border-line px-4 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                            </svg>
                            إضافة نقطة
                        </button>
                    </div>

                    <div class="mt-5 space-y-4">
                        <template x-for="(point, index) in points" :key="index">
                            <div class="grid grid-cols-1 gap-3 rounded-lg border border-line bg-paper/60 p-4 sm:grid-cols-12 sm:items-start">
                                <div class="sm:col-span-3">
                                    <label class="mb-2 block text-xs font-medium text-ink-soft">الأيقونة</label>
                                    <input type="text" x-model="point.icon" :name="`points[${index}][icon]`"
                                        class="h-11 w-full rounded-lg border-line bg-surface text-sm text-ink focus:border-brass focus:ring-brass">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="mb-2 block text-xs font-medium text-ink-soft">العنوان</label>
                                    <input type="text" x-model="point.title" :name="`points[${index}][title]`"
                                        class="h-11 w-full rounded-lg border-line bg-surface text-sm text-ink focus:border-brass focus:ring-brass">
                                </div>
                                <div class="sm:col-span-5">
                                    <label class="mb-2 block text-xs font-medium text-ink-soft">الوصف</label>
                                    <input type="text" x-model="point.description" :name="`points[${index}][description]`"
                                        class="h-11 w-full rounded-lg border-line bg-surface text-sm text-ink focus:border-brass focus:ring-brass">
                                </div>
                                <div class="flex items-end justify-end sm:col-span-1">
                                    <button type="button" @click="points.splice(index, 1)"
                                        class="inline-flex h-11 w-11 items-center justify-center rounded-lg border border-line text-ink-soft transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <p x-show="points.length === 0" x-cloak class="text-center text-sm text-ink-soft">لا توجد نقاط بعد.</p>
                    </div>
                </section>
            @endif

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.page-sections.index') }}"
                    class="inline-flex h-11 items-center justify-center rounded-lg border border-line px-5 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                    إلغاء
                </a>
                <button type="submit"
                    class="inline-flex h-11 items-center justify-center rounded-lg bg-ink px-5 text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
