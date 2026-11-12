<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth bg-[#F8FAFC]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="pdf-worker-url" content="{{ asset('vendor/pdfjs/pdf.worker.min.mjs') }}">
    <meta name="description" content="@yield('meta_description', 'Formnexa — Create professional invoices, build ATS-ready resumes, and convert PDFs cleanly in one free modern workspace. No sign-up required.')">

    <title>@yield('title', 'Formnexa — Invoices, Resumes & PDF Tools in One Modern Workspace')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/formnexa-icon-blue.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans, Inter, Playfair Display, JetBrains Mono & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v3 Play CDN + Stitch "Modern Electric Precision" Design Tokens -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'surface': '#f8f9ff',
                        'surface-bright': '#f8f9ff',
                        'surface-dim': '#cbdbf5',
                        'surface-container-lowest': '#ffffff',
                        'surface-container-low': '#eff4ff',
                        'surface-container': '#e5eeff',
                        'surface-container-high': '#dce9ff',
                        'surface-container-highest': '#d3e4fe',
                        'on-surface': '#0b1c30',
                        'on-surface-variant': '#424656',
                        'primary': '#0050cb',
                        'on-primary': '#ffffff',
                        'primary-container': '#0066ff',
                        'on-primary-container': '#f8f7ff',
                        'primary-fixed': '#dae1ff',
                        'secondary': '#565e74',
                        'secondary-container': '#dae2fd',
                        'outline': '#727687',
                        'outline-variant': '#c2c6d8',
                        'background': '#f8f9ff',
                        'on-background': '#0b1c30',
                        'electric-blue-hover': '#0052CC',
                        'electric-blue-subtle': '#EFF6FF',
                        'canvas-warm-tint': '#F8FAFC',
                        'badge-emerald': '#10B981',
                        'badge-rose': '#F43F5E',
                        'badge-amber': '#F59E0B',
                        'border-subtle': '#E2E8F0',
                        'border-strong': '#CBD5E1',
                        'card-dark-header': '#0B0F19',
                        'bloom-canvas': '#F8FAFC',
                        'bloom-ink': '#0b1c30',
                        'bloom-coral': '#F43F5E',
                        'formnexa-blue': '#0066FF',
                        'emerald-primary': '#0d9488',
                        'emerald-dark': '#0f766e',
                        'charcoal': '#0F172A',
                    },
                    fontFamily: {
                        'sans': ['Plus Jakarta Sans', 'Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                        'display': ['Plus Jakarta Sans', 'sans-serif'],
                        'body': ['Inter', 'sans-serif'],
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
        [x-cloak] {
            display: none !important;
        }
        html, body {
            overflow-x: clip;
            max-width: 100%;
        }
        body {
            font-family: 'Inter', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #F8FAFC;
            color: #0b1c30;
        }
        h1, h2, h3, h4, .font-display {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .font-sans-inter {
            font-family: 'Inter', sans-serif;
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
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-full bg-[#F8FAFC] text-[#0b1c30] antialiased flex flex-col selection:bg-[#0066FF] selection:text-white">

    @php
        $converterGroups = \App\Support\ConverterCatalog::grouped();
        $converterCount = count(\App\Support\ConverterCatalog::all());
    @endphp

    <!-- Top Navigation Bar (Stitch "Modern Electric Precision" Sticky Header) -->
    <header class="no-print sticky top-0 z-40 w-full bg-white/90 backdrop-blur-md border-b border-border-subtle shadow-sm transition-all duration-200"
            x-data="{ mobileMenu: false, mobileSection: 'studios' }">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-3">

            <!-- Brand Logo + Desktop Nav -->
            <div class="flex items-center gap-6 xl:gap-8 min-w-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0">
                    <div class="w-9 h-9 rounded-lg bg-primary-container flex items-center justify-center text-white shadow-sm shadow-[#0066FF]/25 transition-transform duration-200 group-hover:scale-105 overflow-hidden">
                        <img src="{{ asset('images/formnexa-icon-white.png') }}" alt="Formnexa" class="w-5 h-5 object-contain">
                    </div>
                    <span class="font-display font-extrabold text-[19px] sm:text-[20px] leading-none tracking-[-0.03em] text-[#0b1c30] whitespace-nowrap">
                        Formnexa
                    </span>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1 text-[13px] font-semibold">
                    <!-- 1. Invoice Generator -->
                    <a href="{{ route('invoice.studio') }}"
                       class="px-3 py-2 rounded-lg flex items-center gap-1.5 transition-all duration-150 whitespace-nowrap {{ request()->routeIs('invoice.studio') || request()->routeIs('invoice.generator') ? 'text-[#0050cb] bg-[#EFF6FF]' : 'text-[#0b1c30] hover:text-[#0050cb] hover:bg-[#EFF6FF]' }}">
                        <span>Invoice<span class="hidden lg:inline"> Generator</span></span>
                        <span class="material-symbols-outlined text-[16px] text-[#0066FF]">spark</span>
                    </a>

                    <!-- 2. Resume Builder Dropdown -->
                    <div class="relative" x-data="{ openResume: false }" @mouseenter="openResume = true" @mouseleave="openResume = false">
                        <button type="button" @click="openResume = !openResume" @click.outside="openResume = false"
                                class="px-3 py-2 rounded-lg flex items-center gap-1 transition-colors whitespace-nowrap cursor-pointer {{ request()->routeIs('resume.*') ? 'text-[#0050cb] bg-[#EFF6FF]' : 'text-[#565e74] hover:text-[#0b1c30] hover:bg-slate-100/70' }}">
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
                             class="absolute left-0 top-full pt-1.5 z-50 w-72">
                            <div class="bg-white rounded-xl shadow-xl border border-border-subtle p-2 space-y-1">
                                <a href="{{ route('resume.builder') }}" class="flex items-start gap-3 p-2.5 rounded-lg hover:bg-[#EFF6FF] transition group">
                                    <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/70 flex items-center justify-center shrink-0 font-mono font-bold text-[11px]">CV</span>
                                    <div>
                                        <div class="text-xs font-bold text-[#0b1c30] group-hover:text-[#0050cb]">Live Resume Studio</div>
                                        <div class="text-[11px] text-[#565e74] mt-0.5">FlowCV &amp; Figma-grade layouts</div>
                                    </div>
                                </a>
                                <a href="{{ route('resume.builder', ['import' => 1]) }}" class="flex items-start gap-3 p-2.5 rounded-lg hover:bg-[#EFF6FF] transition group">
                                    <span class="w-8 h-8 rounded-lg bg-[#EFF6FF] text-[#0066FF] border border-blue-200/70 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[17px]">upload_file</span>
                                    </span>
                                    <div>
                                        <div class="text-xs font-bold text-[#0b1c30] group-hover:text-[#0050cb]">Import Existing CV</div>
                                        <div class="text-[11px] text-[#565e74] mt-0.5">Upload PDF/DOCX or paste text</div>
                                    </div>
                                </a>
                                <a href="{{ route('resume.templates') }}" class="flex items-start gap-3 p-2.5 rounded-lg hover:bg-[#EFF6FF] transition group border-t border-border-subtle pt-2.5">
                                    <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 border border-amber-200/70 flex items-center justify-center shrink-0 font-mono font-bold text-[11px]">15+</span>
                                    <div>
                                        <div class="text-xs font-bold text-[#0b1c30] group-hover:text-[#0050cb]">Resume Templates Gallery</div>
                                        <div class="text-[11px] text-[#565e74] mt-0.5">Editorial serif &amp; ATS themes</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 3. PDF Converter Two-Column Mega Dropdown -->
                    <div class="relative" x-data="{ openConv: false }" @mouseenter="openConv = true" @mouseleave="openConv = false">
                        <button type="button" @click="openConv = !openConv" @click.outside="openConv = false"
                                class="px-3 py-2 rounded-lg flex items-center gap-1 transition-colors whitespace-nowrap cursor-pointer {{ request()->routeIs('convert.*') ? 'text-[#0050cb] bg-[#EFF6FF]' : 'text-[#565e74] hover:text-[#0b1c30] hover:bg-slate-100/70' }}">
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
                             class="absolute left-1/2 -translate-x-1/2 top-full pt-1.5 z-50 w-[490px] max-w-[calc(100vw-2rem)]">
                            <div class="bg-white rounded-xl shadow-xl border border-border-subtle p-4 text-xs">
                                <div class="grid grid-cols-2 gap-5">
                                    <div>
                                        <div class="text-[10px] font-mono font-semibold uppercase tracking-wider text-[#727687] px-2 mb-2">Convert To PDF</div>
                                        <div class="space-y-1">
                                            @foreach($converterGroups['to_pdf'] as $cTool)
                                                <a href="{{ route('convert.tool', $cTool['slug']) }}"
                                                   class="flex items-center gap-2.5 p-2 rounded-lg hover:bg-[#EFF6FF] transition group">
                                                    <span class="w-7 h-7 rounded-md {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-mono font-bold text-[10px] shrink-0">
                                                        {{ $cTool['badge'] }}
                                                    </span>
                                                    <span class="font-semibold text-[#0b1c30] group-hover:text-[#0050cb] truncate">{{ $cTool['name'] }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-mono font-semibold uppercase tracking-wider text-[#727687] px-2 mb-2">Convert From PDF</div>
                                        <div class="space-y-1">
                                            @foreach($converterGroups['from_pdf'] as $cTool)
                                                <a href="{{ route('convert.tool', $cTool['slug']) }}"
                                                   class="flex items-center gap-2.5 p-2 rounded-lg hover:bg-[#EFF6FF] transition group">
                                                    <span class="w-7 h-7 rounded-md {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-mono font-bold text-[10px] shrink-0">
                                                        {{ $cTool['badge'] }}
                                                    </span>
                                                    <span class="font-semibold text-[#0b1c30] group-hover:text-[#0050cb] truncate">{{ $cTool['name'] }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-border-subtle flex items-center justify-between px-2">
                                    <span class="text-[11px] text-[#565e74]">Free &bull; Instant download</span>
                                    <a href="{{ route('convert.hub') }}" class="text-[11px] font-bold text-[#0066FF] hover:underline">All {{ $converterCount }} PDF Converters &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Templates & 12 Document Tools Mega Dropdown -->
                    <div class="relative" x-data="{ openTools: false }" @mouseenter="openTools = true" @mouseleave="openTools = false">
                        <button type="button" @click="openTools = !openTools" @click.outside="openTools = false"
                                class="px-3 py-2 rounded-lg flex items-center gap-1 transition-colors whitespace-nowrap cursor-pointer {{ request()->routeIs('templates.*') ? 'text-[#0050cb] bg-[#EFF6FF]' : 'text-[#565e74] hover:text-[#0b1c30] hover:bg-slate-100/70' }}">
                            <span>Templates<span class="hidden lg:inline"> &amp; Tools</span></span>
                            <span class="material-symbols-outlined text-[17px] transition-transform duration-150" :class="openTools ? 'rotate-180' : ''">expand_more</span>
                        </button>

                        <div x-show="openTools" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1.5"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1.5"
                             class="absolute right-0 xl:left-1/2 xl:-translate-x-1/2 top-full pt-1.5 z-50 w-[560px] max-w-[calc(100vw-2rem)]">
                            <div class="bg-white rounded-xl shadow-xl border border-border-subtle p-4 text-xs space-y-3">
                                <div class="grid grid-cols-2 gap-2.5 pb-3 border-b border-border-subtle">
                                    <a href="{{ route('templates.index') }}" class="flex items-center gap-3 p-2.5 rounded-lg bg-[#F8FAFC] hover:bg-[#EFF6FF] transition group">
                                        <span class="w-9 h-9 rounded-lg bg-[#EFF6FF] text-[#0066FF] border border-blue-200/70 flex items-center justify-center shrink-0 font-mono text-xs font-extrabold">20+</span>
                                        <div>
                                            <div class="font-bold text-[#0b1c30] group-hover:text-[#0050cb]">Invoice Templates</div>
                                            <div class="text-[11px] text-[#565e74]">6 layouts &amp; industry styles</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('resume.templates') }}" class="flex items-center gap-3 p-2.5 rounded-lg bg-[#F8FAFC] hover:bg-[#EFF6FF] transition group">
                                        <span class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/70 flex items-center justify-center shrink-0 font-mono text-xs font-extrabold">15+</span>
                                        <div>
                                            <div class="font-bold text-[#0b1c30] group-hover:text-emerald-700">Resume Templates</div>
                                            <div class="text-[11px] text-[#565e74]">Figma, FlowCV &amp; ATS designs</div>
                                        </div>
                                    </a>
                                </div>

                                <div>
                                    <div class="text-[10px] font-mono font-semibold uppercase tracking-wider text-[#727687] px-2 mb-2">12 Billing &amp; Business Document Generators</div>
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
                                               class="px-2.5 py-1.5 rounded-lg hover:bg-[#EFF6FF] transition flex items-center justify-between group">
                                                <span class="font-semibold text-[#0b1c30] group-hover:text-[#0050cb] truncate">{{ $label }}</span>
                                                <span class="text-[11px] text-[#727687] group-hover:text-[#0066FF]">&rarr;</span>
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
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                @yield('header_actions')

                @guest
                    <div class="hidden sm:flex items-center gap-2 shrink-0">
                        <a href="{{ route('login') }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-semibold text-[#0b1c30] hover:text-[#0050cb] hover:bg-[#EFF6FF] transition whitespace-nowrap">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 rounded-lg text-xs font-bold text-white bg-[#0B0F19] hover:bg-[#0066FF] transition shadow-sm whitespace-nowrap">
                            Sign Up<span class="hidden lg:inline"> Free</span>
                        </a>
                    </div>
                @endguest

                @auth
                    <div class="hidden sm:block relative pl-2 border-l border-border-subtle shrink-0" x-data="{ userMenu: false }">
                        <button type="button" @click="userMenu = !userMenu" @click.outside="userMenu = false"
                                class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white hover:bg-[#F8FAFC] border border-border-subtle text-xs font-semibold text-[#0b1c30] transition whitespace-nowrap cursor-pointer">
                            <span class="w-6 h-6 rounded-md bg-[#0066FF] text-white flex items-center justify-center text-[11px] font-extrabold uppercase shrink-0">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span class="hidden lg:inline max-w-[110px] truncate">{{ Auth::user()->name }}</span>
                            <span class="material-symbols-outlined text-[16px] text-[#727687]">expand_more</span>
                        </button>

                        <div x-show="userMenu" x-cloak x-transition class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-border-subtle p-2 z-50 text-xs">
                            <div class="px-3 py-2 border-b border-border-subtle mb-1">
                                <div class="font-bold text-[#0b1c30] truncate">{{ Auth::user()->name }}</div>
                                <div class="text-[11px] text-[#565e74] truncate">{{ Auth::user()->email }}</div>
                            </div>
                            <a href="{{ route('invoice.history.page') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#EFF6FF] text-[#0b1c30] hover:text-[#0050cb] font-semibold transition">
                                My Document History
                            </a>
                            <a href="{{ route('invoice.studio') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#EFF6FF] text-[#0b1c30] hover:text-[#0050cb] font-semibold transition">
                                Invoice Generator
                            </a>
                            <a href="{{ route('resume.builder') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#EFF6FF] text-[#0b1c30] hover:text-[#0050cb] font-semibold transition">
                                Resume Builder
                            </a>
                            <a href="{{ route('convert.hub') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#EFF6FF] text-[#0b1c30] hover:text-[#0050cb] font-semibold transition">
                                PDF Converters
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-1 pt-1 border-t border-border-subtle">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-rose-50 text-rose-600 font-semibold transition text-left">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                <!-- Mobile Menu Toggle Button -->
                <button type="button" @click="mobileMenu = !mobileMenu" aria-label="Toggle Menu" :aria-expanded="mobileMenu.toString()"
                        class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-border-strong text-[#0b1c30] hover:bg-[#EFF6FF] transition shrink-0">
                    <span x-show="!mobileMenu" class="material-symbols-outlined text-[22px]">menu</span>
                    <span x-show="mobileMenu" x-cloak class="material-symbols-outlined text-[22px]">close</span>
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
             class="md:hidden absolute left-0 right-0 top-full bg-white border-b border-border-subtle shadow-2xl z-40 max-h-[85vh] overflow-y-auto">
            <div class="px-4 py-4 space-y-4">

                <!-- Primary 3 Studios Quick Links -->
                <div class="space-y-2">
                    <div class="text-[10px] font-mono font-semibold uppercase tracking-wider text-[#727687] px-1">Core Workspaces</div>
                    <div class="grid grid-cols-1 gap-2">
                        <a href="{{ route('invoice.studio') }}" @click="mobileMenu = false"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#EFF6FF] border border-blue-200/60 text-[#0050cb] font-bold text-xs">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                                <span>Invoice &amp; 12 Document Studio</span>
                            </span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                        <a href="{{ route('resume.builder') }}" @click="mobileMenu = false"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-border-subtle text-[#0b1c30] font-bold text-xs">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-emerald-600">badge</span>
                                <span>Resume Builder &amp; CV Importer</span>
                            </span>
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">arrow_forward</span>
                        </a>
                        <a href="{{ route('convert.hub') }}" @click="mobileMenu = false"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-border-subtle text-[#0b1c30] font-bold text-xs">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-[#F43F5E]">picture_as_pdf</span>
                                <span>{{ $converterCount }} PDF Converter Tools</span>
                            </span>
                            <span class="material-symbols-outlined text-[16px] text-[#F43F5E]">arrow_forward</span>
                        </a>
                    </div>
                </div>

                <!-- Template Galleries -->
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('templates.index') }}" @click="mobileMenu = false"
                       class="flex items-center justify-between gap-2 px-3 py-2.5 rounded-xl bg-white border border-border-subtle text-[#0b1c30] font-bold text-xs">
                        <span>Invoice Themes</span>
                        <span class="font-mono text-[10px] font-bold text-[#0066FF]">20+</span>
                    </a>
                    <a href="{{ route('resume.templates') }}" @click="mobileMenu = false"
                       class="flex items-center justify-between gap-2 px-3 py-2.5 rounded-xl bg-white border border-border-subtle text-[#0b1c30] font-bold text-xs">
                        <span>CV Themes</span>
                        <span class="font-mono text-[10px] font-bold text-emerald-600">15+</span>
                    </a>
                </div>

                <!-- Collapsible Converters Section -->
                <div class="border border-border-subtle rounded-xl overflow-hidden">
                    <button type="button" @click="mobileSection = (mobileSection === 'converters' ? '' : 'converters')"
                            class="w-full px-3.5 py-2.5 bg-[#F8FAFC] flex items-center justify-between text-xs font-bold text-[#0b1c30]">
                        <span>PDF Converters ({{ $converterCount }})</span>
                        <span class="material-symbols-outlined text-[18px] text-[#727687] transition-transform" :class="mobileSection === 'converters' ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="mobileSection === 'converters'" class="p-2.5 grid grid-cols-2 gap-1.5 bg-white">
                        @foreach(\App\Support\ConverterCatalog::all() as $cTool)
                            <a href="{{ route('convert.tool', $cTool['slug']) }}" @click="mobileMenu = false"
                               class="flex items-center gap-2 px-2.5 py-2 rounded-lg bg-[#F8FAFC] hover:bg-[#EFF6FF] text-[#0b1c30] font-semibold text-[11px]">
                                <span class="w-6 h-6 rounded {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-mono font-bold text-[9px] shrink-0">{{ $cTool['badge'] }}</span>
                                <span class="truncate">{{ $cTool['name'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Collapsible 12 Document Generators Section -->
                <div class="border border-border-subtle rounded-xl overflow-hidden">
                    <button type="button" @click="mobileSection = (mobileSection === 'generators' ? '' : 'generators')"
                            class="w-full px-3.5 py-2.5 bg-[#F8FAFC] flex items-center justify-between text-xs font-bold text-[#0b1c30]">
                        <span>12 Document Generators</span>
                        <span class="material-symbols-outlined text-[18px] text-[#727687] transition-transform" :class="mobileSection === 'generators' ? 'rotate-180' : ''">expand_more</span>
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
                               class="px-3 py-2 rounded-lg bg-[#F8FAFC] hover:bg-[#EFF6FF] text-[#0b1c30] font-semibold text-xs truncate">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="pt-3 border-t border-border-subtle">
                    @guest
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('login') }}" @click="mobileMenu = false"
                               class="text-center py-2.5 rounded-lg border border-border-strong text-[#0b1c30] font-bold text-xs">
                                Login
                            </a>
                            <a href="{{ route('register') }}" @click="mobileMenu = false"
                               class="text-center py-2.5 rounded-lg bg-[#0066FF] text-white font-bold text-xs shadow-sm">
                                Sign Up Free
                            </a>
                        </div>
                    @endguest

                    @auth
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('invoice.history.page') }}" @click="mobileMenu = false"
                               class="flex items-center justify-center py-2.5 rounded-lg bg-[#EFF6FF] text-[#0050cb] font-bold text-xs">
                                Document History
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full py-2.5 rounded-lg bg-rose-50 text-rose-600 font-bold text-xs">
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

    <!-- Comprehensive Responsive Footer (Stitch "Modern Electric Precision") -->
    <footer class="no-print w-full bg-white border-t border-border-subtle">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 mb-10">
                <!-- Brand Column -->
                <div class="sm:col-span-2 lg:col-span-2 space-y-3">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group">
                        <div class="w-8 h-8 rounded-lg bg-primary-container flex items-center justify-center text-white overflow-hidden">
                            <img src="{{ asset('images/formnexa-icon-white.png') }}" alt="Formnexa" class="w-4 h-4 object-contain">
                        </div>
                        <span class="font-display font-bold text-lg text-[#0b1c30]">Formnexa</span>
                    </a>
                    <p class="text-[13px] text-[#565e74] max-w-sm leading-relaxed">
                        One fast modern workspace for billing documents, designer resumes, and high-precision PDF conversions. Generate, customize, and download in seconds &mdash; 100% free with no account required.
                    </p>
                    <div class="flex flex-wrap items-center gap-2 text-xs font-mono text-[#727687] pt-1">
                        <span class="px-2.5 py-0.5 rounded bg-[#dce9ff]/60 text-[#0b1c30]">No Sign-up Required</span>
                        <span class="px-2.5 py-0.5 rounded bg-[#dce9ff]/60 text-[#0b1c30]">Zero Watermarks</span>
                    </div>
                </div>

                <!-- Column 1: Invoices & Documents -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#0b1c30] mb-3">Invoices &amp; Documents</h4>
                    <ul class="space-y-2 text-[13px] text-[#565e74]">
                        <li><a href="{{ route('invoice.studio') }}" class="hover:text-[#0050cb] transition-colors">Invoice Generator</a></li>
                        <li><a href="{{ route('invoice.generator', 'receipt-generator') }}" class="hover:text-[#0050cb] transition-colors">Receipt Generator</a></li>
                        <li><a href="{{ route('invoice.generator', 'quote-generator') }}" class="hover:text-[#0050cb] transition-colors">Quote &amp; Estimate Maker</a></li>
                        <li><a href="{{ route('invoice.generator', 'purchase-order-generator') }}" class="hover:text-[#0050cb] transition-colors">Purchase &amp; Sales Orders</a></li>
                        <li><a href="{{ route('invoice.generator', 'credit-note-generator') }}" class="hover:text-[#0050cb] transition-colors">Credit Notes &amp; Statements</a></li>
                        <li><a href="{{ route('invoice.generator', 'timesheet-generator') }}" class="hover:text-[#0050cb] transition-colors">Timesheets &amp; Work Orders</a></li>
                        <li><a href="{{ route('templates.index') }}" class="text-[#0050cb] font-semibold hover:underline">20+ Invoice Templates &rarr;</a></li>
                    </ul>
                </div>

                <!-- Column 2: Resume Studio -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#0b1c30] mb-3">Resume Studio</h4>
                    <ul class="space-y-2 text-[13px] text-[#565e74]">
                        <li><a href="{{ route('resume.builder') }}" class="hover:text-[#0050cb] transition-colors">Free Resume Builder</a></li>
                        <li><a href="{{ route('resume.builder', ['import' => 1]) }}" class="hover:text-[#0050cb] transition-colors">Import Existing CV</a></li>
                        <li><a href="{{ route('resume.templates') }}" class="hover:text-[#0050cb] transition-colors">Figma &amp; FlowCV Themes</a></li>
                        <li><a href="{{ route('resume.templates') }}" class="hover:text-[#0050cb] transition-colors">ATS Classic Templates</a></li>
                        <li><a href="{{ route('resume.builder', ['blank' => 1]) }}" class="hover:text-[#0050cb] transition-colors">Blank A4 CV Canvas</a></li>
                    </ul>
                </div>

                <!-- Column 3: PDF Converters -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#0b1c30] mb-3">PDF Converters</h4>
                    <ul class="space-y-2 text-[13px] text-[#565e74]">
                        <li><a href="{{ route('convert.tool', 'jpg-to-pdf') }}" class="hover:text-[#0050cb] transition-colors">JPG to PDF</a></li>
                        <li><a href="{{ route('convert.tool', 'pdf-to-word') }}" class="hover:text-[#0050cb] transition-colors">PDF to Word</a></li>
                        <li><a href="{{ route('convert.tool', 'word-to-pdf') }}" class="hover:text-[#0050cb] transition-colors">Word to PDF</a></li>
                        <li><a href="{{ route('convert.tool', 'excel-to-pdf') }}" class="hover:text-[#0050cb] transition-colors">Excel to PDF</a></li>
                        <li><a href="{{ route('convert.tool', 'pdf-to-excel') }}" class="hover:text-[#0050cb] transition-colors">PDF to Excel</a></li>
                        <li><a href="{{ route('convert.tool', 'pdf-to-pdfa') }}" class="hover:text-[#0050cb] transition-colors">PDF to PDF/A</a></li>
                        <li><a href="{{ route('convert.hub') }}" class="text-[#0050cb] font-semibold hover:underline">Explore Converter Hub &rarr;</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Meta & Legal Bar -->
            <div class="pt-6 border-t border-border-subtle flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-[#565e74]">
                <div>
                    &copy; {{ date('Y') }} Formnexa. All-in-one document suite.
                </div>
                <nav class="flex flex-wrap justify-center items-center gap-x-5 gap-y-2 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-[#0050cb] transition-colors">Home</a>
                    <a href="{{ route('invoice.studio') }}" class="hover:text-[#0050cb] transition-colors">Invoices</a>
                    <a href="{{ route('resume.builder') }}" class="hover:text-[#0050cb] transition-colors">Resumes</a>
                    <a href="{{ route('convert.hub') }}" class="hover:text-[#0050cb] transition-colors">Converters</a>
                    <a href="{{ route('privacy') }}" class="hover:text-[#0050cb] transition-colors">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="hover:text-[#0050cb] transition-colors">Terms of Service</a>
                </nav>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
