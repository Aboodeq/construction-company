@php
    $statusLabels = ['pending' => 'قيد الانتظار', 'confirmed' => 'مؤكد', 'completed' => 'مكتمل', 'cancelled' => 'ملغى'];
@endphp

<x-admin.layouts.app title="تفاصيل الحجز">
    <div class="mx-auto w-full max-w-3xl">
        <div class="mb-8">
            <a href="{{ route('admin.bookings.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-ink-soft transition hover:text-brass">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                        clip-rule="evenodd" />
                </svg>
                العودة إلى الحجوزات
            </a>
            <h2 class="mt-4 font-display text-2xl font-semibold text-ink">{{ $booking->name }}</h2>
            <p class="mt-2 text-sm text-ink-soft">
                <a href="tel:{{ $booking->phone }}" class="hover:text-brass">{{ $booking->phone }}</a>
                @if ($booking->email)
                    · <a href="mailto:{{ $booking->email }}" class="hover:text-brass">{{ $booking->email }}</a>
                @endif
            </p>
        </div>

        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}"
            class="space-y-6 rounded-lg border border-line bg-surface p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="preferred_date" value="تاريخ الزيارة" />
                    <x-text-input id="preferred_date" name="preferred_date" type="date" required
                        :value="old('preferred_date', $booking->preferred_date?->format('Y-m-d'))" />
                    <x-input-error :messages="$errors->get('preferred_date')" />
                </div>

                <div>
                    <x-input-label for="preferred_time" value="الوقت المفضّل" />
                    <x-text-input id="preferred_time" name="preferred_time" type="text"
                        :value="old('preferred_time', $booking->preferred_time)" />
                    <x-input-error :messages="$errors->get('preferred_time')" />
                </div>

                <div>
                    <x-input-label for="city" value="المدينة" />
                    <x-text-input id="city" name="city" type="text" required
                        :value="old('city', $booking->city)" />
                    <x-input-error :messages="$errors->get('city')" />
                </div>

                <div>
                    <x-input-label for="status" value="الحالة" />
                    <select id="status" name="status"
                        class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $booking->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('status')" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="address" value="العنوان" />
                    <x-text-input id="address" name="address" type="text"
                        :value="old('address', $booking->address)" />
                    <x-input-error :messages="$errors->get('address')" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="notes" value="ملاحظات العميل" />
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full rounded-lg border-line bg-paper text-sm leading-7 text-ink focus:border-brass focus:ring-brass">{{ old('notes', $booking->notes) }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" />
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-line pt-6">
                @can('bookings.delete')
                    <x-admin.confirm-form
                        :action="route('admin.bookings.destroy', $booking)"
                        title="حذف الحجز"
                        :message="'سيتم حذف حجز «'.$booking->name.'» نهائيًا. هل تريد المتابعة؟'"
                        class="h-11 px-5 text-sm font-medium">
                        حذف الحجز
                    </x-admin.confirm-form>
                @else
                    <span></span>
                @endcan

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.bookings.index') }}"
                        class="inline-flex h-11 items-center justify-center rounded-lg border border-line px-5 text-sm font-medium text-ink-soft transition hover:bg-paper hover:text-ink">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="inline-flex h-11 items-center justify-center rounded-lg bg-ink px-5 text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                        حفظ التغييرات
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
