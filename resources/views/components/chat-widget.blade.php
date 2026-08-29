@php
    $siteInitial = mb_substr(setting('site_name', 'ش'), 0, 1);
@endphp

<div
    x-data="{
        open: false,
        token: null,
        conversationId: null,
        messages: [],
        draft: '',
        lastId: 0,
        unread: 0,
        sending: false,
        started: false,

        generateToken() {
            const bytes = new Uint8Array(20);
            crypto.getRandomValues(bytes);
            return Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join('');
        },

        headers() {
            return {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            };
        },

        async start() {
            this.token = localStorage.getItem('chat_visitor_token');
            if (!this.token) {
                this.token = this.generateToken();
                localStorage.setItem('chat_visitor_token', this.token);
            }
            const res = await fetch('{{ route('chat.start') }}', {
                method: 'POST', headers: this.headers(),
                body: JSON.stringify({ visitor_token: this.token }),
            });
            const data = await res.json();
            this.conversationId = data.conversation_id;
            this.messages = data.messages;
            if (this.messages.length) this.lastId = Math.max(...this.messages.map(m => m.id));
            this.started = true;
            this.$nextTick(() => this.scrollToBottom());
        },

        async poll() {
            if (!this.conversationId) return;
            const url = `/chat/${this.conversationId}/poll?visitor_token=${this.token}&after=${this.lastId}`;
            const res = await fetch(url, { headers: { Accept: 'application/json' } });
            if (!res.ok) return;
            const data = await res.json();
            if (data.messages.length) {
                data.messages.forEach(m => {
                    this.messages.push(m);
                    this.lastId = Math.max(this.lastId, m.id);
                    if (m.sender_type === 'admin' && !this.open) this.unread++;
                });
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        async send() {
            const text = this.draft.trim();
            if (!text || this.sending) return;
            this.sending = true;
            this.draft = '';
            const res = await fetch(`/chat/${this.conversationId}/send`, {
                method: 'POST', headers: this.headers(),
                body: JSON.stringify({ visitor_token: this.token, message: text }),
            });
            const data = await res.json();
            this.messages.push(data.message);
            this.lastId = Math.max(this.lastId, data.message.id);
            this.sending = false;
            this.$nextTick(() => this.scrollToBottom());
        },

        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.unread = 0;
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        scrollToBottom() {
            const el = this.$refs.thread;
            if (el) el.scrollTop = el.scrollHeight;
        },
    }"
    x-init="start(); setInterval(() => poll(), 6000)"
    @open-chat-widget.window="open = true; unread = 0; $nextTick(() => scrollToBottom())"
    class="fixed bottom-6 left-6 z-50"
>
    {{-- Toggle bubble --}}
    <button type="button" @click="toggle()"
        class="relative flex h-14 w-14 items-center justify-center rounded-full bg-ink text-brass-soft shadow-xl transition hover:bg-brass hover:text-white">
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
        </svg>
        <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" clip-rule="evenodd" />
        </svg>

        <span x-show="unread > 0" x-cloak x-text="unread"
            class="absolute -top-1 -left-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-brass px-1 text-[11px] font-bold text-white"></span>
    </button>

    {{-- Panel --}}
    <div x-show="open" x-cloak x-transition
        class="absolute bottom-18 left-0 flex h-112 w-80 flex-col overflow-hidden rounded-2xl border border-line bg-surface shadow-2xl">

        <div class="flex items-center gap-3 border-b border-line bg-ink px-4 py-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brass-soft font-display text-sm font-bold text-ink">
                {{ $siteInitial }}
            </div>
            <div class="min-w-0">
                <p class="truncate font-display text-sm font-semibold text-brass-soft">{{ setting('site_name', 'الدردشة المباشرة') }}</p>
                <p class="text-xs text-brass-soft/60">عادةً ما نرد خلال دقائق</p>
            </div>
        </div>

        <div x-ref="thread" class="flex-1 space-y-3 overflow-y-auto bg-paper p-4">
            <template x-if="messages.length === 0">
                <p class="text-center text-xs text-ink-soft">أهلًا بك! اكتب رسالتك وسنرد عليك قريبًا.</p>
            </template>
            <template x-for="m in messages" :key="m.id">
                <div :class="m.sender_type === 'visitor' ? 'flex justify-start' : 'flex justify-end'">
                    <p :class="m.sender_type === 'visitor'
                            ? 'max-w-[85%] rounded-2xl rounded-bs-sm bg-ink px-3.5 py-2 text-sm text-brass-soft'
                            : 'max-w-[85%] rounded-2xl rounded-be-sm border border-line bg-surface px-3.5 py-2 text-sm text-ink'"
                        x-text="m.message"></p>
                </div>
            </template>
        </div>

        <form @submit.prevent="send()" class="flex items-center gap-2 border-t border-line bg-surface p-3">
            <input x-model="draft" type="text" placeholder="اكتب رسالتك..."
                class="h-10 flex-1 rounded-lg border-line bg-paper text-sm text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">
            <button type="submit" :disabled="sending"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-ink text-brass-soft transition hover:bg-brass hover:text-white disabled:opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M3.105 2.289a.75.75 0 00-.826.95l1.414 4.925A1.5 1.5 0 005.135 9.25h6.115a.75.75 0 010 1.5H5.135a1.5 1.5 0 00-1.442 1.086l-1.414 4.926a.75.75 0 00.826.95 28.896 28.896 0 0015.293-7.154.75.75 0 000-1.115A28.897 28.897 0 003.105 2.289z" />
                </svg>
            </button>
        </form>
    </div>
</div>
