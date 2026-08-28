@php
    $statusLabels = ['new' => 'جديد', 'read' => 'تمت المشاهدة', 'in_progress' => 'قيد المعالجة', 'closed' => 'مغلق'];
    $statusClasses = [
        'new' => 'border-brass/20 bg-brass-soft text-brass',
        'read' => 'border-line bg-paper text-ink-soft',
        'in_progress' => 'border-forest/15 bg-forest/5 text-forest',
        'closed' => 'border-line bg-paper text-ink-soft/60',
    ];
@endphp

<x-admin.layouts.app title="طلبات الأسعار">
    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <h2 class="font-display text-2xl font-semibold text-ink">طلبات الأسعار</h2>
                <div class="mt-2 flex items-center gap-1.5 text-ink-soft" aria-hidden="true">
                    @for ($i = 0; $i < 24; $i++)
                        <span class="h-2 w-px bg-line"></span>
                    @endfor
                </div>
                <p class="mt-3 max-w-3xl text-sm leading-6 text-ink-soft">
                    طلبات عروض الأسعار الواردة من زوار الموقع.
                </p>
            </div>

            @can('quote-requests.export')
                <a href="{{ route('admin.quote-requests.export', request()->query()) }}"
                    class="inline-flex h-11 items-center justify-center gap-1.5 rounded-lg border border-line bg-surface px-4 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    تصدير CSV
                </a>
            @endcan
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">إجمالي الطلبات</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-ink">{{ number_format($stats['total']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-ink"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">جديدة</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-brass">{{ number_format($stats['new']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-brass"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">قيد المعالجة</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-forest">{{ number_format($stats['in_progress']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-forest"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">مغلقة</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-ink">{{ number_format($stats['closed']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-line"></span>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.quote-requests.index') }}"
            class="mt-6 rounded-lg border border-line bg-surface p-5">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-6">
                    <label for="qr-search" class="mb-2 block text-xs font-medium text-ink-soft">بحث</label>
                    <input id="qr-search" name="search" value="{{ request('search') }}" type="search"
                        placeholder="الاسم أو الهاتف أو المدينة"
                        class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">
                </div>
                <div class="lg:col-span-3">
                    <label for="qr-status" class="mb-2 block text-xs font-medium text-ink-soft">الحالة</label>
                    <select id="qr-status" name="status"
                        class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
                        <option value="">كل الحالات</option>
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 lg:col-span-3">
                    <button type="submit"
                        class="inline-flex h-11 flex-1 items-center justify-center rounded-lg bg-ink px-4 text-sm font-medium text-brass-soft transition hover:bg-brass hover:text-white">
                        تصفية
                    </button>
                    @if (request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.quote-requests.index') }}"
                            class="inline-flex h-11 items-center justify-center rounded-lg border border-line px-4 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                            مسح
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <section class="mt-6 overflow-hidden rounded-lg border border-line bg-surface">
            <div class="divide-y divide-line">
                @forelse ($quoteRequests as $quoteRequest)
                    <a href="{{ route('admin.quote-requests.show', $quoteRequest) }}"
                        class="flex flex-wrap items-center gap-4 p-5 transition hover:bg-paper/45">
                        @if ($quoteRequest->status === 'new')
                            <span class="h-2 w-2 shrink-0 rounded-full bg-brass" title="جديد"></span>
                        @else
                            <span class="h-2 w-2 shrink-0 rounded-full bg-transparent"></span>
                        @endif

                        <div class="min-w-0 flex-1">
                            <p class="{{ $quoteRequest->status === 'new' ? 'font-semibold text-ink' : 'font-medium text-ink' }}">
                                {{ $quoteRequest->name }}
                            </p>
                            <p class="mt-1 truncate text-xs text-ink-soft">
                                {{ $quoteRequest->phone }} · {{ $quoteRequest->project_type }} · {{ $quoteRequest->city }}
                            </p>
                        </div>

                        <span
                            class="inline-flex shrink-0 rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusClasses[$quoteRequest->status] ?? '' }}">
                            {{ $statusLabels[$quoteRequest->status] ?? $quoteRequest->status }}
                        </span>

                        <span class="shrink-0 text-xs text-ink-soft">{{ $quoteRequest->created_at?->diffForHumans() }}</span>
                    </a>
                @empty
                    <div class="px-5 py-14 text-center">
                        <p class="font-medium text-ink">لا توجد طلبات مطابقة</p>
                        <p class="mt-2 text-sm text-ink-soft">جرّب تعديل البحث أو الفلاتر الحالية.</p>
                    </div>
                @endforelse
            </div>
        </section>

        @if ($quoteRequests->hasPages())
            <div class="mt-6 flex flex-col gap-3 border-t border-line pt-5 text-sm text-ink-soft sm:flex-row sm:items-center sm:justify-between">
                <p>عرض {{ $quoteRequests->firstItem() }} - {{ $quoteRequests->lastItem() }} من {{ $quoteRequests->total() }}</p>
                <div class="flex items-center gap-2">
                    @if ($quoteRequests->onFirstPage())
                        <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">السابق</span>
                    @else
                        <a href="{{ $quoteRequests->previousPageUrl() }}" class="rounded-lg border border-line px-4 py-2 transition hover:bg-surface hover:text-ink">السابق</a>
                    @endif
                    @if ($quoteRequests->hasMorePages())
                        <a href="{{ $quoteRequests->nextPageUrl() }}" class="rounded-lg border border-line px-4 py-2 transition hover:bg-surface hover:text-ink">التالي</a>
                    @else
                        <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">التالي</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-admin.layouts.app>
