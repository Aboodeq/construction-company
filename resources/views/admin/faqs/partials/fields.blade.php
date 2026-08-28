@php
    $statusOptions = ['draft' => 'مسودة', 'published' => 'منشور'];
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input-label for="question" value="السؤال" />
        <x-text-input id="question" name="question" type="text" required autofocus
            :value="old('question', $faq?->question)" />
        <x-input-error :messages="$errors->get('question')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="answer" value="الإجابة" />
        <textarea id="answer" name="answer" rows="4" required
            class="w-full rounded-lg border-line bg-paper text-sm leading-7 text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">{{ old('answer', $faq?->answer) }}</textarea>
        <x-input-error :messages="$errors->get('answer')" />
    </div>

    <div>
        <x-input-label for="category" value="التصنيف" />
        <x-text-input id="category" name="category" type="text" list="category-options"
            :value="old('category', $faq?->category)" placeholder="مثال: عام، الأسعار، التنفيذ" />
        <datalist id="category-options">
            @foreach ($existingCategories as $existingCategory)
                <option value="{{ $existingCategory }}"></option>
            @endforeach
        </datalist>
        <x-input-error :messages="$errors->get('category')" />
        <p class="mt-2 text-xs text-ink-soft">اختياري — يساعد على تجميع الأسئلة عند عرضها بالموقع.</p>
    </div>

    <div>
        <x-input-label for="status" value="الحالة" />
        <select id="status" name="status"
            class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $faq?->status ?? 'published') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" />
    </div>

    <div>
        <x-input-label for="order" value="ترتيب العرض" />
        <x-text-input id="order" name="order" type="number" min="0"
            :value="old('order', $faq?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" />
    </div>
</div>
