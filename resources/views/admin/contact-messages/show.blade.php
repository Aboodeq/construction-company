<x-admin.layouts.app title="تفاصيل الرسالة">
    <div class="mx-auto w-full max-w-3xl">
        <div class="mb-8">
            <a href="{{ route('admin.contact-messages.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-ink-soft transition hover:text-brass">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                        clip-rule="evenodd" />
                </svg>
                العودة إلى رسائل التواصل
            </a>
            <h2 class="mt-4 font-display text-2xl font-semibold text-ink">
                {{ $contactMessage->subject ?? 'رسالة بدون موضوع' }}
            </h2>
            <p class="mt-2 text-sm text-ink-soft">
                من {{ $contactMessage->name }} · {{ $contactMessage->created_at?->diffForHumans() }}
            </p>
        </div>

        <section class="rounded-lg border border-line bg-surface p-6">
            <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium text-ink-soft">الاسم</dt>
                    <dd class="mt-1 text-ink">{{ $contactMessage->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-ink-soft">البريد الإلكتروني</dt>
                    <dd class="mt-1 text-ink">
                        <a href="mailto:{{ $contactMessage->email }}" class="hover:text-brass">{{ $contactMessage->email }}</a>
                    </dd>
                </div>
                @if ($contactMessage->phone)
                    <div>
                        <dt class="text-xs font-medium text-ink-soft">الهاتف</dt>
                        <dd class="mt-1 text-ink">
                            <a href="tel:{{ $contactMessage->phone }}" class="hover:text-brass">{{ $contactMessage->phone }}</a>
                        </dd>
                    </div>
                @endif
            </dl>

            <div class="mt-5 border-t border-line pt-5">
                <dt class="text-xs font-medium text-ink-soft">الرسالة</dt>
                <dd class="mt-2 whitespace-pre-line text-sm leading-7 text-ink">{{ $contactMessage->message }}</dd>
            </div>

            <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-line pt-6">
                <div class="flex items-center gap-2">
                    <a href="mailto:{{ $contactMessage->email }}"
                        class="inline-flex h-11 items-center justify-center gap-1.5 rounded-lg bg-ink px-5 text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        الرد عبر البريد
                    </a>

                    <form method="POST" action="{{ route('admin.contact-messages.toggle-replied', $contactMessage) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex h-11 items-center justify-center rounded-lg border border-line px-5 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                            {{ $contactMessage->is_replied ? 'إلغاء علامة "تم الرد"' : 'وضع علامة "تم الرد"' }}
                        </button>
                    </form>
                </div>

                @can('contact-messages.delete')
                    <x-admin.confirm-form
                        :action="route('admin.contact-messages.destroy', $contactMessage)"
                        title="حذف الرسالة"
                        message="سيتم حذف هذه الرسالة نهائيًا. هل تريد المتابعة؟"
                        class="h-11 px-5 text-sm font-medium">
                        حذف الرسالة
                    </x-admin.confirm-form>
                @endcan
            </div>
        </section>
    </div>
</x-admin.layouts.app>
