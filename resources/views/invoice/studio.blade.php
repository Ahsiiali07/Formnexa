@extends('layouts.app')

@section('title', 'Formnexa — ' . ($activeConfig['title'] ?? 'Smart Document Studio'))

@section('header_actions')
<button type="button" @click="$dispatch('open-history-drawer')" class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 rounded-lg text-xs font-semibold bg-white hover:bg-black/[0.04] border border-black/[0.09] text-[#111111] transition whitespace-nowrap shrink-0 cursor-pointer">
    <svg class="w-3.5 h-3.5 text-[#0066FF] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span class="hidden sm:inline">Saved Invoices</span>
    <span class="sm:hidden">Saved</span>
</button>
@endsection

@section('content')
<div x-data="bloomInvoiceEditor(@js($loadedInvoice), @js($config ?? null), @js($loadedTemplate ?? null), @js($templateStyles ?? []), @js($templateColors ?? []), @js($allTemplates ?? []))" x-init="init()" class="relative bg-[#F8FAFC] pb-28 sm:pb-32">

    <!-- Toast Notification -->
    <div x-cloak x-show="toast.visible"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="fixed bottom-20 left-4 right-4 sm:left-auto sm:right-6 sm:bottom-24 z-50 flex items-center gap-3 px-4 sm:px-5 py-3.5 rounded-2xl shadow-2xl text-white text-xs sm:text-sm font-medium"
         :class="toast.type === 'error' ? 'bg-rose-600' : 'bg-[#111111] border border-white/10'">
        <span x-show="toast.type !== 'error'" class="w-2 h-2 rounded-full bg-teal-400 animate-pulse shrink-0"></span>
        <span x-text="toast.message"></span>
    </div>

    <!-- Bloom.io-Inspired Hero Header & Document Studio Bar -->
    <section class="no-print pt-7 sm:pt-11 pb-6 sm:pb-8 text-center max-w-6xl mx-auto px-4">
        <!-- 12 Document Types Switcher Ribbon -->
        <div class="mb-6 -mx-4 px-4 sm:mx-0 sm:px-0 overflow-x-auto scrollbar-hide">
            <div class="inline-flex sm:flex items-center sm:justify-center sm:flex-wrap gap-1.5 min-w-full sm:min-w-0">
                @php
                    $currentSlug = $config['slug'] ?? 'invoice-generator';
                @endphp
                @foreach($allGenerators ?? \App\Http\Controllers\InvoiceController::$generators as $slug => $gen)
                    <a href="{{ $slug === 'invoice-generator' ? route('invoice.studio') : route('invoice.generator', $slug) }}"
                       class="whitespace-nowrap px-3 py-1.5 rounded-lg text-[11px] font-semibold transition border shrink-0 {{ $currentSlug === $slug ? 'bg-[#111111] text-white border-[#111111] shadow-sm' : 'bg-white text-[#111111]/75 border-black/[0.08] hover:border-black/25 hover:text-[#111111]' }}">
                        {{ str_replace(' Generator', '', $gen['name']) }}
                    </a>
                @endforeach
            </div>
        </div>

        <h1 class="text-3xl sm:text-5xl lg:text-[54px] font-extrabold text-[#111111] tracking-[-0.035em] leading-[1.06] mb-3">
            {{ $config['headline'] ?? 'Free Invoice Generator' }}
        </h1>
        <p class="text-sm sm:text-base text-[#111111]/65 font-sans-inter max-w-2xl mx-auto leading-relaxed mb-4">
            {{ $config['subtitle'] ?? 'The trusted free invoice builder. No account required — customize branding, payment links, and export clean PDFs in seconds.' }}
        </p>

        <!-- Clean Monochrome Integration Strip (Bloom.io invoice-generator cue) -->
        <div class="mb-6 flex flex-col items-center gap-2">
            <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-black/40">Integrated With Payment Links &amp; Instant PDF</span>
            <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-1 text-xs font-extrabold tracking-tight text-black/40">
                <span>Stripe</span>
                <span>PayPal</span>
                <span>Venmo</span>
                <span>Cash App</span>
                <span>Zelle</span>
                <span>Direct Bank</span>
            </div>
        </div>

        <!-- Clean Mode & Workspace Controls Bar -->
        <div class="flex flex-col md:flex-row items-center justify-center gap-2.5 sm:gap-3">
            <div class="inline-flex p-1 bg-white rounded-xl border border-black/[0.09] shadow-sm">
                <button type="button" @click="viewMode = 'edit'" class="px-4 sm:px-5 py-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap cursor-pointer" :class="viewMode === 'edit' ? 'bg-[#111111] text-white shadow-sm' : 'text-[#111111]/65 hover:text-[#111111]'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                    <span>Edit Mode</span>
                </button>
                <button type="button" @click="viewMode = 'preview'" class="px-4 sm:px-5 py-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap cursor-pointer" :class="viewMode === 'preview' ? 'bg-[#111111] text-white shadow-sm' : 'text-[#111111]/65 hover:text-[#111111]'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Client Preview</span>
                </button>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-2">
                <!-- Quick Currency Switcher -->
                <div class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-black/[0.09] shadow-sm text-xs font-semibold text-[#111111] whitespace-nowrap">
                    <span class="text-black/45 font-medium text-[11px] uppercase tracking-wider">Currency:</span>
                    <select x-model="currency" @change="onCurrencyChange()" class="bg-transparent font-bold text-[#111111] border-none p-0 pr-1.5 text-xs focus:ring-0 focus:outline-none cursor-pointer">
                        <option value="USD|$">USD ($)</option>
                        <option value="EUR|€">EUR (€)</option>
                        <option value="GBP|£">GBP (£)</option>
                        <option value="CAD|$">CAD ($)</option>
                        <option value="AUD|$">AUD ($)</option>
                        <option value="INR|₹">INR (₹)</option>
                        <option value="PKR|Rs">PKR (Rs)</option>
                        <option value="AED|AED">AED (AED)</option>
                        <option value="SAR|SAR">SAR (SAR)</option>
                        <option value="JPY|¥">JPY (¥)</option>
                        <option value="CNY|¥">CNY (¥)</option>
                        <option value="CHF|CHF">CHF (CHF)</option>
                        <option value="SGD|$">SGD ($)</option>
                        <option value="NZD|$">NZD ($)</option>
                        <option value="BRL|R$">BRL (R$)</option>
                        <option value="MXN|$">MXN ($)</option>
                        <option value="ZAR|R">ZAR (R)</option>
                        <option value="TRY|₺">TRY (₺)</option>
                        <option value="SEK|kr">SEK (kr)</option>
                        <option value="NOK|kr">NOK (kr)</option>
                        <option value="THB|฿">THB (฿)</option>
                        <option value="IDR|Rp">IDR (Rp)</option>
                        <option value="MYR|RM">MYR (RM)</option>
                        <option value="PHP|₱">PHP (₱)</option>
                    </select>
                </div>

                <!-- Custom Template & Color Switcher -->
                <button type="button" @click="templateDrawerOpen = true" class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-xl bg-white border border-black/[0.09] hover:border-[#0066FF] shadow-sm text-xs font-semibold text-[#111111] transition group whitespace-nowrap cursor-pointer">
                    <span class="w-3 h-3 rounded-full border border-white shadow-sm shrink-0" :style="'background-color:' + accent_color"></span>
                    <span class="text-black/45 font-medium text-[11px] uppercase tracking-wider hidden sm:inline">Template:</span>
                    <span class="font-bold text-[#111111] group-hover:text-[#0066FF]" x-text="templateStyles[template_style]?.name || 'Modern Studio'"></span>
                    <svg class="w-3.5 h-3.5 text-black/45 group-hover:text-[#0066FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg>
                </button>

                <button type="button" @click="resetForm()" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-black/50 hover:text-rose-600 hover:bg-white border border-transparent hover:border-black/[0.08] transition whitespace-nowrap cursor-pointer">
                    Reset
                </button>
            </div>
        </div>
    </section>

    <!-- Main Workspace Container -->
    <div class="max-w-4xl mx-auto px-3 sm:px-6">

        <!-- Dynamic Floating Invoice Canvas Card -->
        <div class="bg-white overflow-hidden transition-all duration-300 print-container"
             :class="{
                 'rounded-2xl sm:rounded-3xl shadow-2xl shadow-slate-200/80 border border-slate-200/80': template_style === 'modern' || template_style === 'bold',
                 'rounded-none shadow-lg border-2 border-slate-800': template_style === 'classic',
                 'rounded-xl shadow-sm border border-slate-200': template_style === 'minimal',
                 'rounded-2xl shadow-xl border border-slate-200 border-l-[10px] sm:border-l-[12px]': template_style === 'stripe',
                 'rounded-xl shadow-lg border border-slate-300': template_style === 'compact'
             }"
             :style="template_style === 'stripe' ? 'border-left-color:' + accent_color : (template_style === 'classic' ? 'border-top: 6px solid ' + accent_color : '')">
            
            <!-- Zone 1A: Header Cover Banner (Modern Studio or Custom Uploaded Cover) -->
            <template x-if="template_style === 'modern' || cover_image">
                <div class="relative group bg-slate-900 overflow-hidden" :class="cover_image ? 'h-44 sm:h-64' : 'h-24 sm:h-32 bg-gradient-to-r from-slate-900 via-teal-950 to-slate-900'">
                    <template x-if="cover_image">
                        <img :src="cover_image" alt="Cover Banner" class="w-full h-full object-cover">
                    </template>

                    <!-- Desktop Hover Cover Controls Overlay (No Print) -->
                    <div class="no-print hidden sm:flex absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity items-center justify-center gap-3">
                        <label class="cursor-pointer px-4 py-2 rounded-full bg-white/95 hover:bg-white text-slate-900 text-xs font-bold shadow-lg transition flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            <span x-text="cover_image ? 'Change Cover Photo' : '+ Add Cover Photo'"></span>
                            <input type="file" @change="handleCoverUpload($event)" accept="image/*" class="hidden">
                        </label>
                        <button type="button" x-show="cover_image" @click="cover_image = ''" class="px-4 py-2 rounded-full bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-lg transition">
                            Remove Cover
                        </button>
                    </div>

                    <!-- Touch-Friendly Bottom Cover Toolbar -->
                    <div class="no-print absolute bottom-2.5 left-3 right-3 sm:left-auto sm:right-4 flex items-center justify-between sm:justify-end gap-2">
                        <label x-show="viewMode === 'edit'" class="sm:hidden cursor-pointer inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/90 text-slate-900 text-[10px] font-bold shadow-sm">
                            <span>+ Cover</span>
                            <input type="file" @change="handleCoverUpload($event)" accept="image/*" class="hidden">
                        </label>

                        <div class="flex items-center gap-1.5 bg-slate-900/75 backdrop-blur-md px-2.5 sm:px-3 py-1.5 rounded-full border border-white/10 text-[10px] text-white ml-auto">
                            <span class="text-slate-400 hidden sm:inline">Presets:</span>
                            <button type="button" @click="setPresetCover('emerald')" class="hover:text-teal-400 font-bold transition">Emerald</button>
                            <span>&bull;</span>
                            <button type="button" @click="setPresetCover('abstract')" class="hover:text-teal-400 font-bold transition">Abstract</button>
                            <span>&bull;</span>
                            <button type="button" @click="setPresetCover('dark')" class="hover:text-teal-400 font-bold transition">Obsidian</button>
                            <span>&bull;</span>
                            <button type="button" @click="cover_image = ''" class="hover:text-rose-400 transition">None</button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Zone 1B: Bold Executive Full-Bleed Header Banner -->
            <template x-if="template_style === 'bold' && !cover_image">
                <div class="px-5 sm:px-12 py-6 sm:py-10 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 sm:gap-6" :style="'background-color:' + accent_color">
                    <div class="flex items-center gap-3.5 sm:gap-4 w-full sm:w-auto min-w-0">
                        <template x-if="logo_data">
                            <div class="relative group shrink-0">
                                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl bg-white p-2 shadow-lg flex items-center justify-center overflow-hidden">
                                    <img :src="logo_data" alt="Brand Logo" class="max-w-full max-h-full object-contain">
                                </div>
                                <button type="button" @click="logo_data = ''" class="no-print absolute -top-2 -right-2 bg-rose-600 hover:bg-rose-700 text-white rounded-full p-1 shadow-md transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                        <template x-if="!logo_data && viewMode === 'edit'">
                            <label class="no-print w-16 h-16 rounded-2xl border-2 border-dashed border-white/40 hover:border-white bg-white/10 hover:bg-white/20 p-2 flex flex-col items-center justify-center cursor-pointer text-center transition shrink-0">
                                <svg class="w-5 h-5 text-white/80 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                <span class="text-[9px] font-bold text-white">Logo</span>
                                <input type="file" @change="handleLogoUpload($event)" accept="image/*" class="hidden">
                            </label>
                        </template>
                        <div class="min-w-0 flex-1">
                            <input type="text" x-model="from_name" :readonly="viewMode === 'preview'" placeholder="Your Business Name" class="text-base sm:text-xl font-extrabold text-white bg-transparent border-0 border-b border-transparent focus:border-white/60 hover:border-white/30 rounded-lg px-2 py-0.5 transition w-full placeholder:text-white/50 focus:outline-none">
                            <input type="text" x-model="from_email" :readonly="viewMode === 'preview'" placeholder="billing@yourstudio.com" class="text-xs text-white/80 bg-transparent border-0 border-b border-transparent focus:border-white/60 hover:border-white/30 rounded-lg px-2 py-0.5 transition w-full placeholder:text-white/40 focus:outline-none">
                        </div>
                    </div>
                    <div class="w-full sm:w-auto sm:text-right space-y-1.5">
                        <input type="text" x-model="invoice_title" :readonly="viewMode === 'preview'" class="sm:text-right text-2xl sm:text-4xl font-black uppercase tracking-tight text-white bg-transparent border-0 border-b border-transparent focus:border-white/60 rounded-lg px-2 py-0.5 transition w-full placeholder:text-white/50 focus:outline-none" placeholder="INVOICE">
                        <div class="flex items-center sm:justify-end gap-2 text-xs font-semibold text-white/90 px-2 sm:px-0">
                            <span class="opacity-75">Invoice No.</span>
                            <input type="text" x-model="invoice_number" :readonly="viewMode === 'preview'" class="w-32 sm:w-36 sm:text-right font-bold text-white bg-white/15 border border-white/25 rounded-xl px-2.5 py-1 text-xs focus:ring-1 focus:ring-white focus:outline-none">
                        </div>
                    </div>
                </div>
            </template>

            <!-- Invoice Card Body -->
            <div :class="template_style === 'compact' ? 'p-4 sm:p-8 space-y-6' : 'p-4 sm:p-10 md:p-12 space-y-7 sm:space-y-10'">

                <!-- Zone 2: Logo Badge & Invoice Header Row (Shown for Modern, Classic, Minimal, Stripe, Compact, or Bold with custom cover) -->
                <div x-show="template_style !== 'bold' || cover_image"
                     class="flex flex-col sm:flex-row items-start justify-between gap-5 sm:gap-6 pb-5 sm:pb-6"
                     :class="{
                         'border-b border-slate-100': template_style === 'modern' || template_style === 'minimal' || template_style === 'bold',
                         'border-b-2 border-slate-900': template_style === 'classic',
                         'border-b-2': template_style === 'stripe' || template_style === 'compact'
                     }"
                     :style="(template_style === 'stripe' || template_style === 'compact') ? 'border-bottom-color:' + accent_color : ''">
                    <!-- Logo Upload / Display Area -->
                    <div class="flex items-center gap-3.5 sm:gap-4 w-full sm:flex-1 min-w-0">
                        <template x-if="logo_data">
                            <div class="relative group shrink-0">
                                <div class="w-16 h-16 sm:w-24 sm:h-24 border-2 border-slate-200/80 p-2 bg-white shadow-md flex items-center justify-center overflow-hidden"
                                     :class="template_style === 'classic' ? 'rounded-none' : 'rounded-2xl'">
                                    <img :src="logo_data" alt="Brand Logo" class="max-w-full max-h-full object-contain">
                                </div>
                                <button type="button" @click="logo_data = ''" class="no-print absolute -top-2 -right-2 bg-rose-600 hover:bg-rose-700 text-white rounded-full p-1 shadow-md transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>

                        <template x-if="!logo_data && viewMode === 'edit'">
                            <label class="no-print w-16 h-16 sm:w-24 sm:h-24 border-2 border-dashed border-slate-300 hover:border-teal-500 bg-slate-50 hover:bg-teal-50/40 p-2 flex flex-col items-center justify-center cursor-pointer text-center group transition shrink-0"
                                   :class="template_style === 'classic' ? 'rounded-none' : 'rounded-2xl'">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-slate-400 group-hover:text-teal-600 mb-0.5 sm:mb-1 transition" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                <span class="text-[10px] font-bold text-slate-600 group-hover:text-teal-700">Logo</span>
                                <input type="file" @change="handleLogoUpload($event)" accept="image/*" class="hidden">
                            </label>
                        </template>

                        <div class="min-w-0 flex-1">
                            <input type="text" x-model="from_name" :readonly="viewMode === 'preview'" placeholder="Your Business Name" class="text-base sm:text-xl font-extrabold text-slate-900 border-0 border-b border-transparent focus:border-teal-500 hover:border-slate-300 focus:bg-slate-50/50 rounded-lg px-2 py-1 transition w-full placeholder:text-slate-300 focus:outline-none">
                            <input type="text" x-model="from_email" :readonly="viewMode === 'preview'" placeholder="billing@yourstudio.com" class="text-xs text-slate-500 border-0 border-b border-transparent focus:border-teal-500 hover:border-slate-300 focus:bg-slate-50/50 rounded-lg px-2 py-0.5 transition w-full placeholder:text-slate-300 focus:outline-none">
                        </div>
                    </div>

                    <!-- Invoice Title & Number Badge -->
                    <div class="w-full sm:w-64 shrink-0 sm:text-right space-y-2">
                        <input type="text" x-model="invoice_title" :readonly="viewMode === 'preview'" class="sm:text-right text-2xl sm:text-4xl font-extrabold uppercase tracking-tight border-0 border-b border-transparent focus:border-teal-500 hover:border-slate-300 focus:bg-slate-50/50 rounded-lg px-2 py-1 transition w-full focus:outline-none" :style="'color: ' + accent_color" placeholder="INVOICE">
                        
                        <div class="flex items-center sm:justify-end gap-2 text-xs font-semibold text-slate-600 px-2 sm:px-0">
                            <span class="text-slate-400">Invoice No.</span>
                            <input type="text" x-model="invoice_number" :readonly="viewMode === 'preview'" class="w-32 sm:w-36 sm:text-right font-bold text-slate-900 bg-slate-100/70 border border-slate-200/80 px-2.5 py-1 text-xs focus:ring-1 focus:ring-teal-500 focus:outline-none" :class="template_style === 'classic' ? 'rounded-none' : 'rounded-xl'">
                        </div>
                    </div>
                </div>

                <!-- Zone 3: Dates, Currency & Financial Snapshot Ribbon -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 sm:gap-3 transition-all"
                     :class="{
                         'p-3.5 sm:p-5 rounded-2xl bg-slate-50/80 border border-slate-200/80': template_style === 'modern' || template_style === 'bold' || template_style === 'stripe',
                         'p-3.5 sm:p-4 rounded-none bg-slate-50 border border-slate-300': template_style === 'classic',
                         'py-4 px-1 bg-transparent border-y border-slate-200 rounded-none': template_style === 'minimal',
                         'p-3 sm:p-3.5 rounded-lg bg-slate-50 border border-slate-300': template_style === 'compact'
                     }">
                    <div class="min-w-0">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Issue Date</span>
                        <input type="date" x-model="issue_date" :readonly="viewMode === 'preview'" @change="onTermsChange()" class="w-full min-w-0 bg-white border border-slate-200 rounded-xl px-2 sm:px-2.5 py-1.5 text-xs font-semibold text-slate-800 focus:ring-1 focus:ring-teal-500 focus:outline-none">
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Payment Terms</span>
                        <select x-model="payment_terms" :disabled="viewMode === 'preview'" @change="onTermsChange()" class="w-full min-w-0 bg-white border border-slate-200 rounded-xl px-2 sm:px-2.5 py-1.5 text-xs font-semibold text-slate-800 focus:ring-1 focus:ring-teal-500 focus:outline-none">
                            <option value="Due on receipt">Due on receipt</option>
                            <option value="Net 7">Net 7 Days</option>
                            <option value="Net 14">Net 14 Days</option>
                            <option value="Net 30">Net 30 Days</option>
                            <option value="Net 60">Net 60 Days</option>
                        </select>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Due Date</span>
                        <input type="date" x-model="due_date" :readonly="viewMode === 'preview'" class="w-full min-w-0 bg-white border border-slate-200 rounded-xl px-2 sm:px-2.5 py-1.5 text-xs font-semibold text-slate-800 focus:ring-1 focus:ring-teal-500 focus:outline-none">
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-teal-700 mb-1 flex items-center gap-1" :style="'color:' + accent_color">
                            <span>Currency</span>
                            <span class="font-extrabold" x-text="'(' + currency_symbol + ')'"></span>
                        </span>
                        <select x-model="currency" :disabled="viewMode === 'preview'" @change="onCurrencyChange()" class="w-full min-w-0 bg-white border border-slate-300 rounded-xl px-2 sm:px-2.5 py-1.5 text-xs font-bold text-slate-900 focus:ring-2 focus:ring-teal-500 focus:outline-none shadow-sm">
                            <option value="USD|$">USD ($)</option>
                            <option value="EUR|€">EUR (€)</option>
                            <option value="GBP|£">GBP (£)</option>
                            <option value="CAD|$">CAD ($)</option>
                            <option value="AUD|$">AUD ($)</option>
                            <option value="INR|₹">INR (₹)</option>
                            <option value="PKR|Rs">PKR (Rs)</option>
                            <option value="AED|AED">AED (AED)</option>
                            <option value="SAR|SAR">SAR (SAR)</option>
                            <option value="JPY|¥">JPY (¥)</option>
                            <option value="CNY|¥">CNY (¥)</option>
                            <option value="CHF|CHF">CHF (CHF)</option>
                            <option value="SGD|$">SGD ($)</option>
                            <option value="NZD|$">NZD ($)</option>
                            <option value="BRL|R$">BRL (R$)</option>
                            <option value="MXN|$">MXN ($)</option>
                            <option value="ZAR|R">ZAR (R)</option>
                            <option value="TRY|₺">TRY (₺)</option>
                            <option value="SEK|kr">SEK (kr)</option>
                            <option value="NOK|kr">NOK (kr)</option>
                            <option value="THB|฿">THB (฿)</option>
                            <option value="IDR|Rp">IDR (Rp)</option>
                            <option value="MYR|RM">MYR (RM)</option>
                            <option value="PHP|₱">PHP (₱)</option>
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-1 flex items-center justify-between sm:block pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-200/80 min-w-0">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-1">Amount Due</span>
                        <div class="text-base sm:text-lg font-black tracking-tight tabular-nums" :style="'color: ' + accent_color" x-text="currency_symbol + formatNumber(balanceDue())"></div>
                    </div>
                </div>

                <!-- Zone 4: Clean Two-Column Client & Business Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-8 text-xs font-sans-inter">
                    <!-- From Details -->
                    <div class="space-y-2 transition-all"
                         :class="{
                             'p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm': template_style === 'modern' || template_style === 'bold' || template_style === 'stripe',
                             'p-4 rounded-none bg-white border border-slate-300': template_style === 'classic',
                             'py-2 px-0 border-0 shadow-none bg-transparent': template_style === 'minimal',
                             'p-3.5 rounded-lg bg-slate-50/50 border border-slate-200': template_style === 'compact'
                         }">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Billed From (You)</div>
                        <input type="text" x-model="from_phone" :readonly="viewMode === 'preview'" placeholder="Phone (e.g. +1 555-0199)" class="w-full border-0 border-b border-transparent focus:border-teal-500 hover:border-slate-300 focus:bg-slate-50/50 rounded-lg px-2 py-1 text-slate-700 placeholder:text-slate-300 focus:outline-none">
                        <textarea x-model="from_address" :readonly="viewMode === 'preview'" rows="2" placeholder="Street Address, City, State, Postal Code" class="w-full border-0 border-b border-transparent focus:border-teal-500 hover:border-slate-300 focus:bg-slate-50/50 rounded-lg px-2 py-1 text-slate-700 placeholder:text-slate-300 resize-none focus:outline-none"></textarea>
                        <input type="text" x-model="from_tax_id" :readonly="viewMode === 'preview'" placeholder="Tax / VAT / Business ID" class="w-full border-0 border-b border-transparent focus:border-teal-500 hover:border-slate-300 focus:bg-slate-50/50 rounded-lg px-2 py-1 text-slate-700 placeholder:text-slate-300 focus:outline-none">
                    </div>

                    <!-- Bill To (Client) Details -->
                    <div class="space-y-2 transition-all"
                         :class="{
                             'p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm': template_style === 'modern' || template_style === 'bold' || template_style === 'stripe',
                             'p-4 rounded-none bg-white border border-slate-300': template_style === 'classic',
                             'py-2 px-0 border-0 shadow-none bg-transparent': template_style === 'minimal',
                             'p-3.5 rounded-lg bg-slate-50/50 border border-slate-200': template_style === 'compact'
                         }">
                        <div class="text-[10px] font-bold uppercase tracking-widest mb-2" :style="'color:' + accent_color">Billed To (Client)</div>
                        <input type="text" x-model="to_name" :readonly="viewMode === 'preview'" placeholder="Client Name or Company *" class="w-full font-bold text-sm text-slate-900 border-0 border-b border-transparent focus:border-teal-500 hover:border-slate-300 focus:bg-slate-50/50 rounded-lg px-2 py-1 placeholder:text-slate-300 focus:outline-none">
                        <input type="email" x-model="to_email" :readonly="viewMode === 'preview'" placeholder="client@company.com" class="w-full border-0 border-b border-transparent focus:border-teal-500 hover:border-slate-300 focus:bg-slate-50/50 rounded-lg px-2 py-1 text-slate-700 placeholder:text-slate-300 focus:outline-none">
                        <textarea x-model="to_address" :readonly="viewMode === 'preview'" rows="2" placeholder="Client Billing Address" class="w-full border-0 border-b border-transparent focus:border-teal-500 hover:border-slate-300 focus:bg-slate-50/50 rounded-lg px-2 py-1 text-slate-700 placeholder:text-slate-300 resize-none focus:outline-none"></textarea>
                    </div>
                </div>

                <!-- Zone 5: Dynamic Itemized Billing Grid -->
                <div class="space-y-3">
                    <!-- Desktop & Print Table Layout -->
                    <div class="hidden sm:block print:block overflow-x-auto">
                        <table class="w-full border-collapse" :class="template_style === 'classic' ? 'border border-slate-300' : ''">
                            <thead>
                                <tr class="text-left text-[11px] font-bold uppercase tracking-wider transition-colors"
                                    :class="{
                                        'border-b border-slate-200 text-slate-400': template_style === 'modern' || template_style === 'minimal',
                                        'bg-slate-100 text-slate-800 border-y-2 border-slate-800': template_style === 'classic',
                                        'text-white rounded-lg': template_style === 'bold',
                                        'border-b-2 text-slate-600': template_style === 'stripe' || template_style === 'compact'
                                    }"
                                    :style="template_style === 'bold' ? 'background-color:' + accent_color : ((template_style === 'stripe' || template_style === 'compact') ? 'border-bottom-color:' + accent_color : '')">
                                    <th class="py-3 px-3 w-[50%]">Item Description</th>
                                    <th class="py-3 px-3 text-center w-[15%]">Quantity</th>
                                    <th class="py-3 px-3 text-right w-[18%]">Rate (<span x-text="currency_symbol"></span>)</th>
                                    <th class="py-3 px-3 text-right w-[17%]">Amount</th>
                                    <th class="no-print py-3 px-2 w-[5%]" x-show="viewMode === 'edit'"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-sans-inter">
                                <template x-for="(item, index) in items" :key="'desk-' + index">
                                    <tr class="group hover:bg-slate-50/50 transition" :class="template_style === 'compact' && index % 2 === 1 ? 'bg-slate-50/70' : ''">
                                        <td :class="[template_style === 'compact' ? 'py-2 px-3' : 'py-3 px-3', template_style === 'classic' ? 'border border-slate-200' : '']">
                                            <input type="text" x-model="item.description" :readonly="viewMode === 'preview'" placeholder="Service, product description, or milestone..." class="w-full border-0 border-b border-transparent focus:border-teal-500 hover:border-slate-300 focus:bg-slate-50/50 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-900 transition focus:outline-none">
                                        </td>
                                        <td :class="[template_style === 'compact' ? 'py-2 px-3' : 'py-3 px-3', template_style === 'classic' ? 'border border-slate-200' : '']">
                                            <input type="number" step="any" min="0" x-model.number="item.quantity" :readonly="viewMode === 'preview'" class="w-full text-center border-0 border-b border-transparent focus:border-teal-500 hover:border-slate-300 focus:bg-slate-50/50 rounded-lg px-2 py-1.5 text-sm text-slate-800 transition focus:outline-none">
                                        </td>
                                        <td :class="[template_style === 'compact' ? 'py-2 px-3' : 'py-3 px-3', template_style === 'classic' ? 'border border-slate-200' : '']">
                                            <input type="number" step="any" min="0" x-model.number="item.unit_price" :readonly="viewMode === 'preview'" class="w-full text-right border-0 border-b border-transparent focus:border-teal-500 hover:border-slate-300 focus:bg-slate-50/50 rounded-lg px-2 py-1.5 text-sm text-slate-800 transition focus:outline-none">
                                        </td>
                                        <td :class="[template_style === 'compact' ? 'py-2 px-3' : 'py-3 px-3', template_style === 'classic' ? 'border border-slate-200' : '']" class="text-right font-bold text-slate-900 tabular-nums text-sm">
                                            <span x-text="currency_symbol + formatNumber(item.quantity * item.unit_price)"></span>
                                        </td>
                                        <td class="no-print py-3 px-2 text-center" x-show="viewMode === 'edit'" :class="template_style === 'classic' ? 'border border-slate-200' : ''">
                                            <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-slate-300 hover:text-rose-600 transition p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile-Friendly Touch Line Item Cards -->
                    <div class="sm:hidden print:hidden space-y-3 font-sans-inter">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Line Items</div>
                        <template x-for="(item, index) in items" :key="'mob-' + index">
                            <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-200/90 space-y-3">
                                <div class="flex items-center gap-2">
                                    <input type="text" x-model="item.description" :readonly="viewMode === 'preview'" placeholder="Service or product description..." class="flex-1 bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-medium text-slate-900 focus:ring-1 focus:ring-teal-500 focus:outline-none">
                                    <button type="button" @click="removeItem(index)" x-show="viewMode === 'edit' && items.length > 1" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-3 gap-2 items-end">
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Qty</label>
                                        <input type="number" step="any" min="0" x-model.number="item.quantity" :readonly="viewMode === 'preview'" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs font-semibold text-slate-800 focus:ring-1 focus:ring-teal-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Rate (<span x-text="currency_symbol"></span>)</label>
                                        <input type="number" step="any" min="0" x-model.number="item.unit_price" :readonly="viewMode === 'preview'" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs font-semibold text-slate-800 focus:ring-1 focus:ring-teal-500 focus:outline-none">
                                    </div>
                                    <div class="text-right pb-1">
                                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Amount</span>
                                        <span class="text-sm font-extrabold text-slate-900 tabular-nums" x-text="currency_symbol + formatNumber(item.quantity * item.unit_price)"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="no-print pt-1 sm:pt-2" x-show="viewMode === 'edit'">
                        <button type="button" @click="addItem()" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Add Line Item
                        </button>
                    </div>
                </div>

                <!-- Zone 6: Notes & Totals Calculation (Bloom.io elevated split) -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 sm:gap-8 pt-6 border-t border-slate-200">
                    <!-- Left Column: Payment Link, Notes & Terms -->
                    <div class="md:col-span-7 space-y-5 text-xs font-sans-inter">
                        <!-- Online Payment Integration Link (Bloom highlight) -->
                        <div class="p-4 rounded-2xl bg-teal-50/70 border border-teal-200/80 space-y-2">
                            <div class="flex items-center gap-2 font-bold text-teal-900 text-xs">
                                <svg class="w-4 h-4 text-teal-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                                <span>Online Payment Link (Stripe, PayPal, Venmo, etc.)</span>
                            </div>
                            <input type="url" x-model="payment_link" :readonly="viewMode === 'preview'" placeholder="https://buy.stripe.com/... or paypal.me/yourbusiness" class="w-full bg-white border border-teal-200 rounded-xl px-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:ring-1 focus:ring-teal-500 focus:outline-none">
                            <p class="text-[10px] text-teal-700">When provided, a clickable "Pay Invoice Now" button appears on the client's public link.</p>
                        </div>

                        <!-- Payment Instructions / Bank Info -->
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Bank Transfer & Payment Details</label>
                            <textarea x-model="notes" :readonly="viewMode === 'preview'" rows="3" placeholder="Wire transfer info, IBAN, Swift code, or personal instructions..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-3 text-xs text-slate-800 focus:ring-1 focus:ring-teal-500 focus:outline-none resize-none"></textarea>
                        </div>

                        <!-- Terms & Conditions -->
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Terms & Conditions</label>
                            <textarea x-model="terms" :readonly="viewMode === 'preview'" rows="2" placeholder="Payment due within agreed timeframe. Thank you for your partnership!" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-3 text-xs text-slate-800 focus:ring-1 focus:ring-teal-500 focus:outline-none resize-none"></textarea>
                        </div>

                        <!-- Digital Signature (Bloom feature) -->
                        <div class="pt-1 sm:pt-2">
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Authorized Signature</label>
                                <button type="button" x-show="signature_data && viewMode === 'edit'" @click="signature_data = ''" class="no-print text-[10px] text-rose-600 font-bold hover:underline">Clear Signature</button>
                            </div>
                            
                            <template x-if="signature_data">
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl inline-block">
                                    <img :src="signature_data" alt="Signature" class="max-h-16 max-w-[200px] object-contain">
                                </div>
                            </template>

                            <template x-if="!signature_data && viewMode === 'edit'">
                                <div class="no-print space-y-2">
                                    <canvas id="signaturePad" width="320" height="90" class="w-full max-w-[320px] h-[90px] border border-slate-300 rounded-2xl bg-white touch-none cursor-crosshair shadow-inner"></canvas>
                                    <div class="flex items-center gap-3 text-[10px] text-slate-500">
                                        <button type="button" @click="saveCanvasSignature()" class="font-bold text-teal-700 hover:underline">Save Signature</button>
                                        <span>&bull;</span>
                                        <button type="button" @click="clearSignaturePad()" class="text-slate-500 hover:underline">Clear Pad</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Right Column: Totals Summary (Bloom.io Card) -->
                    <div class="md:col-span-5 bg-slate-50/80 border border-slate-200 rounded-3xl p-5 sm:p-6 space-y-3.5 font-sans-inter self-start">
                        <div class="flex items-center justify-between text-xs text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-900 tabular-nums" x-text="currency_symbol + formatNumber(subtotal())"></span>
                        </div>

                        <!-- Discount -->
                        <div class="flex items-center justify-between gap-3 text-xs text-slate-600">
                            <div class="flex items-center gap-1.5">
                                <span>Discount</span>
                                <div class="no-print inline-flex rounded-full border border-slate-200 p-0.5 bg-white text-[10px]" x-show="viewMode === 'edit'">
                                    <button type="button" @click="discount_type = 'percent'" class="px-2 py-0.5 rounded-full" :class="discount_type === 'percent' ? 'bg-slate-900 text-white font-bold' : 'text-slate-600'">%</button>
                                    <button type="button" @click="discount_type = 'fixed'" class="px-2 py-0.5 rounded-full" :class="discount_type === 'fixed' ? 'bg-slate-900 text-white font-bold' : 'text-slate-600'" x-text="currency_symbol"></button>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 justify-end">
                                <input type="number" step="any" min="0" x-model.number="discount_rate" :readonly="viewMode === 'preview'" class="w-16 text-right bg-white border border-slate-200 rounded-lg px-2 py-0.5 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-teal-500">
                                <span class="text-rose-600 font-bold tabular-nums" x-text="'-' + currency_symbol + formatNumber(discountAmount())"></span>
                            </div>
                        </div>

                        <!-- Tax / VAT -->
                        <div class="flex items-center justify-between gap-3 text-xs text-slate-600">
                            <span>Tax / VAT (%)</span>
                            <div class="flex items-center gap-1.5 justify-end">
                                <input type="number" step="any" min="0" x-model.number="tax_rate" :readonly="viewMode === 'preview'" class="w-16 text-right bg-white border border-slate-200 rounded-lg px-2 py-0.5 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-teal-500">
                                <span class="font-bold text-slate-800 tabular-nums" x-text="'+' + currency_symbol + formatNumber(taxAmount())"></span>
                            </div>
                        </div>

                        <!-- Shipping / Handling -->
                        <div class="flex items-center justify-between gap-3 text-xs text-slate-600">
                            <span>Shipping / Extra</span>
                            <div class="w-24">
                                <input type="number" step="any" min="0" x-model.number="shipping_fee" :readonly="viewMode === 'preview'" class="w-full text-right bg-white border border-slate-200 rounded-lg px-2 py-0.5 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-teal-500">
                            </div>
                        </div>

                        <!-- Grand Total -->
                        <div class="flex items-center justify-between text-sm font-extrabold text-slate-950 pt-3 border-t border-slate-200">
                            <span>Total</span>
                            <span class="tabular-nums" x-text="currency_symbol + formatNumber(total())"></span>
                        </div>

                        <!-- Amount Paid / Deposit -->
                        <div class="flex items-center justify-between gap-3 text-xs text-slate-600">
                            <span>Deposit / Paid</span>
                            <div class="w-24">
                                <input type="number" step="any" min="0" x-model.number="amount_paid" :readonly="viewMode === 'preview'" class="w-full text-right bg-white border border-slate-200 rounded-lg px-2 py-0.5 text-xs text-emerald-700 font-bold focus:outline-none focus:ring-1 focus:ring-teal-500">
                            </div>
                        </div>

                        <!-- Balance Due Highlight (Bloom style clean elevation) -->
                        <div class="p-4 rounded-2xl bg-white border-2 shadow-sm flex items-center justify-between" :style="'border-color:' + accent_color">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Balance Due</span>
                            <span class="text-lg font-black tracking-tight tabular-nums" :style="'color: ' + accent_color" x-text="currency_symbol + formatNumber(balanceDue())"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Floating Action Dock (Mobile-Adaptive + Bloom Desktop Floating Pill Bar) -->
    <div class="no-print fixed bottom-3 left-3 right-3 sm:left-1/2 sm:right-auto sm:bottom-6 sm:-translate-x-1/2 sm:w-max max-w-full z-40 bg-slate-950/92 backdrop-blur-xl border border-slate-800 text-white px-2.5 sm:px-5 py-2 sm:py-3 rounded-2xl sm:rounded-full shadow-2xl flex items-center justify-between sm:justify-center gap-1 sm:gap-2.5">

        <div class="flex items-center gap-0.5 sm:gap-1.5">
            <!-- Save Draft -->
            <button type="button" @click="saveInvoice()" :disabled="saving" class="inline-flex flex-col sm:flex-row items-center gap-0.5 sm:gap-1.5 px-2 sm:px-3 py-1.5 rounded-xl sm:rounded-full text-[10px] sm:text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition whitespace-nowrap">
                <svg x-show="!saving" class="w-4 h-4 text-teal-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <svg x-show="saving" class="w-4 h-4 animate-spin text-teal-400 shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <span class="hidden sm:inline" x-text="saving ? 'Saving...' : 'Save Draft'"></span>
                <span class="sm:hidden" x-text="saving ? 'Saving' : 'Save'"></span>
            </button>

            <!-- Share Link -->
            <button type="button" @click="shareLink()" class="inline-flex flex-col sm:flex-row items-center gap-0.5 sm:gap-1.5 px-2 sm:px-3 py-1.5 rounded-xl sm:rounded-full text-[10px] sm:text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition whitespace-nowrap">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                <span class="hidden sm:inline">Share Link</span>
                <span class="sm:hidden">Share</span>
            </button>

            <!-- Send Email -->
            <button type="button" @click="openEmailModal()" class="inline-flex flex-col sm:flex-row items-center gap-0.5 sm:gap-1.5 px-2 sm:px-3 py-1.5 rounded-xl sm:rounded-full text-[10px] sm:text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition whitespace-nowrap">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                <span class="hidden sm:inline">Send Email</span>
                <span class="sm:hidden">Email</span>
            </button>

            <!-- Print -->
            <button type="button" @click="window.print()" class="inline-flex flex-col sm:flex-row items-center gap-0.5 sm:gap-1.5 px-2 sm:px-3 py-1.5 rounded-xl sm:rounded-full text-[10px] sm:text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition whitespace-nowrap">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.75A2.25 2.25 0 0015 1.5H9a2.25 2.25 0 00-2.25 2.25v3.456"/></svg>
                <span>Print</span>
            </button>
        </div>

        <div class="hidden sm:block h-4 w-px bg-slate-800"></div>

        <!-- Download PDF Primary Pill -->
        <button type="button" @click="downloadPdf()" :disabled="downloading" class="inline-flex items-center gap-1.5 sm:gap-2 px-3.5 sm:px-5 py-2 sm:py-2 rounded-xl sm:rounded-full text-xs font-extrabold text-slate-950 bg-gradient-to-r from-teal-400 to-emerald-400 hover:from-teal-300 hover:to-emerald-300 shadow-md shadow-teal-500/20 transition transform active:scale-95 disabled:opacity-50 whitespace-nowrap shrink-0">
            <svg x-show="!downloading" class="w-4 h-4 text-slate-950 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            <svg x-show="downloading" class="w-4 h-4 animate-spin text-slate-950 shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
            <span class="hidden sm:inline" x-text="downloading ? 'Exporting...' : 'Download PDF'"></span>
            <span class="sm:hidden" x-text="downloading ? 'PDF...' : 'PDF'"></span>
        </button>
    </div>

    <!-- Email Modal (Clean Centered Overlay with Proper Z-Index) -->
    <div x-cloak x-show="emailModalOpen"
         x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="no-print fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true"
         @keydown.escape.window="emailModalOpen = false">
        <div x-show="emailModalOpen"
             @click.outside="emailModalOpen = false"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative z-10 w-full max-w-md bg-white rounded-3xl text-left overflow-hidden shadow-2xl border border-slate-200 p-6 sm:p-8 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 id="modal-title" class="text-base font-bold text-slate-900">Email Invoice to Client</h3>
                <button type="button" @click="emailModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="space-y-3 font-sans-inter text-xs">
                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">To Email Address *</label>
                    <input type="email" x-model="emailForm.to_email" placeholder="client@example.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:bg-white focus:ring-2 focus:ring-teal-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Subject</label>
                    <input type="text" x-model="emailForm.subject" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:bg-white focus:ring-2 focus:ring-teal-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Personal Note</label>
                    <textarea x-model="emailForm.message" rows="3" placeholder="Thank you for your business! Please find your invoice attached..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:bg-white focus:ring-2 focus:ring-teal-500 focus:outline-none resize-none"></textarea>
                </div>
            </div>
            <div class="pt-4 flex items-center justify-end gap-3">
                <button type="button" @click="emailModalOpen = false" class="px-4 py-2 rounded-full text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">Cancel</button>
                <button type="button" @click="sendEmailSubmit()" :disabled="sendingEmail" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold text-white bg-slate-900 hover:bg-[#0066FF] transition disabled:opacity-50">
                    <svg x-show="sendingEmail" class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span x-text="sendingEmail ? 'Sending...' : 'Send Invoice'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- History Drawer (Slide-over) -->
    <div x-cloak x-data="{ open: false }" @open-history-drawer.window="open = true" x-show="open" class="no-print fixed inset-0 z-50 overflow-hidden" @keydown.escape.window="open = false">
        <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-xs transition-opacity" @click="open = false"></div>
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-4 sm:pl-10">
            <div class="relative z-10 w-full max-w-md bg-white border-l border-border-subtle shadow-2xl flex flex-col">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Saved Invoices</h3>
                        <p class="text-xs text-slate-500">
                            @auth Synced to {{ Auth::user()->name }} @else Recent documents from this session @endauth
                        </p>
                    </div>
                    <button type="button" @click="open = false" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-3 font-sans-inter">
                    @guest
                    <div class="p-4 rounded-2xl bg-blue-50/80 border border-blue-200/80 space-y-3 mb-4">
                        <div class="text-xs font-bold text-slate-900">Want permanent access to your history?</div>
                        <p class="text-[11px] text-slate-600 leading-relaxed">
                            Signing in is optional for creating PDFs, but logging in syncs your saved documents permanently across devices.
                        </p>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="flex-1 text-center px-3 py-2 rounded-xl bg-slate-900 hover:bg-[#0066FF] text-white text-xs font-bold transition">Login</a>
                            <a href="{{ route('register') }}" class="flex-1 text-center px-3 py-2 rounded-xl bg-white border border-blue-200 hover:border-[#0066FF] text-[#0066FF] text-xs font-bold transition">Sign Up Free</a>
                        </div>
                    </div>
                    @endguest

                    @auth
                    <div class="mb-3 flex items-center justify-between px-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Your Recent Documents</span>
                        <a href="{{ route('invoice.history.page') }}" class="text-xs font-bold text-[#0066FF] hover:underline">Full History &rarr;</a>
                    </div>
                    @endauth

                    @forelse($recentInvoices as $past)
                    <div class="p-4 rounded-2xl border border-slate-200 hover:border-teal-500 hover:shadow-md transition bg-slate-50/50">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-bold text-sm text-slate-900">#{{ $past->invoice_number }}</span>
                            <span class="text-xs font-bold text-teal-700">{{ $past->currency_symbol }}{{ number_format($past->total, 2) }}</span>
                        </div>
                        <div class="text-xs text-slate-500 mb-3">
                            To: {{ $past->to_name ?: 'Unnamed Client' }} &bull; {{ $past->created_at->diffForHumans() }}
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('invoice.studio', ['load' => $past->uuid]) }}" class="px-3 py-1 bg-white border border-slate-300 rounded-full text-xs font-semibold text-slate-700 hover:border-teal-500 hover:text-teal-700 transition">
                                Edit
                            </a>
                            <a href="{{ route('invoice.pdf', $past->uuid) }}" class="px-3 py-1 bg-white border border-slate-300 rounded-full text-xs font-semibold text-slate-700 hover:border-teal-500 hover:text-teal-700 transition">
                                PDF
                            </a>
                            <a href="{{ route('invoice.show', $past->uuid) }}" target="_blank" class="px-3 py-1 bg-white border border-slate-300 rounded-full text-xs font-semibold text-slate-700 hover:border-teal-500 hover:text-teal-700 transition">
                                View
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 text-slate-400 text-xs">
                        @auth
                            No saved invoices yet. Click "Save Draft" or "Download PDF" to store your first document!
                        @else
                            No documents saved in this browser session yet.
                        @endauth
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Template & Brand Color Slide-Over Drawer -->
    <div x-cloak x-show="templateDrawerOpen" class="no-print fixed inset-0 z-50 overflow-hidden" @keydown.escape.window="templateDrawerOpen = false">
        <div class="absolute inset-0 bg-slate-950/45 backdrop-blur-xs transition-opacity" @click="templateDrawerOpen = false"></div>
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-4 sm:pl-10">
            <div class="w-full max-w-md bg-white border-l border-border-subtle shadow-2xl flex flex-col">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Customize Template</h3>
                        <p class="text-xs text-slate-500">Switch visual layout, accent color, or load a preset</p>
                    </div>
                    <button type="button" @click="templateDrawerOpen = false" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Drawer Tabs -->
                <div class="px-6 pt-3 pb-2 border-b border-slate-100 flex gap-2">
                    <button type="button" @click="templateTab = 'design'" class="px-3.5 py-1.5 rounded-full text-xs font-bold transition" :class="templateTab === 'design' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                        Layout & Color
                    </button>
                    <button type="button" @click="templateTab = 'presets'" class="px-3.5 py-1.5 rounded-full text-xs font-bold transition" :class="templateTab === 'presets' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                        Industry Presets
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    <!-- Tab 1: Layout Styles & Custom Accent Color -->
                    <div x-show="templateTab === 'design'" class="space-y-6">
                        <!-- Custom Accent Color Picker -->
                        <div>
                            <div class="flex items-center justify-between mb-2.5">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Brand Accent Color</label>
                                <span class="text-[11px] font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded" x-text="accent_color"></span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2.5">
                                <template x-for="clr in templateColors" :key="clr.hex">
                                    <button type="button" @click="accent_color = clr.hex; autoSaveLocalStorage();"
                                            class="w-8 h-8 rounded-full border-2 border-white shadow-sm transition transform hover:scale-110"
                                            :class="accent_color.toLowerCase() === clr.hex.toLowerCase() ? 'ring-2 ring-slate-900 scale-110' : ''"
                                            :style="'background-color:' + clr.hex"
                                            :title="clr.name"></button>
                                </template>
                                <label class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-full border border-slate-200 bg-slate-50 hover:bg-slate-100 cursor-pointer text-[11px] font-bold text-slate-700" title="Custom Hex Color">
                                    <input type="color" x-model="accent_color" @input="autoSaveLocalStorage()" class="w-4 h-4 rounded-full border-0 p-0 bg-transparent cursor-pointer">
                                    <span>Custom</span>
                                </label>
                            </div>
                        </div>

                        <!-- 6 Visual Layout Templates -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Select Document Layout</label>
                            <div class="grid grid-cols-2 gap-3">
                                <template x-for="(style, key) in templateStyles" :key="key">
                                    <button type="button" @click="selectTemplateStyle(key)"
                                            class="text-left p-3 rounded-2xl border-2 transition flex flex-col justify-between group"
                                            :class="template_style === key ? 'border-[#0066FF] bg-blue-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                        <div class="w-full">
                                            <!-- Miniature Wireframe Preview -->
                                            <div class="h-14 w-full rounded-lg bg-slate-50 border border-slate-200/80 mb-2 overflow-hidden relative p-2">
                                                <template x-if="key === 'modern'">
                                                    <div>
                                                        <div class="h-2.5 w-full bg-gradient-to-r from-slate-800 to-teal-700 rounded-sm mb-1.5"></div>
                                                        <div class="h-1.5 w-1/2 rounded-full mb-1" :style="'background-color:' + accent_color"></div>
                                                        <div class="h-1 w-3/4 bg-slate-200 rounded-full"></div>
                                                    </div>
                                                </template>
                                                <template x-if="key === 'classic'">
                                                    <div>
                                                        <div class="border-b-2 border-slate-800 pb-1 mb-1 flex justify-between">
                                                            <div class="h-1.5 w-6 bg-slate-700"></div>
                                                            <div class="h-1.5 w-4" :style="'background-color:' + accent_color"></div>
                                                        </div>
                                                        <div class="border border-slate-300 h-5 w-full grid grid-cols-3 gap-px bg-slate-200">
                                                            <div class="bg-white"></div><div class="bg-white"></div><div class="bg-white"></div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <template x-if="key === 'bold'">
                                                    <div>
                                                        <div class="h-5 w-full rounded-sm mb-1.5 flex items-center px-1.5" :style="'background-color:' + accent_color">
                                                            <div class="h-1.5 w-6 bg-white/90 rounded-full"></div>
                                                        </div>
                                                        <div class="h-1.5 w-2/3 bg-slate-200 rounded-full mb-1"></div>
                                                        <div class="h-1.5 w-1/2 bg-slate-200 rounded-full"></div>
                                                    </div>
                                                </template>
                                                <template x-if="key === 'minimal'">
                                                    <div>
                                                        <div class="h-1.5 w-8 bg-slate-800 rounded-full mb-2 mt-1"></div>
                                                        <div class="h-px w-full bg-slate-200 mb-1.5"></div>
                                                        <div class="h-1 w-full bg-slate-100 rounded-full mb-1"></div>
                                                        <div class="h-1 w-2/3 bg-slate-100 rounded-full"></div>
                                                    </div>
                                                </template>
                                                <template x-if="key === 'stripe'">
                                                    <div>
                                                        <div class="absolute left-0 top-0 bottom-0 w-2" :style="'background-color:' + accent_color"></div>
                                                        <div class="pl-2.5 pt-1">
                                                            <div class="h-1.5 w-7 rounded-full mb-1.5" :style="'background-color:' + accent_color"></div>
                                                            <div class="h-1.5 w-full bg-slate-200 rounded mb-1"></div>
                                                            <div class="h-1.5 w-3/4 bg-slate-100 rounded"></div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <template x-if="key === 'compact'">
                                                    <div>
                                                        <div class="flex justify-between mb-1">
                                                            <div class="h-1.5 w-5 bg-slate-700"></div>
                                                            <div class="h-1.5 w-5" :style="'background-color:' + accent_color"></div>
                                                        </div>
                                                        <div class="space-y-1">
                                                            <div class="h-1.5 w-full bg-slate-200"></div>
                                                            <div class="h-1.5 w-full bg-slate-100"></div>
                                                            <div class="h-1.5 w-full bg-slate-200"></div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="font-bold text-xs text-slate-900" x-text="style.name"></span>
                                                <span x-show="template_style === key" class="text-[10px] font-bold text-[#0066FF]">Active</span>
                                            </div>
                                            <div class="text-[10px] text-slate-500 mt-0.5 leading-snug" x-text="style.tagline"></div>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Quick Industry Starters -->
                    <div x-show="templateTab === 'presets'" class="space-y-3">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-slate-500">Load sample structure &amp; line items:</span>
                            <a href="{{ route('templates.index') }}" class="text-xs font-bold text-[#0066FF] hover:underline">Full Gallery &rarr;</a>
                        </div>
                        <template x-for="tpl in allTemplates" :key="tpl.slug">
                            <button type="button" @click="applyIndustryTemplate(tpl)" class="w-full text-left p-3.5 rounded-2xl border border-slate-200 hover:border-[#0066FF] hover:bg-blue-50/30 transition flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-[10px] font-bold uppercase tracking-wider" :style="'color:' + tpl.accent_color" x-text="tpl.category_label"></div>
                                    <div class="font-bold text-xs text-slate-900 mt-0.5" x-text="tpl.name"></div>
                                    <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5" x-text="tpl.description"></div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold shrink-0">Apply</span>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                    <a href="{{ route('templates.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition">Browse 20+ Templates</a>
                    <button type="button" @click="templateDrawerOpen = false" class="px-5 py-2 rounded-full bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">Done</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bloom.io Marketing & Educational Sections (Below the Generator) -->
    <section id="features" class="no-print mt-16 sm:mt-24 max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14">
            <span class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#0066FF] mb-2 block">Built for Creators &amp; Modern Businesses</span>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-[#111111] tracking-[-0.03em]">Everything you need to invoice with confidence</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-8">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-black/[0.08] shadow-sm hover:shadow-md hover:-translate-y-0.5 transition space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-[#F8FAFC] text-[#0066FF] flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#111111]">Custom Cover Photos &amp; Logo</h3>
                <p class="text-xs text-black/60 font-sans-inter leading-relaxed">
                    Make your invoices stand out like high-end design proposals with header banners, custom color palettes, and company branding.
                </p>
            </div>

            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-black/[0.08] shadow-sm hover:shadow-md hover:-translate-y-0.5 transition space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-[#F8FAFC] text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#111111]">Instant Online Payments</h3>
                <p class="text-xs text-black/60 font-sans-inter leading-relaxed">
                    Connect your Stripe, PayPal, or Venmo link. Clients can open their shareable invoice link and pay you with a single tap.
                </p>
            </div>

            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-black/[0.08] shadow-sm hover:shadow-md hover:-translate-y-0.5 transition space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-[#F8FAFC] text-[#FF325A] flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#111111]">Digital Signature Pad</h3>
                <p class="text-xs text-black/60 font-sans-inter leading-relaxed">
                    Sign directly on your screen. Captured signatures are embedded into both the live web view and the downloaded PDF.
                </p>
            </div>
        </div>
    </section>

    <!-- FAQ Accordion (Bloom.io style) -->
    <section id="faq" class="no-print mt-16 sm:mt-24 max-w-4xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-8 sm:mb-12">
            <span class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#0066FF] mb-2 block">Common Questions</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111111] tracking-[-0.03em]">Frequently Asked Questions</h2>
        </div>

        <div class="space-y-3 sm:space-y-4 font-sans-inter" x-data="{ active: 1 }">
            <div class="bg-white rounded-2xl border border-black/[0.08] p-5 cursor-pointer hover:border-black/25 transition" @click="active = (active === 1 ? null : 1)">
                <div class="flex items-center justify-between gap-4 font-bold text-sm text-[#111111]">
                    <span>Can I customize the {{ strtolower($config['title'] ?? 'document') }} template?</span>
                    <svg class="w-4 h-4 text-black/45 transition-transform duration-200 shrink-0" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div x-show="active === 1" x-transition class="mt-3 text-xs text-black/60 leading-relaxed">
                    Yes. Edit labels, add your logo, change currency, and adjust line items before downloading or printing your {{ strtolower($config['title'] ?? 'document') }}.
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-black/[0.08] p-5 cursor-pointer hover:border-black/25 transition" @click="active = (active === 2 ? null : 2)">
                <div class="flex items-center justify-between gap-4 font-bold text-sm text-[#111111]">
                    <span>Is this {{ $config['name'] ?? 'Generator' }} completely free?</span>
                    <svg class="w-4 h-4 text-black/45 transition-transform duration-200 shrink-0" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div x-show="active === 2" x-transition class="mt-3 text-xs text-black/60 leading-relaxed">
                    Yes! You can generate unlimited {{ strtolower($config['title'] ?? 'document') }}s, download crisp PDFs, and share links without any watermarks or hidden charges.
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-black/[0.08] p-5 cursor-pointer hover:border-black/25 transition" @click="active = (active === 3 ? null : 3)">
                <div class="flex items-center justify-between gap-4 font-bold text-sm text-[#111111]">
                    <span>Will I lose my draft if I refresh?</span>
                    <svg class="w-4 h-4 text-black/45 transition-transform duration-200 shrink-0" :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div x-show="active === 3" x-transition class="mt-3 text-xs text-black/60 leading-relaxed">
                    No. Formnexa automatically caches your in-progress work locally in your browser, so you can resume editing at any time.
                </div>
            </div>
        </div>
    </section>

</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('bloomInvoiceEditor', (initialData, config = null, loadedTemplate = null, templateStyles = {}, templateColors = [], allTemplates = []) => ({
        viewMode: 'edit', // 'edit' or 'preview'
        templateDrawerOpen: false,
        templateTab: 'design',
        templateStyles: templateStyles || {},
        templateColors: templateColors || [],
        allTemplates: allTemplates || [],
        template_style: initialData?.template_style || loadedTemplate?.template_style || 'modern',

        uuid: initialData?.uuid || '',
        invoice_title: initialData?.invoice_title || loadedTemplate?.invoice_title || config?.title || 'INVOICE',
        invoice_number: initialData?.invoice_number || (((loadedTemplate?.prefix || config?.prefix || 'INV-')) + Math.floor(1000 + Math.random() * 9000)),
        currency: (initialData?.currency || 'USD') + '|' + (initialData?.currency_symbol || '$'),
        currency_code: initialData?.currency || 'USD',
        currency_symbol: initialData?.currency_symbol || '$',
        issue_date: initialData?.issue_date ? initialData.issue_date.split('T')[0] : new Date().toISOString().split('T')[0],
        due_date: initialData?.due_date ? initialData.due_date.split('T')[0] : '',
        payment_terms: initialData?.payment_terms || loadedTemplate?.payment_terms || config?.terms || 'Due on receipt',
        status: initialData?.status || (config?.is_paid ? 'paid' : 'draft'),
        po_number: initialData?.po_number || '',

        from_name: initialData?.from_name || loadedTemplate?.from_name || '',
        from_email: initialData?.from_email || loadedTemplate?.from_email || '',
        from_phone: initialData?.from_phone || '',
        from_address: initialData?.from_address || loadedTemplate?.from_address || '',
        from_tax_id: initialData?.from_tax_id || '',

        to_name: initialData?.to_name || loadedTemplate?.to_name || '',
        to_email: initialData?.to_email || loadedTemplate?.to_email || '',
        to_phone: initialData?.to_phone || '',
        to_address: initialData?.to_address || loadedTemplate?.to_address || '',
        to_tax_id: initialData?.to_tax_id || '',

        items: initialData?.items && initialData.items.length > 0 
            ? initialData.items.map(i => ({ description: i.description, quantity: parseFloat(i.quantity) || 1, unit_price: parseFloat(i.unit_price) || 0 })) 
            : (loadedTemplate?.items && loadedTemplate.items.length > 0
                ? loadedTemplate.items.map(i => ({ description: i.description, quantity: parseFloat(i.quantity) || 1, unit_price: parseFloat(i.unit_price) || 0 }))
                : [{ description: '', quantity: 1, unit_price: 0 }]),

        discount_type: initialData?.discount_type || 'percent',
        discount_rate: parseFloat(initialData?.discount_rate) || 0,
        tax_rate: parseFloat(initialData?.tax_rate ?? loadedTemplate?.tax_rate) || 0,
        shipping_fee: parseFloat(initialData?.shipping_fee) || 0,
        amount_paid: parseFloat(initialData?.amount_paid) || 0,

        notes: initialData?.notes || loadedTemplate?.notes || '',
        terms: initialData?.terms || loadedTemplate?.terms || '',
        logo_data: initialData?.logo_data || '',
        cover_image: initialData?.cover_image || '',
        signature_data: initialData?.signature_data || '',
        payment_link: initialData?.payment_link || '',
        accent_color: initialData?.accent_color || loadedTemplate?.accent_color || '#0d9488',

        saving: false,
        downloading: false,
        emailModalOpen: false,
        sendingEmail: false,
        emailForm: {
            to_email: '',
            subject: '',
            message: '',
        },

        toast: {
            visible: false,
            message: '',
            type: 'success',
        },

        init() {
            const urlParams = new URLSearchParams(window.location.search);
            const urlStyle = urlParams.get('style');
            const urlColor = urlParams.get('color');

            if (!initialData && !loadedTemplate) {
                this.loadFromLocalStorage();
                if (config) {
                    if (config.title) this.invoice_title = config.title;
                    if (config.terms) this.payment_terms = config.terms;
                    if (config.is_paid) {
                        this.status = 'paid';
                        this.amount_paid = this.total();
                    }
                }
            } else if (loadedTemplate) {
                if (urlStyle && this.templateStyles[urlStyle]) {
                    this.template_style = urlStyle;
                }
                if (urlColor && /^#[0-9A-Fa-f]{6}$/.test(urlColor)) {
                    this.accent_color = urlColor;
                }
                this.showToast(`Loaded template: ${loadedTemplate.name}`);
            }

            if (!this.due_date) {
                this.onTermsChange();
            }
            this.$watch('items', () => {
                if (config?.is_paid) {
                    this.amount_paid = this.total();
                }
                this.autoSaveLocalStorage();
            }, { deep: true });
            this.$watch('from_name', () => this.autoSaveLocalStorage());
            this.$watch('to_name', () => this.autoSaveLocalStorage());
            this.$watch('cover_image', () => this.autoSaveLocalStorage());
            this.$watch('payment_link', () => this.autoSaveLocalStorage());
            this.$watch('template_style', () => this.autoSaveLocalStorage());
            this.$watch('accent_color', () => this.autoSaveLocalStorage());

            this.$nextTick(() => {
                this.initSignatureCanvas();
            });
        },

        selectTemplateStyle(styleKey) {
            this.template_style = styleKey;
            this.autoSaveLocalStorage();
            const label = this.templateStyles[styleKey]?.name || styleKey;
            this.showToast(`Switched layout to ${label}`);
        },

        applyIndustryTemplate(tpl) {
            if (!tpl) return;
            this.template_style = tpl.template_style || 'modern';
            this.accent_color = tpl.accent_color || '#0d9488';
            this.invoice_title = tpl.invoice_title || 'INVOICE';
            this.invoice_number = (tpl.prefix || 'INV-') + Math.floor(1000 + Math.random() * 9000);
            this.payment_terms = tpl.payment_terms || 'Net 14';
            this.tax_rate = parseFloat(tpl.tax_rate) || 0;
            this.from_name = tpl.from_name || '';
            this.from_email = tpl.from_email || '';
            this.from_address = tpl.from_address || '';
            this.to_name = tpl.to_name || '';
            this.to_email = tpl.to_email || '';
            this.to_address = tpl.to_address || '';
            this.notes = tpl.notes || '';
            this.terms = tpl.terms || '';
            if (tpl.items && tpl.items.length > 0) {
                this.items = tpl.items.map(i => ({
                    description: i.description,
                    quantity: parseFloat(i.quantity) || 1,
                    unit_price: parseFloat(i.unit_price) || 0
                }));
            }
            this.onTermsChange();
            this.autoSaveLocalStorage();
            this.templateDrawerOpen = false;
            this.showToast(`Applied template: ${tpl.name}`);
        },

        initSignatureCanvas() {
            const canvas = document.getElementById('signaturePad');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let drawing = false;

            const getPos = (e) => {
                const rect = canvas.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                const scaleX = canvas.width / (rect.width || canvas.width);
                const scaleY = canvas.height / (rect.height || canvas.height);
                return {
                    x: (clientX - rect.left) * scaleX,
                    y: (clientY - rect.top) * scaleY
                };
            };

            const startDraw = (e) => {
                drawing = true;
                const pos = getPos(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#0f172a';
            };

            const draw = (e) => {
                if (!drawing) return;
                const pos = getPos(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
            };

            const stopDraw = () => {
                drawing = false;
            };

            canvas.addEventListener('mousedown', startDraw);
            canvas.addEventListener('mousemove', draw);
            window.addEventListener('mouseup', stopDraw);

            canvas.addEventListener('touchstart', (e) => { e.preventDefault(); startDraw(e); }, { passive: false });
            canvas.addEventListener('touchmove', (e) => { e.preventDefault(); draw(e); }, { passive: false });
            canvas.addEventListener('touchend', stopDraw);
        },

        saveCanvasSignature() {
            const canvas = document.getElementById('signaturePad');
            if (!canvas) return;
            this.signature_data = canvas.toDataURL('image/png');
            this.showToast('Signature saved');
        },

        clearSignaturePad() {
            const canvas = document.getElementById('signaturePad');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            this.signature_data = '';
        },

        setPresetCover(type) {
            if (type === 'emerald') {
                this.cover_image = 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1200&q=80';
            } else if (type === 'abstract') {
                this.cover_image = 'https://images.unsplash.com/photo-1634017839464-5c339ebe3cb4?auto=format&fit=crop&w=1200&q=80';
            } else if (type === 'dark') {
                this.cover_image = 'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?auto=format&fit=crop&w=1200&q=80';
            }
            this.showToast('Cover preset applied');
        },

        handleCoverUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.cover_image = e.target.result;
                this.showToast('Cover image updated');
            };
            reader.readAsDataURL(file);
        },

        handleLogoUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.logo_data = e.target.result;
                this.showToast('Logo updated');
            };
            reader.readAsDataURL(file);
        },

        showToast(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.visible = true;
            setTimeout(() => {
                this.toast.visible = false;
            }, 3500);
        },

        onCurrencyChange() {
            if (!this.currency) return;
            const parts = this.currency.split('|');
            this.currency_code = parts[0] || 'USD';
            this.currency_symbol = parts[1] || '$';
            this.autoSaveLocalStorage();
        },

        onTermsChange() {
            if (!this.issue_date) return;
            const date = new Date(this.issue_date);
            if (this.payment_terms === 'Due on receipt') {
                this.due_date = this.issue_date;
            } else if (this.payment_terms === 'Net 7') {
                date.setDate(date.getDate() + 7);
                this.due_date = date.toISOString().split('T')[0];
            } else if (this.payment_terms === 'Net 14') {
                date.setDate(date.getDate() + 14);
                this.due_date = date.toISOString().split('T')[0];
            } else if (this.payment_terms === 'Net 30') {
                date.setDate(date.getDate() + 30);
                this.due_date = date.toISOString().split('T')[0];
            } else if (this.payment_terms === 'Net 60') {
                date.setDate(date.getDate() + 60);
                this.due_date = date.toISOString().split('T')[0];
            }
        },

        addItem() {
            this.items.push({ description: '', quantity: 1, unit_price: 0 });
        },

        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },

        subtotal() {
            return this.items.reduce((sum, item) => sum + ((parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0)), 0);
        },

        discountAmount() {
            const sub = this.subtotal();
            if (this.discount_type === 'percent') {
                return (sub * (parseFloat(this.discount_rate) || 0)) / 100;
            }
            return Math.min(parseFloat(this.discount_rate) || 0, sub);
        },

        taxAmount() {
            const taxable = Math.max(0, this.subtotal() - this.discountAmount());
            return (taxable * (parseFloat(this.tax_rate) || 0)) / 100;
        },

        total() {
            const total = this.subtotal() - this.discountAmount() + this.taxAmount() + (parseFloat(this.shipping_fee) || 0);
            return Math.max(0, total);
        },

        balanceDue() {
            const bal = this.total() - (parseFloat(this.amount_paid) || 0);
            return Math.max(0, bal);
        },

        formatNumber(num) {
            return Number(num || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        getPayload() {
            return {
                uuid: this.uuid,
                invoice_number: this.invoice_number,
                invoice_title: this.invoice_title,
                currency: this.currency_code,
                currency_symbol: this.currency_symbol,
                currency_pair: this.currency,
                issue_date: this.issue_date,
                due_date: this.due_date,
                payment_terms: this.payment_terms,
                po_number: this.po_number,
                from_name: this.from_name,
                from_email: this.from_email,
                from_phone: this.from_phone,
                from_address: this.from_address,
                from_tax_id: this.from_tax_id,
                to_name: this.to_name,
                to_email: this.to_email,
                to_phone: this.to_phone,
                to_address: this.to_address,
                to_tax_id: this.to_tax_id,
                items: this.items,
                discount_type: this.discount_type,
                discount_rate: this.discount_rate,
                tax_rate: this.tax_rate,
                shipping_fee: this.shipping_fee,
                amount_paid: this.amount_paid,
                notes: this.notes,
                terms: this.terms,
                logo_data: this.logo_data,
                cover_image: this.cover_image,
                signature_data: this.signature_data,
                payment_link: this.payment_link,
                accent_color: this.accent_color,
                template_style: this.template_style || 'modern',
            };
        },

        async saveInvoice(callback = null) {
            this.saving = true;
            try {
                const response = await fetch("{{ route('invoice.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(this.getPayload()),
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    this.uuid = data.uuid;
                    this.showToast(data.message || 'Invoice saved successfully!');
                    if (callback) await callback(data);
                    return data;
                } else {
                    this.showToast(data.message || 'Validation failed. Please check inputs.', 'error');
                    return null;
                }
            } catch (err) {
                console.error(err);
                this.showToast('Failed to save invoice. Please try again.', 'error');
                return null;
            } finally {
                this.saving = false;
            }
        },

        async downloadPdf() {
            this.downloading = true;
            try {
                const data = await this.saveInvoice();
                if (data && data.pdf_url) {
                    window.location.href = data.pdf_url;
                }
            } finally {
                this.downloading = false;
            }
        },

        openEmailModal() {
            this.emailForm.to_email = this.to_email;
            this.emailForm.subject = `Invoice #${this.invoice_number} from ${this.from_name || 'Formnexa'}`;
            this.emailForm.message = `Hi ${this.to_name || 'there'},\n\nPlease review your invoice #${this.invoice_number} for ${this.currency_symbol}${this.formatNumber(this.balanceDue())}.\n\nThank you for your business!`;
            this.emailModalOpen = true;
        },

        async sendEmailSubmit() {
            if (!this.emailForm.to_email) {
                this.showToast('Please enter a recipient email address.', 'error');
                return;
            }

            this.sendingEmail = true;
            try {
                const data = await this.saveInvoice();
                if (!data) {
                    return;
                }

                const emailUrl = data.email_url || ("{{ url('/invoice') }}/" + data.uuid + "/email");
                const response = await fetch(emailUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(this.emailForm),
                });

                const res = await response.json();
                if (response.ok && res.success) {
                    this.emailModalOpen = false;
                    this.showToast(res.message);
                } else {
                    this.showToast(res.message || 'Failed to send email.', 'error');
                }
            } catch (e) {
                console.error(e);
                this.showToast('Failed to connect to mail server.', 'error');
            } finally {
                this.sendingEmail = false;
            }
        },

        shareLink() {
            this.saveInvoice((data) => {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(data.view_url);
                    this.showToast('Shareable link copied to clipboard!');
                } else {
                    prompt('Shareable invoice link:', data.view_url);
                }
            });
        },

        autoSaveLocalStorage() {
            try {
                const key = 'formnexa_draft_' + (config?.slug || 'invoice-generator');
                localStorage.setItem(key, JSON.stringify(this.getPayload()));
            } catch (e) {}
        },

        loadFromLocalStorage() {
            try {
                const slug = config?.slug || 'invoice-generator';
                const key = 'formnexa_draft_' + slug;
                const legacyKey = 'invoiceflow_bloom_draft_' + slug;
                const saved = localStorage.getItem(key) || localStorage.getItem(legacyKey);
                if (saved) {
                    const parsed = JSON.parse(saved);
                    if (parsed.from_name || (parsed.items && parsed.items.length > 0 && parsed.items[0].description)) {
                        this.from_name = parsed.from_name || '';
                        this.from_email = parsed.from_email || '';
                        this.from_phone = parsed.from_phone || '';
                        this.from_address = parsed.from_address || '';
                        this.from_tax_id = parsed.from_tax_id || '';
                        this.to_name = parsed.to_name || '';
                        this.to_email = parsed.to_email || '';
                        this.to_phone = parsed.to_phone || '';
                        this.to_address = parsed.to_address || '';
                        this.notes = parsed.notes || '';
                        this.terms = parsed.terms || '';
                        this.logo_data = parsed.logo_data || '';
                        this.cover_image = parsed.cover_image || '';
                        this.payment_link = parsed.payment_link || '';
                        this.signature_data = parsed.signature_data || '';
                        if (parsed.template_style) this.template_style = parsed.template_style;
                        if (parsed.accent_color) this.accent_color = parsed.accent_color;
                        if (parsed.currency_pair) {
                            this.currency = parsed.currency_pair;
                            this.onCurrencyChange();
                        } else if (parsed.currency) {
                            this.currency = parsed.currency + '|' + (parsed.currency_symbol || '$');
                            this.onCurrencyChange();
                        }
                        if (parsed.items && parsed.items.length) {
                            this.items = parsed.items;
                        }
                        this.showToast('Restored draft from previous session');
                    }
                }
            } catch (e) {}
        },

        resetForm() {
            if (confirm('Are you sure you want to start a new blank form?')) {
                const slug = config?.slug || 'invoice-generator';
                localStorage.removeItem('formnexa_draft_' + slug);
                localStorage.removeItem('invoiceflow_bloom_draft_' + slug);
                window.location.reload();
            }
        }
    }));
});
</script>
@endpush
@endsection
