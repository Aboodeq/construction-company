@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'h-11 w-full rounded-lg border-line bg-paper text-sm text-ink placeholder:text-ink-soft/60 transition focus:border-brass focus:ring-brass disabled:cursor-not-allowed disabled:opacity-60']) }}>
