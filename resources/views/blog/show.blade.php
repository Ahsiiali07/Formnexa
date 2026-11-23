@extends('layouts.app')

@section('content')
<x-schema.article :post="$post" />

<div class="bg-white min-h-screen">
    {{-- Article Header Banner --}}
    <header class="bg-[#fbfbf7] border-b border-[#dfe4df] pt-8 sm:pt-11 pb-9 sm:pb-12 px-4 sm:px-6">
        <div class="max-w-[1160px] mx-auto space-y-5">
            <x-schema.breadcrumbs :items="[
                ['Home', url('/')],
                ['Blog', route('blog.index')],
                [$post->category_label, route('blog.category', $post->category)],
                [\Illuminate\Support\Str::limit($post->title, 48), route('blog.show', $post->slug), $post->title],
            ]" />

            <div class="max-w-3xl space-y-4">
                <div class="flex flex-wrap items-center gap-2.5 text-xs font-semibold">
                    <a href="{{ route('blog.category', $post->category) }}"
                       class="px-3 py-1 rounded-full bg-[#e8f7ed] text-[#184f3a] border border-[#c8f3dc] hover:bg-[#d4eedc] transition">
                        {{ $post->category_label }}
                    </a>
                    <span class="text-[#68736d]">{{ $post->reading_time_minutes }} min read</span>
                    <span class="text-[#68736d]">&bull; Updated {{ ($post->updated_at ?? $post->published_at ?? $post->created_at)?->format('M j, Y') }}</span>
                    @if(!$post->is_published)
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800">Draft Preview</span>
                    @endif
                </div>

                <h1 class="font-display text-3xl sm:text-5xl font-extrabold text-[#17211b] tracking-[-0.04em] leading-[1.08]">
                    {{ $post->title }}
                </h1>

                <p class="text-base sm:text-lg text-[#536058] leading-relaxed">
                    {{ $post->excerpt }}
                </p>
            </div>
        </div>
    </header>

    {{-- Main Reading Grid + Sticky Sidebar --}}
    <div class="max-w-[1160px] mx-auto px-4 sm:px-6 py-10 sm:py-14">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

            {{-- Article Prose Body --}}
            <article class="lg:col-span-8 min-w-0">

                {{-- Prominent ATS Resume Builder & Template Showcase Widget for Resume Guides --}}
                @if($post->category === 'resumes' && !empty($resumeTemplates))
                    <div class="mb-10 rounded-3xl bg-gradient-to-b from-[#f2f8f4] to-white border-2 border-[#c8f3dc] p-6 sm:p-8 shadow-sm space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#dfe4df]">
                            <div class="space-y-1">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-[#c8f3dc] text-[#184f3a] font-mono text-[11px] font-bold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#184f3a] animate-pulse"></span>
                                    <span>Interactive Resume Studio</span>
                                </span>
                                <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                                    Convert or Build Your ATS Resume Online Free
                                </h2>
                                <p class="text-xs sm:text-sm text-[#536058] leading-relaxed max-w-xl">
                                    Upload your current Word (.docx) or PDF resume to rebuild it in a clean ATS layout in seconds, or pick a curated template below to start editing immediately.
                                </p>
                            </div>
                            <div class="flex flex-wrap sm:flex-col gap-2 shrink-0">
                                <a href="{{ route('resume.ats_converter') }}"
                                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-xs font-bold transition shadow-sm whitespace-nowrap">
                                    <x-icon name="upload" class="w-3.5 h-3.5" />
                                    <span>Convert Existing CV</span>
                                </a>
                                <a href="{{ route('resume.builder', ['blank' => 1]) }}"
                                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-white border border-[#c6d1ca] hover:border-[#184f3a] text-[#17211b] text-xs font-bold transition whitespace-nowrap">
                                    <span>Start Fresh Blank CV</span>
                                </a>
                            </div>
                        </div>

                        {{-- Template Visual Cards Grid --}}
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-xs font-extrabold text-[#17211b]">Curated ATS Templates (Click to open in Studio):</span>
                                <a href="{{ route('resume.templates') }}" class="text-xs font-bold text-[#5d3ec4] hover:underline whitespace-nowrap">
                                    Browse all 17 templates &rarr;
                                </a>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                @foreach($resumeTemplates as $tpl)
                                    <div class="p-3.5 rounded-2xl bg-white border border-[#dfe4df] hover:border-[#184f3a] hover:shadow-md transition flex flex-col justify-between gap-3 group">
                                        <div class="flex items-start gap-3">
                                            {{-- Mini Mockup --}}
                                            <div class="w-10 h-14 rounded-md border border-slate-200 bg-white p-1.5 flex flex-col justify-between shrink-0 shadow-2xs group-hover:scale-105 transition-transform"
                                                 style="background-color: {{ ($tpl['style'] ?? '') === 'editorial_figma' ? '#FAF8F4' : '#FFFFFF' }}">
                                                <div class="h-1.5 w-full rounded-xs" style="background-color: {{ $tpl['accent_color'] ?? '#1B3142' }}"></div>
                                                <div class="space-y-0.5 my-auto">
                                                    <div class="h-0.5 w-3/4 bg-slate-300 rounded-xs"></div>
                                                    <div class="h-0.5 w-full bg-slate-200 rounded-xs"></div>
                                                    <div class="h-0.5 w-5/6 bg-slate-200 rounded-xs"></div>
                                                </div>
                                                <div class="h-0.5 w-1/2 bg-slate-300 rounded-xs"></div>
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-1.5 mb-0.5">
                                                    <h3 class="font-bold text-xs sm:text-[13px] text-[#17211b] truncate group-hover:text-[#184f3a]">
                                                        {{ $tpl['name'] }}
                                                    </h3>
                                                </div>
                                                <span class="inline-block px-1.5 py-0.2 rounded bg-[#e8f7ed] text-[#184f3a] text-[9.5px] font-bold mb-1">
                                                    {{ $tpl['badge'] ?? $tpl['category_label'] }}
                                                </span>
                                                <p class="text-[11px] text-[#68736d] line-clamp-2 leading-snug">
                                                    {{ $tpl['tagline'] ?? $tpl['description'] }}
                                                </p>
                                            </div>
                                        </div>

                                        <a href="{{ route('resume.builder', ['template' => $tpl['slug']]) }}"
                                           class="w-full py-2 rounded-xl bg-[#f8f8f3] group-hover:bg-[#184f3a] group-hover:text-white text-xs font-bold text-[#17211b] transition text-center flex items-center justify-center gap-1.5">
                                            <span>Use This Template</span>
                                            <span aria-hidden="true">&rarr;</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-2 flex flex-wrap items-center justify-between gap-3 text-[11px] text-[#68736d] border-t border-[#dfe4df]">
                            <div class="flex items-center gap-3">
                                <span>&check; 100% Free</span>
                                <span>&check; Zero Watermark</span>
                                <span>&check; Machine-Readable Vector PDF</span>
                            </div>
                            <a href="{{ route('resume.builder') }}" class="font-bold text-[#184f3a] hover:underline">
                                Open Blank Resume Studio &rarr;
                            </a>
                        </div>
                    </div>
                @endif

                <div class="max-w-none">
                    {!! $renderedHtml !!}
                </div>

                {{-- Mid/End Tool Callout Box --}}
                @if($post->resolveToolUrl())
                    <div class="mt-10 rounded-[24px] bg-[#e8f7ed] border border-[#c8f3dc] p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                        <div class="space-y-1.5">
                            <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">Try It Free Now</span>
                            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                                {{ $post->featured_tool_label ?: 'Open the Matching Formnexa Tool' }}
                            </h2>
                            <p class="text-xs sm:text-sm text-[#46534b]">Free PDF download, zero watermarks, and no account required.</p>
                        </div>
                        <a href="{{ $post->resolveToolUrl() }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-xs sm:text-sm font-bold transition shrink-0 whitespace-nowrap">
                            <span>Open Tool</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                @endif
            </article>

            {{-- Sticky Desktop Sidebar: Table of Contents + Quick Tool Box --}}
            <aside class="lg:col-span-4 lg:sticky lg:top-24 space-y-6">
                @if(count($toc) > 0)
                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-5 space-y-3">
                        <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">On This Page</div>
                        <nav aria-label="Article contents" class="space-y-2 text-xs sm:text-[13px]">
                            @foreach($toc as $heading)
                                <a href="#{{ $heading['id'] }}"
                                   class="block font-medium text-[#46534b] hover:text-[#184f3a] transition leading-snug">
                                    {{ $heading['text'] }}
                                </a>
                            @endforeach
                        </nav>
                    </div>
                @endif

                @if($post->category === 'resumes')
                    <div class="rounded-2xl bg-[#193e30] text-white p-6 space-y-4 shadow-sm border border-[#2d5f4c]">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#c8f3dc]/20 text-[#c8f3dc] text-[10px] font-mono font-bold uppercase tracking-wider">
                            ATS Compliant Tool
                        </div>
                        <h3 class="font-display text-xl font-extrabold tracking-tight">Convert &amp; Build ATS Resume</h3>
                        <p class="text-xs text-[#b7d2c4] leading-relaxed">
                            Single-column linear layouts engineered to pass automated screening software like Workday, Taleo, and Greenhouse.
                        </p>
                        <div class="space-y-2 pt-1 text-xs font-bold">
                            <a href="{{ route('resume.ats_converter') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white text-[#184f3a] hover:bg-[#e8f7ed] transition shadow-xs">
                                <span>Convert CV (PDF/Word)</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                            <a href="{{ route('resume.builder') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition">
                                <span>Open Resume Studio</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                            <a href="{{ route('resume.templates') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition">
                                <span>Browse 17 Templates</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl bg-[#193e30] text-white p-6 space-y-4">
                        <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#c8f3dc]">Formnexa Suite</div>
                        <h3 class="font-display text-xl font-extrabold tracking-tight">Generate documents in seconds</h3>
                        <p class="text-xs text-[#b7d2c4] leading-relaxed">
                            Build invoices, quotes, ATS resumes, or convert PDF files cleanly without sign-up friction.
                        </p>
                        <div class="space-y-2 pt-1 text-xs font-bold">
                            <a href="{{ route('invoice.studio') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition">
                                <span>Free Invoice Generator</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                            <a href="{{ route('resume.builder') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition">
                                <span>ATS Resume Builder</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                            <a href="{{ route('convert.hub') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition">
                                <span>10 PDF Converters</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </div>
                @endif
            </aside>
        </div>

        {{-- Related Guides --}}
        @if($relatedPosts->isNotEmpty())
            <section class="mt-16 pt-12 border-t border-[#dfe4df] space-y-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">Keep Reading</span>
                        <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] tracking-tight mt-1">Related guides</h2>
                    </div>
                    <a href="{{ route('blog.index') }}" class="text-xs sm:text-sm font-bold text-[#184f3a] hover:underline whitespace-nowrap">
                        All Guides &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach($relatedPosts as $rel)
                        <article class="bg-[#f8f8f3] rounded-2xl border border-[#dfe4df] hover:border-[#184f3a] p-5 flex flex-col justify-between gap-4 transition">
                            <div class="space-y-2.5">
                                <span class="text-[11px] font-bold text-[#184f3a]">{{ $rel->category_label }}</span>
                                <h3 class="font-display text-lg font-extrabold text-[#17211b] leading-snug">
                                    <a href="{{ route('blog.show', $rel->slug) }}" class="hover:text-[#184f3a] transition">{{ $rel->title }}</a>
                                </h3>
                                <p class="text-xs text-[#536058] leading-relaxed">{{ \Illuminate\Support\Str::limit($rel->excerpt, 120) }}</p>
                            </div>
                            <a href="{{ route('blog.show', $rel->slug) }}" class="text-xs font-bold text-[#184f3a] hover:underline">
                                Read article &rarr;
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    {{-- Floating ATS Quick-Access Bar (Popup) for Resume Articles --}}
    @if($post->category === 'resumes')
        <div x-data="{ visible: false, dismissed: false }"
             @scroll.window="visible = (window.pageYOffset > 380 && !dismissed)"
             x-show="visible" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-6"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-6"
             class="no-print fixed bottom-4 left-3 right-3 sm:left-auto sm:right-6 sm:max-w-xl z-50 bg-[#17211b] text-white p-3.5 sm:p-4 rounded-2xl shadow-2xl border border-white/10 flex items-center justify-between gap-3 sm:gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-xl bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center shrink-0 font-bold">
                    <x-icon name="document-text" class="w-5 h-5 text-[#184f3a]" />
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-white truncate">Convert Your CV to ATS Format Free</div>
                    <div class="text-[11px] text-[#a7bab0] truncate">Upload PDF/DOCX or pick from 17 ATS templates</div>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('resume.ats_converter') }}"
                   class="px-3.5 py-2 rounded-xl bg-[#184f3a] hover:bg-[#1f664b] text-white text-xs font-bold transition whitespace-nowrap shadow-sm">
                    Convert CV
                </a>
                <a href="{{ route('resume.builder') }}"
                   class="hidden sm:inline-flex px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition whitespace-nowrap">
                    Live Studio
                </a>
                <button type="button" @click="dismissed = true" class="p-1 rounded-lg text-white/60 hover:text-white hover:bg-white/10 transition cursor-pointer" aria-label="Dismiss">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endif
</div>
@endsection
