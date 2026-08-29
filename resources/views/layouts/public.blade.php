@php
    $navLinks = [
        '#services' => 'خدماتنا',
        '#projects' => 'مشاريعنا',
        '#testimonials' => 'آراء العملاء',
        '#faq' => 'الأسئلة الشائعة',
        '#contact' => 'تواصل معنا',
    ];
    $socialLinks = collect([
        'facebook_url' => 'facebook',
        'instagram_url' => 'instagram',
        'twitter_url' => 'twitter',
        'linkedin_url' => 'linkedin',
        'youtube_url' => 'youtube',
    ])->map(fn ($icon, $key) => ['url' => setting($key), 'icon' => $icon])->filter(fn ($s) => $s['url']);
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth bg-paper">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ setting('default_meta_description', '') }}">

    <title>{{ $title ?? setting('default_meta_title', setting('site_name', config('app.name'))) }}</title>

    @if (setting('favicon'))
        <link rel="icon" href="{{ asset('storage/'.setting('favicon')) }}">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=inter:400,500,600,700|fraunces:500,600,700,800|ibm-plex-sans-arabic:400,500,600,700|reem-kufi:500,600,700&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-paper font-sans text-ink antialiased">

    <header x-data="{ mobileOpen: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 12"
        class="sticky top-0 z-40 border-b transition-colors duration-300"
        :class="scrolled ? 'border-line bg-surface/90 backdrop-blur' : 'border-transparent bg-transparent'">
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-ink font-display text-base font-bold text-brass-soft">
                    {{ mb_substr(setting('site_name', 'ش'), 0, 1) }}
                </span>
                <span class="font-display text-base font-semibold text-ink">{{ setting('site_name', config('app.name')) }}</span>
            </a>

            <nav class="hidden items-center gap-8 lg:flex">
                @foreach ($navLinks as $href => $label)
                    <a href="{{ $href }}" class="text-sm font-medium text-ink-soft transition hover:text-brass">{{ $label }}</a>
                @endforeach
            </nav>

            <div class="hidden items-center gap-5 lg:flex">
                @if (setting('contact_phone'))
                    <a href="tel:{{ setting('contact_phone') }}" class="flex items-center gap-2 text-sm font-medium text-ink-soft transition hover:text-brass">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                        {{ setting('contact_phone') }}
                    </a>
                @endif
                <a href="#contact" class="btn-shimmer inline-flex h-11 items-center justify-center rounded-lg bg-ink px-5 text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                    اطلب عرض سعر
                </a>
            </div>

            <button type="button" @click="mobileOpen = true" aria-label="فتح القائمة"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-ink lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileOpen" x-cloak x-transition.opacity @keydown.escape.window="mobileOpen = false"
            class="fixed inset-0 z-50 bg-ink/50 lg:hidden" @click="mobileOpen = false"></div>
        <div x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 right-0 z-50 w-72 max-w-[85vw] bg-surface p-6 shadow-2xl lg:hidden">
            <div class="flex items-center justify-between">
                <span class="font-display text-base font-semibold text-ink">{{ setting('site_name', config('app.name')) }}</span>
                <button type="button" @click="mobileOpen = false" aria-label="إغلاق القائمة" class="text-ink-soft">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <nav class="mt-8 flex flex-col gap-1">
                @foreach ($navLinks as $href => $label)
                    <a href="{{ $href }}" @click="mobileOpen = false"
                        class="rounded-lg px-3 py-3 text-sm font-medium text-ink transition hover:bg-paper">{{ $label }}</a>
                @endforeach
            </nav>
            <a href="#contact" @click="mobileOpen = false"
                class="mt-6 inline-flex h-11 w-full items-center justify-center rounded-lg bg-ink text-sm font-semibold text-brass-soft">
                اطلب عرض سعر
            </a>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="border-t border-line bg-ink">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-6 py-16 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brass-soft font-display text-base font-bold text-ink">
                        {{ mb_substr(setting('site_name', 'ش'), 0, 1) }}
                    </span>
                    <span class="font-display text-base font-semibold text-brass-soft">{{ setting('site_name', config('app.name')) }}</span>
                </div>
                @if (setting('site_tagline'))
                    <p class="mt-4 text-sm leading-7 text-brass-soft/60">{{ setting('site_tagline') }}</p>
                @endif

                @if ($socialLinks->isNotEmpty())
                    @php
                        $socialPaths = [
                            'facebook' => 'M17 3a1 1 0 011 1v3h-2a1 1 0 00-1 1v2h3l-.5 3H15v7h-3v-7H9v-3h3V8a4 4 0 014-4h1z',
                            'instagram' => 'M12 7.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9zM12 9a3 3 0 110 6 3 3 0 010-6zM17.25 6a.9.9 0 100 1.8.9.9 0 000-1.8zM3 8.4A5.4 5.4 0 018.4 3h7.2A5.4 5.4 0 0121 8.4v7.2a5.4 5.4 0 01-5.4 5.4H8.4A5.4 5.4 0 013 15.6V8.4zm5.4-3.6a3.6 3.6 0 00-3.6 3.6v7.2a3.6 3.6 0 003.6 3.6h7.2a3.6 3.6 0 003.6-3.6V8.4a3.6 3.6 0 00-3.6-3.6H8.4z',
                            'twitter' => 'M20 5.5c-.6.3-1.3.5-2 .6.7-.4 1.3-1.2 1.5-2-.7.4-1.4.7-2.2.9A3.5 3.5 0 0011.5 8c0 .3 0 .5.1.8-2.9-.2-5.5-1.6-7.2-3.7-.3.5-.5 1.1-.5 1.7 0 1.2.6 2.2 1.5 2.8-.5 0-1-.2-1.5-.4v.1c0 1.7 1.2 3.1 2.8 3.4-.3.1-.6.1-1 .1-.2 0-.5 0-.7-.1.5 1.4 1.8 2.4 3.4 2.5A7 7 0 014 16.9 9.9 9.9 0 009.3 18.5c6.4 0 9.9-5.3 9.9-9.9v-.5c.7-.5 1.3-1.1 1.8-1.8-.6.3-1.3.5-2 .6z',
                            'linkedin' => 'M6.94 8.5H4V19h2.94V8.5zM5.47 7.25a1.7 1.7 0 100-3.4 1.7 1.7 0 000 3.4zM20 12.7c0-3-1.6-4.4-3.75-4.4-1.73 0-2.5.95-2.93 1.62V8.5H10.4c.04.85 0 10.5 0 10.5h2.93v-5.86c0-.31.02-.63.12-.85.26-.63.85-1.29 1.85-1.29 1.3 0 1.83.99 1.83 2.44V19H20v-6.3z',
                            'youtube' => 'M21.6 7.2s-.2-1.5-.8-2.1c-.8-.8-1.7-.8-2.1-.9C15.9 4 12 4 12 4h0s-3.9 0-6.7.2c-.4 0-1.3.1-2.1.9-.6.6-.8 2.1-.8 2.1S2.2 9 2.2 10.7v1.6c0 1.8.2 3.5.2 3.5s.2 1.5.8 2.1c.8.8 1.9.8 2.3.9 1.7.2 7.1.2 7.5.2 0 0 3.9 0 6.7-.2.4 0 1.3-.1 2.1-.9.6-.6.8-2.1.8-2.1s.2-1.7.2-3.5v-1.6c0-1.7-.2-3.5-.2-3.5zM9.9 14.6V8.9l5.4 2.9-5.4 2.8z',
                        ];
                    @endphp
                    <div class="mt-5 flex items-center gap-2">
                        @foreach ($socialLinks as $social)
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener"
                                class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/5 text-brass-soft/70 transition hover:bg-brass hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="{{ $socialPaths[$social['icon']] ?? '' }}" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-widest text-brass">روابط سريعة</p>
                <ul class="mt-4 space-y-3 text-sm">
                    @foreach ($navLinks as $href => $label)
                        <li><a href="{{ $href }}" class="text-brass-soft/70 transition hover:text-brass-soft">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-widest text-brass">تواصل معنا</p>
                <ul class="mt-4 space-y-3 text-sm text-brass-soft/70">
                    @if (setting('contact_phone'))
                        <li><a href="tel:{{ setting('contact_phone') }}" class="hover:text-brass-soft">{{ setting('contact_phone') }}</a></li>
                    @endif
                    @if (setting('contact_email'))
                        <li><a href="mailto:{{ setting('contact_email') }}" class="hover:text-brass-soft">{{ setting('contact_email') }}</a></li>
                    @endif
                    @if (setting('contact_address'))
                        <li>{{ setting('contact_address') }}</li>
                    @endif
                </ul>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-widest text-brass">ساعات العمل</p>
                <p class="mt-4 text-sm leading-7 text-brass-soft/70">
                    {{ setting('business_hours', 'السبت - الخميس: 9 صباحًا - 6 مساءً') }}
                </p>
            </div>
        </div>

        <div class="border-t border-white/10">
            <div class="mx-auto flex max-w-7xl flex-col items-center gap-2 px-6 py-6 text-xs text-brass-soft/50 sm:flex-row sm:justify-between">
                <p>© {{ now()->year }} {{ setting('site_name', config('app.name')) }}. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <x-chat-widget />
</body>

</html>
