@extends('layouts.app')

@php
    $c = is_array($resume->content) ? $resume->content : [];
    $p = $c['personal'] ?? [];
    $vis = array_merge([
        'summary' => true,
        'experience' => true,
        'education' => true,
        'skills' => true,
        'languages' => true,
        'awards' => true,
        'projects' => true,
    ], $c['section_visibility'] ?? []);

    $tStyle = $resume->template_style ?: 'flow_sidebar';
    $accent = $resume->accent_color ?: '#1B3142';
    $font = $resume->font_family ?: 'sans';
    $density = $c['density'] ?? 'comfortable';
    $presetAvatar = !empty($p['preset_avatar']) ? asset($p['preset_avatar']) : '';
    $photo = ($resume->photo_data ?: '') ?: $presetAvatar;

    $fullName = trim((string) ($p['full_name'] ?? 'Your Name'));
    $words = preg_split('/\s+/', $fullName ?: 'FN') ?: ['FN'];
    $initials = strtoupper(
        mb_substr($words[0] ?? 'F', 0, 1) .
        (count($words) > 1 ? mb_substr($words[count($words) - 1] ?? '', 0, 1) : '')
    ) ?: 'FN';

    $showPhoto = !empty($p['show_photo']);
    $exps = $c['experience'] ?? [];
    $edus = $c['education'] ?? [];
    $skills = $c['skills'] ?? [];
    $langs = $c['languages'] ?? [];
    $awards = $c['awards'] ?? [];
    $projects = $c['projects'] ?? [];

    $parseBullets = static function (?string $text): array {
        if (!$text) return [];
        $lines = preg_split('/\r?\n/', $text) ?: [];
        $out = [];
        foreach ($lines as $line) {
            $clean = trim((string) preg_replace('/^[\s\x{2022}\-\*\x{25AA}\x{25B8}]+/u', '', $line));
            if ($clean !== '') {
                $out[] = $clean;
            }
        }
        return $out;
    };

    $fontClass = match ($font) {
        'serif' => 'font-serif',
        'mono' => 'font-tech-mono',
        default => 'font-sans',
    };
    $densityClass = $density === 'compact' ? 'text-[11px] leading-[1.48]' : 'text-[12px] leading-[1.55]';
    $sheetBg = $tStyle === 'editorial_figma' ? 'bg-[#FAF8F4] text-[#1F1C18]' : 'bg-white text-slate-800';
@endphp

@section('title', ($fullName ?: $resume->title) . ' — Resume | Formnexa')

@section('content')
<div class="min-h-screen bg-slate-100 py-6 sm:py-10 px-3 sm:px-6 print:bg-white print:p-0">

    {{-- Top Viewer Action Strip --}}
    <div class="no-print max-w-[794px] mx-auto mb-5 flex flex-wrap items-center justify-between gap-3 bg-white border border-slate-200 rounded-2xl px-4 py-3 shadow-xs">
        <div class="min-w-0">
            <div class="text-xs font-extrabold text-slate-900 truncate">{{ $resume->title }}</div>
            <div class="text-[11px] text-slate-500">Updated {{ $resume->updated_at?->diffForHumans() }}</div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('resume.builder', ['load' => $resume->uuid]) }}"
               class="px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                Edit Resume
            </a>
            <button type="button"
                    onclick="navigator.clipboard?.writeText(window.location.href).then(() => alert('Share link copied!'));"
                    class="px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                Copy Link
            </button>
            <button type="button" onclick="window.print()"
                    class="hidden sm:inline-flex px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                Print
            </button>
            <a href="{{ route('resume.pdf', $resume->uuid) }}"
               class="px-4 py-2 rounded-xl bg-[#0066FF] text-white text-xs font-extrabold shadow-xs hover:bg-blue-700 transition">
                Download PDF
            </a>
        </div>
    </div>

    {{-- Responsive Scroll-Safe A4 Sheet --}}
    <div class="w-full overflow-x-auto pb-4 print:overflow-visible print:pb-0">
        <div class="min-w-[640px] sm:min-w-0">
            <div class="w-full max-w-[794px] mx-auto shadow-2xl rounded-sm overflow-hidden border border-slate-200/80 print:shadow-none print:border-0 {{ $sheetBg }} {{ $fontClass }} {{ $densityClass }}"
                 style="min-height: 1122px;">

                {{-- 1. FLOW SIDEBAR --}}
                @if($tStyle === 'flow_sidebar')
                    <div class="grid grid-cols-12 min-h-[1122px]">
                        <aside class="col-span-4 text-white flex flex-col justify-between {{ $density === 'compact' ? 'p-6 space-y-4' : 'p-7 space-y-5' }}"
                               style="background-color: {{ $accent }};">
                            <div class="space-y-5">
                                <div>
                                    <h1 class="text-[22px] font-black tracking-tight leading-[1.12] text-white break-words {{ $font === 'serif' ? 'font-editorial font-bold' : '' }}">
                                        {{ $fullName }}
                                    </h1>
                                    <p class="text-[12px] font-medium text-white/80 mt-1">{{ $p['job_title'] ?? '' }}</p>
                                    @if($showPhoto)
                                        <div class="w-28 h-28 rounded-full mx-auto mt-4 border-[3px] border-white/25 bg-white/10 flex items-center justify-center shadow-lg overflow-hidden">
                                            @if($photo)
                                                <img src="{{ $photo }}" alt="Portrait" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-2xl font-black tracking-tight text-white/95">{{ $initials }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-2 text-[10.5px] text-white/90 pt-2">
                                    @if(!empty($p['email']))<div class="flex items-center gap-2 break-all"><svg class="w-3.5 h-3.5 text-white/70 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg><span>{{ $p['email'] }}</span></div>@endif
                                    @if(!empty($p['phone']))<div class="flex items-center gap-2"><svg class="w-3.5 h-3.5 text-white/70 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg><span>{{ $p['phone'] }}</span></div>@endif
                                    @if(!empty($p['location']))<div class="flex items-center gap-2"><svg class="w-3.5 h-3.5 text-white/70 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg><span>{{ $p['location'] }}</span></div>@endif
                                    @if(!empty($p['linkedin']))<div class="flex items-center gap-2 break-all"><span class="w-3.5 h-3.5 rounded-[3px] bg-white/20 text-white text-[8.5px] font-black flex items-center justify-center shrink-0">in</span><span>{{ $p['linkedin'] }}</span></div>@endif
                                    @if(!empty($p['website']))<div class="flex items-center gap-2 break-all"><svg class="w-3.5 h-3.5 text-white/70 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/></svg><span>{{ $p['website'] }}</span></div>@endif
                                </div>

                                @if(!empty($vis['summary']) && !empty($c['summary']))
                                    <div>
                                        <div class="px-2.5 py-1 rounded bg-white/10 text-[9.5px] font-extrabold uppercase tracking-[0.16em] text-white mb-2">Profile</div>
                                        <p class="text-[10.8px] leading-relaxed text-white/85 whitespace-pre-line">{{ $c['summary'] }}</p>
                                    </div>
                                @endif

                                @if(!empty($vis['languages']) && !empty($langs))
                                    <div>
                                        <div class="px-2.5 py-1 rounded bg-white/10 text-[9.5px] font-extrabold uppercase tracking-[0.16em] text-white mb-2">Languages</div>
                                        <div class="space-y-2">
                                            @foreach($langs as $lang)
                                                @continue(empty($lang['name']))
                                                <div class="flex items-center justify-between gap-2 text-[10.8px]">
                                                    <div>
                                                        <span class="font-semibold text-white">{{ $lang['name'] }}</span>
                                                        @if(!empty($lang['level']))<span class="text-[9.5px] text-white/60 ml-1">({{ $lang['level'] }})</span>@endif
                                                    </div>
                                                    <div class="flex items-center gap-1 shrink-0">
                                                        @for($d = 1; $d <= 5; $d++)
                                                            <span class="w-2 h-2 rounded-full {{ $d <= ($lang['score'] ?? 4) ? 'bg-white' : 'bg-white/20' }}"></span>
                                                        @endfor
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($vis['awards']) && !empty($awards))
                                    <div>
                                        <div class="px-2.5 py-1 rounded bg-white/10 text-[9.5px] font-extrabold uppercase tracking-[0.16em] text-white mb-2">Honors &amp; Awards</div>
                                        <div class="space-y-2">
                                            @foreach($awards as $aw)
                                                @continue(empty($aw['title']))
                                                <div>
                                                    <div class="font-semibold text-white text-[10.8px]">{{ $aw['title'] }}</div>
                                                    <div class="text-[9.5px] text-white/70">{{ implode(' • ', array_filter([$aw['issuer'] ?? '', $aw['date'] ?? ''])) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="pt-3 text-[8.5px] text-white/30 tracking-widest uppercase">Curriculum Vitae</div>
                        </aside>

                        <main class="col-span-8 bg-white {{ $density === 'compact' ? 'p-6 space-y-4' : 'p-8 space-y-6' }}">
                            @if(!empty($vis['experience']) && !empty($exps))
                                <section>
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded bg-slate-100 text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-700 mb-3.5">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $accent }}"></span>
                                        Work Experience
                                    </div>
                                    <div class="space-y-4">
                                        @foreach($exps as $exp)
                                            @continue(empty($exp['role']) && empty($exp['company']))
                                            <div>
                                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                                    <h3 class="font-bold text-[13px] text-slate-900">{{ $exp['company'] ?: ($exp['role'] ?? '') }}</h3>
                                                    <span class="text-[10.5px] font-semibold text-slate-500">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</span>
                                                </div>
                                                <div class="flex flex-wrap items-center justify-between gap-2 text-[11px] font-semibold mb-1.5" style="color: {{ $accent }}">
                                                    <span>{{ $exp['role'] ?? '' }}</span>
                                                    <span class="text-slate-400 font-normal text-[10.5px]">{{ $exp['location'] ?? '' }}</span>
                                                </div>
                                                <ul class="space-y-1 text-slate-700 pl-3.5 list-disc marker:text-slate-400">
                                                    @foreach($parseBullets($exp['bullets_text'] ?? '') as $line)
                                                        <li>{{ $line }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif

                            @if(!empty($vis['education']) && !empty($edus))
                                <section>
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded bg-slate-100 text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-700 mb-3">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $accent }}"></span>
                                        Education
                                    </div>
                                    <div class="space-y-3">
                                        @foreach($edus as $edu)
                                            @continue(empty($edu['degree']) && empty($edu['school']))
                                            <div>
                                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                                    <h3 class="font-bold text-[12.5px] text-slate-900">{{ $edu['degree'] ?? '' }}</h3>
                                                    <span class="text-[10.5px] font-semibold text-slate-500">{{ implode(' – ', array_filter([$edu['start_date'] ?? '', $edu['end_date'] ?? ''])) }}</span>
                                                </div>
                                                <div class="text-[11px] text-slate-600 font-medium">{{ implode(', ', array_filter([$edu['school'] ?? '', $edu['location'] ?? ''])) }}</div>
                                                @if(!empty($edu['details']))<p class="text-[10.5px] text-slate-500 mt-0.5">{{ $edu['details'] }}</p>@endif
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif

                            @if(!empty($vis['skills']) && !empty($skills))
                                <section>
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded bg-slate-100 text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-700 mb-3">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $accent }}"></span>
                                        Core Competencies
                                    </div>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($skills as $sk)
                                            <span class="px-2.5 py-1 rounded text-[10.5px] font-semibold bg-slate-100 text-slate-800 border border-slate-200/80">{{ $sk }}</span>
                                        @endforeach
                                    </div>
                                </section>
                            @endif

                            @if(!empty($vis['projects']) && !empty($projects))
                                <section>
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded bg-slate-100 text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-700 mb-3">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $accent }}"></span>
                                        Key Projects
                                    </div>
                                    <div class="space-y-2.5">
                                        @foreach($projects as $proj)
                                            @continue(empty($proj['name']))
                                            <div>
                                                <div class="flex items-baseline justify-between gap-2">
                                                    <span class="font-bold text-slate-900">{{ $proj['name'] }}</span>
                                                    <span class="text-[10px] text-slate-500">{{ $proj['link'] ?? '' }}</span>
                                                </div>
                                                @if(!empty($proj['description']))<p class="text-slate-600 mt-0.5">{{ $proj['description'] }}</p>@endif
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                        </main>
                    </div>

                {{-- 2. EDITORIAL FIGMA --}}
                @elseif($tStyle === 'editorial_figma')
                    <div class="min-h-[1122px] bg-[#FAF8F4] text-[#1F1C18] {{ $density === 'compact' ? 'p-8 space-y-5' : 'p-10 space-y-6' }}">
                        <header class="border-b pb-6 flex items-start justify-between gap-6" style="border-color: rgba(31,28,24,0.16);">
                            <div class="flex items-center gap-5 min-w-0">
                                @if($showPhoto)
                                    <div class="w-20 h-20 rounded-full border shrink-0 flex items-center justify-center text-xl font-bold tracking-tight overflow-hidden"
                                         style="border-color: {{ $accent }}; color: {{ $accent }}; background-color: #EFECE5;">
                                        @if($photo)<img src="{{ $photo }}" alt="Portrait" class="w-full h-full object-cover">@else<span>{{ $initials }}</span>@endif
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="text-[9.5px] font-bold uppercase tracking-[0.26em] mb-1" style="color: {{ $accent }}">Curriculum Vitae</div>
                                    <h1 class="font-editorial text-[32px] font-bold tracking-tight leading-[1.08] text-[#1A1815]">{{ $fullName }}</h1>
                                    <p class="text-[13.5px] italic font-editorial text-[#59534A] mt-1">{{ $p['job_title'] ?? '' }}</p>
                                </div>
                            </div>
                            <div class="text-right text-[10.5px] text-[#59534A] space-y-1 shrink-0 pt-1">
                                @if(!empty($p['email']))<div class="font-medium text-[#1A1815]">{{ $p['email'] }}</div>@endif
                                @if(!empty($p['phone']))<div>{{ $p['phone'] }}</div>@endif
                                @if(!empty($p['location']))<div>{{ $p['location'] }}</div>@endif
                                @if(!empty($p['website']))<div class="underline decoration-stone-300">{{ $p['website'] }}</div>@endif
                                @if(!empty($p['linkedin']))<div>{{ $p['linkedin'] }}</div>@endif
                            </div>
                        </header>

                        @if(!empty($vis['summary']) && !empty($c['summary']))
                            <section class="border-b pb-5" style="border-color: rgba(31,28,24,0.12);">
                                <div class="grid grid-cols-12 gap-6 items-start">
                                    <div class="col-span-3 text-[9.5px] font-bold uppercase tracking-[0.22em] pt-0.5" style="color: {{ $accent }}">Profile</div>
                                    <p class="col-span-9 text-[12px] leading-[1.65] text-[#35302A] whitespace-pre-line">{{ $c['summary'] }}</p>
                                </div>
                            </section>
                        @endif

                        <div class="grid grid-cols-12 gap-8 items-start">
                            <div class="col-span-8 space-y-6 pr-2 border-r" style="border-color: rgba(31,28,24,0.10);">
                                @if(!empty($vis['experience']) && !empty($exps))
                                    <section>
                                        <div class="flex items-center justify-between border-b pb-1.5 mb-4" style="border-color: rgba(31,28,24,0.16);">
                                            <h2 class="font-editorial text-[14px] font-bold tracking-wide text-[#1A1815]">Experience</h2>
                                            <span class="text-[9px] uppercase tracking-[0.2em]" style="color: {{ $accent }}">Career History</span>
                                        </div>
                                        <div class="space-y-4">
                                            @foreach($exps as $exp)
                                                @continue(empty($exp['role']) && empty($exp['company']))
                                                <div>
                                                    <div class="flex items-baseline justify-between gap-2">
                                                        <h3 class="font-bold text-[12.5px] text-[#1A1815]">{{ $exp['role'] ?: ($exp['company'] ?? '') }}</h3>
                                                        <span class="text-[10px] italic font-editorial text-[#6B645A]">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</span>
                                                    </div>
                                                    <div class="text-[11px] font-semibold mb-1.5" style="color: {{ $accent }}">{{ implode(' — ', array_filter([$exp['company'] ?? '', $exp['location'] ?? ''])) }}</div>
                                                    <ul class="space-y-1 text-[#3D372F] pl-4 list-disc">
                                                        @foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif

                                @if(!empty($vis['projects']) && !empty($projects))
                                    <section>
                                        <div class="border-b pb-1.5 mb-3" style="border-color: rgba(31,28,24,0.16);">
                                            <h2 class="font-editorial text-[14px] font-bold tracking-wide text-[#1A1815]">Selected Works</h2>
                                        </div>
                                        <div class="space-y-2.5">
                                            @foreach($projects as $proj)
                                                @continue(empty($proj['name']))
                                                <div>
                                                    <div class="flex items-baseline justify-between gap-2">
                                                        <span class="font-bold text-[12px] text-[#1A1815]">{{ $proj['name'] }}</span>
                                                        <span class="text-[10px] underline decoration-stone-300 text-[#6B645A]">{{ $proj['link'] ?? '' }}</span>
                                                    </div>
                                                    @if(!empty($proj['description']))<p class="text-[#3D372F] mt-0.5">{{ $proj['description'] }}</p>@endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif
                            </div>

                            <div class="col-span-4 space-y-5">
                                @if(!empty($vis['education']) && !empty($edus))
                                    <section>
                                        <h2 class="font-editorial text-[13.5px] font-bold border-b pb-1.5 mb-3 text-[#1A1815]" style="border-color: rgba(31,28,24,0.16);">Education</h2>
                                        <div class="space-y-3">
                                            @foreach($edus as $edu)
                                                @continue(empty($edu['degree']) && empty($edu['school']))
                                                <div>
                                                    <div class="font-bold text-[11.5px] text-[#1A1815]">{{ $edu['degree'] ?? '' }}</div>
                                                    <div class="text-[11px] font-medium" style="color: {{ $accent }}">{{ $edu['school'] ?? '' }}</div>
                                                    <div class="text-[10px] text-[#6B645A]">{{ implode(' – ', array_filter([$edu['start_date'] ?? '', $edu['end_date'] ?? ''])) }}</div>
                                                    @if(!empty($edu['details']))<p class="text-[10px] text-[#59534A] mt-0.5">{{ $edu['details'] }}</p>@endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif

                                @if(!empty($vis['skills']) && !empty($skills))
                                    <section>
                                        <h2 class="font-editorial text-[13.5px] font-bold border-b pb-1.5 mb-3 text-[#1A1815]" style="border-color: rgba(31,28,24,0.16);">Expertise</h2>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($skills as $sk)
                                                <span class="px-2 py-0.5 rounded-sm border text-[10.5px] font-medium text-[#2E2A24]" style="border-color: rgba(31,28,24,0.22); background-color: rgba(255,255,255,0.65);">{{ $sk }}</span>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif

                                @if(!empty($vis['languages']) && !empty($langs))
                                    <section>
                                        <h2 class="font-editorial text-[13.5px] font-bold border-b pb-1.5 mb-2.5 text-[#1A1815]" style="border-color: rgba(31,28,24,0.16);">Languages</h2>
                                        <div class="space-y-1.5 text-[11px]">
                                            @foreach($langs as $lang)
                                                @continue(empty($lang['name']))
                                                <div class="flex justify-between">
                                                    <span class="font-semibold text-[#1A1815]">{{ $lang['name'] }}</span>
                                                    <span class="italic font-editorial text-[#6B645A]">{{ $lang['level'] ?? '' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif

                                @if(!empty($vis['awards']) && !empty($awards))
                                    <section>
                                        <h2 class="font-editorial text-[13.5px] font-bold border-b pb-1.5 mb-2.5 text-[#1A1815]" style="border-color: rgba(31,28,24,0.16);">Recognition</h2>
                                        <div class="space-y-2 text-[11px]">
                                            @foreach($awards as $aw)
                                                @continue(empty($aw['title']))
                                                <div>
                                                    <div class="font-semibold text-[#1A1815]">{{ $aw['title'] }}</div>
                                                    <div class="text-[10px] text-[#6B645A]">{{ implode(' • ', array_filter([$aw['issuer'] ?? '', $aw['date'] ?? ''])) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif
                            </div>
                        </div>
                    </div>

                {{-- 3. TIMELINE PRO --}}
                @elseif($tStyle === 'timeline_pro')
                    <div class="min-h-[1122px] bg-white">
                        <header class="border-b border-slate-200 px-8 py-6 flex items-center justify-between gap-6"
                                style="background: linear-gradient(90deg, {{ $accent }}0D 0%, #FFFFFF 65%);">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-1.5 self-stretch rounded-full shrink-0" style="background-color: {{ $accent }}"></div>
                                @if($showPhoto)
                                    <div class="w-16 h-16 rounded-2xl shrink-0 flex items-center justify-center text-white font-black text-lg shadow-sm overflow-hidden" style="background-color: {{ $accent }}">
                                        @if($photo)<img src="{{ $photo }}" alt="Portrait" class="w-full h-full object-cover">@else<span>{{ $initials }}</span>@endif
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <h1 class="text-[24px] font-black tracking-tight text-slate-900 leading-tight {{ $font === 'serif' ? 'font-editorial' : '' }}">{{ $fullName }}</h1>
                                    <p class="text-[13px] font-bold mt-0.5" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-1 text-right text-[10.5px] text-slate-600 shrink-0">
                                @if(!empty($p['email']))<div class="font-semibold text-slate-800">{{ $p['email'] }}</div>@endif
                                @if(!empty($p['phone']))<div>{{ $p['phone'] }}</div>@endif
                                @if(!empty($p['location']))<div>{{ $p['location'] }}</div>@endif
                                @if(!empty($p['linkedin']) || !empty($p['website']))<div>{{ $p['linkedin'] ?: ($p['website'] ?? '') }}</div>@endif
                            </div>
                        </header>

                        <div class="grid grid-cols-12 {{ $density === 'compact' ? 'p-6 gap-5' : 'p-8 gap-7' }}">
                            <div class="col-span-8 space-y-6">
                                @if(!empty($vis['summary']) && !empty($c['summary']))
                                    <section>
                                        <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-2 flex items-center gap-2" style="color: {{ $accent }}">
                                            <span class="w-5 h-0.5 rounded-full" style="background-color: {{ $accent }}"></span> Executive Summary
                                        </h2>
                                        <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $c['summary'] }}</p>
                                    </section>
                                @endif

                                @if(!empty($vis['experience']) && !empty($exps))
                                    <section>
                                        <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-4 flex items-center gap-2" style="color: {{ $accent }}">
                                            <span class="w-5 h-0.5 rounded-full" style="background-color: {{ $accent }}"></span> Career Timeline
                                        </h2>
                                        <div class="relative pl-5 space-y-5 border-l-2 ml-1.5" style="border-color: {{ $accent }}30">
                                            @foreach($exps as $exp)
                                                @continue(empty($exp['role']) && empty($exp['company']))
                                                <div class="relative">
                                                    <span class="absolute -left-[27px] top-1 w-3 h-3 rounded-full border-2 bg-white shadow-xs" style="border-color: {{ $accent }}"></span>
                                                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                                                        <h3 class="font-extrabold text-[13px] text-slate-900">{{ $exp['role'] ?: ($exp['company'] ?? '') }}</h3>
                                                        <span class="px-2 py-0.5 rounded-full text-[9.5px] font-bold" style="background-color: {{ $accent }}14; color: {{ $accent }}">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</span>
                                                    </div>
                                                    <div class="text-[11px] font-semibold text-slate-600 mb-1.5">{{ implode(' • ', array_filter([$exp['company'] ?? '', $exp['location'] ?? ''])) }}</div>
                                                    <ul class="space-y-1 text-slate-700 pl-3.5 list-disc">
                                                        @foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif

                                @if(!empty($vis['projects']) && !empty($projects))
                                    <section>
                                        <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-3 flex items-center gap-2" style="color: {{ $accent }}">
                                            <span class="w-5 h-0.5 rounded-full" style="background-color: {{ $accent }}"></span> Key Initiatives
                                        </h2>
                                        <div class="grid grid-cols-1 gap-2.5">
                                            @foreach($projects as $proj)
                                                @continue(empty($proj['name']))
                                                <div class="p-3 rounded-lg border border-slate-200 bg-slate-50/70">
                                                    <div class="flex items-baseline justify-between gap-2">
                                                        <span class="font-bold text-slate-900">{{ $proj['name'] }}</span>
                                                        <span class="text-[10px] text-slate-500">{{ $proj['link'] ?? '' }}</span>
                                                    </div>
                                                    @if(!empty($proj['description']))<p class="text-slate-600 mt-0.5">{{ $proj['description'] }}</p>@endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif
                            </div>

                            <aside class="col-span-4 space-y-5 bg-slate-50/85 p-4 rounded-xl border border-slate-100 h-fit">
                                @if(!empty($vis['skills']) && !empty($skills))
                                    <section>
                                        <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-3" style="color: {{ $accent }}">Skill Proficiency</h2>
                                        <div class="space-y-2.5">
                                            @foreach($skills as $idx => $sk)
                                                @php $pct = 96 - (($idx * 6) % 22); @endphp
                                                <div>
                                                    <div class="flex justify-between text-[10.5px] font-semibold text-slate-800 mb-1">
                                                        <span>{{ $sk }}</span>
                                                        <span class="text-[9.5px] text-slate-400">{{ $pct }}%</span>
                                                    </div>
                                                    <div class="h-1.5 w-full rounded-full bg-slate-200 overflow-hidden">
                                                        <div class="h-full rounded-full" style="width: {{ $pct }}%; background-color: {{ $accent }};"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif

                                @if(!empty($vis['education']) && !empty($edus))
                                    <section class="pt-3 border-t border-slate-200">
                                        <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-2.5" style="color: {{ $accent }}">Education</h2>
                                        <div class="space-y-2.5">
                                            @foreach($edus as $edu)
                                                @continue(empty($edu['degree']) && empty($edu['school']))
                                                <div>
                                                    <div class="font-bold text-[11px] text-slate-900">{{ $edu['degree'] ?? '' }}</div>
                                                    <div class="text-[10.5px] text-slate-600">{{ $edu['school'] ?? '' }}</div>
                                                    <div class="text-[9.5px] text-slate-400">{{ implode(' – ', array_filter([$edu['start_date'] ?? '', $edu['end_date'] ?? ''])) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif

                                @if(!empty($vis['languages']) && !empty($langs))
                                    <section class="pt-3 border-t border-slate-200">
                                        <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-2.5" style="color: {{ $accent }}">Languages</h2>
                                        <div class="space-y-1.5 text-[11px]">
                                            @foreach($langs as $lang)
                                                @continue(empty($lang['name']))
                                                <div class="flex justify-between">
                                                    <span class="font-semibold text-slate-800">{{ $lang['name'] }}</span>
                                                    <span class="text-[10px] font-semibold" style="color: {{ $accent }}">{{ $lang['level'] ?? '' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif
                            </aside>
                        </div>
                    </div>

                {{-- 4. LUXE SPLIT --}}
                @elseif($tStyle === 'luxe_split')
                    <div class="min-h-[1122px] bg-white {{ $density === 'compact' ? 'p-8 space-y-5' : 'p-10 space-y-7' }}">
                        <header class="flex items-center justify-between gap-6 pb-6 border-b border-slate-200">
                            <div class="flex items-center gap-5 min-w-0">
                                <div class="w-16 h-16 rounded-xl border-2 shrink-0 flex items-center justify-center font-black text-xl tracking-tight overflow-hidden"
                                     style="border-color: {{ $accent }}; color: {{ $accent }}">
                                    @if($showPhoto && $photo)<img src="{{ $photo }}" alt="Portrait" class="w-full h-full object-cover">@else<span>{{ $initials }}</span>@endif
                                </div>
                                <div class="min-w-0">
                                    <h1 class="text-[26px] font-black tracking-tight text-slate-900 leading-tight {{ $font === 'serif' ? 'font-editorial' : '' }}">{{ $fullName }}</h1>
                                    <p class="text-[13px] font-semibold tracking-wide text-slate-500 mt-0.5">{{ $p['job_title'] ?? '' }}</p>
                                </div>
                            </div>
                            <div class="text-right text-[10.5px] text-slate-500 space-y-0.5 shrink-0">
                                @if(!empty($p['email']))<div class="font-semibold text-slate-900">{{ $p['email'] }}</div>@endif
                                @if(!empty($p['phone']))<div>{{ $p['phone'] }}</div>@endif
                                @if(!empty($p['location']))<div>{{ $p['location'] }}</div>@endif
                                @if(!empty($p['website']) || !empty($p['linkedin']))<div>{{ $p['website'] ?: ($p['linkedin'] ?? '') }}</div>@endif
                            </div>
                        </header>

                        @if(!empty($vis['summary']) && !empty($c['summary']))
                            <section class="p-4 rounded-xl bg-slate-50 border-l-4" style="border-left-color: {{ $accent }}">
                                <p class="text-[11.5px] leading-relaxed text-slate-700 whitespace-pre-line">{{ $c['summary'] }}</p>
                            </section>
                        @endif

                        <div class="grid grid-cols-12 gap-8 items-start">
                            <div class="col-span-7 space-y-6">
                                @if(!empty($vis['experience']) && !empty($exps))
                                    <section>
                                        <h2 class="text-[10.5px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-3.5">Experience</h2>
                                        <div class="space-y-4">
                                            @foreach($exps as $exp)
                                                @continue(empty($exp['role']) && empty($exp['company']))
                                                <div>
                                                    <div class="font-bold text-[13px] text-slate-900">{{ $exp['role'] ?: ($exp['company'] ?? '') }}</div>
                                                    <div class="flex items-center justify-between text-[11px] font-semibold mb-1.5" style="color: {{ $accent }}">
                                                        <span>{{ implode(' • ', array_filter([$exp['company'] ?? '', $exp['location'] ?? ''])) }}</span>
                                                        <span class="text-slate-400 font-normal">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</span>
                                                    </div>
                                                    <ul class="space-y-1 text-slate-700 pl-4 list-disc">
                                                        @foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif
                            </div>
                            <div class="col-span-5 space-y-5">
                                @if(!empty($vis['education']) && !empty($edus))
                                    <section>
                                        <h2 class="text-[10.5px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-3">Education</h2>
                                        <div class="space-y-3">
                                            @foreach($edus as $edu)
                                                @continue(empty($edu['degree']) && empty($edu['school']))
                                                <div class="p-3 rounded-lg border border-slate-100 bg-slate-50/60">
                                                    <div class="font-bold text-[11.5px] text-slate-900">{{ $edu['degree'] ?? '' }}</div>
                                                    <div class="text-[11px] font-medium" style="color: {{ $accent }}">{{ $edu['school'] ?? '' }}</div>
                                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ implode(' – ', array_filter([$edu['start_date'] ?? '', $edu['end_date'] ?? ''])) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif
                                @if(!empty($vis['skills']) && !empty($skills))
                                    <section>
                                        <h2 class="text-[10.5px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-2.5">Core Skills</h2>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($skills as $sk)<span class="px-2.5 py-1 rounded-full border border-slate-200 text-[10.5px] font-semibold text-slate-700">{{ $sk }}</span>@endforeach
                                        </div>
                                    </section>
                                @endif
                            </div>
                        </div>
                    </div>

                {{-- 5. EXECUTIVE BOLD --}}
                @elseif($tStyle === 'executive_bold')
                    <div class="min-h-[1122px] bg-white">
                        <header class="text-white px-9 py-7 flex flex-wrap items-center justify-between gap-4" style="background-color: {{ $accent }}">
                            <div class="min-w-0">
                                <h1 class="text-[28px] font-black tracking-tight uppercase leading-none text-white {{ $font === 'serif' ? 'font-editorial normal-case text-[31px]' : '' }}">{{ $fullName }}</h1>
                                <p class="text-[13px] font-semibold tracking-[0.14em] uppercase text-white/80 mt-2">{{ $p['job_title'] ?? '' }}</p>
                            </div>
                            <div class="text-right text-[10.5px] text-white/90 space-y-1">
                                @if(!empty($p['email']))<div class="font-bold">{{ $p['email'] }}</div>@endif
                                @if(!empty($p['phone']))<div>{{ $p['phone'] }}</div>@endif
                                @if(!empty($p['location']))<div>{{ $p['location'] }}</div>@endif
                                @if(!empty($p['linkedin']))<div class="text-white/75">{{ $p['linkedin'] }}</div>@endif
                            </div>
                        </header>
                        <div class="{{ $density === 'compact' ? 'p-7 space-y-4' : 'p-9 space-y-6' }}">
                            @if(!empty($vis['summary']) && !empty($c['summary']))
                                <section class="pb-4 border-b-2 border-slate-100">
                                    <div class="text-[10px] font-black uppercase tracking-[0.2em] mb-1.5" style="color: {{ $accent }}">Executive Mandates</div>
                                    <p class="text-[12px] leading-relaxed text-slate-700 font-medium whitespace-pre-line">{{ $c['summary'] }}</p>
                                </section>
                            @endif
                            <div class="grid grid-cols-12 gap-7 items-start">
                                <div class="col-span-8 space-y-5">
                                    @if(!empty($vis['experience']) && !empty($exps))
                                        <section>
                                            <h2 class="text-[11px] font-black uppercase tracking-[0.18em] pb-1.5 border-b mb-3.5" style="color: {{ $accent }}; border-color: {{ $accent }}">Leadership Experience</h2>
                                            <div class="space-y-4">
                                                @foreach($exps as $exp)
                                                    @continue(empty($exp['role']) && empty($exp['company']))
                                                    <div>
                                                        <div class="flex items-baseline justify-between gap-2">
                                                            <h3 class="font-extrabold text-[13px] text-slate-900">{{ $exp['role'] ?: ($exp['company'] ?? '') }}</h3>
                                                            <span class="text-[10.5px] font-bold text-slate-500">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</span>
                                                        </div>
                                                        <div class="text-[11px] font-bold mb-1.5" style="color: {{ $accent }}">{{ implode(' • ', array_filter([$exp['company'] ?? '', $exp['location'] ?? ''])) }}</div>
                                                        <ul class="space-y-1 text-slate-700 pl-4 list-disc">
                                                            @foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </section>
                                    @endif
                                </div>
                                <aside class="col-span-4 space-y-5">
                                    @if(!empty($vis['skills']) && !empty($skills))
                                        <section class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                                            <h2 class="text-[10.5px] font-black uppercase tracking-[0.18em] mb-2.5" style="color: {{ $accent }}">Core Competencies</h2>
                                            <ul class="space-y-1.5 text-[11px] font-semibold text-slate-800">
                                                @foreach($skills as $sk)<li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-xs shrink-0" style="background-color: {{ $accent }}"></span><span>{{ $sk }}</span></li>@endforeach
                                            </ul>
                                        </section>
                                    @endif
                                    @if(!empty($vis['education']) && !empty($edus))
                                        <section class="p-4 rounded-xl border border-slate-200">
                                            <h2 class="text-[10.5px] font-black uppercase tracking-[0.18em] mb-2.5" style="color: {{ $accent }}">Education</h2>
                                            <div class="space-y-2.5">
                                                @foreach($edus as $edu)
                                                    @continue(empty($edu['degree']) && empty($edu['school']))
                                                    <div><div class="font-bold text-[11.5px] text-slate-900">{{ $edu['degree'] ?? '' }}</div><div class="text-[10.5px] text-slate-600">{{ $edu['school'] ?? '' }}</div></div>
                                                @endforeach
                                            </div>
                                        </section>
                                    @endif
                                </aside>
                            </div>
                        </div>
                    </div>

                {{-- 6. CREATIVE CARD --}}
                @elseif($tStyle === 'creative_card')
                    <div class="grid grid-cols-12 min-h-[1122px] bg-white">
                        <aside class="col-span-4 border-r p-6 space-y-5" style="background-color: {{ $accent }}12; border-color: {{ $accent }}25;">
                            <div class="p-4 rounded-2xl bg-white shadow-xs border" style="border-color: {{ $accent }}22;">
                                @if($showPhoto)
                                    <div class="w-16 h-16 rounded-2xl mb-3 flex items-center justify-center text-white font-black text-xl shadow-sm overflow-hidden" style="background-color: {{ $accent }}">
                                        @if($photo)<img src="{{ $photo }}" alt="Portrait" class="w-full h-full object-cover">@else<span>{{ $initials }}</span>@endif
                                    </div>
                                @endif
                                <h1 class="text-[20px] font-black leading-tight text-slate-900 {{ $font === 'serif' ? 'font-editorial' : '' }}">{{ $fullName }}</h1>
                                <p class="text-[11.5px] font-bold mt-1" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</p>
                            </div>
                            <div class="space-y-1.5 text-[10.5px] text-slate-700 px-1">
                                <div class="text-[9.5px] font-extrabold uppercase tracking-[0.18em] mb-1.5" style="color: {{ $accent }}">Reach Out</div>
                                @if(!empty($p['email']))<div class="font-semibold text-slate-900 break-all">{{ $p['email'] }}</div>@endif
                                @if(!empty($p['phone']))<div>{{ $p['phone'] }}</div>@endif
                                @if(!empty($p['location']))<div>{{ $p['location'] }}</div>@endif
                                @if(!empty($p['website']))<div class="font-semibold underline" style="color: {{ $accent }}">{{ $p['website'] }}</div>@endif
                            </div>
                            @if(!empty($vis['skills']) && !empty($skills))
                                <section class="px-1 pt-2 border-t" style="border-color: {{ $accent }}20;">
                                    <div class="text-[9.5px] font-extrabold uppercase tracking-[0.18em] mb-2" style="color: {{ $accent }}">Toolbox &amp; Craft</div>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($skills as $sk)<span class="px-2.5 py-0.5 rounded-full bg-white text-[10px] font-bold border" style="border-color: {{ $accent }}30; color: {{ $accent }};">{{ $sk }}</span>@endforeach
                                    </div>
                                </section>
                            @endif
                        </aside>
                        <main class="col-span-8 {{ $density === 'compact' ? 'p-6 space-y-4' : 'p-8 space-y-6' }}">
                            @if(!empty($vis['summary']) && !empty($c['summary']))
                                <section><div class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-2" style="color: {{ $accent }}">About Me</div><p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $c['summary'] }}</p></section>
                            @endif
                            @if(!empty($vis['experience']) && !empty($exps))
                                <section>
                                    <div class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-3 pb-1 border-b border-slate-200" style="color: {{ $accent }}">Work History</div>
                                    <div class="space-y-4">
                                        @foreach($exps as $exp)
                                            @continue(empty($exp['role']) && empty($exp['company']))
                                            <div>
                                                <div class="flex items-baseline justify-between gap-2"><h3 class="font-extrabold text-[13px] text-slate-900">{{ $exp['role'] ?: ($exp['company'] ?? '') }}</h3><span class="text-[10px] font-semibold text-slate-500">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</span></div>
                                                <div class="text-[11px] font-bold mb-1" style="color: {{ $accent }}">{{ implode(' — ', array_filter([$exp['company'] ?? '', $exp['location'] ?? ''])) }}</div>
                                                <ul class="space-y-1 text-slate-700 pl-3.5 list-disc">@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                            @if(!empty($vis['projects']) && !empty($projects))
                                <section>
                                    <div class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-3 pb-1 border-b border-slate-200" style="color: {{ $accent }}">Featured Portfolio</div>
                                    <div class="space-y-2.5">
                                        @foreach($projects as $proj)
                                            @continue(empty($proj['name']))
                                            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/60"><div class="flex items-baseline justify-between"><span class="font-bold text-slate-900">{{ $proj['name'] }}</span><span class="text-[10px] font-semibold" style="color: {{ $accent }}">{{ $proj['link'] ?? '' }}</span></div>@if(!empty($proj['description']))<p class="text-slate-600 mt-1">{{ $proj['description'] }}</p>@endif</div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                        </main>
                    </div>

                {{-- 7. TECH STACK --}}
                @elseif($tStyle === 'tech_stack')
                    <div class="min-h-[1122px] bg-white border-t-[5px] {{ $density === 'compact' ? 'p-7 space-y-4' : 'p-9 space-y-5' }}" style="border-top-color: {{ $accent }}">
                        <header class="flex flex-wrap items-start justify-between gap-4 pb-4 border-b border-slate-200">
                            <div>
                                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded font-tech-mono text-[10px] font-semibold mb-1.5" style="background-color: {{ $accent }}14; color: {{ $accent }}">$ whoami --role</div>
                                <h1 class="text-[25px] font-black tracking-tight text-slate-900">{{ $fullName }}</h1>
                                <p class="font-tech-mono text-[12px] font-semibold mt-0.5" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</p>
                            </div>
                            <div class="font-tech-mono text-[10px] text-slate-600 space-y-1 text-right">
                                @if(!empty($p['email']))<div class="font-bold text-slate-900">{{ $p['email'] }}</div>@endif
                                @if(!empty($p['website']))<div class="font-semibold" style="color: {{ $accent }}">{{ $p['website'] }}</div>@endif
                                @if(!empty($p['linkedin']))<div>{{ $p['linkedin'] }}</div>@endif
                            </div>
                        </header>
                        @if(!empty($vis['summary']) && !empty($c['summary']))
                            <section><div class="font-tech-mono text-[10px] font-bold uppercase tracking-wider mb-1" style="color: {{ $accent }}">// 01. SYSTEM_SUMMARY</div><p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $c['summary'] }}</p></section>
                        @endif
                        @if(!empty($vis['skills']) && !empty($skills))
                            <section class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                                <div class="font-tech-mono text-[10px] font-bold uppercase tracking-wider mb-2" style="color: {{ $accent }}">// 02. TECH_STACK</div>
                                <div class="flex flex-wrap gap-1.5">@foreach($skills as $sk)<span class="px-2 py-0.5 rounded bg-white border border-slate-300 font-tech-mono text-[10.5px] font-semibold text-slate-800">{{ $sk }}</span>@endforeach</div>
                            </section>
                        @endif
                        @if(!empty($vis['experience']) && !empty($exps))
                            <section>
                                <div class="font-tech-mono text-[10px] font-bold uppercase tracking-wider mb-3" style="color: {{ $accent }}">// 03. PRODUCTION_EXPERIENCE</div>
                                <div class="space-y-4">
                                    @foreach($exps as $exp)
                                        @continue(empty($exp['role']) && empty($exp['company']))
                                        <div>
                                            <div class="flex flex-wrap items-baseline justify-between gap-2"><h3 class="font-extrabold text-[13px] text-slate-900">{{ $exp['role'] ?? '' }} <span class="text-slate-400 font-normal">@</span> <span style="color: {{ $accent }}">{{ $exp['company'] ?? '' }}</span></h3><span class="font-tech-mono text-[10px] text-slate-500">{{ implode(' -> ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</span></div>
                                            <ul class="space-y-1 text-slate-700 pl-4 list-disc mt-1">@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif
                        @if(!empty($vis['projects']) && !empty($projects))
                            <section>
                                <div class="font-tech-mono text-[10px] font-bold uppercase tracking-wider mb-2.5" style="color: {{ $accent }}">// 04. OPEN_SOURCE &amp; ARCHITECTURE</div>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach($projects as $proj)
                                        @continue(empty($proj['name']))
                                        <div class="p-3 rounded-lg border border-slate-200 bg-slate-50/50"><div class="font-tech-mono font-bold text-[11px] text-slate-900">{{ $proj['name'] }}</div>@if(!empty($proj['link']))<div class="font-tech-mono text-[9.5px] mb-1 truncate" style="color: {{ $accent }}">{{ $proj['link'] }}</div>@endif @if(!empty($proj['description']))<p class="text-[10.5px] text-slate-600">{{ $proj['description'] }}</p>@endif</div>
                                    @endforeach
                                </div>
                            </section>
                        @endif
                    </div>

                {{-- 8. MINIMAL SWISS --}}
                @elseif($tStyle === 'minimal_swiss')
                    <div class="min-h-[1122px] bg-white {{ $density === 'compact' ? 'p-8 space-y-5' : 'p-10 space-y-6' }}">
                        <header class="border-t-[4px] pt-5 pb-5 border-b border-slate-200 grid grid-cols-12 gap-4 items-end" style="border-top-color: {{ $accent }}">
                            <div class="col-span-8">
                                <h1 class="text-[30px] font-black tracking-tight leading-none text-slate-900 {{ $font === 'serif' ? 'font-editorial' : '' }}">{{ $fullName }}</h1>
                                <p class="text-[13px] font-semibold mt-2" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</p>
                            </div>
                            <div class="col-span-4 text-right text-[10.5px] text-slate-600 space-y-0.5">
                                @if(!empty($p['email']))<div class="font-semibold text-slate-900">{{ $p['email'] }}</div>@endif
                                @if(!empty($p['phone']))<div>{{ $p['phone'] }}</div>@endif
                                @if(!empty($p['location']))<div>{{ $p['location'] }}</div>@endif
                            </div>
                        </header>
                        @if(!empty($vis['summary']) && !empty($c['summary']))
                            <section class="grid grid-cols-12 gap-5 border-b border-slate-100 pb-4"><div class="col-span-3 text-[9.5px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Profile</div><p class="col-span-9 text-slate-700 leading-relaxed whitespace-pre-line">{{ $c['summary'] }}</p></section>
                        @endif
                        @if(!empty($vis['experience']) && !empty($exps))
                            <section class="grid grid-cols-12 gap-5 border-b border-slate-100 pb-5">
                                <div class="col-span-3 text-[9.5px] font-extrabold uppercase tracking-[0.2em]" style="color: {{ $accent }}">Experience</div>
                                <div class="col-span-9 space-y-4">
                                    @foreach($exps as $exp)
                                        @continue(empty($exp['role']) && empty($exp['company']))
                                        <div>
                                            <div class="flex items-baseline justify-between gap-2"><h3 class="font-extrabold text-[13px] text-slate-900">{{ $exp['role'] ?: ($exp['company'] ?? '') }}</h3><span class="text-[10.5px] text-slate-500">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</span></div>
                                            <div class="text-[11px] font-semibold text-slate-500 mb-1.5">{{ implode(' / ', array_filter([$exp['company'] ?? '', $exp['location'] ?? ''])) }}</div>
                                            <ul class="space-y-1 text-slate-700 pl-3.5 list-disc">@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif
                        @if(!empty($vis['education']) && !empty($edus))
                            <section class="grid grid-cols-12 gap-5 border-b border-slate-100 pb-4"><div class="col-span-3 text-[9.5px] font-extrabold uppercase tracking-[0.2em]" style="color: {{ $accent }}">Education</div><div class="col-span-9 space-y-2.5">@foreach($edus as $edu)@continue(empty($edu['degree']) && empty($edu['school']))<div><div class="font-bold text-slate-900">{{ $edu['degree'] ?? '' }}</div><div class="text-[11px] text-slate-600">{{ $edu['school'] ?? '' }}</div></div>@endforeach</div></section>
                        @endif
                        @if(!empty($vis['skills']) && !empty($skills))
                            <section class="grid grid-cols-12 gap-5"><div class="col-span-3 text-[9.5px] font-extrabold uppercase tracking-[0.2em]" style="color: {{ $accent }}">Capabilities</div><div class="col-span-9 text-slate-800 font-medium">{{ implode('   •   ', $skills) }}</div></section>
                        @endif
                    </div>

                {{-- 9. ATS CLASSIC --}}
                @elseif($tStyle === 'ats_classic')
                    <div class="min-h-[1122px] bg-white text-slate-900 {{ $density === 'compact' ? 'p-8 space-y-3.5' : 'p-10 space-y-4' }}">
                        <header class="text-center pb-3 border-b-2 border-slate-900">
                            <h1 class="text-[24px] font-bold tracking-wide uppercase text-slate-900 {{ $font === 'serif' ? 'font-editorial tracking-normal' : '' }}">{{ $fullName }}</h1>
                            <div class="text-[11.5px] font-semibold text-slate-700 mt-0.5">{{ $p['job_title'] ?? '' }}</div>
                            <div class="text-[10.5px] text-slate-600 mt-1.5">{{ implode(' • ', array_filter([$p['location'] ?? '', $p['phone'] ?? '', $p['email'] ?? '', $p['linkedin'] ?? ''])) }}</div>
                        </header>
                        @if(!empty($vis['summary']) && !empty($c['summary']))
                            <section><h2 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-900 border-b border-slate-400 pb-0.5 mb-1.5">Professional Summary</h2><p class="text-[11.5px] leading-relaxed text-slate-800 whitespace-pre-line">{{ $c['summary'] }}</p></section>
                        @endif
                        @if(!empty($vis['experience']) && !empty($exps))
                            <section>
                                <h2 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-900 border-b border-slate-400 pb-0.5 mb-2">Professional Experience</h2>
                                <div class="space-y-3">
                                    @foreach($exps as $exp)
                                        @continue(empty($exp['role']) && empty($exp['company']))
                                        <div>
                                            <div class="flex justify-between font-bold text-[12px]"><span>{{ $exp['company'] ?: ($exp['role'] ?? '') }}</span><span>{{ $exp['location'] ?? '' }}</span></div>
                                            <div class="flex justify-between italic text-[11.5px] text-slate-800 mb-1"><span>{{ $exp['role'] ?? '' }}</span><span>{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</span></div>
                                            <ul class="space-y-0.5 text-slate-800 pl-4 list-disc">@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif
                        @if(!empty($vis['education']) && !empty($edus))
                            <section>
                                <h2 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-900 border-b border-slate-400 pb-0.5 mb-2">Education</h2>
                                @foreach($edus as $edu)
                                    @continue(empty($edu['degree']) && empty($edu['school']))
                                    <div class="mb-1.5"><div class="flex justify-between font-bold text-[11.5px]"><span>{{ $edu['school'] ?: ($edu['degree'] ?? '') }}</span><span>{{ implode(' – ', array_filter([$edu['start_date'] ?? '', $edu['end_date'] ?? ''])) }}</span></div><div class="text-[11px] text-slate-800">{{ implode(' — ', array_filter([$edu['degree'] ?? '', $edu['details'] ?? ''])) }}</div></div>
                                @endforeach
                            </section>
                        @endif
                        @if(!empty($vis['skills']) && !empty($skills))
                            <section><h2 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-900 border-b border-slate-400 pb-0.5 mb-1.5">Skills &amp; Additional Information</h2><p class="text-[11px] text-slate-800"><strong>Core Competencies:</strong> {{ implode(', ', $skills) }}</p></section>
                        @endif
                    </div>

                {{-- 10. COMPACT ONE-PAGER --}}
                @else
                    <div class="min-h-[1122px] bg-white p-7 space-y-4">
                        <header class="flex items-end justify-between gap-4 border-b-2 pb-3" style="border-color: {{ $accent }}">
                            <div><h1 class="text-[24px] font-black tracking-tight text-slate-900 leading-none {{ $font === 'serif' ? 'font-editorial' : '' }}">{{ $fullName }}</h1><p class="text-[12px] font-bold mt-1" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</p></div>
                            <div class="text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider">One-Page Executive Sheet</div>
                        </header>
                        <div class="grid grid-cols-3 gap-2 px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-[10.5px] text-slate-700">
                            <div class="truncate"><strong class="text-slate-900">Email:</strong> {{ $p['email'] ?? '' }}</div>
                            <div class="truncate text-center"><strong class="text-slate-900">Phone:</strong> {{ $p['phone'] ?? '' }}</div>
                            <div class="truncate text-right"><strong class="text-slate-900">Loc:</strong> {{ $p['location'] ?? '' }}</div>
                        </div>
                        @if(!empty($vis['summary']) && !empty($c['summary']))
                            <section class="p-3 rounded-lg border text-[11px] leading-snug text-slate-700" style="border-color: {{ $accent }}30; background-color: {{ $accent }}06;"><p class="whitespace-pre-line">{{ $c['summary'] }}</p></section>
                        @endif
                        @if(!empty($vis['experience']) && !empty($exps))
                            <section>
                                <h2 class="text-[10.5px] font-extrabold uppercase tracking-[0.16em] mb-2" style="color: {{ $accent }}">Experience</h2>
                                <div class="space-y-3">
                                    @foreach($exps as $exp)
                                        @continue(empty($exp['role']) && empty($exp['company']))
                                        <div>
                                            <div class="flex items-baseline justify-between gap-2"><span class="font-bold text-[12px] text-slate-900">{{ $exp['role'] ?? '' }} — <span style="color: {{ $accent }}">{{ $exp['company'] ?? '' }}</span></span><span class="text-[10px] font-semibold text-slate-500">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</span></div>
                                            <ul class="space-y-0.5 text-[11px] text-slate-700 pl-3.5 list-disc mt-0.5">@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif
                        <div class="grid grid-cols-12 gap-5 pt-2 border-t border-slate-200">
                            @if(!empty($vis['skills']) && !empty($skills))
                                <section class="col-span-6"><h2 class="text-[10px] font-extrabold uppercase tracking-[0.16em] mb-2" style="color: {{ $accent }}">Skills &amp; Domain Expertise</h2><div class="flex flex-wrap gap-1">@foreach($skills as $sk)<span class="px-2 py-0.5 rounded bg-slate-100 text-[10px] font-semibold text-slate-800">{{ $sk }}</span>@endforeach</div></section>
                            @endif
                            @if(!empty($vis['education']) && !empty($edus))
                                <section class="col-span-6"><h2 class="text-[10px] font-extrabold uppercase tracking-[0.16em] mb-2" style="color: {{ $accent }}">Education &amp; Credentials</h2><div class="space-y-1.5">@foreach($edus as $edu)@continue(empty($edu['degree']) && empty($edu['school']))<div class="text-[10.5px]"><div class="font-bold text-slate-900">{{ $edu['degree'] ?? '' }}</div><div class="text-slate-600">{{ implode(' • ', array_filter([$edu['school'] ?? '', $edu['end_date'] ?? ''])) }}</div></div>@endforeach</div></section>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
