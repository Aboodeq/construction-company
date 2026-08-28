@props(['action', 'toName', 'toEmail', 'defaultSubject', 'replies'])

<section x-data="{ open: {{ $replies->isEmpty() ? 'true' : 'false' }} }" class="rounded-lg border border-line bg-surface p-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="font-display text-lg font-semibold text-ink">الرد عبر البريد الإلكتروني</h3>
            <p class="mt-1 text-xs text-ink-soft">سيصل الرد إلى {{ $toEmail }}</p>
        </div>
        <button type="button" @click="open = !open"
            class="inline-flex h-10 items-center justify-center gap-1.5 rounded-lg bg-ink px-4 text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
            <span x-text="open ? 'إخفاء النموذج' : 'كتابة رد'"></span>
        </button>
    </div>

    <form x-show="open" x-cloak x-transition method="POST" action="{{ $action }}" class="mt-5 space-y-4 border-t border-line pt-5">
        @csrf

        <div>
            <x-input-label for="reply-subject-{{ $action }}" value="الموضوع" />
            <x-text-input id="reply-subject-{{ $action }}" name="subject" type="text" required
                :value="old('subject', $defaultSubject)" />
            <x-input-error :messages="$errors->get('subject')" />
        </div>

        <div>
            <x-input-label for="reply-message-{{ $action }}" value="نص الرسالة" />
            <textarea id="reply-message-{{ $action }}" name="message" rows="5" required
                placeholder="اكتب ردك هنا..."
                class="w-full rounded-lg border-line bg-paper text-sm leading-7 text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->get('message')" />
        </div>

        <button type="submit"
            class="inline-flex h-11 items-center justify-center gap-1.5 rounded-lg bg-ink px-5 text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
            إرسال البريد
        </button>
    </form>

    @if ($replies->isNotEmpty())
        <div class="mt-5 space-y-3 border-t border-line pt-5">
            <p class="text-xs font-medium text-ink-soft">سجل الردود المرسلة ({{ $replies->count() }})</p>
            @foreach ($replies as $reply)
                <details class="rounded-lg border border-line bg-paper/60 px-4 py-3">
                    <summary class="cursor-pointer text-sm font-medium text-ink">
                        {{ $reply->subject }}
                        <span class="mr-1 text-xs font-normal text-ink-soft">
                            · {{ $reply->sender?->name ?? 'مستخدم محذوف' }} · {{ $reply->created_at?->diffForHumans() }}
                        </span>
                    </summary>
                    <p class="mt-2 whitespace-pre-line text-sm leading-7 text-ink-soft">{{ $reply->body }}</p>
                </details>
            @endforeach
        </div>
    @endif
</section>
