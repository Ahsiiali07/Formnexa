@extends('layouts.app')

@section('header_actions')
<a href="{{ route('invoice.studio') }}" class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 rounded-lg text-xs font-semibold bg-white hover:bg-black/[0.04] border border-black/[0.09] text-[#111111] transition whitespace-nowrap shrink-0">
    <span class="hidden sm:inline">Open Studio</span>
    <span class="sm:hidden">Studio</span>
    <svg class="w-3.5 h-3.5 text-[#184f3a] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
</a>
@endsection

@section('content')
<div x-data="templateGallery(@js($templates), @js($styles), @js($colors))" class="bg-white min-h-screen">

    <!-- Modern Electric Precision Hero Section -->
    <section class="bg-[#f8f8f3] border-b border-border-subtle overflow-hidden">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 py-8 sm:py-16 lg:py-20">
            <div class="mb-6 flex justify-center lg:justify-start">
                <x-schema.breadcrumbs :items="[['Home', url('/')], ['Invoice Generator', route('invoice.studio')], ['Invoice Templates', route('templates.index')]]" />
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                <!-- Left Copy -->
                <div class="lg:col-span-6 space-y-5 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-border-subtle text-[#184f3a] font-mono text-[11px] font-semibold uppercase tracking-wider shadow-sm">
                        100% Free &bull; 6 Visual Layouts &bull; 20+ Industry Templates
                    </div>
                    <h1 class="font-display text-3xl sm:text-5xl font-extrabold text-[#17211b] tracking-[-0.03em] leading-[1.08]">
                        Invoice Templates
                    </h1>
                    <p class="text-sm sm:text-lg text-[#536058] leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Choose from professionally designed templates across industries, formats, and layout styles &mdash; or customize your own inside the live invoice studio.
                    </p>
                    <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center justify-center lg:justify-start gap-3 pt-1 max-w-sm sm:max-w-none mx-auto">
                        <a href="#template-gallery" class="px-5 sm:px-6 py-3 sm:py-3.5 rounded-lg bg-primary-container hover:bg-electric-blue-hover text-white text-xs sm:text-sm font-bold shadow-lg shadow-[#184f3a]/25 transition whitespace-nowrap text-center">
                            Browse Our Templates
                        </a>
                        <a href="{{ route('invoice.studio') }}" class="px-5 sm:px-6 py-3 sm:py-3.5 rounded-lg bg-white border border-border-strong hover:border-[#17211b] text-[#17211b] text-xs sm:text-sm font-bold transition whitespace-nowrap text-center">
                            Make an Invoice
                        </a>
                    </div>
                </div>

                <!-- Right Floating Mockup Stack (Overflow-Safe on Mobile) -->
                <div class="lg:col-span-6 relative flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-[320px] sm:max-w-md h-64 sm:h-96 mx-auto lg:mx-0">
                        <!-- Back tilted card -->
                        <div class="hidden sm:block absolute right-0 top-6 w-72 sm:w-80 bg-white rounded-2xl border border-border-subtle shadow-xl p-6 transform rotate-3 opacity-85">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-xs font-extrabold text-teal-600 uppercase tracking-wider">Quote</span>
                                <span class="text-xs font-bold text-slate-400">#QUO-1042</span>
                            </div>
                            <div class="space-y-3">
                                <div class="h-8 bg-slate-100 rounded-lg w-3/4"></div>
                                <div class="flex justify-between items-center pt-3">
                                    <div class="h-4 bg-slate-100 rounded w-1/2"></div>
                                    <span class="text-sm font-extrabold text-slate-800">$420.00</span>
                                </div>
                                <div class="h-7 bg-slate-100 rounded-lg w-full"></div>
                                <div class="h-7 bg-slate-100 rounded-lg w-5/6"></div>
                                <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                                    <div class="h-10 w-16 bg-slate-100 rounded-lg"></div>
                                    <span class="text-base font-black text-slate-900">$1,250.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Front primary card -->
                        <div class="relative z-10 w-full max-w-[310px] sm:w-80 mx-auto sm:mx-0 bg-white rounded-2xl border border-border-strong shadow-2xl p-5 sm:p-6 sm:transform sm:-rotate-1 hover:rotate-0 transition duration-300">
                            <div class="flex justify-between items-center mb-5">
                                <span class="text-xl font-extrabold text-slate-900 tracking-tight">Invoice</span>
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold">Modern Studio</span>
                            </div>
                            <div class="space-y-2.5 mb-5">
                                <div class="h-9 bg-slate-100/90 rounded-lg w-28"></div>
                                <div class="h-8 bg-slate-100/90 rounded-lg w-28"></div>
                            </div>
                            <div class="flex justify-end mb-2">
                                <span class="text-sm font-extrabold text-slate-900">$150.00</span>
                            </div>
                            <div class="space-y-2 mb-5">
                                <div class="h-7 bg-slate-100/90 rounded-lg w-full"></div>
                                <div class="h-7 bg-slate-100/90 rounded-lg w-full"></div>
                            </div>
                            <div class="flex items-end justify-between pt-2">
                                <div class="h-14 w-24 bg-slate-100/90 rounded-xl"></div>
                                <div class="text-right">
                                    <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Total Due</div>
                                    <div class="text-lg font-black text-[#184f3a]">$250.00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6 Visual Design Layouts Showcase -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">6 Custom Layout Styles</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Filter by layout style below, or switch layouts anytime inside the Invoice Studio.</p>
            </div>
            <button type="button" x-show="selectedStyle !== 'all'" @click="selectedStyle = 'all'" class="text-xs font-bold text-blue-600 hover:underline self-start sm:self-auto">
                Show All Layouts &times;
            </button>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach($styles as $styleKey => $style)
                <button type="button"
                        @click="selectedStyle = (selectedStyle === '{{ $styleKey }}' ? 'all' : '{{ $styleKey }}')"
                        :class="selectedStyle === '{{ $styleKey }}' ? 'border-[#184f3a] bg-[#e8f7ed]/70 ring-2 ring-blue-500/20' : 'border-slate-200 bg-white hover:border-slate-300'"
                        class="text-left p-3.5 rounded-2xl border transition group flex flex-col justify-between">
                    <div>
                        <!-- Mini Style Icon Wireframe -->
                        <div class="h-12 w-full rounded-lg bg-slate-50 border border-slate-200/80 mb-2.5 overflow-hidden relative p-1.5">
                            @if($styleKey === 'modern')
                                <div class="h-2.5 w-full bg-gradient-to-r from-slate-800 to-teal-700 rounded-sm mb-1.5"></div>
                                <div class="h-1.5 w-1/2 bg-teal-500/70 rounded-full mb-1"></div>
                                <div class="h-1 w-3/4 bg-slate-200 rounded-full"></div>
                            @elseif($styleKey === 'classic')
                                <div class="border-b-2 border-slate-800 pb-1 mb-1 flex justify-between">
                                    <div class="h-1.5 w-6 bg-slate-700"></div>
                                    <div class="h-1.5 w-4 bg-slate-400"></div>
                                </div>
                                <div class="border border-slate-300 h-5 w-full grid grid-cols-3 gap-px bg-slate-200">
                                    <div class="bg-white"></div><div class="bg-white"></div><div class="bg-white"></div>
                                </div>
                            @elseif($styleKey === 'bold')
                                <div class="h-4 w-full bg-[#184f3a] rounded-sm mb-1.5 flex items-center px-1">
                                    <div class="h-1 w-5 bg-white/90 rounded-full"></div>
                                </div>
                                <div class="h-1.5 w-2/3 bg-slate-200 rounded-full mb-1"></div>
                                <div class="h-1.5 w-1/2 bg-slate-200 rounded-full"></div>
                            @elseif($styleKey === 'minimal')
                                <div class="h-1.5 w-8 bg-slate-800 rounded-full mb-2 mt-0.5"></div>
                                <div class="h-px w-full bg-slate-200 mb-1.5"></div>
                                <div class="h-1 w-full bg-slate-100 rounded-full mb-1"></div>
                                <div class="h-1 w-2/3 bg-slate-100 rounded-full"></div>
                            @elseif($styleKey === 'stripe')
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-600"></div>
                                <div class="pl-2 pt-0.5">
                                    <div class="h-1.5 w-7 bg-indigo-600/80 rounded-full mb-1.5"></div>
                                    <div class="h-1.5 w-full bg-indigo-50 rounded mb-1"></div>
                                    <div class="h-1.5 w-3/4 bg-slate-200 rounded"></div>
                                </div>
                            @elseif($styleKey === 'compact')
                                <div class="flex justify-between mb-1">
                                    <div class="h-1.5 w-5 bg-slate-700"></div>
                                    <div class="h-1.5 w-5 bg-amber-600"></div>
                                </div>
                                <div class="space-y-0.5">
                                    <div class="h-1.5 w-full bg-slate-200"></div>
                                    <div class="h-1.5 w-full bg-slate-100"></div>
                                    <div class="h-1.5 w-full bg-slate-200"></div>
                                </div>
                            @endif
                        </div>

                        <div class="font-bold text-xs text-slate-900">{{ $style['name'] }}</div>
                        <div class="text-[10px] text-slate-500 mt-0.5 leading-snug">{{ $style['tagline'] }}</div>
                    </div>
                </button>
            @endforeach
        </div>
    </section>

    <!-- Main Filterable Template Catalog -->
    <section id="template-gallery" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search & Category Controls -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-sm mb-8 space-y-4">
            <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
                <!-- Search Box -->
                <div class="relative flex-1 max-w-md">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" x-model="search" placeholder="Search templates (e.g., Freelance, Construction, SaaS, Legal...)" class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-[#184f3a] focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition">
                </div>

                <!-- Match count -->
                <div class="text-xs font-semibold text-slate-500 flex items-center gap-2">
                    <span>Showing <strong class="text-slate-900" x-text="filteredTemplates().length"></strong> templates</span>
                </div>
            </div>

            <!-- Category Pills -->
            <div class="flex flex-wrap gap-2">
                @foreach($categories as $catSlug => $catLabel)
                    <button type="button"
                            @click="selectedCategory = '{{ $catSlug }}'"
                            :class="selectedCategory === '{{ $catSlug }}' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100 hover:text-slate-900'"
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold border transition">
                        {{ $catLabel }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Templates Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="tpl in filteredTemplates()" :key="tpl.slug">
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-slate-300 transition duration-300 overflow-hidden flex flex-col group">
                    
                    <!-- Live Mini Document Thumbnail -->
                    <div @click="openPreview(tpl)" class="cursor-pointer bg-slate-100/80 p-5 border-b border-slate-200/70 relative overflow-hidden">
                        <div class="bg-white rounded-xl shadow-md border border-slate-200/80 overflow-hidden aspect-[4/3] flex flex-col transition-transform duration-300 group-hover:scale-[1.02] relative"
                             :class="{
                                 'border-l-[6px]': tpl.template_style === 'stripe',
                                 'rounded-none border-2 border-slate-700': tpl.template_style === 'classic'
                             }"
                             :style="tpl.template_style === 'stripe' ? 'border-left-color:' + tpl.accent_color : ''">
                            
                            <!-- Mini Style Header -->
                            <template x-if="tpl.template_style === 'bold'">
                                <div class="p-3 text-white flex justify-between items-center" :style="'background-color:' + tpl.accent_color">
                                    <div>
                                        <div class="text-[9px] font-bold uppercase tracking-wider opacity-85" x-text="tpl.from_name || 'Your Business'"></div>
                                        <div class="text-[7px] opacity-75" x-text="tpl.prefix + '1042'"></div>
                                    </div>
                                    <div class="text-xs font-black tracking-tight" x-text="tpl.invoice_title"></div>
                                </div>
                            </template>

                            <template x-if="tpl.template_style === 'modern'">
                                <div>
                                    <div class="h-4 w-full bg-gradient-to-r from-slate-900 via-teal-900 to-slate-900"></div>
                                    <div class="px-3 pt-2.5 pb-1 flex justify-between items-start">
                                        <div class="text-[9px] font-extrabold text-slate-900" x-text="tpl.from_name || 'Your Studio Name'"></div>
                                        <div class="text-[10px] font-black uppercase" :style="'color:' + tpl.accent_color" x-text="tpl.invoice_title"></div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="tpl.template_style !== 'bold' && tpl.template_style !== 'modern'">
                                <div class="px-3 pt-3 pb-1.5 flex justify-between items-start border-b"
                                     :class="tpl.template_style === 'classic' ? 'border-slate-800 border-b-2' : 'border-slate-100'">
                                    <div>
                                        <div class="text-[9px] font-extrabold text-slate-900" x-text="tpl.from_name || 'Business Name'"></div>
                                        <div class="text-[7px] text-slate-400" x-text="tpl.prefix + '1042'"></div>
                                    </div>
                                    <div class="text-[10px] font-black uppercase" :style="'color:' + tpl.accent_color" x-text="tpl.invoice_title"></div>
                                </div>
                            </template>

                            <!-- Mini Body & Items -->
                            <div class="p-3 flex-1 flex flex-col justify-between text-[7.5px] text-slate-600">
                                <div class="flex justify-between mb-2 text-[7px]">
                                    <div>
                                        <div class="text-slate-400 uppercase font-bold text-[6px]">Bill To</div>
                                        <div class="font-bold text-slate-800" x-text="tpl.to_name || 'Client Name'"></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-slate-400 uppercase font-bold text-[6px]">Terms</div>
                                        <div class="font-semibold text-slate-700" x-text="tpl.payment_terms"></div>
                                    </div>
                                </div>

                                <!-- Mini Table -->
                                <div class="space-y-1 flex-1">
                                    <div class="flex justify-between font-bold text-[6.5px] uppercase text-slate-400 pb-0.5 border-b border-slate-200">
                                        <span>Description</span>
                                        <span>Amount</span>
                                    </div>
                                    <template x-for="(item, idx) in tpl.items.slice(0, 3)" :key="idx">
                                        <div class="flex justify-between items-center py-0.5 border-b border-slate-50">
                                            <span class="truncate pr-2 text-slate-700 font-medium" x-text="item.description"></span>
                                            <span class="font-bold text-slate-900 tabular-nums" x-text="'$' + ((item.quantity || 1) * (item.unit_price || 0)).toFixed(0)"></span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Mini Footer Total -->
                                <div class="pt-1.5 border-t border-slate-200 flex justify-between items-center">
                                    <span class="text-[7px] font-bold uppercase text-slate-400">Total Due</span>
                                    <span class="text-[10px] font-black tabular-nums" :style="'color:' + tpl.accent_color" x-text="'$' + calcTotal(tpl).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Hover Preview Badge -->
                        <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-white/95 backdrop-blur-xs border border-slate-200 shadow-sm text-[10px] font-bold text-slate-700 opacity-0 group-hover:opacity-100 transition">
                            Click to Preview
                        </div>
                    </div>

                    <!-- Card Details -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600" x-text="tpl.category_label"></span>
                                <span class="text-[10px] font-bold uppercase tracking-wider" :style="'color:' + tpl.accent_color" x-text="styles[tpl.template_style]?.name || tpl.template_style"></span>
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900 mb-1" x-text="tpl.name"></h3>
                            <p class="text-xs text-slate-500 leading-relaxed font-sans-inter" x-text="tpl.description"></p>
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-slate-100">
                            <a :href="'{{ route('invoice.studio') }}?template=' + tpl.slug" class="flex-1 text-center px-3.5 py-2.5 rounded-xl bg-slate-900 hover:bg-[#184f3a] text-white text-xs font-bold transition shadow-sm">
                                Use Template
                            </a>
                            <button type="button" @click="openPreview(tpl)" class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                Preview
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty Search State -->
        <div x-show="filteredTemplates().length === 0" x-cloak class="text-center py-16 bg-white rounded-2xl border border-slate-200 mt-4">
            <div class="text-3xl mb-2">📄</div>
            <h3 class="text-base font-bold text-slate-900">No matching templates found</h3>
            <p class="text-xs text-slate-500 mt-1 mb-4">Try clearing your search or selecting a different layout style.</p>
            <button type="button" @click="search = ''; selectedCategory = 'all'; selectedStyle = 'all';" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold">Reset Filters</button>
        </div>
    </section>

    <!-- Live Interactive Template Preview Modal -->
    <div x-cloak x-show="previewTemplate" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs" @keydown.escape.window="previewTemplate = null">
        <div x-show="previewTemplate" @click.outside="previewTemplate = null" class="bg-white w-full max-w-3xl max-h-[90vh] rounded-3xl shadow-2xl border border-slate-200 flex flex-col overflow-hidden">
            
            <!-- Modal Header & Interactive Customization Toolbar -->
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400" x-text="previewTemplate?.category_label"></div>
                    <h3 class="text-base font-extrabold text-slate-900" x-text="previewTemplate?.name"></h3>
                </div>

                <!-- Live Style & Color Tester inside Preview -->
                <div class="flex flex-wrap items-center gap-2">
                    <select x-model="previewStyle" class="text-xs font-bold bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-slate-800">
                        @foreach($styles as $styleKey => $style)
                            <option value="{{ $styleKey }}">{{ $style['name'] }}</option>
                        @endforeach
                    </select>

                    <div class="flex items-center gap-1 bg-white border border-slate-200 rounded-lg px-2 py-1">
                        @foreach($colors as $clr)
                            <button type="button" @click="previewColor = '{{ $clr['hex'] }}'" class="w-4 h-4 rounded-full border border-white shadow-sm transition hover:scale-110" :class="previewColor === '{{ $clr['hex'] }}' ? 'ring-2 ring-slate-900 scale-110' : ''" style="background-color: {{ $clr['hex'] }}" title="{{ $clr['name'] }}"></button>
                        @endforeach
                        <input type="color" x-model="previewColor" class="w-5 h-5 rounded cursor-pointer border-0 p-0 bg-transparent" title="Custom Color">
                    </div>

                    <button type="button" @click="previewTemplate = null" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-800 hover:bg-slate-200/70 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Modal Live Document Preview Area -->
            <div class="flex-1 overflow-y-auto p-6 sm:p-8 bg-slate-100">
                <template x-if="previewTemplate">
                    <div class="bg-white shadow-xl mx-auto overflow-hidden transition-all"
                         :class="{
                             'rounded-2xl border border-slate-200': previewStyle === 'modern' || previewStyle === 'minimal',
                             'rounded-none border-2 border-slate-800': previewStyle === 'classic',
                             'rounded-xl border border-slate-200 border-l-[12px]': previewStyle === 'stripe',
                             'rounded-2xl border border-slate-200': previewStyle === 'bold',
                             'rounded-lg border border-slate-300': previewStyle === 'compact'
                         }"
                         :style="previewStyle === 'stripe' ? 'border-left-color:' + previewColor : ''">
                        
                        <!-- Preview Style Header -->
                        <template x-if="previewStyle === 'modern'">
                            <div class="h-14 bg-gradient-to-r from-slate-900 via-teal-950 to-slate-900"></div>
                        </template>

                        <template x-if="previewStyle === 'bold'">
                            <div class="p-6 text-white flex justify-between items-start" :style="'background-color:' + previewColor">
                                <div>
                                    <div class="text-xl font-extrabold" x-text="previewTemplate.from_name || 'Your Business Studio'"></div>
                                    <div class="text-xs opacity-80 mt-0.5" x-text="previewTemplate.from_email || 'billing@yourcompany.com'"></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black uppercase tracking-tight" x-text="previewTemplate.invoice_title"></div>
                                    <div class="text-xs opacity-85 font-semibold" x-text="'#' + previewTemplate.prefix + '1042'"></div>
                                </div>
                            </div>
                        </template>

                        <div class="p-6 sm:p-8 space-y-6">
                            <!-- Non-Bold Header -->
                            <template x-if="previewStyle !== 'bold'">
                                <div class="flex justify-between items-start pb-4" :class="previewStyle === 'classic' ? 'border-b-2 border-slate-900' : 'border-b border-slate-100'">
                                    <div>
                                        <div class="text-lg font-extrabold text-slate-900" x-text="previewTemplate.from_name || 'Your Business Studio'"></div>
                                        <div class="text-xs text-slate-500 mt-0.5 whitespace-pre-line" x-text="previewTemplate.from_address || '123 Business Road, Suite 100'"></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-black uppercase tracking-tight" :style="'color:' + previewColor" x-text="previewTemplate.invoice_title"></div>
                                        <div class="text-xs font-bold text-slate-500 mt-1" x-text="'#' + previewTemplate.prefix + '1042'"></div>
                                    </div>
                                </div>
                            </template>

                            <!-- Parties & Terms -->
                            <div class="grid grid-cols-2 gap-4 text-xs" :class="previewStyle === 'compact' ? 'bg-slate-50 p-3 rounded-lg border border-slate-200' : ''">
                                <div>
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Billed To</div>
                                    <div class="font-bold text-slate-900" x-text="previewTemplate.to_name || 'Client Name / Company'"></div>
                                    <div class="text-slate-500 whitespace-pre-line mt-0.5" x-text="previewTemplate.to_address || 'Client Street Address'"></div>
                                </div>
                                <div class="text-right space-y-1">
                                    <div><span class="text-slate-400">Terms:</span> <strong class="text-slate-800" x-text="previewTemplate.payment_terms"></strong></div>
                                    <div><span class="text-slate-400">Issue Date:</span> <strong class="text-slate-800">{{ now()->format('M d, Y') }}</strong></div>
                                </div>
                            </div>

                            <!-- Items Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs border-collapse min-w-[400px]" :class="previewStyle === 'classic' ? 'border border-slate-300' : ''">
                                    <thead>
                                        <tr class="text-left uppercase text-[10px] tracking-wider"
                                            :class="{
                                                'bg-slate-100 text-slate-700 border-y border-slate-300': previewStyle === 'classic',
                                                'text-slate-400 border-b border-slate-200': previewStyle === 'modern' || previewStyle === 'minimal',
                                                'text-white': previewStyle === 'bold',
                                                'border-b-2 text-slate-600': previewStyle === 'stripe' || previewStyle === 'compact'
                                            }"
                                            :style="previewStyle === 'bold' ? 'background-color:' + previewColor : (previewStyle === 'stripe' ? 'border-bottom-color:' + previewColor : '')">
                                            <th class="py-2.5 px-3">Description</th>
                                            <th class="py-2.5 px-3 text-center">Qty</th>
                                            <th class="py-2.5 px-3 text-right">Rate</th>
                                            <th class="py-2.5 px-3 text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <template x-for="(item, i) in previewTemplate.items" :key="i">
                                            <tr :class="previewStyle === 'compact' && i % 2 === 1 ? 'bg-slate-50/80' : ''">
                                                <td class="py-2.5 px-3 font-medium text-slate-800" :class="previewStyle === 'classic' ? 'border border-slate-200' : ''" x-text="item.description"></td>
                                                <td class="py-2.5 px-3 text-center text-slate-600" :class="previewStyle === 'classic' ? 'border border-slate-200' : ''" x-text="item.quantity"></td>
                                                <td class="py-2.5 px-3 text-right text-slate-600" :class="previewStyle === 'classic' ? 'border border-slate-200' : ''" x-text="'$' + Number(item.unit_price).toFixed(2)"></td>
                                                <td class="py-2.5 px-3 text-right font-bold text-slate-900" :class="previewStyle === 'classic' ? 'border border-slate-200' : ''" x-text="'$' + (item.quantity * item.unit_price).toFixed(2)"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Totals & Notes -->
                            <div class="flex flex-col sm:flex-row justify-between gap-6 pt-2">
                                <div class="text-xs text-slate-500 max-w-sm">
                                    <div class="font-bold text-slate-700 mb-1">Notes & Terms</div>
                                    <p x-text="previewTemplate.notes"></p>
                                    <p class="mt-1 text-[11px] text-slate-400" x-text="previewTemplate.terms"></p>
                                </div>
                                <div class="w-full sm:w-56 space-y-1.5 text-xs">
                                    <div class="flex justify-between text-slate-600">
                                        <span>Subtotal</span>
                                        <span class="font-bold text-slate-900" x-text="'$' + calcSubtotal(previewTemplate).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                    </div>
                                    <div x-show="previewTemplate.tax_rate > 0" class="flex justify-between text-slate-600">
                                        <span x-text="'Tax (' + previewTemplate.tax_rate + '%)'"></span>
                                        <span class="font-bold text-slate-900" x-text="'$' + (calcSubtotal(previewTemplate) * previewTemplate.tax_rate / 100).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2 border-t-2 font-extrabold text-sm" :style="'border-color:' + previewColor + '; color:' + previewColor">
                                        <span>Total Due</span>
                                        <span x-text="'$' + calcTotal(previewTemplate).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Modal Footer Action -->
            <div class="px-5 sm:px-6 py-4 bg-white border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                <div class="text-xs text-slate-500">
                    Customization tip: You can edit every line, logo, and currency inside the studio.
                </div>
                <a :href="'{{ route('invoice.studio') }}?template=' + previewTemplate?.slug + '&style=' + previewStyle + '&color=' + encodeURIComponent(previewColor)"
                   class="w-full sm:w-auto text-center px-5 py-2.5 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-xs font-bold shadow-md transition whitespace-nowrap">
                    Customize This Template &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- How Templates Work Section -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 shadow-sm">
            <div class="max-w-3xl mb-8">
                <h2 class="text-2xl font-extrabold text-slate-900">How to Use Formnexa Invoice Templates</h2>
                <p class="text-sm text-slate-600 mt-2">Skip formatting spreadsheets or fighting Word margins. Pick any layout, adjust your colors, and generate a crisp PDF in under a minute.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/60">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-[#184f3a] font-extrabold flex items-center justify-center mb-3">1</div>
                    <h4 class="font-bold text-sm text-slate-900 mb-1">Choose Industry or Blank Layout</h4>
                    <p class="text-slate-600 leading-relaxed">Select a blank design style (Modern, Classic, Bold, Minimal, Stripe, or Compact) or start with pre-filled industry line items.</p>
                </div>
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/60">
                    <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 font-extrabold flex items-center justify-center mb-3">2</div>
                    <h4 class="font-bold text-sm text-slate-900 mb-1">Customize Style, Color & Logo</h4>
                    <p class="text-slate-600 leading-relaxed">Upload your company logo, draw a digital signature, pick any brand accent color, and switch layouts live without losing data.</p>
                </div>
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/60">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 font-extrabold flex items-center justify-center mb-3">3</div>
                    <h4 class="font-bold text-sm text-slate-900 mb-1">Download PDF or Send to Client</h4>
                    <p class="text-slate-600 leading-relaxed">Export a clean A4 vector PDF matching your selected template, or send your client a live hosted invoice link with one click.</p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('templateGallery', (templates, styles, colors) => ({
        templates: templates || [],
        styles: styles || {},
        colors: colors || [],
        search: '',
        selectedCategory: 'all',
        selectedStyle: 'all',
        previewTemplate: null,
        previewStyle: 'modern',
        previewColor: '#184f3a',

        filteredTemplates() {
            const q = this.search.trim().toLowerCase();
            return this.templates.filter(tpl => {
                const matchCat = this.selectedCategory === 'all' || tpl.category === this.selectedCategory;
                const matchStyle = this.selectedStyle === 'all' || tpl.template_style === this.selectedStyle;
                const matchQuery = !q ||
                    tpl.name.toLowerCase().includes(q) ||
                    tpl.category_label.toLowerCase().includes(q) ||
                    tpl.description.toLowerCase().includes(q);
                return matchCat && matchStyle && matchQuery;
            });
        },

        openPreview(tpl) {
            this.previewTemplate = tpl;
            this.previewStyle = tpl.template_style || 'modern';
            this.previewColor = tpl.accent_color || '#184f3a';
        },

        calcSubtotal(tpl) {
            if (!tpl || !tpl.items) return 0;
            return tpl.items.reduce((sum, item) => sum + ((parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0)), 0);
        },

        calcTotal(tpl) {
            const sub = this.calcSubtotal(tpl);
            const tax = sub * ((parseFloat(tpl?.tax_rate) || 0) / 100);
            return sub + tax;
        }
    }));
});
</script>
@endpush
