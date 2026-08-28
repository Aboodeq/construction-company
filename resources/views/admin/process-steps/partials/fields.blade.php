<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <x-input-label for="step_number" value="رقم الخطوة" />
        <x-text-input id="step_number" name="step_number" type="number" min="1" required
            :value="old('step_number', $processStep?->step_number)" />
        <x-input-error :messages="$errors->get('step_number')" />
    </div>

    <div>
        <x-input-label for="order" value="ترتيب العرض" />
        <x-text-input id="order" name="order" type="number" min="0"
            :value="old('order', $processStep?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="title" value="العنوان" />
        <x-text-input id="title" name="title" type="text" required autofocus
            :value="old('title', $processStep?->title)" />
        <x-input-error :messages="$errors->get('title')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="description" value="الوصف" />
        <textarea id="description" name="description" rows="3"
            class="w-full rounded-lg border-line bg-paper text-sm leading-7 text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">{{ old('description', $processStep?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" />
    </div>

    <div>
        <x-input-label for="icon" value="أيقونة (اختياري)" />
        <x-text-input id="icon" name="icon" type="text"
            :value="old('icon', $processStep?->icon)" placeholder="مثال: chat" />
        <x-input-error :messages="$errors->get('icon')" />
    </div>
</div>
