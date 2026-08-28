<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input-label for="label" value="التسمية" />
        <x-text-input id="label" name="label" type="text" required autofocus
            :value="old('label', $companyStatistic?->label)" placeholder="مثال: سنوات الخبرة" />
        <x-input-error :messages="$errors->get('label')" />
    </div>

    <div>
        <x-input-label for="number" value="الرقم" />
        <x-text-input id="number" name="number" type="number" min="0" required
            :value="old('number', $companyStatistic?->number)" />
        <x-input-error :messages="$errors->get('number')" />
    </div>

    <div>
        <x-input-label for="suffix" value="لاحقة (اختياري)" />
        <x-text-input id="suffix" name="suffix" type="text" maxlength="10"
            :value="old('suffix', $companyStatistic?->suffix)" placeholder="مثال: +" />
        <x-input-error :messages="$errors->get('suffix')" />
    </div>

    <div>
        <x-input-label for="icon" value="أيقونة (اختياري)" />
        <x-text-input id="icon" name="icon" type="text"
            :value="old('icon', $companyStatistic?->icon)" placeholder="مثال: users" />
        <x-input-error :messages="$errors->get('icon')" />
    </div>

    <div>
        <x-input-label for="order" value="ترتيب العرض" />
        <x-text-input id="order" name="order" type="number" min="0"
            :value="old('order', $companyStatistic?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" />
    </div>
</div>
