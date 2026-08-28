<x-guest-layout title="إعادة تعيين كلمة المرور">
    <p class="reveal reveal-d1 text-xs font-medium uppercase tracking-widest text-brass">استعادة الدخول</p>
    <h2 class="reveal reveal-d2 mt-3 font-display text-4xl font-semibold text-ink text-balance">إعادة تعيين كلمة المرور</h2>
    <p class="reveal reveal-d3 mt-3 text-sm leading-6 text-ink-soft">اختر كلمة مرور جديدة للمتابعة إلى لوحة التحكم.</p>

    <form method="POST" action="{{ route('password.store') }}" class="mt-9 space-y-6">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="reveal reveal-d4">
            <x-input-label for="email" value="البريد الإلكتروني" />
            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required
                autofocus autocomplete="username" class="transition focus:scale-[1.01]" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="reveal reveal-d5">
            <x-input-label for="password" value="كلمة المرور الجديدة" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password"
                class="transition focus:scale-[1.01]" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="reveal reveal-d6">
            <x-input-label for="password_confirmation" value="تأكيد كلمة المرور" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" class="transition focus:scale-[1.01]" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <x-primary-button class="btn-shimmer reveal reveal-d7 w-full">
            إعادة تعيين كلمة المرور
        </x-primary-button>
    </form>
</x-guest-layout>
