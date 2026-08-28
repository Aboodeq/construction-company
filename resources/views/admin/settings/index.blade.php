@php
    $tabs = [
        'general' => 'عام',
        'contact' => 'التواصل',
        'social' => 'التواصل الاجتماعي',
        'seo' => 'تحسين محركات البحث',
        'appearance' => 'المظهر',
    ];
@endphp

<x-admin.layouts.app title="الإعدادات">
    <div x-data="{ tab: 'general' }" class="mx-auto w-full max-w-4xl">
        <div class="mb-8">
            <h2 class="font-display text-2xl font-semibold text-ink">إعدادات الموقع</h2>
            <div class="mt-2 flex items-center gap-1.5 text-ink-soft" aria-hidden="true">
                @for ($i = 0; $i < 24; $i++)
                    <span class="h-2 w-px bg-line"></span>
                @endfor
            </div>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-ink-soft">
                البيانات العامة للموقع، وسائل التواصل، بيانات SEO الافتراضية، وألوان الهوية.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="flex flex-wrap gap-2 border-b border-line pb-4">
                @foreach ($tabs as $key => $label)
                    <button type="button" @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}' ? 'bg-ink text-brass-soft' : 'border border-line text-ink-soft hover:bg-paper hover:text-ink'"
                        class="inline-flex h-10 items-center justify-center rounded-lg px-4 text-sm font-medium transition">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    يرجى تصحيح الأخطاء أدناه قبل الحفظ.
                </div>
            @endif

            {{-- عام --}}
            <section x-show="tab === 'general'" x-cloak class="space-y-5 rounded-lg border border-line bg-surface p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <x-input-label for="site_name" value="اسم الموقع" />
                        <x-text-input id="site_name" name="site_name" type="text" required
                            :value="old('site_name', $settings['site_name'] ?? null)" />
                        <x-input-error :messages="$errors->get('site_name')" />
                    </div>
                    <div>
                        <x-input-label for="site_tagline" value="الشعار النصي" />
                        <x-text-input id="site_tagline" name="site_tagline" type="text"
                            :value="old('site_tagline', $settings['site_tagline'] ?? null)" />
                        <x-input-error :messages="$errors->get('site_tagline')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <x-input-label value="الشعار (Logo)" />
                        <div class="flex items-center gap-4">
                            @if ($settings['logo'] ?? null)
                                <img src="{{ asset('storage/'.$settings['logo']) }}" alt="" class="h-12 w-12 rounded-lg border border-line object-cover">
                            @endif
                            <div class="min-w-0 flex-1 space-y-2">
                                <input type="file" name="logo" accept="image/*"
                                    class="block w-full text-sm text-ink-soft file:me-3 file:rounded-lg file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-medium file:text-brass-soft hover:file:bg-brass">
                                @if ($settings['logo'] ?? null)
                                    <label class="inline-flex items-center gap-2 text-xs text-ink-soft">
                                        <input type="checkbox" name="remove_logo" value="1" class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
                                        إزالة الشعار الحالي
                                    </label>
                                @endif
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('logo')" />
                    </div>

                    <div>
                        <x-input-label value="أيقونة الموقع (Favicon)" />
                        <div class="flex items-center gap-4">
                            @if ($settings['favicon'] ?? null)
                                <img src="{{ asset('storage/'.$settings['favicon']) }}" alt="" class="h-12 w-12 rounded-lg border border-line object-cover">
                            @endif
                            <div class="min-w-0 flex-1 space-y-2">
                                <input type="file" name="favicon" accept="image/*"
                                    class="block w-full text-sm text-ink-soft file:me-3 file:rounded-lg file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-medium file:text-brass-soft hover:file:bg-brass">
                                @if ($settings['favicon'] ?? null)
                                    <label class="inline-flex items-center gap-2 text-xs text-ink-soft">
                                        <input type="checkbox" name="remove_favicon" value="1" class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
                                        إزالة الأيقونة الحالية
                                    </label>
                                @endif
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('favicon')" />
                    </div>
                </div>

                <div>
                    <input type="hidden" name="maintenance_mode" value="0">
                    <label class="inline-flex items-center gap-2 text-sm text-ink">
                        <input type="checkbox" name="maintenance_mode" value="1" class="h-4 w-4 rounded border-line text-brass focus:ring-brass"
                            @checked(old('maintenance_mode', $settings['maintenance_mode'] ?? '0') == '1')>
                        تفعيل وضع الصيانة
                    </label>
                    <p class="mt-2 text-xs text-ink-soft">عند التفعيل، سيظهر للزوار إشعار بأن الموقع تحت الصيانة.</p>
                </div>
            </section>

            {{-- التواصل --}}
            <section x-show="tab === 'contact'" x-cloak class="space-y-5 rounded-lg border border-line bg-surface p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <x-input-label for="contact_phone" value="رقم الهاتف" />
                        <x-text-input id="contact_phone" name="contact_phone" type="text"
                            :value="old('contact_phone', $settings['contact_phone'] ?? null)" />
                        <x-input-error :messages="$errors->get('contact_phone')" />
                    </div>
                    <div>
                        <x-input-label for="contact_whatsapp" value="رقم واتساب" />
                        <x-text-input id="contact_whatsapp" name="contact_whatsapp" type="text"
                            :value="old('contact_whatsapp', $settings['contact_whatsapp'] ?? null)" />
                        <x-input-error :messages="$errors->get('contact_whatsapp')" />
                    </div>
                    <div>
                        <x-input-label for="contact_email" value="البريد الإلكتروني" />
                        <x-text-input id="contact_email" name="contact_email" type="email"
                            :value="old('contact_email', $settings['contact_email'] ?? null)" />
                        <x-input-error :messages="$errors->get('contact_email')" />
                    </div>
                    <div>
                        <x-input-label for="business_hours" value="ساعات العمل" />
                        <x-text-input id="business_hours" name="business_hours" type="text"
                            :value="old('business_hours', $settings['business_hours'] ?? null)" />
                        <x-input-error :messages="$errors->get('business_hours')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="contact_address" value="العنوان" />
                        <x-text-input id="contact_address" name="contact_address" type="text"
                            :value="old('contact_address', $settings['contact_address'] ?? null)" />
                        <x-input-error :messages="$errors->get('contact_address')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="google_maps_embed" value="رابط تضمين خرائط جوجل" />
                        <textarea id="google_maps_embed" name="google_maps_embed" rows="3"
                            class="w-full rounded-lg border-line bg-paper text-sm leading-7 text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">{{ old('google_maps_embed', $settings['google_maps_embed'] ?? null) }}</textarea>
                        <x-input-error :messages="$errors->get('google_maps_embed')" />
                    </div>
                </div>
            </section>

            {{-- التواصل الاجتماعي --}}
            <section x-show="tab === 'social'" x-cloak class="space-y-5 rounded-lg border border-line bg-surface p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    @foreach (['facebook_url' => 'فيسبوك', 'instagram_url' => 'إنستغرام', 'twitter_url' => 'إكس (تويتر)', 'linkedin_url' => 'لينكدإن', 'youtube_url' => 'يوتيوب'] as $key => $label)
                        <div>
                            <x-input-label for="{{ $key }}" :value="$label" />
                            <x-text-input id="{{ $key }}" name="{{ $key }}" type="url" placeholder="https://..."
                                :value="old($key, $settings[$key] ?? null)" />
                            <x-input-error :messages="$errors->get($key)" />
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- SEO --}}
            <section x-show="tab === 'seo'" x-cloak class="space-y-5 rounded-lg border border-line bg-surface p-6">
                <div>
                    <x-input-label for="default_meta_title" value="عنوان SEO الافتراضي" />
                    <x-text-input id="default_meta_title" name="default_meta_title" type="text"
                        :value="old('default_meta_title', $settings['default_meta_title'] ?? null)" />
                    <x-input-error :messages="$errors->get('default_meta_title')" />
                </div>
                <div>
                    <x-input-label for="default_meta_description" value="وصف SEO الافتراضي" />
                    <textarea id="default_meta_description" name="default_meta_description" rows="3"
                        class="w-full rounded-lg border-line bg-paper text-sm leading-7 text-ink placeholder:text-ink-soft/60 focus:border-brass focus:ring-brass">{{ old('default_meta_description', $settings['default_meta_description'] ?? null) }}</textarea>
                    <x-input-error :messages="$errors->get('default_meta_description')" />
                </div>
                <div>
                    <x-input-label value="صورة المشاركة الافتراضية (OG Image)" />
                    <div class="flex items-center gap-4">
                        @if ($settings['default_og_image'] ?? null)
                            <img src="{{ asset('storage/'.$settings['default_og_image']) }}" alt="" class="h-12 w-20 rounded-lg border border-line object-cover">
                        @endif
                        <div class="min-w-0 flex-1 space-y-2">
                            <input type="file" name="default_og_image" accept="image/*"
                                class="block w-full text-sm text-ink-soft file:me-3 file:rounded-lg file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-medium file:text-brass-soft hover:file:bg-brass">
                            @if ($settings['default_og_image'] ?? null)
                                <label class="inline-flex items-center gap-2 text-xs text-ink-soft">
                                    <input type="checkbox" name="remove_default_og_image" value="1" class="h-4 w-4 rounded border-line text-brass focus:ring-brass">
                                    إزالة الصورة الحالية
                                </label>
                            @endif
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('default_og_image')" />
                </div>
                <div>
                    <x-input-label for="google_analytics_code" value="معرّف Google Analytics" />
                    <x-text-input id="google_analytics_code" name="google_analytics_code" type="text" placeholder="G-XXXXXXXXXX"
                        :value="old('google_analytics_code', $settings['google_analytics_code'] ?? null)" />
                    <x-input-error :messages="$errors->get('google_analytics_code')" />
                </div>
            </section>

            {{-- المظهر --}}
            <section x-show="tab === 'appearance'" x-cloak class="space-y-5 rounded-lg border border-line bg-surface p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <x-input-label for="primary_color" value="اللون الأساسي" />
                        <div class="flex items-center gap-3">
                            <input id="primary_color" name="primary_color" type="color" class="h-11 w-14 rounded-lg border border-line bg-paper"
                                value="{{ old('primary_color', $settings['primary_color'] ?? '#1b1b18') }}">
                            <span class="text-sm text-ink-soft">{{ old('primary_color', $settings['primary_color'] ?? '#1b1b18') }}</span>
                        </div>
                        <x-input-error :messages="$errors->get('primary_color')" />
                    </div>
                    <div>
                        <x-input-label for="accent_color" value="لون التمييز" />
                        <div class="flex items-center gap-3">
                            <input id="accent_color" name="accent_color" type="color" class="h-11 w-14 rounded-lg border border-line bg-paper"
                                value="{{ old('accent_color', $settings['accent_color'] ?? '#a9812e') }}">
                            <span class="text-sm text-ink-soft">{{ old('accent_color', $settings['accent_color'] ?? '#a9812e') }}</span>
                        </div>
                        <x-input-error :messages="$errors->get('accent_color')" />
                    </div>
                </div>
            </section>

            <div class="flex items-center justify-end border-t border-line pt-6">
                <button type="submit"
                    class="inline-flex h-11 items-center justify-center rounded-lg bg-ink px-6 text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                    حفظ الإعدادات
                </button>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
