<x-guest-layout title="نسيت كلمة المرور">
    <p class="reveal reveal-d1 text-xs font-medium uppercase tracking-widest text-brass">استعادة الدخول</p>
    <h2 class="reveal reveal-d2 mt-3 font-display text-4xl font-semibold text-ink text-balance">نسيت كلمة المرور؟</h2>
    <p class="reveal reveal-d3 mt-3 text-sm leading-6 text-ink-soft">
        لا مشكلة، أدخل بريدك الإلكتروني وسنرسل لك رابطًا لإعادة تعيين كلمة المرور.
    </p>

    <x-auth-session-status class="reveal reveal-d3 mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-9 space-y-6">
        @csrf

        <div class="reveal reveal-d4">
            <x-input-label for="email" value="البريد الإلكتروني" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                placeholder="name@example.com" class="transition focus:scale-[1.01]" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button class="btn-shimmer reveal reveal-d5 w-full">
            إرسال رابط إعادة التعيين
        </x-primary-button>

        <a href="{{ route('login') }}"
            class="reveal reveal-d6 block text-center text-sm font-medium text-ink-soft transition hover:text-brass">
            العودة إلى تسجيل الدخول
        </a>
    </form>
</x-guest-layout>
