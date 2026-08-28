@php
    $statusLabels = ['open' => 'مفتوحة', 'closed' => 'مغلقة'];
@endphp

<x-admin.layouts.app title="محادثة مع {{ $conversation->visitor_name ?: 'زائر' }}">
    <div
        x-data="{
            messages: @js($conversation->messages->map(fn ($m) => ['id' => $m->id, 'sender_type' => $m->sender_type, 'message' => $m->message, 'created_at' => $m->created_at->format('H:i')])),
            lastId: {{ $conversation->messages->max('id') ?? 0 }},
            draft: '',
            sending: false,

            async poll() {
                const res = await fetch(`{{ route('admin.chats.poll', $conversation) }}?after=${this.lastId}`, {
                    headers: { Accept: 'application/json' },
                });
                if (!res.ok) return;
                const data = await res.json();
                if (data.messages.length) {
                    data.messages.forEach(m => {
                        this.messages.push({ ...m, created_at: new Date(m.created_at).toLocaleTimeString('ar', { hour: '2-digit', minute: '2-digit' }) });
                        this.lastId = Math.max(this.lastId, m.id);
                    });
                    this.$nextTick(() => this.scrollToBottom());
                }
            },

            async send() {
                const text = this.draft.trim();
                if (!text || this.sending) return;
                this.sending = true;
                const res = await fetch('{{ route('admin.chats.reply', $conversation) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ message: text }),
                });
                if (res.ok) {
                    this.draft = '';
                    await this.poll();
                }
                this.sending = false;
            },

            scrollToBottom() {
                const el = this.$refs.thread;
                if (el) el.scrollTop = el.scrollHeight;
            },
        }"
        x-init="scrollToBottom(); setInterval(() => poll(), 5000)"
        class="mx-auto flex w-full max-w-3xl flex-col"
    >
        <div class="mb-6 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.chats.index') }}"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-ink-soft transition hover:bg-surface hover:text-ink">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-display text-xl font-semibold text-ink">
                        {{ $conversation->visitor_name ?: 'زائر بدون اسم' }}
                    </h2>
                    <p class="mt-0.5 text-xs text-ink-soft">
                        {{ $conversation->visitor_email ?? 'لم يقدّم بريدًا إلكترونيًا' }}
                        @if ($conversation->assignedTo)
                            · مسندة إلى {{ $conversation->assignedTo->name }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @can('chats.reply')
                    <form method="POST" action="{{ route('admin.chats.toggle-status', $conversation) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex h-9 items-center rounded-lg border border-line px-3 text-xs font-medium text-ink-soft transition hover:bg-surface hover:text-ink">
                            {{ $conversation->status === 'open' ? 'إغلاق المحادثة' : 'إعادة فتح المحادثة' }}
                        </button>
                    </form>
                @endcan
                @can('chats.delete')
                    <x-admin.confirm-form
                        :action="route('admin.chats.destroy', $conversation)"
                        method="DELETE"
                        title="حذف المحادثة"
                        message="سيتم حذف هذه المحادثة وكل رسائلها نهائيًا. هل تريد المتابعة؟"
                        triggerLabel="حذف المحادثة"
                        class="inline-flex h-9 items-center rounded-lg border border-red-200 px-3 text-xs font-medium text-red-600 transition hover:bg-red-50"
                    >
                        حذف
                    </x-admin.confirm-form>
                @endcan
            </div>
        </div>

        <section class="flex h-[32rem] flex-col overflow-hidden rounded-lg border border-line bg-surface">
            <div x-ref="thread" class="flex-1 space-y-3 overflow-y-auto bg-paper p-5">
                <template x-for="m in messages" :key="m.id">
                    <div :class="m.sender_type === 'visitor' ? 'flex justify-start' : 'flex justify-end'">
                        <div :class="m.sender_type === 'visitor' ? 'max-w-[70%]' : 'max-w-[70%] text-left'">
                            <p :class="m.sender_type === 'visitor'
                                    ? 'rounded-2xl rounded-bs-sm bg-ink px-4 py-2.5 text-sm text-brass-soft'
                                    : 'rounded-2xl rounded-be-sm border border-line bg-surface px-4 py-2.5 text-sm text-ink'"
                                x-text="m.message"></p>
                            <p :class="m.sender_type === 'visitor' ? 'mt-1 text-[11px] text-ink-soft' : 'mt-1 text-[11px] text-ink-soft text-left'" x-text="m.created_at"></p>
                        </div>
                    </div>
                </template>
            </div>

            @can('chats.reply')
                <form @submit.prevent="send()" class="flex items-center gap-2 border-t border-line bg-surface p-4">
                    <input x-model="draft" type="text" placeholder="اكتب ردك هنا..."
                        class="h-11 flex-1 rounded-lg border-line bg-paper text-sm text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">
                    <button type="submit" :disabled="sending"
                        class="inline-flex h-11 shrink-0 items-center gap-2 rounded-lg bg-ink px-5 text-sm font-medium text-brass-soft transition hover:bg-brass hover:text-white disabled:opacity-50">
                        إرسال
                    </button>
                </form>
            @else
                <div class="border-t border-line bg-surface p-4 text-center text-xs text-ink-soft">
                    لا تملك صلاحية الرد على المحادثات.
                </div>
            @endcan
        </section>
    </div>
</x-admin.layouts.app>
