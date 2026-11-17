@extends('layouts.app')

@push('styles')
    @vite('resources/css/home.css')
@endpush

@section('content')
@php
    $converterAll = \App\Support\ConverterCatalog::all();
    $faqItems = [
        [
            'q' => 'Can I start creating without an account?',
            'a' => 'Yes. Open any studio right away, build your resume, invoice, or converted PDF, and download your file immediately without logging in. Sign up anytime to save document history across devices.',
        ],
        [
            'q' => 'How do I start an ATS CV online for free?',
            'a' => 'Open the ATS Resume Builder to pick from 17+ recruiter-vetted templates, enter your job experience and skills, or upload an existing PDF/DOCX resume to auto-fill sections in seconds with zero watermarks.',
        ],
        [
            'q' => 'Can I convert PDF to Word DOCX without losing formatting?',
            'a' => 'Yes. Formnexa\'s PDF to Word converter reconstructs native paragraphs, tables, headings, and font spacing cleanly so you can edit the resulting DOCX in Microsoft Word or Google Docs with zero formatting loss.',
        ],
        [
            'q' => 'How many document types does Formnexa support?',
            'a' => 'Alongside our Resume Builder and Smart CV Importer, you get 12 business document generators (Invoices, Receipts, Quotes, Estimates, Credit Notes, Purchase Orders, Sales Orders, Proforma Invoices, Timesheets, Work Orders, Account Statements, and Packing Slips) plus ' . count($converterAll) . ' PDF conversion tools.',
        ],
        [
            'q' => 'Will my resume work cleanly with ATS hiring software?',
            'a' => 'Every resume layout is built around clean headings, readable typography, and structured PDF output so both Applicant Tracking Systems and hiring managers read every section clearly.',
        ],
        [
            'q' => 'Are there watermarks on downloaded PDFs or converted files?',
            'a' => 'Never. Every invoice, resume, and converted file exports cleanly at crisp resolution with zero Formnexa watermarks.',
        ],
    ];
@endphp

<div class="fn-home" x-data="{ activeProduct: 'resume', openFaq: 0, dirTab: 'docs' }">

    {{-- 1. HERO + INTERACTIVE PRODUCT STAGE (Figma Make) --}}
    <section class="fn-hero">
        <div class="fn-hero-grid" aria-hidden="true"></div>
        <div class="fn-hero-glow fn-glow-one" aria-hidden="true"></div>
        <div class="fn-hero-glow fn-glow-two" aria-hidden="true"></div>

        {{-- Floating Micro-Cards (Figma Make) --}}
        <div class="fn-floating-chip fn-chip-resume" aria-hidden="true">
            <span>
                <svg class="w-4 h-4 text-[#6142be]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2h12v20H6z"/><circle cx="12" cy="8" r="2"/><path d="M9 14h6M9 18h6"/></svg>
            </span>
            <div>
                <strong>Resume score</strong>
                <small>Excellent &bull; 98% ATS match</small>
            </div>
        </div>
        <div class="fn-floating-chip fn-chip-pdf" aria-hidden="true">
            <span>
                <svg class="w-4 h-4 text-[#184f3a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m5 12 4 4L19 6"/></svg>
            </span>
            <div>
                <strong>PDF ready</strong>
                <small>Converted in 0.8 sec</small>
            </div>
        </div>

        <div class="fn-hero-inner">
            <span class="fn-eyebrow">
                <span class="dot" aria-hidden="true"></span>
                <span>Your documents, beautifully simple</span>
            </span>

            <h1>
                Free Invoice Maker, <span>Resume Builder &amp; PDF Converter</span>
            </h1>

            <p class="fn-hero-copy">
                Build a standout ATS resume, create client-ready invoices with live tax &amp; signatures, or convert any file to print-ready PDF &mdash; all in one beautifully designed workspace.
            </p>

            <div class="fn-hero-actions">
                <a href="{{ route('invoice.studio') }}" class="fn-primary-btn">
                    <span>Create for free</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="#how" class="fn-secondary-btn">
                    <span class="w-4 h-4 rounded-full bg-[#184f3a]/15 text-[#184f3a] inline-flex items-center justify-center text-[9px] mr-1">&#9654;</span>
                    See how it works
                </a>
            </div>

            <div class="fn-hero-note">
                <span>
                    <x-icon name="check-circle" class="w-4 h-4 text-[#184f3a]" />
                    No credit card
                </span>
                <span>
                    <x-icon name="check-circle" class="w-4 h-4 text-[#184f3a]" />
                    Free to start
                </span>
                <span>
                    <x-icon name="shield" class="w-4 h-4 text-[#184f3a]" />
                    Secure &amp; private
                </span>
            </div>
        </div>

        {{-- Interactive Product Switcher --}}
        <div class="fn-product-stage" id="tools">
            <div class="fn-product-tabs" role="tablist" aria-label="Formnexa tools">
                <button type="button" role="tab" :aria-selected="(activeProduct === 'resume').toString()"
                        :class="activeProduct === 'resume' ? 'active' : ''"
                        @click="activeProduct = 'resume'">
                    <x-icon name="document-text" class="w-[17px] h-[17px]" />
                    <span>Resume Builder</span>
                </button>
                <button type="button" role="tab" :aria-selected="(activeProduct === 'invoice').toString()"
                        :class="activeProduct === 'invoice' ? 'active' : ''"
                        @click="activeProduct = 'invoice'">
                    <x-icon name="receipt" class="w-[17px] h-[17px]" />
                    <span>Invoice Maker</span>
                </button>
                <button type="button" role="tab" :aria-selected="(activeProduct === 'pdf').toString()"
                        :class="activeProduct === 'pdf' ? 'active' : ''"
                        @click="activeProduct = 'pdf'">
                    <x-icon name="arrows-right-left" class="w-[17px] h-[17px]" />
                    <span>PDF Converter</span>
                </button>
            </div>

            <div class="fn-preview-shell"
                 :class="activeProduct === 'resume' ? 'violet' : (activeProduct === 'invoice' ? 'mint' : 'orange')">
                <div class="fn-preview-window">
                    <div class="fn-window-top">
                        <div class="fn-window-dots">
                            <i></i><i></i><i></i>
                        </div>
                        <span class="fn-window-title" x-text="activeProduct === 'resume' ? 'Formnexa · Resume Studio' : (activeProduct === 'invoice' ? 'Formnexa · Invoice Studio' : 'Formnexa · File Converter')"></span>
                        <a href="{{ route('resume.builder') }}"
                           :href="activeProduct === 'resume' ? '{{ route('resume.builder') }}' : (activeProduct === 'invoice' ? '{{ route('invoice.studio') }}' : '{{ route('convert.hub') }}')"
                           class="fn-window-pill">
                            <span x-text="activeProduct === 'resume' ? 'Open Resume Studio' : (activeProduct === 'invoice' ? 'Open Invoice Maker' : 'Open Converter Hub')"></span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>

                    {{-- Tab 1: Resume Preview --}}
                    <div x-show="activeProduct === 'resume'" x-transition.opacity.duration.150ms class="fn-resume-preview">
                        <div class="fn-preview-sidebar">
                            <div class="fn-side-label">Sections</div>
                            <div class="fn-side-item done">Personal info</div>
                            <div class="fn-side-item done">Work experience</div>
                            <div class="fn-side-item current">Skills &amp; ATS</div>
                            <div class="fn-side-item">Education</div>
                            <a href="{{ route('resume.builder', ['import' => 1]) }}" rel="nofollow"
                               class="mt-auto inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-[#f0ebff] hover:bg-[#e3d9ff] text-[#5d3ec4] text-xs font-bold transition">
                                <x-icon name="upload" class="w-[15px] h-[15px]" />
                                Import Existing CV
                            </a>
                        </div>

                        <div class="fn-preview-canvas">
                            <div class="fn-resume-paper">
                                <div class="fn-paper-head">
                                    <div class="fn-avatar">AM</div>
                                    <div>
                                        <strong>Amelia Morgan</strong>
                                        <span>Senior Product Designer &middot; London</span>
                                    </div>
                                </div>
                                <div class="fn-paper-grid">
                                    <div class="fn-paper-block">
                                        <b>Experience</b>
                                        <p>Lead Designer at Northstar &mdash; grew signup completion by 34% across web &amp; mobile.</p>
                                    </div>
                                    <div class="fn-paper-block">
                                        <b>Skills</b>
                                        <div class="fn-chip-list">
                                            <span>Figma</span>
                                            <span>Design Systems</span>
                                            <span>UX Research</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="fn-score-pill">
                                <b>92</b>
                                <div>
                                    <strong>ATS-ready layout!</strong>
                                    <span>15+ curated CV templates</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Invoice Preview --}}
                    <div x-show="activeProduct === 'invoice'" x-cloak x-transition.opacity.duration.150ms class="fn-invoice-preview">
                        <div class="fn-invoice-sheet">
                            <div class="fn-inv-top">
                                <div>
                                    <strong>Acme Studio</strong>
                                    <span>Invoice #FN-2048</span>
                                </div>
                                <div>
                                    <em>Due in 14 days</em>
                                    <span>Bill to: Oakwood Media</span>
                                </div>
                            </div>
                            <div class="fn-inv-rows">
                                <div>
                                    <span>Brand identity refresh</span>
                                    <strong>$2,400</strong>
                                </div>
                                <div>
                                    <span>Website design system</span>
                                    <strong>$1,850</strong>
                                </div>
                            </div>
                            <div class="fn-inv-total">
                                <span>Total due</span>
                                <strong>$4,250.00</strong>
                            </div>
                        </div>

                        <div class="fn-invoice-options">
                            <div class="fn-opt-card">
                                <span>Currency</span>
                                <strong>USD &middot; EUR &middot; GBP &middot; PKR</strong>
                            </div>
                            <div class="fn-opt-card">
                                <span>Brand theme</span>
                                <div class="fn-swatches">
                                    <i style="background: #184f3a"></i>
                                    <i style="background: #7655d7"></i>
                                    <i style="background: #ff815f"></i>
                                    <i style="background: #17211b"></i>
                                </div>
                            </div>
                            <a href="{{ route('templates.index') }}"
                               class="fn-opt-card hover:border-[#184f3a] transition block no-underline text-[#17211b]">
                                <span>20+ Presets</span>
                                <strong class="text-[#184f3a]">Browse Invoice Themes &rarr;</strong>
                            </a>
                        </div>
                    </div>

                    {{-- Tab 3: PDF Converter Preview --}}
                    <div x-show="activeProduct === 'pdf'" x-cloak x-transition.opacity.duration.150ms class="fn-converter-preview">
                        <a href="{{ route('convert.hub') }}" class="fn-drop-zone block no-underline text-[#17211b] hover:border-[#e47857] transition">
                            <div class="fn-drop-icon">
                                <x-icon name="upload" class="w-[22px] h-[22px]" />
                            </div>
                            <strong>Drop your file to convert</strong>
                            <span>PDF, Word, Excel, PowerPoint, JPG, PNG or HTML &bull; {{ count($converterAll) }} fast tools</span>
                        </a>
                        <div class="fn-file-row">
                            <div class="fn-file-meta">
                                <span class="fn-file-badge">PDF</span>
                                <div>
                                    <strong>project-proposal-final.pdf</strong>
                                    <span>1.8 MB &middot; Clean layout preserved</span>
                                </div>
                            </div>
                            <span class="fn-file-done">
                                <x-icon name="check-circle" class="w-4 h-4" />
                                Ready to download
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Trust & Capabilities Showcase (Structured metrics grid + refined context statement) --}}
        <div class="fn-trust-strip">
            <div class="fn-trust-grid">
                <div class="fn-trust-card">
                    <div class="fn-trust-icon bg-[#eaf7ee] text-[#184f3a]">
                        <x-icon name="sparkles" class="w-5 h-5" />
                    </div>
                    <div>
                        <strong>100% Free Forever</strong>
                        <span>No sign-up, no credit card &amp; zero watermarks on any document</span>
                    </div>
                </div>

                <div class="fn-trust-card">
                    <div class="fn-trust-icon bg-[#ede9fe] text-[#7655d7]">
                        <x-icon name="document-text" class="w-5 h-5" />
                    </div>
                    <div>
                        <strong>17+ ATS CV Templates</strong>
                        <span>Engineered for 98%+ pass rates on Taleo, Workday &amp; Greenhouse</span>
                    </div>
                </div>

                <div class="fn-trust-card">
                    <div class="fn-trust-icon bg-[#e0f2fe] text-[#0284c7]">
                        <x-icon name="receipt" class="w-5 h-5" />
                    </div>
                    <div>
                        <strong>24 Global Currencies</strong>
                        <span>Multi-currency invoices in USD, EUR, GBP, PKR, CAD &amp; AUD</span>
                    </div>
                </div>

                <div class="fn-trust-card">
                    <div class="fn-trust-icon bg-[#ffe4d6] text-[#e47857]">
                        <x-icon name="arrows-right-left" class="w-5 h-5" />
                    </div>
                    <div>
                        <strong>Lossless PDF Tools</strong>
                        <span>Convert Word to PDF &amp; PDF to DOCX preserving original layouts</span>
                    </div>
                </div>
            </div>

            <div class="fn-trust-summary">
                <div class="fn-summary-badge">
                    <span class="w-2 h-2 rounded-full bg-[#184f3a] animate-pulse"></span>
                    <span>All-in-One Professional Workspace</span>
                </div>
                <p>
                    Formnexa is a free online workspace for freelancers, small businesses, and job seekers. Create professional invoices, quotes, receipts, and packing slips in 24 currencies; start an ATS CV online or import an existing resume; and convert PDF files to and from Word DOCX without losing formatting, with zero watermarks and no sign-up required.
                </p>
            </div>
        </div>
    </section>

    {{-- 2. THREE POWERFUL TOOLS SECTION --}}
    <section class="fn-tools-section">
        <div class="fn-section-head">
            <span class="fn-section-tag">ONE WORKSPACE</span>
            <h2>Resume Builder, Invoice Maker and PDF Converter in one workspace.</h2>
            <p>Everything you need to apply for a job, bill a client cleanly, or get a file into the exact format you need.</p>
        </div>

        <div class="fn-tool-cards">
            {{-- Card 01: Resume Builder --}}
            <article class="fn-tool-card fn-card-violet">
                <div>
                    <span class="fn-card-index">01</span>
                    <h3>ATS Resume Builder</h3>
                    <p>Start a recruiter-approved ATS CV online in minutes or import an existing PDF/DOCX resume with smart section parsing.</p>
                    <a href="{{ route('resume.builder') }}" class="fn-card-link">
                        <span>Start ATS CV Online</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
                <div class="fn-card-art">
                    <div class="fn-resume-sheet">
                        <div class="fn-scan-beam" aria-hidden="true"></div>
                        <div class="fn-rm-header">
                            <div class="fn-rm-avatar">AM</div>
                            <div class="fn-rm-id">
                                <span class="fn-rm-name">Amelia Morgan</span>
                                <span class="fn-rm-role">Lead Product Designer</span>
                            </div>
                        </div>
                        <div class="fn-rm-section">
                            <div class="fn-rm-sec-title">EXPERIENCE</div>
                            <div class="fn-rm-job-head">
                                <strong>Staff Designer &bull; Stripe</strong>
                                <span>2022 &ndash; Pres.</span>
                            </div>
                            <div class="fn-rm-bullet">
                                <span class="fn-dot"></span>
                                <span class="fn-rm-line w-full"></span>
                            </div>
                            <div class="fn-rm-bullet">
                                <span class="fn-dot"></span>
                                <span class="fn-rm-line w-[75%]"></span>
                            </div>
                        </div>
                        <div class="fn-rm-tags">
                            <span class="fn-rm-tag">Figma</span>
                            <span class="fn-rm-tag">Design Systems</span>
                            <span class="fn-rm-tag">React</span>
                        </div>
                    </div>
                    <div class="fn-ats-badge">
                        <div class="fn-ats-circle">
                            <svg viewBox="0 0 36 36" class="fn-ats-ring">
                                <path class="fn-ats-ring-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <path class="fn-ats-ring-val" stroke-dasharray="98, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            </svg>
                            <span class="fn-ats-val">98%</span>
                        </div>
                        <div class="fn-ats-text">
                            <strong>ATS Match</strong>
                            <span>17+ Themes</span>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Card 02: Invoice Maker --}}
            <article class="fn-tool-card fn-card-mint">
                <div>
                    <span class="fn-card-index">02</span>
                    <h3>Invoice &amp; Doc Maker</h3>
                    <p>Create branded invoices, quotes, receipts, packing slips &amp; 12 business documents with live taxes, currencies, and shareable links.</p>
                    <a href="{{ route('invoice.studio') }}" class="fn-card-link">
                        <span>Create Free Invoice</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
                <div class="fn-card-art">
                    <div class="fn-invoice-sheet">
                        <div class="fn-im-header">
                            <div class="fn-im-brand">
                                <div class="fn-im-logo">SA</div>
                                <div>
                                    <span class="fn-im-company">Studio Apex Ltd.</span>
                                    <span class="fn-im-num">#INV-2026-084</span>
                                </div>
                            </div>
                            <div class="fn-im-date">Nov 16, 2026</div>
                        </div>
                        <div class="fn-im-table">
                            <div class="fn-im-row">
                                <span class="fn-im-desc">UI Design System &amp; Tokens</span>
                                <span class="fn-im-qty">1 &times;</span>
                                <strong class="fn-im-amt">$2,400.00</strong>
                            </div>
                            <div class="fn-im-row">
                                <span class="fn-im-desc">API Backend Integration</span>
                                <span class="fn-im-qty">1 &times;</span>
                                <strong class="fn-im-amt">$1,850.00</strong>
                            </div>
                        </div>
                        <div class="fn-im-footer">
                            <div class="fn-im-total">
                                <span>Total Due:</span>
                                <strong>$4,250.00</strong>
                            </div>
                        </div>
                    </div>
                    <div class="fn-paid-stamp-v2">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>PAID IN FULL</span>
                    </div>
                </div>
            </article>

            {{-- Card 03: PDF Converter --}}
            <article class="fn-tool-card fn-card-orange">
                <div>
                    <span class="fn-card-index">03</span>
                    <h3>PDF &amp; Word Converter</h3>
                    <p>Convert PDF to DOCX without losing formatting, turn Word into crisp PDFs, or export JPG, Excel &amp; PowerPoint seamlessly.</p>
                    <a href="{{ route('convert.hub') }}" class="fn-card-link">
                        <span>Explore {{ count($converterAll) }} Converters</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
                <div class="fn-card-art">
                    <div class="fn-converter-stage">
                        <div class="fn-conv-card fn-conv-docx">
                            <span class="fn-conv-badge">DOCX</span>
                            <div class="fn-conv-icon docx-color">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                                </svg>
                            </div>
                            <div class="fn-conv-meta">
                                <span class="fn-conv-name">Report.docx</span>
                                <span class="fn-conv-size">1.4 MB</span>
                            </div>
                        </div>
                        <div class="fn-conv-pipeline">
                            <div class="fn-pipeline-track">
                                <div class="fn-pipeline-beam"></div>
                            </div>
                            <div class="fn-pipeline-action">
                                <svg class="w-4 h-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="fn-pipeline-status">0.4s &bull; Lossless</span>
                        </div>
                        <div class="fn-conv-card fn-conv-pdf">
                            <div class="fn-conv-check">
                                <svg class="w-2.5 h-2.5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="fn-conv-badge pdf-badge">PDF</span>
                            <div class="fn-conv-icon pdf-color">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-9.5 8.5h-2v1h2a1 1 0 0 0 0-2zm5 2h-1.5v-3h1.5a1.5 1.5 0 0 1 0 3zm3.5-3.5h-2.5v4H17v-1.5h-1v-1h1v-1.5z"/>
                                </svg>
                            </div>
                            <div class="fn-conv-meta">
                                <span class="fn-conv-name">Report.pdf</span>
                                <span class="fn-conv-size">Ready to share</span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        {{-- Compact Quick Directory for All 12 Generators, 10 Converters & Galleries --}}
        <div class="mt-14 bg-[#f8f8f3] border border-[#dfe4df] rounded-[26px] p-5 sm:p-7">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-[#dfe4df]">
                <div>
                    <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">Complete Suite Directory</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-[#17211b] mt-0.5 tracking-tight">All 12 document generators and 10 PDF converters</h2>
                </div>
                <div class="inline-flex flex-wrap gap-1.5 bg-white p-1 rounded-xl border border-[#dfe4df]">
                    <button type="button" @click="dirTab = 'docs'"
                            :class="dirTab === 'docs' ? 'bg-[#17211b] text-white' : 'text-[#536058] hover:text-[#17211b]'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                        12 Business Docs
                    </button>
                    <button type="button" @click="dirTab = 'conv'"
                            :class="dirTab === 'conv' ? 'bg-[#17211b] text-white' : 'text-[#536058] hover:text-[#17211b]'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                        {{ count($converterAll) }} PDF Converters
                    </button>
                    <button type="button" @click="dirTab = 'templates'"
                            :class="dirTab === 'templates' ? 'bg-[#17211b] text-white' : 'text-[#536058] hover:text-[#17211b]'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                        Template Galleries
                    </button>
                </div>
            </div>

            {{-- Directory Tab: 12 Business Document Generators --}}
            <div x-show="dirTab === 'docs'" class="pt-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2.5">
                @foreach($generators as $slug => $meta)
                    <a href="{{ $slug === 'invoice-generator' ? route('invoice.studio') : route('invoice.generator', $slug) }}"
                       class="group flex items-center justify-between gap-2 px-3.5 py-3 rounded-xl bg-white border border-[#dfe4df] hover:border-[#184f3a] hover:shadow-sm transition">
                        <span class="text-xs sm:text-[13px] font-semibold text-[#17211b] group-hover:text-[#184f3a] truncate">
                            {{ $meta['name'] ?? ucwords(str_replace('-', ' ', $slug)) }}
                        </span>
                        <span class="text-xs text-[#68736d] group-hover:text-[#184f3a] shrink-0">&rarr;</span>
                    </a>
                @endforeach
            </div>

            {{-- Directory Tab: PDF Converters --}}
            <div x-show="dirTab === 'conv'" x-cloak class="pt-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5">
                @foreach($converterAll as $cTool)
                    <a href="{{ route('convert.tool', $cTool['slug']) }}"
                       class="group flex items-center gap-2.5 px-3 py-3 rounded-xl bg-white border border-[#dfe4df] hover:border-[#184f3a] hover:shadow-sm transition">
                        <span class="w-7 h-7 rounded-md {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-mono font-bold text-[10px] shrink-0">
                            {{ $cTool['badge'] }}
                        </span>
                        <span class="text-xs font-semibold text-[#17211b] group-hover:text-[#184f3a] truncate">{{ $cTool['name'] }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Directory Tab: Template Galleries --}}
            <div x-show="dirTab === 'templates'" x-cloak class="pt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('resume.templates') }}"
                   class="group flex items-center justify-between gap-4 p-4 rounded-2xl bg-white border border-[#dfe4df] hover:border-[#7655d7] transition">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <span class="w-11 h-11 rounded-xl bg-[#f0ebff] text-[#6142be] flex items-center justify-center font-display font-extrabold text-sm shrink-0">17+</span>
                        <div class="min-w-0">
                            <div class="text-sm font-bold text-[#17211b] group-hover:text-[#6142be] truncate">Resume Templates Gallery</div>
                            <div class="text-xs text-[#68736d] truncate">Minimal, Editorial, Technical &amp; ATS CV layouts</div>
                        </div>
                    </div>
                    <x-icon name="arrow-right" class="w-[18px] h-[18px] text-[#68736d] group-hover:text-[#6142be]" />
                </a>
                <a href="{{ route('templates.index') }}"
                   class="group flex items-center justify-between gap-4 p-4 rounded-2xl bg-white border border-[#dfe4df] hover:border-[#184f3a] transition">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <span class="w-11 h-11 rounded-xl bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center font-display font-extrabold text-sm shrink-0">24+</span>
                        <div class="min-w-0">
                            <div class="text-sm font-bold text-[#17211b] group-hover:text-[#184f3a] truncate">Invoice &amp; Business Presets</div>
                            <div class="text-xs text-[#68736d] truncate">Modern presets with custom branding swatches</div>
                        </div>
                    </div>
                    <x-icon name="arrow-right" class="w-[18px] h-[18px] text-[#68736d] group-hover:text-[#184f3a]" />
                </a>
            </div>
        </div>
    </section>

    {{-- 3. DESIGNED FOR YOU (FEATURE SHOWCASE) --}}
    <section class="fn-feature-section" id="features">
        <div class="fn-feature-bg-grid" aria-hidden="true"></div>
        <div class="fn-feature-inner">
            <div class="fn-feature-visual" aria-hidden="true">
                <div class="fn-visual-blob"></div>
                <div class="fn-template-window">
                    <div class="fn-tw-head">
                        <div class="fn-tw-dots">
                            <i></i><i></i><i></i>
                        </div>
                        <span class="fn-tw-title">formnexa-templates-preview.pdf</span>
                        <span class="fn-tw-zoom">100% &bull; Vector</span>
                    </div>
                    <div class="fn-template-grid">
                        {{-- Mini Template 1: Modern Tech CV (Alex Vance) --}}
                        <div class="fn-mini-template fn-tpl-cv">
                            <div class="fn-doc-head">
                                <div class="fn-doc-avatar bg-purple-100 text-[#7655d7]">AV</div>
                                <div class="min-w-0">
                                    <span class="fn-doc-name">Alex Vance</span>
                                    <span class="fn-doc-role text-purple-700">Senior Cloud Eng</span>
                                </div>
                            </div>
                            <div class="fn-doc-body">
                                <span class="fn-doc-sec">EXPERIENCE</span>
                                <p class="fn-doc-p">Lead Architect &bull; Vertex (2023–26)</p>
                                <p class="fn-doc-sub">Scaled clusters to 10M events/day</p>
                                <p class="fn-doc-sub text-emerald-700 font-semibold">&check; 99.99% Cluster Uptime</p>
                                <span class="fn-doc-sec" style="margin-top: 4px;">KEY SKILLS</span>
                                <div class="fn-doc-chips">
                                    <span>TypeScript</span>
                                    <span>Kubernetes</span>
                                    <span>Go</span>
                                    <span>PostgreSQL</span>
                                </div>
                            </div>
                            <div class="fn-doc-foot">
                                <span class="fn-ats-score">
                                    <svg class="w-2.5 h-2.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    98% ATS Pass
                                </span>
                                <span class="fn-mt-tag">Modern CV</span>
                            </div>
                        </div>

                        {{-- Mini Template 2: Studio Invoice (Active / Featured) --}}
                        <div class="fn-mini-template active fn-tpl-invoice">
                            <div class="fn-mt-check">
                                <svg class="w-2.5 h-2.5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="fn-doc-head">
                                <div class="fn-doc-avatar bg-[#c8f3dc] text-[#184f3a]">SA</div>
                                <div class="min-w-0">
                                    <span class="fn-doc-name">Studio Apex</span>
                                    <span class="fn-doc-role text-emerald-800">#INV-2026-084</span>
                                </div>
                            </div>
                            <div class="fn-doc-body">
                                <div class="fn-doc-client">
                                    <span>Billed: Acme Corp</span>
                                    <span>Net 14</span>
                                </div>
                                <div class="fn-doc-table">
                                    <div class="fn-doc-row">
                                        <span>UI Design System</span>
                                        <strong>$2,400</strong>
                                    </div>
                                    <div class="fn-doc-row">
                                        <span>Full-Stack App</span>
                                        <strong>$4,200</strong>
                                    </div>
                                    <div class="fn-doc-row text-[#5e6d64]">
                                        <span>Hosting &amp; SSL</span>
                                        <strong>$350</strong>
                                    </div>
                                </div>
                                <div class="fn-doc-total-bar">
                                    <span>Total Due</span>
                                    <strong>$6,600 USD</strong>
                                </div>
                            </div>
                            <div class="fn-doc-foot">
                                <span class="fn-paid-stamp">
                                    <svg class="w-2.5 h-2.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    PAID
                                </span>
                                <span class="fn-mt-tag">Studio Invoice</span>
                            </div>
                        </div>

                        {{-- Mini Template 3: Executive Quote / PO --}}
                        <div class="fn-mini-template alt fn-tpl-quote">
                            <div class="fn-doc-head">
                                <div class="fn-doc-avatar bg-[#ffe4d6] text-[#ff815f]">PO</div>
                                <div class="min-w-0">
                                    <span class="fn-doc-name">Creative Quote</span>
                                    <span class="fn-doc-role text-orange-700">Proposal #Q-104</span>
                                </div>
                            </div>
                            <div class="fn-doc-body">
                                <div class="fn-doc-client">
                                    <span>For: Global Logistics Inc.</span>
                                </div>
                                <div class="fn-doc-table">
                                    <div class="fn-doc-row">
                                        <span>Cloud Security Audit</span>
                                        <strong>$1,850</strong>
                                    </div>
                                    <div class="fn-doc-row">
                                        <span>Speed &amp; DB Tuning</span>
                                        <strong>$2,000</strong>
                                    </div>
                                </div>
                                <div class="fn-quote-est">
                                    <span>Estimated Cost</span>
                                    <strong>$3,850.00</strong>
                                </div>
                            </div>
                            <div class="fn-doc-foot">
                                <span class="fn-quote-valid">Valid 30 Days</span>
                                <span class="fn-mt-tag">Quote &amp; PO</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fn-customize-card">
                    <div class="fn-cust-top">
                        <div>
                            <strong>Brand theme &amp; style</strong>
                            <span class="block text-[11px] text-[#536058] font-medium">Applied instantly to all exports</span>
                        </div>
                        <span class="fn-live-pill">
                            <span class="fn-live-dot"></span>
                            <span>LIVE</span>
                        </span>
                    </div>
                    <div class="fn-swatch-row">
                        <i style="background: #184f3a" class="active" title="Forest Green"></i>
                        <i style="background: #7655d7" title="Electric Violet"></i>
                        <i style="background: #ff815f" title="Coral Flame"></i>
                        <i style="background: #17211b" title="Obsidian Noir"></i>
                    </div>
                    <div class="fn-cust-bottom">
                        <div class="fn-font-row">
                            <span>Font family</span>
                            <span class="fn-font-pill">Manrope &bull; Crisp</span>
                        </div>
                        <div class="fn-export-row">
                            <span>Format</span>
                            <span class="fn-export-pill">
                                <svg class="w-3 h-3 text-[#184f3a]" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Lossless Vector PDF
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fn-feature-copy">
                <span class="fn-section-tag">DESIGNED FOR YOU</span>
                <h2>Professional results. Zero design experience.</h2>
                <p>Clean templates handle margins, spacing, typography, and print alignment automatically &mdash; so every PDF you share looks polished right away.</p>

                <div class="fn-feature-points">
                    <div>
                        <span class="ic">
                            <x-icon name="sparkles" class="w-[19px] h-[19px]" />
                        </span>
                        <div>
                            <strong>Smart, balanced formatting</strong>
                            <span>Never fight broken page breaks or misaligned tables again.</span>
                        </div>
                    </div>
                    <div>
                        <span class="ic">
                            <x-icon name="layers" class="w-[19px] h-[19px]" />
                        </span>
                        <div>
                            <strong>One consistent style across documents</strong>
                            <span>Match colors and typography across resumes, invoices, quotes, and receipts.</span>
                        </div>
                    </div>
                    <div>
                        <span class="ic">
                            <x-icon name="shield-check" class="w-[19px] h-[19px]" />
                        </span>
                        <div>
                            <strong>Share securely or keep offline</strong>
                            <span>Export crisp watermark-free PDFs instantly, or share live links when needed.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. MADE FOR REAL MOMENTUM (PEOPLE & SOCIAL PROOF COLLAGE - FIGMA MAKE) --}}
    <section class="fn-people-section">
        <div class="fn-people-copy">
            <span class="fn-section-tag">MADE FOR REAL MOMENTUM</span>
            <h2>
                Great work deserves<br>
                to look the part.
            </h2>
            <p>
                From first job applications to fast-growing businesses, Formnexa helps ambitious people show up with confidence at every career and business milestone.
            </p>
            <div class="fn-impact-stats">
                <div>
                    <strong>50K+</strong>
                    <span>documents created</span>
                </div>
                <div>
                    <strong>4.9/5</strong>
                    <span>average user rating</span>
                </div>
                <div>
                    <strong>120+</strong>
                    <span>countries reached</span>
                </div>
            </div>
            <div class="pt-2">
                <a href="{{ route('invoice.studio') }}" class="fn-primary-btn inline-flex">
                    <span>Start creating</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>

        <div class="fn-people-collage" aria-label="People working with Formnexa">
            <figure class="fn-photo-main">
                <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1000&q=85"
                     alt="Creative team collaborating around a table"
                     loading="lazy"
                     decoding="async">
                <figcaption>
                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    <span>Built for teams and individuals</span>
                </figcaption>
            </figure>
            <figure class="fn-photo-detail">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=600&q=85"
                     alt="Professional smiling confidently"
                     loading="lazy"
                     decoding="async">
            </figure>
            <div class="fn-success-card">
                <span>
                    <x-icon name="sparkles" class="w-4 h-4 text-[#6142be]" />
                </span>
                <div>
                    <strong>You&rsquo;re ready to go</strong>
                    <small>Your document looks great</small>
                </div>
            </div>
            <div class="fn-time-card">
                <svg class="w-3.5 h-3.5 text-[#184f3a] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Average creation time: <strong>8 min</strong></span>
            </div>
        </div>
    </section>

    {{-- 5. THREE EASY STEPS --}}
    <section class="fn-steps-section" id="how">
        <div class="fn-section-head">
            <span class="fn-section-tag">SIMPLE BY DESIGN</span>
            <h2>From blank page to done in three easy steps.</h2>
        </div>

        <div class="fn-steps">
            <div class="fn-step-line" aria-hidden="true"></div>
            <article>
                <span>01</span>
                <h3>Pick a tool</h3>
                <p>Choose Resume Builder, Invoice Maker, any of the 12 business generators, or a PDF converter.</p>
            </article>
            <article>
                <span>02</span>
                <h3>Make it yours</h3>
                <p>Fill in your details, import an existing CV, or drop your file &mdash; live previews update as you type.</p>
            </article>
            <article>
                <span>03</span>
                <h3>Download &amp; share</h3>
                <p>Export a print-ready, watermark-free PDF or shareable link in seconds without friction.</p>
            </article>
        </div>
    </section>

    {{-- 6. TESTIMONIAL BAND (FIGMA MAKE WITH VERIFIED PORTRAIT) --}}
    <section class="fn-testimonial">
        <div class="fn-testimonial-inner">
            <div class="fn-testimonial-photo">
                <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=700&q=85"
                     alt="Sarah Khan, Marketing Specialist"
                     loading="lazy"
                     decoding="async">
                <span>
                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    <span>Verified user</span>
                </span>
            </div>
            <div class="fn-testimonial-copy">
                <div class="fn-quote-mark" aria-hidden="true">&ldquo;</div>
                <blockquote>
                    Formnexa cut the time I spend on client proposals, invoices, and CVs in half. Everything looks like I hired a designer &mdash; and I had it ready in under 15 minutes.
                </blockquote>
                <div class="fn-customer">
                    <div>
                        <strong>Sarah Khan</strong>
                        <span>Marketing Specialist</span>
                    </div>
                    <div class="fn-stars" aria-label="Rated 5 out of 5 stars">
                        @for($s = 0; $s < 5; $s++)
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. FAQ ACCORDION --}}
    <section class="fn-faq-section" id="faq">
        <div class="fn-faq-intro">
            <span class="fn-section-tag">GOOD TO KNOW</span>
            <h2>Frequently asked questions about Formnexa.</h2>
            <p>Everything you need to know before generating your first resume, invoice, or converted file.</p>
            <a href="{{ route('invoice.studio') }}" class="fn-text-link">
                <span>Jump into the workspace</span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="fn-faq-list">
            @foreach($faqItems as $idx => $item)
                <div class="fn-faq-item" :class="openFaq === {{ $idx }} ? 'open' : ''">
                    <button type="button"
                            @click="openFaq = openFaq === {{ $idx }} ? null : {{ $idx }}"
                            :aria-expanded="(openFaq === {{ $idx }}).toString()">
                        <span>{{ $item['q'] }}</span>
                        <span class="fn-faq-plus" aria-hidden="true">+</span>
                    </button>
                    <div class="fn-faq-answer" x-show="openFaq === {{ $idx }}" x-collapse.duration.200ms>
                        <p>{{ $item['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 6.5. LATEST GUIDES & BLOG ARTICLES --}}
    @if(isset($latestPosts) && $latestPosts->isNotEmpty())
        <section class="max-w-[1160px] mx-auto px-4 sm:px-6 pb-20">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <span class="fn-section-tag">GUIDES &amp; TEMPLATES BLOG</span>
                    <h2 class="font-display text-2xl sm:text-4xl font-extrabold text-[#17211b] tracking-tight mt-2">
                        Practical guides to help you get paid &amp; hired.
                    </h2>
                </div>
                <a href="{{ route('blog.index') }}" class="fn-text-link !mt-0 whitespace-nowrap">
                    <span>Read all guides</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($latestPosts as $bPost)
                    <article class="bg-[#f8f8f3] rounded-3xl border border-[#dfe4df] hover:border-[#184f3a] p-6 flex flex-col justify-between gap-4 transition">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-2 text-xs font-semibold">
                                <span class="px-2.5 py-1 rounded-full bg-white text-[#184f3a] border border-[#dfe4df]">{{ $bPost->category_label }}</span>
                                <span class="text-[#68736d]">{{ $bPost->reading_time_minutes }} min read</span>
                            </div>
                            <h3 class="font-display text-lg sm:text-xl font-extrabold text-[#17211b] leading-snug">
                                <a href="{{ route('blog.show', $bPost->slug) }}" class="hover:text-[#184f3a] transition">{{ $bPost->title }}</a>
                            </h3>
                            <p class="text-xs sm:text-sm text-[#536058] leading-relaxed">{{ \Illuminate\Support\Str::limit($bPost->excerpt, 125) }}</p>
                        </div>
                        <a href="{{ route('blog.show', $bPost->slug) }}" class="text-xs font-bold text-[#184f3a] hover:underline">
                            Read guide<span class="fn-sr-only">: {{ $bPost->title }}</span> &rarr;
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- 7. SOFT MINT CTA BANNER --}}
    <section class="fn-cta-section">
        <div class="fn-cta-orb fn-orb-a" aria-hidden="true"></div>
        <div class="fn-cta-orb fn-orb-b" aria-hidden="true"></div>
        <div class="fn-cta-inner">
            <div class="fn-cta-spark">
                <x-icon name="sparkles" class="w-6 h-6" />
            </div>
            <h2>Your next great document starts right here.</h2>
            <p>Try Formnexa free &mdash; build a resume, generate a branded invoice, or convert a PDF in seconds.</p>
            <div class="fn-hero-actions">
                <a href="{{ route('invoice.studio') }}" class="fn-primary-btn dark">
                    <span>Start creating &mdash; it&apos;s free</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ route('resume.builder') }}" class="fn-secondary-btn">
                    <span>Open Resume Builder</span>
                </a>
            </div>
        </div>
    </section>

</div>

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Organization',
            '@id' => url('/') . '#org',
            'name' => 'Formnexa',
            'url' => url('/'),
            'logo' => asset('images/formnexa-logo.png'),
        ],
        [
            '@type' => 'WebSite',
            '@id' => url('/') . '#website',
            'url' => url('/'),
            'name' => 'Formnexa',
            'publisher' => ['@id' => url('/') . '#org'],
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqItems)->map(fn ($i) => [
                '@type' => 'Question',
                'name' => $i['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $i['a']],
            ])->values()->all(),
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@endpush
@endsection
