<aside x-cloak
    class="fixed inset-y-0 right-0 z-40 w-72 max-w-[calc(100vw-1rem)] transform border-l border-line bg-surface shadow-2xl transition-transform duration-300 ease-in-out lg:shadow-none"
    :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full'">
    <div class="flex h-full flex-col">

        {{-- Brand + close button --}}
        <div class="flex items-center justify-between gap-3 border-b border-line px-6 py-6">
            <div class="flex items-center gap-3 overflow-hidden">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-ink font-display text-lg font-bold text-brass-soft">
                    {{ mb_substr(setting('site_name', 'ش'), 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="truncate font-display text-base font-semibold text-ink">
                        {{ setting('site_name', 'لوحة التحكم') }}</p>
                    <p class="text-xs text-ink-soft">لوحة الإدارة</p>
                </div>
            </div>

            <button type="button" @click="sidebarOpen = false" aria-label="Close sidebar"
                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-ink-soft transition hover:bg-paper hover:text-ink">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6" @click="if (!isDesktop) sidebarOpen = false">

            <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" icon="home">
                الرئيسية
            </x-admin.nav-link>

            <x-admin.nav-section title="المحتوى">
                @can('services.view')
                    <x-admin.nav-link :href="route('admin.services.index')" :active="request()->routeIs('admin.services.*')" icon="wrench">الخدمات</x-admin.nav-link>
                @endcan
                @can('projects.view')
                    <x-admin.nav-link :href="route('admin.projects.index')" :active="request()->routeIs('admin.projects.*')" icon="building">المشاريع</x-admin.nav-link>
                @endcan
                @can('blog.view')
                    <x-admin.nav-link :href="route('admin.blog.index')" :active="request()->routeIs('admin.blog.*')" icon="document">المدونة</x-admin.nav-link>
                @endcan
                @can('testimonials.view')
                    <x-admin.nav-link :href="route('admin.testimonials.index')" :active="request()->routeIs('admin.testimonials.*')" icon="star">آراء العملاء</x-admin.nav-link>
                @endcan
                @can('faqs.view')
                    <x-admin.nav-link :href="route('admin.faqs.index')" :active="request()->routeIs('admin.faqs.*')" icon="question">
                        الأسئلة الشائعة
                    </x-admin.nav-link>
                @endcan
                <x-admin.nav-link :href="route('admin.dashboard')" :active="false" icon="users">فريق العمل</x-admin.nav-link>
            </x-admin.nav-section>

            <x-admin.nav-section title="الطلبات">
                <x-admin.nav-link :href="route('admin.dashboard')" :active="false" icon="clipboard">طلبات
                    الأسعار</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.dashboard')" :active="false" icon="calendar">الحجوزات</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.dashboard')" :active="false" icon="mail">رسائل التواصل</x-admin.nav-link>
            </x-admin.nav-section>

            <x-admin.nav-section title="الصفحة الرئيسية">
                <x-admin.nav-link :href="route('admin.dashboard')" :active="false" icon="image">شرائح البانر</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.dashboard')" :active="false" icon="chart">الإحصائيات</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.dashboard')" :active="false" icon="flag">مراحل العمل</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.dashboard')" :active="false" icon="layout">أقسام الصفحات</x-admin.nav-link>
            </x-admin.nav-section>

            @canany(['users.view', 'roles.view', 'settings.edit'])
                <x-admin.nav-section title="النظام">
                    @can('users.view')
                        <x-admin.nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" icon="user-group">المستخدمون</x-admin.nav-link>
                    @endcan
                    @can('roles.view')
                        <x-admin.nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')" icon="settings">الأدوار والصلاحيات</x-admin.nav-link>
                    @endcan
                </x-admin.nav-section>
            @endcanany

        </nav>

        {{-- User menu --}}
        <div class="border-t border-line p-4">
            <div class="flex items-center gap-3 rounded-lg px-2 py-2">
                <a href="{{ route('admin.profile.edit') }}" class="flex min-w-0 flex-1 items-center gap-3">
                    <div
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brass-soft text-sm font-semibold text-brass">
                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-ink">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-ink-soft">{{ auth()->user()->getRoleNames()->first() }}</p>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="shrink-0 text-ink-soft transition hover:text-brass"
                        title="تسجيل الخروج">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h6a1 1 0 100-2H4V6h5a1 1 0 100-2H3zm11.293 2.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L15.586 10H8a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

    </div>
</aside>

{{-- Mobile overlay --}}
<div x-show="sidebarOpen && !isDesktop" x-cloak x-transition.opacity @click="sidebarOpen = false"
    class="fixed inset-0 z-30 bg-ink/40 lg:hidden"></div>
