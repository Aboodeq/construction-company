@php
    $statusLabels = ['open' => 'مفتوحة', 'closed' => 'مغلقة'];
    $statusClasses = [
        'open' => 'border-forest/15 bg-forest/5 text-forest',
        'closed' => 'border-line bg-paper text-ink-soft',
    ];
@endphp

<x-admin.layouts.app title="المحادثات المباشرة">
    <div class="mx-auto w-full max-w-5xl">
        <div class="mb-8">
            <h2 class="font-display text-2xl font-semibold text-ink">المحادثات المباشرة</h2>
            <div class="mt-2 flex items-center gap-1.5 text-ink-soft" aria-hidden="true">
                @for ($i = 0; $i < 24; $i++)
                    <span class="h-2 w-px bg-line"></span>
                @endfor
            </div>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-ink-soft">
                محادثات زوار الموقع عبر أداة الدردشة المباشرة.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">إجمالي المحادثات</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-ink">{{ number_format($stats['total']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-ink"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">مفتوحة</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-forest">{{ number_format($stats['open']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-forest"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">بحاجة إلى رد</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-brass">{{ number_format($stats['unread']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-brass"></span>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.chats.index') }}" class="mt-6 flex items-center gap-3">
            <select name="status" onchange="this.form.submit()"
                class="h-11 rounded-lg border-line bg-surface text-sm text-ink focus:border-brass focus:ring-brass">
                <option value="">كل المحادثات</option>
                @foreach ($statusLabels as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>

        <section class="mt-6 overflow-hidden rounded-lg border border-line bg-surface">
            <div class="divide-y divide-line">
                @forelse ($conversations as $conversation)
                    <a href="{{ route('admin.chats.show', $conversation) }}"
                        class="flex flex-wrap items-center gap-4 p-5 transition hover:bg-paper/45">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brass-soft font-display text-lg font-bold text-brass">
                            {{ mb_substr($conversation->visitor_name ?: 'ز', 0, 1) }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="{{ $conversation->unread_count > 0 ? 'font-semibold text-ink' : 'font-medium text-ink' }}">
                                {{ $conversation->visitor_name ?: 'زائر بدون اسم' }}
                            </p>
                            <p class="mt-1 truncate text-xs text-ink-soft">
                                {{ $conversation->visitor_email ?? 'لم يقدّم بريدًا إلكترونيًا' }}
                            </p>
                        </div>

                        @if ($conversation->unread_count > 0)
                            <span class="flex h-6 min-w-6 shrink-0 items-center justify-center rounded-full bg-brass px-1.5 text-xs font-bold text-white">
                                {{ $conversation->unread_count }}
                            </span>
                        @endif

                        <span class="inline-flex shrink-0 rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusClasses[$conversation->status] ?? '' }}">
                            {{ $statusLabels[$conversation->status] ?? $conversation->status }}
                        </span>

                        <span class="shrink-0 text-xs text-ink-soft">
                            {{ $conversation->last_message_at?->diffForHumans() ?? $conversation->created_at?->diffForHumans() }}
                        </span>
                    </a>
                @empty
                    <div class="px-5 py-14 text-center">
                        <p class="font-medium text-ink">لا توجد محادثات بعد</p>
                        <p class="mt-2 text-sm text-ink-soft">ستظهر هنا محادثات الزوار فور استخدامهم أداة الدردشة بالموقع.</p>
                    </div>
                @endforelse
            </div>
        </section>

        @if ($conversations->hasPages())
            <div class="mt-6 flex justify-center gap-2 text-sm text-ink-soft">
                @if ($conversations->onFirstPage())
                    <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">السابق</span>
                @else
                    <a href="{{ $conversations->previousPageUrl() }}" class="rounded-lg border border-line px-4 py-2 hover:bg-surface hover:text-ink">السابق</a>
                @endif
                @if ($conversations->hasMorePages())
                    <a href="{{ $conversations->nextPageUrl() }}" class="rounded-lg border border-line px-4 py-2 hover:bg-surface hover:text-ink">التالي</a>
                @else
                    <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">التالي</span>
                @endif
            </div>
        @endif
    </div>
</x-admin.layouts.app>
