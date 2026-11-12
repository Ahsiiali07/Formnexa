@extends('layouts.app')

@section('title', 'Free PDF Converter Tools — JPG, Word, PowerPoint, Excel, HTML & PDF/A | Formnexa')
@section('meta_description', 'Convert to and from PDF effortlessly: JPG to PDF, Word to PDF, PowerPoint to PDF, Excel to PDF, HTML to PDF, PDF to JPG, PDF to Word, PDF to PowerPoint, PDF to Excel & PDF/A.')

@section('content')
@php
    $toCount = count($groups['to_pdf']);
    $fromCount = count($groups['from_pdf']);
    $totalCount = $toCount + $fromCount;
@endphp
<div x-data="{ tab: 'all' }" class="min-h-screen bg-[#F8FAFC] pb-16 sm:pb-20">
    {{-- Modern Electric Precision Hero Header --}}
    <section class="bg-white border-b border-border-subtle pt-10 pb-10 sm:pb-12 px-4 sm:px-6">
        <div class="max-w-[1280px] mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-electric-blue-subtle border border-blue-200/70 text-[#0050cb] font-mono text-[11px] font-semibold uppercase tracking-wider mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[#0066FF]"></span>
                {{ $totalCount }} Free PDF Conversion Tools &bull; No Sign-up Required
            </div>
            <h1 class="font-display text-3xl sm:text-5xl font-extrabold tracking-[-0.03em] text-[#0b1c30] leading-[1.08]">
                Every PDF conversion tool in one fast workspace.
            </h1>
            <p class="mt-4 text-sm sm:text-base text-[#565e74] max-w-2xl mx-auto leading-relaxed">
                Convert images, Word, PowerPoint, Excel, and HTML cleanly into PDF &mdash; or transform PDFs back into high-DPI JPGs, editable Word, slides, Excel tables, and archival PDF/A.
            </p>

            {{-- Responsive Filter Tabs --}}
            <div class="mt-7 inline-flex flex-wrap justify-center p-1 bg-[#F8FAFC] rounded-xl border border-border-subtle gap-1 shadow-sm max-w-full">
                <button type="button" @click="tab = 'all'"
                        class="px-3.5 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition cursor-pointer"
                        :class="tab === 'all' ? 'bg-[#0B0F19] text-white shadow-sm' : 'text-[#565e74] hover:text-[#0b1c30]'">
                    All Converters ({{ $totalCount }})
                </button>
                <button type="button" @click="tab = 'to_pdf'"
                        class="px-3.5 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition cursor-pointer"
                        :class="tab === 'to_pdf' ? 'bg-[#0B0F19] text-white shadow-sm' : 'text-[#565e74] hover:text-[#0b1c30]'">
                    Convert to PDF ({{ $toCount }})
                </button>
                <button type="button" @click="tab = 'from_pdf'"
                        class="px-3.5 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition cursor-pointer"
                        :class="tab === 'from_pdf' ? 'bg-[#0B0F19] text-white shadow-sm' : 'text-[#565e74] hover:text-[#0b1c30]'">
                    Convert from PDF ({{ $fromCount }})
                </button>
            </div>
        </div>
    </section>

    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 pt-8 sm:pt-10 space-y-10 sm:space-y-12">
        {{-- Section 1: CONVERT TO PDF --}}
        <section x-show="tab === 'all' || tab === 'to_pdf'">
            <div class="mb-5">
                <h2 class="font-mono text-xs font-semibold uppercase tracking-wider text-[#0050cb]">Convert To PDF</h2>
                <p class="font-display text-lg sm:text-xl font-bold text-[#0b1c30] mt-0.5 tracking-tight">Turn documents, spreadsheets, slides &amp; images into PDF</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                @foreach($groups['to_pdf'] as $tool)
                    <a href="{{ route('convert.tool', $tool['slug']) }}"
                       class="group bg-white rounded-xl border border-border-subtle p-5 shadow-sm hover:shadow-md hover:border-[#0066FF] transition-all duration-200 flex flex-col justify-between gap-4">
                        <div class="space-y-3.5">
                            <div class="flex items-center justify-between">
                                <span class="w-11 h-11 rounded-lg {{ $tool['color_bg'] }} {{ $tool['color_text'] }} border {{ $tool['color_border'] }} flex items-center justify-center font-mono font-bold text-sm">
                                    {{ $tool['badge'] }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-[#F8FAFC] border border-border-subtle text-[11px] font-bold text-[#0b1c30] group-hover:bg-[#0066FF] group-hover:text-white group-hover:border-[#0066FF] transition">
                                    Open Tool &rarr;
                                </span>
                            </div>
                            <div>
                                <h3 class="font-display text-base font-bold text-[#0b1c30] group-hover:text-[#0050cb] transition">{{ $tool['name'] }}</h3>
                                <p class="text-xs text-[#565e74] mt-1.5 leading-relaxed">{{ $tool['tagline'] }}</p>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-border-subtle text-[11px] font-medium text-[#727687]">
                            Accepts: <span class="text-[#0b1c30] font-semibold">{{ $tool['accept_label'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- Section 2: CONVERT FROM PDF --}}
        <section x-show="tab === 'all' || tab === 'from_pdf'">
            <div class="mb-5">
                <h2 class="font-mono text-xs font-semibold uppercase tracking-wider text-emerald-600">Convert From PDF</h2>
                <p class="font-display text-lg sm:text-xl font-bold text-[#0b1c30] mt-0.5 tracking-tight">Export PDFs back to JPG, Word, PowerPoint, Excel &amp; PDF/A</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                @foreach($groups['from_pdf'] as $tool)
                    <a href="{{ route('convert.tool', $tool['slug']) }}"
                       class="group bg-white rounded-xl border border-border-subtle p-5 shadow-sm hover:shadow-md hover:border-[#0066FF] transition-all duration-200 flex flex-col justify-between gap-4">
                        <div class="space-y-3.5">
                            <div class="flex items-center justify-between">
                                <span class="w-11 h-11 rounded-lg {{ $tool['color_bg'] }} {{ $tool['color_text'] }} border {{ $tool['color_border'] }} flex items-center justify-center font-mono font-bold text-sm">
                                    {{ $tool['badge'] }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-[#F8FAFC] border border-border-subtle text-[11px] font-bold text-[#0b1c30] group-hover:bg-[#0066FF] group-hover:text-white group-hover:border-[#0066FF] transition">
                                    Open Tool &rarr;
                                </span>
                            </div>
                            <div>
                                <h3 class="font-display text-base font-bold text-[#0b1c30] group-hover:text-[#0050cb] transition">{{ $tool['name'] }}</h3>
                                <p class="text-xs text-[#565e74] mt-1.5 leading-relaxed">{{ $tool['tagline'] }}</p>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-border-subtle text-[11px] font-medium text-[#727687]">
                            Output: <span class="font-mono text-[#0b1c30] font-semibold uppercase">.{{ $tool['output_ext'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection
