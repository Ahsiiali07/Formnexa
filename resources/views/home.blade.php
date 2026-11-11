@extends('layouts.app')

@section('title', 'Formnexa — Invoices, Resume Builder & PDF Converter Suite')
@section('meta_description', 'Create branded invoices & 12 business documents, build ATS-ready resumes with FlowCV & Figma templates, and convert JPG, Word, PowerPoint, Excel, HTML & PDFs cleanly — 100% free.')

@section('content')
<div x-data="{
        activeModule: 'invoice',
        faqOpen: 1,
        switchModule(mod) { this.activeModule = mod; }
    }"
    class="bg-white text-[#111111]">

    {{-- =======================================================================
         1. BLOOM-STYLE WARM ALABASTER HERO (#F6F5F1)
         ======================================================================= --}}
    <section class="bg-[#F6F5F1] pt-10 sm:pt-16 lg:pt-20 pb-36 sm:pb-48 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="max-w-5xl mx-auto text-center relative">

            {{-- Floating Bloom-style rotated micro-stickers on md+ --}}
            <div class="hidden md:block pointer-events-none select-none" aria-hidden="true">
                <span class="inline-block px-3.5 py-1.5 rounded-lg bg-[#22C55E] text-white font-extrabold text-[11px] tracking-[0.12em] uppercase shadow-md -rotate-3 mb-4">
                    GET PAID
                </span>
            </div>

            {{-- Giant Tight-Tracked Editorial Headline --}}
            <div class="relative inline-block">
                <h1 class="text-[38px] sm:text-[60px] lg:text-[74px] font-extrabold tracking-[-0.04em] leading-[1.03] text-[#111111]">
                    Create invoices.<br class="hidden sm:inline">
                    Build resumes. Convert PDFs.
                </h1>

                {{-- Playful corner micro-tags around display headline --}}
                <span class="hidden lg:inline-block absolute -left-8 -bottom-5 px-3 py-1 rounded-lg bg-[#0EA5E9] text-white font-extrabold text-[11px] tracking-[0.12em] uppercase shadow-md rotate-3 select-none pointer-events-none" aria-hidden="true">
                    GET HIRED
                </span>
                <span class="hidden lg:inline-block absolute -right-8 -bottom-5 px-3 py-1 rounded-lg bg-[#FF325A] text-white font-extrabold text-[11px] tracking-[0.12em] uppercase shadow-md -rotate-3 select-none pointer-events-none" aria-hidden="true">
                    CONVERT PDF
                </span>
            </div>

            {{-- Concise Subtitle --}}
            <p class="mt-6 sm:mt-7 text-base sm:text-lg text-[#111111]/65 font-sans-inter max-w-2xl mx-auto leading-relaxed">
                Three complete document workflows in one fast modern workspace. Draft clean invoices, import or style designer resumes, and run 10 instant file conversions &mdash; free with no account required.
            </p>

            {{-- Primary & Module Action Row --}}
            <div class="mt-8 sm:mt-9 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('invoice.studio') }}"
                   class="inline-flex items-center gap-2.5 px-6 sm:px-7 py-3.5 sm:py-4 rounded-xl bg-[#0066FF] hover:bg-[#0052CC] text-white text-sm font-bold tracking-tight shadow-[0_12px_28px_-6px_rgba(0,102,255,0.45)] transition transform hover:-translate-y-0.5 whitespace-nowrap">
                    <span>Open Invoice Generator</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ route('resume.builder') }}"
                   class="inline-flex items-center gap-2 px-5 sm:px-6 py-3.5 sm:py-4 rounded-xl bg-white hover:bg-[#111111] text-[#111111] hover:text-white border border-black/[0.1] text-sm font-bold shadow-2xs transition whitespace-nowrap">
                    <span>Build Resume</span>
                </a>
                <a href="{{ route('convert.hub') }}"
                   class="inline-flex items-center gap-2 px-5 sm:px-6 py-3.5 sm:py-4 rounded-xl bg-white hover:bg-[#111111] text-[#111111] hover:text-white border border-black/[0.1] text-sm font-bold shadow-2xs transition whitespace-nowrap">
                    <span>10 PDF Converters</span>
                </a>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         2. ELEVATED INTERACTIVE WORKSPACE SHOWCASE + FEATURE TABS
         ======================================================================= --}}
    <section class="px-4 sm:px-6 lg:px-8 -mt-28 sm:-mt-36 pb-16 relative z-10">
        <div class="max-w-6xl mx-auto">

            {{-- Elevated Browser Window Mockup Frame (Bloom.io signature overlap) --}}
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-black/10 shadow-[0_32px_90px_-20px_rgba(17,17,17,0.18)] overflow-hidden">
                {{-- Window Top Chrome --}}
                <div class="bg-[#111111] px-4 sm:px-6 py-3 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#FF5F56]"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#FFBD2E]"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#27C93F]"></span>
                        <span class="hidden sm:inline-block ml-3 text-[11px] font-medium text-white/50 tracking-wide font-sans-inter">
                            formnexa.app/<span x-text="activeModule === 'invoice' ? 'invoice-generator' : (activeModule === 'resume' ? 'resume-builder' : (activeModule === 'import' ? 'resume-builder?import=1' : (activeModule === 'templates' ? 'templates' : 'convert')))"></span>
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] font-semibold">
                        <a :href="activeModule === 'invoice' ? '{{ route('invoice.studio') }}' : (activeModule === 'resume' ? '{{ route('resume.builder') }}' : (activeModule === 'import' ? '{{ route('resume.builder', ['import' => 1]) }}' : (activeModule === 'templates' ? '{{ route('templates.index') }}' : '{{ route('convert.hub') }}')))"
                           class="px-3 py-1 rounded-md bg-white/10 hover:bg-[#0066FF] text-white transition whitespace-nowrap">
                            Launch Live Tool &rarr;
                        </a>
                    </div>
                </div>

                {{-- Workspace Preview Body --}}
                <div class="p-5 sm:p-8 lg:p-10 bg-white min-h-[370px] sm:min-h-[420px]">

                    {{-- MODULE PREVIEW A: INVOICE & 12 BILLING DOCS --}}
                    <div x-show="activeModule === 'invoice'" x-transition.opacity.duration.200ms class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center">
                        <div class="lg:col-span-5 space-y-5">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-[#F6F5F1] text-[#111111] text-[11px] font-extrabold uppercase tracking-wider">
                                Module 01 &bull; Invoicing &amp; Billing
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-[-0.03em] text-[#111111] leading-tight">
                                Generate custom invoices, quotes &amp; receipts in seconds.
                            </h2>
                            <p class="text-sm text-[#111111]/65 leading-relaxed font-sans-inter">
                                Switch between 12 billing formats, upload custom cover headers and logos, embed Stripe or PayPal links, and export clean zero-watermark PDFs.
                            </p>
                            <div class="grid grid-cols-2 gap-2 text-xs font-semibold text-[#111111]/80">
                                <div class="p-2.5 rounded-xl bg-[#F6F5F1]">6 Layout Engines</div>
                                <div class="p-2.5 rounded-xl bg-[#F6F5F1]">24 Global Currencies</div>
                                <div class="p-2.5 rounded-xl bg-[#F6F5F1]">Digital Signature Pad</div>
                                <div class="p-2.5 rounded-xl bg-[#F6F5F1]">Direct Client Email</div>
                            </div>
                            <div class="flex flex-wrap gap-2.5 pt-1">
                                <a href="{{ route('invoice.studio') }}" class="px-5 py-2.5 rounded-lg bg-[#0066FF] hover:bg-[#0052CC] text-white text-xs font-bold transition">Open Invoice Studio &rarr;</a>
                                <a href="{{ route('templates.index') }}" class="px-4 py-2.5 rounded-lg border border-black/15 hover:border-black text-xs font-bold text-[#111111] transition">20+ Templates</a>
                            </div>
                        </div>

                        {{-- Crisp Interactive Invoice Sheet Card --}}
                        <div class="lg:col-span-7">
                            <div class="rounded-2xl border border-black/10 bg-white shadow-lg overflow-hidden">
                                <div class="h-14 bg-gradient-to-r from-[#111111] via-[#0F172A] to-[#0066FF] px-6 flex items-center justify-between text-white">
                                    <span class="font-bold text-xs tracking-wide uppercase opacity-90">Northstar Design Studio</span>
                                    <span class="text-xs font-mono font-bold bg-white/15 px-2.5 py-0.5 rounded">INV-2026-04</span>
                                </div>
                                <div class="p-5 sm:p-6 space-y-4">
                                    <div class="flex flex-wrap justify-between gap-4 border-b border-black/[0.06] pb-4 text-xs">
                                        <div>
                                            <div class="text-[10px] font-bold uppercase tracking-wider text-black/40">Billed To</div>
                                            <div class="font-bold text-[#111111] mt-0.5">Acme Commerce Inc.</div>
                                            <div class="text-black/50">billing@acmecommerce.io</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-[10px] font-bold uppercase tracking-wider text-black/40">Balance Due</div>
                                            <div class="text-2xl font-black text-[#111111] tracking-tight">$4,650.00</div>
                                            <div class="text-[11px] text-emerald-600 font-semibold">Due on receipt</div>
                                        </div>
                                    </div>
                                    <div class="space-y-2 text-xs">
                                        <div class="flex justify-between items-center py-2 px-3 rounded-lg bg-[#F6F5F1]">
                                            <span class="font-semibold text-[#111111]">Brand Identity &amp; Design System</span>
                                            <span class="font-bold text-[#111111]">$3,200.00</span>
                                        </div>
                                        <div class="flex justify-between items-center py-2 px-3 rounded-lg bg-[#F6F5F1]">
                                            <span class="font-semibold text-[#111111]">Responsive Marketing Website</span>
                                            <span class="font-bold text-[#111111]">$1,450.00</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap items-center justify-between gap-2 pt-2 text-[11px]">
                                        <div class="flex items-center gap-1.5 text-black/55 font-medium">
                                            <span class="px-2 py-1 rounded border border-black/10">Stripe</span>
                                            <span class="px-2 py-1 rounded border border-black/10">PayPal</span>
                                            <span class="px-2 py-1 rounded border border-black/10">Bank Transfer</span>
                                        </div>
                                        <span class="px-3 py-1.5 rounded-lg bg-[#111111] text-white font-bold">Download Free PDF</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MODULE PREVIEW B: RESUME BUILDER --}}
                    <div x-show="activeModule === 'resume'" x-cloak x-transition.opacity.duration.200ms class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center">
                        <div class="lg:col-span-5 space-y-5">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-emerald-50 text-emerald-800 text-[11px] font-extrabold uppercase tracking-wider">
                                Module 02 &bull; Career &amp; Resumes
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-[-0.03em] text-[#111111] leading-tight">
                                FlowCV &amp; Figma-grade resume studio with live A4 preview.
                            </h2>
                            <p class="text-sm text-[#111111]/65 leading-relaxed font-sans-inter">
                                Build standout CVs side-by-side with live formatting. Customize section ordering, accent colors, fonts, profile photos, and download crisp ATS-friendly PDFs.
                            </p>
                            <div class="flex flex-wrap gap-2.5 pt-1">
                                <a href="{{ route('resume.builder') }}" class="px-5 py-2.5 rounded-lg bg-[#0066FF] hover:bg-[#0052CC] text-white text-xs font-bold transition">Open Resume Builder &rarr;</a>
                                <a href="{{ route('resume.templates') }}" class="px-4 py-2.5 rounded-lg border border-black/15 hover:border-black text-xs font-bold text-[#111111] transition">Browse 15+ CV Themes</a>
                            </div>
                        </div>

                        <div class="lg:col-span-7">
                            <div class="rounded-2xl border border-black/10 shadow-lg overflow-hidden grid grid-cols-12 min-h-[280px] bg-white">
                                <div class="col-span-4 bg-[#182B3A] text-white p-5 space-y-4">
                                    <div class="w-12 h-12 rounded-full bg-white/15 flex items-center justify-center font-black text-sm">BW</div>
                                    <div>
                                        <div class="font-bold text-sm leading-tight">Brian Wayne</div>
                                        <div class="text-[10px] text-white/65 mt-0.5">Product Strategist</div>
                                    </div>
                                    <div class="pt-2 border-t border-white/15 space-y-1.5 text-[10px] text-white/75">
                                        <div class="font-bold uppercase tracking-wider text-white/45 text-[9px]">Skills</div>
                                        <div>Product Strategy</div>
                                        <div>Growth Analytics</div>
                                        <div>Figma &amp; Design Systems</div>
                                    </div>
                                </div>
                                <div class="col-span-8 p-5 sm:p-6 space-y-4">
                                    <div>
                                        <div class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-[#182B3A] bg-[#F6F5F1] px-2.5 py-1 rounded">Work Experience</div>
                                        <div class="mt-2.5 space-y-2.5">
                                            <div>
                                                <div class="flex justify-between text-xs font-bold text-[#111111]">
                                                    <span>Senior Product Consultant &bull; Horizon</span>
                                                    <span class="text-black/45">2023 &ndash; Now</span>
                                                </div>
                                                <p class="text-[11px] text-black/60 mt-1">Led cross-functional redesign driving +38% activation across billing and resume suites.</p>
                                            </div>
                                            <div>
                                                <div class="flex justify-between text-xs font-bold text-[#111111]">
                                                    <span>Business Analyst &bull; Apex Advisory</span>
                                                    <span class="text-black/45">2020 &ndash; 2023</span>
                                                </div>
                                                <p class="text-[11px] text-black/60 mt-1">Delivered executive reporting pipelines for 40+ enterprise accounts.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-[#182B3A] bg-[#F6F5F1] px-2.5 py-1 rounded">Education</div>
                                        <div class="mt-2 flex justify-between text-xs font-bold text-[#111111]">
                                            <span>MBA, Strategy &amp; Operations</span>
                                            <span class="text-black/45">2020</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MODULE PREVIEW C: SMART CV IMPORTER --}}
                    <div x-show="activeModule === 'import'" x-cloak x-transition.opacity.duration.200ms class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center">
                        <div class="lg:col-span-5 space-y-5">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-blue-50 text-[#0066FF] text-[11px] font-extrabold uppercase tracking-wider">
                                Smart CV Parser
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-[-0.03em] text-[#111111] leading-tight">
                                Upload any existing PDF or DOCX CV &amp; restyle instantly.
                            </h2>
                            <p class="text-sm text-[#111111]/65 leading-relaxed font-sans-inter">
                                Already have an old resume? Upload your PDF or Word file or paste plain text &mdash; Formnexa automatically extracts your roles, dates, skills, and education and maps them cleanly onto any of our 15+ modern themes.
                            </p>
                            <a href="{{ route('resume.builder', ['import' => 1]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#0066FF] hover:bg-[#0052CC] text-white text-xs font-bold transition">Import Existing CV Now &rarr;</a>
                        </div>
                        <div class="lg:col-span-7">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="p-4 rounded-2xl border border-black/[0.08] bg-[#F6F5F1] space-y-2">
                                    <span class="inline-flex px-2 py-0.5 rounded bg-white text-[10px] font-extrabold text-[#111111]">STEP 01</span>
                                    <div class="font-bold text-sm text-[#111111]">Upload or Paste</div>
                                    <p class="text-xs text-black/60 leading-relaxed">Drop your current `.pdf` or `.docx` file or paste LinkedIn / plain CV text.</p>
                                </div>
                                <div class="p-4 rounded-2xl border border-black/[0.08] bg-[#F6F5F1] space-y-2">
                                    <span class="inline-flex px-2 py-0.5 rounded bg-white text-[10px] font-extrabold text-[#0066FF]">STEP 02</span>
                                    <div class="font-bold text-sm text-[#111111]">Auto-Structure</div>
                                    <p class="text-xs text-black/60 leading-relaxed">Extracts contact info, summary, experience bullets, education, and skills cleanly.</p>
                                </div>
                                <div class="p-4 rounded-2xl border border-black/[0.08] bg-[#111111] text-white space-y-2">
                                    <span class="inline-flex px-2 py-0.5 rounded bg-[#0066FF] text-[10px] font-extrabold text-white">STEP 03</span>
                                    <div class="font-bold text-sm">Pick Any Theme</div>
                                    <p class="text-xs text-white/70 leading-relaxed">Applies FlowCV sidebars, Figma editorial layouts, or ATS formats with 1 click.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MODULE PREVIEW D: CONVERT TO PDF (5) --}}
                    <div x-show="activeModule === 'to_pdf'" x-cloak x-transition.opacity.duration.200ms class="space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                            <div>
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-rose-50 text-[#FF325A] text-[11px] font-extrabold uppercase tracking-wider">Module 03 &bull; Convert To PDF</div>
                                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-[-0.03em] text-[#111111] mt-2">Turn images, Office documents &amp; web HTML into crisp PDFs.</h2>
                            </div>
                            <a href="{{ route('convert.hub') }}" class="text-xs font-bold text-[#0066FF] hover:underline shrink-0">Explore All 10 Converters &rarr;</a>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                            @foreach($converterGroups['to_pdf'] as $cTool)
                                <a href="{{ route('convert.tool', $cTool['slug']) }}" class="p-4 rounded-2xl bg-[#F6F5F1] hover:bg-white border border-black/[0.07] hover:border-black/25 hover:shadow-md transition flex flex-col justify-between gap-4 group">
                                    <span class="w-10 h-10 rounded-xl {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-black text-xs">{{ $cTool['badge'] }}</span>
                                    <div>
                                        <div class="font-extrabold text-sm text-[#111111] group-hover:text-[#0066FF]">{{ $cTool['name'] }}</div>
                                        <p class="text-[11px] text-black/55 mt-1 line-clamp-2">{{ $cTool['tagline'] }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- MODULE PREVIEW E: CONVERT FROM PDF (5) --}}
                    <div x-show="activeModule === 'from_pdf'" x-cloak x-transition.opacity.duration.200ms class="space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                            <div>
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-amber-50 text-amber-700 text-[11px] font-extrabold uppercase tracking-wider">Module 03 &bull; Convert From PDF</div>
                                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-[-0.03em] text-[#111111] mt-2">Extract high-DPI JPGs, editable Office files &amp; archival PDF/A.</h2>
                            </div>
                            <a href="{{ route('convert.hub') }}" class="text-xs font-bold text-[#0066FF] hover:underline shrink-0">Explore All 10 Converters &rarr;</a>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                            @foreach($converterGroups['from_pdf'] as $cTool)
                                <a href="{{ route('convert.tool', $cTool['slug']) }}" class="p-4 rounded-2xl bg-[#F6F5F1] hover:bg-white border border-black/[0.07] hover:border-black/25 hover:shadow-md transition flex flex-col justify-between gap-4 group">
                                    <span class="w-10 h-10 rounded-xl {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-black text-xs">{{ $cTool['badge'] }}</span>
                                    <div>
                                        <div class="font-extrabold text-sm text-[#111111] group-hover:text-[#0066FF]">{{ $cTool['name'] }}</div>
                                        <p class="text-[11px] text-black/55 mt-1 line-clamp-2">{{ $cTool['tagline'] }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- MODULE PREVIEW F: ALL TEMPLATES (35+) --}}
                    <div x-show="activeModule === 'templates'" x-cloak x-transition.opacity.duration.200ms class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        <div class="p-6 rounded-2xl bg-[#F6F5F1] border border-black/[0.07] space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-extrabold uppercase tracking-wider text-[#0066FF]">20+ Invoice Templates</span>
                                <span class="text-xs font-bold text-black/45">6 Layouts</span>
                            </div>
                            <h3 class="text-xl font-extrabold text-[#111111]">Industry-tailored invoices, quotes &amp; purchase orders</h3>
                            <p class="text-xs text-black/60 leading-relaxed">Freelance web dev, consulting, construction, creative agency, photography, SaaS retainers, and clean corporate ledgers.</p>
                            <a href="{{ route('templates.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-[#111111] hover:bg-[#0066FF] text-white text-xs font-bold transition">Browse Invoice Templates &rarr;</a>
                        </div>
                        <div class="p-6 rounded-2xl bg-[#F6F5F1] border border-black/[0.07] space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-700">15+ Resume Templates</span>
                                <span class="text-xs font-bold text-black/45">10 Engines</span>
                            </div>
                            <h3 class="text-xl font-extrabold text-[#111111]">FlowCV-style sidebars, Figma spreads &amp; ATS singles</h3>
                            <p class="text-xs text-black/60 leading-relaxed">Upload your existing CV once and switch between editorial serif spreads, sleek timelines, or minimalist tech layouts effortlessly.</p>
                            <a href="{{ route('resume.templates') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-[#111111] hover:bg-emerald-600 text-white text-xs font-bold transition">Browse Resume Templates &rarr;</a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Signature Bloom-Style Horizontal/Wrapped Filter Strip below showcase window --}}
            <div class="mt-8 flex flex-wrap items-center justify-center gap-2.5">
                @foreach([
                    ['invoice', 'Invoice & Billing Studio'],
                    ['resume', 'Resume Builder (CV)'],
                    ['import', 'Import Existing CV'],
                    ['to_pdf', 'Convert to PDF (5)'],
                    ['from_pdf', 'Convert from PDF (5)'],
                    ['templates', 'Templates Gallery (35+)'],
                ] as [$tabKey, $tabLabel])
                    <button type="button"
                            @click="switchModule('{{ $tabKey }}')"
                            :class="activeModule === '{{ $tabKey }}'
                                ? 'bg-[#111111] text-white border-[#111111] shadow-md'
                                : 'bg-white text-[#111111] border-black/[0.09] hover:border-black/30'"
                            class="px-4 sm:px-5 py-2.5 rounded-xl border text-xs sm:text-[13px] font-bold transition cursor-pointer">
                        {{ $tabLabel }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =======================================================================
         3. CAPABILITY SUMMARY STRIP
         ======================================================================= --}}
    <section class="border-y border-black/[0.06] bg-white py-9 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#111111]">12</div>
                <div class="text-xs font-medium text-black/55 mt-1">Billing &amp; Order Generators</div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#111111]">15+</div>
                <div class="text-xs font-medium text-black/55 mt-1">Designer Resume Templates</div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#111111]">10</div>
                <div class="text-xs font-medium text-black/55 mt-1">Fast PDF Conversion Tools</div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#0066FF]">100% Free</div>
                <div class="text-xs font-medium text-black/55 mt-1">Zero Watermarks &bull; No Sign-up</div>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         4. MODULE 01 DEEP SHOWCASE: INVOICE GENERATOR & 12 DOCUMENTS (#F6F5F1)
         ======================================================================= --}}
    <section class="bg-[#F6F5F1] py-16 sm:py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto space-y-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                <div class="lg:col-span-6 space-y-5">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-white border border-black/[0.08] text-xs font-extrabold uppercase tracking-wider text-[#0066FF]">
                        01 / Invoice Generator
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-extrabold tracking-[-0.035em] text-[#111111] leading-[1.08]">
                        Invoice &amp; get paid without friction.
                    </h2>
                    <p class="text-sm sm:text-base text-black/65 font-sans-inter leading-relaxed">
                        Just like the best modern freelance workspaces, Formnexa lets you customize logos, cover artwork, taxes, discounts, digital signatures, and payment links (Stripe, PayPal, Venmo, Zelle) with instant vector PDF output.
                    </p>
                    <div class="flex flex-wrap gap-3 pt-1">
                        <a href="{{ route('invoice.studio') }}" class="px-6 py-3.5 rounded-xl bg-[#111111] hover:bg-[#0066FF] text-white text-xs sm:text-sm font-bold transition shadow-sm">
                            Launch Invoice Generator &rarr;
                        </a>
                        <a href="{{ route('templates.index') }}" class="px-5 py-3.5 rounded-xl bg-white hover:bg-black/[0.03] border border-black/[0.1] text-[#111111] text-xs sm:text-sm font-bold transition">
                            20+ Invoice Templates
                        </a>
                    </div>
                </div>

                {{-- 12 Document Generators Quick Matrix --}}
                <div class="lg:col-span-6 bg-white rounded-3xl border border-black/[0.08] p-6 sm:p-7 shadow-[0_20px_50px_-15px_rgba(17,17,17,0.08)]">
                    <div class="text-[11px] font-extrabold uppercase tracking-[0.14em] text-black/40 mb-4">
                        All 12 Supported Document Formats
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        @foreach($generators as $slug => $gen)
                            <a href="{{ $slug === 'invoice-generator' ? route('invoice.studio') : route('invoice.generator', $slug) }}"
                               class="px-3 py-2.5 rounded-xl bg-[#F6F5F1] hover:bg-[#111111] text-[#111111] hover:text-white text-xs font-bold transition flex items-center justify-between gap-1 group">
                                <span class="truncate">{{ str_replace(' Generator', '', $gen['name']) }}</span>
                                <span class="opacity-40 group-hover:opacity-100">&rarr;</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         5. MODULE 02 DEEP SHOWCASE: RESUME BUILDER & CV IMPORTER (#FFFFFF)
         ======================================================================= --}}
    <section class="bg-white py-16 sm:py-24 px-4 sm:px-6 lg:px-8 border-t border-black/[0.06]">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
            {{-- Left Visual Cards Grid --}}
            <div class="lg:col-span-6 order-2 lg:order-1 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach(array_slice($resumeTemplates, 0, 4) as $rTpl)
                    <a href="{{ route('resume.builder', ['template' => $rTpl['slug']]) }}"
                       class="group rounded-2xl border border-black/[0.08] bg-[#F6F5F1]/65 hover:bg-white hover:border-black/25 hover:shadow-lg p-5 transition flex flex-col justify-between gap-4">
                        <div class="flex items-center justify-between">
                            <span class="w-3.5 h-3.5 rounded-full border border-white shadow-xs" style="background-color: {{ $rTpl['accent_color'] ?? '#1B3142' }}"></span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-black/45">{{ $rTpl['category_label'] ?? 'Designer CV' }}</span>
                        </div>
                        <div>
                            <div class="font-extrabold text-sm text-[#111111] group-hover:text-[#0066FF] transition">{{ $rTpl['name'] }}</div>
                            <div class="text-[11px] text-black/55 mt-1 line-clamp-2">{{ $rTpl['description'] }}</div>
                        </div>
                        <div class="text-xs font-bold text-[#0066FF] pt-2 border-t border-black/[0.06]">
                            Use Theme &rarr;
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Right Copy --}}
            <div class="lg:col-span-6 order-1 lg:order-2 space-y-5">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-emerald-50 border border-emerald-200/70 text-xs font-extrabold uppercase tracking-wider text-emerald-700">
                    02 / Resume Builder &amp; Importer
                </span>
                <h2 class="text-3xl sm:text-5xl font-extrabold tracking-[-0.035em] text-[#111111] leading-[1.08]">
                    Import your CV once. Switch modern themes in a tap.
                </h2>
                <p class="text-sm sm:text-base text-black/65 font-sans-inter leading-relaxed">
                    Crafted with FlowCV-style dark sidebars, Figma editorial serif typography, connected timelines, and ATS-friendly single columns. Upload an old PDF or DOCX resume to auto-populate every section cleanly.
                </p>
                <div class="flex flex-wrap gap-3 pt-1">
                    <a href="{{ route('resume.builder') }}" class="px-6 py-3.5 rounded-xl bg-[#111111] hover:bg-emerald-600 text-white text-xs sm:text-sm font-bold transition shadow-sm">
                        Build Resume Free &rarr;
                    </a>
                    <a href="{{ route('resume.builder', ['import' => 1]) }}" class="px-5 py-3.5 rounded-xl bg-[#F6F5F1] hover:bg-black/10 text-[#111111] text-xs sm:text-sm font-bold transition">
                        Import Existing CV
                    </a>
                    <a href="{{ route('resume.templates') }}" class="px-5 py-3.5 rounded-xl border border-black/10 hover:border-black text-[#111111] text-xs sm:text-sm font-bold transition">
                        15+ CV Themes
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         6. MODULE 03 DEEP SHOWCASE: COMPLETE 10-TOOL PDF CONVERTER SUITE (#F6F5F1)
         ======================================================================= --}}
    <section class="bg-[#F6F5F1] py-16 sm:py-24 px-4 sm:px-6 lg:px-8 border-t border-black/[0.06]">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 sm:mb-12">
                <div class="max-w-2xl space-y-3">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-white border border-black/[0.08] text-xs font-extrabold uppercase tracking-wider text-[#FF325A]">
                        03 / PDF Converter Suite
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-extrabold tracking-[-0.035em] text-[#111111] leading-[1.08]">
                        10 essential file &amp; PDF conversions built in.
                    </h2>
                    <p class="text-sm sm:text-base text-black/65 font-sans-inter">
                        No watermarks, no page limits locked behind paywalls. Convert images, Office documents, or HTML into PDF &mdash; and convert PDFs back into editable formats or ISO PDF/A archives.
                    </p>
                </div>
                <a href="{{ route('convert.hub') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-[#111111] hover:bg-[#0066FF] text-white text-xs sm:text-sm font-bold transition shrink-0 self-start md:self-auto">
                    All 10 Converters &rarr;
                </a>
            </div>

            {{-- Exact Two-Column Structure: CONVERT TO PDF (5) | CONVERT FROM PDF (5) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                {{-- Column 1: CONVERT TO PDF --}}
                <div class="bg-white rounded-3xl border border-black/[0.08] p-6 sm:p-8 shadow-2xs space-y-4">
                    <div class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-black/40 pb-2 border-b border-black/[0.06]">
                        Convert To PDF
                    </div>
                    <div class="space-y-2.5">
                        @foreach($converterGroups['to_pdf'] as $cTool)
                            <a href="{{ route('convert.tool', $cTool['slug']) }}"
                               class="flex items-center justify-between gap-4 p-3 rounded-2xl hover:bg-[#F6F5F1] transition group">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <span class="w-10 h-10 rounded-xl {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-black text-xs shrink-0">
                                        {{ $cTool['badge'] }}
                                    </span>
                                    <div class="min-w-0">
                                        <div class="font-extrabold text-sm text-[#111111] group-hover:text-[#0066FF] transition">{{ $cTool['name'] }}</div>
                                        <div class="text-xs text-black/55 truncate">{{ $cTool['accept_label'] }}</div>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-black/35 group-hover:text-[#0066FF] shrink-0">&rarr;</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Column 2: CONVERT FROM PDF --}}
                <div class="bg-white rounded-3xl border border-black/[0.08] p-6 sm:p-8 shadow-2xs space-y-4">
                    <div class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-black/40 pb-2 border-b border-black/[0.06]">
                        Convert From PDF
                    </div>
                    <div class="space-y-2.5">
                        @foreach($converterGroups['from_pdf'] as $cTool)
                            <a href="{{ route('convert.tool', $cTool['slug']) }}"
                               class="flex items-center justify-between gap-4 p-3 rounded-2xl hover:bg-[#F6F5F1] transition group">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <span class="w-10 h-10 rounded-xl {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-black text-xs shrink-0">
                                        {{ $cTool['badge'] }}
                                    </span>
                                    <div class="min-w-0">
                                        <div class="font-extrabold text-sm text-[#111111] group-hover:text-[#0066FF] transition">{{ $cTool['name'] }}</div>
                                        <div class="text-xs text-black/55 truncate">{{ $cTool['tagline'] }}</div>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-black/35 group-hover:text-[#0066FF] shrink-0">&rarr;</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         7. BLOOM-STYLE FAQ ACCORDION + DARK CLOSING WORKSPACE CARD (#FFFFFF)
         ======================================================================= --}}
    <section class="bg-white py-16 sm:py-24 px-4 sm:px-6 lg:px-8 border-t border-black/[0.06]">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-10 sm:mb-12">
                <span class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#0066FF]">Frequently Asked Questions</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-[-0.03em] text-[#111111] mt-2">Everything you need to know</h2>
            </div>

            <div class="space-y-3 font-sans-inter">
                @foreach([
                    [1, 'Do I need an account to create invoices, build a resume, or convert PDFs?', 'No sign-up is ever required to use any of the three Formnexa suites. You can draft invoices, upload or edit resumes, convert files, and export watermark-free downloads immediately. Signing in is 100% optional and unlocks synced cloud history across devices.'],
                    [2, 'Can I import my existing CV and apply a new modern theme?', 'Yes. Inside the Resume Builder, click Import Existing CV to upload a PDF or DOCX resume (or paste raw text). Formnexa parses your sections cleanly so you can switch across FlowCV-style dark sidebars, Figma editorial serif layouts, and ATS templates in one click.'],
                    [3, 'Which file conversions are supported in the PDF suite?', 'You get 10 bidirectional tools: JPG to PDF, WORD to PDF, POWERPOINT to PDF, EXCEL to PDF, HTML to PDF, PDF to JPG (individual or ZIP), PDF to WORD (.docx), PDF to POWERPOINT (.pptx), PDF to EXCEL (.xlsx), and archival PDF to PDF/A-1b / 3b.'],
                    [4, 'How many business billing formats can I generate besides standard invoices?', 'Formnexa supports 12 tailored generators: Invoices, Receipts, Quotes, Estimates, Credit Notes, Purchase Orders, Sales Orders, Proforma Invoices, Timesheets, Work Orders, Account Statements, and Packing Slips &mdash; with 20+ customizable presets.'],
                ] as [$idx, $q, $a])
                    <div class="rounded-2xl border border-black/[0.08] bg-[#F6F5F1]/60 overflow-hidden transition"
                         :class="faqOpen === {{ $idx }} ? 'bg-white shadow-sm border-black/15' : ''">
                        <button type="button" @click="faqOpen = (faqOpen === {{ $idx }} ? null : {{ $idx }})"
                                class="w-full px-6 py-4.5 text-left flex items-center justify-between gap-4 font-bold text-sm sm:text-base text-[#111111] cursor-pointer">
                            <span>{{ $q }}</span>
                            <span class="w-6 h-6 rounded-full bg-white border border-black/[0.08] flex items-center justify-center text-xs shrink-0 transition-transform"
                                  :class="faqOpen === {{ $idx }} ? 'rotate-45' : ''">+</span>
                        </button>
                        <div x-show="faqOpen === {{ $idx }}" x-cloak x-transition.opacity.duration.150ms class="px-6 pb-5 text-xs sm:text-sm text-black/65 leading-relaxed">
                            {!! $a !!}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Architectural Dark CTA Card --}}
            <div class="mt-16 rounded-3xl bg-[#111111] text-white p-8 sm:p-12 text-center space-y-6 shadow-[0_30px_80px_-20px_rgba(17,17,17,0.4)]">
                <h2 class="text-2xl sm:text-4xl font-extrabold tracking-[-0.03em]">Start creating in seconds.</h2>
                <p class="text-xs sm:text-base text-white/65 max-w-xl mx-auto font-sans-inter">
                    Jump straight into the workspace you need &mdash; no paywalls, no trial traps, and no forced registration.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-3 pt-1">
                    <a href="{{ route('invoice.studio') }}" class="px-6 py-3.5 rounded-xl bg-[#0066FF] hover:bg-blue-500 text-white text-xs sm:text-sm font-bold transition">Create an Invoice</a>
                    <a href="{{ route('resume.builder') }}" class="px-6 py-3.5 rounded-xl bg-white text-[#111111] hover:bg-emerald-400 text-xs sm:text-sm font-bold transition">Build a Resume</a>
                    <a href="{{ route('convert.hub') }}" class="px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs sm:text-sm font-bold transition">Convert PDFs</a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
