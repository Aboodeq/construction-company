@php
    $roleLabels = ['admin' => 'مدير عام', 'editor' => 'محرر'];
    $protectedRoles = ['admin', 'editor'];
@endphp

<x-admin.layouts.app title="الأدوار والصلاحيات">
    <div class="mx-auto w-full max-w-4xl">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <h2 class="font-display text-2xl font-semibold text-ink">الأدوار والصلاحيات</h2>
                <div class="mt-2 flex items-center gap-1.5 text-ink-soft" aria-hidden="true">
                    @for ($i = 0; $i < 24; $i++)
                        <span class="h-2 w-px bg-line"></span>
                    @endfor
                </div>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-ink-soft">
                    كل دور يحدد ما يستطيع صاحبه رؤيته وتعديله في لوحة التحكم. دور "مدير عام" يملك كل الصلاحيات دائمًا
                    ولا يمكن تعديله.
                </p>
            </div>

            @can('roles.create')
                <a href="{{ route('admin.roles.create') }}"
                    class="inline-flex h-11 items-center justify-center gap-1.5 rounded-lg bg-ink px-4 text-sm font-medium text-brass-soft transition hover:bg-brass hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    إضافة دور
                </a>
            @endcan
        </div>

        <section class="overflow-hidden rounded-lg border border-line bg-surface">
            <div class="divide-y divide-line">
                @foreach ($roles as $role)
                    <article class="flex flex-wrap items-center gap-4 p-5">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-semibold text-ink">{{ $roleLabels[$role->name] ?? $role->name }}</p>
                                @if (in_array($role->name, $protectedRoles, true))
                                    <span class="inline-flex rounded-full border border-line bg-paper px-2.5 py-1 text-xs font-medium text-ink-soft">
                                        دور أساسي
                                    </span>
                                @endif
                            </div>
                            @if (! in_array($role->name, $protectedRoles, true))
                                <p class="mt-1 text-xs text-ink-soft">{{ $role->name }}</p>
                            @endif
                        </div>

                        <div class="text-sm">
                            <p class="text-xs font-medium text-ink-soft">الصلاحيات</p>
                            <p class="mt-1 text-ink">{{ $role->permissions_count }}</p>
                        </div>

                        <div class="text-sm">
                            <p class="text-xs font-medium text-ink-soft">المستخدمون</p>
                            <p class="mt-1 text-ink">{{ $role->users_count }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            @can('roles.edit')
                                @if ($role->name !== 'admin')
                                    <a href="{{ route('admin.roles.edit', $role) }}"
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
                                @endif
                            @endcan
                            @can('roles.delete')
                                @if (! in_array($role->name, $protectedRoles, true))
                                    <x-admin.confirm-form
                                        :action="route('admin.roles.destroy', $role)"
                                        title="حذف الدور"
                                        :message="'سيتم حذف دور «'.$role->name.'». هذا الإجراء متاح فقط إن لم يكن هناك مستخدمون مرتبطون به.'"
                                        class="h-9 w-9"
                                        triggerLabel="حذف">
                                        <span class="sr-only">حذف</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </x-admin.confirm-form>
                                @endif
                            @endcan
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
</x-admin.layouts.app>
