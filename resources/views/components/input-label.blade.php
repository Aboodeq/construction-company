@props(['value'])

<label {{ $attributes->merge(['class' => 'mb-2 block text-xs font-medium text-ink-soft']) }}>
    {{ $value ?? $slot }}
</label>
