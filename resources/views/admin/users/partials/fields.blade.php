@php
    $roleLabels = ['admin' => 'مدير عام', 'editor' => 'محرر'];
    $currentRole = old('role', $user?->getRoleNames()->first());
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <x-input-label for="name" value="الاسم" />
        <x-text-input id="name" name="name" type="text" required autofocus
            :value="old('name', $user?->name)" />
        <x-input-error :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="email" value="البريد الإلكتروني" />
        <x-text-input id="email" name="email" type="email" required autocomplete="username"
            :value="old('email', $user?->email)" />
        <x-input-error :messages="$errors->get('email')" />
    </div>

    <div>
        <x-input-label for="password" :value="$user ? 'كلمة مرور جديدة (اختياري)' : 'كلمة المرور'" />
        <x-text-input id="password" name="password" type="password" :required="! $user" autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')" />
    </div>

    <div>
        <x-input-label for="password_confirmation" value="تأكيد كلمة المرور" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" :required="! $user"
            autocomplete="new-password" />
    </div>

    <div>
        <x-input-label for="role" value="الدور" />
        <select id="role" name="role" required
            class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
            @foreach ($roles as $role)
                <option value="{{ $role->name }}" @selected($currentRole === $role->name)>
                    {{ $roleLabels[$role->name] ?? $role->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('role')" />
        <p class="mt-2 text-xs text-ink-soft">
            لإنشاء دور مخصص بصلاحيات مختلفة، من صفحة
            <a href="{{ route('admin.roles.index') }}" class="text-brass hover:text-ink">الأدوار والصلاحيات</a>.
        </p>
    </div>

    <div class="flex items-end pb-2">
        <label for="is_active" class="inline-flex items-center gap-2.5 text-sm text-ink">
            <input id="is_active" name="is_active" type="checkbox" value="1"
                @checked(old('is_active', $user?->is_active ?? true))
                class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
            حساب نشط (يمكنه تسجيل الدخول)
        </label>
    </div>
</div>
