@extends('layouts.app')

@section('title', 'Free CV & Resume Templates — Figma, FlowCV & ATS Styles | Formnexa')
@section('meta_description', 'Browse 15 designer CV & resume templates inspired by Figma Community, FlowCV & Template.net. Customize layouts, colors, fonts & export PDFs for free.')

@section('content')
<div x-data="resumeGalleryPage()" class="min-h-screen bg-white">

    {{-- Modern Electric Precision Hero Banner --}}
    <section class="relative overflow-hidden bg-[#f8f8f3] border-b border-border-subtle pt-10 pb-10 sm:pb-12 px-4 sm:px-6">
        <div class="max-w-[1280px] mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div class="max-w-3xl">
                    <div class="inline-flex flex-wrap items-center gap-2 px-3 py-1 rounded-full bg-white border border-border-subtle text-emerald-700 font-mono text-[11px] font-semibold uppercase tracking-wider mb-4 shadow-sm">
                        15 Templates &bull; 10 Distinct Layout Engines &bull; No Sign-up Required
                    </div>
                    <h1 class="font-display text-3xl sm:text-5xl font-extrabold tracking-[-0.03em] text-[#17211b] leading-[1.08]">
                        Resume &amp; CV templates crafted like
                        <span class="text-[#184f3a]">Figma</span>, <span class="text-emerald-600">FlowCV</span> &amp; Editorial Print.
                    </h1>
                    <p class="mt-4 text-sm sm:text-lg text-[#536058] max-w-2xl leading-relaxed">
                        Choose from warm editorial serif spreads, FlowCV-style dark portrait sidebars, connected career timelines with skill bars, and 100% ATS-verified Wall Street singles.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2.5 sm:gap-3 shrink-0">
                    <a href="{{ route('resume.builder', ['import' => 1]) }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-primary-container hover:bg-electric-blue-hover text-white text-sm font-bold shadow-sm transition">
                        <span>Import Existing CV &rarr;</span>
                    </a>
                    <a href="{{ route('resume.builder', ['blank' => 1]) }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-white border border-border-strong text-[#17211b] text-sm font-bold hover:border-[#17211b] transition">
                        <span>+ Start Blank</span>
                    </a>
                </div>
            </div>

            {{-- Category Filter Bar --}}
            <div id="resume-gallery" class="mt-8 flex items-center gap-2 overflow-x-auto scrollbar-hide pb-1">
                @foreach($categories as $catKey => $catLabel)
                    <button type="button"
                            @click="activeCategory = '{{ $catKey }}'"
                            class="px-4 py-2 rounded-lg text-xs sm:text-sm font-bold whitespace-nowrap transition border cursor-pointer"
                            :class="activeCategory === '{{ $catKey }}'
                                ? 'bg-[#17211b] text-white border-[#17211b] shadow-sm'
                                : 'bg-white text-[#536058] border-border-subtle hover:border-[#184f3a] hover:text-[#17211b]'">
                        {{ $catLabel }}
                        @if($catKey === 'all')
                            <span class="ml-1 text-[11px] opacity-75">({{ count($templates) }})</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Full-Page Portrait A4 Gallery Grid --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7 lg:gap-8">
            @foreach($templates as $tpl)
                @php
                    $c = $tpl['preset_content'];
                    $p = $c['personal'] ?? [];
                    $accent = $tpl['accent_color'] ?? '#1B3142';
                    $tStyle = $tpl['style'] ?? 'flow_sidebar';
                    $exps = array_slice($c['experience'] ?? [], 0, 2);
                    $edus = array_slice($c['education'] ?? [], 0, 2);
                    $skills = array_slice($c['skills'] ?? [], 0, 7);
                    $langs = array_slice($c['languages'] ?? [], 0, 3);
                    $projs = array_slice($c['projects'] ?? [], 0, 2);
                    $words = preg_split('/\s+/', trim($p['full_name'] ?? 'FN'));
                    $init = strtoupper(substr($words[0] ?? 'F', 0, 1) . substr($words[count($words) - 1] ?? '', 0, 1));
                @endphp

                <article x-show="activeCategory === 'all' || activeCategory === '{{ $tpl['category'] }}'"
                         x-transition.opacity.duration.200ms
                         class="group flex flex-col bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-slate-300 transition-all duration-300 overflow-hidden">

                    {{-- Full Portrait A4 Canvas Showcase Frame (1 : 1.41) --}}
                    <div class="relative bg-slate-100/90 p-4 sm:p-5 border-b border-slate-200/70">

                        {{-- Portrait A4 Miniature Card --}}
                        <div class="relative aspect-[1/1.41] w-full rounded-md overflow-hidden shadow-md border border-slate-200/90 bg-white select-none transition-transform duration-300 group-hover:-translate-y-0.5"
                             @click="openPreview('{{ $tpl['slug'] }}')"
                             style="cursor: pointer;">

                            {{-- 1. FLOW SIDEBAR MINIATURE (FlowCV Flagship) --}}
                            @if($tStyle === 'flow_sidebar')
                                <div class="grid grid-cols-12 h-full text-[7.4px] leading-[1.35]">
                                    <div class="col-span-4 text-white p-3 flex flex-col justify-between" style="background-color: {{ $accent }};">
                                        <div class="space-y-2.5">
                                            <div>
                                                <div class="font-black text-[9.5px] leading-tight text-white">{{ $p['full_name'] ?? '' }}</div>
                                                <div class="text-[6.5px] text-white/80 mt-0.5">{{ $p['job_title'] ?? '' }}</div>
                                                @if(!empty($tpl['show_photo']))
                                                    <div class="w-11 h-11 rounded-full mx-auto mt-2.5 border-2 border-white/30 bg-white/15 flex items-center justify-center font-black text-[10px] text-white overflow-hidden shadow-sm">
                                                        @if(!empty($p['preset_avatar']))
                                                            <img src="{{ asset($p['preset_avatar']) }}" alt="" class="w-full h-full object-cover">
                                                        @else
                                                            {{ $init }}
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="space-y-0.5 text-[6px] text-white/85">
                                                <div class="truncate">{{ $p['email'] ?? '' }}</div>
                                                <div>{{ $p['phone'] ?? '' }}</div>
                                                <div>{{ $p['location'] ?? '' }}</div>
                                            </div>
                                            <div>
                                                <div class="px-1.5 py-0.5 rounded bg-white/10 font-extrabold uppercase tracking-widest text-[5.5px] text-white mb-1">Profile</div>
                                                <p class="text-[6.1px] text-white/85 line-clamp-4">{{ $c['summary'] ?? '' }}</p>
                                            </div>
                                            <div class="space-y-1">
                                                <div class="px-1.5 py-0.5 rounded bg-white/10 font-extrabold uppercase tracking-widest text-[5.5px] text-white">Languages</div>
                                                @foreach($langs as $lg)
                                                    <div class="flex items-center justify-between text-[6.2px]">
                                                        <span>{{ $lg['name'] }}</span>
                                                        <span class="flex gap-0.5">
                                                            @for($d = 1; $d <= 5; $d++)
                                                                <span class="w-1 h-1 rounded-full {{ $d <= ($lg['score'] ?? 4) ? 'bg-white' : 'bg-white/25' }}"></span>
                                                            @endfor
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="text-[5.2px] text-white/35 uppercase tracking-widest">Curriculum Vitae</div>
                                    </div>
                                    <div class="col-span-8 p-3.5 space-y-2.5 bg-white text-slate-800">
                                        <div class="px-2 py-0.5 rounded bg-slate-100 font-extrabold uppercase tracking-widest text-[6.5px] text-slate-700">Work Experience</div>
                                        @foreach($exps as $e)
                                            <div>
                                                <div class="flex justify-between font-bold text-[8px] text-slate-900">
                                                    <span>{{ $e['company'] ?? '' }}</span>
                                                    <span class="text-slate-400 font-normal text-[6.5px]">{{ ($e['start_date'] ?? '') . ' – ' . ($e['end_date'] ?? '') }}</span>
                                                </div>
                                                <div class="font-semibold text-[7px]" style="color: {{ $accent }}">{{ $e['role'] ?? '' }}</div>
                                                <p class="text-[6.5px] text-slate-600 mt-0.5 line-clamp-3">{{ $e['bullets_text'] ?? '' }}</p>
                                            </div>
                                        @endforeach
                                        <div class="px-2 py-0.5 rounded bg-slate-100 font-extrabold uppercase tracking-widest text-[6.5px] text-slate-700">Education</div>
                                        @foreach(array_slice($edus, 0, 1) as $ed)
                                            <div>
                                                <div class="font-bold text-[7.5px]">{{ $ed['degree'] ?? '' }}</div>
                                                <div class="text-[6.5px] text-slate-500">{{ $ed['school'] ?? '' }}</div>
                                            </div>
                                        @endforeach
                                        <div class="px-2 py-0.5 rounded bg-slate-100 font-extrabold uppercase tracking-widest text-[6.5px] text-slate-700">Skills</div>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(array_slice($skills, 0, 6) as $sk)
                                                <span class="px-1.5 py-0.5 rounded bg-slate-100 text-[6px] font-semibold text-slate-700">{{ $sk }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                            {{-- 2. EDITORIAL FIGMA MINIATURE --}}
                            @elseif($tStyle === 'editorial_figma')
                                <div class="h-full bg-[#FAF8F4] p-4 flex flex-col justify-between text-[#1F1C18] text-[7.2px] leading-[1.35]">
                                    <div class="space-y-2.5">
                                        <div class="border-b pb-2 flex justify-between items-start gap-2" style="border-color: rgba(31,28,24,0.18);">
                                            <div class="flex items-center gap-2">
                                                @if(!empty($tpl['show_photo']))
                                                    <div class="w-9 h-9 rounded-full border flex items-center justify-center font-bold text-[9px] shrink-0 overflow-hidden" style="border-color: {{ $accent }}; color: {{ $accent }}; background: #EFECE5;">
                                                        @if(!empty($p['preset_avatar']))
                                                            <img src="{{ asset($p['preset_avatar']) }}" alt="" class="w-full h-full object-cover">
                                                        @else
                                                            {{ $init }}
                                                        @endif
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-[5.5px] uppercase tracking-[0.22em] font-bold" style="color: {{ $accent }}">Curriculum Vitae</div>
                                                    <div class="font-editorial text-[12px] font-bold leading-tight text-[#1A1815]">{{ $p['full_name'] ?? '' }}</div>
                                                    <div class="font-editorial italic text-[7.5px] text-[#59534A]">{{ $p['job_title'] ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="text-right text-[6px] text-[#59534A] space-y-0.5 shrink-0">
                                                <div class="font-semibold text-[#1A1815]">{{ $p['email'] ?? '' }}</div>
                                                <div>{{ $p['location'] ?? '' }}</div>
                                                <div>{{ $p['website'] ?? '' }}</div>
                                            </div>
                                        </div>
                                        <div class="border-b pb-2 grid grid-cols-12 gap-2" style="border-color: rgba(31,28,24,0.12);">
                                            <div class="col-span-3 text-[5.8px] uppercase tracking-[0.18em] font-bold" style="color: {{ $accent }}">Profile</div>
                                            <p class="col-span-9 text-[6.5px] text-[#3D372F] line-clamp-3">{{ $c['summary'] ?? '' }}</p>
                                        </div>
                                        <div class="grid grid-cols-12 gap-3 pt-0.5">
                                            <div class="col-span-8 space-y-2 border-r pr-2" style="border-color: rgba(31,28,24,0.1);">
                                                <div class="font-editorial font-bold text-[8.5px] border-b pb-0.5" style="border-color: rgba(31,28,24,0.15);">Experience</div>
                                                @foreach($exps as $e)
                                                    <div>
                                                        <div class="flex justify-between font-bold text-[7.5px]">
                                                            <span>{{ $e['role'] ?? '' }}</span>
                                                            <span class="font-editorial italic text-[6px] text-[#6B645A]">{{ ($e['start_date'] ?? '') . '–' . ($e['end_date'] ?? '') }}</span>
                                                        </div>
                                                        <div class="font-semibold text-[6.5px]" style="color: {{ $accent }}">{{ $e['company'] ?? '' }}</div>
                                                        <p class="text-[6px] text-[#4B443B] mt-0.5 line-clamp-3">{{ $e['bullets_text'] ?? '' }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-span-4 space-y-2">
                                                <div class="font-editorial font-bold text-[8.5px] border-b pb-0.5" style="border-color: rgba(31,28,24,0.15);">Education</div>
                                                @foreach(array_slice($edus, 0, 1) as $ed)
                                                    <div class="text-[6.3px]">
                                                        <div class="font-bold">{{ $ed['degree'] ?? '' }}</div>
                                                        <div style="color: {{ $accent }}">{{ $ed['school'] ?? '' }}</div>
                                                    </div>
                                                @endforeach
                                                <div class="font-editorial font-bold text-[8.5px] border-b pb-0.5 pt-1" style="border-color: rgba(31,28,24,0.15);">Expertise</div>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach(array_slice($skills, 0, 5) as $sk)
                                                        <span class="px-1 py-0.5 border text-[5.8px] bg-white/60" style="border-color: rgba(31,28,24,0.2);">{{ $sk }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-[5.5px] uppercase tracking-[0.22em] text-[#8C8478] border-t pt-1.5" style="border-color: rgba(31,28,24,0.1);">Figma Editorial Print Sheet</div>
                                </div>

                            {{-- 3. TIMELINE PRO MINIATURE (Template.net Style) --}}
                            @elseif($tStyle === 'timeline_pro')
                                <div class="h-full bg-white flex flex-col text-[7.2px] leading-[1.35]">
                                    <div class="px-3.5 py-2.5 border-b border-slate-200 flex items-center justify-between gap-2" style="background: {{ $accent }}0D;">
                                        <div class="flex items-center gap-2">
                                            <div class="w-1 h-7 rounded-full" style="background-color: {{ $accent }};"></div>
                                            @if(!empty($tpl['show_photo']))
                                                <div class="w-8 h-8 rounded-lg shrink-0 overflow-hidden flex items-center justify-center text-white font-black text-[8px]" style="background-color: {{ $accent }};">
                                                    @if(!empty($p['preset_avatar']))<img src="{{ asset($p['preset_avatar']) }}" alt="" class="w-full h-full object-cover">@else{{ $init }}@endif
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-black text-[10px] text-slate-900">{{ $p['full_name'] ?? '' }}</div>
                                                <div class="font-bold text-[7px]" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right text-[6px] text-slate-500">
                                            <div class="font-semibold text-slate-800">{{ $p['email'] ?? '' }}</div>
                                            <div>{{ $p['location'] ?? '' }}</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-12 gap-2.5 p-3.5 flex-1">
                                        <div class="col-span-8 space-y-2.5">
                                            <div class="text-[6px] font-extrabold uppercase tracking-widest" style="color: {{ $accent }}">Career Timeline</div>
                                            <div class="relative pl-3 border-l-2 space-y-2.5" style="border-color: {{ $accent }}35;">
                                                @foreach($exps as $e)
                                                    <div class="relative">
                                                        <span class="absolute -left-[15.5px] top-0.5 w-2 h-2 rounded-full border bg-white" style="border-color: {{ $accent }};"></span>
                                                        <div class="flex justify-between font-bold text-[7.5px] text-slate-900">
                                                            <span>{{ $e['role'] ?? '' }}</span>
                                                            <span class="px-1 rounded text-[5.5px] font-bold" style="background: {{ $accent }}16; color: {{ $accent }};">{{ $e['start_date'] ?? '' }}–{{ $e['end_date'] ?? '' }}</span>
                                                        </div>
                                                        <div class="text-[6.5px] font-semibold text-slate-500">{{ $e['company'] ?? '' }}</div>
                                                        <p class="text-[6px] text-slate-600 line-clamp-3 mt-0.5">{{ $e['bullets_text'] ?? '' }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-span-4 bg-slate-50 rounded-lg p-2.5 space-y-2 border border-slate-100">
                                            <div class="text-[6px] font-extrabold uppercase tracking-widest" style="color: {{ $accent }}">Skills</div>
                                            @foreach(array_slice($skills, 0, 5) as $idx => $sk)
                                                @php $pct = 96 - (($idx * 7) % 24); @endphp
                                                <div>
                                                    <div class="flex justify-between text-[6px] font-semibold text-slate-800 mb-0.5">
                                                        <span class="truncate">{{ $sk }}</span>
                                                        <span class="text-slate-400">{{ $pct }}%</span>
                                                    </div>
                                                    <div class="h-1 w-full rounded-full bg-slate-200 overflow-hidden">
                                                        <div class="h-full rounded-full" style="width: {{ $pct }}%; background-color: {{ $accent }};"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="text-[6px] font-extrabold uppercase tracking-widest pt-1 border-t border-slate-200" style="color: {{ $accent }}">Education</div>
                                            @foreach(array_slice($edus, 0, 1) as $ed)
                                                <div class="text-[6px]">
                                                    <div class="font-bold text-slate-800">{{ $ed['degree'] ?? '' }}</div>
                                                    <div class="text-slate-500">{{ $ed['school'] ?? '' }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                            {{-- 4. LUXE SPLIT MINIATURE --}}
                            @elseif($tStyle === 'luxe_split')
                                <div class="h-full bg-white p-4 space-y-2.5 text-[7.2px] leading-[1.35]">
                                    <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-slate-200">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg border-2 flex items-center justify-center font-black text-[9px]" style="border-color: {{ $accent }}; color: {{ $accent }};">{{ $init }}</div>
                                            <div>
                                                <div class="font-black text-[10.5px] text-slate-900">{{ $p['full_name'] ?? '' }}</div>
                                                <div class="text-[7px] font-semibold text-slate-500">{{ $p['job_title'] ?? '' }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right text-[6px] text-slate-500">
                                            <div class="font-bold text-slate-800">{{ $p['email'] ?? '' }}</div>
                                            <div>{{ $p['location'] ?? '' }}</div>
                                        </div>
                                    </div>
                                    <div class="p-2 rounded-md bg-slate-50 border-l-2 text-[6.3px] text-slate-600 line-clamp-2" style="border-left-color: {{ $accent }};">
                                        {{ $c['summary'] ?? '' }}
                                    </div>
                                    <div class="grid grid-cols-12 gap-3 pt-1">
                                        <div class="col-span-7 space-y-2">
                                            <div class="text-[6px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Experience</div>
                                            @foreach($exps as $e)
                                                <div>
                                                    <div class="font-bold text-[7.5px] text-slate-900">{{ $e['role'] ?? '' }}</div>
                                                    <div class="text-[6.3px] font-semibold" style="color: {{ $accent }}">{{ $e['company'] ?? '' }}</div>
                                                    <p class="text-[6px] text-slate-600 line-clamp-3 mt-0.5">{{ $e['bullets_text'] ?? '' }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="col-span-5 space-y-2">
                                            <div class="text-[6px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Education</div>
                                            @foreach(array_slice($edus, 0, 1) as $ed)
                                                <div class="p-1.5 rounded bg-slate-50 border border-slate-100 text-[6px]">
                                                    <div class="font-bold text-slate-900">{{ $ed['degree'] ?? '' }}</div>
                                                    <div style="color: {{ $accent }}">{{ $ed['school'] ?? '' }}</div>
                                                </div>
                                            @endforeach
                                            <div class="text-[6px] font-extrabold uppercase tracking-[0.18em] text-slate-400 pt-1">Skills</div>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach(array_slice($skills, 0, 5) as $sk)
                                                    <span class="px-1.5 py-0.5 rounded-full border border-slate-200 text-[5.8px] font-semibold text-slate-700">{{ $sk }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            {{-- 5. EXECUTIVE BOLD MINIATURE (FlowCV Framed Dark Card Banner) --}}
                            @elseif($tStyle === 'executive_bold')
                                <div class="h-full bg-white flex flex-col pt-3 px-3 pb-3 text-[7.2px] leading-[1.35] space-y-2.5">
                                    <div class="rounded-lg text-white px-3 py-2.5 space-y-1.5" style="background-color: {{ $accent }};">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <div class="font-black text-[10.5px] tracking-tight">{{ $p['full_name'] ?? '' }}</div>
                                                <div class="text-[6.5px] text-white/80">{{ $p['job_title'] ?? '' }}</div>
                                            </div>
                                        </div>
                                        <div class="flex gap-2 px-2 py-0.5 rounded bg-white/10 text-[5.5px] text-white/90">
                                            <span class="truncate">{{ $p['email'] ?? '' }}</span>
                                            <span>•</span>
                                            <span>{{ $p['location'] ?? '' }}</span>
                                        </div>
                                    </div>
                                    <div class="px-1 space-y-2.5 flex-1">
                                        <div class="border-b border-slate-100 pb-2 text-[6.3px] text-slate-700 line-clamp-2">{{ $c['summary'] ?? '' }}</div>
                                        <div class="grid grid-cols-12 gap-3">
                                            <div class="col-span-8 space-y-2">
                                                <div class="text-[6px] font-black uppercase tracking-widest border-b pb-0.5" style="color: {{ $accent }}; border-color: {{ $accent }};">Leadership History</div>
                                                @foreach($exps as $e)
                                                    <div>
                                                        <div class="font-bold text-[7.5px] text-slate-900">{{ $e['role'] ?? '' }}</div>
                                                        <div class="text-[6.3px] font-bold" style="color: {{ $accent }}">{{ $e['company'] ?? '' }}</div>
                                                        <p class="text-[6px] text-slate-600 line-clamp-3">{{ $e['bullets_text'] ?? '' }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-span-4 space-y-2">
                                                <div class="p-2 rounded bg-slate-50 border border-slate-200 space-y-1">
                                                    <div class="text-[5.8px] font-black uppercase tracking-widest" style="color: {{ $accent }}">Competencies</div>
                                                    @foreach(array_slice($skills, 0, 5) as $sk)
                                                        <div class="text-[6px] font-semibold text-slate-700 flex items-center gap-1">
                                                            <span class="w-1 h-1 shrink-0" style="background: {{ $accent }};"></span>
                                                            <span class="truncate">{{ $sk }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            {{-- 6. CREATIVE CARD MINIATURE --}}
                            @elseif($tStyle === 'creative_card')
                                <div class="grid grid-cols-12 h-full text-[7.2px] leading-[1.35]">
                                    <div class="col-span-4 p-2.5 space-y-2.5 border-r" style="background: {{ $accent }}14; border-color: {{ $accent }}28;">
                                        <div class="p-2 rounded-lg bg-white border shadow-sm" style="border-color: {{ $accent }}24;">
                                            <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white font-black text-[8.5px] mb-1.5 overflow-hidden" style="background: {{ $accent }};">
                                                @if(!empty($p['preset_avatar']))<img src="{{ asset($p['preset_avatar']) }}" alt="" class="w-full h-full object-cover">@else{{ $init }}@endif
                                            </div>
                                            <div class="font-black text-[8.5px] text-slate-900 leading-tight">{{ $p['full_name'] ?? '' }}</div>
                                            <div class="text-[6px] font-bold mt-0.5" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</div>
                                        </div>
                                        <div class="space-y-1 text-[5.8px] text-slate-700 px-0.5">
                                            <div class="font-bold text-slate-900 truncate">{{ $p['email'] ?? '' }}</div>
                                            <div class="underline" style="color: {{ $accent }}">{{ $p['website'] ?? '' }}</div>
                                        </div>
                                        <div class="pt-1 border-t space-y-1" style="border-color: {{ $accent }}20;">
                                            <div class="text-[5.5px] font-extrabold uppercase tracking-widest" style="color: {{ $accent }}">Toolbox</div>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach(array_slice($skills, 0, 5) as $sk)
                                                    <span class="px-1.5 py-0.5 rounded-full bg-white text-[5.6px] font-bold border" style="border-color: {{ $accent }}35; color: {{ $accent }};">{{ $sk }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-8 p-3.5 space-y-2.5 bg-white">
                                        <div class="text-[6px] font-extrabold uppercase tracking-widest" style="color: {{ $accent }}">About</div>
                                        <p class="text-[6.2px] text-slate-600 line-clamp-3">{{ $c['summary'] ?? '' }}</p>
                                        <div class="text-[6px] font-extrabold uppercase tracking-widest border-b border-slate-200 pb-0.5" style="color: {{ $accent }}">Experience</div>
                                        @foreach($exps as $e)
                                            <div>
                                                <div class="font-bold text-[7.5px] text-slate-900">{{ $e['role'] ?? '' }}</div>
                                                <div class="text-[6.3px] font-semibold" style="color: {{ $accent }}">{{ $e['company'] ?? '' }}</div>
                                                <p class="text-[6px] text-slate-600 line-clamp-3">{{ $e['bullets_text'] ?? '' }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            {{-- 7. TECH STACK MINIATURE --}}
                            @elseif($tStyle === 'tech_stack')
                                <div class="h-full bg-white border-t-[4px] p-3.5 space-y-2.5 text-[7.2px] leading-[1.35]" style="border-top-color: {{ $accent }};">
                                    <div class="flex justify-between items-start pb-2 border-b border-slate-200">
                                        <div>
                                            <div class="inline-block px-1.5 py-0.5 rounded font-tech-mono text-[5.5px] font-bold mb-0.5" style="background: {{ $accent }}14; color: {{ $accent }};">$ whoami --role</div>
                                            <div class="font-black text-[10.5px] text-slate-900">{{ $p['full_name'] ?? '' }}</div>
                                            <div class="font-tech-mono text-[6.8px] font-semibold" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</div>
                                        </div>
                                        <div class="text-right font-tech-mono text-[5.8px] text-slate-600">
                                            <div>{{ $p['website'] ?? '' }}</div>
                                            <div>{{ $p['email'] ?? '' }}</div>
                                        </div>
                                    </div>
                                    <div class="p-2 rounded bg-slate-50 border border-slate-200">
                                        <div class="font-tech-mono text-[5.8px] font-bold mb-1" style="color: {{ $accent }}">// TECH_STACK</div>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(array_slice($skills, 0, 7) as $sk)
                                                <span class="px-1.5 py-0.5 rounded bg-white border border-slate-300 font-tech-mono text-[5.8px] font-semibold text-slate-800">{{ $sk }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="space-y-1.5">
                                        <div class="font-tech-mono text-[5.8px] font-bold" style="color: {{ $accent }}">// EXPERIENCE</div>
                                        @foreach($exps as $e)
                                            <div>
                                                <div class="font-bold text-[7.3px] text-slate-900">{{ $e['role'] ?? '' }} <span style="color: {{ $accent }}">@ {{ $e['company'] ?? '' }}</span></div>
                                                <p class="text-[6px] text-slate-600 line-clamp-2">{{ $e['bullets_text'] ?? '' }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(!empty($projs))
                                        <div class="grid grid-cols-2 gap-1.5 pt-0.5">
                                            @foreach($projs as $pr)
                                                <div class="p-1.5 rounded border border-slate-200 bg-slate-50/70">
                                                    <div class="font-tech-mono font-bold text-[6px] text-slate-900 truncate">{{ $pr['name'] ?? '' }}</div>
                                                    <div class="text-[5.6px] text-slate-500 line-clamp-2">{{ $pr['description'] ?? '' }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                            {{-- 8. MINIMAL SWISS MINIATURE --}}
                            @elseif($tStyle === 'minimal_swiss')
                                <div class="h-full bg-white p-4 space-y-2.5 text-[7.2px] leading-[1.35]">
                                    <div class="border-t-[3px] pt-2 pb-2 border-b border-slate-200 flex justify-between items-end" style="border-top-color: {{ $accent }};">
                                        <div>
                                            <div class="font-black text-[11px] text-slate-900">{{ $p['full_name'] ?? '' }}</div>
                                            <div class="text-[7px] font-semibold" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</div>
                                        </div>
                                        <div class="text-right text-[5.8px] text-slate-500">{{ $p['email'] ?? '' }}</div>
                                    </div>
                                    @foreach([['label' => 'Profile', 'type' => 'summary'], ['label' => 'Experience', 'type' => 'exp'], ['label' => 'Education', 'type' => 'edu'], ['label' => 'Skills', 'type' => 'skills']] as $row)
                                        <div class="grid grid-cols-12 gap-2 border-b border-slate-100 pb-2">
                                            <div class="col-span-3 text-[5.8px] font-extrabold uppercase tracking-widest" style="color: {{ $accent }}">{{ $row['label'] }}</div>
                                            <div class="col-span-9 text-[6.2px] text-slate-700">
                                                @if($row['type'] === 'summary')
                                                    <p class="line-clamp-3">{{ $c['summary'] ?? '' }}</p>
                                                @elseif($row['type'] === 'exp')
                                                    @foreach($exps as $e)
                                                        <div class="mb-1">
                                                            <div class="font-bold text-slate-900">{{ $e['role'] ?? '' }} — {{ $e['company'] ?? '' }}</div>
                                                            <p class="line-clamp-2 text-slate-600">{{ $e['bullets_text'] ?? '' }}</p>
                                                        </div>
                                                    @endforeach
                                                @elseif($row['type'] === 'edu')
                                                    @foreach(array_slice($edus, 0, 1) as $ed)
                                                        <div class="font-bold text-slate-900">{{ $ed['degree'] ?? '' }}</div>
                                                        <div class="text-slate-500">{{ $ed['school'] ?? '' }}</div>
                                                    @endforeach
                                                @else
                                                    <div>{{ implode(' • ', array_slice($skills, 0, 6)) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            {{-- 9. ATS CLASSIC MINIATURE --}}
                            @elseif($tStyle === 'ats_classic')
                                <div class="h-full bg-white p-4 space-y-2 text-[7px] leading-[1.35] text-slate-900">
                                    <div class="text-center border-b-2 border-slate-900 pb-1.5">
                                        <div class="font-editorial font-bold text-[11px] uppercase">{{ $p['full_name'] ?? '' }}</div>
                                        <div class="text-[6.8px] font-semibold text-slate-700">{{ $p['job_title'] ?? '' }}</div>
                                        <div class="text-[5.8px] text-slate-500 mt-0.5">{{ ($p['location'] ?? '') . ' • ' . ($p['phone'] ?? '') . ' • ' . ($p['email'] ?? '') }}</div>
                                    </div>
                                    <div>
                                        <div class="font-bold uppercase tracking-widest text-[6.2px] border-b border-slate-400 pb-0.5 mb-1">Summary</div>
                                        <p class="text-[6px] text-slate-700 line-clamp-3">{{ $c['summary'] ?? '' }}</p>
                                    </div>
                                    <div>
                                        <div class="font-bold uppercase tracking-widest text-[6.2px] border-b border-slate-400 pb-0.5 mb-1">Experience</div>
                                        @foreach($exps as $e)
                                            <div class="mb-1.5">
                                                <div class="flex justify-between font-bold text-[6.8px]">
                                                    <span>{{ $e['company'] ?? '' }}</span>
                                                    <span>{{ $e['location'] ?? '' }}</span>
                                                </div>
                                                <div class="italic text-[6.2px]">{{ $e['role'] ?? '' }} ({{ ($e['start_date'] ?? '') . '–' . ($e['end_date'] ?? '') }})</div>
                                                <p class="text-[6px] text-slate-700 line-clamp-2">{{ $e['bullets_text'] ?? '' }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div>
                                        <div class="font-bold uppercase tracking-widest text-[6.2px] border-b border-slate-400 pb-0.5 mb-1">Skills</div>
                                        <p class="text-[6px]">{{ implode(', ', array_slice($skills, 0, 7)) }}</p>
                                    </div>
                                </div>

                            {{-- 10. COMPACT ONEPAGE MINIATURE --}}
                            @else
                                <div class="h-full bg-white p-3.5 space-y-2 text-[7px] leading-[1.35]">
                                    <div class="flex justify-between items-end border-b-2 pb-1.5" style="border-color: {{ $accent }};">
                                        <div>
                                            <div class="font-black text-[10px] text-slate-900">{{ $p['full_name'] ?? '' }}</div>
                                            <div class="text-[6.8px] font-bold" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</div>
                                        </div>
                                        <span class="text-[5.5px] uppercase font-bold text-slate-400">One-Pager</span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-1 px-2 py-1 rounded bg-slate-50 border border-slate-200 text-[5.6px] text-slate-600">
                                        <span class="truncate">{{ $p['email'] ?? '' }}</span>
                                        <span class="truncate text-center">{{ $p['phone'] ?? '' }}</span>
                                        <span class="truncate text-right">{{ $p['location'] ?? '' }}</span>
                                    </div>
                                    <div class="p-1.5 rounded border text-[6px] text-slate-700 line-clamp-2" style="border-color: {{ $accent }}30; background: {{ $accent }}08;">
                                        {{ $c['summary'] ?? '' }}
                                    </div>
                                    <div class="space-y-1.5">
                                        <div class="text-[5.8px] font-extrabold uppercase tracking-widest" style="color: {{ $accent }}">Experience</div>
                                        @foreach($exps as $e)
                                            <div>
                                                <div class="font-bold text-[7px] text-slate-900">{{ $e['role'] ?? '' }} <span style="color: {{ $accent }}">— {{ $e['company'] ?? '' }}</span></div>
                                                <p class="text-[5.8px] text-slate-600 line-clamp-2">{{ $e['bullets_text'] ?? '' }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-200">
                                        <div class="flex flex-wrap gap-0.5">
                                            @foreach(array_slice($skills, 0, 5) as $sk)
                                                <span class="px-1 py-0.5 rounded bg-slate-100 text-[5.5px] font-semibold">{{ $sk }}</span>
                                            @endforeach
                                        </div>
                                        <div class="text-[5.8px]">
                                            @foreach(array_slice($edus, 0, 1) as $ed)
                                                <div class="font-bold">{{ $ed['degree'] ?? '' }}</div>
                                                <div class="text-slate-500">{{ $ed['school'] ?? '' }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Hover Action Veil --}}
                            <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/45 transition-all duration-200 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <div class="flex items-center gap-2.5 px-3">
                                    <button type="button"
                                            @click.stop="openPreview('{{ $tpl['slug'] }}')"
                                            class="px-3.5 py-2 rounded-xl bg-white text-slate-900 text-xs font-extrabold shadow-lg hover:bg-slate-50 transition">
                                        Live Preview
                                    </button>
                                    <a href="{{ route('resume.builder', ['template' => $tpl['slug']]) }}"
                                       @click.stop
                                       class="px-3.5 py-2 rounded-xl bg-[#184f3a] text-white text-xs font-extrabold shadow-lg hover:bg-[#123e2d] transition">
                                        Use Template
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Footer Details --}}
                    <div class="p-5 flex-1 flex flex-col justify-between gap-4">
                        <div>
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="inline-flex items-center gap-1.5 mb-1">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold tracking-wide bg-slate-100 text-slate-700 border border-slate-200/80">
                                            {{ $tpl['badge'] ?? 'Designer' }}
                                        </span>
                                    </div>
                                    <h3 class="font-extrabold text-base text-slate-900 leading-snug">{{ $tpl['name'] }}</h3>
                                    <div class="text-xs font-semibold text-slate-500 mt-0.5">{{ $tpl['role'] }}</div>
                                </div>
                                <span class="w-4 h-4 rounded-full shrink-0 mt-1 border border-slate-200 shadow-sm"
                                      style="background-color: {{ $accent }};"
                                      title="Default accent {{ $accent }}"></span>
                            </div>
                            <p class="text-xs text-slate-600 mt-2.5 leading-relaxed line-clamp-2">{{ $tpl['tagline'] }}</p>
                        </div>

                        <div class="space-y-3">
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(($tpl['tags'] ?? []) as $tag)
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10.5px] font-bold">{{ $tag }}</span>
                                @endforeach
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                <a href="{{ route('resume.builder', ['template' => $tpl['slug']]) }}"
                                   class="flex-1 text-center py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-[#184f3a] transition">
                                    Customize This CV
                                </a>
                                <button type="button"
                                        @click="openPreview('{{ $tpl['slug'] }}')"
                                        class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 hover:border-slate-300 transition">
                                    Preview
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Full-Resolution Interactive Live Preview Modal --}}
    <div x-show="modalOpen"
         x-cloak
         @keydown.escape.window="modalOpen = false"
         class="fixed inset-0 z-50 bg-slate-950/75 backdrop-blur-xs flex items-center justify-center p-3 sm:p-6">
        <div class="relative w-full max-w-6xl max-h-[92vh] bg-slate-100 rounded-2xl shadow-2xl overflow-hidden flex flex-col lg:flex-row border border-slate-300/60"
             @click.away="modalOpen = false">

            {{-- Left Control Sidebar inside Preview Modal --}}
            <div class="w-full lg:w-80 bg-white border-b lg:border-b-0 lg:border-r border-slate-200 p-5 flex flex-col justify-between gap-5 overflow-y-auto shrink-0">
                <div class="space-y-5">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <div class="text-[11px] font-extrabold uppercase tracking-wider text-[#184f3a]">Interactive Customizer</div>
                            <h2 class="text-lg font-black text-slate-900 mt-0.5" x-text="currentTemplateMeta().name || 'Resume Preview'"></h2>
                            <p class="text-xs text-slate-500 mt-0.5" x-text="currentTemplateMeta().role || ''"></p>
                        </div>
                        <button type="button" @click="modalOpen = false"
                                class="w-8 h-8 rounded-lg border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center font-bold">
                            &times;
                        </button>
                    </div>

                    {{-- Switch Layout Engine (10 Styles) --}}
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Layout Engine (10)</label>
                        <div class="grid grid-cols-2 gap-1.5">
                            @foreach($styles as $sKey => $sInfo)
                                <button type="button"
                                        @click="template_style = '{{ $sKey }}'"
                                        class="px-2.5 py-2 rounded-lg border text-left text-[11px] font-bold transition truncate"
                                        :class="template_style === '{{ $sKey }}'
                                            ? 'border-[#184f3a] bg-[#e8f7ed] text-[#184f3a]'
                                            : 'border-slate-200 text-slate-700 hover:border-slate-300'">
                                    {{ $sInfo['name'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Color Swatches (8 Designer Inks) --}}
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Accent Ink</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($colors as $clr)
                                <button type="button"
                                        @click="accent_color = '{{ $clr['hex'] }}'"
                                        class="w-7 h-7 rounded-full border-2 transition flex items-center justify-center"
                                        style="background-color: {{ $clr['hex'] }};"
                                        :class="accent_color === '{{ $clr['hex'] }}' ? 'border-slate-900 ring-2 ring-slate-900/20 scale-110' : 'border-white shadow-sm'"
                                        title="{{ $clr['name'] }}">
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Font Family (Sans / Serif / Mono) --}}
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Typography</label>
                        <div class="grid grid-cols-3 gap-1.5 text-xs">
                            <button type="button" @click="font_family = 'sans'"
                                    class="py-2 rounded-lg border font-sans font-bold transition"
                                    :class="font_family === 'sans' ? 'border-[#184f3a] bg-[#e8f7ed] text-[#184f3a]' : 'border-slate-200 text-slate-700'">Sans</button>
                            <button type="button" @click="font_family = 'serif'"
                                    class="py-2 rounded-lg border font-editorial font-bold transition"
                                    :class="font_family === 'serif' ? 'border-[#184f3a] bg-[#e8f7ed] text-[#184f3a]' : 'border-slate-200 text-slate-700'">Serif</button>
                            <button type="button" @click="font_family = 'mono'"
                                    class="py-2 rounded-lg border font-tech-mono font-bold transition"
                                    :class="font_family === 'mono' ? 'border-[#184f3a] bg-[#e8f7ed] text-[#184f3a]' : 'border-slate-200 text-slate-700'">Mono</button>
                        </div>
                    </div>
                </div>

                <div class="space-y-2 pt-3 border-t border-slate-200">
                    <a :href="buildLaunchUrl(false)"
                       class="block w-full text-center py-3 rounded-xl bg-[#184f3a] text-white text-sm font-extrabold shadow-sm hover:bg-[#123e2d] transition">
                        Use This Design &rarr;
                    </a>
                    <a :href="buildLaunchUrl(true)"
                       class="block w-full text-center py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">
                        &#128196; Use + Import My Existing CV
                    </a>
                </div>
            </div>

            {{-- Right Full A4 Live Preview Scroll Area --}}
            <div class="flex-1 overflow-y-auto p-4 sm:p-8 bg-slate-200/80">
                @include('resume._preview_reactive')
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function resumeGalleryPage() {
    const templatesMap = @js(collect($templates)->keyBy('slug'));
    return {
        activeCategory: 'all',
        modalOpen: false,
        activeTemplateSlug: 'atlantic-flow',
        template_style: 'flow_sidebar',
        accent_color: '#1B3142',
        font_family: 'sans',
        density: 'comfortable',
        photo_data: '',
        content: @js($templates[0]['preset_content']),

        currentTemplateMeta() {
            return templatesMap[this.activeTemplateSlug] || {};
        },

        openPreview(slug) {
            const tpl = templatesMap[slug];
            if (!tpl) return;
            this.activeTemplateSlug = slug;
            this.template_style = tpl.style || 'flow_sidebar';
            this.accent_color = tpl.accent_color || '#1B3142';
            this.font_family = tpl.font_family || 'sans';
            this.density = tpl.density || 'comfortable';
            this.photo_data = '';
            this.content = JSON.parse(JSON.stringify(tpl.preset_content));
            this.modalOpen = true;
        },

        buildLaunchUrl(withImport) {
            const base = @js(route('resume.builder'));
            const params = new URLSearchParams({
                template: this.activeTemplateSlug,
                style: this.template_style,
                color: this.accent_color,
                font: this.font_family,
                density: this.density,
            });
            if (withImport) params.set('import', '1');
            return base + '?' + params.toString();
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
    };
}
</script>
@endpush
