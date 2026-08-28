<x-admin.layouts.app title="رسائل التواصل">
    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-8">
            <h2 class="font-display text-2xl font-semibold text-ink">رسائل التواصل</h2>
            <div class="mt-2 flex items-center gap-1.5 text-ink-soft" aria-hidden="true">
                @for ($i = 0; $i < 24; $i++)
                    <span class="h-2 w-px bg-line"></span>
                @endfor
            </div>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-ink-soft">
                الرسائل الواردة من نموذج التواصل بالموقع.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">إجمالي الرسائل</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-ink">{{ number_format($stats['total']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-ink"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">غير مقروءة</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-brass">{{ number_format($stats['unread']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-brass"></span>
                </div>
            </div>
            <div class="rounded-lg border border-line bg-surface p-5">
                <p class="text-xs font-medium text-ink-soft">بحاجة إلى رد</p>
                <div class="mt-4 flex items-end justify-between gap-3">
                    <p class="font-display text-3xl font-bold text-forest">{{ number_format($stats['unreplied']) }}</p>
                    <span class="h-10 w-1 rounded-full bg-forest"></span>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.contact-messages.index') }}"
            class="mt-6 rounded-lg border border-line bg-surface p-5">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-6">
                    <label for="cm-search" class="mb-2 block text-xs font-medium text-ink-soft">بحث</label>
                    <input id="cm-search" name="search" value="{{ request('search') }}" type="search"
                        placeholder="الاسم أو البريد أو الموضوع"
                        class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">
                </div>
                <div class="lg:col-span-3">
                    <label for="cm-filter" class="mb-2 block text-xs font-medium text-ink-soft">التصفية</label>
                    <select id="cm-filter" name="filter"
                        class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
                        <option value="">الكل</option>
                        <option value="unread" @selected(request('filter') === 'unread')>غير مقروءة</option>
                        <option value="unreplied" @selected(request('filter') === 'unreplied')>بحاجة إلى رد</option>
                    </select>
                </div>
                <div class="flex gap-2 lg:col-span-3">
                    <button type="submit"
                        class="inline-flex h-11 flex-1 items-center justify-center rounded-lg bg-ink px-4 text-sm font-medium text-brass-soft transition hover:bg-brass hover:text-white">
                        تصفية
                    </button>
                    @if (request()->hasAny(['search', 'filter']))
                        <a href="{{ route('admin.contact-messages.index') }}"
                            class="inline-flex h-11 items-center justify-center rounded-lg border border-line px-4 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                            مسح
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <section class="mt-6 overflow-hidden rounded-lg border border-line bg-surface">
            <div class="divide-y divide-line">
                @forelse ($messages as $message)
                    <a href="{{ route('admin.contact-messages.show', $message) }}"
                        class="flex flex-wrap items-center gap-4 p-5 transition hover:bg-paper/45">
                        @if (! $message->is_read)
                            <span class="h-2 w-2 shrink-0 rounded-full bg-brass" title="غير مقروءة"></span>
                        @else
                            <span class="h-2 w-2 shrink-0 rounded-full bg-transparent"></span>
                        @endif

                        <div class="min-w-0 flex-1">
                            <p class="{{ ! $message->is_read ? 'font-semibold text-ink' : 'font-medium text-ink' }}">
                                {{ $message->name }}
                                @if ($message->subject)
                                    <span class="text-ink-soft"> — {{ $message->subject }}</span>
                                @endif
                            </p>
                            <p class="mt-1 line-clamp-1 max-w-xl text-xs text-ink-soft">{{ $message->message }}</p>
                        </div>

                        @if ($message->is_replied)
                            <span class="inline-flex shrink-0 rounded-full border border-forest/15 bg-forest/5 px-2.5 py-1 text-xs font-medium text-forest">
                                تم الرد
                            </span>
                        @else
                            <span class="inline-flex shrink-0 rounded-full border border-line bg-paper px-2.5 py-1 text-xs font-medium text-ink-soft">
                                بانتظار الرد
                            </span>
                        @endif

                        <span class="shrink-0 text-xs text-ink-soft">{{ $message->created_at?->diffForHumans() }}</span>
                    </a>
                @empty
                    <div class="px-5 py-14 text-center">
                        <p class="font-medium text-ink">لا توجد رسائل مطابقة</p>
                        <p class="mt-2 text-sm text-ink-soft">جرّب تعديل البحث أو الفلاتر الحالية.</p>
                    </div>
                @endforelse
            </div>
        </section>

        @if ($messages->hasPages())
            <div class="mt-6 flex flex-col gap-3 border-t border-line pt-5 text-sm text-ink-soft sm:flex-row sm:items-center sm:justify-between">
                <p>عرض {{ $messages->firstItem() }} - {{ $messages->lastItem() }} من {{ $messages->total() }}</p>
                <div class="flex items-center gap-2">
                    @if ($messages->onFirstPage())
                        <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">السابق</span>
                    @else
                        <a href="{{ $messages->previousPageUrl() }}" class="rounded-lg border border-line px-4 py-2 transition hover:bg-surface hover:text-ink">السابق</a>
                    @endif
                    @if ($messages->hasMorePages())
                        <a href="{{ $messages->nextPageUrl() }}" class="rounded-lg border border-line px-4 py-2 transition hover:bg-surface hover:text-ink">التالي</a>
                    @else
                        <span class="rounded-lg border border-line px-4 py-2 text-ink-soft/50">التالي</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-admin.layouts.app>
