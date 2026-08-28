<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-paper">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'تسجيل الدخول' }} - {{ setting('site_name', config('app.name')) }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|fraunces:500,600,700,800,900|ibm-plex-sans-arabic:400,500,600,700|reem-kufi:500,600,700&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-ink">
    <div class="grid h-screen grid-cols-1 overflow-hidden lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">

        {{-- Brand panel --}}
        <aside class="relative hidden overflow-y-auto bg-ink lg:flex lg:flex-col">

            {{-- Ambient drift + blueprint grid --}}
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div
                    class="blueprint-drift absolute -right-24 -top-24 h-96 w-96 rounded-full bg-brass/20 blur-3xl">
                </div>
                <div
                    class="blueprint-drift-slow absolute -bottom-32 -left-16 h-96 w-96 rounded-full bg-forest/40 blur-3xl">
                </div>
                <div class="blueprint-grid absolute inset-0"></div>
            </div>

            {{-- Blueprint corner marks --}}
            <svg class="reveal reveal-d1 pointer-events-none absolute right-8 top-8 h-10 w-10 text-brass/30"
                viewBox="0 0 40 40" fill="none">
                <polyline points="0,40 0,0 40,0" stroke="currentColor" stroke-width="1.5" class="draw-line" />
            </svg>
            <svg class="reveal reveal-d7 pointer-events-none absolute bottom-8 left-8 h-10 w-10 text-brass/30"
                viewBox="0 0 40 40" fill="none">
                <polyline points="40,0 40,40 0,40" stroke="currentColor" stroke-width="1.5" class="draw-line"
                    style="animation-delay:.5s" />
            </svg>

            <div class="relative flex h-full flex-col justify-between px-12 py-12 xl:px-16">

                <div class="reveal reveal-d1 flex items-center gap-3">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-brass-soft font-display text-lg font-bold text-ink">
                        {{ mb_substr(setting('site_name', 'ش'), 0, 1) }}
                    </div>
                    <p class="font-display text-lg font-semibold text-brass-soft">{{ setting('site_name', 'لوحة التحكم') }}</p>
                </div>

                <div class="max-w-lg">
                    <p class="reveal reveal-d2 mb-4 text-xs font-medium uppercase tracking-widest text-brass">
                        لوحة الإدارة
                    </p>
                    <h1 class="reveal reveal-d3 font-display text-4xl font-semibold leading-snug text-paper text-balance">
                        مساحة عمل واحدة لإدارة كل تفاصيل الموقع
                    </h1>
                    <p class="reveal reveal-d4 mt-4 text-sm leading-7 text-brass-soft/70">
                        من هنا يتابع فريق العمل خدمات الشركة ومشاريعها، ويرد على طلبات الأسعار والحجوزات، ويحدّث محتوى
                        الموقع أولًا بأول.
                    </p>

                    {{-- Blueprint illustration: crane + skyline --}}
                    <div class="reveal reveal-d5 mt-8 text-brass-soft/50">
                        <svg viewBox="0 0 240 130" fill="none" class="h-28 w-full max-w-xs" aria-hidden="true">
                            <line x1="8" y1="118" x2="232" y2="118" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" class="draw-line" style="animation-delay:.05s" />
                            <rect x="18" y="72" width="30" height="46" stroke="currentColor" stroke-width="1.5"
                                class="draw-line" style="animation-delay:.2s" />
                            <rect x="58" y="42" width="34" height="76" stroke="currentColor" stroke-width="1.5"
                                class="draw-line" style="animation-delay:.35s" />
                            <rect x="102" y="88" width="24" height="30" stroke="currentColor" stroke-width="1.5"
                                class="draw-line" style="animation-delay:.5s" />
                            <line x1="168" y1="118" x2="168" y2="18" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" class="draw-line" style="animation-delay:.65s" />
                            <line x1="168" y1="18" x2="226" y2="18" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" class="draw-line" style="animation-delay:.8s" />
                            <line x1="168" y1="18" x2="148" y2="30" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" class="draw-line" style="animation-delay:.9s" />
                            <line x1="168" y1="42" x2="192" y2="18" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" class="draw-line" style="animation-delay:1s" />
                            <line x1="210" y1="18" x2="210" y2="50" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" class="draw-line" style="animation-delay:1.1s" />
                            <rect x="206" y="50" width="8" height="8" stroke="currentColor" stroke-width="1.5"
                                class="draw-line" style="animation-delay:1.2s" />
                        </svg>
                    </div>

                    {{-- Company statistics --}}
                    @if ($stats->isNotEmpty())
                        <dl class="reveal reveal-d6 mt-8 grid grid-cols-2 gap-x-6 gap-y-5 border-t border-brass-soft/10 pt-8">
                            @foreach ($stats as $stat)
                                <div x-data="{ n: 0 }" x-init="
                                    const target = {{ $stat->number }};
                                    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { n = target; return; }
                                    const start = performance.now(); const dur = 1200 + {{ $loop->index }} * 120;
                                    const step = (ts) => {
                                        const p = Math.min((ts - start) / dur, 1);
                                        n = Math.floor(p * target);
                                        if (p < 1) requestAnimationFrame(step);
                                    };
                                    requestAnimationFrame(step);
                                ">
                                    <dt class="text-xs text-brass-soft/60">{{ $stat->label }}</dt>
                                    <dd class="mt-1 font-display text-2xl font-semibold text-brass-soft">
                                        <span x-text="n"></span>{{ $stat->suffix }}
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                </div>

                <ul class="space-y-3 text-sm text-brass-soft/80">
                    @foreach ([['building', 'الخدمات والمشاريع ومعرض الأعمال'], ['clipboard', 'طلبات الأسعار والحجوزات ورسائل التواصل'], ['document', 'محتوى المدونة والصفحة الرئيسية'], ['users', 'فريق العمل والإعدادات العامة']] as [$icon, $label])
                        <li class="reveal flex items-center gap-3" style="animation-delay: {{ .5 + $loop->index * .08 }}s">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-white/5 text-brass">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                    stroke="currentColor" class="h-4 w-4">
                                    @php
                                        $paths = [
                                            'building' => 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21',
                                            'clipboard' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z',
                                            'document' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
                                            'users' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
                                        ];
                                    @endphp
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $paths[$icon] }}" />
                                </svg>
                            </span>
                            {{ $label }}
                        </li>
                    @endforeach
                </ul>

                <div class="reveal" style="animation-delay:.85s">
                    <div class="flex items-center gap-1.5" aria-hidden="true">
                        @for ($i = 0; $i < 18; $i++)
                            <span class="h-2 w-px bg-brass-soft/20"></span>
                        @endfor
                    </div>
                    <p class="mt-3 text-xs text-brass-soft/50">هذه المنطقة مخصصة لفريق العمل فقط</p>
                </div>
            </div>
        </aside>

        {{-- Form panel --}}
        <main class="relative flex min-w-0 items-center justify-center overflow-y-auto bg-surface px-6 py-16 sm:px-12 lg:px-20">

            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="paper-grid absolute inset-0"></div>
                <div class="absolute inset-x-0 top-0 h-1 bg-linear-to-l from-transparent via-brass to-transparent">
                </div>
                <div class="blueprint-drift absolute -right-40 top-1/3 h-80 w-80 rounded-full bg-brass-soft/40 blur-3xl">
                </div>
            </div>

            <div class="relative w-full min-w-0 max-w-md py-4">

                {{-- Compact brand header on small screens --}}
                <div class="reveal mb-10 flex items-center gap-3 lg:hidden">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-ink font-display text-base font-bold text-brass-soft">
                        {{ mb_substr(setting('site_name', 'ش'), 0, 1) }}
                    </div>
                    <p class="font-display text-base font-semibold text-ink">{{ setting('site_name', 'لوحة التحكم') }}</p>
                </div>

                {{ $slot }}

                <div class="reveal mt-10 flex items-center gap-2 text-xs text-ink-soft/70" style="animation-delay:.5s">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5">
                        <path fill-rule="evenodd"
                            d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                            clip-rule="evenodd" />
                    </svg>
                    اتصال آمن ومشفّر بين متصفحك وخوادم لوحة التحكم
                </div>
            </div>
        </main>
    </div>
</body>

</html>
