@extends('layouts.app')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "ContactPage",
    "name": "Contact Us | Formnexa",
    "description": "Contact Formnexa support and product team. Inquire about invoice generation, ATS resume builder, and PDF converter tools.",
    "url": "{{ url('/contact') }}"
}
</script>
@endpush

@section('content')
<div class="relative overflow-hidden bg-gradient-to-b from-[#f2f7f4]/80 via-[#f8faf8] to-[#ffffff] min-h-screen">

    {{-- Subtle Ambient Glows --}}
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[720px] h-[340px] bg-[#377d60]/10 rounded-full blur-[100px] pointer-events-none -z-0"></div>
    <div class="absolute top-64 right-[-80px] w-[360px] h-[360px] bg-[#7655d7]/5 rounded-full blur-[90px] pointer-events-none -z-0"></div>

    <div class="relative z-10 max-w-[1180px] mx-auto px-4 sm:px-6 pt-8 sm:pt-12 pb-16 sm:pb-24 space-y-10 sm:space-y-12">

        {{-- Breadcrumb Navigation --}}
        <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-semibold text-[#68736d]">
            <a href="{{ route('home') }}" class="hover:text-[#184f3a] transition-colors">Home</a>
            <svg class="w-3.5 h-3.5 text-[#98a39c]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
            </svg>
            <span class="text-[#17211b] font-bold">Contact Us</span>
        </nav>

        {{-- Hero Section Header --}}
        <div class="max-w-[760px] space-y-4">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-[#c4eccf] shadow-[0_2px_12px_rgba(24,79,58,0.06)] text-xs font-bold text-[#184f3a]">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>Direct Support &amp; Feedback</span>
                <span class="text-[#98a39c]">&bull;</span>
                <span class="text-[#536058] font-medium">Replies within 24 hours</span>
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-[52px] font-extrabold text-[#17211b] tracking-[-0.03em] leading-[1.12]">
                Get in touch with the <span class="bg-gradient-to-r from-[#184f3a] via-[#2f7d5c] to-[#184f3a] bg-clip-text text-transparent">Formnexa team</span>.
            </h1>

            <p class="text-base sm:text-lg text-[#536058] leading-relaxed">
                Have questions about ATS resume optimization, multi-currency invoicing, or PDF conversions? Share your feedback, report a bug, or request a feature below.
            </p>

            {{-- Trust Badges Strip --}}
            <div class="pt-2 flex flex-wrap items-center gap-4 text-xs font-semibold text-[#536058]">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#184f3a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>No automated bot runaround</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#184f3a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Direct engineering review</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#184f3a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Zero spam or marketing newsletters</span>
                </div>
            </div>
        </div>

        {{-- Success / Error Feedback Alerts --}}
        @if(session('success'))
            <div class="p-5 sm:p-6 rounded-3xl bg-[#eaf7ee] border border-[#b8ecc8] text-[#184f3a] shadow-sm flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-full bg-[#184f3a] text-white flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <strong class="block text-sm sm:text-base font-extrabold text-[#17211b]">Message Submitted!</strong>
                    <p class="text-xs sm:text-sm text-[#235843] leading-relaxed">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-5 sm:p-6 rounded-3xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm space-y-2">
                <strong class="block text-sm font-bold">Please correct the highlighted fields:</strong>
                <ul class="list-disc list-inside text-xs sm:text-sm space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Main Two-Column Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

            {{-- Left Column: Interactive Contact Form --}}
            <div class="lg:col-span-7 bg-white rounded-3xl border border-[#dfe5e0] p-6 sm:p-9 shadow-[0_20px_50px_-15px_rgba(24,79,58,0.06)] relative overflow-hidden"
                 x-data="{
                     subject: @js(old('subject', '')),
                     message: @js(old('message', '')),
                     charCount: 0,
                     setTopic(t) {
                         this.subject = t;
                     }
                 }"
                 x-init="charCount = message.length">

                {{-- Card Top Brand Bar --}}
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#184f3a] via-[#377d60] to-[#7655d7]"></div>

                <div class="mb-6 space-y-1">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">Send Us a Direct Message</h2>
                    <p class="text-xs sm:text-sm text-[#68736d]">Fill out the form below. We read every message and respond promptly.</p>
                </div>

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Honeypot anti-spam (hidden) --}}
                    <input type="text" name="website_hp" style="display:none !important;" tabindex="-1" autocomplete="off">

                    {{-- Quick Topic Pill Selector --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#17211b]">
                            Choose a Topic <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach([
                                'Resume Builder & ATS Question' => '📄 ATS Resume',
                                'Invoice Maker & Billing' => '🧾 Invoices & Docs',
                                'PDF Converter Tools' => '🔄 PDF Tools',
                                'Feature Request & Feedback' => '💡 Feature Idea',
                                'Bug Report' => '🐛 Bug Report',
                                'General Inquiry' => '💬 General Question',
                            ] as $val => $label)
                                <button type="button"
                                        @click="setTopic('{{ $val }}')"
                                        :class="subject === '{{ $val }}'
                                            ? 'bg-[#184f3a] text-white border-[#184f3a] shadow-sm'
                                            : 'bg-[#fafbf9] text-[#3f4a43] border-[#dfe5e0] hover:border-[#184f3a] hover:bg-white'"
                                        class="px-3 py-2.5 rounded-xl text-xs font-bold border text-left transition-all duration-150 cursor-pointer flex items-center justify-between">
                                    <span>{{ $label }}</span>
                                    <span x-show="subject === '{{ $val }}'" class="text-xs font-extrabold text-[#c8f3dc]">&check;</span>
                                </button>
                            @endforeach
                        </div>

                        {{-- Fallback Native Dropdown (Synchronized with Pills for full accessibility) --}}
                        <div class="pt-1.5">
                            <select name="subject" id="subject" required x-model="subject"
                                    class="w-full px-4 py-2.5 rounded-xl border border-[#dfe5e0] focus:border-[#184f3a] focus:ring-4 focus:ring-[#184f3a]/10 text-xs sm:text-sm text-[#17211b] outline-none bg-white transition">
                                <option value="">Or select an inquiry topic from the list...</option>
                                <option value="Resume Builder & ATS Question">ATS Resume Builder &amp; Templates</option>
                                <option value="Invoice Maker & Billing">Invoice Maker &amp; Multi-Currency Billing</option>
                                <option value="PDF Converter Tools">PDF Conversion Tools (Word, Excel, JPG)</option>
                                <option value="Feature Request & Feedback">Feature Suggestion or Feedback</option>
                                <option value="Bug Report">Report a Technical Issue</option>
                                <option value="General Inquiry">General Question / Partnership</option>
                            </select>
                        </div>
                    </div>

                    {{-- Name Input --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-[#17211b]">
                                Your Name <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-[#68736d]">How should we address you?</span>
                        </div>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                               placeholder="e.g. Sarah Vance"
                               class="w-full px-4 py-3 rounded-xl border border-[#dfe5e0] bg-[#fafbf9] focus:bg-white focus:border-[#184f3a] focus:ring-4 focus:ring-[#184f3a]/10 text-sm text-[#17211b] outline-none transition">
                    </div>

                    {{-- Email Input (Optional) --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#17211b]">
                                Email Address <span class="text-[11px] font-normal text-[#68736d] lowercase">(optional)</span>
                            </label>
                            <span class="text-[11px] text-[#68736d]">Provide if you'd like a response</span>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               placeholder="e.g. sarah@example.com (optional)"
                               class="w-full px-4 py-3 rounded-xl border border-[#dfe5e0] bg-[#fafbf9] focus:bg-white focus:border-[#184f3a] focus:ring-4 focus:ring-[#184f3a]/10 text-sm text-[#17211b] outline-none transition">
                    </div>

                    {{-- Message Textarea --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="message" class="block text-xs font-bold uppercase tracking-wider text-[#17211b]">
                                Your Message <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] font-mono text-[#68736d]" x-text="charCount + ' / 3000 chars'">0 / 3000 chars</span>
                        </div>
                        <textarea name="message" id="message" rows="5" required minlength="10" maxlength="3000"
                                  x-model="message"
                                  @input="charCount = message.length"
                                  placeholder="How can we help? Please describe your question, suggested feature, or issue in detail..."
                                  class="w-full px-4 py-3.5 rounded-xl border border-[#dfe5e0] bg-[#fafbf9] focus:bg-white focus:border-[#184f3a] focus:ring-4 focus:ring-[#184f3a]/10 text-sm text-[#17211b] outline-none resize-y transition leading-relaxed">{{ old('message') }}</textarea>
                    </div>

                    {{-- Action Button & Disclaimer --}}
                    <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <button type="submit"
                                class="group inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-sm font-bold shadow-sm hover:shadow-lg hover:shadow-[#184f3a]/20 transition-all duration-200 cursor-pointer">
                            <span>Send Message</span>
                            <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                        <div class="flex items-center gap-2 text-xs text-[#68736d]">
                            <svg class="w-4 h-4 text-[#184f3a] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>Encrypted &bull; Direct inbox delivery</span>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Right Column: Information, FAQ Accordion & Quick Tools --}}
            <div class="lg:col-span-5 space-y-6">

                {{-- Card 1: Fast Support Highlights --}}
                <div class="bg-white rounded-3xl border border-[#dfe5e0] p-6 sm:p-7 shadow-sm space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#eaf7ee] text-[#184f3a] flex items-center justify-center shrink-0">
                            <x-icon name="sparkles" class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-[#17211b]">Fast &amp; Direct Support</h2>
                            <p class="text-xs text-[#68736d]">No bureaucracy, no endless hold queues</p>
                        </div>
                    </div>

                    <div class="space-y-3.5 text-xs text-[#536058]">
                        <div class="flex items-start gap-3 p-3 rounded-2xl bg-[#fafbf9] border border-[#edf1ed]">
                            <div class="w-6 h-6 rounded-lg bg-[#eaf7ee] text-[#184f3a] flex items-center justify-center shrink-0 mt-0.5 font-bold">⏱</div>
                            <div>
                                <strong class="block text-[#17211b] font-bold">24-Hour Average Turnaround</strong>
                                <span>We review and address inbound feedback within 1 business day.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-2xl bg-[#fafbf9] border border-[#edf1ed]">
                            <div class="w-6 h-6 rounded-lg bg-[#ede9fe] text-[#7655d7] flex items-center justify-center shrink-0 mt-0.5 font-bold">🎯</div>
                            <div>
                                <strong class="block text-[#17211b] font-bold">Direct Developer Review</strong>
                                <span>Bug reports and template suggestions go straight to the active developers.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-2xl bg-[#fafbf9] border border-[#edf1ed]">
                            <div class="w-6 h-6 rounded-lg bg-[#eaf7ee] text-[#184f3a] flex items-center justify-center shrink-0 mt-0.5 font-bold">🛡</div>
                            <div>
                                <strong class="block text-[#17211b] font-bold">Privacy Guaranteed</strong>
                                <span>Your details are never sold, rented, or added to advertising databases.</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Interactive Quick FAQ Accordion --}}
                <div class="bg-white rounded-3xl border border-[#dfe5e0] p-6 sm:p-7 shadow-sm space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#ede9fe] text-[#7655d7] flex items-center justify-center shrink-0">
                            <x-icon name="document-text" class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-[#17211b]">Frequently Answered Questions</h2>
                            <p class="text-xs text-[#68736d]">Instant answers to common queries</p>
                        </div>
                    </div>

                    <div class="space-y-2.5 divide-y divide-[#edf1ed]">
                        {{-- FAQ Item 1 --}}
                        <div x-data="{ open: false }" class="pt-2.5 first:pt-0">
                            <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between text-left text-xs font-bold text-[#17211b] hover:text-[#184f3a] transition-colors py-1 cursor-pointer">
                                <span>How does the ATS Resume formatting pass automated screenings?</span>
                                <svg class="w-4 h-4 text-[#98a39c] transition-transform duration-200" :class="open ? 'rotate-180 text-[#184f3a]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="mt-2 text-xs text-[#536058] leading-relaxed pr-2">
                                Formnexa builds single-column layouts using standard section headers (Experience, Skills, Education) and selectable vector typography to allow screening systems to extract candidate details cleanly.
                            </div>
                        </div>

                        {{-- FAQ Item 2 --}}
                        <div x-data="{ open: false }" class="pt-2.5">
                            <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between text-left text-xs font-bold text-[#17211b] hover:text-[#184f3a] transition-colors py-1 cursor-pointer">
                                <span>Are invoices and documents 100% free with no watermarks?</span>
                                <svg class="w-4 h-4 text-[#98a39c] transition-transform duration-200" :class="open ? 'rotate-180 text-[#184f3a]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="mt-2 text-xs text-[#536058] leading-relaxed pr-2">
                                Yes. Every invoice, quote, receipt, and purchase order created on Formnexa is completely free to download in PDF, print, or share. There are zero watermarks and no hidden fees.
                            </div>
                        </div>

                        {{-- FAQ Item 3 --}}
                        <div x-data="{ open: false }" class="pt-2.5">
                            <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between text-left text-xs font-bold text-[#17211b] hover:text-[#184f3a] transition-colors py-1 cursor-pointer">
                                <span>Can I request a custom document format or currency?</span>
                                <svg class="w-4 h-4 text-[#98a39c] transition-transform duration-200" :class="open ? 'rotate-180 text-[#184f3a]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="mt-2 text-xs text-[#536058] leading-relaxed pr-2">
                                Absolutely! Select "Feature Idea" in the topic selector above, let us know what document type or country tax schema you need, and we will prioritize it in upcoming tool updates.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Quick Tool Fast-Links --}}
                <div class="bg-gradient-to-br from-[#f2f7f3] to-[#eaf2ec] rounded-3xl border border-[#d6e5d8] p-6 sm:p-7 space-y-3.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-[#184f3a] uppercase tracking-wider">Explore Formnexa Tools</span>
                        <span class="text-[11px] font-semibold text-[#536058]">100% Free Online</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                        <a href="{{ route('resume.templates') }}"
                           class="p-3 rounded-2xl bg-white/90 border border-[#dfe5e0] hover:border-[#184f3a] text-[#17211b] hover:text-[#184f3a] transition-all duration-150 flex items-center justify-between shadow-xs">
                            <span class="truncate">ATS CV Studio</span>
                            <span class="text-[#184f3a]">&rarr;</span>
                        </a>
                        <a href="{{ route('invoice.studio') }}"
                           class="p-3 rounded-2xl bg-white/90 border border-[#dfe5e0] hover:border-[#184f3a] text-[#17211b] hover:text-[#184f3a] transition-all duration-150 flex items-center justify-between shadow-xs">
                            <span class="truncate">Invoice Maker</span>
                            <span class="text-[#184f3a]">&rarr;</span>
                        </a>
                        <a href="{{ route('convert.hub') }}"
                           class="p-3 rounded-2xl bg-white/90 border border-[#dfe5e0] hover:border-[#184f3a] text-[#17211b] hover:text-[#184f3a] transition-all duration-150 flex items-center justify-between shadow-xs">
                            <span class="truncate">10 PDF Tools</span>
                            <span class="text-[#184f3a]">&rarr;</span>
                        </a>
                        <a href="{{ route('blog.index') }}"
                           class="p-3 rounded-2xl bg-white/90 border border-[#dfe5e0] hover:border-[#184f3a] text-[#17211b] hover:text-[#184f3a] transition-all duration-150 flex items-center justify-between shadow-xs">
                            <span class="truncate">Help &amp; Guides</span>
                            <span class="text-[#184f3a]">&rarr;</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
