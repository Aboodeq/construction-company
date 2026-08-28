@php
    $statusLabels = ['new' => 'جديد', 'read' => 'تمت المشاهدة', 'in_progress' => 'قيد المعالجة', 'closed' => 'مغلق'];
    $fileTypeLabels = ['image' => 'صورة', 'plan' => 'مخطط'];
@endphp

<x-admin.layouts.app title="تفاصيل طلب سعر">
    <div class="mx-auto w-full max-w-4xl">
        <div class="mb-8">
            <a href="{{ route('admin.quote-requests.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-ink-soft transition hover:text-brass">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                        clip-rule="evenodd" />
                </svg>
                العودة إلى طلبات الأسعار
            </a>
            <h2 class="mt-4 font-display text-2xl font-semibold text-ink">{{ $quoteRequest->name }}</h2>
            <p class="mt-2 text-sm text-ink-soft">
                تم الإرسال {{ $quoteRequest->created_at?->diffForHumans() }} ({{ $quoteRequest->created_at?->format('Y-m-d H:i') }})
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:items-start">
            <div class="space-y-6 lg:col-span-2">
                <section class="rounded-lg border border-line bg-surface p-6">
                    <h3 class="font-display text-lg font-semibold text-ink">بيانات التواصل</h3>
                    <dl class="mt-4 grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium text-ink-soft">الاسم</dt>
                            <dd class="mt-1 text-ink">{{ $quoteRequest->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-ink-soft">الهاتف</dt>
                            <dd class="mt-1 text-ink">
                                <a href="tel:{{ $quoteRequest->phone }}" class="hover:text-brass">{{ $quoteRequest->phone }}</a>
                            </dd>
                        </div>
                        @if ($quoteRequest->email)
                            <div>
                                <dt class="text-xs font-medium text-ink-soft">البريد الإلكتروني</dt>
                                <dd class="mt-1 text-ink">
                                    <a href="mailto:{{ $quoteRequest->email }}" class="hover:text-brass">{{ $quoteRequest->email }}</a>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </section>

                <section class="rounded-lg border border-line bg-surface p-6">
                    <h3 class="font-display text-lg font-semibold text-ink">تفاصيل المشروع</h3>
                    <dl class="mt-4 grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium text-ink-soft">نوع المشروع</dt>
                            <dd class="mt-1 text-ink">{{ $quoteRequest->project_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-ink-soft">المدينة</dt>
                            <dd class="mt-1 text-ink">{{ $quoteRequest->city }}</dd>
                        </div>
                        @if ($quoteRequest->area)
                            <div>
                                <dt class="text-xs font-medium text-ink-soft">المساحة</dt>
                                <dd class="mt-1 text-ink">{{ number_format((float) $quoteRequest->area) }} م²</dd>
                            </div>
                        @endif
                        @if ($quoteRequest->estimated_budget)
                            <div>
                                <dt class="text-xs font-medium text-ink-soft">الميزانية التقديرية</dt>
                                <dd class="mt-1 text-ink">{{ $quoteRequest->estimated_budget }}</dd>
                            </div>
                        @endif
                    </dl>
                    @if ($quoteRequest->description)
                        <div class="mt-4 border-t border-line pt-4">
                            <dt class="text-xs font-medium text-ink-soft">تفاصيل إضافية</dt>
                            <dd class="mt-2 text-sm leading-7 text-ink">{{ $quoteRequest->description }}</dd>
                        </div>
                    @endif
                </section>

                @can('quote-requests.edit')
                    @if ($quoteRequest->email)
                        <x-admin.email-reply-panel
                            :action="route('admin.quote-requests.reply-email', $quoteRequest)"
                            :toName="$quoteRequest->name"
                            :toEmail="$quoteRequest->email"
                            :defaultSubject="'رد بخصوص طلب السعر الخاص بك'"
                            :replies="$quoteRequest->emailReplies"
                        />
                    @endif
                @endcan

                @if ($quoteRequest->files->isNotEmpty())
                    <section class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-lg font-semibold text-ink">الملفات المرفقة</h3>
                        <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3">
                            @foreach ($quoteRequest->files as $file)
                                <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank"
                                    class="block overflow-hidden rounded-lg border border-line">
                                    <img src="{{ asset('storage/'.$file->file_path) }}" alt="" class="h-24 w-full object-cover">
                                    <span class="block bg-paper px-2 py-1 text-center text-xs text-ink-soft">
                                        {{ $fileTypeLabels[$file->type] ?? $file->type }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="space-y-6 lg:sticky lg:top-24">
                @can('quote-requests.edit')
                    <div class="rounded-lg border border-line bg-surface p-6">
                        <h3 class="font-display text-base font-semibold text-ink">حالة الطلب</h3>
                        <form method="POST" action="{{ route('admin.quote-requests.update-status', $quoteRequest) }}" class="mt-4 space-y-3">
                            @csrf
                            @method('PATCH')
                            <select name="status"
                                class="h-11 w-full rounded-lg border-line bg-paper text-sm text-ink focus:border-brass focus:ring-brass">
                                @foreach ($statusLabels as $value => $label)
                                    <option value="{{ $value }}" @selected($quoteRequest->status === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="submit"
                                class="inline-flex h-11 w-full items-center justify-center rounded-lg bg-ink text-sm font-semibold text-brass-soft transition hover:bg-brass hover:text-white">
                                تحديث الحالة
                            </button>
                        </form>
                    </div>
                @endcan

                @can('quote-requests.delete')
                    <div class="rounded-lg border border-red-100 bg-red-50/40 p-6">
                        <h3 class="font-display text-base font-semibold text-ink">منطقة الخطر</h3>
                        <p class="mt-1 text-sm text-ink-soft">حذف الطلب نهائي ولا يمكن التراجع عنه.</p>
                        <x-admin.confirm-form
                            :action="route('admin.quote-requests.destroy', $quoteRequest)"
                            title="حذف الطلب"
                            :message="'سيتم حذف طلب «'.$quoteRequest->name.'» نهائيًا. هل تريد المتابعة؟'"
                            class="mt-4 h-11 w-full px-5 text-sm font-semibold">
                            حذف الطلب
                        </x-admin.confirm-form>
                    </div>
                @endcan
            </aside>
        </div>
    </div>
</x-admin.layouts.app>
