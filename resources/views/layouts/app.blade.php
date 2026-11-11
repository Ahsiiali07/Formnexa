<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F6F5F1]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="pdf-worker-url" content="{{ asset('vendor/pdfjs/pdf.worker.min.mjs') }}">
    <meta name="description" content="@yield('meta_description', 'Formnexa — Create professional invoices, build ATS-ready resumes, and convert PDFs cleanly in one free modern workspace. No sign-up required.')">

    <title>@yield('title', 'Formnexa — Invoices, Resumes & PDF Tools in One Modern Workspace')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/formnexa-icon-blue.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans, Inter, Playfair Display & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600;700&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS (Play CDN) & Theme Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bloom-canvas': '#F6F5F1',
                        'bloom-ink': '#111111',
                        'bloom-coral': '#FF325A',
                        'formnexa-blue': '#0066FF',
                        'emerald-primary': '#0d9488',
                        'emerald-dark': '#0f766e',
                        'charcoal': '#0f172a',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js & Axios (formerly bundled via Vite) -->
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
        html {
            scroll-behavior: smooth;
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
            }
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #F6F5F1;
            color: #111111;
        }
        .font-sans-inter {
            font-family: 'Inter', sans-serif;
        }
        .font-editorial {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .font-tech-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-[#F6F5F1] text-[#111111] antialiased flex flex-col selection:bg-[#0066FF] selection:text-white">

    @php
        $converterGroups = \App\Support\ConverterCatalog::grouped();
    @endphp

    <!-- Bloom-Inspired Clean Architectural Header -->
    <header class="no-print sticky top-0 z-40 bg-[#F6F5F1]/90 backdrop-blur-md border-b border-black/[0.06] transition-all duration-200" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[72px] sm:h-[78px] flex items-center justify-between gap-4">

            <!-- Brand Logo (Links to Unified Homepage) -->
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0">
                <img src="{{ asset('images/formnexa-mark.png') }}" alt="Formnexa" class="h-8 sm:h-9 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                <span class="font-extrabold text-[20px] sm:text-[22px] leading-none tracking-[-0.035em] text-[#111111] whitespace-nowrap">
                    Formnexa
                </span>
            </a>

            <!-- Desktop Typographic Navigation Links (Flat, Clean & Uncluttered like Bloom.io) -->
            <nav class="hidden md:flex items-center gap-5 xl:gap-7 text-[13.5px] xl:text-[14px] font-medium text-[#111111]">
                <!-- 1. Invoice Generator Direct Link with Sparkle (Bloom Signature Item) -->
                <a href="{{ route('invoice.studio') }}"
                   class="inline-flex items-center gap-1.5 py-2 transition whitespace-nowrap {{ request()->routeIs('invoice.studio') || request()->routeIs('invoice.generator') ? 'text-[#0066FF] font-semibold' : 'text-[#111111]/85 hover:text-[#111111]' }}">
                    <span>Invoice<span class="hidden xl:inline"> Generator</span></span>
                    <svg class="w-4 h-4 text-[#0066FF] shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 2L14.2 8.2L20.5 10.5L14.2 12.8L12 19L9.8 12.8L3.5 10.5L9.8 8.2L12 2Z" opacity="0.9"/>
                        <path d="M19 15L20 17.5L22.5 18.5L20 19.5L19 22L18 19.5L15.5 18.5L18 17.5L19 15Z" opacity="0.6"/>
                    </svg>
                </a>

                <!-- 2. Resume Builder Dropdown -->
                <div class="relative" x-data="{ openResume: false }" @mouseenter="openResume = true" @mouseleave="openResume = false">
                    <button type="button" @click="openResume = !openResume" @click.outside="openResume = false"
                            class="inline-flex items-center gap-1 py-2 transition whitespace-nowrap cursor-pointer {{ request()->routeIs('resume.*') ? 'text-[#0066FF] font-semibold' : 'text-[#111111]/85 hover:text-[#111111]' }}">
                        <span>Resume<span class="hidden xl:inline"> Builder</span></span>
                        <svg class="w-3.5 h-3.5 text-black/45 transition-transform duration-200" :class="openResume ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>

                    <div x-show="openResume" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1.5 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1.5 scale-95"
                         class="absolute left-0 top-full pt-2 z-50 w-72">
                        <div class="bg-white rounded-2xl shadow-[0_24px_60px_-15px_rgba(17,17,17,0.16)] border border-black/[0.08] p-2.5 text-xs space-y-1">
                            <a href="{{ route('resume.builder') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F6F5F1] transition group">
                                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/70 flex items-center justify-center shrink-0 font-extrabold text-[11px]">CV</span>
                                <div>
                                    <div class="font-bold text-[#111111] group-hover:text-[#0066FF]">Live Resume Studio</div>
                                    <div class="text-[11px] text-black/55 mt-0.5">Build &amp; customize FlowCV &amp; Figma layouts</div>
                                </div>
                            </a>
                            <a href="{{ route('resume.builder', ['import' => 1]) }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F6F5F1] transition group">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-[#0066FF] border border-blue-200/70 flex items-center justify-center shrink-0 font-bold text-sm">&#8679;</span>
                                <div>
                                    <div class="font-bold text-[#111111] group-hover:text-[#0066FF]">Import Existing CV</div>
                                    <div class="text-[11px] text-black/55 mt-0.5">Upload PDF/DOCX or paste raw CV text</div>
                                </div>
                            </a>
                            <a href="{{ route('resume.templates') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F6F5F1] transition group border-t border-black/[0.06] pt-2.5">
                                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 border border-amber-200/70 flex items-center justify-center shrink-0 font-extrabold text-[11px]">15+</span>
                                <div>
                                    <div class="font-bold text-[#111111] group-hover:text-[#0066FF]">Resume Templates Gallery</div>
                                    <div class="text-[11px] text-black/55 mt-0.5">Editorial serif, ATS &amp; creative themes</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 3. PDF Converter Two-Column Mega Dropdown (Convert TO PDF & Convert FROM PDF) -->
                <div class="relative" x-data="{ openConv: false }" @mouseenter="openConv = true" @mouseleave="openConv = false">
                    <button type="button" @click="openConv = !openConv" @click.outside="openConv = false"
                            class="inline-flex items-center gap-1 py-2 transition whitespace-nowrap cursor-pointer {{ request()->routeIs('convert.*') ? 'text-[#0066FF] font-semibold' : 'text-[#111111]/85 hover:text-[#111111]' }}">
                        <span>PDF Converter</span>
                        <svg class="w-3.5 h-3.5 text-black/45 transition-transform duration-200" :class="openConv ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>

                    <div x-show="openConv" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1.5 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1.5 scale-95"
                         class="absolute left-1/2 -translate-x-1/2 top-full pt-2 z-50 w-[490px] max-w-[calc(100vw-2rem)]">
                        <div class="bg-white rounded-2xl shadow-[0_24px_60px_-15px_rgba(17,17,17,0.16)] border border-black/[0.08] p-5 text-xs">
                            <div class="grid grid-cols-2 gap-6">
                                {{-- Left Col: CONVERT TO PDF --}}
                                <div>
                                    <div class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-black/40 px-2 mb-2.5">Convert To PDF</div>
                                    <div class="space-y-1">
                                        @foreach($converterGroups['to_pdf'] as $cTool)
                                            <a href="{{ route('convert.tool', $cTool['slug']) }}"
                                               class="flex items-center gap-2.5 p-2 rounded-xl hover:bg-[#F6F5F1] transition group">
                                                <span class="w-7 h-7 rounded-lg {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-black text-[10px] shrink-0">
                                                    {{ $cTool['badge'] }}
                                                </span>
                                                <span class="font-bold text-[#111111]/85 group-hover:text-[#111111] truncate">{{ $cTool['name'] }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Right Col: CONVERT FROM PDF --}}
                                <div>
                                    <div class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-black/40 px-2 mb-2.5">Convert From PDF</div>
                                    <div class="space-y-1">
                                        @foreach($converterGroups['from_pdf'] as $cTool)
                                            <a href="{{ route('convert.tool', $cTool['slug']) }}"
                                               class="flex items-center gap-2.5 p-2 rounded-xl hover:bg-[#F6F5F1] transition group">
                                                <span class="w-7 h-7 rounded-lg {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-black text-[10px] shrink-0">
                                                    {{ $cTool['badge'] }}
                                                </span>
                                                <span class="font-bold text-[#111111]/85 group-hover:text-[#111111] truncate">{{ $cTool['name'] }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3.5 pt-3 border-t border-black/[0.06] flex items-center justify-between px-2">
                                <span class="text-[11px] text-black/45">Free &bull; Instant download</span>
                                <a href="{{ route('convert.hub') }}" class="text-[11px] font-bold text-[#0066FF] hover:underline">All 10 PDF Converters &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Templates & 12 Document Tools Unified Mega Dropdown -->
                <div class="relative" x-data="{ openTools: false }" @mouseenter="openTools = true" @mouseleave="openTools = false">
                    <button type="button" @click="openTools = !openTools" @click.outside="openTools = false"
                            class="inline-flex items-center gap-1 py-2 transition whitespace-nowrap cursor-pointer {{ request()->routeIs('templates.*') ? 'text-[#0066FF] font-semibold' : 'text-[#111111]/85 hover:text-[#111111]' }}">
                        <span>Templates<span class="hidden xl:inline"> &amp; Tools</span></span>
                        <svg class="w-3.5 h-3.5 text-black/45 transition-transform duration-200" :class="openTools ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>

                    <div x-show="openTools" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1.5 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1.5 scale-95"
                         class="absolute right-0 xl:left-1/2 xl:-translate-x-1/2 top-full pt-2 z-50 w-[560px] max-w-[calc(100vw-2rem)]">
                        <div class="bg-white rounded-2xl shadow-[0_24px_60px_-15px_rgba(17,17,17,0.16)] border border-black/[0.08] p-4 text-xs space-y-3">
                            <!-- Top Featured Template Galleries -->
                            <div class="grid grid-cols-2 gap-2.5 pb-3 border-b border-black/[0.06]">
                                <a href="{{ route('templates.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl bg-[#F6F5F1]/80 hover:bg-[#F6F5F1] transition group">
                                    <span class="w-9 h-9 rounded-lg bg-blue-50 text-[#0066FF] border border-blue-200/70 flex items-center justify-center shrink-0 text-sm font-black">20+</span>
                                    <div>
                                        <div class="font-bold text-[#111111] group-hover:text-[#0066FF]">Invoice Templates</div>
                                        <div class="text-[11px] text-black/50">6 layouts &amp; industry styles</div>
                                    </div>
                                </a>
                                <a href="{{ route('resume.templates') }}" class="flex items-center gap-3 p-2.5 rounded-xl bg-[#F6F5F1]/80 hover:bg-[#F6F5F1] transition group">
                                    <span class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/70 flex items-center justify-center shrink-0 text-sm font-black">15+</span>
                                    <div>
                                        <div class="font-bold text-[#111111] group-hover:text-emerald-700">Resume Templates</div>
                                        <div class="text-[11px] text-black/50">Figma, FlowCV &amp; ATS designs</div>
                                    </div>
                                </a>
                            </div>

                            <!-- 12 Specialized Document Generators Grid -->
                            <div>
                                <div class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-black/40 px-2 mb-2">12 Billing &amp; Business Document Generators</div>
                                <div class="grid grid-cols-2 gap-1">
                                    @foreach([
                                        ['invoice-generator', 'Invoice Generator', 'Bill clients for work'],
                                        ['receipt-generator', 'Receipt Generator', 'Proof of payment receipts'],
                                        ['quote-generator', 'Quote Generator', 'Bids & service proposals'],
                                        ['estimate-generator', 'Estimate Generator', 'Cost breakdowns'],
                                        ['credit-note-generator', 'Credit Note Generator', 'Refunds & adjustments'],
                                        ['purchase-order-generator', 'Purchase Order', 'Vendor procurement orders'],
                                        ['sales-order-generator', 'Sales Order', 'Confirm order delivery'],
                                        ['proforma-invoice-generator', 'Proforma Invoice', 'Customs & pre-shipment'],
                                        ['timesheet-generator', 'Timesheet Generator', 'Hourly billing logs'],
                                        ['work-order-generator', 'Work Order', 'Maintenance & labor tasks'],
                                        ['account-statement-generator', 'Account Statement', 'Balance summaries'],
                                        ['packing-slip-generator', 'Packing Slip', 'Shipping item manifests'],
                                    ] as [$slug, $label, $desc])
                                        <a href="{{ $slug === 'invoice-generator' ? route('invoice.studio') : route('invoice.generator', $slug) }}"
                                           class="px-2.5 py-1.5 rounded-lg hover:bg-[#F6F5F1] transition flex items-center justify-between group">
                                            <span class="font-semibold text-[#111111]/85 group-hover:text-[#0066FF] truncate">{{ $label }}</span>
                                            <span class="text-[10px] text-black/35 group-hover:text-[#0066FF]">&rarr;</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Right Header Actions & Auth -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                @yield('header_actions')

                @guest
                    <div class="hidden sm:flex items-center gap-2 shrink-0">
                        <a href="{{ route('login') }}"
                           class="px-3 py-2 rounded-lg text-[13.5px] font-medium text-[#111111]/85 hover:text-[#111111] transition whitespace-nowrap">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 rounded-lg text-[13px] font-semibold text-white bg-[#111111] hover:bg-[#0066FF] transition shadow-[0_2px_8px_rgba(0,0,0,0.12)] whitespace-nowrap">
                            Sign Up<span class="hidden xl:inline"> Free</span>
                        </a>
                    </div>
                @endguest

                @auth
                    <div class="hidden sm:block relative pl-2 border-l border-black/[0.08] shrink-0" x-data="{ userMenu: false }">
                        <button type="button" @click="userMenu = !userMenu" @click.outside="userMenu = false"
                                class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white hover:bg-black/[0.03] border border-black/[0.08] text-xs font-semibold text-[#111111] transition whitespace-nowrap">
                            <span class="w-6 h-6 rounded-md bg-[#0066FF] text-white flex items-center justify-center text-[11px] font-extrabold uppercase shrink-0">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span class="hidden lg:inline max-w-[110px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-black/45 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>

                        <div x-show="userMenu" x-cloak x-transition class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-[0_24px_60px_-15px_rgba(17,17,17,0.16)] border border-black/[0.08] p-2 z-50 text-xs">
                            <div class="px-3 py-2 border-b border-black/[0.06] mb-1">
                                <div class="font-bold text-[#111111] truncate">{{ Auth::user()->name }}</div>
                                <div class="text-[11px] text-black/45 truncate">{{ Auth::user()->email }}</div>
                            </div>
                            <a href="{{ route('invoice.history.page') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-[#F6F5F1] text-[#111111]/85 font-semibold transition">
                                My Document History
                            </a>
                            <a href="{{ route('invoice.studio') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-[#F6F5F1] text-[#111111]/85 font-semibold transition">
                                Invoice Generator
                            </a>
                            <a href="{{ route('resume.builder') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-[#F6F5F1] text-[#111111]/85 font-semibold transition">
                                Resume Builder
                            </a>
                            <a href="{{ route('convert.hub') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-[#F6F5F1] text-[#111111]/85 font-semibold transition">
                                PDF Converters
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-1 pt-1 border-t border-black/[0.06]">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-rose-50 text-rose-600 font-semibold transition text-left">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                <!-- Mobile Hamburger Toggle -->
                <button type="button" @click="mobileMenu = !mobileMenu" aria-label="Toggle Menu"
                        class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-black/[0.09] text-[#111111] transition shrink-0">
                    <svg x-show="!mobileMenu" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                    <svg x-show="mobileMenu" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Slide-Down Navigation Drawer (3 Core Flows cleanly organized) -->
        <div x-show="mobileMenu" x-cloak
             @click.outside="mobileMenu = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden absolute left-0 right-0 top-full bg-white border-b border-black/[0.08] shadow-2xl z-40 max-h-[84vh] overflow-y-auto">
            <div class="px-4 py-4 space-y-4">
                <!-- Core 3 Modules Cards -->
                <div class="space-y-2">
                    <div class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-black/40 px-1">Three Core Studios</div>
                    <div class="grid grid-cols-1 gap-2">
                        <a href="{{ route('invoice.studio') }}" @click="mobileMenu = false"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#F6F5F1] text-[#111111] font-bold text-xs">
                            <span>Invoice &amp; 12 Document Studio</span>
                            <span class="text-[#0066FF]">Open &rarr;</span>
                        </a>
                        <a href="{{ route('resume.builder') }}" @click="mobileMenu = false"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#F6F5F1] text-[#111111] font-bold text-xs">
                            <span>Resume Builder &amp; CV Importer</span>
                            <span class="text-emerald-600">Open &rarr;</span>
                        </a>
                        <a href="{{ route('convert.hub') }}" @click="mobileMenu = false"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#F6F5F1] text-[#111111] font-bold text-xs">
                            <span>10 PDF Converter Tools</span>
                            <span class="text-[#FF325A]">Open &rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Template Galleries & Import Shortcut -->
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('templates.index') }}" @click="mobileMenu = false"
                       class="flex items-center justify-between gap-2 px-3 py-2.5 rounded-xl bg-white border border-black/[0.08] text-[#111111] font-bold text-xs">
                        <span>Invoice Themes</span>
                        <span class="text-[10px] font-extrabold text-[#0066FF]">20+</span>
                    </a>
                    <a href="{{ route('resume.templates') }}" @click="mobileMenu = false"
                       class="flex items-center justify-between gap-2 px-3 py-2.5 rounded-xl bg-white border border-black/[0.08] text-[#111111] font-bold text-xs">
                        <span>CV Themes</span>
                        <span class="text-[10px] font-extrabold text-emerald-600">15+</span>
                    </a>
                </div>

                <!-- Mobile 10 PDF Converters Grid -->
                <div>
                    <div class="flex items-center justify-between px-1 mb-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-black/40">PDF Converters (10)</span>
                        <a href="{{ route('convert.hub') }}" @click="mobileMenu = false" class="text-[10px] font-bold text-[#0066FF]">View All &rarr;</a>
                    </div>
                    <div class="grid grid-cols-2 gap-1.5">
                        @foreach(\App\Support\ConverterCatalog::all() as $cTool)
                            <a href="{{ route('convert.tool', $cTool['slug']) }}" @click="mobileMenu = false"
                               class="flex items-center gap-2 px-2.5 py-2 rounded-xl bg-[#F6F5F1]/80 text-[#111111]/85 font-semibold text-[11px]">
                                <span class="w-6 h-6 rounded-md {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-black text-[9px] shrink-0">{{ $cTool['badge'] }}</span>
                                <span class="truncate">{{ $cTool['name'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile 12 Document Tools Grid -->
                <div>
                    <div class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-black/40 px-1 mb-2">12 Document Generators</div>
                    <div class="grid grid-cols-2 gap-1.5">
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
                               class="px-3 py-2 rounded-xl bg-[#F6F5F1]/80 text-[#111111]/80 font-semibold text-xs truncate">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="pt-3 border-t border-black/[0.06]">
                    @guest
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('login') }}" @click="mobileMenu = false"
                               class="text-center py-2.5 rounded-xl border border-black/15 text-[#111111] font-bold text-xs">
                                Login
                            </a>
                            <a href="{{ route('register') }}" @click="mobileMenu = false"
                               class="text-center py-2.5 rounded-xl bg-[#111111] text-white font-bold text-xs">
                                Sign Up Free
                            </a>
                        </div>
                    @endguest

                    @auth
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('invoice.history.page') }}" @click="mobileMenu = false"
                               class="flex items-center justify-center py-2.5 rounded-xl bg-[#F6F5F1] text-[#111111] font-bold text-xs">
                                Document History
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full py-2.5 rounded-xl bg-rose-50 text-rose-600 font-bold text-xs">
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
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Unified Architectural Footer Covering All 3 Modules (Invoicing, Resume Builder, PDF Tools) -->
    <footer class="no-print bg-white border-t border-black/[0.07] pt-14 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 pb-12">
                <!-- Left Brand Column -->
                <div class="lg:col-span-4 space-y-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group">
                        <img src="{{ asset('images/formnexa-mark.png') }}" alt="Formnexa" class="h-8 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                        <span class="font-extrabold text-xl text-[#111111] tracking-[-0.03em]">Formnexa</span>
                    </a>
                    <p class="text-xs sm:text-[13px] text-black/60 leading-relaxed font-sans-inter max-w-sm">
                        One fast modern workspace for billing documents, designer resumes, and high-precision PDF conversions. Generate, customize, and download in seconds — 100% free with no account required.
                    </p>
                    <div class="flex flex-wrap items-center gap-2 pt-1 text-[11px] font-semibold">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[#F6F5F1] text-[#111111]/75">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            No Sign-up Required
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[#F6F5F1] text-[#111111]/75">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#0066FF]"></span>
                            Zero Watermarks
                        </span>
                    </div>
                </div>

                <!-- Column 1: Invoices & Document Generators -->
                <div class="lg:col-span-3">
                    <h3 class="text-[11px] font-extrabold uppercase tracking-[0.14em] text-black/40 mb-4">Invoices &amp; Documents</h3>
                    <div class="space-y-2 text-xs sm:text-[13px] font-sans-inter">
                        <div><a href="{{ route('invoice.studio') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">Invoice Generator</a></div>
                        <div><a href="{{ route('invoice.generator', 'receipt-generator') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">Receipt Generator</a></div>
                        <div><a href="{{ route('invoice.generator', 'quote-generator') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">Quote &amp; Estimate Maker</a></div>
                        <div><a href="{{ route('invoice.generator', 'purchase-order-generator') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">Purchase &amp; Sales Orders</a></div>
                        <div><a href="{{ route('invoice.generator', 'credit-note-generator') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">Credit Notes &amp; Statements</a></div>
                        <div><a href="{{ route('invoice.generator', 'timesheet-generator') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">Timesheets &amp; Work Orders</a></div>
                        <div><a href="{{ route('templates.index') }}" class="font-semibold text-[#0066FF] hover:underline">20+ Invoice Templates &rarr;</a></div>
                    </div>
                </div>

                <!-- Column 2: Resume Studio -->
                <div class="lg:col-span-2">
                    <h3 class="text-[11px] font-extrabold uppercase tracking-[0.14em] text-black/40 mb-4">Resume Studio</h3>
                    <div class="space-y-2 text-xs sm:text-[13px] font-sans-inter">
                        <div><a href="{{ route('resume.builder') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">Free Resume Builder</a></div>
                        <div><a href="{{ route('resume.builder', ['import' => 1]) }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">Import Existing CV</a></div>
                        <div><a href="{{ route('resume.templates') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">Figma &amp; FlowCV Themes</a></div>
                        <div><a href="{{ route('resume.templates') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">ATS Classic Templates</a></div>
                        <div><a href="{{ route('resume.builder', ['blank' => 1]) }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">Blank A4 CV Canvas</a></div>
                    </div>
                </div>

                <!-- Column 3: PDF Converter Suite -->
                <div class="lg:col-span-3">
                    <h3 class="text-[11px] font-extrabold uppercase tracking-[0.14em] text-black/40 mb-4">PDF Converters</h3>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs font-sans-inter">
                        <div><a href="{{ route('convert.tool', 'jpg-to-pdf') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">JPG to PDF</a></div>
                        <div><a href="{{ route('convert.tool', 'pdf-to-jpg') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">PDF to JPG</a></div>
                        <div><a href="{{ route('convert.tool', 'word-to-pdf') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">Word to PDF</a></div>
                        <div><a href="{{ route('convert.tool', 'pdf-to-word') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">PDF to Word</a></div>
                        <div><a href="{{ route('convert.tool', 'powerpoint-to-pdf') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">PPTX to PDF</a></div>
                        <div><a href="{{ route('convert.tool', 'pdf-to-powerpoint') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">PDF to PPTX</a></div>
                        <div><a href="{{ route('convert.tool', 'excel-to-pdf') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">Excel to PDF</a></div>
                        <div><a href="{{ route('convert.tool', 'pdf-to-excel') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">PDF to Excel</a></div>
                        <div><a href="{{ route('convert.tool', 'html-to-pdf') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">HTML to PDF</a></div>
                        <div><a href="{{ route('convert.tool', 'pdf-to-pdfa') }}" class="text-[#111111]/75 hover:text-[#0066FF] transition">PDF to PDF/A</a></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('convert.hub') }}" class="text-xs font-semibold text-[#0066FF] hover:underline">Explore Converter Hub &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Bottom Divider & Links -->
            <div class="pt-7 border-t border-black/[0.06] flex flex-col sm:flex-row items-center justify-between text-xs text-black/50 gap-4">
                <div>
                    &copy; {{ date('Y') }} Formnexa. All rights reserved.
                </div>
                <nav class="inline-flex flex-wrap justify-center items-center gap-x-4 gap-y-1.5 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-[#111111] transition">Home</a>
                    <a href="{{ route('invoice.studio') }}" class="hover:text-[#111111] transition">Invoice Generator</a>
                    <a href="{{ route('resume.builder') }}" class="hover:text-[#111111] transition">Resume Builder</a>
                    <a href="{{ route('convert.hub') }}" class="hover:text-[#111111] transition">PDF Tools</a>
                    <a href="{{ route('terms') }}" class="hover:text-[#111111] transition">Terms</a>
                    <a href="{{ route('privacy') }}" class="hover:text-[#111111] transition">Privacy</a>
                </nav>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    @stack('scripts')
</body>
</html>
