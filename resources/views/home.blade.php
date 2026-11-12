@extends('layouts.app')

@section('title', 'Formnexa — Invoices, Resume Builder & PDF Converter Suite')
@section('meta_description', 'Create branded invoices & 12 business documents, build ATS-ready resumes with FlowCV & Figma templates, and convert JPG, Word, PowerPoint, Excel, HTML & PDFs cleanly — 100% free.')

@section('content')
@php
    $totalConverters = count($converterGroups['to_pdf']) + count($converterGroups['from_pdf']);
@endphp

<div x-data="{ faqOpen: 1 }" class="bg-[#F8FAFC] text-[#0b1c30]">

    {{-- =======================================================================
         1. HERO SECTION (Stitch "Modern Electric Precision")
         ======================================================================= --}}
    <section class="relative pt-10 sm:pt-14 md:pt-20 pb-14 sm:pb-16 px-4 sm:px-6 overflow-hidden">
        <div class="max-w-[1280px] mx-auto text-center relative z-10">

            {{-- Status Pill Badge --}}
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-border-subtle shadow-sm mb-5 sm:mb-6">
                <span class="w-2 h-2 rounded-full bg-badge-emerald animate-pulse shrink-0"></span>
                <span class="font-mono text-[11px] sm:text-xs font-medium text-[#565e74]">100% Free &bull; Zero Watermarks &bull; No Sign-up Required</span>
            </div>

            {{-- Display Headline with Playful Micro-Badges --}}
            <div class="relative max-w-4xl mx-auto my-2">
                <span class="inline-block sm:mb-2 bg-badge-emerald text-white font-semibold text-[11px] tracking-[0.04em] px-3 py-1 rounded-full uppercase shadow-md shadow-emerald-500/20 -rotate-2">
                    GET PAID
                </span>

                <h1 class="font-display text-[36px] sm:text-[50px] lg:text-[60px] font-extrabold text-[#0b1c30] tracking-[-0.035em] leading-[1.08] mt-3 mb-4">
                    Create invoices. <br class="hidden sm:inline">Build resumes. Convert PDFs.
                </h1>

                <div class="flex flex-wrap items-center justify-center gap-3 mt-1 mb-6">
                    <span class="bg-primary-container text-white font-semibold text-[11px] tracking-[0.04em] px-3.5 py-1 rounded-full uppercase shadow-md shadow-blue-600/20 -rotate-3">
                        GET HIRED
                    </span>
                    <span class="bg-badge-rose text-white font-semibold text-[11px] tracking-[0.04em] px-3.5 py-1 rounded-full uppercase shadow-md shadow-rose-500/20 rotate-3">
                        CONVERT PDF
                    </span>
                </div>
            </div>

            {{-- Hero Subtitle --}}
            <p class="max-w-2xl mx-auto text-[15px] sm:text-lg text-[#565e74] leading-relaxed mb-8">
                Three complete document workflows in one fast modern workspace. Draft clean invoices, import or style designer resumes, and run {{ $totalConverters }} instant file conversions &mdash; free with no account required.
            </p>

            {{-- Primary Action CTA Cluster (Stacked full-width on mobile, row on sm+) --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 sm:gap-3.5 mb-12 sm:mb-14 max-w-md sm:max-w-none mx-auto">
                <a href="{{ route('invoice.studio') }}"
                   class="w-full sm:w-auto px-6 py-3.5 rounded-lg bg-primary-container hover:bg-electric-blue-hover text-white font-semibold text-sm shadow-lg shadow-[#0066FF]/25 flex items-center justify-center gap-2 transition duration-150 active:scale-95 whitespace-nowrap">
                    <span>Open Invoice Generator</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
                <a href="{{ route('resume.builder') }}"
                   class="w-full sm:w-auto px-6 py-3.5 rounded-lg bg-white text-[#0b1c30] border border-border-strong hover:bg-[#F8FAFC] hover:border-[#0b1c30] font-semibold text-sm flex items-center justify-center transition duration-150 whitespace-nowrap">
                    Build Resume
                </a>
                <a href="{{ route('convert.hub') }}"
                   class="w-full sm:w-auto px-6 py-3.5 rounded-lg bg-white text-[#0b1c30] border border-border-strong hover:bg-[#F8FAFC] hover:border-[#0b1c30] font-semibold text-sm flex items-center justify-center transition duration-150 whitespace-nowrap">
                    {{ $totalConverters }} PDF Converters
                </a>
            </div>

            {{-- Quick Stats Counter Strip --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 max-w-4xl mx-auto rounded-xl bg-white border border-border-subtle shadow-sm overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-r lg:border-b-0 border-border-subtle">
                    <div class="font-display text-2xl font-bold text-[#0b1c30]">12</div>
                    <div class="font-mono text-[11px] sm:text-xs text-[#565e74] mt-0.5">Billing &amp; Order Generators</div>
                </div>
                <div class="p-4 sm:p-5 border-b lg:border-b-0 lg:border-r border-border-subtle">
                    <div class="font-display text-2xl font-bold text-[#0b1c30]">15+</div>
                    <div class="font-mono text-[11px] sm:text-xs text-[#565e74] mt-0.5">Designer Resume Templates</div>
                </div>
                <div class="p-4 sm:p-5 border-r border-border-subtle">
                    <div class="font-display text-2xl font-bold text-[#0b1c30]">{{ $totalConverters }}</div>
                    <div class="font-mono text-[11px] sm:text-xs text-[#565e74] mt-0.5">Fast PDF Conversion Tools</div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="font-display text-2xl font-bold text-[#0050cb]">100% Free</div>
                    <div class="font-mono text-[11px] sm:text-xs text-[#565e74] mt-0.5">Zero Watermarks &bull; No Sign-up</div>
                </div>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         2. MODULE 01: INTERACTIVE OS-GRADE APP PREVIEW WINDOW (#0B0F19 Chrome)
         ======================================================================= --}}
    <section class="max-w-[1280px] mx-auto px-4 sm:px-6 mb-16 sm:mb-24" id="invoices">
        <div class="rounded-xl overflow-hidden border border-border-strong bg-white shadow-xl">
            {{-- Dark Window Chrome Titlebar --}}
            <div class="bg-card-dark-header px-4 py-3 flex items-center justify-between text-white gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex items-center gap-1.5 shrink-0">
                        <span class="w-3 h-3 rounded-full bg-badge-rose inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-badge-amber inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-badge-emerald inline-block"></span>
                    </div>
                    <div class="font-mono text-slate-400 text-xs px-2.5 py-1 rounded bg-slate-800/70 hidden sm:block truncate">
                        formnexa.app/invoice-generator
                    </div>
                </div>
                <a href="{{ route('invoice.studio') }}"
                   class="px-3 py-1.5 bg-slate-800 hover:bg-[#0066FF] text-xs font-semibold rounded-md text-white flex items-center gap-1.5 transition shrink-0 whitespace-nowrap">
                    <span>Launch Live Tool</span>
                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                </a>
            </div>

            {{-- App Canvas Split Layout --}}
            <div class="p-5 sm:p-8 lg:p-10 grid grid-cols-1 lg:grid-cols-12 gap-8 bg-[#f8f9ff]">
                {{-- Left Pitch & Features --}}
                <div class="lg:col-span-6 flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-electric-blue-subtle text-[#0050cb] border border-border-subtle font-mono text-[11px] font-semibold mb-4">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#0066FF]"></span>
                            MODULE 01 &bull; INVOICING &amp; BILLING
                        </div>
                        <h2 class="font-display text-2xl sm:text-4xl font-bold text-[#0b1c30] tracking-[-0.025em] leading-tight mb-4">
                            Generate custom invoices, quotes &amp; receipts in seconds.
                        </h2>
                        <p class="text-[15px] text-[#565e74] leading-relaxed mb-6">
                            Switch between 12 billing formats, upload custom cover headers and logos, embed Stripe or PayPal links, and export clean zero-watermark PDFs.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-7">
                            <div class="flex items-center gap-2.5 p-3 rounded-lg bg-white border border-border-subtle">
                                <span class="material-symbols-outlined text-[#0066FF] text-[20px]">layers</span>
                                <span class="text-xs font-semibold text-[#0b1c30]">6 Layout Engines</span>
                            </div>
                            <div class="flex items-center gap-2.5 p-3 rounded-lg bg-white border border-border-subtle">
                                <span class="material-symbols-outlined text-[#0066FF] text-[20px]">currency_exchange</span>
                                <span class="text-xs font-semibold text-[#0b1c30]">24 Global Currencies</span>
                            </div>
                            <div class="flex items-center gap-2.5 p-3 rounded-lg bg-white border border-border-subtle">
                                <span class="material-symbols-outlined text-[#0066FF] text-[20px]">draw</span>
                                <span class="text-xs font-semibold text-[#0b1c30]">Digital Signature Pad</span>
                            </div>
                            <div class="flex items-center gap-2.5 p-3 rounded-lg bg-white border border-border-subtle">
                                <span class="material-symbols-outlined text-[#0066FF] text-[20px]">send</span>
                                <span class="text-xs font-semibold text-[#0b1c30]">Direct Client Email</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 pt-4 border-t border-border-subtle">
                        <a href="{{ route('invoice.studio') }}"
                           class="px-5 py-2.5 rounded-lg bg-primary-container hover:bg-electric-blue-hover text-white font-semibold text-xs sm:text-sm transition flex items-center gap-1.5 shadow-sm">
                            <span>Open Invoice Studio</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                        <a href="{{ route('templates.index') }}" class="text-xs sm:text-sm font-semibold text-[#565e74] hover:text-[#0050cb] transition">
                            20+ Templates &rarr;
                        </a>
                    </div>
                </div>

                {{-- Right Live Invoice Sheet Card --}}
                <div class="lg:col-span-6">
                    <div class="bg-white rounded-xl border border-border-strong p-5 sm:p-6 shadow-md relative overflow-hidden">
                        <div class="flex flex-wrap justify-between items-start gap-2 border-b border-border-subtle pb-4 mb-5">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-6 h-6 rounded bg-[#0b1c30] text-white flex items-center justify-center font-bold text-xs shrink-0">N</div>
                                    <span class="font-display font-bold text-sm sm:text-base text-[#0b1c30] truncate">NORTHSTAR DESIGN STUDIO</span>
                                </div>
                                <div class="font-mono text-[#727687] text-xs">INV-2026-04</div>
                            </div>
                            <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-semibold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 shrink-0">
                                Ready to Download
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                            <div>
                                <div class="font-mono text-[#727687] uppercase text-[10px] mb-1">Billed To</div>
                                <div class="text-xs font-bold text-[#0b1c30]">Acme Commerce Inc.</div>
                                <div class="text-xs text-[#565e74] truncate">billing@acmecommerce.io</div>
                            </div>
                            <div class="sm:text-right">
                                <div class="font-mono text-[#727687] uppercase text-[10px] mb-1">Balance Due</div>
                                <div class="font-display text-2xl font-extrabold text-[#0050cb]">$4,650.00</div>
                                <div class="font-mono text-[#727687] text-[11px]">Due on receipt</div>
                            </div>
                        </div>

                        <div class="border border-border-subtle rounded-lg overflow-x-auto mb-5">
                            <table class="w-full text-left text-xs sm:text-sm">
                                <thead class="bg-[#f8f9ff] font-mono text-[#727687] text-[10px] uppercase border-b border-border-subtle">
                                    <tr>
                                        <th class="p-2.5">Description</th>
                                        <th class="p-2.5 text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border-subtle text-xs">
                                    <tr>
                                        <td class="p-2.5 text-[#0b1c30] font-medium">Brand Identity &amp; Design System</td>
                                        <td class="p-2.5 text-right font-mono text-[#0b1c30] font-semibold whitespace-nowrap">$3,200.00</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 text-[#0b1c30] font-medium">Responsive Marketing Website</td>
                                        <td class="p-2.5 text-right font-mono text-[#0b1c30] font-semibold whitespace-nowrap">$1,450.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-3 border-t border-border-subtle">
                            <div class="flex flex-wrap items-center gap-1.5 font-mono text-[11px] text-[#565e74]">
                                <span class="px-2 py-0.5 bg-slate-100 rounded">Stripe</span>
                                <span class="px-2 py-0.5 bg-slate-100 rounded">PayPal</span>
                                <span class="px-2 py-0.5 bg-slate-100 rounded">Bank Transfer</span>
                            </div>
                            <a href="{{ route('invoice.studio') }}"
                               class="w-full sm:w-auto px-4 py-2 rounded-lg bg-[#0050cb] hover:bg-electric-blue-hover text-white font-semibold text-xs flex items-center justify-center gap-1.5 transition">
                                <span class="material-symbols-outlined text-[15px]">download</span>
                                <span>Download Free PDF</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         3. MODULE 02: RESUME STUDIO & SMART CV IMPORTER
         ======================================================================= --}}
    <section class="max-w-[1280px] mx-auto px-4 sm:px-6 mb-16 sm:mb-24" id="resumes">
        <div class="bg-white rounded-2xl border border-border-subtle p-5 sm:p-8 lg:p-12 shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                {{-- Left: Pitch & 3-Step Parser --}}
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-electric-blue-subtle text-[#0050cb] border border-border-subtle font-mono text-[11px] font-semibold mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#0066FF]"></span>
                        MODULE 02 &bull; CAREER &amp; RESUMES
                    </div>
                    <h2 class="font-display text-2xl sm:text-4xl font-bold text-[#0b1c30] tracking-[-0.025em] leading-tight mb-4">
                        FlowCV &amp; Figma-grade resume studio with live A4 preview.
                    </h2>
                    <p class="text-[15px] text-[#565e74] leading-relaxed mb-7">
                        Build standout CVs side-by-side with live formatting. Customize section ordering, accent colors, fonts, profile photos, and download crisp ATS-friendly PDFs.
                    </p>

                    {{-- 3-Step Smart CV Parser Box --}}
                    <div class="p-4 sm:p-6 rounded-xl bg-[#f8f9ff] border border-border-subtle mb-7">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                            <span class="text-xs font-bold text-[#0b1c30] flex items-center gap-2">
                                <span class="material-symbols-outlined text-[#0066FF] text-[20px]">smart_toy</span>
                                Smart CV Parser &amp; Importer
                            </span>
                            <span class="font-mono text-[#727687] text-[11px]">PDF / DOCX Supported</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="p-3.5 bg-white rounded-lg border border-border-subtle">
                                <div class="font-mono text-[#0066FF] text-[11px] font-bold mb-1">STEP 01</div>
                                <div class="text-xs font-bold text-[#0b1c30] mb-1">Upload or Paste</div>
                                <p class="text-xs text-[#565e74]">Drop .pdf or .docx or paste LinkedIn summary.</p>
                            </div>
                            <div class="p-3.5 bg-white rounded-lg border border-border-subtle">
                                <div class="font-mono text-[#0066FF] text-[11px] font-bold mb-1">STEP 02</div>
                                <div class="text-xs font-bold text-[#0b1c30] mb-1">Auto-Structure</div>
                                <p class="text-xs text-[#565e74]">Extracts roles, dates, bullets &amp; skills cleanly.</p>
                            </div>
                            <div class="p-3.5 bg-white rounded-lg border border-border-subtle">
                                <div class="font-mono text-[#0066FF] text-[11px] font-bold mb-1">STEP 03</div>
                                <div class="text-xs font-bold text-[#0b1c30] mb-1">Pick Any Theme</div>
                                <p class="text-xs text-[#565e74]">Switch FlowCV sidebar or Figma serif in 1 click.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                        <a href="{{ route('resume.builder') }}"
                           class="px-5 py-2.5 rounded-lg bg-primary-container hover:bg-electric-blue-hover text-white font-semibold text-xs sm:text-sm transition shadow-sm flex items-center gap-2">
                            <span>Open Resume Builder</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                        <a href="{{ route('resume.builder', ['import' => 1]) }}"
                           class="px-4 py-2.5 rounded-lg bg-[#F8FAFC] border border-border-strong hover:border-[#0b1c30] text-[#0b1c30] font-semibold text-xs sm:text-sm transition">
                            Import Existing CV
                        </a>
                        <a href="{{ route('resume.templates') }}" class="text-xs sm:text-sm font-semibold text-[#565e74] hover:text-[#0050cb] transition">
                            Browse 15+ CV Themes &rarr;
                        </a>
                    </div>
                </div>

                {{-- Right: Miniature Resume Card --}}
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-xl border border-border-strong p-5 shadow-lg max-w-sm mx-auto">
                        <div class="flex items-center gap-3 border-b border-border-subtle pb-4 mb-4">
                            <div class="w-12 h-12 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-sm tracking-wider shrink-0">
                                BW
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-[#0b1c30] truncate">Brian Wayne</div>
                                <div class="text-xs font-semibold text-[#0050cb]">Product Strategist</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-3 text-xs">
                            <div class="col-span-4 border-r border-border-subtle pr-2">
                                <div class="font-mono uppercase text-[10px] text-[#727687] font-bold mb-2">Skills</div>
                                <div class="flex flex-col gap-1 text-[#424656] font-mono text-[10.5px]">
                                    <span>&bull; Strategy</span>
                                    <span>&bull; Analytics</span>
                                    <span>&bull; Figma UI</span>
                                </div>
                            </div>
                            <div class="col-span-8 pl-1">
                                <div class="font-mono uppercase text-[10px] text-[#727687] font-bold mb-2">Work Experience</div>
                                <div class="mb-2.5">
                                    <div class="text-[11px] text-[#0b1c30] font-semibold">Sr. Product Lead &bull; Horizon</div>
                                    <div class="text-[10px] text-[#727687] font-mono">2023 &ndash; Present</div>
                                    <p class="text-[11px] text-[#565e74] mt-0.5 leading-snug">Led cross-functional redesign driving +38% activation rate.</p>
                                </div>
                                <div>
                                    <div class="text-[11px] text-[#0b1c30] font-semibold">Business Analyst &bull; Apex</div>
                                    <div class="text-[10px] text-[#727687] font-mono">2020 &ndash; 2023</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-border-subtle flex justify-between items-center text-xs">
                            <span class="font-mono text-[#727687] text-[11px]">Theme: Atlantic Flow</span>
                            <span class="font-semibold text-emerald-600 text-[11px]">ATS Compliant &#10003;</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         4. MODULE 03: PDF CONVERTER SUITE (Dual Bento Grid)
         ======================================================================= --}}
    <section class="max-w-[1280px] mx-auto px-4 sm:px-6 mb-16 sm:mb-24" id="converters">
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-12">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-electric-blue-subtle text-[#0050cb] border border-border-subtle font-mono text-[11px] font-semibold mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-[#0066FF]"></span>
                MODULE 03 &bull; PDF CONVERTER SUITE
            </div>
            <h2 class="font-display text-2xl sm:text-4xl font-bold text-[#0b1c30] tracking-[-0.025em] mb-3">
                {{ $totalConverters }} essential file &amp; PDF conversions built in.
            </h2>
            <p class="text-[15px] text-[#565e74]">
                No watermarks, no page limits locked behind paywalls. Convert images, Office documents, or HTML into PDF &mdash; and extract editable files cleanly.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
            {{-- Convert TO PDF --}}
            <div class="bg-white rounded-2xl border border-border-subtle p-5 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-border-subtle">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary-container"></span>
                        <h3 class="font-display text-base sm:text-lg font-bold text-[#0b1c30]">Convert To PDF</h3>
                    </div>
                    <span class="font-mono text-xs text-[#727687]">{{ count($converterGroups['to_pdf']) }} Quick Tools</span>
                </div>
                <div class="flex flex-col gap-3">
                    @foreach($converterGroups['to_pdf'] as $cTool)
                        <a href="{{ route('convert.tool', $cTool['slug']) }}"
                           class="p-3.5 rounded-xl border border-border-subtle hover:border-[#0066FF] transition flex items-center justify-between gap-3 group bg-[#f8f9ff]/60 hover:bg-white">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-9 h-9 rounded-lg {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-mono text-xs font-bold shrink-0">
                                    {{ $cTool['badge'] }}
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs sm:text-sm font-bold text-[#0b1c30] group-hover:text-[#0050cb] transition">{{ $cTool['name'] }}</div>
                                    <div class="text-xs text-[#565e74] truncate">{{ $cTool['accept_label'] }}</div>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-[#727687] group-hover:text-[#0066FF] group-hover:translate-x-1 transition-all text-[20px] shrink-0">arrow_forward</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Convert FROM PDF --}}
            <div class="bg-white rounded-2xl border border-border-subtle p-5 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-border-subtle">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-badge-emerald"></span>
                        <h3 class="font-display text-base sm:text-lg font-bold text-[#0b1c30]">Convert From PDF</h3>
                    </div>
                    <span class="font-mono text-xs text-[#727687]">{{ count($converterGroups['from_pdf']) }} Quick Tools</span>
                </div>
                <div class="flex flex-col gap-3">
                    @foreach($converterGroups['from_pdf'] as $cTool)
                        <a href="{{ route('convert.tool', $cTool['slug']) }}"
                           class="p-3.5 rounded-xl border border-border-subtle hover:border-[#0066FF] transition flex items-center justify-between gap-3 group bg-[#f8f9ff]/60 hover:bg-white">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-9 h-9 rounded-lg {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-mono text-xs font-bold shrink-0">
                                    {{ $cTool['badge'] }}
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs sm:text-sm font-bold text-[#0b1c30] group-hover:text-[#0050cb] transition">{{ $cTool['name'] }}</div>
                                    <div class="text-xs text-[#565e74] truncate">{{ $cTool['tagline'] }}</div>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-[#727687] group-hover:text-[#0066FF] group-hover:translate-x-1 transition-all text-[20px] shrink-0">arrow_forward</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         5. CURATED DESIGN PRESETS GRID
         ======================================================================= --}}
    <section class="max-w-[1280px] mx-auto px-4 sm:px-6 mb-16 sm:mb-24" id="templates">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 sm:mb-10 gap-4">
            <div>
                <div class="font-mono text-[#0050cb] text-xs font-semibold uppercase mb-2">Curated Design Presets</div>
                <h2 class="font-display text-2xl sm:text-4xl font-bold text-[#0b1c30] tracking-[-0.025em]">
                    Engineered for consultants, creators &amp; agencies.
                </h2>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('templates.index') }}" class="px-3.5 py-1.5 rounded-full bg-[#dce9ff]/70 hover:bg-[#0066FF] hover:text-white text-[#0b1c30] text-xs font-semibold transition">
                    20+ Invoices
                </a>
                <a href="{{ route('resume.templates') }}" class="px-3.5 py-1.5 rounded-full bg-[#dce9ff]/70 hover:bg-[#0066FF] hover:text-white text-[#0b1c30] text-xs font-semibold transition">
                    15+ Resumes
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
            @foreach(array_slice($resumeTemplates, 0, 4) as $idx => $rTpl)
                <a href="{{ route('resume.builder', ['template' => $rTpl['slug']]) }}"
                   class="bg-white rounded-xl border border-border-subtle p-5 hover:border-[#0066FF] hover:shadow-md transition flex flex-col justify-between group">
                    <div>
                        <div class="h-32 rounded-lg bg-[#f8f9ff] border border-border-subtle mb-4 p-3 flex flex-col justify-between overflow-hidden">
                            @if($idx === 0)
                                <div class="flex gap-2">
                                    <div class="w-1/3 h-16 rounded" style="background-color: {{ $rTpl['accent_color'] ?? '#1B3142' }}; opacity: 0.25;"></div>
                                    <div class="w-2/3 flex flex-col gap-1.5">
                                        <div class="h-3 w-3/4 bg-slate-300 rounded"></div>
                                        <div class="h-2 w-full bg-slate-200 rounded"></div>
                                        <div class="h-2 w-5/6 bg-slate-200 rounded"></div>
                                    </div>
                                </div>
                            @elseif($idx === 1)
                                <div class="flex flex-col gap-2">
                                    <div class="h-4 w-1/2 bg-slate-800 rounded"></div>
                                    <div class="h-1.5 w-full bg-slate-300 rounded"></div>
                                    <div class="h-1.5 w-4/5 bg-slate-200 rounded"></div>
                                    <div class="h-1.5 w-full bg-slate-200 rounded"></div>
                                </div>
                            @elseif($idx === 2)
                                <div class="flex gap-2 items-center">
                                    <div class="w-1 h-16 bg-[#0066FF] rounded-full"></div>
                                    <div class="flex flex-col gap-2 w-full">
                                        <div class="h-2.5 w-2/3 bg-slate-300 rounded"></div>
                                        <div class="h-2 w-full bg-slate-200 rounded"></div>
                                        <div class="h-2.5 w-1/2 bg-slate-300 rounded"></div>
                                    </div>
                                </div>
                            @else
                                <div class="flex justify-between items-start">
                                    <div class="h-3 w-1/3 bg-slate-800 rounded"></div>
                                    <div class="h-6 w-6 rounded bg-slate-300"></div>
                                </div>
                                <div class="flex flex-col gap-1.5 mt-2">
                                    <div class="h-2 w-full bg-slate-200 rounded"></div>
                                    <div class="h-2 w-4/5 bg-slate-200 rounded"></div>
                                </div>
                            @endif
                            <div class="text-[10px] font-mono text-[#727687] truncate">{{ $rTpl['badge'] ?? $rTpl['category_label'] }}</div>
                        </div>
                        <div class="text-sm font-bold text-[#0b1c30] group-hover:text-[#0050cb] transition">{{ $rTpl['name'] }}</div>
                        <p class="text-xs text-[#565e74] mt-1 line-clamp-2">{{ $rTpl['description'] }}</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs font-semibold">
                        <span class="text-[#0050cb]">Resume</span>
                        <span class="text-[#565e74] group-hover:text-[#0050cb]">Use Theme &rarr;</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- =======================================================================
         6. SUPPORTED DOCUMENTS GRID (All 12 Generators)
         ======================================================================= --}}
    <section class="max-w-[1280px] mx-auto px-4 sm:px-6 mb-16 sm:mb-24">
        <div class="bg-white rounded-2xl border border-border-subtle p-5 sm:p-8 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="font-display text-xl sm:text-2xl font-bold text-[#0b1c30]">12 Document Generators</h3>
                    <p class="text-xs sm:text-sm text-[#565e74] mt-0.5">Every financial and billing tool you need for complete operations.</p>
                </div>
                <a href="{{ route('invoice.studio') }}" class="text-xs sm:text-sm font-semibold text-[#0050cb] flex items-center gap-1 hover:underline shrink-0">
                    <span>View All 12 Studios</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @foreach($generators as $slug => $gen)
                    <a href="{{ $slug === 'invoice-generator' ? route('invoice.studio') : route('invoice.generator', $slug) }}"
                       class="p-3 bg-[#f8f9ff] hover:bg-white rounded-lg border border-border-subtle hover:border-[#0066FF] transition text-center group">
                        <div class="text-xs font-bold text-[#0b1c30] group-hover:text-[#0050cb] truncate">
                            {{ str_replace(' Generator', '', $gen['name']) }}
                        </div>
                        <div class="text-[10.5px] text-[#727687] font-mono mt-0.5 truncate">{{ $gen['prefix'] }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =======================================================================
         7. FAQ ACCORDION
         ======================================================================= --}}
    <section class="max-w-[820px] mx-auto px-4 sm:px-6 mb-16 sm:mb-24">
        <div class="text-center mb-8 sm:mb-10">
            <div class="font-mono text-xs text-[#727687] uppercase tracking-wider mb-2">Frequently Asked Questions</div>
            <h2 class="font-display text-2xl sm:text-4xl font-bold text-[#0b1c30] tracking-[-0.025em]">
                Everything you need to know
            </h2>
        </div>

        <div class="space-y-3">
            @foreach([
                [1, 'Do I need an account to create invoices, build a resume, or convert PDFs?', 'No sign-up is ever required to use any of the three Formnexa suites. You can draft invoices, upload or edit resumes, convert files, and export watermark-free downloads immediately. Signing in is 100% optional and unlocks synced cloud history across devices.'],
                [2, 'Can I import my existing CV and apply a new modern theme?', 'Yes. Inside the Resume Builder, click Import Existing CV to upload a PDF or DOCX resume (or paste raw text). Formnexa parses your sections cleanly so you can switch across FlowCV-style dark sidebars, Figma editorial serif layouts, and ATS templates in one click.'],
                [3, 'Which file conversions are supported in the PDF suite?', 'You get 10 bidirectional tools: JPG to PDF, WORD to PDF, POWERPOINT to PDF, EXCEL to PDF, HTML to PDF, PDF to JPG (individual or ZIP), PDF to WORD (.docx), PDF to POWERPOINT (.pptx), PDF to EXCEL (.xlsx), and archival PDF to PDF/A-1b / 3b.'],
                [4, 'How many business billing formats can I generate besides standard invoices?', 'Formnexa supports 12 tailored generators: Invoices, Receipts, Quotes, Estimates, Credit Notes, Purchase Orders, Sales Orders, Proforma Invoices, Timesheets, Work Orders, Account Statements, and Packing Slips &mdash; with 20+ customizable presets.'],
            ] as [$idx, $q, $a])
                <div class="border border-border-subtle rounded-xl bg-white overflow-hidden transition"
                     :class="faqOpen === {{ $idx }} ? 'shadow-sm border-border-strong' : ''">
                    <button type="button" @click="faqOpen = (faqOpen === {{ $idx }} ? null : {{ $idx }})"
                            class="w-full p-5 text-left flex justify-between items-center gap-4 cursor-pointer">
                        <h4 class="font-display text-sm sm:text-base text-[#0b1c30] font-semibold leading-snug">{{ $q }}</h4>
                        <span class="material-symbols-outlined text-[#727687] transition-transform duration-200 shrink-0"
                              :class="faqOpen === {{ $idx }} ? 'rotate-180 text-[#0066FF]' : ''">expand_more</span>
                    </button>
                    <div x-show="faqOpen === {{ $idx }}" x-cloak x-transition.opacity.duration.150ms
                         class="px-5 pb-5 text-sm text-[#565e74] leading-relaxed border-t border-border-subtle pt-3">
                        {!! $a !!}
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- =======================================================================
         8. HIGH-IMPACT BOTTOM DARK CTA BANNER
         ======================================================================= --}}
    <section class="max-w-[1280px] mx-auto px-4 sm:px-6 pb-16 sm:pb-24">
        <div class="rounded-2xl bg-card-dark-header text-white p-8 sm:p-14 text-center relative overflow-hidden shadow-2xl">
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-80 sm:w-96 h-80 sm:h-96 bg-[#0066FF]/20 blur-[90px] rounded-full pointer-events-none"></div>
            <div class="relative z-10 max-w-2xl mx-auto">
                <h2 class="font-display text-2xl sm:text-4xl font-bold mb-4 tracking-[-0.025em]">
                    Start creating in seconds.
                </h2>
                <p class="text-sm sm:text-lg text-slate-300 mb-8">
                    Jump straight into the workspace you need &mdash; no paywalls, no trial traps, and no forced registration.
                </p>
                <div class="flex flex-col sm:flex-row flex-wrap justify-center gap-3 sm:gap-4">
                    <a href="{{ route('invoice.studio') }}"
                       class="px-6 py-3 rounded-lg bg-primary-container hover:bg-electric-blue-hover text-white font-semibold text-sm transition shadow-lg shadow-[#0066FF]/25">
                        Create an Invoice
                    </a>
                    <a href="{{ route('resume.builder') }}"
                       class="px-6 py-3 rounded-lg bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 font-semibold text-sm transition">
                        Build a Resume
                    </a>
                    <a href="{{ route('convert.hub') }}"
                       class="px-6 py-3 rounded-lg bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 font-semibold text-sm transition">
                        Convert PDFs
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
