@php
    $pointIcons = [
        'shield-check' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z',
        'clock' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'users' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
    ];
    $defaultIcon = 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z';
    $whatsapp = setting('contact_whatsapp') ? preg_replace('/\D/', '', setting('contact_whatsapp')) : null;
@endphp

<x-public-layout :title="setting('default_meta_title', setting('site_name', config('app.name')))">

    {{-- Hero --}}
    @if ($heroSlides->isNotEmpty())
        <section x-data="{
                slides: @js($heroSlides->map(fn ($s) => [
                    'title' => $s->title,
                    'subtitle' => $s->subtitle,
                    'image' => $s->image ? asset('storage/'.$s->image) : null,
                    'button_text' => $s->button_text,
                    'button_url' => $s->button_url,
                ])),
                active: 0,
                next() { this.active = (this.active + 1) % this.slides.length },
                go(i) { this.active = i },
            }"
            x-init="slides.length > 1 && setInterval(() => next(), 6500)"
            class="relative overflow-hidden bg-ink">

            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="blueprint-drift absolute -right-24 -top-24 h-96 w-96 rounded-full bg-brass/20 blur-3xl"></div>
                <div class="blueprint-drift-slow absolute -bottom-32 -left-16 h-96 w-96 rounded-full bg-forest/40 blur-3xl"></div>
                <div class="blueprint-grid absolute inset-0"></div>
            </div>

            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="active === index" x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak
                    class="relative flex min-h-144 items-center bg-cover bg-center sm:min-h-168"
                    :style="slide.image ? `background-image: linear-gradient(to top, rgba(27,27,24,.85), rgba(27,27,24,.55)), url('${slide.image}')` : ''">

                    <div class="relative mx-auto w-full max-w-4xl px-6 py-24 text-center">
                        <p class="reveal reveal-d1 text-xs font-medium uppercase tracking-widest text-brass">
                            {{ setting('site_name', config('app.name')) }}
                        </p>
                        <h1 class="reveal reveal-d2 mt-4 font-display text-4xl font-semibold leading-tight text-paper text-balance sm:text-5xl lg:text-6xl"
                            x-text="slide.title"></h1>
                        <p x-show="slide.subtitle" class="reveal reveal-d3 mx-auto mt-5 max-w-2xl text-base leading-8 text-brass-soft/70 sm:text-lg"
                            x-text="slide.subtitle"></p>
                        <div x-show="slide.button_text" class="reveal reveal-d4 mt-8">
                            <a :href="slide.button_url || '#contact'" class="btn-shimmer inline-flex h-12 items-center justify-center rounded-lg bg-brass px-7 text-sm font-semibold text-white transition hover:bg-brass/90"
                                x-text="slide.button_text"></a>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="slides.length > 1" class="absolute inset-x-0 bottom-8 flex items-center justify-center gap-2">
                <template x-for="(slide, index) in slides" :key="index">
                    <button type="button" @click="go(index)" aria-label="عرض الشريحة"
                        class="h-2 rounded-full transition-all"
                        :class="active === index ? 'w-8 bg-brass' : 'w-2 bg-white/30 hover:bg-white/50'"></button>
                </template>
            </div>
        </section>
    @endif

    {{-- Stats --}}
    @if ($stats->isNotEmpty())
        <section class="border-b border-line bg-surface">
            <div class="mx-auto grid max-w-7xl grid-cols-2 gap-8 px-6 py-14 sm:grid-cols-4">
                @foreach ($stats as $stat)
                    <div x-data="{ n: 0 }" x-intersect.once="
                            const target = {{ $stat->number }};
                            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { n = target; return; }
                            const start = performance.now(); const dur = 1200 + {{ $loop->index }} * 120;
                            const step = (ts) => {
                                const p = Math.min((ts - start) / dur, 1);
                                n = Math.floor(p * target);
                                if (p < 1) requestAnimationFrame(step);
                            };
                            requestAnimationFrame(step);
                        "
                        class="text-center">
                        <p class="font-display text-3xl font-bold text-ink sm:text-4xl">
                            <span x-text="n"></span>{{ $stat->suffix }}
                        </p>
                        <p class="mt-2 text-sm text-ink-soft">{{ $stat->label }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Why choose us --}}
    @if ($whyChooseUs)
        <section class="relative overflow-hidden bg-paper py-20 sm:py-28">
            <div class="paper-grid pointer-events-none absolute inset-0"></div>
            <div class="relative mx-auto max-w-6xl px-6">
                <div class="mx-auto max-w-2xl text-center" x-data x-intersect.once="$el.classList.add('reveal')">
                    @if ($whyChooseUs->subtitle)
                        <p class="text-xs font-medium uppercase tracking-widest text-brass">{{ $whyChooseUs->subtitle }}</p>
                    @endif
                    <h2 class="mt-4 font-display text-3xl font-semibold text-ink text-balance sm:text-4xl">{{ $whyChooseUs->title }}</h2>
                    @if ($whyChooseUs->content)
                        <p class="mt-4 text-sm leading-7 text-ink-soft">{{ $whyChooseUs->content }}</p>
                    @endif
                </div>

                @if (!empty($whyChooseUs->extra_data['points']))
                    <div class="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-3">
                        @foreach ($whyChooseUs->extra_data['points'] as $point)
                            <div x-data x-intersect.once="$el.classList.add('reveal')" style="animation-delay: {{ $loop->index * 0.1 }}s"
                                class="rounded-xl border border-line bg-surface p-6 text-center">
                                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brass-soft text-brass">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $pointIcons[$point['icon'] ?? ''] ?? $defaultIcon }}" />
                                    </svg>
                                </span>
                                <h3 class="mt-4 font-display text-lg font-semibold text-ink">{{ $point['title'] ?? '' }}</h3>
                                <p class="mt-2 text-sm leading-6 text-ink-soft">{{ $point['description'] ?? '' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- Services --}}
    @if ($services->isNotEmpty())
        <section id="services" class="scroll-mt-20 bg-surface py-20 sm:py-28">
            <div class="mx-auto max-w-6xl px-6">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-xs font-medium uppercase tracking-widest text-brass">خدماتنا</p>
                    <h2 class="mt-4 font-display text-3xl font-semibold text-ink text-balance sm:text-4xl">حلول متكاملة لكل مشروع</h2>
                </div>

                <div class="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <div x-data x-intersect.once="$el.classList.add('reveal')" style="animation-delay: {{ $loop->index * 0.08 }}s"
                            class="rounded-xl border border-line bg-paper p-6 transition hover:border-brass/30 hover:shadow-sm">
                            <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-ink font-display text-base font-bold text-brass-soft">
                                {{ mb_substr($service->title, 0, 1) }}
                            </span>
                            <h3 class="mt-5 font-display text-lg font-semibold text-ink">{{ $service->title }}</h3>
                            @if ($service->category)
                                <span class="mt-3 inline-flex rounded-full border border-line bg-surface px-2.5 py-1 text-xs text-ink-soft">
                                    {{ $service->category->name }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Process steps --}}
    @if ($processSteps->isNotEmpty())
        <section class="bg-paper py-20 sm:py-28">
            <div class="mx-auto max-w-6xl px-6">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-xs font-medium uppercase tracking-widest text-brass">آلية العمل</p>
                    <h2 class="mt-4 font-display text-3xl font-semibold text-ink text-balance sm:text-4xl">من أول اتصال حتى التسليم</h2>
                </div>

                <div class="mt-14 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach ($processSteps as $step)
                        <div x-data x-intersect.once="$el.classList.add('reveal')" style="animation-delay: {{ $loop->index * 0.1 }}s"
                            class="relative text-center">
                            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full border-2 border-brass bg-surface font-display text-xl font-bold text-brass">
                                {{ $step->step_number }}
                            </span>
                            @if (!$loop->last)
                                <span class="absolute right-1/2 top-7 hidden h-px w-full -translate-y-1/2 bg-line lg:block" aria-hidden="true"></span>
                            @endif
                            <h3 class="relative mt-4 font-display text-base font-semibold text-ink">{{ $step->title }}</h3>
                            @if ($step->description)
                                <p class="relative mt-2 text-sm leading-6 text-ink-soft">{{ $step->description }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Projects --}}
    @if ($projects->isNotEmpty())
        <section id="projects" class="scroll-mt-20 bg-surface py-20 sm:py-28">
            <div class="mx-auto max-w-6xl px-6">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-xs font-medium uppercase tracking-widest text-brass">أعمالنا</p>
                    <h2 class="mt-4 font-display text-3xl font-semibold text-ink text-balance sm:text-4xl">مشاريع تحمل بصمتنا</h2>
                </div>

                <div class="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        <div x-data x-intersect.once="$el.classList.add('reveal')" style="animation-delay: {{ $loop->index * 0.08 }}s"
                            class="group overflow-hidden rounded-xl border border-line bg-paper">
                            <div class="relative h-48 overflow-hidden bg-ink">
                                @if ($project->cover_image)
                                    <img src="{{ asset('storage/'.$project->cover_image) }}" alt="{{ $project->title }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @endif
                                <div class="absolute inset-0 bg-linear-to-t from-ink/70 via-transparent to-transparent"></div>
                                @if ($project->category)
                                    <span class="absolute right-3 top-3 rounded-full bg-surface/90 px-2.5 py-1 text-xs font-medium text-ink">
                                        {{ $project->category->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="font-display text-lg font-semibold text-ink">{{ $project->title }}</h3>
                                @if ($project->location)
                                    <p class="mt-1 flex items-center gap-1.5 text-sm text-ink-soft">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                        {{ $project->location }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Testimonials --}}
    @if ($testimonials->isNotEmpty())
        <section id="testimonials" class="scroll-mt-20 bg-ink py-20 sm:py-28">
            <div class="mx-auto max-w-6xl px-6">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-xs font-medium uppercase tracking-widest text-brass">آراء العملاء</p>
                    <h2 class="mt-4 font-display text-3xl font-semibold text-paper text-balance sm:text-4xl">ثقة عملائنا شهادتنا</h2>
                </div>

                <div class="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <div x-data x-intersect.once="$el.classList.add('reveal')" style="animation-delay: {{ $loop->index * 0.08 }}s"
                            class="rounded-xl border border-white/10 bg-white/5 p-6">
                            <div class="flex items-center gap-1 text-brass" aria-hidden="true">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="{{ $i <= $testimonial->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.538 1.118l-3.37-2.447a1 1 0 00-1.175 0l-3.37 2.447c-.783.57-1.838-.196-1.538-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z" />
                                    </svg>
                                @endfor
                            </div>
                            <p class="mt-4 text-sm leading-7 text-brass-soft/80">"{{ $testimonial->review }}"</p>
                            <div class="mt-5 flex items-center gap-3 border-t border-white/10 pt-5">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brass-soft font-display text-sm font-bold text-ink">
                                    {{ mb_substr($testimonial->client_name, 0, 1) }}
                                </span>
                                <p class="text-sm font-medium text-paper">{{ $testimonial->client_name }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FAQ --}}
    @if ($faqs->isNotEmpty())
        <section id="faq" class="scroll-mt-20 bg-paper py-20 sm:py-28">
            <div class="mx-auto max-w-3xl px-6">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-xs font-medium uppercase tracking-widest text-brass">الأسئلة الشائعة</p>
                    <h2 class="mt-4 font-display text-3xl font-semibold text-ink text-balance sm:text-4xl">لديك سؤال؟</h2>
                </div>

                <div x-data="{ openIndex: 0 }" class="mt-12 space-y-3">
                    @foreach ($faqs as $faq)
                        <div class="overflow-hidden rounded-xl border border-line bg-surface">
                            <button type="button" @click="openIndex = openIndex === {{ $loop->index }} ? null : {{ $loop->index }}"
                                class="flex w-full items-center justify-between gap-4 p-5 text-right">
                                <span class="font-medium text-ink">{{ $faq->question }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-ink-soft transition-transform"
                                    :class="openIndex === {{ $loop->index }} ? 'rotate-180' : ''"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="openIndex === {{ $loop->index }}" x-collapse x-cloak class="px-5 pb-5 text-sm leading-7 text-ink-soft">
                                {{ $faq->answer }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Contact CTA --}}
    <section id="contact" class="scroll-mt-20 relative overflow-hidden bg-ink py-20 sm:py-28">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="blueprint-drift absolute -right-24 -top-24 h-96 w-96 rounded-full bg-brass/20 blur-3xl"></div>
            <div class="blueprint-grid absolute inset-0"></div>
        </div>

        <div class="relative mx-auto max-w-3xl px-6 text-center">
            <h2 class="font-display text-3xl font-semibold text-paper text-balance sm:text-4xl">
                {{ $contactCta->title ?? 'جاهز لبدء مشروعك؟' }}
            </h2>
            @if ($contactCta?->subtitle)
                <p class="mt-4 text-sm leading-7 text-brass-soft/70 sm:text-base">{{ $contactCta->subtitle }}</p>
            @endif

            <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                @if (setting('contact_phone'))
                    <a href="tel:{{ setting('contact_phone') }}"
                        class="inline-flex h-12 items-center justify-center gap-2 rounded-lg bg-surface px-6 text-sm font-semibold text-ink transition hover:bg-brass-soft">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                        اتصل بنا
                    </a>
                @endif
                @if ($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener"
                        class="inline-flex h-12 items-center justify-center gap-2 rounded-lg border border-white/20 px-6 text-sm font-semibold text-paper transition hover:bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2a10 10 0 00-8.6 15.1L2 22l4.9-1.4A10 10 0 1012 2zm0 18a8 8 0 01-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1112 20zm4.4-5.6c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1-.2.2-.6.8-.8 1-.1.1-.3.2-.5.1-.2-.1-1-.4-2-1.2-.7-.6-1.2-1.4-1.4-1.6-.1-.2 0-.4.1-.5l.4-.4c.1-.1.1-.2.2-.4v-.4c0-.1-.5-1.3-.7-1.8-.2-.4-.4-.4-.5-.4h-.5c-.2 0-.4.1-.6.3-.2.2-.8.8-.8 1.9 0 1.1.8 2.2.9 2.4.1.2 1.6 2.4 3.9 3.4.5.2 1 .4 1.3.5.5.1 1 .1 1.3.1.4-.1 1.4-.6 1.6-1.1.2-.5.2-.9.1-1z" />
                        </svg>
                        واتساب
                    </a>
                @endif
                <button type="button" @click="window.dispatchEvent(new CustomEvent('open-chat-widget'))"
                    class="inline-flex h-12 items-center justify-center gap-2 rounded-lg border border-white/20 px-6 text-sm font-semibold text-paper transition hover:bg-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                    </svg>
                    محادثة مباشرة
                </button>
            </div>
        </div>
    </section>

</x-app-layout>
