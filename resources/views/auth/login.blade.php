<x-guest-layout title="تسجيل الدخول">
    <p class="reveal reveal-d1 text-xs font-medium uppercase tracking-widest text-brass">تسجيل الدخول</p>
    <h2 class="reveal reveal-d2 mt-3 font-display text-4xl font-semibold text-ink text-balance">مرحبًا بعودتك</h2>
    <p class="reveal reveal-d3 mt-3 text-sm leading-6 text-ink-soft">أدخل بيانات الدخول الخاصة بك للوصول إلى لوحة التحكم.</p>

    <x-auth-session-status class="reveal reveal-d3 mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-9 space-y-6">
        @csrf

        <div class="reveal reveal-d4">
            <x-input-label for="email" value="البريد الإلكتروني" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                autocomplete="username" placeholder="name@example.com"
                class="transition focus:scale-[1.01]" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="reveal reveal-d5">
            <x-input-label for="password" value="كلمة المرور" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="••••••••" class="transition focus:scale-[1.01]" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="reveal reveal-d6 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-ink-soft">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-line text-brass focus:ring-brass">
                تذكرني
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-brass transition hover:text-ink">
                    نسيت كلمة المرور؟
                </a>
            @endif
        </div>

        <x-primary-button class="btn-shimmer reveal reveal-d7 w-full">
            تسجيل الدخول
        </x-primary-button>
    </form>
</x-guest-layout>
