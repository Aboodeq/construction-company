<x-guest-layout title="تفعيل البريد الإلكتروني">
    <p class="reveal reveal-d1 text-xs font-medium uppercase tracking-widest text-brass">خطوة أخيرة</p>
    <h2 class="reveal reveal-d2 mt-3 font-display text-4xl font-semibold text-ink text-balance">فعّل بريدك الإلكتروني</h2>
    <p class="reveal reveal-d3 mt-3 text-sm leading-6 text-ink-soft">
        أرسلنا رابط تفعيل إلى بريدك الإلكتروني، الرجاء الضغط عليه لتفعيل حسابك. إن لم تصلك الرسالة يمكنك طلب رابط جديد.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="reveal reveal-d3 mt-6 flex items-center gap-2 rounded-lg border border-forest/15 bg-forest/5 px-4 py-3 text-sm text-forest">
            تم إرسال رابط تفعيل جديد إلى بريدك الإلكتروني.
        </div>
    @endif

    <div class="reveal reveal-d4 mt-9 flex items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="btn-shimmer">
                إعادة إرسال رابط التفعيل
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm font-medium text-ink-soft transition hover:text-brass">
                تسجيل الخروج
            </button>
        </form>
    </div>
</x-guest-layout>
