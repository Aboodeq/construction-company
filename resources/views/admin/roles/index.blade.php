@php
    $roleLabels = ['admin' => 'مدير عام', 'editor' => 'محرر'];
    $protectedRoles = ['admin', 'editor'];

    $tiers = [
        'admin' => ['badge' => 'bg-ink text-brass-soft', 'bar' => 'bg-ink'],
        'editor' => ['badge' => 'bg-forest/10 text-forest', 'bar' => 'bg-forest'],
        'custom' => ['badge' => 'bg-brass-soft text-brass', 'bar' => 'bg-brass'],
    ];

    $totalUsersAssigned = $roles->sum('users_count');
@endphp

<x-admin.layouts.app title="الأدوار والصلاحيات">
    <div class="mx-auto w-full max-w-6xl">
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

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">إجمالي الأدوار</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-ink">{{ $roles->count() }}</p>
                    <span class="h-10 w-1 rounded-full bg-ink"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">صلاحيات معرّفة بالنظام</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-brass">{{ $totalPermissions }}</p>
                    <span class="h-10 w-1 rounded-full bg-brass"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">مستخدمون مرتبطون بأدوار</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-forest">{{ $totalUsersAssigned }}</p>
                    <span class="h-10 w-1 rounded-full bg-forest"></span>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($roles as $role)
                @php
                    $tierKey = in_array($role->name, $protectedRoles, true) ? $role->name : 'custom';
                    $tier = $tiers[$tierKey];
                    $label = $roleLabels[$role->name] ?? $role->name;
                    $permissionCount = $role->permissions->count();
                    $coverage = $totalPermissions > 0 ? round(($permissionCount / $totalPermissions) * 100) : 0;
                    $touchedGroups = collect($groupLabels)
                        ->filter(fn ($groupLabel, $prefix) => $role->permissions->contains(fn ($p) => str_starts_with($p->name, $prefix.'.')))
                        ->values();
                @endphp
                <article class="relative overflow-hidden rounded-xl border border-line bg-surface p-6 transition hover:border-brass/30 hover:shadow-lg">
                    <div class="absolute inset-x-0 top-0 h-1 {{ $tier['bar'] }}"></div>

                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl font-display text-lg font-bold {{ $tier['badge'] }}">
                                {{ mb_substr($label, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="truncate font-display text-lg font-semibold text-ink">{{ $label }}</h3>
                                @if ($tierKey === 'custom')
                                    <p class="truncate text-xs text-ink-soft">{{ $role->name }}</p>
                                @endif
                            </div>
                        </div>
                        @if ($tierKey !== 'custom')
                            <span class="shrink-0 rounded-full border border-line bg-paper px-2.5 py-1 text-xs font-medium text-ink-soft">
                                أساسي
                            </span>
                        @endif
                    </div>

                    <div class="mt-5">
                        <div class="flex items-center justify-between text-xs text-ink-soft">
                            <span>الصلاحيات</span>
                            <span class="font-medium text-ink">{{ $permissionCount }} / {{ $totalPermissions }}</span>
                        </div>
                        <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-paper">
                            <div class="h-full rounded-full {{ $tier['bar'] }}" style="width: {{ $coverage }}%"></div>
                        </div>
                    </div>

                    <div class="mt-4 flex min-h-7 flex-wrap gap-1.5">
                        @forelse ($touchedGroups as $groupLabel)
                            <span class="rounded-full border border-line bg-paper px-2.5 py-1 text-xs text-ink-soft">
                                {{ $groupLabel }}
                            </span>
                        @empty
                            <span class="text-xs text-ink-soft/60">بلا صلاحيات محددة بعد</span>
                        @endforelse
                    </div>

                    <div class="mt-5 flex items-center justify-between border-t border-line pt-4">
                        <div class="flex items-center">
                            @forelse ($role->users as $user)
                                <div class="-ms-2 flex h-7 w-7 shrink-0 items-center justify-center rounded-full border-2 border-surface bg-brass-soft text-xs font-semibold text-brass first:ms-0"
                                    title="{{ $user->name }}">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                            @empty
                                <span class="text-xs text-ink-soft">لا يوجد مستخدمون</span>
                            @endforelse
                            @if ($role->users_count > $role->users->count())
                                <div class="-ms-2 flex h-7 w-7 shrink-0 items-center justify-center rounded-full border-2 border-surface bg-ink text-xs font-semibold text-brass-soft">
                                    +{{ $role->users_count - $role->users->count() }}
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-1.5">
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
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</x-admin.layouts.app>
