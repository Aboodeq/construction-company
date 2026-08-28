@php
    $statusLabels = ['published' => 'منشور', 'draft' => 'مسودة'];
    $statusClasses = [
        'published' => 'border-forest/15 bg-forest/5 text-forest',
        'draft' => 'border-line bg-paper text-ink-soft',
    ];
@endphp

<x-admin.layouts.app title="تعديل عضو فريق">
    <div class="mx-auto w-full max-w-5xl">

        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <a href="{{ route('admin.team.index') }}"
                    class="inline-flex items-center gap-1.5 text-sm text-ink-soft transition hover:text-brass">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                            clip-rule="evenodd" />
                    </svg>
                    العودة إلى فريق العمل
                </a>
                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <h2 class="font-display text-2xl font-semibold text-ink">{{ $member->name }}</h2>
                    <span
                        class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusClasses[$member->status] ?? '' }}">
                        {{ $statusLabels[$member->status] ?? $member->status }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:items-start">

            {{-- Main column --}}
            <div class="space-y-6 lg:col-span-2">
                <form id="team-member-edit-form" method="POST" action="{{ route('admin.team.update', $member) }}"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">بيانات العضو</h3>
                        <div class="mt-5">
                            @include('admin.team.partials.fields', ['member' => $member])
                        </div>
                    </section>
                </form>
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6 lg:sticky lg:top-24">
                <div class="rounded-lg border border-line bg-surface p-6">
                    <h3 class="font-display text-base font-semibold text-ink">الصورة الشخصية</h3>

                    <div class="mt-4 flex flex-col items-center gap-4">
                        @if ($member->image)
                            <img src="{{ asset('storage/'.$member->image) }}" alt=""
                                class="h-32 w-32 rounded-full border border-line object-cover">
                        @else
                            <div class="flex h-32 w-32 items-center justify-center rounded-full border border-dashed border-line bg-paper font-display text-3xl font-bold text-ink-soft">
                                {{ mb_substr($member->name, 0, 1) }}
                            </div>
                        @endif

                        <div class="w-full space-y-3">
                            <input type="file" name="image" accept="image/*" form="team-member-edit-form"
                                class="block w-full text-sm text-ink-soft file:me-3 file:rounded-lg file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-medium file:text-brass-soft hover:file:bg-brass">
                            <x-input-error :messages="$errors->get('image')" />

                            @if ($member->image)
                                <label class="inline-flex items-center gap-2 text-sm text-ink-soft">
                                    <input type="checkbox" name="remove_image" value="1" form="team-member-edit-form"
                                        class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
                                    إزالة الصورة الحالية
                                </label>
                            @endif
                        </div>
                    </div>

                    <button type="submit" form="team-member-edit-form"
                        class="mt-6 inline-flex h-11 w-full items-center justify-center rounded-lg bg-ink text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                        حفظ التغييرات
                    </button>

                    <form method="POST" action="{{ route('admin.team.toggle-published', $member) }}" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex h-10 w-full items-center justify-center rounded-lg border border-line text-xs font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                            {{ $member->status === 'published' ? 'تحويل إلى مسودة' : 'نشر الآن' }}
                        </button>
                    </form>
                </div>

                @can('team.delete')
                    <div class="rounded-lg border border-red-100 bg-red-50/40 p-6">
                        <h3 class="font-display text-base font-semibold text-ink">منطقة الخطر</h3>
                        <p class="mt-1 text-sm text-ink-soft">حذف عضو الفريق نهائي ولا يمكن التراجع عنه.</p>
                        <x-admin.confirm-form
                            :action="route('admin.team.destroy', $member)"
                            title="حذف عضو الفريق"
                            :message="'سيتم حذف «'.$member->name.'» نهائيًا. هل تريد المتابعة؟'"
                            class="mt-4 h-11 w-full px-5 text-sm font-semibold">
                            حذف العضو
                        </x-admin.confirm-form>
                    </div>
                @endcan
            </aside>
        </div>
    </div>
</x-admin.layouts.app>
