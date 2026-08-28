<header>
    <h3 class="font-display text-lg font-semibold text-ink">البيانات الأساسية</h3>
    <p class="mt-1 text-sm text-ink-soft">الاسم والبريد الإلكتروني المستخدمان لحسابك.</p>
</header>

<form method="POST" action="{{ route('admin.profile.update') }}" class="mt-6 space-y-5">
    @csrf
    @method('patch')

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <x-input-label for="name" value="الاسم" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="البريد الإلكتروني" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>
    </div>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="rounded-lg border border-brass/20 bg-brass-soft/40 px-4 py-3 text-sm text-ink">
            بريدك الإلكتروني غير مفعّل.
            <button form="send-verification" class="font-medium text-brass underline hover:text-ink">
                إعادة إرسال رابط التفعيل
            </button>

            @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-forest">تم إرسال رابط تفعيل جديد إلى بريدك الإلكتروني.</p>
            @endif
        </div>

        <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
            @csrf
        </form>
    @endif

    <div class="flex items-center gap-4">
        <x-primary-button>حفظ التغييرات</x-primary-button>

        @if (session('status') === 'profile-updated')
            <p class="text-sm font-medium text-forest">تم الحفظ بنجاح.</p>
        @endif
    </div>
</form>
