@extends('layouts.app')

@section('header_actions')
<button type="button" @click="$dispatch('open-resume-history')" class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 rounded-lg text-xs font-semibold bg-white hover:bg-black/[0.04] border border-black/[0.09] text-[#111111] transition whitespace-nowrap shrink-0 cursor-pointer">
    <svg class="w-3.5 h-3.5 text-[#184f3a] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span class="hidden sm:inline">Saved Resumes</span>
    <span class="sm:hidden">Saved</span>
</button>
@endsection

@section('content')
<x-schema.tool name="Free ATS Resume Builder" description="Build an ATS-friendly resume with {{ count($allTemplates) }} modern templates or import your existing PDF or DOCX CV." />

<div x-data="resumeStudio(
        @js($loadedResume),
        @js($loadedTemplate),
        @js($styles),
        @js($colors),
        @js($allTemplates),
        @js($defaultContent),
        @js($blankContent),
        @js($openImport)
     )"
     x-init="init()"
     class="bg-[#f8f8f3] min-h-screen pb-28 sm:pb-32">

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

    <!-- Top Resume Workspace Toolbar -->
    <section class="no-print bg-white border-b border-black/[0.07]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-3">
            <div class="flex flex-wrap items-center justify-between gap-2 pb-2 border-b border-[#f0f2ef]">
                <x-schema.breadcrumbs :items="[
                    ['Home', url('/')],
                    ['Resume Templates', route('resume.templates')],
                    ['ATS Resume Builder', route('resume.builder')],
                ]" />
                <h1 class="font-display text-xs sm:text-sm font-extrabold text-[#17211b] tracking-tight">Free CV Maker &amp; ATS Resume Builder</h1>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2.5">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-[11px] font-bold whitespace-nowrap">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Free Resume Studio</span>
                </div>

                <input type="text" x-model="title" placeholder="Resume Document Title..."
                       class="text-sm font-extrabold text-[#111111] bg-[#f8f8f3] border border-black/[0.09] rounded-lg px-3 py-1.5 w-full sm:w-64 focus:bg-white focus:border-[#184f3a] focus:outline-none">
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Import Existing CV / Convert CV to ATS -->
                <button type="button" @click="importTargetStyle = template_style; importModalOpen = true"
                        class="inline-flex items-center gap-1.5 px-3.5 sm:px-4 py-2 rounded-lg text-xs font-bold bg-[#184f3a] hover:bg-[#123e2d] text-white shadow-sm transition whitespace-nowrap cursor-pointer"
                        title="Convert CV to ATS format for free">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    <span>Convert CV to ATS / Import</span>
                </button>

                <!-- Template & Design Drawer Trigger -->
                <button type="button" @click="designDrawerOpen = true"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white border border-black/[0.1] hover:border-[#184f3a] text-xs font-bold text-[#111111] transition whitespace-nowrap cursor-pointer">
                    <span class="w-3 h-3 rounded-full border border-white shadow-sm shrink-0" :style="'background-color:' + accent_color"></span>
                    <span x-text="styles[template_style]?.name || 'Flow Navy Sidebar'"></span>
                    <svg class="w-3.5 h-3.5 text-black/45" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>

                <!-- Highlighted Browse Templates Button -->
                <button type="button" @click="designDrawerOpen = true; designTab = 'presets'"
                        class="inline-flex items-center gap-1.5 px-3.5 sm:px-4 py-2 rounded-lg text-xs font-bold bg-[#f0ebff] hover:bg-[#e3d9ff] text-[#5d3ec4] border border-[#ddd2fb] transition whitespace-nowrap cursor-pointer shadow-xs"
                        title="Browse {{ count($allTemplates) }} modern &amp; ATS resume templates">
                    <svg class="w-3.5 h-3.5 text-[#5d3ec4]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                    <span>Browse Templates</span>
                    <span class="px-1.5 py-0.5 rounded-full bg-[#5d3ec4] text-white text-[10px] font-mono font-bold leading-none">{{ count($allTemplates) }}</span>
                </button>

                <button type="button" @click="resetToBlank()"
                        class="px-3 py-2 rounded-lg text-xs font-semibold text-black/50 hover:text-rose-600 hover:bg-rose-50 transition whitespace-nowrap cursor-pointer">
                    Clear Blank
                </button>
            </div>
        </div>

        <!-- Mobile Edit / Live Preview Switcher -->
        <div class="lg:hidden px-4 pb-3 flex items-center justify-center">
            <div class="inline-flex p-1 bg-slate-200/80 rounded-full border border-slate-300/80 w-full max-w-xs">
                <button type="button" @click="mobileTab = 'edit'"
                        class="flex-1 py-2 rounded-full text-xs font-bold transition text-center"
                        :class="mobileTab === 'edit' ? 'bg-white text-slate-950 shadow-sm' : 'text-slate-600'">
                    Edit Details
                </button>
                <button type="button" @click="mobileTab = 'preview'"
                        class="flex-1 py-2 rounded-full text-xs font-bold transition text-center"
                        :class="mobileTab === 'preview' ? 'bg-white text-slate-950 shadow-sm' : 'text-slate-600'">
                    Live Preview
                </button>
            </div>
        </div>
    </section>

    <!-- Main Split-Screen Resume Studio -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 pt-5">

        <!-- ResumeBuilder-Style Visual A4 4-Card Carousel Showcase -->
        <div x-data="{
                 railCat: 'all',
                 railPage: 0,
                 slideRail(dir) {
                     const el = this.$refs.tplRail;
                     if (!el) return;
                     const step = Math.max(el.clientWidth, 300) + 16;
                     el.scrollBy({ left: dir * step, behavior: 'smooth' });
                 },
                 syncRailPage() {
                     const el = this.$refs.tplRail;
                     if (!el || !el.clientWidth) return;
                     this.railPage = Math.round(el.scrollLeft / el.clientWidth);
                 }
             }"
             class="no-print mb-6 rounded-3xl border border-[#dfe4df] bg-gradient-to-br from-[#f6f2ff] via-[#fff8f6] to-[#effaf3] p-4 sm:p-5 shadow-xs">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-4 px-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#184f3a] animate-pulse"></span>
                    <h2 class="text-xs sm:text-sm font-extrabold text-[#17211b]">Choose a Resume Template:</h2>
                    <span class="text-[11px] text-[#536058] hidden xl:inline">Click any full A4 template card below to edit your CV</span>
                </div>

                <div class="flex flex-wrap items-center justify-between lg:justify-end gap-2">
                    {{-- Quick Category Filters --}}
                    <div class="flex items-center gap-1 overflow-x-auto scrollbar-hide max-w-full">
                        @foreach(($categories ?? \App\Support\ResumeCatalog::categories()) as $cKey => $cLabel)
                            <button type="button" @click="railCat = '{{ $cKey }}'; railPage = 0; $nextTick(() => $refs.tplRail?.scrollTo({ left: 0, behavior: 'smooth' }))"
                                    class="px-2.5 py-1 rounded-full text-[10.5px] font-bold whitespace-nowrap transition cursor-pointer border"
                                    :class="railCat === '{{ $cKey }}'
                                        ? 'bg-[#17211b] text-white border-[#17211b] shadow-2xs'
                                        : 'bg-white/90 text-[#536058] border-[#dfe4df] hover:border-[#184f3a] hover:text-[#17211b]'">
                                {{ $cLabel }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Carousel Navigation + Full Gallery Link --}}
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button type="button"
                                @click="slideRail(-1)"
                                class="w-8 h-8 rounded-full bg-white border border-[#dfe4df] hover:border-[#184f3a] text-[#17211b] flex items-center justify-center font-bold text-base shadow-2xs cursor-pointer transition"
                                aria-label="Previous templates">&lsaquo;</button>
                        <button type="button"
                                @click="slideRail(1)"
                                class="w-8 h-8 rounded-full bg-white border border-[#dfe4df] hover:border-[#184f3a] text-[#17211b] flex items-center justify-center font-bold text-base shadow-2xs cursor-pointer transition"
                                aria-label="Next templates">&rsaquo;</button>
                        <a href="{{ route('resume.templates') }}"
                           class="ml-1 px-3 py-1.5 rounded-full bg-white border border-[#dfe4df] hover:border-[#5d3ec4] text-[11px] font-bold text-[#5d3ec4] transition whitespace-nowrap">
                            See All {{ count($allTemplates) }} Templates &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Horizontal 4-Card Visual Slider (Desktop: 4 cards visible, Tablet: 2-3, Mobile: 1.2) -->
            <div x-ref="tplRail"
                 @scroll.debounce.80ms="syncRailPage()"
                 class="flex items-stretch gap-4 overflow-x-auto snap-x snap-mandatory scrollbar-hide scroll-smooth pb-1 pt-0.5 px-0.5">
                @foreach($allTemplates as $tplIndex => $tpl)
                    <div x-show="railCat === 'all' || railCat === '{{ $tpl['category'] }}'"
                         x-on:click="applyTemplatePreset(allTemplates[{{ $tplIndex }}], false)"
                         role="button"
                         tabindex="0"
                         class="w-[78%] sm:w-[calc((100%-16px)/2)] md:w-[calc((100%-32px)/3)] lg:w-[calc((100%-48px)/4)] shrink-0 snap-start rounded-2xl bg-white border-2 p-2.5 flex flex-col justify-between gap-2.5 cursor-pointer group transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl"
                         :class="activeTemplateSlug === '{{ $tpl['slug'] }}'
                             ? 'border-[#184f3a] ring-2 ring-[#184f3a]/25 shadow-md'
                             : 'border-slate-200/90 shadow-sm hover:border-[#184f3a]/60'">

                        {{-- Full Portrait A4 Miniature Preview --}}
                        <div class="relative aspect-[1/1.41] w-full rounded-lg overflow-hidden border border-slate-200/90 bg-white select-none shadow-xs"
                             style="aspect-ratio: 1 / 1.41;">
                            @include('resume._template_miniature', ['tpl' => $tpl])

                            {{-- Active Checkmark Pill --}}
                            <div x-show="activeTemplateSlug === '{{ $tpl['slug'] }}'"
                                 class="absolute top-2 right-2 px-2 py-0.5 rounded-full bg-[#184f3a] text-white text-[10px] font-extrabold shadow-md flex items-center gap-1">
                                <span>&check;</span>
                                <span>Active</span>
                            </div>

                            {{-- Hover Action Overlay --}}
                            <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/40 transition-all duration-200 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <span class="px-3.5 py-2 rounded-full bg-[#184f3a] text-white text-xs font-extrabold shadow-lg">
                                    Use Template
                                </span>
                            </div>
                        </div>

                        {{-- Card Label & Accent Dot --}}
                        <div class="px-0.5 flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <div class="text-xs font-extrabold text-[#17211b] truncate group-hover:text-[#184f3a]">{{ $tpl['name'] }}</div>
                                <div class="text-[10.5px] text-[#68736d] truncate">{{ $tpl['role'] }}</div>
                            </div>
                            <span class="w-3 h-3 rounded-full shrink-0 mt-1 border border-slate-200" style="background-color: {{ $tpl['accent_color'] }}"></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

            <!-- LEFT COLUMN: Structured Resume Editor Accordion -->
            <div class="no-print lg:col-span-5 space-y-3" :class="mobileTab === 'preview' ? 'hidden lg:block' : 'block'">

                <!-- Quick CV Import Callout Card -->
                <div class="p-4 rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50/70 border border-[#c8f3dc] flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-extrabold text-slate-900">Convert CV to ATS Format for Free</div>
                        <div class="text-[11px] text-slate-600 mt-0.5">Upload a PDF or Word (.docx) file or paste raw CV text to instantly populate an ATS-friendly template.</div>
                    </div>
                    <button type="button" @click="importTargetStyle = template_style; importModalOpen = true"
                            class="px-3.5 py-2 rounded-xl bg-white border border-[#c8f3dc] hover:border-[#184f3a] text-[#184f3a] text-xs font-bold shadow-sm transition shrink-0">
                        Convert CV Free
                    </button>
                </div>

                <!-- 1. Personal Details -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                    <button type="button" @click="toggleSection('personal')" class="w-full px-5 py-4 flex items-center justify-between text-left hover:bg-slate-50/60 transition">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center text-sm font-bold">&#128100;</span>
                            <div>
                                <div class="text-sm font-extrabold text-slate-900">Personal Details</div>
                                <div class="text-[11px] text-slate-400" x-text="content.personal.full_name || 'Name, title, photo, contact links'"></div>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="openSection === 'personal' ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>

                    <div x-show="openSection === 'personal'" x-transition class="px-5 pb-5 pt-2 border-t border-slate-100 space-y-3.5 text-xs">
                        <!-- Avatar Upload & Toggle -->
                        <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center gap-3">
                                <template x-if="photo_data">
                                    <img :src="photo_data" class="w-12 h-12 rounded-full object-cover border border-slate-300" alt="Avatar">
                                </template>
                                <template x-if="!photo_data">
                                    <div class="w-12 h-12 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-sm">Photo</div>
                                </template>
                                <div class="flex flex-wrap gap-2">
                                    <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-white border border-slate-300 hover:border-[#184f3a] font-bold text-slate-700 transition">
                                        <span x-text="photo_data ? 'Change Photo' : 'Upload Photo'"></span>
                                        <input type="file" @change="handlePhotoUpload($event)" accept="image/*" class="hidden">
                                    </label>
                                    <button type="button" x-show="photo_data" @click="photo_data = ''" class="px-2.5 py-1.5 rounded-lg text-rose-600 hover:bg-rose-50 font-bold transition">Remove</button>
                                </div>
                            </div>
                            <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-600 cursor-pointer shrink-0">
                                <input type="checkbox" x-model="content.personal.show_photo" class="rounded border-slate-300 text-[#184f3a] focus:ring-[#184f3a]">
                                <span>Show Photo</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Full Name *</label>
                                <input type="text" x-model="content.personal.full_name" placeholder="Brian T. Wayne" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-semibold focus:bg-white focus:border-[#184f3a] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Professional Title</label>
                                <input type="text" x-model="content.personal.job_title" placeholder="Business Development Consultant" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-semibold focus:bg-white focus:border-[#184f3a] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Email</label>
                                <input type="email" x-model="content.personal.email" placeholder="you@domain.com" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-[#184f3a] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Phone</label>
                                <input type="text" x-model="content.personal.phone" placeholder="+1 555-0199" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-[#184f3a] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Location</label>
                                <input type="text" x-model="content.personal.location" placeholder="California, USA" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-[#184f3a] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">LinkedIn URL</label>
                                <input type="text" x-model="content.personal.linkedin" placeholder="linkedin.com/in/username" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-[#184f3a] focus:outline-none">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Website / Portfolio / GitHub</label>
                                <input type="text" x-model="content.personal.website" placeholder="yourportfolio.com" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-[#184f3a] focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Profile / Professional Summary -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                    <button type="button" @click="toggleSection('summary')" class="w-full px-5 py-4 flex items-center justify-between text-left hover:bg-slate-50/60 transition">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center text-sm font-bold">&#128221;</span>
                            <div>
                                <div class="text-sm font-extrabold text-slate-900">Profile Summary</div>
                                <div class="text-[11px] text-slate-400">Concise career headline &amp; core strengths</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" @click.stop x-model="content.section_visibility.summary" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500" title="Show/Hide Section">
                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="openSection === 'summary' ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </div>
                    </button>
                    <div x-show="openSection === 'summary'" x-transition class="px-5 pb-5 pt-2 border-t border-slate-100 text-xs">
                        <textarea x-model="content.summary" rows="4" placeholder="Summarize your experience, achievements, and target impact in 2–4 sentences..." class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 leading-relaxed focus:bg-white focus:border-[#184f3a] focus:outline-none resize-y"></textarea>
                    </div>
                </div>

                <!-- 3. Work Experience -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                    <button type="button" @click="toggleSection('experience')" class="w-full px-5 py-4 flex items-center justify-between text-left hover:bg-slate-50/60 transition">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-sm font-bold">&#128188;</span>
                            <div>
                                <div class="text-sm font-extrabold text-slate-900">Work Experience</div>
                                <div class="text-[11px] text-slate-400" x-text="content.experience.length + ' role(s)'"></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" @click.stop x-model="content.section_visibility.experience" class="rounded border-slate-300 text-teal-600" title="Show/Hide Section">
                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="openSection === 'experience' ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </div>
                    </button>
                    <div x-show="openSection === 'experience'" x-transition class="px-5 pb-5 pt-3 border-t border-slate-100 space-y-4 text-xs">
                        <template x-for="(exp, idx) in content.experience" :key="'edit-exp-' + idx">
                            <div class="p-4 rounded-2xl bg-slate-50/90 border border-slate-200/90 space-y-3">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[11px] font-extrabold text-slate-700" x-text="(exp.role || exp.company || 'Experience #' + (idx + 1))"></span>
                                    <div class="flex items-center gap-1">
                                        <button type="button" @click="moveItem(content.experience, idx, -1)" x-show="idx > 0" class="px-2 py-1 rounded bg-white border border-slate-200 text-[10px] font-bold text-slate-600 hover:bg-slate-100" title="Move Up">&uarr;</button>
                                        <button type="button" @click="moveItem(content.experience, idx, 1)" x-show="idx < content.experience.length - 1" class="px-2 py-1 rounded bg-white border border-slate-200 text-[10px] font-bold text-slate-600 hover:bg-slate-100" title="Move Down">&darr;</button>
                                        <button type="button" @click="content.experience.splice(idx, 1)" x-show="content.experience.length > 1" class="px-2 py-1 rounded text-rose-600 hover:bg-rose-50 font-bold">Delete</button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    <input type="text" x-model="exp.role" placeholder="Job Title (e.g. Product Manager)" class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-900 font-semibold focus:border-[#184f3a] focus:outline-none">
                                    <input type="text" x-model="exp.company" placeholder="Company Name" class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-800 focus:border-[#184f3a] focus:outline-none">
                                    <input type="text" x-model="exp.start_date" placeholder="Start (e.g. Jan 2022)" class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-800 focus:border-[#184f3a] focus:outline-none">
                                    <input type="text" x-model="exp.end_date" placeholder="End (e.g. Present)" class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-800 focus:border-[#184f3a] focus:outline-none">
                                    <input type="text" x-model="exp.location" placeholder="Location (e.g. New York, NY)" class="sm:col-span-2 px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-800 focus:border-[#184f3a] focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Key Achievements (One bullet per line)</label>
                                    <textarea x-model="exp.bullets_text" rows="3" placeholder="• Increased revenue by 30%..." class="w-full p-2.5 rounded-xl bg-white border border-slate-200 text-slate-800 leading-relaxed focus:border-[#184f3a] focus:outline-none resize-y"></textarea>
                                </div>
                            </div>
                        </template>
                        <button type="button" @click="addExperience()" class="w-full py-2.5 rounded-xl border-2 border-dashed border-slate-300 hover:border-[#184f3a] text-slate-700 hover:text-[#184f3a] font-bold transition">
                            + Add Work Experience
                        </button>
                    </div>
                </div>

                <!-- 4. Education -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                    <button type="button" @click="toggleSection('education')" class="w-full px-5 py-4 flex items-center justify-between text-left hover:bg-slate-50/60 transition">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-bold">&#127891;</span>
                            <div>
                                <div class="text-sm font-extrabold text-slate-900">Education</div>
                                <div class="text-[11px] text-slate-400" x-text="content.education.length + ' degree(s)'"></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" @click.stop x-model="content.section_visibility.education" class="rounded border-slate-300 text-teal-600" title="Show/Hide Section">
                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="openSection === 'education' ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </div>
                    </button>
                    <div x-show="openSection === 'education'" x-transition class="px-5 pb-5 pt-3 border-t border-slate-100 space-y-4 text-xs">
                        <template x-for="(edu, idx) in content.education" :key="'edit-edu-' + idx">
                            <div class="p-4 rounded-2xl bg-slate-50/90 border border-slate-200/90 space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-extrabold text-slate-700" x-text="edu.degree || 'Education #' + (idx + 1)"></span>
                                    <button type="button" @click="content.education.splice(idx, 1)" x-show="content.education.length > 1" class="text-rose-600 font-bold hover:underline">Delete</button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    <input type="text" x-model="edu.degree" placeholder="Degree (e.g. MBA, B.S. Computer Science)" class="sm:col-span-2 px-3 py-2 rounded-xl bg-white border border-slate-200 font-semibold text-slate-900 focus:border-[#184f3a] focus:outline-none">
                                    <input type="text" x-model="edu.school" placeholder="University / School" class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-800 focus:border-[#184f3a] focus:outline-none">
                                    <input type="text" x-model="edu.location" placeholder="Location" class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-800 focus:border-[#184f3a] focus:outline-none">
                                    <input type="text" x-model="edu.start_date" placeholder="Start Year" class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-800 focus:border-[#184f3a] focus:outline-none">
                                    <input type="text" x-model="edu.end_date" placeholder="End / Graduation Year" class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-800 focus:border-[#184f3a] focus:outline-none">
                                    <input type="text" x-model="edu.details" placeholder="Honors, GPA, Coursework (Optional)" class="sm:col-span-2 px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-800 focus:border-[#184f3a] focus:outline-none">
                                </div>
                            </div>
                        </template>
                        <button type="button" @click="addEducation()" class="w-full py-2.5 rounded-xl border-2 border-dashed border-slate-300 hover:border-[#184f3a] text-slate-700 hover:text-[#184f3a] font-bold transition">
                            + Add Education
                        </button>
                    </div>
                </div>

                <!-- 5. Skills & Languages -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                    <button type="button" @click="toggleSection('skills')" class="w-full px-5 py-4 flex items-center justify-between text-left hover:bg-slate-50/60 transition">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold">&#9889;</span>
                            <div>
                                <div class="text-sm font-extrabold text-slate-900">Skills &amp; Languages</div>
                                <div class="text-[11px] text-slate-400" x-text="content.skills.length + ' skills, ' + content.languages.length + ' languages'"></div>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="openSection === 'skills' ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="openSection === 'skills'" x-transition class="px-5 pb-5 pt-3 border-t border-slate-100 space-y-5 text-xs">
                        <!-- Skills -->
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Skills (Press Enter or paste comma-separated)</label>
                                <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-600 cursor-pointer">
                                    <input type="checkbox" x-model="content.section_visibility.skills" class="rounded border-slate-300 text-teal-600">
                                    <span>Visible</span>
                                </label>
                            </div>
                            <div class="flex gap-2">
                                <input type="text" x-model="skillInput" @keydown.enter.prevent="addSkillInput()" placeholder="e.g. Product Strategy, Figma, Laravel..." class="flex-1 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:bg-white focus:border-[#184f3a] focus:outline-none">
                                <button type="button" @click="addSkillInput()" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-[#184f3a] transition">Add</button>
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="(sk, idx) in content.skills" :key="'tag-' + idx">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-800 font-semibold">
                                        <span x-text="sk"></span>
                                        <button type="button" @click="content.skills.splice(idx, 1)" class="text-slate-400 hover:text-rose-600 font-bold">&times;</button>
                                    </span>
                                </template>
                            </div>
                        </div>

                        <!-- Languages -->
                        <div class="space-y-2.5 pt-3 border-t border-slate-100">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Languages</label>
                                <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-600 cursor-pointer">
                                    <input type="checkbox" x-model="content.section_visibility.languages" class="rounded border-slate-300 text-teal-600">
                                    <span>Visible</span>
                                </label>
                            </div>
                            <template x-for="(lang, lIdx) in content.languages" :key="'edit-l-' + lIdx">
                                <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-200">
                                    <input type="text" x-model="lang.name" placeholder="Language" class="w-28 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 font-semibold">
                                    <input type="text" x-model="lang.level" placeholder="Fluent / Native" class="flex-1 min-w-0 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200">
                                    <div class="flex items-center gap-1 shrink-0" title="Proficiency Rating">
                                        <template x-for="d in 5" :key="d">
                                            <button type="button" @click="lang.score = d"
                                                    class="w-3 h-3 rounded-full transition"
                                                    :class="d <= (lang.score || 4) ? 'bg-[#184f3a]' : 'bg-slate-300'"></button>
                                        </template>
                                    </div>
                                    <button type="button" @click="content.languages.splice(lIdx, 1)" class="text-rose-500 hover:text-rose-700 font-bold px-1">&times;</button>
                                </div>
                            </template>
                            <button type="button" @click="content.languages.push({name:'', level:'Fluent', score:4}); content.section_visibility.languages = true;" class="text-xs font-bold text-[#184f3a] hover:underline">
                                + Add Language
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 6. Awards & Projects -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                    <button type="button" @click="toggleSection('extras')" class="w-full px-5 py-4 flex items-center justify-between text-left hover:bg-slate-50/60 transition">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-sm font-bold">&#127942;</span>
                            <div>
                                <div class="text-sm font-extrabold text-slate-900">Awards, Certifications &amp; Projects</div>
                                <div class="text-[11px] text-slate-400">Optional credential &amp; portfolio blocks</div>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="openSection === 'extras' ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="openSection === 'extras'" x-transition class="px-5 pb-5 pt-3 border-t border-slate-100 space-y-5 text-xs">
                        <!-- Awards -->
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Awards &amp; Certifications</label>
                                <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-600 cursor-pointer">
                                    <input type="checkbox" x-model="content.section_visibility.awards" class="rounded border-slate-300 text-teal-600">
                                    <span>Visible</span>
                                </label>
                            </div>
                            <template x-for="(aw, aIdx) in content.awards" :key="'edit-aw-' + aIdx">
                                <div class="grid grid-cols-12 gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-200 items-center">
                                    <input type="text" x-model="aw.title" placeholder="Award / Certification" class="col-span-5 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 font-semibold">
                                    <input type="text" x-model="aw.issuer" placeholder="Issuer" class="col-span-4 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200">
                                    <input type="text" x-model="aw.date" placeholder="Year" class="col-span-2 px-2 py-1.5 rounded-lg bg-white border border-slate-200">
                                    <button type="button" @click="content.awards.splice(aIdx, 1)" class="col-span-1 text-rose-500 font-bold text-center">&times;</button>
                                </div>
                            </template>
                            <button type="button" @click="content.awards.push({title:'', issuer:'', date:''}); content.section_visibility.awards = true;" class="text-xs font-bold text-[#184f3a] hover:underline">
                                + Add Award / Certification
                            </button>
                        </div>

                        <!-- Projects -->
                        <div class="space-y-2.5 pt-3 border-t border-slate-100">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Projects</label>
                                <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-600 cursor-pointer">
                                    <input type="checkbox" x-model="content.section_visibility.projects" class="rounded border-slate-300 text-teal-600">
                                    <span>Visible</span>
                                </label>
                            </div>
                            <template x-for="(proj, pIdx) in content.projects" :key="'edit-pr-' + pIdx">
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                                    <div class="flex flex-wrap sm:flex-nowrap gap-2">
                                        <input type="text" x-model="proj.name" placeholder="Project Name" class="min-w-0 flex-1 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 font-semibold">
                                        <input type="text" x-model="proj.link" placeholder="Link / URL" class="min-w-0 flex-1 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200">
                                        <button type="button" @click="content.projects.splice(pIdx, 1)" class="text-rose-500 font-bold px-2 shrink-0">&times;</button>
                                    </div>
                                    <textarea x-model="proj.description" rows="2" placeholder="Impact and tech used..." class="w-full p-2 rounded-lg bg-white border border-slate-200 resize-none"></textarea>
                                </div>
                            </template>
                            <button type="button" @click="content.projects.push({name:'', role:'', link:'', description:''}); content.section_visibility.projects = true;" class="text-xs font-bold text-[#184f3a] hover:underline">
                                + Add Project
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Sticky Real-Time A4 Resume Canvas -->
            <div class="lg:col-span-7 lg:sticky lg:top-24" :class="mobileTab === 'edit' ? 'hidden lg:block' : 'block'">
                <div class="no-print mb-3 flex flex-wrap items-center justify-between gap-2 px-1 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold text-[11px]">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                            <span>ATS CV Checker: Structure Verified</span>
                        </span>
                        <span class="text-slate-500 font-medium hidden sm:inline">&bull; <strong class="text-slate-800" x-text="styles[template_style]?.name"></strong></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('convert.tool', 'pdf-to-word') }}" class="text-[11px] font-semibold text-slate-500 hover:text-[#184f3a] hidden sm:inline" title="Convert exported ATS CV PDF to Word DOCX">Export to Word (DOCX) &rarr;</a>
                        <button type="button" @click="designDrawerOpen = true" class="text-[#184f3a] font-bold hover:underline">
                            Change Template &rarr;
                        </button>
                    </div>
                </div>

                <!-- Scroll-safe wrapper on mobile for crisp A4 formatting -->
                <div class="sm:hidden mb-2 px-2 py-1 rounded-lg bg-[#e8f7ed] border border-[#c8f3dc] text-[11px] font-medium text-[#184f3a] text-center">
                    Swipe left/right on the sheet to inspect full A4 layout
                </div>
                <div class="w-full overflow-x-auto pb-2">
                    <div class="min-w-[600px] sm:min-w-0">
                        @include('resume._preview_reactive')
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bottom Floating Action Dock (Mobile & Desktop) -->
    <div class="no-print fixed bottom-3 left-3 right-3 sm:left-1/2 sm:right-auto sm:bottom-6 sm:-translate-x-1/2 sm:w-max max-w-full z-40 bg-slate-950/92 backdrop-blur-xl border border-slate-800 text-white px-2.5 sm:px-5 py-2 sm:py-3 rounded-2xl sm:rounded-full shadow-2xl flex items-center justify-between sm:justify-center gap-1 sm:gap-2.5">
        <div class="flex items-center gap-0.5 sm:gap-1.5">
            <button type="button" @click="saveResume()" :disabled="saving" class="inline-flex flex-col sm:flex-row items-center gap-0.5 sm:gap-1.5 px-2 sm:px-3 py-1.5 rounded-xl sm:rounded-full text-[10px] sm:text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition whitespace-nowrap">
                <svg x-show="!saving" class="w-4 h-4 text-teal-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <svg x-show="saving" class="w-4 h-4 animate-spin text-teal-400 shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <span class="hidden sm:inline" x-text="saving ? 'Saving...' : 'Save Resume'"></span>
                <span class="sm:hidden" x-text="saving ? 'Saving' : 'Save'"></span>
            </button>

            <button type="button" @click="shareLink()" class="inline-flex flex-col sm:flex-row items-center gap-0.5 sm:gap-1.5 px-2 sm:px-3 py-1.5 rounded-xl sm:rounded-full text-[10px] sm:text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition whitespace-nowrap">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                <span class="hidden sm:inline">Share Link</span>
                <span class="sm:hidden">Share</span>
            </button>

            <button type="button" @click="importTargetStyle = template_style; importModalOpen = true" class="inline-flex flex-col sm:flex-row items-center gap-0.5 sm:gap-1.5 px-2 sm:px-3 py-1.5 rounded-xl sm:rounded-full text-[10px] sm:text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition whitespace-nowrap">
                <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                <span class="hidden sm:inline">Import CV</span>
                <span class="sm:hidden">Import</span>
            </button>

            <button type="button" @click="window.print()" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition whitespace-nowrap">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.75A2.25 2.25 0 0015 1.5H9a2.25 2.25 0 00-2.25 2.25v3.456"/></svg>
                <span>Print</span>
            </button>
        </div>

        <div class="hidden sm:block h-4 w-px bg-slate-800"></div>

        <button type="button" @click="downloadPdf()" :disabled="downloading" class="inline-flex items-center gap-1.5 sm:gap-2 px-3.5 sm:px-5 py-2 rounded-xl sm:rounded-full text-xs font-extrabold text-slate-950 bg-gradient-to-r from-teal-400 to-emerald-400 hover:from-teal-300 hover:to-emerald-300 shadow-md shadow-teal-500/20 transition transform active:scale-95 disabled:opacity-50 whitespace-nowrap shrink-0">
            <svg x-show="!downloading" class="w-4 h-4 text-slate-950 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            <svg x-show="downloading" class="w-4 h-4 animate-spin text-slate-950 shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
            <span class="hidden sm:inline" x-text="downloading ? 'Exporting...' : 'Download PDF'"></span>
            <span class="sm:hidden" x-text="downloading ? 'PDF...' : 'PDF'"></span>
        </button>
    </div>

    <!-- ================================================================ -->
    <!-- IMPORT EXISTING CV FILE OR PASTE CV TEXT MODAL                   -->
    <!-- ================================================================ -->
    <div x-cloak x-show="importModalOpen"
         x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="no-print fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs overflow-y-auto"
         @keydown.escape.window="importModalOpen = false">
        <div x-show="importModalOpen" @click.outside="importModalOpen = false"
             class="relative z-10 w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Convert CV to ATS Format for Free &bull; Import File or Text</h3>
                    <p class="text-xs text-slate-500">Upload a PDF / Word (.docx) file or paste text to instantly convert into an ATS-friendly CV template.</p>
                </div>
                <button type="button" @click="importModalOpen = false" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-800 hover:bg-slate-200/70 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-5 text-xs">
                <!-- Import Source Tabs -->
                <div class="inline-flex p-1 bg-slate-100 rounded-full border border-slate-200">
                    <button type="button" @click="importMode = 'text'" class="px-4 py-1.5 rounded-full font-bold transition" :class="importMode === 'text' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600'">Paste CV Text</button>
                    <button type="button" @click="importMode = 'file'" class="px-4 py-1.5 rounded-full font-bold transition" :class="importMode === 'file' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600'">Upload PDF / DOCX / TXT</button>
                </div>

                <!-- Paste Text Mode -->
                <div x-show="importMode === 'text'" class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="font-bold text-slate-700 uppercase tracking-wider text-[10px]">Paste your existing resume or LinkedIn text</label>
                        <button type="button" @click="fillSampleImportText()" class="text-[11px] font-bold text-[#184f3a] hover:underline">Insert Sample CV Text</button>
                    </div>
                    <textarea x-model="importText" rows="9"
                              placeholder="Paste your entire CV here (Name, Email, Experience, Education, Skills, Languages)..."
                              class="w-full p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-800 leading-relaxed focus:bg-white focus:border-[#184f3a] focus:outline-none"></textarea>
                </div>

                <!-- File Upload Mode -->
                <div x-show="importMode === 'file'" class="space-y-3">
                    <label class="block p-8 rounded-2xl border-2 border-dashed border-slate-300 hover:border-[#184f3a] bg-slate-50 hover:bg-[#e8f7ed]/40 text-center cursor-pointer transition">
                        <svg class="w-10 h-10 text-[#184f3a] mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        <div class="text-sm font-extrabold text-slate-900" x-text="importFileName || 'Click to choose your CV file'"></div>
                        <div class="text-xs text-slate-500 mt-1">Supports PDF (.pdf), Word (.docx), Text (.txt), or JSON Resume (.json) up to 5MB</div>
                        <input type="file" @change="onImportFileSelect($event)" accept=".pdf,.docx,.txt,.md,.json" class="hidden">
                    </label>
                </div>

                <!-- Optional Target Layout Choice Right Inside Import Modal -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="font-bold text-slate-800">Target Template Layout</div>
                        <div class="text-[11px] text-slate-500">Your parsed CV will immediately populate this template:</div>
                    </div>
                    <select x-model="importTargetStyle" class="px-3 py-2 rounded-xl bg-white border border-slate-300 font-bold text-slate-900 text-xs">
                        @foreach($styles as $sKey => $st)
                            <option value="{{ $sKey }}">{{ $st['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Parsed Feedback Alert -->
                <div x-show="importError" x-cloak class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 font-semibold" x-text="importError"></div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-3">
                <button type="button" @click="importModalOpen = false" class="px-4 py-2 rounded-full text-xs font-semibold text-slate-600 hover:bg-slate-200 transition">Cancel</button>
                <button type="button" @click="runImport()" :disabled="importing"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#184f3a] hover:bg-[#123e2d] text-white text-xs font-bold shadow-md transition disabled:opacity-50">
                    <svg x-show="importing" class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span x-text="importing ? 'Parsing CV...' : 'Extract & Apply to Resume'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- ================================================================ -->
    <!-- TEMPLATE, COLOR & TYPOGRAPHY SLIDE-OVER DRAWER                   -->
    <!-- ================================================================ -->
    <div x-cloak x-show="designDrawerOpen" class="no-print fixed inset-0 z-50 overflow-hidden" @keydown.escape.window="designDrawerOpen = false">
        <div class="absolute inset-0 bg-slate-950/45 backdrop-blur-xs" @click="designDrawerOpen = false"></div>
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-4 sm:pl-10">
            <div class="w-full max-w-md bg-white border-l border-border-subtle shadow-2xl flex flex-col">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Resume Design &amp; Templates</h3>
                        <p class="text-xs text-slate-500">Switch layouts, colors, fonts, or load a starter</p>
                    </div>
                    <button type="button" @click="designDrawerOpen = false" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="px-6 pt-3 pb-2 border-b border-slate-100 flex gap-2">
                    <button type="button" @click="designTab = 'layout'" class="px-3.5 py-1.5 rounded-full text-xs font-bold transition" :class="designTab === 'layout' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'">Layouts &amp; Colors</button>
                    <button type="button" @click="designTab = 'presets'" class="px-3.5 py-1.5 rounded-full text-xs font-bold transition" :class="designTab === 'presets' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'" x-text="'Templates (' + (allTemplates?.length || 15) + ')'"></button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-6 text-xs">
                    <div x-show="designTab === 'layout'" class="space-y-6">
                        <!-- Colors -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="font-bold uppercase tracking-wider text-slate-500">Accent Color</label>
                                <span class="font-mono font-bold text-slate-600" x-text="accent_color"></span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2.5">
                                <template x-for="clr in colors" :key="clr.hex">
                                    <button type="button" @click="accent_color = clr.hex; autoSave();"
                                            class="w-8 h-8 rounded-full border-2 border-white shadow-sm transition hover:scale-110"
                                            :class="accent_color.toLowerCase() === clr.hex.toLowerCase() ? 'ring-2 ring-slate-900 scale-110' : ''"
                                            :style="'background-color:' + clr.hex" :title="clr.name"></button>
                                </template>
                                <label class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-full border border-slate-200 bg-slate-50 cursor-pointer font-bold text-slate-700">
                                    <input type="color" x-model="accent_color" @input="autoSave()" class="w-4 h-4 border-0 p-0 bg-transparent cursor-pointer">
                                    <span>Custom</span>
                                </label>
                            </div>
                        </div>

                        <!-- Typography & Spacing -->
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block font-bold uppercase tracking-wider text-slate-500 mb-2">Font Family</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <button type="button" @click="font_family = 'sans'; autoSave();" class="py-2 rounded-xl border font-sans font-bold transition" :class="font_family === 'sans' ? 'border-[#184f3a] bg-[#e8f7ed] text-[#184f3a]' : 'border-slate-200 text-slate-700'">Sans Serif</button>
                                    <button type="button" @click="font_family = 'serif'; autoSave();" class="py-2 rounded-xl border font-editorial font-bold transition" :class="font_family === 'serif' ? 'border-[#184f3a] bg-[#e8f7ed] text-[#184f3a]' : 'border-slate-200 text-slate-700'">Editorial Serif</button>
                                    <button type="button" @click="font_family = 'mono'; autoSave();" class="py-2 rounded-xl border font-tech-mono font-bold transition" :class="font_family === 'mono' ? 'border-[#184f3a] bg-[#e8f7ed] text-[#184f3a]' : 'border-slate-200 text-slate-700'">Tech Mono</button>
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold uppercase tracking-wider text-slate-500 mb-2">Page Density</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" @click="density = 'comfortable'; autoSave();" class="py-2 rounded-xl border font-bold transition" :class="density !== 'compact' ? 'border-[#184f3a] bg-[#e8f7ed] text-[#184f3a]' : 'border-slate-200 text-slate-700'">Comfortable</button>
                                    <button type="button" @click="density = 'compact'; autoSave();" class="py-2 rounded-xl border font-bold transition" :class="density === 'compact' ? 'border-[#184f3a] bg-[#e8f7ed] text-[#184f3a]' : 'border-slate-200 text-slate-700'">Compact One-Page</button>
                                </div>
                            </div>
                        </div>

                        <!-- {{ count($styles) }} Visual Layout Engines with Mini Wireframes -->
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-500 mb-2.5">{{ count($styles) }} Modern Layout Engines</label>
                            <div class="grid grid-cols-2 gap-3">
                                <template x-for="(st, key) in styles" :key="key">
                                    <button type="button" @click="template_style = key; activeTemplateSlug = ''; autoSave(); showToast('Switched layout to ' + st.name);"
                                            class="text-left p-2.5 rounded-2xl border-2 transition flex flex-col justify-between gap-2"
                                            :class="template_style === key ? 'border-[#184f3a] bg-[#e8f7ed]/60' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                        {{-- Mini Visual Wireframe Preview --}}
                                        <div class="w-full h-14 rounded-lg border border-slate-200/80 overflow-hidden bg-white p-1.5 flex flex-col justify-between"
                                             :class="{
                                                 'bg-[#FAF8F4]': key === 'editorial_figma',
                                                 'bg-[#F8FAFC]': key === 'bento_modern',
                                                 'bg-white': key !== 'editorial_figma' && key !== 'bento_modern'
                                             }">
                                            <template x-if="key === 'banner_split_date'">
                                                <div class="space-y-1 h-full">
                                                    <div class="h-3.5 w-full rounded-xs p-0.5 flex justify-between items-center" :style="'background:' + accent_color"><span class="h-1 w-8 bg-white rounded"></span><span class="w-2 h-2 rounded-full bg-white/80"></span></div>
                                                    <div class="grid grid-cols-12 gap-1"><div class="col-span-3 h-3 bg-slate-200 rounded-xs"></div><div class="col-span-9 h-3 bg-slate-100 rounded-xs"></div></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'ribbon_centered'">
                                                <div class="space-y-1 h-full">
                                                    <div class="h-2.5 w-full rounded-xs flex items-center justify-center" :style="'background:' + accent_color"><span class="h-1 w-8 bg-white rounded"></span></div>
                                                    <div class="grid grid-cols-2 gap-1"><div class="h-1 bg-slate-200 rounded"></div><div class="h-1 bg-slate-200 rounded"></div></div>
                                                    <div class="h-2.5 bg-slate-100 rounded"></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'bento_modern'">
                                                <div class="space-y-1 h-full">
                                                    <div class="h-2.5 w-full rounded bg-white border border-slate-200 flex items-center px-1 gap-1"><span class="w-1.5 h-1.5 rounded-full" :style="'background:' + accent_color"></span><span class="h-1 w-8 bg-slate-700 rounded"></span></div>
                                                    <div class="grid grid-cols-12 gap-1"><div class="col-span-8 h-4 rounded bg-white border border-slate-200"></div><div class="col-span-4 h-4 rounded bg-white border border-slate-200"></div></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'stripe_clean'">
                                                <div class="border-t-2 pt-1 space-y-1 h-full" :style="'border-color:' + accent_color">
                                                    <div class="flex justify-between items-center"><span class="h-1.5 w-10 bg-slate-800 rounded"></span><span class="h-1 w-4 bg-slate-300 rounded"></span></div>
                                                    <div class="grid grid-cols-12 gap-1"><div class="col-span-8 h-3 bg-slate-100 rounded"></div><div class="col-span-4 h-3 bg-slate-200 rounded"></div></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'aurora_pill'">
                                                <div class="space-y-1 h-full">
                                                    <div class="h-3.5 w-full rounded p-0.5 flex items-center gap-1" :style="'background:' + accent_color + '18'"><span class="w-2 h-2 rounded-full" :style="'background:' + accent_color"></span><span class="h-1 w-8 bg-slate-700 rounded"></span></div>
                                                    <div class="grid grid-cols-12 gap-1"><div class="col-span-8 h-3 border-l-2 pl-1 bg-slate-50" :style="'border-color:' + accent_color"></div><div class="col-span-4 h-3 bg-slate-100 rounded"></div></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'obsidian_header'">
                                                <div class="space-y-1 h-full">
                                                    <div class="h-3.5 w-full rounded-sm bg-[#0F172A] border-t-2 flex items-center px-1" :style="'border-color:' + accent_color"><span class="h-1 w-8 bg-white rounded"></span></div>
                                                    <div class="grid grid-cols-12 gap-1"><div class="col-span-8 h-3 bg-slate-100 rounded"></div><div class="col-span-4 h-3 bg-slate-200 rounded"></div></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'flow_sidebar'">
                                                <div class="grid grid-cols-12 gap-1 h-full">
                                                    <div class="col-span-4 rounded-sm" :style="'background:' + accent_color"></div>
                                                    <div class="col-span-8 space-y-1 pt-0.5"><div class="h-1.5 w-3/4 bg-slate-200 rounded"></div><div class="h-1 w-full bg-slate-100 rounded"></div><div class="h-1 w-5/6 bg-slate-100 rounded"></div></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'editorial_figma'">
                                                <div class="space-y-1 h-full">
                                                    <div class="h-2.5 border-b border-stone-300 flex justify-between items-center"><span class="h-1 w-8 bg-stone-700 rounded"></span><span class="h-1 w-4 bg-stone-300 rounded"></span></div>
                                                    <div class="grid grid-cols-12 gap-1 pt-0.5"><div class="col-span-7 space-y-1"><div class="h-1 w-full bg-stone-300 rounded"></div><div class="h-1 w-4/5 bg-stone-200 rounded"></div></div><div class="col-span-5 space-y-1"><div class="h-1 w-full bg-stone-200 rounded"></div></div></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'timeline_pro'">
                                                <div class="grid grid-cols-12 gap-1.5 h-full">
                                                    <div class="col-span-7 border-l-2 pl-1.5 space-y-1.5" :style="'border-color:' + accent_color"><div class="h-1.5 w-5/6 bg-slate-300 rounded"></div><div class="h-1.5 w-3/4 bg-slate-200 rounded"></div></div>
                                                    <div class="col-span-5 space-y-1.5 pt-0.5"><div class="h-1.5 w-full rounded-full bg-slate-200"><div class="h-full w-4/5 rounded-full" :style="'background:' + accent_color"></div></div><div class="h-1.5 w-full rounded-full bg-slate-200"><div class="h-full w-2/3 rounded-full" :style="'background:' + accent_color"></div></div></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'luxe_split'">
                                                <div class="space-y-1.5 h-full">
                                                    <div class="flex items-center gap-1.5"><div class="w-3.5 h-3.5 rounded-sm border-2" :style="'border-color:' + accent_color"></div><div class="h-1.5 w-10 bg-slate-700 rounded"></div></div>
                                                    <div class="h-2 w-full bg-slate-100 border-l-2" :style="'border-color:' + accent_color"></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'executive_bold'">
                                                <div class="space-y-1.5 h-full">
                                                    <div class="h-3.5 w-full rounded-sm" :style="'background:' + accent_color"></div>
                                                    <div class="grid grid-cols-12 gap-1"><div class="col-span-8 h-3 bg-slate-100 rounded-sm"></div><div class="col-span-4 h-3 bg-slate-200 rounded-sm"></div></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'creative_card'">
                                                <div class="grid grid-cols-12 gap-1 h-full">
                                                    <div class="col-span-4 rounded-sm p-0.5" :style="'background:' + accent_color + '25'"><div class="w-2.5 h-2.5 rounded-sm" :style="'background:' + accent_color"></div></div>
                                                    <div class="col-span-8 space-y-1 pt-0.5"><div class="h-1.5 w-2/3 bg-slate-300 rounded"></div><div class="h-1 w-full bg-slate-100 rounded"></div></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'tech_stack'">
                                                <div class="border-t-2 pt-1 space-y-1 h-full" :style="'border-color:' + accent_color">
                                                    <div class="h-1.5 w-9 bg-slate-800 rounded"></div>
                                                    <div class="flex gap-1"><span class="h-1.5 w-4 bg-slate-200 rounded"></span><span class="h-1.5 w-5 bg-slate-200 rounded"></span><span class="h-1.5 w-4 bg-slate-200 rounded"></span></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'minimal_swiss'">
                                                <div class="border-t-2 pt-1 space-y-1.5 h-full" :style="'border-color:' + accent_color">
                                                    <div class="grid grid-cols-12 gap-1"><div class="col-span-3 h-1 bg-slate-400 rounded"></div><div class="col-span-9 h-1 bg-slate-200 rounded"></div></div>
                                                    <div class="grid grid-cols-12 gap-1"><div class="col-span-3 h-1 bg-slate-400 rounded"></div><div class="col-span-9 h-1 bg-slate-200 rounded"></div></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'ats_classic'">
                                                <div class="space-y-1 h-full text-center">
                                                    <div class="h-1.5 w-10 bg-slate-800 rounded mx-auto"></div>
                                                    <div class="h-px w-full bg-slate-800"></div>
                                                    <div class="h-1 w-full bg-slate-200 rounded"></div>
                                                    <div class="h-1 w-5/6 bg-slate-200 rounded"></div>
                                                </div>
                                            </template>
                                            <template x-if="key === 'compact_onepage'">
                                                <div class="space-y-1 h-full">
                                                    <div class="grid grid-cols-3 gap-0.5"><div class="h-1.5 bg-slate-100 rounded"></div><div class="h-1.5 bg-slate-100 rounded"></div><div class="h-1.5 bg-slate-100 rounded"></div></div>
                                                    <div class="h-2 w-full rounded" :style="'background:' + accent_color + '15'"></div>
                                                    <div class="grid grid-cols-2 gap-1"><div class="h-2 bg-slate-100 rounded"></div><div class="h-2 bg-slate-100 rounded"></div></div>
                                                </div>
                                            </template>
                                        </div>

                                        <div>
                                            <div class="flex items-center justify-between gap-1 mb-0.5">
                                                <span class="font-bold text-slate-900 truncate" x-text="st.name"></span>
                                                <span x-show="template_style === key" class="text-[9.5px] font-extrabold text-[#184f3a] shrink-0">Active</span>
                                            </div>
                                            <span x-show="st.badge" class="inline-block text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 mb-1" x-text="st.badge"></span>
                                            <p class="text-[10px] text-slate-500 leading-snug line-clamp-2" x-text="st.tagline"></p>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div x-show="designTab === 'presets'" class="space-y-2.5">
                        <template x-if="hasCustomCv">
                            <div class="p-3 rounded-2xl bg-[#e8f7ed] border border-[#c8f3dc] text-[11px] text-slate-700 space-y-2">
                                <div class="font-bold text-slate-900">Your uploaded CV stays active</div>
                                <p class="leading-snug text-slate-600">Clicking any template applies its layout, colors &amp; typography around your own CV data.</p>
                            </div>
                        </template>
                        <template x-for="tpl in allTemplates" :key="tpl.slug">
                            <div class="w-full p-3.5 rounded-2xl border border-slate-200 hover:border-[#184f3a] hover:bg-[#e8f7ed]/40 transition flex items-center justify-between gap-3">
                                <button type="button" @click="applyTemplatePreset(tpl, false)" class="text-left flex-1 min-w-0">
                                    <div class="text-[10px] font-bold uppercase tracking-wider" :style="'color:' + tpl.accent_color" x-text="tpl.category_label"></div>
                                    <div class="font-bold text-slate-900 mt-0.5" x-text="tpl.name"></div>
                                    <div class="text-[11px] text-slate-500 line-clamp-1" x-text="tpl.description"></div>
                                </button>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button type="button" @click="applyTemplatePreset(tpl, false)"
                                            class="px-2.5 py-1 rounded-full bg-slate-900 text-white text-[10px] font-bold hover:bg-[#184f3a] transition"
                                            x-text="hasCustomCv ? 'Apply Theme' : 'Load'"></button>
                                    <button type="button" x-show="hasCustomCv" @click="applyTemplatePreset(tpl, true)"
                                            class="px-2 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold hover:bg-slate-200 transition" title="Replace your text with this template's sample CV">Sample</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-between items-center">
                    <a href="{{ route('resume.templates') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">Full Resume Gallery</a>
                    <button type="button" @click="designDrawerOpen = false" class="px-5 py-2 rounded-full bg-slate-900 text-white text-xs font-bold">Done</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================ -->
    <!-- SAVED RESUMES DRAWER                                             -->
    <!-- ================================================================ -->
    <div x-cloak x-data="{ open: false }" @open-resume-history.window="open = true" x-show="open" class="no-print fixed inset-0 z-50 overflow-hidden" @keydown.escape.window="open = false">
        <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-xs" @click="open = false"></div>
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-4 sm:pl-10">
            <div class="w-full max-w-md bg-white border-l border-border-subtle shadow-2xl flex flex-col">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Saved Resumes</h3>
                        <p class="text-xs text-slate-500">@auth Synced to {{ Auth::user()->name }} @else Recent resumes from this session @endauth</p>
                    </div>
                    <button type="button" @click="open = false" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-3 text-xs">
                    @guest
                    <div class="p-4 rounded-2xl bg-[#e8f7ed]/80 border border-[#c8f3dc] space-y-3 mb-4">
                        <div class="font-bold text-slate-900">Save resumes across devices?</div>
                        <p class="text-[11px] text-slate-600 leading-relaxed">Sign in or create a free account anytime to permanently sync your resumes &amp; invoices.</p>
                        <div class="flex gap-2">
                            <a href="{{ route('login') }}" class="flex-1 text-center py-2 rounded-xl bg-slate-900 text-white font-bold">Login</a>
                            <a href="{{ route('register') }}" class="flex-1 text-center py-2 rounded-xl bg-white border border-[#c8f3dc] text-[#184f3a] font-bold">Sign Up Free</a>
                        </div>
                    </div>
                    @endguest

                    @forelse($recentResumes as $past)
                        <div class="p-4 rounded-2xl border border-slate-200 hover:border-[#184f3a] bg-slate-50/50 transition">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-sm text-slate-900">{{ $past->title }}</span>
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $past->accent_color ?: '#162a42' }}"></span>
                            </div>
                            <div class="text-slate-500 mb-3">{{ $past->content['personal']['full_name'] ?? 'Resume' }} &bull; {{ $past->created_at->diffForHumans() }}</div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('resume.builder', ['load' => $past->uuid]) }}" class="px-3 py-1 bg-white border border-slate-300 rounded-full font-semibold text-slate-700 hover:border-[#184f3a]">Edit</a>
                                <a href="{{ route('resume.pdf', $past->uuid) }}" class="px-3 py-1 bg-white border border-slate-300 rounded-full font-semibold text-slate-700 hover:border-[#184f3a]">PDF</a>
                                <a href="{{ route('resume.show', $past->uuid) }}" target="_blank" class="px-3 py-1 bg-white border border-slate-300 rounded-full font-semibold text-slate-700 hover:border-[#184f3a]">View</a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400">
                            No saved resumes yet. Click "Save Resume" or "Download PDF" to keep your resume here.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- CV Maker Editorial Guide, H2 Sections, FAQ & Internal Cross-Links --}}
    <section class="no-print max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-14 space-y-8">
        <div class="bg-white rounded-[26px] border border-[#dfe4df] p-6 sm:p-10 shadow-sm space-y-10">
            {{-- H2 1: Create a CV free online and download as PDF --}}
            <div class="space-y-3">
                <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">Free Online CV Maker</span>
                <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b]">Create a CV free online and download as PDF</h2>
                <p class="text-xs sm:text-sm text-[#46534b] leading-relaxed">
                    Building a professional curriculum vitae should not require getting trapped behind a surprise subscription paywall or paying a recurring fee just to export your document. Formnexa's free online CV maker enables you to select an executive layout, enter your career milestones, and download a crisp vector PDF directly from your browser in minutes. Every exported CV is 100% watermark-free with selectable text, allowing you to submit your job application immediately. If a recruiter or hiring manager specifically requests an editable Word document, you can also convert your finished resume with our <a href="{{ route('convert.tool', 'pdf-to-word') }}" class="text-[#184f3a] font-bold hover:underline">PDF to Word Converter</a> without losing formatting.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-[#dfe4df]">
                {{-- H2 2: Make a CV on your phone --}}
                <div class="space-y-3">
                    <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#6142be]">Mobile-First Editing</span>
                    <h2 class="font-display text-lg sm:text-xl font-extrabold text-[#17211b]">Make a CV on your phone</h2>
                    <p class="text-xs sm:text-sm text-[#46534b] leading-relaxed">
                        Many professionals and students write and update their resumes directly on a smartphone. Formnexa is engineered with a mobile-responsive interface that works seamlessly in mobile Google Chrome and Apple Safari on Android and iPhone devices. You can draft your summary, paste bullet points from your phone notes or LinkedIn, switch color palettes, and download the vector PDF straight to your device's Downloads folder without installing third-party apps. For a walkthrough with mobile tips, check out our guide on <a href="{{ route('blog.show', 'how-to-make-cv-on-phone-free-guide') }}" class="text-[#184f3a] font-bold hover:underline">how to make a CV on your phone for free</a>.
                    </p>
                </div>

                {{-- H2 3: Edit your existing CV online --}}
                <div class="space-y-3">
                    <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">Instant Import &amp; Rebuild</span>
                    <h2 class="font-display text-lg sm:text-xl font-extrabold text-[#17211b]">Edit your existing CV online</h2>
                    <p class="text-xs sm:text-sm text-[#46534b] leading-relaxed">
                        If you already have a saved resume in PDF or Word format, you do not have to start from scratch. Click <strong>Convert CV to ATS / Import</strong> in the top toolbar or visit our dedicated <a href="{{ route('resume.ats_converter') }}" class="text-[#184f3a] font-bold hover:underline">ATS Resume Converter</a>. Our parser reads your work experience, education history, and technical competencies into editable builder fields in under 60 seconds, letting you refresh bullet points and update contact information effortlessly. For tips on adjusting older files, read <a href="{{ route('blog.show', 'how-to-edit-pdf-cv-online-free-guide') }}" class="text-[#184f3a] font-bold hover:underline">how to edit a PDF CV online for free</a>.
                    </p>
                </div>
            </div>

            {{-- H2 4: ATS-friendly CV templates --}}
            <div class="space-y-3 pt-4 border-t border-[#dfe4df]">
                <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#6142be]">Screening Software Standards</span>
                <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b]">ATS-friendly CV templates</h2>
                <p class="text-xs sm:text-sm text-[#46534b] leading-relaxed">
                    Applicant Tracking Systems evaluate CVs line by line from left to right and top to bottom. Decorative multi-column grids, complex graphic skill meters, and text placed inside images frequently trigger parsing errors in automated hiring software like Workday, Greenhouse, Taleo, and Lever. Formnexa's <a href="{{ route('resume.templates') }}" class="text-[#184f3a] font-bold hover:underline">{{ count($allTemplates) }} modern &amp; ATS CV templates</a> use single-column and clean linear hierarchies with recognized headings so your qualifications reach hiring managers accurately. Wondering how traditional visual designs stack up against automated filters? Read our side-by-side analysis on <a href="{{ route('blog.show', 'canva-cv-vs-ats-friendly-resume-guide') }}" class="text-[#184f3a] font-bold hover:underline">Canva CV vs ATS-Friendly CV</a>. If you are applying for corporate roles locally, see the <a href="{{ route('blog.show', 'cv-format-for-jobs-in-pakistan-guide') }}" class="text-[#184f3a] font-bold hover:underline">standard CV format for jobs in Pakistan</a>, or our entry-level <a href="{{ route('blog.show', 'cv-format-for-freshers-free-template-guide') }}" class="text-[#184f3a] font-bold hover:underline">CV format for freshers and recent graduates</a>.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-[26px] border border-[#dfe4df] p-6 sm:p-10 shadow-sm">
            <x-faq :items="[
                ['q' => 'Can I create a CV free online and download it as a PDF?', 'a' => 'Yes. Formnexa allows you to create your CV online and download a watermark-free vector PDF with zero cost. You can pick any template, customize typography and colors, enter your career milestones, and export your PDF instantly with no hidden trial fees or subscription traps.'],
                ['q' => 'Can I make a CV on my phone?', 'a' => 'Yes. Formnexa is fully responsive and runs smoothly in mobile web browsers on Android and iPhone (Chrome, Safari, Edge). You can enter details, switch styles, and download the finished PDF directly to your mobile device without installing an app.'],
                ['q' => 'Can I edit my existing CV online?', 'a' => 'Yes. Click \'Convert CV to ATS / Import\' in the top bar or use our ATS Resume Converter. Upload your current PDF or Word (.docx) file, or paste your text. Formnexa extracts your experience, education, and skills into editable form fields so you can update dates, change wording, and export a clean PDF.'],
                ['q' => 'Is the CV download really free with no watermark?', 'a' => 'Yes, 100%. Every PDF generated by Formnexa is completely watermark-free and uses crisp vector text. We do not place trial banners, logo stamps, or locked download walls on your document.'],
                ['q' => 'Do I need an account to create and download my CV?', 'a' => 'No. You can build, edit, and download your CV entirely in guest mode without creating an account or providing an email address. Your draft is automatically saved in your browser\'s private local storage. Account registration is optional if you wish to sync drafts across multiple devices.'],
                ['q' => 'What is an ATS-friendly CV?', 'a' => 'An ATS-friendly CV is formatted with machine-readable typography, linear reading hierarchy, and recognized section headers (such as Professional Summary, Work Experience, Education, and Skills). It avoids multi-layer tables and graphical rating meters so Applicant Tracking Systems (like Workday, Taleo, Greenhouse, and Lever) parse your qualifications accurately without dropped text.'],
            ]" heading="Free CV Maker & Resume Builder — Frequently Asked Questions" />
        </div>

        <x-related-tools :tools="[
            [
                'title' => 'Free ATS Resume Checker',
                'description' => 'Test your finished CV against ATS formatting rules and compare missing keywords against your target job description.',
                'url' => route('resume.checker'),
                'badge' => '0-100 Score',
                'cta' => 'Check My ATS Score',
            ],
            [
                'title' => 'ATS Resume Converter',
                'description' => 'Upload an older PDF or Word CV and rebuild it automatically into clean, editable resume sections.',
                'url' => route('resume.ats_converter'),
                'badge' => 'PDF / DOCX',
                'cta' => 'Convert Existing CV',
            ],
            [
                'title' => 'ATS Resume Templates Gallery',
                'description' => 'Browse all ' . count($allTemplates) . ' single-column and modern resume designs ready for one-click editing.',
                'url' => route('resume.templates'),
                'badge' => count($allTemplates) . ' Designs',
                'cta' => 'Browse Templates',
            ],
        ]" heading="Test &amp; Enhance Your Resume" eyebrow="Career Tools" />
    </section>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('resumeStudio', (loadedResume, loadedTemplate, styles, colors, allTemplates, defaultContent, blankContent, openImport) => ({
        styles: styles || {},
        colors: colors || [],
        allTemplates: allTemplates || [],
        mobileTab: 'edit',
        openSection: 'personal',
        designDrawerOpen: false,
        designTab: 'layout',
        importModalOpen: !!openImport,
        importMode: 'text',
        importText: '',
        importFile: null,
        importFileName: '',
        importTargetStyle: 'flow_sidebar',
        importing: false,
        importError: '',
        hasCustomCv: false,

        uuid: loadedResume?.uuid || '',
        activeTemplateSlug: loadedTemplate?.slug || (loadedResume ? '' : 'atlantic-flow'),
        title: loadedResume?.title || loadedTemplate?.name || 'Business Development Resume',
        template_style: loadedResume?.template_style || loadedTemplate?.template_style || loadedTemplate?.style || 'flow_sidebar',
        accent_color: loadedResume?.accent_color || loadedTemplate?.accent_color || '#1B3142',
        font_family: loadedResume?.font_family || loadedTemplate?.font_family || 'sans',
        density: loadedResume?.content?.density || loadedResume?.density || loadedTemplate?.density || 'comfortable',
        photo_data: loadedResume?.photo_data || '',
        content: JSON.parse(JSON.stringify(loadedResume?.content || loadedTemplate?.content || loadedTemplate?.preset_content || defaultContent)),

        skillInput: '',
        saving: false,
        downloading: false,
        toast: { visible: false, message: '', type: 'success' },
        toolStartedTracked: false,

        markStarted() {
            if (!this.toolStartedTracked) {
                this.toolStartedTracked = true;
                if (typeof window.track === 'function') {
                    window.track('tool_started', { tool_name: 'resume_builder' });
                }
            }
        },

        init() {
            const urlParams = new URLSearchParams(window.location.search);
            const s = urlParams.get('style');
            const c = urlParams.get('color');
            const f = urlParams.get('font');
            const d = urlParams.get('density');
            const isBlankReq = urlParams.get('blank') === '1';

            if (isBlankReq) {
                this.hasCustomCv = false;
                this.content = JSON.parse(JSON.stringify(blankContent));
                localStorage.removeItem('formnexa_resume_draft');
            } else if (loadedResume) {
                this.hasCustomCv = true;
            } else {
                const cached = localStorage.getItem('formnexa_resume_draft');
                if (cached) {
                    try {
                        const p = JSON.parse(cached);
                        if (!loadedTemplate && p && p.content) {
                            this.title = p.title || this.title;
                            this.template_style = p.template_style || this.template_style;
                            this.accent_color = p.accent_color || this.accent_color;
                            this.font_family = p.font_family || this.font_family;
                            this.density = p.density || this.density;
                            this.photo_data = p.photo_data || '';
                            this.hasCustomCv = !!p.hasCustomCv;
                            this.content = p.content;
                            this.activeTemplateSlug = p.activeTemplateSlug || this.findMatchingSlug(this.template_style, this.accent_color);
                        } else if (loadedTemplate && p && p.hasCustomCv && p.content && (p.content.personal?.full_name || '').trim() !== '') {
                            // Preserve the user's imported CV content while switching onto the newly selected template theme
                            this.hasCustomCv = true;
                            this.photo_data = p.photo_data || '';
                            this.content = p.content;
                            if (!this.photo_data && this.content.personal) {
                                this.content.personal.show_photo = !!loadedTemplate.show_photo;
                            }
                            this.title = (this.content.personal?.full_name || loadedTemplate.name) + ' — Resume';
                        }
                    } catch (e) {}
                }
            }

            if (s && this.styles[s]) this.template_style = s;
            if (c && /^#[0-9A-Fa-f]{6}$/.test(c)) this.accent_color = c;
            if (f && ['sans', 'serif', 'mono'].includes(f)) this.font_family = f;
            if (d && ['comfortable', 'compact'].includes(d)) this.density = d;

            if (loadedTemplate) {
                this.showToast(this.hasCustomCv
                    ? `Applied ${loadedTemplate.name} theme to your CV`
                    : `Loaded template: ${loadedTemplate.name}`);
            }

            this.importTargetStyle = this.template_style;

            this.$watch('template_style', (val) => {
                if (val) this.importTargetStyle = val;
            });
            this.$watch('content', () => { this.markStarted(); this.autoSave(); }, { deep: true });
            this.$watch('title', () => { this.markStarted(); this.autoSave(); });
        },

        findMatchingSlug(style, accent) {
            const exact = this.allTemplates.find(t => (t.template_style || t.style) === style && (t.accent_color || '').toLowerCase() === (accent || '').toLowerCase());
            if (exact) return exact.slug;
            const byStyle = this.allTemplates.find(t => (t.template_style || t.style) === style);
            return byStyle ? byStyle.slug : '';
        },

        initials() {
            const name = (this.content?.personal?.full_name || 'FN').trim();
            if (!name) return 'FN';
            const parts = name.split(/\s+/);
            return ((parts[0]?.[0] || '') + (parts.length > 1 ? (parts[parts.length - 1]?.[0] || '') : '')).toUpperCase() || 'FN';
        },

        avatarUrl() {
            if (this.photo_data) return this.photo_data;
            const preset = this.content?.personal?.preset_avatar || '';
            if (!preset) return '';
            const base = @js(asset(''));
            return (preset.startsWith('http') || preset.startsWith('data:')) ? preset : (base + preset.replace(/^\/+/, ''));
        },

        parseBullets(text) {
            if (!text) return [];
            return text.split(/\r?\n/)
                .map(line => line.replace(/^[\s•\-\*▪▸]+/, '').trim())
                .filter(Boolean);
        },

        toggleSection(sec) {
            this.openSection = this.openSection === sec ? '' : sec;
        },

        hasNonEmpty(arr, keys) {
            if (!Array.isArray(arr) || arr.length === 0) return false;
            return arr.some(row => keys.some(k => row && String(row[k] || '').trim() !== ''));
        },

        addExperience() {
            this.content.experience.push({ role: '', company: '', location: '', start_date: '', end_date: '', bullets_text: '' });
            this.content.section_visibility.experience = true;
        },

        addEducation() {
            this.content.education.push({ degree: '', school: '', location: '', start_date: '', end_date: '', details: '' });
            this.content.section_visibility.education = true;
        },

        moveItem(arr, idx, dir) {
            const target = idx + dir;
            if (target < 0 || target >= arr.length) return;
            const copy = [...arr];
            [copy[idx], copy[target]] = [copy[target], copy[idx]];
            this.content.experience = copy;
        },

        addSkillInput() {
            const raw = this.skillInput.trim();
            if (!raw) return;
            raw.split(/[,|;]+/).forEach(part => {
                const s = part.trim();
                if (s && !this.content.skills.includes(s)) {
                    this.content.skills.push(s);
                }
            });
            this.content.section_visibility.skills = true;
            this.skillInput = '';
        },

        handlePhotoUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photo_data = e.target.result;
                this.content.personal.show_photo = true;
                this.autoSave();
                this.showToast('Profile photo updated');
            };
            reader.readAsDataURL(file);
        },

        onImportFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.markStarted();
            this.importFile = file;
            this.importFileName = file.name;
            this.importError = '';

            const ext = (file.name.split('.').pop() || 'pdf').toLowerCase();
            if (typeof window.track === 'function') {
                window.track('file_uploaded', { tool_name: 'resume_builder', file_type: ext });
            }
        },

        fillSampleImportText() {
            this.markStarted();
            this.importMode = 'text';
            this.importText = `Jordan Michaels
Senior Product Manager
jordan.michaels@example.com | +1 415-890-3321 | San Francisco, CA | linkedin.com/in/jordanmichaels

PROFILE
Strategic Product Manager with 6+ years leading SaaS workflow products from discovery through enterprise scale. Focused on customer retention, analytics, and cross-functional agile delivery.

WORK EXPERIENCE
Senior Product Manager | Horizon Cloud | San Francisco, CA | 2022 - Present
• Launched enterprise self-serve onboarding increasing trial-to-paid conversion by 34%.
• Led roadmap for 3 engineering squads shipping real-time collaboration tools.

Product Manager | PeakMetrics | Austin, TX | 2019 - 2022
• Managed B2B analytics platform serving 1,800+ paying organizations.
• Reduced churn by 18% via automated health-score alerts and billing insights.

EDUCATION
MBA, Technology Management | UC Berkeley Haas | 2017 - 2019

SKILLS
Product Strategy, Roadmapping, SQL, A/B Testing, Agile / Scrum, Figma, User Research, Go-To-Market

LANGUAGES
English (Native), French (Conversational)

CERTIFICATIONS
Certified Scrum Product Owner (CSPO) - 2021`;
        },

        async runImport() {
            this.importError = '';
            if (this.importMode === 'file' && !this.importFile) {
                this.importError = 'Please select a PDF, DOCX, TXT, or JSON CV file first.';
                return;
            }
            if (this.importMode === 'text' && !this.importText.trim()) {
                this.importError = 'Please paste your CV text or click "Insert Sample CV Text".';
                return;
            }

            this.importing = true;
            try {
                let response;
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                if (this.importMode === 'file') {
                    const fd = new FormData();
                    fd.append('cv_file', this.importFile);
                    response = await fetch('/api/resumes/import', {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: fd,
                    });
                } else {
                    response = await fetch('/api/resumes/import', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify({ cv_text: this.importText }),
                    });
                }

                const data = await response.json();
                if (!response.ok || !data.success) {
                    this.importError = data.message || 'Could not parse CV.';
                    return;
                }

                const targetStyle = this.importTargetStyle || this.template_style;
                const urlTplSlug = new URLSearchParams(window.location.search).get('template') || '';
                const matchingTpl = this.allTemplates.find(t => t.slug === urlTplSlug && (t.template_style === targetStyle || t.style === targetStyle))
                    || this.allTemplates.find(t => t.template_style === targetStyle || t.style === targetStyle)
                    || null;
                const parsed = data.parsed;

                // Adjust photo visibility & density automatically so imported CVs fit cleanly into the chosen theme
                const supportsPhoto = matchingTpl ? !!matchingTpl.show_photo : ['flow_sidebar', 'creative_card', 'timeline_pro'].includes(targetStyle);
                if (!parsed.personal) parsed.personal = {};
                parsed.personal.show_photo = !!this.photo_data || supportsPhoto;

                const bulletCount = (parsed.experience || []).reduce((acc, e) => acc + ((e.bullets_text || '').split(/\n/).filter(Boolean).length), 0);
                if ((parsed.experience?.length || 0) >= 4 || (parsed.education?.length || 0) >= 3 || (parsed.skills?.length || 0) >= 10 || bulletCount >= 10) {
                    this.density = 'compact';
                }

                this.content = parsed;
                this.template_style = targetStyle;
                this.hasCustomCv = true;
                if (parsed?.personal?.full_name) {
                    this.title = parsed.personal.full_name + ' Resume';
                }
                this.importModalOpen = false;
                this.openSection = 'personal';
                this.autoSave();
                if (typeof window.track === 'function') {
                    window.track('conversion_completed', { tool_name: 'resume_builder' });
                }
                this.showToast(`Applied CV to ${this.styles[targetStyle]?.name || 'template'} (${data.stats.experience} roles, ${data.stats.skills} skills)!`);
            } catch (e) {
                console.error(e);
                this.importError = 'Network or parsing error. Please try pasting CV text directly.';
            } finally {
                this.importing = false;
            }
        },

        applyTemplatePreset(tpl, forceResetSample = false) {
            if (!tpl) return;
            this.markStarted();
            if (typeof window.track === 'function') {
                window.track('template_selected', { template_name: tpl.name || tpl.slug || tpl.style || 'template' });
            }
            const nextStyle = tpl.template_style || tpl.style || 'flow_sidebar';
            this.activeTemplateSlug = tpl.slug || '';
            this.template_style = nextStyle;
            this.importTargetStyle = nextStyle;
            this.accent_color = tpl.accent_color || '#1B3142';
            this.font_family = tpl.font_family || 'sans';
            this.density = tpl.density || 'comfortable';

            if (!this.hasCustomCv || forceResetSample) {
                this.title = tpl.name;
                this.content = JSON.parse(JSON.stringify(tpl.content || tpl.preset_content));
                this.hasCustomCv = false;
                this.showToast(`Loaded template: ${tpl.name}`);
            } else {
                // Keep user's imported/customized CV content intact and adapt layout tokens to the chosen theme
                if (!this.photo_data && this.content?.personal) {
                    this.content.personal.show_photo = !!tpl.show_photo;
                }
                this.showToast(`Adjusted your CV to ${tpl.name} theme`);
            }

            this.designDrawerOpen = false;
            this.autoSave();
        },

        resetToBlank() {
            if (confirm('Clear all fields to start a blank resume?')) {
                this.uuid = '';
                this.photo_data = '';
                this.hasCustomCv = false;
                this.title = 'My Resume';
                this.content = JSON.parse(JSON.stringify(blankContent));
                localStorage.removeItem('formnexa_resume_draft');
                this.showToast('Cleared to blank resume');
            }
        },

        autoSave() {
            try {
                localStorage.setItem('formnexa_resume_draft', JSON.stringify({
                    activeTemplateSlug: this.activeTemplateSlug,
                    title: this.title,
                    template_style: this.template_style,
                    accent_color: this.accent_color,
                    font_family: this.font_family,
                    density: this.density,
                    photo_data: this.photo_data,
                    hasCustomCv: this.hasCustomCv,
                    content: this.content,
                }));
            } catch (e) {}
        },

        async saveResume() {
            this.saving = true;
            this.content.density = this.density;
            try {
                const response = await fetch('/api/resumes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        uuid: this.uuid,
                        title: this.title,
                        template_style: this.template_style,
                        accent_color: this.accent_color,
                        font_family: this.font_family,
                        photo_data: this.photo_data,
                        content: this.content,
                    }),
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    this.uuid = data.uuid;
                    this.showToast(data.message || 'Resume saved!');
                    return data;
                }
                this.showToast(data.message || 'Could not save resume.', 'error');
                return null;
            } catch (e) {
                console.error(e);
                this.showToast('Failed to save resume.', 'error');
                return null;
            } finally {
                this.saving = false;
            }
        },

        async downloadPdf() {
            this.downloading = true;
            try {
                const data = await this.saveResume();
                if (data && data.pdf_url) {
                    if (typeof window.track === 'function') {
                        window.track('document_downloaded', { tool_name: 'resume_builder', format: 'pdf' });
                    }
                    window.location.href = data.pdf_url;
                }
            } finally {
                this.downloading = false;
            }
        },

        async shareLink() {
            const data = await this.saveResume();
            if (data && data.view_url) {
                if (navigator.clipboard) {
                    await navigator.clipboard.writeText(data.view_url);
                    this.showToast('Shareable resume link copied!');
                } else {
                    prompt('Copy your resume link:', data.view_url);
                }
            }
        },

        showToast(message, type = 'success') {
            this.toast = { visible: true, message, type };
            setTimeout(() => { this.toast.visible = false; }, 3500);
        }
    }));
});
</script>
@endpush
