<header>
    <h3 class="font-display text-lg font-semibold text-ink">كلمة المرور</h3>
    <p class="mt-1 text-sm text-ink-soft">يُفضّل استخدام كلمة مرور طويلة وعشوائية للحفاظ على أمان حسابك.</p>
</header>

<form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-5">
    @csrf
    @method('put')

    <div>
        <x-input-label for="current_password" value="كلمة المرور الحالية" />
        <x-text-input id="current_password" name="current_password" type="password" autocomplete="current-password" />
        <x-input-error :messages="$errors->updatePassword->get('current_password')" />
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <x-input-label for="password" value="كلمة المرور الجديدة" />
            <x-text-input id="password" name="password" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="تأكيد كلمة المرور" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>تحديث كلمة المرور</x-primary-button>

        @if (session('status') === 'password-updated')
            <p class="text-sm font-medium text-forest">تم تحديث كلمة المرور.</p>
        @endif
    </div>
</form>
