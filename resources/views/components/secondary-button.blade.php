<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex h-11 items-center justify-center rounded-lg border border-line bg-surface px-5 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink focus:outline-none focus:ring-2 focus:ring-brass focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60']) }}>
    {{ $slot }}
</button>
