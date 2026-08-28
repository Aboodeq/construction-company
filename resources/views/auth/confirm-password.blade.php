<x-guest-layout title="تأكيد كلمة المرور">
    <p class="reveal reveal-d1 text-xs font-medium uppercase tracking-widest text-brass">تأكيد الهوية</p>
    <h2 class="reveal reveal-d2 mt-3 font-display text-4xl font-semibold text-ink text-balance">هذه منطقة محمية</h2>
    <p class="reveal reveal-d3 mt-3 text-sm leading-6 text-ink-soft">
        الرجاء تأكيد كلمة المرور الخاصة بك قبل المتابعة، وذلك حفاظًا على أمان لوحة التحكم.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-9 space-y-6">
        @csrf

        <div class="reveal reveal-d4">
            <x-input-label for="password" value="كلمة المرور" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                autofocus placeholder="••••••••" class="transition focus:scale-[1.01]" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <x-primary-button class="btn-shimmer reveal reveal-d5 w-full">
            تأكيد
        </x-primary-button>
    </form>
</x-guest-layout>
