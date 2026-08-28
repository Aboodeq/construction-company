@php
    $statusOptions = ['draft' => 'مسودة', 'published' => 'منشورة'];
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input-label for="title" value="عنوان الخدمة" />
        <x-text-input id="title" name="title" type="text" required
            :value="old('title', $service?->title)" placeholder="مثال: تشطيب فلل فاخر" />
        <x-input-error :messages="$errors->get('title')" />
    </div>

    <div>
        <x-input-label for="service_category_id" value="التصنيف" />
        <select id="service_category_id" name="service_category_id"
            class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
            <option value="">بدون تصنيف</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('service_category_id', $service?->service_category_id) === $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('service_category_id')" />
    </div>

    <div>
        <x-input-label for="status" value="الحالة" />
        <select id="status" name="status"
            class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $service?->status ?? 'draft') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="short_description" value="وصف مختصر" />
        <x-text-input id="short_description" name="short_description" type="text"
            :value="old('short_description', $service?->short_description)"
            placeholder="جملة قصيرة تظهر في قوائم الخدمات" />
        <x-input-error :messages="$errors->get('short_description')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="description" value="الوصف التفصيلي" />
        <textarea id="description" name="description" rows="6" required
            class="w-full rounded-lg border-line bg-paper text-sm text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">{{ old('description', $service?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" />
    </div>

    <div>
        <x-input-label for="order" value="ترتيب العرض" />
        <x-text-input id="order" name="order" type="number" min="0"
            :value="old('order', $service?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" />
        <p class="mt-2 text-xs text-ink-soft">الرقم الأصغر يظهر أولًا في قائمة الخدمات بالموقع.</p>
    </div>

    <div class="flex items-end pb-2">
        <label for="is_featured" class="inline-flex items-center gap-2.5 text-sm text-ink">
            <input id="is_featured" name="is_featured" type="checkbox" value="1"
                @checked(old('is_featured', $service?->is_featured))
                class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
            خدمة مميزة (تظهر في واجهات مميزة بالموقع)
        </label>
    </div>
</div>
