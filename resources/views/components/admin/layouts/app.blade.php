<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-paper">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'لوحة التحكم' }} - {{ setting('site_name', config('app.name')) }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|fraunces:500,600,700,800,900&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-ink" x-data="{ sidebarOpen: false, isDesktop: false }" x-init="
    const mediaQuery = window.matchMedia('(min-width: 1024px)');
    const syncSidebar = () => {
        isDesktop = mediaQuery.matches;
        sidebarOpen = mediaQuery.matches;
    };
    syncSidebar();
    if (mediaQuery.addEventListener) {
        mediaQuery.addEventListener('change', syncSidebar);
    } else {
        mediaQuery.addListener(syncSidebar);
    }
" @keydown.escape.window="sidebarOpen = false">

    <div class="flex h-full min-h-screen">

        @include('admin.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col transition-[margin-right] duration-300 ease-in-out"
            :class="sidebarOpen ? 'lg:mr-72' : 'lg:mr-0'">

            @include('admin.partials.header')

            @if (session('success'))
                <div
                    class="mx-6 mt-6 flex items-center gap-2 rounded-lg border border-forest/15 bg-forest/5 px-4 py-3 text-sm text-forest">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mx-6 mt-6 flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-10 lg:py-8">
                {{ $slot }}
            </main>

        </div>
    </div>

</body>

</html>
