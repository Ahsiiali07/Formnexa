<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth bg-[#ffffff]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="pdf-worker-url" content="{{ asset('vendor/pdfjs/pdf.worker.min.mjs') }}">
    <meta name="description" content="@yield('meta_description', 'Formnexa — Create professional resumes, build billing invoices, and convert PDFs cleanly in one fast workspace. Free to start, no credit card required.')">

    <title>@yield('title', 'Formnexa — Invoices, Resumes & PDF Tools in One Workspace')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/formnexa-icon-blue.png') }}">

    <!-- Google Fonts: DM Sans, Manrope (Figma Make Design System), Playfair Display, JetBrains Mono & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v3 Play CDN + FormNexa Figma Design Tokens -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'ink': '#17211b',
                        'muted': '#68736d',
                        'green-brand': '#184f3a',
                        'mint': '#c8f3dc',
                        'mint-soft': '#e8f7ed',
                        'lime-brand': '#d9f99d',
                        'cream': '#f8f8f3',
                        'line': '#dfe4df',
                        'purple-brand': '#7655d7',
                        'coral-brand': '#ff815f',
                        'footer-dark': '#121d17',

                        // Semantic aliases so studio, converter, gallery & auth pages stay cohesive
                        'surface': '#fbfbf7',
                        'surface-bright': '#ffffff',
                        'surface-dim': '#e2ebe5',
                        'surface-container-lowest': '#ffffff',
                        'surface-container-low': '#f8f8f3',
                        'surface-container': '#eef4ef',
                        'surface-container-high': '#e8f7ed',
                        'surface-container-highest': '#d4eedc',
                        'on-surface': '#17211b',
                        'on-surface-variant': '#536058',
                        'primary': '#184f3a',
                        'on-primary': '#ffffff',
                        'primary-container': '#184f3a',
                        'on-primary-container': '#ffffff',
                        'primary-fixed': '#e8f7ed',
                        'secondary': '#536058',
                        'secondary-container': '#e8f7ed',
                        'outline': '#68736d',
                        'outline-variant': '#dfe4df',
                        'background': '#ffffff',
                        'on-background': '#17211b',
                        'electric-blue-hover': '#123e2d',
                        'electric-blue-subtle': '#e8f7ed',
                        'canvas-warm-tint': '#f8f8f3',
                        'badge-emerald': '#1f6a4d',
                        'badge-rose': '#e47857',
                        'badge-amber': '#d97706',
                        'border-subtle': '#dfe4df',
                        'border-strong': '#c6d1ca',
                        'card-dark-header': '#121d17',
                        'bloom-canvas': '#f8f8f3',
                        'bloom-ink': '#17211b',
                        'bloom-coral': '#ff815f',
                        'formnexa-blue': '#184f3a',
                        'emerald-primary': '#184f3a',
                        'emerald-dark': '#123e2d',
                        'charcoal': '#17211b',
                    },
                    fontFamily: {
                        'sans': ['DM Sans', 'Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
                        'display': ['Manrope', 'DM Sans', 'sans-serif'],
                        'body': ['DM Sans', 'Inter', 'sans-serif'],
                        'mono': ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- Axios & Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        :root {
            --ink: #17211b;
            --muted: #68736d;
            --green: #184f3a;
            --mint: #c8f3dc;
            --lime: #d9f99d;
            --cream: #f8f8f3;
            --line: #dfe4df;
            --purple: #7655d7;
            --coral: #ff815f;
        }
        [x-cloak] {
            display: none !important;
        }
        html, body {
            overflow-x: clip;
            max-width: 100%;
        }
        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #ffffff;
            color: #17211b;
        }
        h1, h2, h3, h4, .font-display {
            font-family: 'Manrope', 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .font-sans-inter {
            font-family: 'DM Sans', 'Inter', sans-serif;
        }
        .font-editorial {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .font-tech-mono, .font-label-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 450, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* FormNexa Figma Brand Mark (3 tilted ascending bars) */
        .fn-brand-mark {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: var(--green);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 2.5px;
            position: relative;
            flex-shrink: 0;
        }
        .fn-brand-mark i {
            display: block;
            width: 3.5px;
            height: 15px;
            border-radius: 99px;
            background: #ffffff;
            font-style: normal;
        }
        .fn-brand-mark i.b2 {
            height: 11px;
            background: var(--mint);
        }
        .fn-brand-mark i.b3 {
            height: 16px;
            transform: rotate(-16deg);
        }
        .fn-brand-mark.dark {
            background: #235d46;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f2;
        }
        ::-webkit-scrollbar-thumb {
            background: #c5d0c9;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a39a;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-full bg-white text-[#17211b] antialiased flex flex-col selection:bg-[#184f3a] selection:text-white">

    @php
        $converterGroups = \App\Support\ConverterCatalog::grouped();
        $converterCount = count(\App\Support\ConverterCatalog::all());
    @endphp

    <!-- Sticky Navigation Bar (Figma Make FormNexa Aesthetic) -->
    <header class="no-print sticky top-0 z-40 w-full bg-white/95 backdrop-blur-md border-b border-[#ecefe9] transition-all duration-200"
            x-data="{ mobileMenu: false, mobileSection: '' }">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 h-[74px] flex items-center justify-between gap-3">

            <!-- Brand Logo + Desktop Nav -->
            <div class="flex items-center gap-6 xl:gap-9 min-w-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0">
                    <span class="fn-brand-mark transition-transform duration-200 group-hover:scale-105" aria-hidden="true">
                        <i class="b1"></i><i class="b2"></i><i class="b3"></i>
                    </span>
                    <span class="font-display font-extrabold text-[20px] leading-none tracking-[-0.04em] text-[#17211b] whitespace-nowrap">
                        form<span class="text-[#377d60]">nexa</span>
                    </span>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1 lg:gap-1.5 text-[14px] font-medium">
                    <!-- 1. Invoice Generator -->
                    <a href="{{ route('invoice.studio') }}"
                       class="px-3 py-2 rounded-lg flex items-center gap-1.5 transition-all duration-150 whitespace-nowrap {{ request()->routeIs('invoice.studio') || request()->routeIs('invoice.generator') ? 'text-[#184f3a] bg-[#e8f7ed] font-semibold' : 'text-[#3f4a43] hover:text-[#17211b] hover:bg-[#f2f6f3]' }}">
                        <span>Invoice<span class="hidden lg:inline"> Maker</span></span>
                    </a>

                    <!-- 2. Resume Builder Dropdown -->
                    <div class="relative" x-data="{ openResume: false }" @mouseenter="openResume = true" @mouseleave="openResume = false">
                        <button type="button" @click="openResume = !openResume" @click.outside="openResume = false"
                                class="px-3 py-2 rounded-lg flex items-center gap-1 transition-colors whitespace-nowrap cursor-pointer {{ request()->routeIs('resume.*') ? 'text-[#184f3a] bg-[#e8f7ed] font-semibold' : 'text-[#3f4a43] hover:text-[#17211b] hover:bg-[#f2f6f3]' }}">
                            <span>Resume<span class="hidden lg:inline"> Builder</span></span>
                            <span class="material-symbols-outlined text-[17px] transition-transform duration-150" :class="openResume ? 'rotate-180' : ''">expand_more</span>
                        </button>

                        <div x-show="openResume" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1.5"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1.5"
                             class="absolute left-0 top-full pt-2 z-50 w-72">
                            <div class="bg-white rounded-2xl shadow-xl shadow-[#17211b]/8 border border-[#dfe4df] p-2 space-y-1">
                                <a href="{{ route('resume.builder') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#f0ebff]/60 transition group">
                                    <span class="w-8 h-8 rounded-lg bg-[#f0ebff] text-[#6142be] border border-[#ddd2fb] flex items-center justify-center shrink-0 font-mono font-bold text-[11px]">CV</span>
                                    <div>
                                        <div class="text-xs font-bold text-[#17211b] group-hover:text-[#6142be]">Live Resume Studio</div>
                                        <div class="text-[11px] text-[#68736d] mt-0.5">ATS-ready &amp; modern layouts</div>
                                    </div>
                                </a>
                                <a href="{{ route('resume.builder', ['import' => 1]) }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#e8f7ed]/70 transition group">
                                    <span class="w-8 h-8 rounded-lg bg-[#e8f7ed] text-[#184f3a] border border-[#c8f3dc] flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[17px]">upload_file</span>
                                    </span>
                                    <div>
                                        <div class="text-xs font-bold text-[#17211b] group-hover:text-[#184f3a]">Import Existing CV</div>
                                        <div class="text-[11px] text-[#68736d] mt-0.5">Upload PDF/DOCX or paste text</div>
                                    </div>
                                </a>
                                <a href="{{ route('resume.templates') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#f8f8f3] transition group border-t border-[#dfe4df] pt-2.5">
                                    <span class="w-8 h-8 rounded-lg bg-[#fff0e8] text-[#b45309] border border-[#fed7c3] flex items-center justify-center shrink-0 font-mono font-bold text-[11px]">15+</span>
                                    <div>
                                        <div class="text-xs font-bold text-[#17211b] group-hover:text-[#184f3a]">Resume Templates Gallery</div>
                                        <div class="text-[11px] text-[#68736d] mt-0.5">Editorial serif &amp; ATS themes</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 3. PDF Converter Two-Column Mega Dropdown -->
                    <div class="relative" x-data="{ openConv: false }" @mouseenter="openConv = true" @mouseleave="openConv = false">
                        <button type="button" @click="openConv = !openConv" @click.outside="openConv = false"
                                class="px-3 py-2 rounded-lg flex items-center gap-1 transition-colors whitespace-nowrap cursor-pointer {{ request()->routeIs('convert.*') ? 'text-[#184f3a] bg-[#e8f7ed] font-semibold' : 'text-[#3f4a43] hover:text-[#17211b] hover:bg-[#f2f6f3]' }}">
                            <span>PDF Converter</span>
                            <span class="material-symbols-outlined text-[17px] transition-transform duration-150" :class="openConv ? 'rotate-180' : ''">expand_more</span>
                        </button>

                        <div x-show="openConv" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1.5"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1.5"
                             class="absolute left-1/2 -translate-x-1/2 top-full pt-2 z-50 w-[490px] max-w-[calc(100vw-2rem)]">
                            <div class="bg-white rounded-2xl shadow-xl shadow-[#17211b]/8 border border-[#dfe4df] p-4 text-xs">
                                <div class="grid grid-cols-2 gap-5">
                                    <div>
                                        <div class="text-[10px] font-mono font-semibold uppercase tracking-wider text-[#68736d] px-2 mb-2">Convert To PDF</div>
                                        <div class="space-y-1">
                                            @foreach($converterGroups['to_pdf'] as $cTool)
                                                <a href="{{ route('convert.tool', $cTool['slug']) }}"
                                                   class="flex items-center gap-2.5 p-2 rounded-lg hover:bg-[#f8f8f3] transition group">
                                                    <span class="w-7 h-7 rounded-md {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-mono font-bold text-[10px] shrink-0">
                                                        {{ $cTool['badge'] }}
                                                    </span>
                                                    <span class="font-semibold text-[#17211b] group-hover:text-[#184f3a] truncate">{{ $cTool['name'] }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-mono font-semibold uppercase tracking-wider text-[#68736d] px-2 mb-2">Convert From PDF</div>
                                        <div class="space-y-1">
                                            @foreach($converterGroups['from_pdf'] as $cTool)
                                                <a href="{{ route('convert.tool', $cTool['slug']) }}"
                                                   class="flex items-center gap-2.5 p-2 rounded-lg hover:bg-[#f8f8f3] transition group">
                                                    <span class="w-7 h-7 rounded-md {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-mono font-bold text-[10px] shrink-0">
                                                        {{ $cTool['badge'] }}
                                                    </span>
                                                    <span class="font-semibold text-[#17211b] group-hover:text-[#184f3a] truncate">{{ $cTool['name'] }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-[#dfe4df] flex items-center justify-between px-2">
                                    <span class="text-[11px] text-[#68736d]">Free &bull; Instant download</span>
                                    <a href="{{ route('convert.hub') }}" class="text-[11px] font-bold text-[#184f3a] hover:underline">All {{ $converterCount }} PDF Converters &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Templates & 12 Document Tools Mega Dropdown -->
                    <div class="relative" x-data="{ openTools: false }" @mouseenter="openTools = true" @mouseleave="openTools = false">
                        <button type="button" @click="openTools = !openTools" @click.outside="openTools = false"
                                class="px-3 py-2 rounded-lg flex items-center gap-1 transition-colors whitespace-nowrap cursor-pointer {{ request()->routeIs('templates.*') ? 'text-[#184f3a] bg-[#e8f7ed] font-semibold' : 'text-[#3f4a43] hover:text-[#17211b] hover:bg-[#f2f6f3]' }}">
                            <span>Templates<span class="hidden lg:inline"> &amp; Docs</span></span>
                            <span class="material-symbols-outlined text-[17px] transition-transform duration-150" :class="openTools ? 'rotate-180' : ''">expand_more</span>
                        </button>

                        <div x-show="openTools" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1.5"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1.5"
                             class="absolute right-0 xl:left-1/2 xl:-translate-x-1/2 top-full pt-2 z-50 w-[560px] max-w-[calc(100vw-2rem)]">
                            <div class="bg-white rounded-2xl shadow-xl shadow-[#17211b]/8 border border-[#dfe4df] p-4 text-xs space-y-3">
                                <div class="grid grid-cols-2 gap-2.5 pb-3 border-b border-[#dfe4df]">
                                    <a href="{{ route('templates.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl bg-[#f8f8f3] hover:bg-[#e8f7ed] transition group">
                                        <span class="w-9 h-9 rounded-lg bg-[#e8f7ed] text-[#184f3a] border border-[#c8f3dc] flex items-center justify-center shrink-0 font-mono text-xs font-extrabold">20+</span>
                                        <div>
                                            <div class="font-bold text-[#17211b] group-hover:text-[#184f3a]">Invoice Templates</div>
                                            <div class="text-[11px] text-[#68736d]">6 layouts &amp; industry styles</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('resume.templates') }}" class="flex items-center gap-3 p-2.5 rounded-xl bg-[#f8f8f3] hover:bg-[#f0ebff] transition group">
                                        <span class="w-9 h-9 rounded-lg bg-[#f0ebff] text-[#6142be] border border-[#ddd2fb] flex items-center justify-center shrink-0 font-mono text-xs font-extrabold">15+</span>
                                        <div>
                                            <div class="font-bold text-[#17211b] group-hover:text-[#6142be]">Resume Templates</div>
                                            <div class="text-[11px] text-[#68736d]">Editorial, modern &amp; ATS designs</div>
                                        </div>
                                    </a>
                                </div>

                                <div>
                                    <div class="text-[10px] font-mono font-semibold uppercase tracking-wider text-[#68736d] px-2 mb-2">12 Billing &amp; Business Document Generators</div>
                                    <div class="grid grid-cols-2 gap-1">
                                        @foreach([
                                            ['invoice-generator', 'Invoice Generator'],
                                            ['receipt-generator', 'Receipt Generator'],
                                            ['quote-generator', 'Quote Generator'],
                                            ['estimate-generator', 'Estimate Generator'],
                                            ['credit-note-generator', 'Credit Note Generator'],
                                            ['purchase-order-generator', 'Purchase Order'],
                                            ['sales-order-generator', 'Sales Order'],
                                            ['proforma-invoice-generator', 'Proforma Invoice'],
                                            ['timesheet-generator', 'Timesheet Generator'],
                                            ['work-order-generator', 'Work Order'],
                                            ['account-statement-generator', 'Account Statement'],
                                            ['packing-slip-generator', 'Packing Slip'],
                                        ] as [$slug, $label])
                                            <a href="{{ $slug === 'invoice-generator' ? route('invoice.studio') : route('invoice.generator', $slug) }}"
                                               class="px-2.5 py-1.5 rounded-lg hover:bg-[#e8f7ed]/70 transition flex items-center justify-between group">
                                                <span class="font-semibold text-[#17211b] group-hover:text-[#184f3a] truncate">{{ $label }}</span>
                                                <span class="text-[11px] text-[#68736d] group-hover:text-[#184f3a]">&rarr;</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Right Header Actions & Auth -->
            <div class="flex items-center gap-2 sm:gap-2.5 shrink-0">
                @yield('header_actions')

                @guest
                    <div class="hidden sm:flex items-center gap-2 shrink-0">
                        <a href="{{ route('login') }}"
                           class="px-3.5 py-2 rounded-lg text-[14px] font-medium text-[#17211b] hover:bg-[#f2f6f3] transition whitespace-nowrap">
                            Log in
                        </a>
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-[10px] text-[14px] font-semibold text-white bg-[#17211b] hover:bg-[#184f3a] transition shadow-sm whitespace-nowrap">
                            <span>Get started</span>
                            <span class="text-xs leading-none">&rarr;</span>
                        </a>
                    </div>
                @endguest

                @auth
                    <div class="hidden sm:block relative pl-2 border-l border-[#dfe4df] shrink-0" x-data="{ userMenu: false }">
                        <button type="button" @click="userMenu = !userMenu" @click.outside="userMenu = false"
                                class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-[#f8f8f3] hover:bg-[#e8f7ed] border border-[#dfe4df] text-xs font-semibold text-[#17211b] transition whitespace-nowrap cursor-pointer">
                            <span class="w-6 h-6 rounded-md bg-[#184f3a] text-white flex items-center justify-center text-[11px] font-extrabold uppercase shrink-0">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span class="hidden lg:inline max-w-[110px] truncate">{{ Auth::user()->name }}</span>
                            <span class="material-symbols-outlined text-[16px] text-[#68736d]">expand_more</span>
                        </button>

                        <div x-show="userMenu" x-cloak x-transition class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-xl shadow-[#17211b]/8 border border-[#dfe4df] p-2 z-50 text-xs">
                            <div class="px-3 py-2 border-b border-[#dfe4df] mb-1">
                                <div class="font-bold text-[#17211b] truncate">{{ Auth::user()->name }}</div>
                                <div class="text-[11px] text-[#68736d] truncate">{{ Auth::user()->email }}</div>
                            </div>
                            <a href="{{ route('invoice.history.page') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#e8f7ed] text-[#17211b] hover:text-[#184f3a] font-semibold transition">
                                My Document History
                            </a>
                            <a href="{{ route('invoice.studio') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#e8f7ed] text-[#17211b] hover:text-[#184f3a] font-semibold transition">
                                Invoice Maker
                            </a>
                            <a href="{{ route('resume.builder') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#e8f7ed] text-[#17211b] hover:text-[#184f3a] font-semibold transition">
                                Resume Builder
                            </a>
                            <a href="{{ route('convert.hub') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#e8f7ed] text-[#17211b] hover:text-[#184f3a] font-semibold transition">
                                PDF Converters
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-1 pt-1 border-t border-[#dfe4df]">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-rose-50 text-rose-600 font-semibold transition text-left">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                <!-- Mobile Menu Toggle Button -->
                <button type="button" @click="mobileMenu = !mobileMenu" aria-label="Toggle menu" :aria-expanded="mobileMenu.toString()"
                        class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-[9px] bg-white border border-[#dfe4df] text-[#17211b] hover:bg-[#f8f8f3] transition shrink-0">
                    <span x-show="!mobileMenu" class="material-symbols-outlined text-[21px]">menu</span>
                    <span x-show="mobileMenu" x-cloak class="material-symbols-outlined text-[21px]">close</span>
                </button>
            </div>
        </div>

        <!-- Mobile Slide-Down Drawer Navigation -->
        <div x-show="mobileMenu" x-cloak
             @click.outside="mobileMenu = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden absolute left-0 right-0 top-full bg-white border-b border-[#dfe4df] shadow-2xl z-40 max-h-[85vh] overflow-y-auto">
            <div class="px-4 py-4 space-y-4">

                <!-- Primary 3 Workspaces Quick Links -->
                <div class="space-y-2">
                    <div class="text-[10px] font-mono font-semibold uppercase tracking-wider text-[#68736d] px-1">One Workspace</div>
                    <div class="grid grid-cols-1 gap-2">
                        <a href="{{ route('resume.builder') }}" @click="mobileMenu = false"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#f0ebff] border border-[#e3d9ff] text-[#17211b] font-bold text-xs">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-[#6142be]">badge</span>
                                <span>Resume Builder &amp; CV Importer</span>
                            </span>
                            <span class="material-symbols-outlined text-[16px] text-[#6142be]">arrow_forward</span>
                        </a>
                        <a href="{{ route('invoice.studio') }}" @click="mobileMenu = false"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#e8f7ed] border border-[#c8f3dc] text-[#17211b] font-bold text-xs">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-[#184f3a]">receipt_long</span>
                                <span>Invoice Maker &amp; 12 Document Tools</span>
                            </span>
                            <span class="material-symbols-outlined text-[16px] text-[#184f3a]">arrow_forward</span>
                        </a>
                        <a href="{{ route('convert.hub') }}" @click="mobileMenu = false"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#fff0e8] border border-[#fedac7] text-[#17211b] font-bold text-xs">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-[#b45309]">picture_as_pdf</span>
                                <span>{{ $converterCount }} PDF Converter Tools</span>
                            </span>
                            <span class="material-symbols-outlined text-[16px] text-[#b45309]">arrow_forward</span>
                        </a>
                    </div>
                </div>

                <!-- Template Galleries -->
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('templates.index') }}" @click="mobileMenu = false"
                       class="flex items-center justify-between gap-2 px-3 py-2.5 rounded-xl bg-[#f8f8f3] border border-[#dfe4df] text-[#17211b] font-bold text-xs">
                        <span>Invoice Themes</span>
                        <span class="font-mono text-[10px] font-bold text-[#184f3a]">20+</span>
                    </a>
                    <a href="{{ route('resume.templates') }}" @click="mobileMenu = false"
                       class="flex items-center justify-between gap-2 px-3 py-2.5 rounded-xl bg-[#f8f8f3] border border-[#dfe4df] text-[#17211b] font-bold text-xs">
                        <span>CV Themes</span>
                        <span class="font-mono text-[10px] font-bold text-[#6142be]">15+</span>
                    </a>
                </div>

                <!-- Collapsible Converters Section -->
                <div class="border border-[#dfe4df] rounded-xl overflow-hidden">
                    <button type="button" @click="mobileSection = (mobileSection === 'converters' ? '' : 'converters')"
                            class="w-full px-3.5 py-2.5 bg-[#f8f8f3] flex items-center justify-between text-xs font-bold text-[#17211b]">
                        <span>PDF Converters ({{ $converterCount }})</span>
                        <span class="material-symbols-outlined text-[18px] text-[#68736d] transition-transform" :class="mobileSection === 'converters' ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="mobileSection === 'converters'" class="p-2.5 grid grid-cols-2 gap-1.5 bg-white">
                        @foreach(\App\Support\ConverterCatalog::all() as $cTool)
                            <a href="{{ route('convert.tool', $cTool['slug']) }}" @click="mobileMenu = false"
                               class="flex items-center gap-2 px-2.5 py-2 rounded-lg bg-[#f8f8f3] hover:bg-[#e8f7ed] text-[#17211b] font-semibold text-[11px]">
                                <span class="w-6 h-6 rounded {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-mono font-bold text-[9px] shrink-0">{{ $cTool['badge'] }}</span>
                                <span class="truncate">{{ $cTool['name'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Collapsible 12 Document Generators Section -->
                <div class="border border-[#dfe4df] rounded-xl overflow-hidden">
                    <button type="button" @click="mobileSection = (mobileSection === 'generators' ? '' : 'generators')"
                            class="w-full px-3.5 py-2.5 bg-[#f8f8f3] flex items-center justify-between text-xs font-bold text-[#17211b]">
                        <span>12 Document Generators</span>
                        <span class="material-symbols-outlined text-[18px] text-[#68736d] transition-transform" :class="mobileSection === 'generators' ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="mobileSection === 'generators'" class="p-2.5 grid grid-cols-2 gap-1.5 bg-white">
                        @foreach([
                            ['invoice-generator', 'Invoice'],
                            ['receipt-generator', 'Receipt'],
                            ['quote-generator', 'Quote'],
                            ['estimate-generator', 'Estimate'],
                            ['credit-note-generator', 'Credit Note'],
                            ['purchase-order-generator', 'Purchase Order'],
                            ['sales-order-generator', 'Sales Order'],
                            ['proforma-invoice-generator', 'Proforma'],
                            ['timesheet-generator', 'Timesheet'],
                            ['work-order-generator', 'Work Order'],
                            ['account-statement-generator', 'Statement'],
                            ['packing-slip-generator', 'Packing Slip'],
                        ] as [$slug, $label])
                            <a href="{{ $slug === 'invoice-generator' ? route('invoice.studio') : route('invoice.generator', $slug) }}" @click="mobileMenu = false"
                               class="px-3 py-2 rounded-lg bg-[#f8f8f3] hover:bg-[#e8f7ed] text-[#17211b] font-semibold text-xs truncate">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="pt-3 border-t border-[#dfe4df]">
                    @guest
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('login') }}" @click="mobileMenu = false"
                               class="text-center py-2.5 rounded-[10px] border border-[#c6d1ca] text-[#17211b] font-bold text-xs">
                                Log in
                            </a>
                            <a href="{{ route('register') }}" @click="mobileMenu = false"
                               class="text-center py-2.5 rounded-[10px] bg-[#17211b] text-white font-bold text-xs shadow-sm">
                                Get started &rarr;
                            </a>
                        </div>
                    @endguest

                    @auth
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('invoice.history.page') }}" @click="mobileMenu = false"
                               class="flex items-center justify-center py-2.5 rounded-[10px] bg-[#e8f7ed] text-[#184f3a] font-bold text-xs">
                                Document History
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full py-2.5 rounded-[10px] bg-rose-50 text-rose-600 font-bold text-xs">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 w-full">
        @yield('content')
    </main>

    <!-- Dark Forest Footer (Figma Make FormNexa Aesthetic) -->
    <footer class="no-print w-full bg-[#121d17] text-[#a7bab0]">
        <div class="max-w-[1200px] mx-auto px-5 sm:px-6 pt-16 pb-8">
            <div class="grid grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr_1fr] gap-8 sm:gap-10 pb-12 border-b border-white/[0.08]">
                <!-- Brand Column -->
                <div class="col-span-2 lg:col-span-1 space-y-3.5">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group">
                        <span class="fn-brand-mark dark" aria-hidden="true">
                            <i class="b1"></i><i class="b2"></i><i class="b3"></i>
                        </span>
                        <span class="font-display font-extrabold text-[20px] leading-none tracking-[-0.04em] text-[#d4f1df]">
                            form<span class="text-[#6bb68f]">nexa</span>
                        </span>
                    </a>
                    <p class="text-[14px] text-[#9eb2a7] max-w-[280px] leading-[1.6]">
                        Simple tools for creating resumes, generating invoices, and converting files &mdash; built so you can focus on what matters.
                    </p>
                    <div class="flex flex-wrap items-center gap-2 text-[11px] font-mono pt-1">
                        <span class="px-2.5 py-1 rounded-full bg-white/[0.06] text-[#c8f3dc] border border-white/[0.08]">No Sign-up Required</span>
                        <span class="px-2.5 py-1 rounded-full bg-white/[0.06] text-[#c8f3dc] border border-white/[0.08]">Zero Watermarks</span>
                    </div>
                </div>

                <!-- Column 1: Products -->
                <div>
                    <h4 class="text-[14px] font-semibold text-white mb-4">Products</h4>
                    <ul class="space-y-2.5 text-[14px]">
                        <li><a href="{{ route('resume.builder') }}" class="hover:text-white transition-colors">Resume Builder</a></li>
                        <li><a href="{{ route('resume.builder', ['import' => 1]) }}" class="hover:text-white transition-colors">Smart CV Importer</a></li>
                        <li><a href="{{ route('invoice.studio') }}" class="hover:text-white transition-colors">Invoice Maker</a></li>
                        <li><a href="{{ route('convert.hub') }}" class="hover:text-white transition-colors">PDF Converter</a></li>
                        <li><a href="{{ route('resume.templates') }}" class="hover:text-white transition-colors">Resume Templates</a></li>
                        <li><a href="{{ route('templates.index') }}" class="hover:text-white transition-colors">Invoice Templates</a></li>
                    </ul>
                </div>

                <!-- Column 2: Document & PDF Tools -->
                <div>
                    <h4 class="text-[14px] font-semibold text-white mb-4">Popular Tools</h4>
                    <ul class="space-y-2.5 text-[14px]">
                        <li><a href="{{ route('invoice.generator', 'receipt-generator') }}" class="hover:text-white transition-colors">Receipt Generator</a></li>
                        <li><a href="{{ route('invoice.generator', 'quote-generator') }}" class="hover:text-white transition-colors">Quote &amp; Estimate Maker</a></li>
                        <li><a href="{{ route('invoice.generator', 'purchase-order-generator') }}" class="hover:text-white transition-colors">Purchase &amp; Sales Orders</a></li>
                        <li><a href="{{ route('convert.tool', 'pdf-to-word') }}" class="hover:text-white transition-colors">PDF to Word</a></li>
                        <li><a href="{{ route('convert.tool', 'jpg-to-pdf') }}" class="hover:text-white transition-colors">JPG to PDF</a></li>
                        <li><a href="{{ route('convert.tool', 'word-to-pdf') }}" class="hover:text-white transition-colors">Word to PDF</a></li>
                    </ul>
                </div>

                <!-- Column 3: Company -->
                <div>
                    <h4 class="text-[14px] font-semibold text-white mb-4">Company</h4>
                    <ul class="space-y-2.5 text-[14px]">
                        <li><a href="{{ route('home') }}#features" class="hover:text-white transition-colors">Why Formnexa</a></li>
                        <li><a href="{{ route('home') }}#how" class="hover:text-white transition-colors">How it works</a></li>
                        <li><a href="{{ route('home') }}#faq" class="hover:text-white transition-colors">Questions &amp; Answers</a></li>
                        @auth
                            <li><a href="{{ route('invoice.history.page') }}" class="hover:text-white transition-colors">Document History</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Log in</a></li>
                        @endauth
                        <li><a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Meta & Legal Bar -->
            <div class="pt-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-[13px] text-[#7c8f84]">
                <span>&copy; {{ date('Y') }} Formnexa. All rights reserved.</span>
                <span>Made for people who get things done.</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
