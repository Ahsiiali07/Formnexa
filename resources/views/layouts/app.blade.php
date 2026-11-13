<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth bg-[#ffffff]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="pdf-worker-url" content="{{ asset('vendor/pdfjs/pdf.worker.min.mjs') }}">
    @include('partials.seo-head')

    <!-- Favicon & Touch Icons (Formnexa Forest Green Brand Mark) -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=2">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=2">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=2">

    <!-- Vite Compiled Assets (Tailwind CSS + Self-Hosted Fonts + Alpine.js + PDF Tools) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                            <x-icon name="chevron-down" class="w-[17px] h-[17px] transition-transform duration-150" ::class="openResume ? 'rotate-180' : ''" />
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
                                <a href="{{ route('resume.builder', ['import' => 1]) }}" rel="nofollow" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#e8f7ed]/70 transition group">
                                    <span class="w-8 h-8 rounded-lg bg-[#e8f7ed] text-[#184f3a] border border-[#c8f3dc] flex items-center justify-center shrink-0">
                                        <x-icon name="upload" class="w-[17px] h-[17px]" />
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
                            <x-icon name="chevron-down" class="w-[17px] h-[17px] transition-transform duration-150" ::class="openConv ? 'rotate-180' : ''" />
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
                            <x-icon name="chevron-down" class="w-[17px] h-[17px] transition-transform duration-150" ::class="openTools ? 'rotate-180' : ''" />
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

                    <!-- 5. Blog & SEO Guides -->
                    <a href="{{ route('blog.index') }}"
                       class="px-3 py-2 rounded-lg flex items-center gap-1.5 transition-all duration-150 whitespace-nowrap {{ request()->routeIs('blog.*') ? 'text-[#184f3a] bg-[#e8f7ed] font-semibold' : 'text-[#3f4a43] hover:text-[#17211b] hover:bg-[#f2f6f3]' }}">
                        <span>Blog</span>
                    </a>
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
                            <x-icon name="chevron-down" class="w-4 h-4 text-[#68736d]" />
                        </button>

                        <div x-show="userMenu" x-cloak x-transition class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-xl shadow-[#17211b]/8 border border-[#dfe4df] p-2 z-50 text-xs">
                            <div class="px-3 py-2 border-b border-[#dfe4df] mb-1">
                                <div class="font-bold text-[#17211b] truncate">{{ Auth::user()->name }}</div>
                                <div class="text-[11px] text-[#68736d] truncate">{{ Auth::user()->email }}</div>
                            </div>
                            @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-[#e8f7ed] text-[#184f3a] font-bold transition mb-1">
                                    Admin Dashboard
                                </a>
                            @endif
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
                    <x-icon name="bars" class="w-[21px] h-[21px]" x-show="!mobileMenu" />
                    <x-icon name="x-mark" class="w-[21px] h-[21px]" x-show="mobileMenu" x-cloak />
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
                                <x-icon name="badge" class="w-[18px] h-[18px] text-[#6142be]" />
                                <span>Resume Builder &amp; CV Importer</span>
                            </span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-[#6142be]" />
                        </a>
                        <a href="{{ route('invoice.studio') }}" @click="mobileMenu = false"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#e8f7ed] border border-[#c8f3dc] text-[#17211b] font-bold text-xs">
                            <span class="flex items-center gap-2">
                                <x-icon name="receipt" class="w-[18px] h-[18px] text-[#184f3a]" />
                                <span>Invoice Maker &amp; 12 Document Tools</span>
                            </span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-[#184f3a]" />
                        </a>
                        <a href="{{ route('convert.hub') }}" @click="mobileMenu = false"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#fff0e8] border border-[#fedac7] text-[#17211b] font-bold text-xs">
                            <span class="flex items-center gap-2">
                                <x-icon name="document-pdf" class="w-[18px] h-[18px] text-[#b45309]" />
                                <span>{{ $converterCount }} PDF Converter Tools</span>
                            </span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-[#b45309]" />
                        </a>
                    </div>
                </div>

                <!-- Template Galleries & Blog -->
                <div class="grid grid-cols-3 gap-2">
                    <a href="{{ route('templates.index') }}" @click="mobileMenu = false"
                       class="flex items-center justify-between gap-1 px-3 py-2.5 rounded-xl bg-[#f8f8f3] border border-[#dfe4df] text-[#17211b] font-bold text-xs">
                        <span>Invoices</span>
                        <span class="font-mono text-[10px] font-bold text-[#184f3a]">20+</span>
                    </a>
                    <a href="{{ route('resume.templates') }}" @click="mobileMenu = false"
                       class="flex items-center justify-between gap-1 px-3 py-2.5 rounded-xl bg-[#f8f8f3] border border-[#dfe4df] text-[#17211b] font-bold text-xs">
                        <span>CVs</span>
                        <span class="font-mono text-[10px] font-bold text-[#6142be]">15+</span>
                    </a>
                    <a href="{{ route('blog.index') }}" @click="mobileMenu = false"
                       class="flex items-center justify-center gap-1 px-3 py-2.5 rounded-xl bg-[#e8f7ed] border border-[#c8f3dc] text-[#184f3a] font-bold text-xs">
                        <span>Blog &rarr;</span>
                    </a>
                </div>

                <!-- Collapsible Converters Section -->
                <div class="border border-[#dfe4df] rounded-xl overflow-hidden">
                    <button type="button" @click="mobileSection = (mobileSection === 'converters' ? '' : 'converters')"
                            class="w-full px-3.5 py-2.5 bg-[#f8f8f3] flex items-center justify-between text-xs font-bold text-[#17211b]">
                        <span>PDF Converters ({{ $converterCount }})</span>
                        <x-icon name="chevron-down" class="w-[18px] h-[18px] text-[#68736d] transition-transform" ::class="mobileSection === 'converters' ? 'rotate-180' : ''" />
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
                        <x-icon name="chevron-down" class="w-[18px] h-[18px] text-[#68736d] transition-transform" ::class="mobileSection === 'generators' ? 'rotate-180' : ''" />
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
                        <div class="space-y-2">
                            @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" @click="mobileMenu = false"
                                   class="flex items-center justify-center py-2.5 rounded-[10px] bg-[#184f3a] text-white font-bold text-xs">
                                    Admin Dashboard
                                </a>
                            @endif
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
                    <h3 class="text-[14px] font-semibold text-white mb-4">Products</h3>
                    <ul class="space-y-2.5 text-[14px]">
                        <li><a href="{{ route('resume.builder') }}" class="hover:text-white transition-colors">Resume Builder</a></li>
                        <li><a href="{{ route('invoice.studio') }}" class="hover:text-white transition-colors">Invoice Maker</a></li>
                        <li><a href="{{ route('convert.hub') }}" class="hover:text-white transition-colors">PDF Converter</a></li>
                        <li><a href="{{ route('resume.templates') }}" class="hover:text-white transition-colors">Resume Templates</a></li>
                        <li><a href="{{ route('templates.index') }}" class="hover:text-white transition-colors">Invoice Templates</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-white transition-colors">Guides &amp; Blog</a></li>
                    </ul>
                </div>

                <!-- Column 2: Document & PDF Tools -->
                <div>
                    <h3 class="text-[14px] font-semibold text-white mb-4">Popular Tools</h3>
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
                    <h3 class="text-[14px] font-semibold text-white mb-4">Company</h3>
                    <ul class="space-y-2.5 text-[14px]">
                        <li><a href="{{ url('/#features') }}" class="hover:text-white transition-colors">Why Formnexa</a></li>
                        <li><a href="{{ url('/#how') }}" class="hover:text-white transition-colors">How it works</a></li>
                        <li><a href="{{ url('/#faq') }}" class="hover:text-white transition-colors">Questions &amp; Answers</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-white transition-colors">Blog</a></li>
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
