<x-admin.layouts.app title="لوحة التحكم">

    <div class="mb-8">
        <h2 class="font-display text-2xl font-semibold text-ink">مرحبًا بعودتك 👋</h2>
        <div class="mt-2 flex items-center gap-1.5 text-ink-soft" aria-hidden="true">
            @for ($i = 0; $i < 24; $i++)
                <span class="h-2 w-px bg-line"></span>
            @endfor
        </div>
        <p class="mt-3 text-sm text-ink-soft">نظرة عامة سريعة على أداء الموقع اليوم.</p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-line bg-surface p-5">
            <p class="text-xs font-medium text-ink-soft">المشاريع المنجزة</p>
            <p class="mt-2 font-display text-3xl font-bold text-ink">12</p>
        </div>
        <div class="rounded-xl border border-line bg-surface p-5">
            <p class="text-xs font-medium text-ink-soft">الخدمات النشطة</p>
            <p class="mt-2 font-display text-3xl font-bold text-ink">10</p>
        </div>
        <div class="rounded-xl border border-line bg-surface p-5">
            <p class="text-xs font-medium text-ink-soft">طلبات جديدة</p>
            <p class="mt-2 font-display text-3xl font-bold text-brass">4</p>
        </div>
        <div class="rounded-xl border border-line bg-surface p-5">
            <p class="text-xs font-medium text-ink-soft">رسائل غير مقروءة</p>
            <p class="mt-2 font-display text-3xl font-bold text-ink">2</p>
        </div>
    </div>

</x-admin.layouts.app>
