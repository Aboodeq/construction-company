@php
    $statusOptions = ['pending' => 'قيد المراجعة', 'published' => 'منشور'];
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <x-input-label for="client_name" value="اسم العميل" />
        <x-text-input id="client_name" name="client_name" type="text" required autofocus
            :value="old('client_name', $testimonial?->client_name)" />
        <x-input-error :messages="$errors->get('client_name')" />
    </div>

    <div>
        <x-input-label for="project_id" value="المشروع المرتبط (اختياري)" />
        <select id="project_id" name="project_id"
            class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
            <option value="">بدون ربط بمشروع</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @selected((int) old('project_id', $testimonial?->project_id) === $project->id)>
                    {{ $project->title }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('project_id')" />
    </div>

    <div>
        <x-input-label value="التقييم" />
        <div x-data="{ rating: {{ old('rating', $testimonial?->rating ?? 5) }} }" class="flex h-11 items-center gap-1">
            <input type="hidden" name="rating" :value="rating">
            <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                <button type="button" @click="rating = star" class="text-brass transition hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                        :fill="star <= rating ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="1.5">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.286 3.957c.3.922-.755 1.688-1.538 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.783.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.062 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z" />
                    </svg>
                </button>
            </template>
            <span class="ms-2 text-sm text-ink-soft" x-text="rating + ' / 5'"></span>
        </div>
        <x-input-error :messages="$errors->get('rating')" />
    </div>

    <div>
        <x-input-label for="status" value="الحالة" />
        <select id="status" name="status"
            class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $testimonial?->status ?? 'published') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" />
    </div>

    <div>
        <x-input-label for="order" value="ترتيب العرض" />
        <x-text-input id="order" name="order" type="number" min="0"
            :value="old('order', $testimonial?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" />
    </div>

    <div class="flex items-end pb-2">
        <label for="is_featured" class="inline-flex items-center gap-2.5 text-sm text-ink">
            <input id="is_featured" name="is_featured" type="checkbox" value="1"
                @checked(old('is_featured', $testimonial?->is_featured))
                class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
            رأي مميز (يظهر في واجهات مميزة بالموقع)
        </label>
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="review" value="نص الرأي" />
        <textarea id="review" name="review" rows="4" required
            class="w-full rounded-lg border-line bg-paper text-sm leading-7 text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">{{ old('review', $testimonial?->review) }}</textarea>
        <x-input-error :messages="$errors->get('review')" />
    </div>
</div>
