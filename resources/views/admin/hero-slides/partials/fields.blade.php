@php
    $statusOptions = ['draft' => 'مسودة', 'published' => 'منشور'];
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input-label for="title" value="العنوان الرئيسي" />
        <x-text-input id="title" name="title" type="text" required autofocus
            :value="old('title', $heroSlide?->title)" />
        <x-input-error :messages="$errors->get('title')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="subtitle" value="العنوان الفرعي" />
        <x-text-input id="subtitle" name="subtitle" type="text"
            :value="old('subtitle', $heroSlide?->subtitle)" />
        <x-input-error :messages="$errors->get('subtitle')" />
    </div>

    <div>
        <x-input-label for="button_text" value="نص الزر" />
        <x-text-input id="button_text" name="button_text" type="text"
            :value="old('button_text', $heroSlide?->button_text)" placeholder="مثال: اطلب عرض سعر" />
        <x-input-error :messages="$errors->get('button_text')" />
    </div>

    <div>
        <x-input-label for="button_url" value="رابط الزر" />
        <x-text-input id="button_url" name="button_url" type="text"
            :value="old('button_url', $heroSlide?->button_url)" placeholder="مثال: /quote-request" />
        <x-input-error :messages="$errors->get('button_url')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="video_url" value="رابط فيديو (اختياري)" />
        <x-text-input id="video_url" name="video_url" type="url"
            :value="old('video_url', $heroSlide?->video_url)" placeholder="https://..." />
        <x-input-error :messages="$errors->get('video_url')" />
    </div>

    <div>
        <x-input-label for="status" value="الحالة" />
        <select id="status" name="status"
            class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $heroSlide?->status ?? 'published') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" />
    </div>

    <div>
        <x-input-label for="order" value="ترتيب العرض" />
        <x-text-input id="order" name="order" type="number" min="0"
            :value="old('order', $heroSlide?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" />
    </div>
</div>
