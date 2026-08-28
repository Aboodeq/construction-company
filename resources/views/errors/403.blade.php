<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-paper">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>لا تملك صلاحية الوصول - {{ setting('site_name', config('app.name')) }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=inter:400,500,600,700|ibm-plex-sans-arabic:400,500,600,700|reem-kufi:600,700&display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex h-full min-h-screen flex-col items-center justify-center bg-paper px-6 font-sans text-ink antialiased">
    <div class="w-full max-w-sm text-center">
        <p class="font-display text-7xl font-bold text-brass">403</p>
        <h1 class="mt-4 font-display text-2xl font-semibold text-ink">لا تملك صلاحية الوصول</h1>
        <p class="mt-3 text-sm leading-6 text-ink-soft">
            {{ $exception->getMessage() ?: 'حسابك لا يملك الصلاحية اللازمة للقيام بهذا الإجراء. تواصل مع مدير النظام إذا كنت بحاجة إلى وصول إضافي.' }}
        </p>

        <div class="mt-8 flex items-center justify-center gap-3">
            @auth
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex h-11 items-center justify-center rounded-lg bg-ink px-5 text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                    العودة إلى لوحة التحكم
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="inline-flex h-11 items-center justify-center rounded-lg bg-ink px-5 text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                    تسجيل الدخول
                </a>
            @endauth
        </div>
    </div>
</body>

</html>
