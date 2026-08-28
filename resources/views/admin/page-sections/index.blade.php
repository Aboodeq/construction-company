<x-admin.layouts.app title="أقسام الصفحات">
    <div class="mx-auto w-full max-w-4xl">
        <div class="mb-8">
            <h2 class="font-display text-2xl font-semibold text-ink">أقسام الصفحات</h2>
            <div class="mt-2 flex items-center gap-1.5 text-ink-soft" aria-hidden="true">
                @for ($i = 0; $i < 24; $i++)
                    <span class="h-2 w-px bg-line"></span>
                @endfor
            </div>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-ink-soft">
                النصوص التحريرية الثابتة المستخدمة في أقسام الصفحة الرئيسية وصفحة من نحن.
            </p>
        </div>

        <section class="overflow-hidden rounded-lg border border-line bg-surface">
            <div class="divide-y divide-line">
                @foreach ($pageSections as $pageSection)
                    <a href="{{ route('admin.page-sections.edit', $pageSection) }}"
                        class="flex flex-wrap items-center gap-4 p-5 transition hover:bg-paper/45">
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-ink">{{ $pageSection->title ?? $pageSection->key }}</p>
                            <p class="mt-1 font-mono text-xs text-ink-soft">{{ $pageSection->key }}</p>
                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-ink-soft" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                        </svg>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</x-admin.layouts.app>
