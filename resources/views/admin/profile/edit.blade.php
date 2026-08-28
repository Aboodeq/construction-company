<x-admin.layouts.app title="الملف الشخصي">
    <div class="mx-auto w-full max-w-3xl">
        <div class="mb-8">
            <h2 class="font-display text-2xl font-semibold text-ink">الملف الشخصي</h2>
            <div class="mt-2 flex items-center gap-1.5 text-ink-soft" aria-hidden="true">
                @for ($i = 0; $i < 24; $i++)
                    <span class="h-2 w-px bg-line"></span>
                @endfor
            </div>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-ink-soft">
                إدارة بيانات حسابك الشخصي وكلمة المرور المستخدمة لتسجيل الدخول إلى لوحة التحكم.
            </p>
        </div>

        <div class="space-y-6">
            <section class="rounded-lg border border-line bg-surface p-6">
                @include('admin.profile.partials.update-profile-information-form')
            </section>

            <section class="rounded-lg border border-line bg-surface p-6">
                @include('admin.profile.partials.update-password-form')
            </section>
        </div>
    </div>
</x-admin.layouts.app>
