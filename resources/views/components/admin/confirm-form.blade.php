@props([
    'action',
    'method' => 'DELETE',
    'title' => 'تأكيد الحذف',
    'message' => 'هل أنت متأكد من هذا الإجراء؟ لا يمكن التراجع عنه.',
    'confirmLabel' => 'حذف',
    'triggerLabel' => null,
])

<div x-data="{ open: false }" @keydown.escape.window="open = false">
    <button type="button" @click="open = true" @if ($triggerLabel) title="{{ $triggerLabel }}" @endif
        {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-lg border border-line text-ink-soft transition hover:border-red-200 hover:bg-red-50 hover:text-red-600']) }}>
        {{ $slot }}
    </button>

    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="open" x-transition.opacity @click="open = false" class="absolute inset-0 bg-ink/50"></div>

        <div x-show="open" x-transition
            class="relative w-full max-w-sm rounded-xl border border-line bg-surface p-6 shadow-2xl">
            <h3 class="font-display text-lg font-semibold text-ink">{{ $title }}</h3>
            <p class="mt-2 text-sm leading-6 text-ink-soft">{{ $message }}</p>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" @click="open = false"
                    class="inline-flex h-10 items-center justify-center rounded-lg border border-line bg-surface px-4 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                    إلغاء
                </button>
                <form method="POST" action="{{ $action }}">
                    @csrf
                    @if (strtoupper($method) !== 'POST')
                        @method($method)
                    @endif
                    <button type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-lg bg-red-600 px-4 text-sm font-semibold text-white transition hover:bg-red-700">
                        {{ $confirmLabel }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
