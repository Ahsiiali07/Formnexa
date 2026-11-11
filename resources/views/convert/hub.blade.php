@extends('layouts.app')

@section('title', 'Free PDF Converter Tools — JPG, Word, PowerPoint, Excel, HTML & PDF/A | Formnexa')
@section('meta_description', 'Convert to and from PDF effortlessly: JPG to PDF, Word to PDF, PowerPoint to PDF, Excel to PDF, HTML to PDF, PDF to JPG, PDF to Word, PDF to PowerPoint, PDF to Excel & PDF/A.')

@section('content')
<div x-data="{ tab: 'all' }" class="min-h-screen bg-white pb-20">
    {{-- Bloom-Style Hero Header --}}
    <section class="bg-[#F6F5F1] border-b border-black/[0.06] pt-11 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-white border border-black/[0.08] text-[#0066FF] text-xs font-extrabold uppercase tracking-wider mb-4">
                10 Free PDF Conversion Tools &bull; No Sign-up Required
            </div>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-[-0.035em] text-[#111111] leading-[1.08]">
                Every PDF conversion tool in one fast workspace.
            </h1>
            <p class="mt-4 text-sm sm:text-base text-black/65 font-sans-inter max-w-2xl mx-auto leading-relaxed">
                Convert images, Word, PowerPoint, Excel, and HTML cleanly into PDF &mdash; or transform PDFs back into high-DPI JPGs, editable Word, slides, Excel tables, and archival PDF/A.
            </p>

            {{-- Filter Tabs --}}
            <div class="mt-7 inline-flex p-1 bg-white rounded-xl border border-black/[0.08] gap-1 shadow-2xs">
                <button type="button" @click="tab = 'all'"
                        class="px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition cursor-pointer"
                        :class="tab === 'all' ? 'bg-[#111111] text-white' : 'text-black/60 hover:text-[#111111]'">
                    All Converters (10)
                </button>
                <button type="button" @click="tab = 'to_pdf'"
                        class="px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition cursor-pointer"
                        :class="tab === 'to_pdf' ? 'bg-[#111111] text-white' : 'text-black/60 hover:text-[#111111]'">
                    Convert to PDF (5)
                </button>
                <button type="button" @click="tab = 'from_pdf'"
                        class="px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition cursor-pointer"
                        :class="tab === 'from_pdf' ? 'bg-[#111111] text-white' : 'text-black/60 hover:text-[#111111]'">
                    Convert from PDF (5)
                </button>
            </div>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 space-y-12">
        {{-- Section 1: CONVERT TO PDF --}}
        <section x-show="tab === 'all' || tab === 'to_pdf'">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-xs font-extrabold uppercase tracking-[0.18em] text-black/40">Convert To PDF</h2>
                    <p class="text-lg font-extrabold text-[#111111] mt-0.5 tracking-tight">Turn documents, spreadsheets, slides &amp; images into PDF</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($groups['to_pdf'] as $tool)
                    <a href="{{ route('convert.tool', $tool['slug']) }}"
                       class="group bg-[#F6F5F1]/65 hover:bg-white rounded-2xl border border-black/[0.08] p-5 shadow-2xs hover:shadow-xl hover:border-black/25 transition-all duration-200 flex flex-col justify-between gap-4">
                        <div class="space-y-3.5">
                            <div class="flex items-center justify-between">
                                <span class="w-11 h-11 rounded-xl {{ $tool['color_bg'] }} {{ $tool['color_text'] }} border {{ $tool['color_border'] }} flex items-center justify-center font-black text-sm shadow-2xs">
                                    {{ $tool['badge'] }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-white border border-black/[0.08] text-[10px] font-bold text-[#111111]/75 group-hover:bg-[#111111] group-hover:text-white transition">
                                    Open Tool &rarr;
                                </span>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-[#111111] group-hover:text-[#0066FF] transition">{{ $tool['name'] }}</h3>
                                <p class="text-xs text-black/60 mt-1.5 leading-relaxed font-sans-inter">{{ $tool['tagline'] }}</p>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-black/[0.06] text-[11px] font-semibold text-black/45">
                            Accepts: <span class="text-black/70">{{ $tool['accept_label'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- Section 2: CONVERT FROM PDF --}}
        <section x-show="tab === 'all' || tab === 'from_pdf'">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-xs font-extrabold uppercase tracking-[0.18em] text-black/40">Convert From PDF</h2>
                    <p class="text-lg font-extrabold text-[#111111] mt-0.5 tracking-tight">Export PDFs back to JPG, Word, PowerPoint, Excel &amp; PDF/A</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($groups['from_pdf'] as $tool)
                    <a href="{{ route('convert.tool', $tool['slug']) }}"
                       class="group bg-[#F6F5F1]/65 hover:bg-white rounded-2xl border border-black/[0.08] p-5 shadow-2xs hover:shadow-xl hover:border-black/25 transition-all duration-200 flex flex-col justify-between gap-4">
                        <div class="space-y-3.5">
                            <div class="flex items-center justify-between">
                                <span class="w-11 h-11 rounded-xl {{ $tool['color_bg'] }} {{ $tool['color_text'] }} border {{ $tool['color_border'] }} flex items-center justify-center font-black text-sm shadow-2xs">
                                    {{ $tool['badge'] }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-white border border-black/[0.08] text-[10px] font-bold text-[#111111]/75 group-hover:bg-[#111111] group-hover:text-white transition">
                                    Open Tool &rarr;
                                </span>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-[#111111] group-hover:text-[#0066FF] transition">{{ $tool['name'] }}</h3>
                                <p class="text-xs text-black/60 mt-1.5 leading-relaxed font-sans-inter">{{ $tool['tagline'] }}</p>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-black/[0.06] text-[11px] font-semibold text-black/45">
                            Output: <span class="text-black/70 uppercase">.{{ $tool['output_ext'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection
