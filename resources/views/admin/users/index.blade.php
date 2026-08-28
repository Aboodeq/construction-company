@php
    $roleLabels = ['admin' => 'مدير عام', 'editor' => 'محرر'];
@endphp

<x-admin.layouts.app title="المستخدمون">
    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <h2 class="font-display text-2xl font-semibold text-ink">المستخدمون</h2>
                <div class="mt-2 flex items-center gap-1.5 text-ink-soft" aria-hidden="true">
                    @for ($i = 0; $i < 24; $i++)
                        <span class="h-2 w-px bg-line"></span>
                    @endfor
                </div>
                <p class="mt-3 max-w-3xl text-sm leading-6 text-ink-soft">
                    حسابات فريق العمل الذين يملكون وصولًا للوحة التحكم، ودور كل منهم.
                </p>
            </div>

            @can('users.create')
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex h-11 items-center justify-center gap-1.5 rounded-lg bg-ink px-4 text-sm font-medium text-brass-soft transition hover:bg-brass hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    إضافة مستخدم
                </a>
            @endcan
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">إجمالي المستخدمين</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-ink">{{ number_format($stats['total']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-ink"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">نشطون</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-forest">{{ number_format($stats['active']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-forest"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">مدراء عامون</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-brass">{{ number_format($stats['admins']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-brass"></span>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}"
            class="mt-6 rounded-lg border border-line bg-surface p-5">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-6">
                    <label for="users-search" class="mb-2 block text-xs font-medium text-ink-soft">بحث</label>
                    <input id="users-search" name="search" value="{{ request('search') }}" type="search"
                        placeholder="الاسم أو البريد الإلكتروني"
                        class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">
                </div>
                <div class="lg:col-span-3">
                    <label for="users-role" class="mb-2 block text-xs font-medium text-ink-soft">الدور</label>
                    <select id="users-role" name="role"
                        class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
                        <option value="">كل الأدوار</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" @selected(request('role') === $role->name)>
                                {{ $roleLabels[$role->name] ?? $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 lg:col-span-3">
                    <button type="submit"
                        class="inline-flex h-11 flex-1 items-center justify-center rounded-lg bg-ink px-4 text-sm font-medium text-brass-soft transition hover:bg-brass hover:text-white">
                        تصفية
                    </button>
                    @if (request()->hasAny(['search', 'role']))
                        <a href="{{ route('admin.users.index') }}"
                            class="inline-flex h-11 items-center justify-center rounded-lg border border-line px-4 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                            مسح
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <section class="mt-6 overflow-hidden rounded-lg border border-line bg-surface">
            <div class="divide-y divide-line">
                @forelse ($users as $user)
                    <article class="flex flex-wrap items-center gap-4 p-5 transition hover:bg-paper/45">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brass-soft font-display text-lg font-bold text-brass">
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-semibold text-ink">{{ $user->name }}</p>
                                @if (! $user->is_active)
                                    <span class="inline-flex rounded-full border border-line bg-paper px-2.5 py-1 text-xs font-medium text-ink-soft">
                                        معطّل
                                    </span>
                                @endif
                                @if ($user->id === auth()->id())
                                    <span class="inline-flex rounded-full border border-brass/15 bg-brass-soft px-2.5 py-1 text-xs font-medium text-brass">
                                        أنت
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1 truncate text-xs text-ink-soft">{{ $user->email }}</p>
                        </div>

                        <div class="text-sm">
                            <p class="text-xs font-medium text-ink-soft">الدور</p>
                            <p class="mt-1 text-ink">{{ $roleLabels[$user->getRoleNames()->first()] ?? $user->getRoleNames()->first() ?? 'بدون دور' }}</p>
                        </div>

                        <div class="text-sm">
                            <p class="text-xs font-medium text-ink-soft">آخر دخول</p>
                            <p class="mt-1 text-ink">{{ $user->last_login_at?->diffForHumans() ?? 'لم يسجّل دخول بعد' }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            @can('users.edit')
                                <a href="{{ route('admin.users.edit', $user) }}"
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
                            @can('users.delete')
                                @if ($user->id !== auth()->id())
                                    <x-admin.confirm-form
                                        :action="route('admin.users.destroy', $user)"
                                        title="حذف المستخدم"
                                        :message="'سيتم حذف حساب «'.$user->name.'» نهائيًا. هل تريد المتابعة؟'"
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
                @empty
                    <div class="px-5 py-14 text-center">
                        <p class="font-medium text-ink">لا يوجد مستخدمون مطابقون</p>
                    </div>
                @endforelse
            </div>
        </section>

        @if ($users->hasPages())
            <div class="mt-6 flex flex-col gap-3 border-t border-line pt-5 text-sm text-ink-soft sm:flex-row sm:items-center sm:justify-between">
                <p>
                    عرض {{ $users->firstItem() }} - {{ $users->lastItem() }} من {{ $users->total() }}
                </p>
                <div class="flex items-center gap-2">
                    @if ($users->onFirstPage())
                        <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">السابق</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}"
                            class="rounded-lg border border-line px-4 py-2 transition hover:bg-surface hover:text-ink">
                            السابق
                        </a>
                    @endif

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}"
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
