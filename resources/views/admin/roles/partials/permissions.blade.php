@php
    $selected = old('permissions', $rolePermissions ?? []);
@endphp

<div class="space-y-5" x-data="{
    allChecked(prefix) {
        const boxes = document.querySelectorAll('input[data-group=\'' + prefix + '\']');
        return boxes.length > 0 && Array.from(boxes).every(b => b.checked);
    },
    toggleGroup(prefix, checked) {
        document.querySelectorAll('input[data-group=\'' + prefix + '\']').forEach(b => b.checked = checked);
    },
}">
    @foreach ($groupedPermissions as $prefix => $group)
        <div class="rounded-lg border border-line bg-paper p-4">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-ink">{{ $group['label'] }}</p>
                <label class="inline-flex items-center gap-2 text-xs text-ink-soft">
                    <input type="checkbox"
                        @change="toggleGroup('{{ $prefix }}', $event.target.checked)"
                        class="h-3.5 w-3.5 rounded border-line text-brass focus:ring-brass">
                    تحديد الكل
                </label>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-4">
                @foreach ($group['permissions'] as $permission)
                    <label class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm text-ink transition hover:bg-surface">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                            data-group="{{ $prefix }}"
                            @checked(in_array($permission->name, $selected, true))
                            class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
                        {{ str($permission->name)->after($prefix.'.')->replace(['view', 'create', 'edit', 'delete', 'export'], ['عرض', 'إضافة', 'تعديل', 'حذف', 'تصدير']) }}
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
