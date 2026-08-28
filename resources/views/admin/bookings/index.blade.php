@php
    $statusLabels = ['pending' => 'قيد الانتظار', 'confirmed' => 'مؤكد', 'completed' => 'مكتمل', 'cancelled' => 'ملغى'];
    $statusClasses = [
        'pending' => 'border-brass/20 bg-brass-soft text-brass',
        'confirmed' => 'border-forest/15 bg-forest/5 text-forest',
        'completed' => 'border-line bg-paper text-ink-soft',
        'cancelled' => 'border-red-100 bg-red-50 text-red-600',
    ];
@endphp

<x-admin.layouts.app title="الحجوزات">
    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-8">
            <h2 class="font-display text-2xl font-semibold text-ink">الحجوزات</h2>
            <div class="mt-2 flex items-center gap-1.5 text-ink-soft" aria-hidden="true">
                @for ($i = 0; $i < 24; $i++)
                    <span class="h-2 w-px bg-line"></span>
                @endfor
            </div>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-ink-soft">
                حجوزات المعاينة والاستشارة الواردة من زوار الموقع، مرتبة حسب الموعد المفضّل.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">إجمالي الحجوزات</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-ink">{{ number_format($stats['total']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-ink"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">قيد الانتظار</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-brass">{{ number_format($stats['pending']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-brass"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">مؤكدة</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-forest">{{ number_format($stats['confirmed']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-forest"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">مواعيد قادمة</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-ink">{{ number_format($stats['upcoming']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-line"></span>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.bookings.index') }}"
            class="mt-6 rounded-lg border border-line bg-surface p-5">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-6">
                    <label for="bookings-search" class="mb-2 block text-xs font-medium text-ink-soft">بحث</label>
                    <input id="bookings-search" name="search" value="{{ request('search') }}" type="search"
                        placeholder="الاسم أو الهاتف أو المدينة"
                        class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">
                </div>
                <div class="lg:col-span-3">
                    <label for="bookings-status" class="mb-2 block text-xs font-medium text-ink-soft">الحالة</label>
                    <select id="bookings-status" name="status"
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
                        <a href="{{ route('admin.bookings.index') }}"
                            class="inline-flex h-11 items-center justify-center rounded-lg border border-line px-4 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                            مسح
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <section class="mt-6 overflow-hidden rounded-lg border border-line bg-surface">
            <div class="divide-y divide-line">
                @forelse ($bookings as $booking)
                    <article class="flex flex-wrap items-center gap-4 p-5 transition hover:bg-paper/45">
                        <div class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-lg bg-brass-soft text-brass">
                            <span class="text-[10px] font-medium leading-none">{{ $booking->preferred_date?->translatedFormat('M') }}</span>
                            <span class="font-display text-lg font-bold leading-tight">{{ $booking->preferred_date?->format('d') }}</span>
                        </div>

                        <div class="min-w-0 flex-1">
                            @can('bookings.view')
                                <a href="{{ route('admin.bookings.edit', $booking) }}" class="font-semibold text-ink transition hover:text-brass">
                                    {{ $booking->name }}
                                </a>
                            @else
                                <p class="font-semibold text-ink">{{ $booking->name }}</p>
                            @endcan
                            <p class="mt-1 truncate text-xs text-ink-soft">
                                {{ $booking->phone }} · {{ $booking->city }}
                                @if ($booking->preferred_time)
                                    · {{ $booking->preferred_time }}
                                @endif
                            </p>
                        </div>

                        <span
                            class="inline-flex shrink-0 rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusClasses[$booking->status] ?? '' }}">
                            {{ $statusLabels[$booking->status] ?? $booking->status }}
                        </span>

                        <div class="flex shrink-0 items-center gap-2">
                            @can('bookings.view')
                                <a href="{{ route('admin.bookings.edit', $booking) }}"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-line text-ink-soft transition hover:border-brass/40 hover:text-brass"
                                    title="عرض">
                                    <span class="sr-only">عرض</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                            @endcan
                            @can('bookings.delete')
                                <x-admin.confirm-form
                                    :action="route('admin.bookings.destroy', $booking)"
                                    title="حذف الحجز"
                                    :message="'سيتم حذف حجز «'.$booking->name.'» نهائيًا. هل تريد المتابعة؟'"
                                    class="h-9 w-9"
                                    triggerLabel="حذف">
                                    <span class="sr-only">حذف</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </x-admin.confirm-form>
                            @endcan
                        </div>
                    </article>
                @empty
                    <div class="px-5 py-14 text-center">
                        <p class="font-medium text-ink">لا توجد حجوزات مطابقة</p>
                        <p class="mt-2 text-sm text-ink-soft">جرّب تعديل البحث أو الفلاتر الحالية.</p>
                    </div>
                @endforelse
            </div>
        </section>

        @if ($bookings->hasPages())
            <div class="mt-6 flex flex-col gap-3 border-t border-line pt-5 text-sm text-ink-soft sm:flex-row sm:items-center sm:justify-between">
                <p>عرض {{ $bookings->firstItem() }} - {{ $bookings->lastItem() }} من {{ $bookings->total() }}</p>
                <div class="flex items-center gap-2">
                    @if ($bookings->onFirstPage())
                        <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">السابق</span>
                    @else
                        <a href="{{ $bookings->previousPageUrl() }}" class="rounded-lg border border-line px-4 py-2 transition hover:bg-surface hover:text-ink">السابق</a>
                    @endif
                    @if ($bookings->hasMorePages())
                        <a href="{{ $bookings->nextPageUrl() }}" class="rounded-lg border border-line px-4 py-2 transition hover:bg-surface hover:text-ink">التالي</a>
                    @else
                        <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">التالي</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-admin.layouts.app>
