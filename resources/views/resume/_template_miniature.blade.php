@php
    $c = $tpl['preset_content'] ?? ($tpl['content'] ?? []);
    $p = $c['personal'] ?? [];
    $accent = $tpl['accent_color'] ?? '#1B3142';
    $tStyle = $tpl['style'] ?? ($tpl['template_style'] ?? 'flow_sidebar');
    $exps = array_slice($c['experience'] ?? [], 0, 3);
    $edus = array_slice($c['education'] ?? [], 0, 2);
    $skills = array_slice($c['skills'] ?? [], 0, 8);
    $langs = array_slice($c['languages'] ?? [], 0, 3);
    $awards = array_slice($c['awards'] ?? [], 0, 2);
    $projs = array_slice($c['projects'] ?? [], 0, 2);
    $words = preg_split('/\s+/', trim($p['full_name'] ?? 'FN'));
    $init = strtoupper(substr($words[0] ?? 'F', 0, 1) . substr($words[count($words) - 1] ?? '', 0, 1));

    $parseBullets = static function (?string $text, int $limit = 3): array {
        if (!$text) {
            return [];
        }
        $lines = preg_split('/\r?\n/', trim($text)) ?: [];
        $out = [];
        foreach ($lines as $line) {
            $clean = ltrim(trim($line), "-•*▪▸ \t");
            if ($clean !== '') {
                $out[] = $clean;
            }
            if (count($out) >= $limit) {
                break;
            }
        }
        return $out;
    };
@endphp

<div class="mini-a4-shell h-full w-full overflow-hidden bg-white select-none">
{{-- RB-1. BANNER SPLIT DATE MINIATURE (Casey Clark, Pat Morgan) --}}
@if($tStyle === 'banner_split_date')
    <div class="mini-a4-sheet h-full bg-white flex flex-col justify-between text-slate-800">
        <div class="px-[1.4em] py-[1.05em] text-white flex items-center justify-between gap-[0.8em] shrink-0" style="background-color: {{ $accent }};">
            <div class="min-w-0 flex-1">
                <div class="font-bold text-[1.38em] leading-tight text-white truncate">{{ $p['full_name'] ?? '' }}</div>
                <div class="text-[0.82em] text-white/90 truncate mt-[0.15em] font-medium">{{ $p['job_title'] ?? '' }}</div>
                <div class="text-[0.72em] text-white/80 truncate mt-[0.12em]">{{ implode(' • ', array_filter([$p['location'] ?? '', $p['phone'] ?? '', $p['email'] ?? ''])) }}</div>
            </div>
            @if(!empty($tpl['show_photo']))
                <div class="w-[3.8em] h-[3.8em] rounded-full border border-white/80 bg-white/15 shrink-0 overflow-hidden flex items-center justify-center font-black text-[1em] text-white">
                    @if(!empty($p['preset_avatar']))<img src="{{ asset($p['preset_avatar']) }}" alt="{{ $p['full_name'] ?? 'Profile' }}" width="64" height="64" class="w-full h-full object-cover">@else{{ $init }}@endif
                </div>
            @endif
        </div>
        <div class="p-[1.2em] flex-1 flex flex-col justify-between gap-[0.55em] min-h-0">
            <p class="text-[0.8em] text-slate-600 line-clamp-3 border-b border-slate-200 pb-[0.45em]">{{ $c['summary'] ?? '' }}</p>

            <div>
                <div class="font-bold text-[0.88em] mb-[0.25em]" style="color: {{ $accent }}">Skills</div>
                <div class="grid grid-cols-2 gap-x-[0.9em] gap-y-[0.15em] text-[0.78em] text-slate-700">
                    @foreach(array_slice($skills, 0, 6) as $sk)
                        <div class="truncate">&bull; {{ $sk }}</div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-[0.45em]">
                <div class="font-bold text-[0.88em] border-b border-slate-200 pb-[0.15em]" style="color: {{ $accent }}">Professional Experience</div>
                @foreach($exps as $idx => $e)
                    @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                    <div class="grid grid-cols-12 gap-[0.5em] items-start">
                        <div class="col-span-3 text-[0.7em] text-slate-500 font-semibold leading-tight pt-[0.1em]">
                            <div>{{ $e['start_date'] ?? '' }} &ndash;</div>
                            <div>{{ $e['end_date'] ?? '' }}</div>
                        </div>
                        <div class="col-span-9 min-w-0">
                            <div class="font-bold text-[0.85em] text-slate-900 truncate">{{ $e['role'] ?? '' }}</div>
                            <div class="text-[0.76em] font-semibold truncate mb-[0.12em]" style="color: {{ $accent }}">{{ $e['company'] ?? '' }}@if(!empty($e['location'])) &bull; {{ $e['location'] }}@endif</div>
                            <ul class="space-y-[0.1em] text-[0.73em] text-slate-600">
                                @foreach($bullets as $b)
                                    <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="space-y-[0.35em] pt-[0.3em] border-t border-slate-200">
                <div>
                    <div class="font-bold text-[0.86em] mb-[0.15em]" style="color: {{ $accent }}">Education</div>
                    @foreach(array_slice($edus, 0, 1) as $ed)
                        <div class="grid grid-cols-12 gap-[0.5em] items-start">
                            <div class="col-span-3 text-[0.7em] text-slate-500 font-semibold">{{ $ed['start_date'] ?? '' }} &ndash; {{ $ed['end_date'] ?? '' }}</div>
                            <div class="col-span-9 min-w-0">
                                <div class="font-bold text-[0.8em] text-slate-900 truncate">{{ $ed['degree'] ?? '' }}</div>
                                <div class="text-[0.72em] text-slate-600 truncate">{{ $ed['school'] ?? '' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if(!empty($awards))
                    <div>
                        <div class="font-bold text-[0.86em] mb-[0.12em]" style="color: {{ $accent }}">Certifications</div>
                        <div class="grid grid-cols-2 gap-x-[0.8em] gap-y-[0.12em]">
                            @foreach($awards as $aw)
                                <div class="text-[0.72em] text-slate-700 truncate"><span class="font-semibold text-slate-900">{{ $aw['title'] ?? '' }}</span> &bull; {{ $aw['date'] ?? '' }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

{{-- RB-2. RIBBON CENTERED MINIATURE (Chris Taylor) --}}
@elseif($tStyle === 'ribbon_centered')
    <div class="mini-a4-sheet h-full bg-white p-[1.25em] flex flex-col justify-between gap-[0.5em] text-slate-800">
        <div>
            <div class="py-[0.6em] px-[1em] text-center text-white font-bold text-[1.3em] tracking-tight" style="background-color: {{ $accent }};">{{ $p['full_name'] ?? '' }}</div>
            <div class="flex justify-between gap-[0.6em] text-[0.72em] text-slate-500 pt-[0.35em] pb-[0.4em] border-b border-slate-200">
                <span class="truncate">{{ $p['location'] ?? '' }}@if(!empty($p['phone'])) &bull; {{ $p['phone'] }}@endif</span>
                <span class="font-semibold text-slate-700 truncate">{{ $p['email'] ?? '' }}</span>
            </div>
        </div>

        <p class="text-[0.78em] text-slate-600 line-clamp-3">{{ $c['summary'] ?? '' }}</p>

        <div>
            <div class="font-bold text-[0.88em] border-b border-slate-300 pb-[0.15em] mb-[0.25em]" style="color: {{ $accent }}">Skills</div>
            <div class="grid grid-cols-2 gap-x-[0.8em] gap-y-[0.15em] text-[0.76em] text-slate-700">
                @foreach(array_slice($skills, 0, 6) as $sk)
                    <div class="truncate">&bull; {{ $sk }}</div>
                @endforeach
            </div>
        </div>

        <div class="space-y-[0.45em]">
            <div class="font-bold text-[0.88em] border-b border-slate-300 pb-[0.15em]" style="color: {{ $accent }}">Professional Experience</div>
            @foreach($exps as $idx => $e)
                @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                <div>
                    <div class="flex justify-between gap-[0.4em] font-bold text-[0.84em] text-slate-900">
                        <span class="truncate">{{ $e['role'] ?? '' }}</span>
                        <span class="shrink-0 text-[0.74em] font-semibold text-slate-500">{{ ($e['start_date'] ?? '') . ' – ' . ($e['end_date'] ?? '') }}</span>
                    </div>
                    <div class="text-[0.75em] font-semibold mb-[0.1em]" style="color: {{ $accent }}">{{ $e['company'] ?? '' }}@if(!empty($e['location'])), {{ $e['location'] }}@endif</div>
                    <ul class="space-y-[0.1em] text-[0.73em] text-slate-600">
                        @foreach($bullets as $b)
                            <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="space-y-[0.35em] pt-[0.3em] border-t border-slate-200">
            <div>
                <div class="font-bold text-[0.86em] mb-[0.12em]" style="color: {{ $accent }}">Education</div>
                @foreach(array_slice($edus, 0, 1) as $ed)
                    <div class="flex justify-between items-baseline gap-[0.4em]">
                        <div class="min-w-0">
                            <span class="font-bold text-[0.79em] text-slate-900">{{ $ed['degree'] ?? '' }}</span>
                            <span class="text-[0.72em] text-slate-600"> &mdash; {{ $ed['school'] ?? '' }}</span>
                        </div>
                        <span class="text-[0.7em] text-slate-500 shrink-0">{{ $ed['end_date'] ?? '' }}</span>
                    </div>
                @endforeach
            </div>
            @if(!empty($awards))
                <div>
                    <div class="font-bold text-[0.86em] mb-[0.12em]" style="color: {{ $accent }}">Certifications &amp; Honors</div>
                    <div class="grid grid-cols-2 gap-x-[0.8em] text-[0.72em] text-slate-700">
                        @foreach($awards as $aw)
                            <div class="truncate">&bull; <span class="font-semibold text-slate-900">{{ $aw['title'] ?? '' }}</span> ({{ $aw['date'] ?? '' }})</div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

{{-- A. BENTO MODERN MINIATURE (Maya Patel, Hannah Brooks) --}}
@elseif($tStyle === 'bento_modern')
    <div class="mini-a4-sheet h-full bg-[#F8FAFC] p-[1em] flex flex-col justify-between gap-[0.5em] text-slate-800">
        <div class="bg-white rounded-[0.65em] border border-slate-200 p-[0.75em] flex items-center justify-between gap-[0.55em] shrink-0">
            <div class="flex items-center gap-[0.6em] min-w-0 flex-1">
                @if(!empty($tpl['show_photo']))
                    <div class="w-[3.2em] h-[3.2em] rounded-[0.6em] shrink-0 flex items-center justify-center text-white font-black text-[0.95em] overflow-hidden" style="background-color: {{ $accent }};">
                        @if(!empty($p['preset_avatar']))<img src="{{ asset($p['preset_avatar']) }}" alt="{{ $p['full_name'] ?? 'Profile' }}" width="64" height="64" class="w-full h-full object-cover">@else{{ $init }}@endif
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <div class="inline-block px-[0.5em] py-[0.1em] rounded-full text-[0.65em] font-extrabold uppercase max-w-full truncate mb-[0.15em]" style="background: {{ $accent }}15; color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</div>
                    <div class="font-black text-[1.25em] text-slate-900 truncate leading-tight">{{ $p['full_name'] ?? '' }}</div>
                </div>
            </div>
            <div class="text-right text-[0.7em] text-slate-500 space-y-[0.15em] shrink-0 max-w-[40%]">
                <div class="px-[0.45em] py-[0.15em] rounded bg-slate-100 font-bold text-slate-700 truncate">{{ $p['email'] ?? '' }}</div>
                <div class="truncate">{{ $p['location'] ?? '' }}</div>
            </div>
        </div>

        <div class="bg-white rounded-[0.65em] border border-slate-200 p-[0.7em] border-l-[0.25em] shrink-0" style="border-left-color: {{ $accent }};">
            <p class="text-[0.78em] text-slate-600 line-clamp-3">{{ $c['summary'] ?? '' }}</p>
        </div>

        <div class="grid grid-cols-12 gap-[0.55em] flex-1 min-h-0">
            <div class="col-span-8 bg-white rounded-[0.65em] border border-slate-200 p-[0.75em] flex flex-col justify-between gap-[0.45em]">
                <div class="space-y-[0.45em]">
                    <div class="text-[0.74em] font-extrabold uppercase tracking-wider" style="color: {{ $accent }}">Career Impact</div>
                    @foreach($exps as $idx => $e)
                        @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                        <div class="pl-[0.55em] border-l-[0.15em]" style="border-color: {{ $accent }}40;">
                            <div class="flex justify-between gap-[0.35em] items-baseline">
                                <span class="font-bold text-[0.84em] text-slate-900 truncate">{{ $e['role'] ?? '' }}</span>
                                <span class="text-[0.7em] text-slate-400 shrink-0">{{ $e['start_date'] ?? '' }}–{{ $e['end_date'] ?? '' }}</span>
                            </div>
                            <div class="text-[0.75em] font-semibold mb-[0.1em]" style="color: {{ $accent }}">{{ $e['company'] ?? '' }}</div>
                            <ul class="space-y-[0.1em] text-[0.72em] text-slate-600">
                                @foreach($bullets as $b)
                                    <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
                @if(!empty($projs))
                    @php $pr = $projs[0]; @endphp
                    <div class="p-[0.5em] rounded-[0.4em] bg-slate-50 border border-slate-200/80">
                        <div class="text-[0.7em] font-extrabold uppercase tracking-wider mb-[0.1em]" style="color: {{ $accent }}">Key Initiative</div>
                        <div class="font-bold text-[0.78em] text-slate-900 truncate">{{ $pr['name'] ?? '' }}</div>
                        <p class="text-[0.7em] text-slate-600 line-clamp-2">{{ $pr['description'] ?? '' }}</p>
                    </div>
                @endif
            </div>

            <div class="col-span-4 flex flex-col justify-between gap-[0.45em]">
                <div class="bg-white rounded-[0.65em] border border-slate-200 p-[0.65em]">
                    <div class="text-[0.7em] font-extrabold uppercase tracking-wider mb-[0.25em]" style="color: {{ $accent }}">Core Stack</div>
                    <div class="flex flex-wrap gap-[0.2em]">
                        @foreach($skills as $sk)
                            <span class="px-[0.4em] py-[0.15em] rounded text-[0.68em] font-bold" style="background: {{ $accent }}12; color: #1e293b;">{{ $sk }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="bg-white rounded-[0.65em] border border-slate-200 p-[0.65em]">
                    <div class="text-[0.7em] font-extrabold uppercase tracking-wider mb-[0.2em]" style="color: {{ $accent }}">Education</div>
                    @foreach(array_slice($edus, 0, 1) as $ed)
                        <div class="font-bold text-[0.76em] text-slate-900 leading-snug">{{ $ed['degree'] ?? '' }}</div>
                        <div class="text-[0.7em] text-slate-500 mt-[0.08em]">{{ $ed['school'] ?? '' }}</div>
                    @endforeach
                </div>
                <div class="bg-white rounded-[0.65em] border border-slate-200 p-[0.65em] flex-1 flex flex-col justify-between">
                    @if(!empty($awards))
                        <div>
                            <div class="text-[0.7em] font-extrabold uppercase tracking-wider mb-[0.15em]" style="color: {{ $accent }}">Honors</div>
                            @foreach($awards as $aw)
                                <div class="text-[0.7em] font-semibold text-slate-800 leading-snug">{{ $aw['title'] ?? '' }}</div>
                                <div class="text-[0.66em] text-slate-500 mb-[0.15em]">{{ $aw['issuer'] ?? '' }} &bull; {{ $aw['date'] ?? '' }}</div>
                            @endforeach
                        </div>
                    @endif
                    @if(!empty($langs))
                        <div class="pt-[0.2em] border-t border-slate-100">
                            <div class="text-[0.68em] font-extrabold uppercase tracking-wider mb-[0.1em]" style="color: {{ $accent }}">Languages</div>
                            <div class="text-[0.7em] text-slate-600">{{ implode(' • ', array_map(fn($l) => $l['name'] ?? '', $langs)) }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

{{-- B. STRIPE CLEAN MINIATURE (Lucas Meyer, Claire Dupont) --}}
@elseif($tStyle === 'stripe_clean')
    <div class="mini-a4-sheet h-full bg-white border-t-[0.4em] p-[1.25em] flex flex-col justify-between gap-[0.55em] text-slate-800" style="border-top-color: {{ $accent }};">
        <div class="flex justify-between items-start border-b border-slate-200 pb-[0.5em] gap-[0.5em] shrink-0">
            <div class="min-w-0">
                <div class="font-black text-[1.38em] text-slate-900 leading-none truncate">{{ $p['full_name'] ?? '' }}</div>
                <div class="text-[0.82em] font-bold mt-[0.25em] truncate" style="color: {{ $accent }}">&mdash; {{ $p['job_title'] ?? '' }}</div>
            </div>
            <div class="text-right text-[0.72em] text-slate-500 space-y-[0.1em] shrink-0">
                <div class="font-bold text-slate-800">{{ $p['email'] ?? '' }}</div>
                <div>{{ $p['phone'] ?? '' }} &bull; {{ $p['location'] ?? '' }}</div>
            </div>
        </div>

        <p class="text-[0.78em] text-slate-600 line-clamp-3 border-b border-slate-100 pb-[0.45em]">{{ $c['summary'] ?? '' }}</p>

        <div class="grid grid-cols-12 gap-[0.85em] flex-1">
            <div class="col-span-8 flex flex-col justify-between gap-[0.45em]">
                <div class="space-y-[0.45em]">
                    <div class="text-[0.76em] font-black uppercase tracking-wider border-b pb-[0.15em]" style="color: {{ $accent }}; border-color: {{ $accent }}30;">Experience</div>
                    @foreach($exps as $idx => $e)
                        @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                        <div>
                            <div class="flex justify-between font-bold text-[0.85em] text-slate-900 gap-[0.35em]">
                                <span class="truncate">{{ $e['role'] ?? '' }}</span>
                                <span class="text-slate-400 font-semibold text-[0.74em] shrink-0">{{ ($e['start_date'] ?? '') . '–' . ($e['end_date'] ?? '') }}</span>
                            </div>
                            <div class="text-[0.75em] font-semibold mb-[0.1em]" style="color: {{ $accent }}">{{ $e['company'] ?? '' }}</div>
                            <ul class="space-y-[0.1em] text-[0.73em] text-slate-600">
                                @foreach($bullets as $b)
                                    <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
                @if(!empty($projs))
                    @php $pr = $projs[0]; @endphp
                    <div class="p-[0.5em] rounded bg-slate-50 border border-slate-200/80">
                        <div class="text-[0.72em] font-black uppercase tracking-wider mb-[0.1em]" style="color: {{ $accent }}">Selected Initiative</div>
                        <div class="font-bold text-[0.78em] text-slate-900 truncate">{{ $pr['name'] ?? '' }}</div>
                        <p class="text-[0.71em] text-slate-600 line-clamp-2">{{ $pr['description'] ?? '' }}</p>
                    </div>
                @endif
            </div>

            <div class="col-span-4 flex flex-col justify-between gap-[0.5em]">
                <div>
                    <div class="text-[0.76em] font-black uppercase tracking-wider border-b pb-[0.15em] mb-[0.25em]" style="color: {{ $accent }}; border-color: {{ $accent }}30;">Skills</div>
                    <div class="flex flex-wrap gap-[0.2em]">
                        @foreach($skills as $sk)
                            <span class="px-[0.45em] py-[0.15em] rounded bg-slate-100 text-slate-800 text-[0.7em] font-bold">{{ $sk }}</span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <div class="text-[0.76em] font-black uppercase tracking-wider border-b pb-[0.15em] mb-[0.2em]" style="color: {{ $accent }}; border-color: {{ $accent }}30;">Education</div>
                    @foreach(array_slice($edus, 0, 1) as $ed)
                        <div class="font-bold text-[0.78em] text-slate-900 leading-snug">{{ $ed['degree'] ?? '' }}</div>
                        <div class="text-[0.7em] text-slate-500 mt-[0.08em]">{{ $ed['school'] ?? '' }}</div>
                    @endforeach
                </div>
                <div class="space-y-[0.3em]">
                    @if(!empty($awards))
                        <div class="pt-[0.2em] border-t border-slate-200">
                            <div class="text-[0.72em] font-black uppercase tracking-wider mb-[0.1em]" style="color: {{ $accent }}">Honors</div>
                            @foreach($awards as $aw)
                                <div class="text-[0.71em] font-semibold text-slate-800">{{ $aw['title'] ?? '' }}</div>
                            @endforeach
                        </div>
                    @endif
                    @if(!empty($langs))
                        <div class="pt-[0.2em] border-t border-slate-200">
                            <div class="text-[0.7em] font-black uppercase tracking-wider mb-[0.08em]" style="color: {{ $accent }}">Languages</div>
                            <div class="text-[0.7em] text-slate-600">{{ implode(' • ', array_map(fn($l) => $l['name'] ?? '', $langs)) }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

{{-- C. AURORA PILL MINIATURE (Noah Bennett, Mateo Alvarez) --}}
@elseif($tStyle === 'aurora_pill')
    <div class="mini-a4-sheet h-full bg-white p-[1.15em] flex flex-col justify-between gap-[0.5em] text-slate-800">
        <div class="rounded-[0.7em] border p-[0.8em] space-y-[0.35em] shrink-0" style="background: linear-gradient(135deg, {{ $accent }}14 0%, {{ $accent }}05 100%); border-color: {{ $accent }}30;">
            <div class="flex items-center gap-[0.6em]">
                @if(!empty($tpl['show_photo']))
                    <div class="w-[3.2em] h-[3.2em] rounded-full border border-white shrink-0 overflow-hidden flex items-center justify-center font-black text-white text-[0.95em]" style="background-color: {{ $accent }};">
                        @if(!empty($p['preset_avatar']))<img src="{{ asset($p['preset_avatar']) }}" alt="{{ $p['full_name'] ?? 'Profile' }}" width="64" height="64" class="w-full h-full object-cover">@else{{ $init }}@endif
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <div class="font-black text-[1.28em] text-slate-900 truncate leading-tight">{{ $p['full_name'] ?? '' }}</div>
                    <span class="inline-block px-[0.55em] py-[0.12em] rounded-full text-white text-[0.68em] font-bold mt-[0.15em] max-w-full truncate" style="background-color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-[0.3em] text-[0.68em]">
                <span class="px-[0.5em] py-[0.12em] rounded-full bg-white border border-slate-200 font-semibold">{{ $p['email'] ?? '' }}</span>
                @if(!empty($p['phone']))<span class="px-[0.5em] py-[0.12em] rounded-full bg-white border border-slate-200">{{ $p['phone'] }}</span>@endif
                <span class="px-[0.5em] py-[0.12em] rounded-full bg-white border border-slate-200">{{ $p['location'] ?? '' }}</span>
            </div>
        </div>

        <p class="text-[0.78em] text-slate-600 line-clamp-2 px-[0.15em]">{{ $c['summary'] ?? '' }}</p>

        <div class="grid grid-cols-12 gap-[0.8em] flex-1">
            <div class="col-span-8 flex flex-col justify-between gap-[0.45em]">
                <div class="space-y-[0.45em]">
                    <div class="text-[0.76em] font-black uppercase tracking-wider" style="color: {{ $accent }}">Work Experience</div>
                    @foreach($exps as $idx => $e)
                        @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                        <div class="pl-[0.55em] border-l-[0.18em]" style="border-color: {{ $accent }};">
                            <div class="flex justify-between gap-[0.35em] font-bold text-[0.85em] text-slate-900">
                                <span class="truncate">{{ $e['role'] ?? '' }}</span>
                                <span class="text-[0.72em] text-slate-400 shrink-0">{{ $e['start_date'] ?? '' }}–{{ $e['end_date'] ?? '' }}</span>
                            </div>
                            <div class="text-[0.75em] font-semibold mb-[0.1em]" style="color: {{ $accent }}">{{ $e['company'] ?? '' }}</div>
                            <ul class="space-y-[0.1em] text-[0.72em] text-slate-600">
                                @foreach($bullets as $b)
                                    <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
                @if(!empty($projs))
                    @php $pr = $projs[0]; @endphp
                    <div class="p-[0.5em] rounded-[0.5em] border" style="background: {{ $accent }}08; border-color: {{ $accent }}28;">
                        <div class="text-[0.72em] font-black uppercase tracking-wider mb-[0.1em]" style="color: {{ $accent }}">Highlighted Project</div>
                        <div class="font-bold text-[0.78em] text-slate-900 truncate">{{ $pr['name'] ?? '' }}</div>
                        <p class="text-[0.71em] text-slate-600 line-clamp-2">{{ $pr['description'] ?? '' }}</p>
                    </div>
                @endif
            </div>

            <div class="col-span-4 flex flex-col justify-between gap-[0.45em]">
                <div class="p-[0.6em] rounded-[0.55em] bg-slate-50 border border-slate-200">
                    <div class="text-[0.72em] font-black uppercase mb-[0.25em]" style="color: {{ $accent }}">Skills</div>
                    <div class="flex flex-wrap gap-[0.2em]">
                        @foreach($skills as $sk)
                            <span class="px-[0.4em] py-[0.14em] rounded-full bg-white border border-slate-200 text-[0.68em] font-bold text-slate-800">{{ $sk }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="p-[0.6em] rounded-[0.55em] bg-slate-50 border border-slate-200 space-y-[0.35em] flex-1">
                    <div>
                        <div class="text-[0.72em] font-black uppercase mb-[0.12em]" style="color: {{ $accent }}">Education</div>
                        @foreach(array_slice($edus, 0, 1) as $ed)
                            <div class="font-bold text-[0.76em] text-slate-900 leading-snug">{{ $ed['degree'] ?? '' }}</div>
                            <div class="text-[0.7em] text-slate-500">{{ $ed['school'] ?? '' }}</div>
                        @endforeach
                    </div>
                    @if(!empty($awards))
                        <div class="pt-[0.2em] border-t border-slate-200">
                            <div class="text-[0.7em] font-black uppercase mb-[0.1em]" style="color: {{ $accent }}">Honors</div>
                            @foreach($awards as $aw)
                                <div class="text-[0.7em] font-semibold text-slate-800 leading-snug">{{ $aw['title'] ?? '' }}</div>
                            @endforeach
                        </div>
                    @endif
                    @if(!empty($langs))
                        <div class="pt-[0.2em] border-t border-slate-200">
                            <div class="text-[0.68em] font-black uppercase mb-[0.08em]" style="color: {{ $accent }}">Languages</div>
                            <div class="text-[0.69em] text-slate-600">{{ implode(' • ', array_map(fn($l) => $l['name'] ?? '', $langs)) }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

{{-- D. OBSIDIAN HEADER MINIATURE (Zara Khalid, Omar Farooq) --}}
@elseif($tStyle === 'obsidian_header')
    <div class="mini-a4-sheet h-full bg-white flex flex-col justify-between text-slate-800">
        <div class="bg-[#0F172A] text-white p-[1.1em] border-t-[0.35em] flex items-center justify-between gap-[0.65em] shrink-0" style="border-top-color: {{ $accent }};">
            <div class="flex items-center gap-[0.65em] min-w-0 flex-1">
                @if(!empty($tpl['show_photo']))
                    <div class="w-[3.2em] h-[3.2em] rounded-[0.55em] border shrink-0 overflow-hidden flex items-center justify-center font-black text-[0.95em]" style="border-color: {{ $accent }}; background: {{ $accent }}30;">
                        @if(!empty($p['preset_avatar']))<img src="{{ asset($p['preset_avatar']) }}" alt="{{ $p['full_name'] ?? 'Profile' }}" width="64" height="64" class="w-full h-full object-cover">@else{{ $init }}@endif
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <div class="font-black text-[1.28em] text-white truncate leading-tight">{{ $p['full_name'] ?? '' }}</div>
                    <span class="inline-block px-[0.5em] py-[0.12em] rounded text-[0.68em] font-bold text-white mt-[0.2em] max-w-full truncate" style="background-color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</span>
                </div>
            </div>
            <div class="text-right text-[0.7em] text-slate-300 shrink-0 max-w-[40%]">
                <div class="font-bold text-white truncate">{{ $p['email'] ?? '' }}</div>
                <div class="truncate">{{ $p['location'] ?? '' }}</div>
                @if(!empty($p['website']))<div class="truncate text-slate-400">{{ $p['website'] }}</div>@endif
            </div>
        </div>

        <div class="p-[1.1em] flex-1 flex flex-col justify-between gap-[0.5em]">
            <div class="p-[0.55em] rounded bg-slate-50 border border-slate-200 text-[0.78em] text-slate-600 line-clamp-3">{{ $c['summary'] ?? '' }}</div>

            <div class="grid grid-cols-12 gap-[0.8em] flex-1">
                <div class="col-span-8 flex flex-col justify-between gap-[0.45em]">
                    <div class="space-y-[0.45em]">
                        <div class="text-[0.76em] font-black uppercase tracking-wider border-b border-slate-200 pb-[0.15em] text-slate-900">Experience</div>
                        @foreach($exps as $idx => $e)
                            @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                            <div>
                                <div class="flex justify-between gap-[0.35em] font-bold text-[0.85em] text-slate-900">
                                    <span class="truncate">{{ $e['role'] ?? '' }}</span>
                                    <span class="text-[0.72em] text-slate-400 shrink-0">{{ $e['start_date'] ?? '' }}–{{ $e['end_date'] ?? '' }}</span>
                                </div>
                                <div class="text-[0.75em] font-bold mb-[0.1em]" style="color: {{ $accent }}">{{ $e['company'] ?? '' }}</div>
                                <ul class="space-y-[0.1em] text-[0.73em] text-slate-600">
                                    @foreach($bullets as $b)
                                        <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                    @if(!empty($projs))
                        @php $pr = $projs[0]; @endphp
                        <div class="p-[0.5em] rounded bg-slate-900 text-white">
                            <div class="text-[0.7em] font-bold uppercase tracking-wider text-slate-300">Architecture &amp; OSS</div>
                            <div class="font-bold text-[0.78em] text-white truncate mt-[0.08em]">{{ $pr['name'] ?? '' }}</div>
                            <p class="text-[0.7em] text-slate-300 line-clamp-2">{{ $pr['description'] ?? '' }}</p>
                        </div>
                    @endif
                </div>

                <div class="col-span-4 flex flex-col justify-between gap-[0.45em]">
                    <div>
                        <div class="text-[0.76em] font-black uppercase tracking-wider border-b border-slate-200 pb-[0.15em] mb-[0.25em] text-slate-900">Core Stack</div>
                        <div class="flex flex-wrap gap-[0.2em]">
                            @foreach($skills as $sk)
                                <span class="px-[0.4em] py-[0.15em] rounded bg-slate-900 text-white text-[0.68em] font-semibold">{{ $sk }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-[0.35em]">
                        <div>
                            <div class="text-[0.74em] font-black uppercase text-slate-900 border-b border-slate-200 pb-[0.1em] mb-[0.15em]">Education</div>
                            @foreach(array_slice($edus, 0, 1) as $ed)
                                <div class="font-bold text-[0.76em] text-slate-900 leading-snug">{{ $ed['degree'] ?? '' }}</div>
                                <div class="text-[0.7em] text-slate-500">{{ $ed['school'] ?? '' }}</div>
                            @endforeach
                        </div>
                        @if(!empty($awards))
                            <div>
                                <div class="text-[0.74em] font-black uppercase text-slate-900 border-b border-slate-200 pb-[0.1em] mb-[0.15em]">Certifications</div>
                                @foreach($awards as $aw)
                                    <div class="text-[0.7em] font-semibold text-slate-800">{{ $aw['title'] ?? '' }}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- 1. FLOW SIDEBAR MINIATURE (Brian T. Wayne, Drew Miller, Dr. Amara Okafor) --}}
@elseif($tStyle === 'flow_sidebar')
    <div class="mini-a4-sheet grid grid-cols-12 h-full">
        {{-- Left Sidebar: Fully populated top to bottom --}}
        <div class="col-span-4 text-white p-[1.05em] flex flex-col justify-between gap-[0.45em]" style="background-color: {{ $accent }};">
            <div class="space-y-[0.55em]">
                <div>
                    <div class="font-black text-[1.18em] leading-tight text-white">{{ $p['full_name'] ?? '' }}</div>
                    <div class="text-[0.78em] text-white/85 mt-[0.12em] leading-snug">{{ $p['job_title'] ?? '' }}</div>
                    @if(!empty($tpl['show_photo']))
                        <div class="w-[4em] h-[4em] rounded-full mx-auto mt-[0.5em] border-[0.16em] border-white/35 bg-white/15 flex items-center justify-center font-black text-[1em] text-white overflow-hidden shadow-sm">
                            @if(!empty($p['preset_avatar']))
                                <img src="{{ asset($p['preset_avatar']) }}" alt="{{ $p['full_name'] ?? 'Resume Profile' }} Portrait" width="64" height="64" class="w-full h-full object-cover">
                            @else
                                {{ $init }}
                            @endif
                        </div>
                    @endif
                </div>
                <div class="space-y-[0.15em] text-[0.72em] text-white/90">
                    <div class="truncate">{{ $p['email'] ?? '' }}</div>
                    <div>{{ $p['phone'] ?? '' }}</div>
                    <div>{{ $p['location'] ?? '' }}</div>
                </div>
                <div>
                    <div class="px-[0.5em] py-[0.15em] rounded bg-white/12 font-extrabold uppercase tracking-wider text-[0.68em] text-white mb-[0.2em]">Profile</div>
                    <p class="text-[0.73em] text-white/90 line-clamp-4 leading-snug">{{ $c['summary'] ?? '' }}</p>
                </div>
                @if(!empty($langs))
                    <div class="space-y-[0.2em]">
                        <div class="px-[0.5em] py-[0.15em] rounded bg-white/12 font-extrabold uppercase tracking-wider text-[0.68em] text-white">Languages</div>
                        @foreach($langs as $lg)
                            <div class="flex items-center justify-between text-[0.72em]">
                                <span class="truncate">{{ $lg['name'] }}</span>
                                <span class="flex gap-[0.12em] shrink-0">
                                    @for($d = 1; $d <= 5; $d++)
                                        <span class="w-[0.38em] h-[0.38em] rounded-full {{ $d <= ($lg['score'] ?? 4) ? 'bg-white' : 'bg-white/25' }}"></span>
                                    @endfor
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div>
                    <div class="px-[0.5em] py-[0.15em] rounded bg-white/12 font-extrabold uppercase tracking-wider text-[0.68em] text-white mb-[0.2em]">Core Skills</div>
                    <div class="flex flex-wrap gap-[0.2em]">
                        @foreach(array_slice($skills, 0, 6) as $sk)
                            <span class="px-[0.4em] py-[0.12em] rounded bg-white/15 text-[0.68em] font-semibold text-white truncate">{{ $sk }}</span>
                        @endforeach
                    </div>
                </div>
                @if(!empty($awards))
                    <div class="space-y-[0.15em]">
                        <div class="px-[0.5em] py-[0.15em] rounded bg-white/12 font-extrabold uppercase tracking-wider text-[0.68em] text-white">Honors</div>
                        @foreach($awards as $aw)
                            <div class="text-[0.7em] text-white/90 leading-snug">&bull; {{ $aw['title'] ?? '' }}</div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="text-[0.62em] text-white/45 uppercase tracking-widest pt-[0.2em]">Curriculum Vitae</div>
        </div>

        {{-- Right Column: Fully populated with 3 jobs, education, and projects --}}
        <div class="col-span-8 p-[1.1em] flex flex-col justify-between gap-[0.45em] bg-white text-slate-800">
            <div class="space-y-[0.45em]">
                <div class="px-[0.55em] py-[0.18em] rounded bg-slate-100 font-extrabold uppercase tracking-wider text-[0.74em] text-slate-700">Work Experience</div>
                @foreach($exps as $idx => $e)
                    @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                    <div>
                        <div class="flex justify-between font-bold text-[0.85em] text-slate-900 gap-[0.35em]">
                            <span class="truncate">{{ $e['company'] ?? '' }}</span>
                            <span class="text-slate-400 font-normal text-[0.74em] shrink-0">{{ ($e['start_date'] ?? '') . ' – ' . ($e['end_date'] ?? '') }}</span>
                        </div>
                        <div class="font-semibold text-[0.77em] mb-[0.1em]" style="color: {{ $accent }}">{{ $e['role'] ?? '' }}</div>
                        <ul class="space-y-[0.1em] text-[0.72em] text-slate-600">
                            @foreach($bullets as $b)
                                <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="space-y-[0.4em] pt-[0.2em] border-t border-slate-100">
                <div>
                    <div class="px-[0.55em] py-[0.18em] rounded bg-slate-100 font-extrabold uppercase tracking-wider text-[0.74em] text-slate-700 mb-[0.2em]">Education</div>
                    @foreach($edus as $ed)
                        <div class="mb-[0.2em]">
                            <div class="font-bold text-[0.8em] text-slate-900 leading-snug">{{ $ed['degree'] ?? '' }}</div>
                            <div class="text-[0.72em] text-slate-500">{{ $ed['school'] ?? '' }} @if(!empty($ed['end_date']))&bull; {{ $ed['end_date'] }}@endif</div>
                        </div>
                    @endforeach
                </div>
                @if(!empty($projs))
                    <div>
                        <div class="px-[0.55em] py-[0.18em] rounded bg-slate-100 font-extrabold uppercase tracking-wider text-[0.74em] text-slate-700 mb-[0.15em]">Key Project</div>
                        <div class="font-bold text-[0.78em] text-slate-900">{{ $projs[0]['name'] ?? '' }}</div>
                        <div class="text-[0.71em] text-slate-600 line-clamp-2">{{ $projs[0]['description'] ?? '' }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

{{-- 2. EDITORIAL FIGMA MINIATURE (Camille Laurent, Elena Rossi) --}}
@elseif($tStyle === 'editorial_figma')
    <div class="mini-a4-sheet h-full bg-[#FAF8F4] p-[1.2em] flex flex-col justify-between gap-[0.5em] text-[#1F1C18]">
        <div class="space-y-[0.5em] flex-1">
            <div class="border-b pb-[0.5em] flex justify-between items-start gap-[0.55em]" style="border-color: rgba(31,28,24,0.18);">
                <div class="flex items-center gap-[0.55em] min-w-0">
                    @if(!empty($tpl['show_photo']))
                        <div class="w-[3.4em] h-[3.4em] rounded-full border flex items-center justify-center font-bold text-[0.95em] shrink-0 overflow-hidden" style="border-color: {{ $accent }}; color: {{ $accent }}; background: #EFECE5;">
                            @if(!empty($p['preset_avatar']))
                                <img src="{{ asset($p['preset_avatar']) }}" alt="{{ $p['full_name'] ?? 'Resume Profile' }} Portrait" width="64" height="64" class="w-full h-full object-cover">
                            @else
                                {{ $init }}
                            @endif
                        </div>
                    @endif
                    <div class="min-w-0">
                        <div class="text-[0.66em] uppercase tracking-[0.2em] font-bold" style="color: {{ $accent }}">Curriculum Vitae</div>
                        <div class="font-editorial text-[1.38em] font-bold leading-tight text-[#1A1815] truncate">{{ $p['full_name'] ?? '' }}</div>
                        <div class="font-editorial italic text-[0.84em] text-[#59534A] truncate">{{ $p['job_title'] ?? '' }}</div>
                    </div>
                </div>
                <div class="text-right text-[0.7em] text-[#59534A] space-y-[0.1em] shrink-0">
                    <div class="font-semibold text-[#1A1815]">{{ $p['email'] ?? '' }}</div>
                    <div>{{ $p['location'] ?? '' }}</div>
                    @if(!empty($p['website']))<div>{{ $p['website'] }}</div>@endif
                </div>
            </div>

            <div class="border-b pb-[0.45em] grid grid-cols-12 gap-[0.5em]" style="border-color: rgba(31,28,24,0.12);">
                <div class="col-span-3 text-[0.7em] uppercase tracking-[0.14em] font-bold" style="color: {{ $accent }}">Profile</div>
                <p class="col-span-9 text-[0.76em] text-[#3D372F] line-clamp-3">{{ $c['summary'] ?? '' }}</p>
            </div>

            <div class="grid grid-cols-12 gap-[0.75em]">
                <div class="col-span-8 space-y-[0.45em] border-r pr-[0.65em]" style="border-color: rgba(31,28,24,0.12);">
                    <div class="font-editorial font-bold text-[0.95em] border-b pb-[0.15em]" style="border-color: rgba(31,28,24,0.15);">Experience</div>
                    @foreach($exps as $idx => $e)
                        @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                        <div>
                            <div class="flex justify-between font-bold text-[0.84em] gap-[0.3em]">
                                <span class="truncate">{{ $e['role'] ?? '' }}</span>
                                <span class="font-editorial italic text-[0.72em] text-[#6B645A] shrink-0">{{ ($e['start_date'] ?? '') . '–' . ($e['end_date'] ?? '') }}</span>
                            </div>
                            <div class="font-semibold text-[0.76em] mb-[0.1em]" style="color: {{ $accent }}">{{ $e['company'] ?? '' }}</div>
                            <ul class="space-y-[0.1em] text-[0.72em] text-[#4B443B]">
                                @foreach($bullets as $b)
                                    <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                    @if(!empty($projs))
                        @php $pr = $projs[0]; @endphp
                        <div class="pt-[0.15em]">
                            <div class="font-editorial font-bold text-[0.88em] border-b pb-[0.12em] mb-[0.15em]" style="border-color: rgba(31,28,24,0.15);">Selected Work</div>
                            <div class="font-bold text-[0.76em]">{{ $pr['name'] ?? '' }}</div>
                            <p class="text-[0.7em] text-[#4B443B] line-clamp-2">{{ $pr['description'] ?? '' }}</p>
                        </div>
                    @endif
                </div>

                <div class="col-span-4 space-y-[0.55em]">
                    <div>
                        <div class="font-editorial font-bold text-[0.9em] border-b pb-[0.12em] mb-[0.2em]" style="border-color: rgba(31,28,24,0.15);">Education</div>
                        @foreach(array_slice($edus, 0, 1) as $ed)
                            <div class="text-[0.74em]">
                                <div class="font-bold leading-snug">{{ $ed['degree'] ?? '' }}</div>
                                <div class="mt-[0.08em]" style="color: {{ $accent }}">{{ $ed['school'] ?? '' }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <div class="font-editorial font-bold text-[0.9em] border-b pb-[0.12em] mb-[0.2em]" style="border-color: rgba(31,28,24,0.15);">Expertise</div>
                        <div class="flex flex-wrap gap-[0.2em]">
                            @foreach(array_slice($skills, 0, 7) as $sk)
                                <span class="px-[0.38em] py-[0.12em] border text-[0.68em] bg-white/70" style="border-color: rgba(31,28,24,0.2);">{{ $sk }}</span>
                            @endforeach
                        </div>
                    </div>
                    @if(!empty($awards))
                        <div>
                            <div class="font-editorial font-bold text-[0.88em] border-b pb-[0.12em] mb-[0.15em]" style="border-color: rgba(31,28,24,0.15);">Honors</div>
                            @foreach($awards as $aw)
                                <div class="text-[0.71em] font-semibold">{{ $aw['title'] ?? '' }}</div>
                                <div class="text-[0.66em] text-[#59534A]">{{ $aw['issuer'] ?? '' }}</div>
                            @endforeach
                        </div>
                    @endif
                    @if(!empty($langs))
                        <div>
                            <div class="font-editorial font-bold text-[0.88em] border-b pb-[0.12em] mb-[0.15em]" style="border-color: rgba(31,28,24,0.15);">Languages</div>
                            <div class="text-[0.7em] text-[#4B443B]">{{ implode(' • ', array_map(fn($l) => $l['name'] ?? '', $langs)) }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="text-[0.65em] uppercase tracking-[0.18em] text-[#8C8478] border-t pt-[0.35em] flex justify-between" style="border-color: rgba(31,28,24,0.12);">
            <span>Figma Editorial Folio</span>
            <span>{{ $p['location'] ?? '' }}</span>
        </div>
    </div>

{{-- 3. TIMELINE PRO MINIATURE (Sophia Chen-Miller, Daniel Park) --}}
@elseif($tStyle === 'timeline_pro')
    <div class="mini-a4-sheet h-full bg-white flex flex-col justify-between text-slate-800">
        <div class="px-[1.2em] py-[0.9em] border-b border-slate-200 flex items-center justify-between gap-[0.55em] shrink-0" style="background: {{ $accent }}0D;">
            <div class="flex items-center gap-[0.55em] min-w-0 flex-1">
                <div class="w-[0.3em] h-[2.6em] rounded-full shrink-0" style="background-color: {{ $accent }};"></div>
                @if(!empty($tpl['show_photo']))
                    <div class="w-[3em] h-[3em] rounded-[0.5em] shrink-0 overflow-hidden flex items-center justify-center text-white font-black text-[0.9em]" style="background-color: {{ $accent }};">
                        @if(!empty($p['preset_avatar']))<img src="{{ asset($p['preset_avatar']) }}" alt="{{ $p['full_name'] ?? 'Resume Profile' }} Portrait" width="64" height="64" class="w-full h-full object-cover">@else{{ $init }}@endif
                    </div>
                @endif
                <div class="min-w-0">
                    <div class="font-black text-[1.25em] text-slate-900 truncate leading-tight">{{ $p['full_name'] ?? '' }}</div>
                    <div class="font-bold text-[0.8em] truncate" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</div>
                </div>
            </div>
            <div class="text-right text-[0.7em] text-slate-500 shrink-0 max-w-[40%]">
                <div class="font-semibold text-slate-800 truncate">{{ $p['email'] ?? '' }}</div>
                <div class="truncate">{{ $p['location'] ?? '' }}</div>
            </div>
        </div>

        <div class="p-[1.1em] flex-1 flex flex-col justify-between gap-[0.5em]">
            <p class="text-[0.78em] text-slate-600 line-clamp-2 border-b border-slate-100 pb-[0.4em]">{{ $c['summary'] ?? '' }}</p>

            <div class="grid grid-cols-12 gap-[0.8em] flex-1">
                <div class="col-span-8 flex flex-col justify-between gap-[0.45em]">
                    <div class="space-y-[0.45em]">
                        <div class="text-[0.74em] font-extrabold uppercase tracking-wider" style="color: {{ $accent }}">Career Timeline</div>
                        <div class="relative pl-[0.9em] border-l-[0.16em] space-y-[0.55em]" style="border-color: {{ $accent }}35;">
                            @foreach($exps as $idx => $e)
                                @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                                <div class="relative">
                                    <span class="absolute -left-[1.25em] top-[0.18em] w-[0.55em] h-[0.55em] rounded-full border-[0.12em] bg-white" style="border-color: {{ $accent }};"></span>
                                    <div class="flex justify-between font-bold text-[0.84em] text-slate-900 gap-[0.3em]">
                                        <span class="truncate">{{ $e['role'] ?? '' }}</span>
                                        <span class="px-[0.35em] rounded text-[0.7em] font-bold shrink-0" style="background: {{ $accent }}16; color: {{ $accent }};">{{ $e['start_date'] ?? '' }}–{{ $e['end_date'] ?? '' }}</span>
                                    </div>
                                    <div class="text-[0.75em] font-semibold text-slate-500 mb-[0.1em]">{{ $e['company'] ?? '' }}</div>
                                    <ul class="space-y-[0.1em] text-[0.72em] text-slate-600">
                                        @foreach($bullets as $b)
                                            <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if(!empty($projs))
                        @php $pr = $projs[0]; @endphp
                        <div class="p-[0.5em] rounded bg-slate-50 border border-slate-200/80">
                            <div class="text-[0.7em] font-extrabold uppercase tracking-wider mb-[0.1em]" style="color: {{ $accent }}">Key Deliverable</div>
                            <div class="font-bold text-[0.78em] text-slate-900 truncate">{{ $pr['name'] ?? '' }}</div>
                            <p class="text-[0.7em] text-slate-600 line-clamp-2">{{ $pr['description'] ?? '' }}</p>
                        </div>
                    @endif
                </div>

                <div class="col-span-4 bg-slate-50 rounded-[0.55em] p-[0.65em] border border-slate-100 flex flex-col justify-between gap-[0.45em]">
                    <div class="space-y-[0.35em]">
                        <div class="text-[0.72em] font-extrabold uppercase tracking-wider" style="color: {{ $accent }}">Core Skills</div>
                        @foreach(array_slice($skills, 0, 5) as $idx => $sk)
                            @php $pct = 96 - (($idx * 7) % 22); @endphp
                            <div>
                                <div class="flex justify-between text-[0.7em] font-semibold text-slate-800 mb-[0.1em]">
                                    <span class="truncate">{{ $sk }}</span>
                                    <span class="text-slate-400">{{ $pct }}%</span>
                                </div>
                                <div class="h-[0.3em] w-full rounded-full bg-slate-200 overflow-hidden">
                                    <div class="h-full rounded-full" style="width: {{ $pct }}%; background-color: {{ $accent }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="space-y-[0.35em] pt-[0.3em] border-t border-slate-200">
                        <div>
                            <div class="text-[0.72em] font-extrabold uppercase tracking-wider mb-[0.1em]" style="color: {{ $accent }}">Education</div>
                            @foreach(array_slice($edus, 0, 1) as $ed)
                                <div class="font-bold text-[0.75em] text-slate-800 leading-snug">{{ $ed['degree'] ?? '' }}</div>
                                <div class="text-[0.69em] text-slate-500">{{ $ed['school'] ?? '' }}</div>
                            @endforeach
                        </div>
                        @if(!empty($awards))
                            <div>
                                <div class="text-[0.7em] font-extrabold uppercase tracking-wider mb-[0.1em]" style="color: {{ $accent }}">Honors</div>
                                @foreach(array_slice($awards, 0, 1) as $aw)
                                    <div class="text-[0.7em] font-semibold text-slate-700 leading-snug">{{ $aw['title'] ?? '' }}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- 4. LUXE SPLIT MINIATURE (Henrik Lindqvist) --}}
@elseif($tStyle === 'luxe_split')
    <div class="mini-a4-sheet h-full bg-white p-[1.25em] flex flex-col justify-between gap-[0.55em] text-slate-800">
        <div class="flex items-center justify-between gap-[0.6em] pb-[0.6em] border-b border-slate-200 shrink-0">
            <div class="flex items-center gap-[0.6em] min-w-0">
                <div class="w-[2.9em] h-[2.9em] rounded-[0.5em] border-[0.14em] flex items-center justify-center font-black text-[1em] shrink-0" style="border-color: {{ $accent }}; color: {{ $accent }};">{{ $init }}</div>
                <div class="min-w-0">
                    <div class="font-black text-[1.3em] text-slate-900 truncate leading-tight">{{ $p['full_name'] ?? '' }}</div>
                    <div class="text-[0.82em] font-semibold text-slate-500 truncate">{{ $p['job_title'] ?? '' }}</div>
                </div>
            </div>
            <div class="text-right text-[0.7em] text-slate-500 shrink-0 max-w-[40%]">
                <div class="font-bold text-slate-800 truncate">{{ $p['email'] ?? '' }}</div>
                <div class="truncate">{{ $p['location'] ?? '' }}</div>
            </div>
        </div>

        <div class="p-[0.55em] rounded bg-slate-50 border-l-[0.2em] text-[0.78em] text-slate-600 line-clamp-3 shrink-0" style="border-left-color: {{ $accent }};">
            {{ $c['summary'] ?? '' }}
        </div>

        <div class="grid grid-cols-12 gap-[0.85em] flex-1">
            <div class="col-span-7 flex flex-col justify-between gap-[0.45em]">
                <div class="space-y-[0.45em]">
                    <div class="text-[0.74em] font-extrabold uppercase tracking-[0.14em] text-slate-400">Experience</div>
                    @foreach($exps as $idx => $e)
                        @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                        <div>
                            <div class="font-bold text-[0.85em] text-slate-900">{{ $e['role'] ?? '' }}</div>
                            <div class="text-[0.76em] font-semibold mb-[0.1em]" style="color: {{ $accent }}">{{ $e['company'] ?? '' }} &bull; {{ $e['start_date'] ?? '' }}–{{ $e['end_date'] ?? '' }}</div>
                            <ul class="space-y-[0.1em] text-[0.72em] text-slate-600">
                                @foreach($bullets as $b)
                                    <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
                @if(!empty($projs))
                    @php $pr = $projs[0]; @endphp
                    <div class="pt-[0.25em] border-t border-slate-100">
                        <div class="text-[0.7em] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-[0.1em]">Projects</div>
                        <div class="font-bold text-[0.78em] text-slate-900 truncate">{{ $pr['name'] ?? '' }}</div>
                        <p class="text-[0.71em] text-slate-600 line-clamp-2">{{ $pr['description'] ?? '' }}</p>
                    </div>
                @endif
            </div>

            <div class="col-span-5 flex flex-col justify-between gap-[0.45em]">
                <div>
                    <div class="text-[0.74em] font-extrabold uppercase tracking-[0.14em] text-slate-400 mb-[0.2em]">Education</div>
                    @foreach(array_slice($edus, 0, 1) as $ed)
                        <div class="p-[0.5em] rounded bg-slate-50 border border-slate-100 text-[0.74em]">
                            <div class="font-bold text-slate-900 leading-snug">{{ $ed['degree'] ?? '' }}</div>
                            <div class="mt-[0.08em]" style="color: {{ $accent }}">{{ $ed['school'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
                <div>
                    <div class="text-[0.74em] font-extrabold uppercase tracking-[0.14em] text-slate-400 mb-[0.2em]">Skills</div>
                    <div class="flex flex-wrap gap-[0.2em]">
                        @foreach($skills as $sk)
                            <span class="px-[0.45em] py-[0.15em] rounded-full border border-slate-200 text-[0.68em] font-semibold text-slate-700">{{ $sk }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="space-y-[0.3em]">
                    @if(!empty($awards))
                        <div>
                            <div class="text-[0.7em] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-[0.1em]">Honors</div>
                            @foreach(array_slice($awards, 0, 1) as $aw)
                                <div class="text-[0.71em] font-semibold text-slate-800">{{ $aw['title'] ?? '' }}</div>
                            @endforeach
                        </div>
                    @endif
                    @if(!empty($langs))
                        <div>
                            <div class="text-[0.7em] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-[0.1em]">Languages</div>
                            <div class="text-[0.7em] text-slate-600">{{ implode(' • ', array_map(fn($l) => $l['name'] ?? '', $langs)) }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

{{-- 5. EXECUTIVE BOLD MINIATURE (Marcus Vance, David Vance) --}}
@elseif($tStyle === 'executive_bold')
    <div class="mini-a4-sheet h-full bg-white flex flex-col justify-between p-[1.05em] gap-[0.55em] text-slate-800">
        <div class="rounded-[0.65em] text-white px-[1.1em] py-[0.85em] space-y-[0.35em] shrink-0" style="background-color: {{ $accent }};">
            <div class="flex justify-between items-center gap-[0.55em]">
                <div class="min-w-0">
                    <div class="font-black text-[1.32em] tracking-tight truncate">{{ $p['full_name'] ?? '' }}</div>
                    <div class="text-[0.82em] text-white/85 truncate">{{ $p['job_title'] ?? '' }}</div>
                </div>
                @if(!empty($tpl['show_photo']))
                    <div class="w-[3em] h-[3em] rounded-full border border-white/40 overflow-hidden shrink-0">
                        @if(!empty($p['preset_avatar']))<img src="{{ asset($p['preset_avatar']) }}" alt="{{ $p['full_name'] ?? 'Profile' }}" width="64" height="64" class="w-full h-full object-cover">@endif
                    </div>
                @endif
            </div>
            <div class="flex flex-wrap gap-[0.4em] px-[0.55em] py-[0.18em] rounded bg-white/12 text-[0.7em] text-white/95">
                <span class="truncate">{{ $p['email'] ?? '' }}</span>
                <span>&bull;</span>
                <span>{{ $p['phone'] ?? '' }}</span>
                <span>&bull;</span>
                <span>{{ $p['location'] ?? '' }}</span>
            </div>
        </div>

        <div class="border-b border-slate-200 pb-[0.45em] text-[0.78em] text-slate-700 line-clamp-3 shrink-0">{{ $c['summary'] ?? '' }}</div>

        <div class="grid grid-cols-12 gap-[0.85em] flex-1">
            <div class="col-span-8 flex flex-col justify-between gap-[0.45em]">
                <div class="space-y-[0.45em]">
                    <div class="text-[0.74em] font-black uppercase tracking-wider border-b pb-[0.15em]" style="color: {{ $accent }}; border-color: {{ $accent }};">Leadership History</div>
                    @foreach($exps as $idx => $e)
                        @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                        <div>
                            <div class="flex justify-between gap-[0.3em] font-bold text-[0.85em] text-slate-900">
                                <span class="truncate">{{ $e['role'] ?? '' }}</span>
                                <span class="text-[0.72em] text-slate-400 shrink-0">{{ $e['start_date'] ?? '' }}–{{ $e['end_date'] ?? '' }}</span>
                            </div>
                            <div class="text-[0.76em] font-bold mb-[0.1em]" style="color: {{ $accent }}">{{ $e['company'] ?? '' }}</div>
                            <ul class="space-y-[0.1em] text-[0.72em] text-slate-600">
                                @foreach($bullets as $b)
                                    <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
                @if(!empty($projs))
                    @php $pr = $projs[0]; @endphp
                    <div class="p-[0.5em] rounded bg-slate-50 border border-slate-200/80">
                        <div class="text-[0.7em] font-black uppercase tracking-wider" style="color: {{ $accent }}">Executive Mandate</div>
                        <div class="font-bold text-[0.78em] text-slate-900 truncate">{{ $pr['name'] ?? '' }}</div>
                        <p class="text-[0.7em] text-slate-600 line-clamp-2">{{ $pr['description'] ?? '' }}</p>
                    </div>
                @endif
            </div>

            <div class="col-span-4 flex flex-col justify-between gap-[0.45em]">
                <div class="p-[0.6em] rounded bg-slate-50 border border-slate-200 space-y-[0.25em]">
                    <div class="text-[0.72em] font-black uppercase tracking-wider" style="color: {{ $accent }}">Competencies</div>
                    @foreach(array_slice($skills, 0, 6) as $sk)
                        <div class="text-[0.71em] font-semibold text-slate-700 flex items-center gap-[0.3em]">
                            <span class="w-[0.35em] h-[0.35em] shrink-0" style="background: {{ $accent }};"></span>
                            <span class="truncate">{{ $sk }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="p-[0.6em] rounded bg-slate-50 border border-slate-200 space-y-[0.35em] flex-1">
                    <div>
                        <div class="text-[0.72em] font-black uppercase tracking-wider mb-[0.12em]" style="color: {{ $accent }}">Education</div>
                        @foreach(array_slice($edus, 0, 1) as $ed)
                            <div class="font-bold text-[0.75em] text-slate-900 leading-snug">{{ $ed['degree'] ?? '' }}</div>
                            <div class="text-[0.69em] text-slate-500">{{ $ed['school'] ?? '' }}</div>
                        @endforeach
                    </div>
                    @if(!empty($awards))
                        <div class="pt-[0.18em] border-t border-slate-200">
                            <div class="text-[0.7em] font-black uppercase tracking-wider mb-[0.08em]" style="color: {{ $accent }}">Board Honors</div>
                            @foreach(array_slice($awards, 0, 1) as $aw)
                                <div class="text-[0.7em] font-semibold text-slate-700">{{ $aw['title'] ?? '' }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

{{-- 6. CREATIVE CARD MINIATURE (Maya Lin-Vance, Nadia Al-Mansoor) --}}
@elseif($tStyle === 'creative_card')
    <div class="mini-a4-sheet grid grid-cols-12 h-full text-slate-800">
        <div class="col-span-4 p-[0.9em] flex flex-col justify-between gap-[0.55em] border-r" style="background: {{ $accent }}14; border-color: {{ $accent }}28;">
            <div class="space-y-[0.55em]">
                <div class="p-[0.65em] rounded-[0.55em] bg-white border shadow-sm" style="border-color: {{ $accent }}24;">
                    <div class="w-[3.4em] h-[3.4em] rounded-[0.5em] flex items-center justify-center text-white font-black text-[0.95em] mb-[0.35em] overflow-hidden" style="background: {{ $accent }};">
                        @if(!empty($p['preset_avatar']))<img src="{{ asset($p['preset_avatar']) }}" alt="{{ $p['full_name'] ?? 'Resume Profile' }} Portrait" width="64" height="64" class="w-full h-full object-cover">@else{{ $init }}@endif
                    </div>
                    <div class="font-black text-[1.05em] text-slate-900 leading-tight">{{ $p['full_name'] ?? '' }}</div>
                    <div class="text-[0.74em] font-bold mt-[0.12em]" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</div>
                </div>
                <div class="space-y-[0.15em] text-[0.71em] text-slate-700 px-[0.15em]">
                    <div class="font-bold text-slate-900 truncate">{{ $p['email'] ?? '' }}</div>
                    <div>{{ $p['location'] ?? '' }}</div>
                    @if(!empty($p['website']))<div class="underline truncate" style="color: {{ $accent }}">{{ $p['website'] }}</div>@endif
                </div>
                <div class="pt-[0.35em] border-t space-y-[0.25em]" style="border-color: {{ $accent }}20;">
                    <div class="text-[0.7em] font-extrabold uppercase tracking-wider" style="color: {{ $accent }}">Toolbox</div>
                    <div class="flex flex-wrap gap-[0.2em]">
                        @foreach(array_slice($skills, 0, 7) as $sk)
                            <span class="px-[0.4em] py-[0.12em] rounded-full bg-white text-[0.67em] font-bold border" style="border-color: {{ $accent }}35; color: {{ $accent }};">{{ $sk }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @if(!empty($langs))
                <div class="pt-[0.3em] border-t text-[0.69em]" style="border-color: {{ $accent }}20;">
                    <div class="font-extrabold uppercase tracking-wider mb-[0.1em]" style="color: {{ $accent }}">Languages</div>
                    <div class="text-slate-600">{{ implode(' • ', array_map(fn($l) => $l['name'] ?? '', $langs)) }}</div>
                </div>
            @endif
        </div>

        <div class="col-span-8 p-[1.1em] flex flex-col justify-between gap-[0.45em] bg-white">
            <div class="space-y-[0.45em]">
                <div>
                    <div class="text-[0.74em] font-extrabold uppercase tracking-wider mb-[0.15em]" style="color: {{ $accent }}">About</div>
                    <p class="text-[0.76em] text-slate-600 line-clamp-3">{{ $c['summary'] ?? '' }}</p>
                </div>
                <div class="text-[0.74em] font-extrabold uppercase tracking-wider border-b border-slate-200 pb-[0.15em]" style="color: {{ $accent }}">Experience</div>
                @foreach($exps as $idx => $e)
                    @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                    <div>
                        <div class="flex justify-between gap-[0.3em] font-bold text-[0.85em] text-slate-900">
                            <span class="truncate">{{ $e['role'] ?? '' }}</span>
                            <span class="text-[0.72em] text-slate-400 shrink-0">{{ $e['start_date'] ?? '' }}–{{ $e['end_date'] ?? '' }}</span>
                        </div>
                        <div class="text-[0.76em] font-semibold mb-[0.1em]" style="color: {{ $accent }}">{{ $e['company'] ?? '' }}</div>
                        <ul class="space-y-[0.1em] text-[0.72em] text-slate-600">
                            @foreach($bullets as $b)
                                <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="space-y-[0.35em] pt-[0.25em] border-t border-slate-200">
                <div>
                    <div class="text-[0.72em] font-extrabold uppercase tracking-wider mb-[0.12em]" style="color: {{ $accent }}">Education</div>
                    @foreach(array_slice($edus, 0, 1) as $ed)
                        <div class="font-bold text-[0.78em] text-slate-900">{{ $ed['degree'] ?? '' }}</div>
                        <div class="text-[0.71em] text-slate-500">{{ $ed['school'] ?? '' }}</div>
                    @endforeach
                </div>
                @if(!empty($projs))
                    @php $pr = $projs[0]; @endphp
                    <div class="p-[0.45em] rounded bg-slate-50 border border-slate-200/80">
                        <div class="text-[0.7em] font-extrabold uppercase tracking-wider" style="color: {{ $accent }}">Featured Case</div>
                        <div class="font-bold text-[0.76em] text-slate-900 truncate">{{ $pr['name'] ?? '' }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

{{-- 7. TECH STACK MINIATURE (Arjun Mehta, Kenji Takahashi, Alex Rivera) --}}
@elseif($tStyle === 'tech_stack')
    <div class="mini-a4-sheet h-full bg-white border-t-[0.4em] p-[1.15em] flex flex-col justify-between gap-[0.45em] text-slate-800" style="border-top-color: {{ $accent }};">
        <div class="flex justify-between items-start pb-[0.45em] border-b border-slate-200 gap-[0.45em] shrink-0">
            <div class="min-w-0">
                <div class="inline-block px-[0.45em] py-[0.12em] rounded font-tech-mono text-[0.68em] font-bold mb-[0.15em]" style="background: {{ $accent }}14; color: {{ $accent }};">$ whoami --role</div>
                <div class="font-black text-[1.32em] text-slate-900 truncate leading-tight">{{ $p['full_name'] ?? '' }}</div>
                <div class="font-tech-mono text-[0.78em] font-semibold truncate" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</div>
            </div>
            <div class="text-right font-tech-mono text-[0.7em] text-slate-600 shrink-0 max-w-[42%]">
                @if(!empty($p['website']))<div class="truncate">{{ $p['website'] }}</div>@endif
                <div class="truncate font-semibold text-slate-800">{{ $p['email'] ?? '' }}</div>
                <div>{{ $p['location'] ?? '' }}</div>
            </div>
        </div>

        <p class="text-[0.76em] text-slate-600 line-clamp-2 shrink-0">{{ $c['summary'] ?? '' }}</p>

        <div class="p-[0.55em] rounded bg-slate-50 border border-slate-200 shrink-0">
            <div class="font-tech-mono text-[0.72em] font-bold mb-[0.2em]" style="color: {{ $accent }}">// TECH_STACK</div>
            <div class="flex flex-wrap gap-[0.2em]">
                @foreach($skills as $sk)
                    <span class="px-[0.4em] py-[0.12em] rounded bg-white border border-slate-300 font-tech-mono text-[0.68em] font-semibold text-slate-800">{{ $sk }}</span>
                @endforeach
            </div>
        </div>

        <div class="space-y-[0.4em] flex-1">
            <div class="font-tech-mono text-[0.72em] font-bold" style="color: {{ $accent }}">// EXPERIENCE</div>
            @foreach($exps as $idx => $e)
                @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                <div>
                    <div class="flex justify-between gap-[0.35em] font-bold text-[0.84em] text-slate-900">
                        <span class="truncate">{{ $e['role'] ?? '' }} <span style="color: {{ $accent }}">@ {{ $e['company'] ?? '' }}</span></span>
                        <span class="font-tech-mono text-[0.72em] text-slate-400 shrink-0">{{ $e['start_date'] ?? '' }}–{{ $e['end_date'] ?? '' }}</span>
                    </div>
                    <ul class="space-y-[0.1em] text-[0.72em] text-slate-600 mt-[0.08em]">
                        @foreach($bullets as $b)
                            <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-12 gap-[0.55em] pt-[0.25em] border-t border-slate-200">
            @if(!empty($projs))
                <div class="col-span-7 space-y-[0.2em]">
                    <div class="font-tech-mono text-[0.69em] font-bold" style="color: {{ $accent }}">// OPEN_SOURCE_PROJECTS</div>
                    @foreach(array_slice($projs, 0, 1) as $pr)
                        <div class="p-[0.45em] rounded border border-slate-200 bg-slate-50/70">
                            <div class="font-tech-mono font-bold text-[0.73em] text-slate-900 truncate">{{ $pr['name'] ?? '' }}</div>
                            <div class="text-[0.68em] text-slate-500 line-clamp-2">{{ $pr['description'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="{{ !empty($projs) ? 'col-span-5' : 'col-span-12' }} space-y-[0.2em]">
                <div class="font-tech-mono text-[0.69em] font-bold" style="color: {{ $accent }}">// EDUCATION</div>
                @foreach(array_slice($edus, 0, 1) as $ed)
                    <div class="font-bold text-[0.74em] text-slate-900 leading-snug">{{ $ed['degree'] ?? '' }}</div>
                    <div class="text-[0.68em] text-slate-500">{{ $ed['school'] ?? '' }}</div>
                @endforeach
            </div>
        </div>
    </div>

{{-- 8. MINIMAL SWISS MINIATURE (Lukas Weber) --}}
@elseif($tStyle === 'minimal_swiss')
    <div class="mini-a4-sheet h-full bg-white p-[1.25em] flex flex-col justify-between gap-[0.45em] text-slate-800">
        <div class="border-t-[0.3em] pt-[0.5em] pb-[0.45em] border-b border-slate-200 flex justify-between items-end shrink-0" style="border-top-color: {{ $accent }};">
            <div class="min-w-0">
                <div class="font-black text-[1.36em] text-slate-900 truncate leading-tight">{{ $p['full_name'] ?? '' }}</div>
                <div class="text-[0.82em] font-semibold truncate" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</div>
            </div>
            <div class="text-right text-[0.71em] text-slate-500 shrink-0">
                <div class="font-semibold text-slate-800">{{ $p['email'] ?? '' }}</div>
                <div>{{ $p['location'] ?? '' }}</div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-[0.6em] border-b border-slate-100 pb-[0.4em]">
            <div class="col-span-3 text-[0.72em] font-extrabold uppercase tracking-wider" style="color: {{ $accent }}">Profile</div>
            <p class="col-span-9 text-[0.77em] text-slate-700 line-clamp-3">{{ $c['summary'] ?? '' }}</p>
        </div>

        <div class="grid grid-cols-12 gap-[0.6em] border-b border-slate-100 pb-[0.4em] flex-1">
            <div class="col-span-3 text-[0.72em] font-extrabold uppercase tracking-wider" style="color: {{ $accent }}">Experience</div>
            <div class="col-span-9 space-y-[0.45em]">
                @foreach($exps as $idx => $e)
                    @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                    <div>
                        <div class="flex justify-between gap-[0.25em] font-bold text-[0.82em] text-slate-900">
                            <span class="truncate">{{ $e['role'] ?? '' }} &mdash; {{ $e['company'] ?? '' }}</span>
                            <span class="text-[0.72em] text-slate-400 shrink-0">{{ $e['start_date'] ?? '' }}–{{ $e['end_date'] ?? '' }}</span>
                        </div>
                        <ul class="space-y-[0.1em] text-[0.72em] text-slate-600 mt-[0.08em]">
                            @foreach($bullets as $b)
                                <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        @if(!empty($projs))
            <div class="grid grid-cols-12 gap-[0.6em] border-b border-slate-100 pb-[0.35em]">
                <div class="col-span-3 text-[0.72em] font-extrabold uppercase tracking-wider" style="color: {{ $accent }}">Projects</div>
                <div class="col-span-9 text-[0.73em]">
                    <div class="font-bold text-slate-900">{{ $projs[0]['name'] ?? '' }}</div>
                    <p class="text-slate-600 line-clamp-2">{{ $projs[0]['description'] ?? '' }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-12 gap-[0.6em] border-b border-slate-100 pb-[0.35em]">
            <div class="col-span-3 text-[0.72em] font-extrabold uppercase tracking-wider" style="color: {{ $accent }}">Education</div>
            <div class="col-span-9 text-[0.74em]">
                @foreach(array_slice($edus, 0, 1) as $ed)
                    <div class="font-bold text-slate-900">{{ $ed['degree'] ?? '' }}</div>
                    <div class="text-slate-500">{{ $ed['school'] ?? '' }} &bull; {{ $ed['end_date'] ?? '' }}</div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-12 gap-[0.6em]">
            <div class="col-span-3 text-[0.72em] font-extrabold uppercase tracking-wider" style="color: {{ $accent }}">Skills</div>
            <div class="col-span-9 text-[0.73em] text-slate-700">{{ implode(' • ', $skills) }}</div>
        </div>
    </div>

{{-- 9. ATS CLASSIC MINIATURE (Jonathan Sterling) --}}
@elseif($tStyle === 'ats_classic')
    <div class="mini-a4-sheet h-full bg-white p-[1.25em] flex flex-col justify-between gap-[0.45em] text-slate-900">
        <div class="text-center border-b-[0.18em] border-slate-900 pb-[0.45em] shrink-0">
            <div class="font-editorial font-bold text-[1.38em] uppercase tracking-tight">{{ $p['full_name'] ?? '' }}</div>
            <div class="text-[0.82em] font-semibold text-slate-700">{{ $p['job_title'] ?? '' }}</div>
            <div class="text-[0.72em] text-slate-500 mt-[0.12em]">{{ implode(' • ', array_filter([$p['location'] ?? '', $p['phone'] ?? '', $p['email'] ?? ''])) }}</div>
        </div>

        <div>
            <div class="font-bold uppercase tracking-wider text-[0.76em] border-b border-slate-400 pb-[0.12em] mb-[0.2em]">Executive Summary</div>
            <p class="text-[0.75em] text-slate-700 line-clamp-3">{{ $c['summary'] ?? '' }}</p>
        </div>

        <div class="flex-1 space-y-[0.45em]">
            <div class="font-bold uppercase tracking-wider text-[0.76em] border-b border-slate-400 pb-[0.12em]">Professional Experience</div>
            @foreach($exps as $idx => $e)
                @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                <div>
                    <div class="flex justify-between font-bold text-[0.82em]">
                        <span>{{ $e['company'] ?? '' }}</span>
                        <span class="text-slate-600 font-normal">{{ $e['location'] ?? '' }}</span>
                    </div>
                    <div class="flex justify-between italic text-[0.76em] text-slate-700 mb-[0.1em]">
                        <span>{{ $e['role'] ?? '' }}</span>
                        <span>{{ ($e['start_date'] ?? '') . ' – ' . ($e['end_date'] ?? '') }}</span>
                    </div>
                    <ul class="space-y-[0.08em] text-[0.72em] text-slate-700">
                        @foreach($bullets as $b)
                            <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="space-y-[0.35em] pt-[0.2em] border-t border-slate-200">
            <div>
                <div class="font-bold uppercase tracking-wider text-[0.76em] border-b border-slate-400 pb-[0.12em] mb-[0.15em]">Education</div>
                @foreach(array_slice($edus, 0, 1) as $ed)
                    <div class="flex justify-between text-[0.76em]">
                        <span><strong class="font-bold">{{ $ed['school'] ?? '' }}</strong> &mdash; {{ $ed['degree'] ?? '' }}</span>
                        <span class="text-slate-600 shrink-0">{{ $ed['end_date'] ?? '' }}</span>
                    </div>
                @endforeach
            </div>
            <div>
                <div class="font-bold uppercase tracking-wider text-[0.76em] border-b border-slate-400 pb-[0.12em] mb-[0.15em]">Core Skills &amp; Credentials</div>
                <p class="text-[0.72em] text-slate-700">{{ implode(', ', $skills) }}</p>
                @if(!empty($awards))
                    <p class="text-[0.71em] text-slate-600 mt-[0.1em]"><strong>Certifications:</strong> {{ implode(' • ', array_map(fn($a) => ($a['title'] ?? '') . ' (' . ($a['date'] ?? '') . ')', $awards)) }}</p>
                @endif
            </div>
        </div>
    </div>

{{-- 10. COMPACT ONEPAGE MINIATURE (Priya Nair) --}}
@else
    <div class="mini-a4-sheet h-full bg-white p-[1.15em] flex flex-col justify-between gap-[0.45em] text-slate-800">
        <div class="flex justify-between items-end border-b-[0.2em] pb-[0.4em] shrink-0" style="border-color: {{ $accent }};">
            <div class="min-w-0">
                <div class="font-black text-[1.32em] text-slate-900 truncate leading-tight">{{ $p['full_name'] ?? '' }}</div>
                <div class="text-[0.8em] font-bold truncate" style="color: {{ $accent }}">{{ $p['job_title'] ?? '' }}</div>
            </div>
            <span class="text-[0.68em] uppercase font-bold text-slate-400 shrink-0">Executive Summary</span>
        </div>

        <div class="grid grid-cols-3 gap-[0.35em] px-[0.55em] py-[0.28em] rounded bg-slate-50 border border-slate-200 text-[0.7em] text-slate-600 shrink-0">
            <span class="truncate">{{ $p['email'] ?? '' }}</span>
            <span class="truncate text-center">{{ $p['phone'] ?? '' }}</span>
            <span class="truncate text-right">{{ $p['location'] ?? '' }}</span>
        </div>

        <div class="p-[0.5em] rounded border text-[0.75em] text-slate-700 line-clamp-3 shrink-0" style="border-color: {{ $accent }}30; background: {{ $accent }}08;">
            {{ $c['summary'] ?? '' }}
        </div>

        <div class="space-y-[0.4em] flex-1">
            <div class="text-[0.74em] font-extrabold uppercase tracking-wider" style="color: {{ $accent }}">Experience</div>
            @foreach($exps as $idx => $e)
                @php $bullets = $parseBullets($e['bullets_text'] ?? '', $idx === 0 ? 3 : 2); @endphp
                <div>
                    <div class="flex justify-between gap-[0.3em] font-bold text-[0.84em] text-slate-900">
                        <span class="truncate">{{ $e['role'] ?? '' }} <span style="color: {{ $accent }}">&mdash; {{ $e['company'] ?? '' }}</span></span>
                        <span class="text-[0.71em] text-slate-400 shrink-0">{{ $e['start_date'] ?? '' }}–{{ $e['end_date'] ?? '' }}</span>
                    </div>
                    <ul class="space-y-[0.1em] text-[0.72em] text-slate-600 mt-[0.08em]">
                        @foreach($bullets as $b)
                            <li class="flex items-start gap-[0.25em]"><span class="shrink-0">&bull;</span><span class="line-clamp-2">{{ $b }}</span></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-12 gap-[0.6em] pt-[0.3em] border-t border-slate-200">
            <div class="col-span-7 space-y-[0.25em]">
                <div class="text-[0.7em] font-extrabold uppercase tracking-wider" style="color: {{ $accent }}">Core Competencies</div>
                <div class="flex flex-wrap gap-[0.2em]">
                    @foreach($skills as $sk)
                        <span class="px-[0.4em] py-[0.12em] rounded bg-slate-100 text-[0.68em] font-semibold">{{ $sk }}</span>
                    @endforeach
                </div>
            </div>
            <div class="col-span-5 space-y-[0.25em] text-[0.73em]">
                <div>
                    <div class="text-[0.7em] font-extrabold uppercase tracking-wider mb-[0.08em]" style="color: {{ $accent }}">Education</div>
                    @foreach(array_slice($edus, 0, 1) as $ed)
                        <div class="font-bold leading-snug">{{ $ed['degree'] ?? '' }}</div>
                        <div class="text-slate-500 text-[0.9em]">{{ $ed['school'] ?? '' }}</div>
                    @endforeach
                </div>
                @if(!empty($awards))
                    <div>
                        <div class="text-[0.68em] font-extrabold uppercase tracking-wider" style="color: {{ $accent }}">Certifications</div>
                        <div class="text-[0.9em] text-slate-700">{{ $awards[0]['title'] ?? '' }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
</div>
