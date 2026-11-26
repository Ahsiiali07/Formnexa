@extends('layouts.app')

<x-schema.tool
    :name="$page['h1']"
    :description="$page['description']"
    category="BusinessApplication"
/>

@section('content')
<div class="min-h-screen bg-[#fafbfa] text-[#17211b] py-8 sm:py-12"
     x-data="resignationStudio(@js($page['preset_reason']), @js($page['preset_tone']), {{ (int) ($page['preset_days'] ?? 14) }})"
     x-cloak>

    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        {{-- Visible Breadcrumbs + BreadcrumbList JSON-LD --}}
        <div class="mb-5 flex justify-center">
            <x-schema.breadcrumbs :items="[
                ['Home', url('/')],
                ['Resignation Letter Generator', route('letter.resignation')],
                [$page['h1'], route('letter.resignation.landing', $page['slug'])],
            ]" />
        </div>

        {{-- Top Badge --}}
        <div class="flex items-center justify-center gap-2 mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e8f7ed] text-[#184f3a] border border-[#c8f3dc]">
                <span class="w-2 h-2 rounded-full bg-[#184f3a]"></span>
                {{ $page['badge'] }} &bull; Free PDF &amp; Word Download
            </span>
        </div>

        {{-- Unique Header Copy --}}
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black font-display tracking-tight text-[#17211b] mb-3">
                {{ $page['h1'] }}
            </h1>
            <p class="text-base sm:text-lg text-[#55635a] leading-relaxed mb-4">
                {{ $page['subtitle'] }}
            </p>
            <a href="{{ route('letter.resignation') }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-[#184f3a] hover:underline">
                <span>&larr; Back to Main Resignation Letter Generator (All 8 Reasons)</span>
            </a>
        </div>

        {{-- Shared Studio Widget Preselected for This Reason & Tone --}}
        @include('letters._studio-widget', [
            'presetReason' => $page['preset_reason'],
            'presetTone' => $page['preset_tone'],
            'presetDays' => $page['preset_days'] ?? 14,
        ])

        {{-- Unique Scenario Content, Sample Letter, Tips & FAQ --}}
        <div class="mt-16 pt-12 border-t border-[#e2e8e3] space-y-10">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Scenario Advice --}}
                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-4">
                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-mono font-bold uppercase tracking-wider">DRAFT</span>
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        {{ $page['when_to_use']['heading'] }}
                    </h2>
                    @foreach($page['when_to_use']['paragraphs'] as $paragraph)
                        <p class="text-sm text-[#536058] leading-relaxed">{{ $paragraph }}</p>
                    @endforeach
                </section>

                {{-- Scenario Tips --}}
                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-4">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        Practical Tips Before You Submit
                    </h2>
                    <ul class="space-y-2.5 text-sm text-[#536058] leading-relaxed">
                        @foreach($page['tips'] as $tip)
                            <li class="flex items-start gap-2.5">
                                <span class="text-[#184f3a] font-bold mt-0.5">&check;</span>
                                <span>{{ $tip }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </div>

            {{-- Unique Sample Letter --}}
            <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        {{ $page['sample_heading'] }}
                    </h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-mono font-bold uppercase tracking-wider">DRAFT COPY</span>
                </div>
                <pre class="bg-[#f8f8f3] border border-[#dfe4df] rounded-2xl p-5 sm:p-6 text-xs sm:text-sm text-[#17211b] font-sans whitespace-pre-wrap leading-relaxed">{{ $page['sample_letter'] }}</pre>
            </section>

            {{-- Scenario FAQ --}}
            <div class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm">
                <x-faq :items="$page['faqs']" heading="Common Questions About This Situation" eyebrow="Resignation FAQ" />
            </div>

            {{-- Related Tools & Sibling Pages --}}
            @php
                $relatedTools = [
                    [
                        'title' => 'Main Resignation Letter Generator',
                        'description' => 'Switch among all 8 resignation reasons and 4 tones in one place.',
                        'url' => route('letter.resignation'),
                        'badge' => 'All Options',
                        'cta' => 'Open Main Generator',
                    ],
                    [
                        'title' => 'Free ATS Resume Builder',
                        'description' => 'Update your CV with your latest role before your transition starts.',
                        'url' => route('resume.builder'),
                        'badge' => count(\App\Support\ResumeCatalog::templates()) . ' Templates',
                        'cta' => 'Update Resume',
                    ],
                    [
                        'title' => 'Free ATS Resume Checker',
                        'description' => 'Scan your refreshed resume for ATS parsing safety and missing keywords.',
                        'url' => route('resume.checker'),
                        'badge' => '0-100 Score',
                        'cta' => 'Check Resume',
                    ],
                ];
            @endphp
            <x-related-tools :tools="$relatedTools" heading="Next Steps for Your Transition" eyebrow="Career Tools" />

        </div>

    </div>
</div>
@endsection
