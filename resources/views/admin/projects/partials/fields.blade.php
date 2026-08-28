@php
    $statusOptions = ['draft' => 'مسودة', 'published' => 'منشور'];
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input-label for="title" value="اسم المشروع" />
        <x-text-input id="title" name="title" type="text" required
            :value="old('title', $project?->title)" placeholder="مثال: فيلا العائلة الملكية" />
        <x-input-error :messages="$errors->get('title')" />
    </div>

    <div>
        <x-input-label for="project_category_id" value="التصنيف" />
        <select id="project_category_id" name="project_category_id"
            class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
            <option value="">بدون تصنيف</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('project_category_id', $project?->project_category_id) === $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('project_category_id')" />
    </div>

    <div>
        <x-input-label for="status" value="الحالة" />
        <select id="status" name="status"
            class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $project?->status ?? 'draft') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" />
    </div>

    <div>
        <x-input-label for="client_name" value="اسم العميل" />
        <x-text-input id="client_name" name="client_name" type="text"
            :value="old('client_name', $project?->client_name)" />
        <x-input-error :messages="$errors->get('client_name')" />
    </div>

    <div>
        <x-input-label for="location" value="الموقع" />
        <x-text-input id="location" name="location" type="text"
            :value="old('location', $project?->location)" placeholder="مثال: الرياض" />
        <x-input-error :messages="$errors->get('location')" />
    </div>

    <div>
        <x-input-label for="area" value="المساحة (م²)" />
        <x-text-input id="area" name="area" type="number" step="0.01" min="0"
            :value="old('area', $project?->area)" />
        <x-input-error :messages="$errors->get('area')" />
    </div>

    <div>
        <x-input-label for="duration" value="مدة التنفيذ" />
        <x-text-input id="duration" name="duration" type="text"
            :value="old('duration', $project?->duration)" placeholder="مثال: 4 أشهر" />
        <x-input-error :messages="$errors->get('duration')" />
    </div>

    <div>
        <x-input-label for="completion_date" value="تاريخ التسليم" />
        <x-text-input id="completion_date" name="completion_date" type="date"
            :value="old('completion_date', $project?->completion_date?->format('Y-m-d'))" />
        <x-input-error :messages="$errors->get('completion_date')" />
    </div>

    <div>
        <x-input-label for="order" value="ترتيب العرض" />
        <x-text-input id="order" name="order" type="number" min="0"
            :value="old('order', $project?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="description" value="وصف المشروع" />
        <textarea id="description" name="description" rows="6" required
            class="w-full rounded-lg border-line bg-paper text-sm text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">{{ old('description', $project?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" />
    </div>

    <div class="flex items-end pb-2 sm:col-span-2">
        <label for="is_featured" class="inline-flex items-center gap-2.5 text-sm text-ink">
            <input id="is_featured" name="is_featured" type="checkbox" value="1"
                @checked(old('is_featured', $project?->is_featured))
                class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
            مشروع مميز (يظهر في واجهات مميزة بالموقع)
        </label>
    </div>
</div>
