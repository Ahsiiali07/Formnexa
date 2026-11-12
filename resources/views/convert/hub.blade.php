@extends('layouts.app')

@section('content')
@php
    $toCount = count($groups['to_pdf']);
    $fromCount = count($groups['from_pdf']);
    $totalCount = $toCount + $fromCount;
@endphp
<x-schema.tool name="Free Online PDF Converter Hub" description="Convert JPG, Word, Excel, PowerPoint and HTML to PDF, or turn PDFs into Word, Excel and JPG online." category="UtilitiesApplication" />

<div x-data="{ tab: 'all' }" class="min-h-screen bg-[#f8f8f3] pb-16 sm:pb-20">
    {{-- Modern Electric Precision Hero Header --}}
    <section class="bg-white border-b border-border-subtle pt-7 sm:pt-10 pb-10 sm:pb-12 px-4 sm:px-6">
        <div class="max-w-[1280px] mx-auto text-center">
            <div class="flex justify-center mb-4">
                <x-schema.breadcrumbs :items="[['Home', url('/')], ['PDF Converters', route('convert.hub')]]" />
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-electric-blue-subtle border border-[#c8f3dc] text-[#184f3a] font-mono text-[11px] font-semibold uppercase tracking-wider mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[#184f3a]"></span>
                {{ $totalCount }} Free PDF Conversion Tools &bull; No Sign-up Required
            </div>
            <h1 class="font-display text-3xl sm:text-5xl font-extrabold tracking-[-0.03em] text-[#17211b] leading-[1.08]">
                Every PDF conversion tool in one fast workspace.
            </h1>
            <p class="mt-4 text-sm sm:text-base text-[#536058] max-w-2xl mx-auto leading-relaxed">
                Convert images, Word, PowerPoint, Excel, and HTML cleanly into PDF &mdash; or transform PDFs back into high-DPI JPGs, editable Word, slides, Excel tables, and archival PDF/A.
            </p>

            {{-- Responsive Filter Tabs --}}
            <div class="mt-7 inline-flex flex-wrap justify-center p-1 bg-[#f8f8f3] rounded-xl border border-border-subtle gap-1 shadow-sm max-w-full">
                <button type="button" @click="tab = 'all'"
                        class="px-3.5 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition cursor-pointer"
                        :class="tab === 'all' ? 'bg-[#17211b] text-white shadow-sm' : 'text-[#536058] hover:text-[#17211b]'">
                    All Converters ({{ $totalCount }})
                </button>
                <button type="button" @click="tab = 'to_pdf'"
                        class="px-3.5 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition cursor-pointer"
                        :class="tab === 'to_pdf' ? 'bg-[#17211b] text-white shadow-sm' : 'text-[#536058] hover:text-[#17211b]'">
                    Convert to PDF ({{ $toCount }})
                </button>
                <button type="button" @click="tab = 'from_pdf'"
                        class="px-3.5 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition cursor-pointer"
                        :class="tab === 'from_pdf' ? 'bg-[#17211b] text-white shadow-sm' : 'text-[#536058] hover:text-[#17211b]'">
                    Convert from PDF ({{ $fromCount }})
                </button>
            </div>
        </div>
    </section>

    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 pt-8 sm:pt-10 space-y-10 sm:space-y-12">
        {{-- Section 1: CONVERT TO PDF --}}
        <section x-show="tab === 'all' || tab === 'to_pdf'">
            <div class="mb-5">
                <h2 class="font-mono text-xs font-semibold uppercase tracking-wider text-[#184f3a]">Convert To PDF</h2>
                <p class="font-display text-lg sm:text-xl font-bold text-[#17211b] mt-0.5 tracking-tight">Turn documents, spreadsheets, slides &amp; images into PDF</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                @foreach($groups['to_pdf'] as $tool)
                    <a href="{{ route('convert.tool', $tool['slug']) }}"
                       class="group bg-white rounded-xl border border-border-subtle p-5 shadow-sm hover:shadow-md hover:border-[#184f3a] transition-all duration-200 flex flex-col justify-between gap-4">
                        <div class="space-y-3.5">
                            <div class="flex items-center justify-between">
                                <span class="w-11 h-11 rounded-lg {{ $tool['color_bg'] }} {{ $tool['color_text'] }} border {{ $tool['color_border'] }} flex items-center justify-center font-mono font-bold text-sm">
                                    {{ $tool['badge'] }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-[#f8f8f3] border border-border-subtle text-[11px] font-bold text-[#17211b] group-hover:bg-[#184f3a] group-hover:text-white group-hover:border-[#184f3a] transition">
                                    Open Tool &rarr;
                                </span>
                            </div>
                            <div>
                                <h3 class="font-display text-base font-bold text-[#17211b] group-hover:text-[#184f3a] transition">{{ $tool['name'] }}</h3>
                                <p class="text-xs text-[#536058] mt-1.5 leading-relaxed">{{ $tool['tagline'] }}</p>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-border-subtle text-[11px] font-medium text-[#68736d]">
                            Accepts: <span class="text-[#17211b] font-semibold">{{ $tool['accept_label'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- Section 2: CONVERT FROM PDF --}}
        <section x-show="tab === 'all' || tab === 'from_pdf'">
            <div class="mb-5">
                <h2 class="font-mono text-xs font-semibold uppercase tracking-wider text-emerald-600">Convert From PDF</h2>
                <p class="font-display text-lg sm:text-xl font-bold text-[#17211b] mt-0.5 tracking-tight">Export PDFs back to JPG, Word, PowerPoint, Excel &amp; PDF/A</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                @foreach($groups['from_pdf'] as $tool)
                    <a href="{{ route('convert.tool', $tool['slug']) }}"
                       class="group bg-white rounded-xl border border-border-subtle p-5 shadow-sm hover:shadow-md hover:border-[#184f3a] transition-all duration-200 flex flex-col justify-between gap-4">
                        <div class="space-y-3.5">
                            <div class="flex items-center justify-between">
                                <span class="w-11 h-11 rounded-lg {{ $tool['color_bg'] }} {{ $tool['color_text'] }} border {{ $tool['color_border'] }} flex items-center justify-center font-mono font-bold text-sm">
                                    {{ $tool['badge'] }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-[#f8f8f3] border border-border-subtle text-[11px] font-bold text-[#17211b] group-hover:bg-[#184f3a] group-hover:text-white group-hover:border-[#184f3a] transition">
                                    Open Tool &rarr;
                                </span>
                            </div>
                            <div>
                                <h3 class="font-display text-base font-bold text-[#17211b] group-hover:text-[#184f3a] transition">{{ $tool['name'] }}</h3>
                                <p class="text-xs text-[#536058] mt-1.5 leading-relaxed">{{ $tool['tagline'] }}</p>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-border-subtle text-[11px] font-medium text-[#68736d]">
                            Output: <span class="font-mono text-[#17211b] font-semibold uppercase">.{{ $tool['output_ext'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection
