<x-admin.layouts.app title="إضافة إحصائية">
    <div class="mx-auto w-full max-w-2xl">
        <div class="mb-8">
            <a href="{{ route('admin.company-statistics.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-ink-soft transition hover:text-brass">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                        clip-rule="evenodd" />
                </svg>
                العودة إلى الإحصائيات
            </a>
            <h2 class="mt-4 font-display text-2xl font-semibold text-ink">إضافة إحصائية</h2>
        </div>

        <form method="POST" action="{{ route('admin.company-statistics.store') }}"
            class="space-y-6 rounded-lg border border-line bg-surface p-6">
            @csrf

            @include('admin.company-statistics.partials.fields', ['companyStatistic' => null])

            <div class="flex items-center justify-end gap-3 border-t border-line pt-6">
                <a href="{{ route('admin.company-statistics.index') }}"
                    class="inline-flex h-11 items-center justify-center rounded-lg border border-line px-5 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                    إلغاء
                </a>
                <button type="submit"
                    class="inline-flex h-11 items-center justify-center rounded-lg bg-ink px-5 text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                    إضافة الإحصائية
                </button>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
