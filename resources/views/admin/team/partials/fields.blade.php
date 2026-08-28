@php
    $statusOptions = ['draft' => 'مسودة', 'published' => 'منشور'];
    $links = old('social_links', $member?->social_links ?? []);
    $socialFields = [
        'linkedin' => 'لينكدإن',
        'twitter' => 'إكس (تويتر)',
        'instagram' => 'إنستغرام',
        'facebook' => 'فيسبوك',
    ];
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <x-input-label for="name" value="الاسم" />
        <x-text-input id="name" name="name" type="text" required autofocus
            :value="old('name', $member?->name)" />
        <x-input-error :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="position" value="المسمى الوظيفي" />
        <x-text-input id="position" name="position" type="text" required
            :value="old('position', $member?->position)" placeholder="مثال: مهندس معماري" />
        <x-input-error :messages="$errors->get('position')" />
    </div>

    <div>
        <x-input-label for="status" value="الحالة" />
        <select id="status" name="status"
            class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $member?->status ?? 'published') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" />
    </div>

    <div>
        <x-input-label for="order" value="ترتيب العرض" />
        <x-text-input id="order" name="order" type="number" min="0"
            :value="old('order', $member?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="bio" value="نبذة مختصرة" />
        <textarea id="bio" name="bio" rows="3"
            class="w-full rounded-lg border-line bg-paper text-sm leading-7 text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">{{ old('bio', $member?->bio) }}</textarea>
        <x-input-error :messages="$errors->get('bio')" />
    </div>

    <div class="sm:col-span-2">
        <p class="mb-2 text-xs font-medium text-ink-soft">روابط التواصل الاجتماعي (اختياري)</p>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ($socialFields as $key => $label)
                <div>
                    <x-input-label for="social_{{ $key }}" :value="$label" />
                    <x-text-input id="social_{{ $key }}" name="social_links[{{ $key }}]" type="url"
                        :value="$links[$key] ?? null" placeholder="https://" />
                    <x-input-error :messages="$errors->get('social_links.'.$key)" />
                </div>
            @endforeach
        </div>
    </div>
</div>
