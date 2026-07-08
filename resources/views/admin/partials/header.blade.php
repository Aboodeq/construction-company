<header
    class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-line bg-paper/90 px-4 backdrop-blur sm:px-6 lg:px-10">

    <button type="button" @click="sidebarOpen = !sidebarOpen" :aria-expanded="sidebarOpen.toString()"
        :aria-label="sidebarOpen ? 'Close sidebar' : 'Open sidebar'"
        class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-line bg-surface text-ink-soft transition hover:border-brass/40 hover:text-brass">
        <svg x-show="!sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                clip-rule="evenodd" />
        </svg>
        <svg x-show="sidebarOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
                d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"
                clip-rule="evenodd" />
        </svg>
    </button>

    <div class="min-w-0 flex-1">
        <h1 class="truncate font-display text-base font-semibold text-ink sm:text-lg">{{ $title ?? 'لوحة التحكم' }}</h1>
    </div>

    <div class="flex shrink-0 items-center gap-3 sm:gap-5">


        <a href="{{ route('home') }}"
            target="_blank"
            class="hidden items-center gap-1.5 whitespace-nowrap text-sm text-ink-soft transition hover:text-brass sm:inline-flex">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
            stroke="currentColor" class="h-4 w-4">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
        </svg>
        <span>زيارة الموقع</span>
        </a>

        <div class="hidden h-6 w-px bg-line sm:block"></div>

        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-ink text-sm font-semibold text-brass-soft">
            {{ mb_substr(auth()->user()->name, 0, 1) }}
        </div>

    </div>

</header>
