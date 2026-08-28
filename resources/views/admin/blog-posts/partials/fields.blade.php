@php
    $statusOptions = ['draft' => 'مسودة', 'published' => 'منشورة'];
    $selectedAuthor = old('author_id', $post?->author_id ?? auth()->id());
    $publishedAtValue = old('published_at', $post?->published_at?->format('Y-m-d\TH:i'));
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input-label for="title" value="عنوان المقالة" />
        <x-text-input id="title" name="title" type="text" required autofocus
            :value="old('title', $post?->title)" placeholder="مثال: أهم اتجاهات التشطيبات لهذا العام" />
        <x-input-error :messages="$errors->get('title')" />
    </div>

    <div>
        <x-input-label for="blog_category_id" value="التصنيف" />
        <select id="blog_category_id" name="blog_category_id"
            class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
            <option value="">بدون تصنيف</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('blog_category_id', $post?->blog_category_id) === $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('blog_category_id')" />
    </div>

    <div>
        <x-input-label for="author_id" value="الكاتب" />
        <select id="author_id" name="author_id"
            class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
            @foreach ($authors as $author)
                <option value="{{ $author->id }}" @selected((int) $selectedAuthor === $author->id)>
                    {{ $author->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('author_id')" />
    </div>

    <div>
        <x-input-label for="status" value="الحالة" />
        <select id="status" name="status"
            class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $post?->status ?? 'draft') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" />
    </div>

    <div>
        <x-input-label for="published_at" value="تاريخ النشر" />
        <x-text-input id="published_at" name="published_at" type="datetime-local" :value="$publishedAtValue" />
        <x-input-error :messages="$errors->get('published_at')" />
        <p class="mt-2 text-xs text-ink-soft">
            اتركه فارغًا لنشر المقالة فور حفظها، أو اختر تاريخًا مستقبليًا لجدولة نشرها تلقائيًا.
        </p>
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="excerpt" value="مقتطف مختصر" />
        <x-text-input id="excerpt" name="excerpt" type="text"
            :value="old('excerpt', $post?->excerpt)" placeholder="جملة أو جملتان تظهران في قائمة المدونة" />
        <x-input-error :messages="$errors->get('excerpt')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="content" value="محتوى المقالة" />
        <textarea id="content" name="content" rows="14" required
            class="w-full rounded-lg border-line bg-paper text-sm leading-7 text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">{{ old('content', $post?->content) }}</textarea>
        <x-input-error :messages="$errors->get('content')" />
    </div>
</div>
