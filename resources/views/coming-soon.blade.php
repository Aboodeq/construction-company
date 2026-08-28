<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-paper">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ setting('site_name', config('app.name')) }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=inter:400,500,600|ibm-plex-sans-arabic:400,500,600,700|fraunces:600,700|reem-kufi:600,700&display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex h-full min-h-screen flex-col items-center justify-center bg-paper px-6 font-sans text-ink antialiased">
    <div class="w-full max-w-md text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-ink font-display text-2xl font-bold text-brass-soft">
            {{ mb_substr(setting('site_name', 'ش'), 0, 1) }}
        </div>

        <h1 class="mt-6 font-display text-3xl font-semibold text-ink text-balance">
            {{ setting('site_name', config('app.name')) }}
        </h1>

        <div class="mx-auto mt-4 flex items-center justify-center gap-1.5 text-ink-soft" aria-hidden="true">
            @for ($i = 0; $i < 18; $i++)
                <span class="h-2 w-px bg-line"></span>
            @endfor
        </div>

        <p class="mt-5 text-sm leading-7 text-ink-soft">
            نعمل حاليًا على بناء موقعنا الجديد. تواصل معنا عبر المحادثة المباشرة في أسفل الصفحة
            وسنكون سعداء بالرد على استفساراتك.
        </p>
    </div>

    <x-chat-widget />
</body>

</html>
