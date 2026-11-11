<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>{{ $resume->title ?: ($resume->content['personal']['full_name'] ?? 'Resume') }}</title>
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
    $accent = preg_match('/^#[0-9A-Fa-f]{6}$/', (string) ($resume->accent_color ?? '')) ? $resume->accent_color : '#1B3142';
    $font = $resume->font_family ?: 'sans';
    $density = $c['density'] ?? 'comfortable';
    $presetRel = ltrim((string) ($p['preset_avatar'] ?? ''), '/');
    $presetPath = ($presetRel && file_exists(public_path($presetRel))) ? public_path($presetRel) : '';
    $photo = ($resume->photo_data ?: '') ?: $presetPath;

    // Compute solid 6-digit hex pastel mix for DomPDF compatibility (DomPDF ignores 8-digit hex alpha)
    $tintHex = static function (string $hex, float $whiteMix = 0.90): string {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6) return '#F1F5F9';
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $nr = (int) round($r * (1 - $whiteMix) + 255 * $whiteMix);
        $ng = (int) round($g * (1 - $whiteMix) + 255 * $whiteMix);
        $nb = (int) round($b * (1 - $whiteMix) + 255 * $whiteMix);
        return sprintf('#%02X%02X%02X', $nr, $ng, $nb);
    };

    $accentLight = $tintHex($accent, 0.91);
    $accentMid   = $tintHex($accent, 0.80);

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

    $fontStack = match ($font) {
        'serif' => "'DejaVu Serif', Georgia, serif",
        'mono'  => "'DejaVu Sans Mono', monospace",
        default => "'DejaVu Sans', Helvetica, Arial, sans-serif",
    };
    $bodySize = $density === 'compact' ? '9.2pt' : '9.8pt';
    $lineHeight = $density === 'compact' ? '1.38' : '1.46';
    $pageBg = $tStyle === 'editorial_figma' ? '#FAF8F4' : '#FFFFFF';
@endphp
<style>
    @page {
        size: A4 portrait;
        margin: 0;
    }
    * {
        box-sizing: border-box;
    }
    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        background-color: {{ $pageBg }};
        color: {{ $tStyle === 'editorial_figma' ? '#1F1C18' : '#1E293B' }};
        font-family: {!! $fontStack !!};
        font-size: {{ $bodySize }};
        line-height: {{ $lineHeight }};
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    ul {
        margin: 3px 0 0 0;
        padding-left: 14px;
    }
    li {
        margin-bottom: 2px;
    }
    .serif {
        font-family: 'DejaVu Serif', Georgia, serif;
    }
    .mono {
        font-family: 'DejaVu Sans Mono', monospace;
    }
    .muted {
        color: #64748B;
    }
</style>
</head>
<body>

{{-- =======================================================================
     1. FLOW SIDEBAR
     ======================================================================= --}}
@if($tStyle === 'flow_sidebar')
    <table style="width: 100%; height: 296mm; border-collapse: collapse;">
        <tr>
            <td valign="top" style="width: 34%; background-color: {{ $accent }}; color: #FFFFFF; padding: 26px 20px;">
                <div class="{{ $font === 'serif' ? 'serif' : '' }}" style="font-size: 16pt; font-weight: bold; line-height: 1.15; color: #FFFFFF;">
                    {{ $fullName }}
                </div>
                @if(!empty($p['job_title']))
                    <div style="font-size: 9.5pt; color: #CBD5E1; margin-top: 4px;">{{ $p['job_title'] }}</div>
                @endif

                @if($showPhoto)
                    <div style="text-align: center; margin: 14px 0;">
                        @if($photo)
                            <img src="{{ $photo }}" style="width: 82px; height: 82px; border-radius: 41px; border: 3px solid rgba(255,255,255,0.3); object-fit: cover;">
                        @else
                            <div style="width: 78px; height: 78px; margin: 0 auto; border-radius: 39px; border: 2px solid #94A3B8; background-color: rgba(255,255,255,0.10); line-height: 74px; text-align: center; font-size: 20pt; font-weight: bold; color: #FFFFFF;">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>
                @endif

                <div style="margin-top: 18px; padding-top: 12px; border-top: 1px solid #475569; font-size: 8.6pt; color: #E2E8F0;">
                    <div style="font-size: 7.5pt; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; color: #94A3B8; margin-bottom: 6px;">Contact</div>
                    @if(!empty($p['email']))<div style="margin-bottom: 3px;">{{ $p['email'] }}</div>@endif
                    @if(!empty($p['phone']))<div style="margin-bottom: 3px;">{{ $p['phone'] }}</div>@endif
                    @if(!empty($p['location']))<div style="margin-bottom: 3px;">{{ $p['location'] }}</div>@endif
                    @if(!empty($p['linkedin']))<div style="margin-bottom: 3px;">{{ $p['linkedin'] }}</div>@endif
                    @if(!empty($p['website']))<div style="margin-bottom: 3px;">{{ $p['website'] }}</div>@endif
                </div>

                @if(!empty($vis['summary']) && !empty($c['summary']))
                    <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #475569;">
                        <div style="font-size: 7.5pt; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; color: #94A3B8; margin-bottom: 6px;">&#9632; Profile</div>
                        <div style="font-size: 8.7pt; color: #E2E8F0;">{{ $c['summary'] }}</div>
                    </div>
                @endif

                @if(!empty($vis['languages']) && !empty($langs))
                    <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #475569;">
                        <div style="font-size: 7.5pt; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; color: #94A3B8; margin-bottom: 8px;">&#9632; Languages</div>
                        <table>
                            @foreach($langs as $lang)
                                @continue(empty($lang['name']))
                                <tr>
                                    <td style="padding-bottom: 5px; font-size: 8.8pt; color: #FFFFFF;">
                                        <strong>{{ $lang['name'] }}</strong>
                                        <div style="font-size: 7.5pt; color: #CBD5E1;">{{ $lang['level'] ?? '' }}</div>
                                    </td>
                                    <td align="right" valign="middle" style="padding-bottom: 5px; white-space: nowrap;">
                                        @for($d = 1; $d <= 5; $d++)
                                            <span style="font-size: 9pt; color: {{ $d <= ($lang['score'] ?? 4) ? '#FFFFFF' : '#64748B' }};">&#9679;</span>
                                        @endfor
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif

                @if(!empty($vis['awards']) && !empty($awards))
                    <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #475569;">
                        <div style="font-size: 7.5pt; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; color: #94A3B8; margin-bottom: 6px;">&#9632; Honors &amp; Awards</div>
                        @foreach($awards as $aw)
                            @continue(empty($aw['title']))
                            <div style="margin-bottom: 6px;">
                                <div style="font-weight: bold; font-size: 8.8pt; color: #FFFFFF;">{{ $aw['title'] }}</div>
                                <div style="font-size: 7.8pt; color: #CBD5E1;">{{ implode(' • ', array_filter([$aw['issuer'] ?? '', $aw['date'] ?? ''])) }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </td>

            <td valign="top" style="width: 66%; background-color: #FFFFFF; padding: 26px 24px;">
                @if(!empty($vis['experience']) && !empty($exps))
                    <div style="background-color: #F1F5F9; padding: 5px 10px; font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.4px; color: #334155; margin-bottom: 10px; border-left: 3px solid {{ $accent }};">
                        Work Experience
                    </div>
                    @foreach($exps as $exp)
                        @continue(empty($exp['role']) && empty($exp['company']))
                        <div style="margin-bottom: 12px;">
                            <table>
                                <tr>
                                    <td style="font-weight: bold; font-size: 10.3pt; color: #0F172A;">{{ $exp['company'] ?: ($exp['role'] ?? '') }}</td>
                                    <td align="right" style="font-size: 8.5pt; color: #64748B; white-space: nowrap;">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; font-size: 9.2pt; color: {{ $accent }};">{{ $exp['role'] ?? '' }}</td>
                                    <td align="right" style="font-size: 8.3pt; color: #94A3B8;">{{ $exp['location'] ?? '' }}</td>
                                </tr>
                            </table>
                            <ul>
                                @foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach
                            </ul>
                        </div>
                    @endforeach
                @endif

                @if(!empty($vis['education']) && !empty($edus))
                    <div style="background-color: #F1F5F9; padding: 5px 10px; font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.4px; color: #334155; margin: 14px 0 10px 0; border-left: 3px solid {{ $accent }};">
                        Education
                    </div>
                    @foreach($edus as $edu)
                        @continue(empty($edu['degree']) && empty($edu['school']))
                        <div style="margin-bottom: 8px;">
                            <table>
                                <tr>
                                    <td style="font-weight: bold; font-size: 10pt; color: #0F172A;">{{ $edu['degree'] ?? '' }}</td>
                                    <td align="right" style="font-size: 8.5pt; color: #64748B; white-space: nowrap;">{{ implode(' – ', array_filter([$edu['start_date'] ?? '', $edu['end_date'] ?? ''])) }}</td>
                                </tr>
                            </table>
                            <div style="font-size: 9pt; color: #475569;">{{ implode(', ', array_filter([$edu['school'] ?? '', $edu['location'] ?? ''])) }}</div>
                            @if(!empty($edu['details']))<div style="font-size: 8.3pt; color: #64748B;">{{ $edu['details'] }}</div>@endif
                        </div>
                    @endforeach
                @endif

                @if(!empty($vis['skills']) && !empty($skills))
                    <div style="background-color: #F1F5F9; padding: 5px 10px; font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.4px; color: #334155; margin: 14px 0 10px 0; border-left: 3px solid {{ $accent }};">
                        Core Competencies
                    </div>
                    <div style="line-height: 1.9;">
                        @foreach($skills as $sk)
                            <span style="display: inline-block; background-color: #F1F5F9; border: 1px solid #E2E8F0; padding: 2px 7px; border-radius: 3px; font-size: 8.5pt; font-weight: bold; color: #1E293B; margin: 0 4px 4px 0;">{{ $sk }}</span>
                        @endforeach
                    </div>
                @endif

                @if(!empty($vis['projects']) && !empty($projects))
                    <div style="background-color: #F1F5F9; padding: 5px 10px; font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.4px; color: #334155; margin: 14px 0 8px 0; border-left: 3px solid {{ $accent }};">
                        Key Projects
                    </div>
                    @foreach($projects as $proj)
                        @continue(empty($proj['name']))
                        <div style="margin-bottom: 7px;">
                            <table>
                                <tr>
                                    <td style="font-weight: bold; color: #0F172A;">{{ $proj['name'] }}</td>
                                    <td align="right" style="font-size: 8.2pt; color: #64748B;">{{ $proj['link'] ?? '' }}</td>
                                </tr>
                            </table>
                            @if(!empty($proj['description']))<div style="font-size: 8.8pt; color: #475569;">{{ $proj['description'] }}</div>@endif
                        </div>
                    @endforeach
                @endif
            </td>
        </tr>
    </table>

{{-- =======================================================================
     2. EDITORIAL FIGMA (Cream Archival Canvas + Serif Titles + Hairlines)
     ======================================================================= --}}
@elseif($tStyle === 'editorial_figma')
    <div style="padding: 32px 34px; background-color: #FAF8F4; min-height: 270mm;">
        <table style="border-bottom: 1px solid #D6D0C4; padding-bottom: 14px; margin-bottom: 14px;">
            <tr>
                <td valign="middle" style="width: 65%; padding-bottom: 12px;">
                    <div style="font-size: 7.5pt; font-weight: bold; letter-spacing: 2.2px; text-transform: uppercase; color: {{ $accent }}; margin-bottom: 4px;">Curriculum Vitae</div>
                    <div class="serif" style="font-size: 22pt; font-weight: bold; color: #1A1815; line-height: 1.08;">{{ $fullName }}</div>
                    <div class="serif" style="font-style: italic; font-size: 11pt; color: #59534A; margin-top: 3px;">{{ $p['job_title'] ?? '' }}</div>
                </td>
                <td align="right" valign="middle" style="width: 35%; font-size: 8.6pt; color: #59534A; padding-bottom: 12px;">
                    @if(!empty($p['email']))<div style="font-weight: bold; color: #1A1815;">{{ $p['email'] }}</div>@endif
                    @if(!empty($p['phone']))<div>{{ $p['phone'] }}</div>@endif
                    @if(!empty($p['location']))<div>{{ $p['location'] }}</div>@endif
                    @if(!empty($p['website']))<div>{{ $p['website'] }}</div>@endif
                </td>
            </tr>
        </table>

        @if(!empty($vis['summary']) && !empty($c['summary']))
            <table style="border-bottom: 1px solid #E5E0D5; margin-bottom: 16px;">
                <tr>
                    <td valign="top" style="width: 22%; padding-bottom: 12px; font-size: 8pt; font-weight: bold; letter-spacing: 1.8px; text-transform: uppercase; color: {{ $accent }};">Profile</td>
                    <td valign="top" style="width: 78%; padding-bottom: 12px; color: #35302A;">{{ $c['summary'] }}</td>
                </tr>
            </table>
        @endif

        <table>
            <tr>
                <td valign="top" style="width: 64%; padding-right: 18px; border-right: 1px solid #E5E0D5;">
                    @if(!empty($vis['experience']) && !empty($exps))
                        <div class="serif" style="font-size: 11.5pt; font-weight: bold; border-bottom: 1px solid #D6D0C4; padding-bottom: 4px; margin-bottom: 10px; color: #1A1815;">Experience</div>
                        @foreach($exps as $exp)
                            @continue(empty($exp['role']) && empty($exp['company']))
                            <div style="margin-bottom: 12px;">
                                <table>
                                    <tr>
                                        <td style="font-weight: bold; font-size: 10pt; color: #1A1815;">{{ $exp['role'] ?: ($exp['company'] ?? '') }}</td>
                                        <td align="right" class="serif" style="font-style: italic; font-size: 8.5pt; color: #6B645A;">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</td>
                                    </tr>
                                </table>
                                <div style="font-size: 9pt; font-weight: bold; color: {{ $accent }}; margin-bottom: 3px;">{{ implode(' — ', array_filter([$exp['company'] ?? '', $exp['location'] ?? ''])) }}</div>
                                <ul>@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                            </div>
                        @endforeach
                    @endif

                    @if(!empty($vis['projects']) && !empty($projects))
                        <div class="serif" style="font-size: 11.5pt; font-weight: bold; border-bottom: 1px solid #D6D0C4; padding-bottom: 4px; margin: 12px 0 8px 0; color: #1A1815;">Selected Works</div>
                        @foreach($projects as $proj)
                            @continue(empty($proj['name']))
                            <div style="margin-bottom: 8px;">
                                <strong>{{ $proj['name'] }}</strong>
                                @if(!empty($proj['link'])) <span style="font-size: 8.2pt; color: #6B645A;">({{ $proj['link'] }})</span>@endif
                                @if(!empty($proj['description']))<div style="color: #3D372F;">{{ $proj['description'] }}</div>@endif
                            </div>
                        @endforeach
                    @endif
                </td>

                <td valign="top" style="width: 36%; padding-left: 18px;">
                    @if(!empty($vis['education']) && !empty($edus))
                        <div class="serif" style="font-size: 11pt; font-weight: bold; border-bottom: 1px solid #D6D0C4; padding-bottom: 4px; margin-bottom: 8px; color: #1A1815;">Education</div>
                        @foreach($edus as $edu)
                            @continue(empty($edu['degree']) && empty($edu['school']))
                            <div style="margin-bottom: 8px;">
                                <div style="font-weight: bold; color: #1A1815;">{{ $edu['degree'] ?? '' }}</div>
                                <div style="color: {{ $accent }}; font-size: 8.8pt;">{{ $edu['school'] ?? '' }}</div>
                                <div style="font-size: 8pt; color: #6B645A;">{{ implode(' – ', array_filter([$edu['start_date'] ?? '', $edu['end_date'] ?? ''])) }}</div>
                            </div>
                        @endforeach
                    @endif

                    @if(!empty($vis['skills']) && !empty($skills))
                        <div class="serif" style="font-size: 11pt; font-weight: bold; border-bottom: 1px solid #D6D0C4; padding-bottom: 4px; margin: 12px 0 8px 0; color: #1A1815;">Expertise</div>
                        <div style="line-height: 1.9;">
                            @foreach($skills as $sk)
                                <span style="display: inline-block; border: 1px solid #C9C1B2; background-color: #FFFFFF; padding: 2px 6px; font-size: 8.3pt; color: #2E2A24; margin: 0 4px 4px 0;">{{ $sk }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($vis['languages']) && !empty($langs))
                        <div class="serif" style="font-size: 11pt; font-weight: bold; border-bottom: 1px solid #D6D0C4; padding-bottom: 4px; margin: 12px 0 8px 0; color: #1A1815;">Languages</div>
                        <table>
                            @foreach($langs as $lang)
                                @continue(empty($lang['name']))
                                <tr>
                                    <td style="padding-bottom: 3px; font-weight: bold;">{{ $lang['name'] }}</td>
                                    <td align="right" class="serif" style="padding-bottom: 3px; font-style: italic; color: #6B645A;">{{ $lang['level'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </td>
            </tr>
        </table>
    </div>

{{-- =======================================================================
     3. TIMELINE PRO (Vertical Spine + Skill Progress Bars via Nested Tables)
     ======================================================================= --}}
@elseif($tStyle === 'timeline_pro')
    <table style="background-color: {{ $accentLight }}; border-bottom: 1px solid #CBD5E1;">
        <tr>
            <td valign="middle" style="width: 64%; padding: 22px 26px;">
                <div style="border-left: 5px solid {{ $accent }}; padding-left: 12px;">
                    <div class="{{ $font === 'serif' ? 'serif' : '' }}" style="font-size: 19pt; font-weight: bold; color: #0F172A; line-height: 1.1;">{{ $fullName }}</div>
                    <div style="font-size: 10.5pt; font-weight: bold; color: {{ $accent }}; margin-top: 3px;">{{ $p['job_title'] ?? '' }}</div>
                </div>
            </td>
            <td align="right" valign="middle" style="width: 36%; padding: 22px 26px; font-size: 8.6pt; color: #475569;">
                @if(!empty($p['email']))<div style="font-weight: bold; color: #0F172A;">{{ $p['email'] }}</div>@endif
                @if(!empty($p['phone']))<div>{{ $p['phone'] }}</div>@endif
                @if(!empty($p['location']))<div>{{ $p['location'] }}</div>@endif
                @if(!empty($p['linkedin']) || !empty($p['website']))<div>{{ $p['linkedin'] ?: ($p['website'] ?? '') }}</div>@endif
            </td>
        </tr>
    </table>

    <table style="padding: 20px 26px;">
        <tr>
            <td valign="top" style="width: 63%; padding: 20px 18px 20px 26px;">
                @if(!empty($vis['summary']) && !empty($c['summary']))
                    <div style="font-size: 8.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; margin-bottom: 6px;">Executive Summary</div>
                    <p style="margin: 0 0 14px 0; color: #334155;">{{ $c['summary'] }}</p>
                @endif

                @if(!empty($vis['experience']) && !empty($exps))
                    <div style="font-size: 8.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; margin-bottom: 10px;">Career Timeline</div>
                    <div style="border-left: 2px solid {{ $accentMid }}; padding-left: 14px; margin-left: 4px;">
                        @foreach($exps as $exp)
                            @continue(empty($exp['role']) && empty($exp['company']))
                            <div style="margin-bottom: 12px;">
                                <table>
                                    <tr>
                                        <td style="font-weight: bold; font-size: 10.2pt; color: #0F172A;">
                                            <span style="color: {{ $accent }};">&#9679;</span> {{ $exp['role'] ?: ($exp['company'] ?? '') }}
                                        </td>
                                        <td align="right" style="white-space: nowrap;">
                                            <span style="background-color: {{ $accentLight }}; color: {{ $accent }}; padding: 1px 7px; border-radius: 8px; font-size: 8pt; font-weight: bold;">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</span>
                                        </td>
                                    </tr>
                                </table>
                                <div style="font-size: 9pt; font-weight: bold; color: #475569; margin: 2px 0 4px 0;">{{ implode(' • ', array_filter([$exp['company'] ?? '', $exp['location'] ?? ''])) }}</div>
                                <ul>@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                            </div>
                        @endforeach
                    </div>
                @endif
            </td>

            <td valign="top" style="width: 37%; padding: 20px 26px 20px 8px;">
                <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 14px;">
                    @if(!empty($vis['skills']) && !empty($skills))
                        <div style="font-size: 8.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; margin-bottom: 10px;">Skill Proficiency</div>
                        @foreach($skills as $idx => $sk)
                            @php $pct = 96 - (($idx * 6) % 22); @endphp
                            <div style="margin-bottom: 7px;">
                                <table>
                                    <tr>
                                        <td style="font-size: 8.6pt; font-weight: bold; color: #1E293B;">{{ $sk }}</td>
                                        <td align="right" style="font-size: 7.8pt; color: #94A3B8;">{{ $pct }}%</td>
                                    </tr>
                                </table>
                                <table style="margin-top: 2px; background-color: #E2E8F0; height: 5px;">
                                    <tr>
                                        <td style="width: {{ $pct }}%; background-color: {{ $accent }}; height: 5px;"></td>
                                        <td style="width: {{ 100 - $pct }}%; height: 5px;"></td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    @endif

                    @if(!empty($vis['education']) && !empty($edus))
                        <div style="border-top: 1px solid #E2E8F0; padding-top: 10px; margin-top: 10px;">
                            <div style="font-size: 8.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; margin-bottom: 6px;">Education</div>
                            @foreach($edus as $edu)
                                @continue(empty($edu['degree']) && empty($edu['school']))
                                <div style="margin-bottom: 6px;">
                                    <div style="font-weight: bold; font-size: 9pt;">{{ $edu['degree'] ?? '' }}</div>
                                    <div style="font-size: 8.4pt; color: #475569;">{{ $edu['school'] ?? '' }}</div>
                                    <div style="font-size: 7.8pt; color: #94A3B8;">{{ implode(' – ', array_filter([$edu['start_date'] ?? '', $edu['end_date'] ?? ''])) }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($vis['languages']) && !empty($langs))
                        <div style="border-top: 1px solid #E2E8F0; padding-top: 10px; margin-top: 10px;">
                            <div style="font-size: 8.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; margin-bottom: 6px;">Languages</div>
                            <table>
                                @foreach($langs as $lang)
                                    @continue(empty($lang['name']))
                                    <tr>
                                        <td style="font-weight: bold; font-size: 8.6pt;">{{ $lang['name'] }}</td>
                                        <td align="right" style="font-size: 8.2pt; font-weight: bold; color: {{ $accent }};">{{ $lang['level'] ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

{{-- =======================================================================
     4. LUXE SPLIT
     ======================================================================= --}}
@elseif($tStyle === 'luxe_split')
    <div style="padding: 30px 34px;">
        <table style="border-bottom: 1px solid #CBD5E1; margin-bottom: 16px;">
            <tr>
                <td valign="middle" style="width: 65%; padding-bottom: 14px;">
                    <table>
                        <tr>
                            <td valign="middle" style="width: 58px;">
                                <div style="width: 50px; height: 50px; border: 2px solid {{ $accent }}; border-radius: 8px; line-height: 46px; text-align: center; font-weight: bold; font-size: 15pt; color: {{ $accent }};">{{ $initials }}</div>
                            </td>
                            <td valign="middle" style="padding-left: 12px;">
                                <div class="{{ $font === 'serif' ? 'serif' : '' }}" style="font-size: 19pt; font-weight: bold; color: #0F172A;">{{ $fullName }}</div>
                                <div style="font-size: 10pt; font-weight: bold; color: #64748B;">{{ $p['job_title'] ?? '' }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td align="right" valign="middle" style="width: 35%; font-size: 8.6pt; color: #64748B; padding-bottom: 14px;">
                    @if(!empty($p['email']))<div style="font-weight: bold; color: #0F172A;">{{ $p['email'] }}</div>@endif
                    @if(!empty($p['phone']))<div>{{ $p['phone'] }}</div>@endif
                    @if(!empty($p['location']))<div>{{ $p['location'] }}</div>@endif
                </td>
            </tr>
        </table>

        @if(!empty($vis['summary']) && !empty($c['summary']))
            <div style="background-color: #F8FAFC; border-left: 4px solid {{ $accent }}; padding: 12px 14px; margin-bottom: 18px; color: #334155;">{{ $c['summary'] }}</div>
        @endif

        <table>
            <tr>
                <td valign="top" style="width: 60%; padding-right: 18px;">
                    @if(!empty($vis['experience']) && !empty($exps))
                        <div style="font-size: 8.2pt; font-weight: bold; letter-spacing: 1.8px; text-transform: uppercase; color: #94A3B8; margin-bottom: 10px;">Experience</div>
                        @foreach($exps as $exp)
                            @continue(empty($exp['role']) && empty($exp['company']))
                            <div style="margin-bottom: 12px;">
                                <div style="font-weight: bold; font-size: 10pt; color: #0F172A;">{{ $exp['role'] ?: ($exp['company'] ?? '') }}</div>
                                <table>
                                    <tr>
                                        <td style="font-size: 9pt; font-weight: bold; color: {{ $accent }};">{{ implode(' • ', array_filter([$exp['company'] ?? '', $exp['location'] ?? ''])) }}</td>
                                        <td align="right" style="font-size: 8.3pt; color: #94A3B8;">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</td>
                                    </tr>
                                </table>
                                <ul>@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                            </div>
                        @endforeach
                    @endif
                </td>
                <td valign="top" style="width: 40%; padding-left: 12px;">
                    @if(!empty($vis['education']) && !empty($edus))
                        <div style="font-size: 8.2pt; font-weight: bold; letter-spacing: 1.8px; text-transform: uppercase; color: #94A3B8; margin-bottom: 8px;">Education</div>
                        @foreach($edus as $edu)
                            @continue(empty($edu['degree']) && empty($edu['school']))
                            <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; padding: 9px 10px; margin-bottom: 8px; border-radius: 5px;">
                                <div style="font-weight: bold; font-size: 9.2pt;">{{ $edu['degree'] ?? '' }}</div>
                                <div style="color: {{ $accent }}; font-size: 8.8pt;">{{ $edu['school'] ?? '' }}</div>
                                <div style="font-size: 8pt; color: #94A3B8;">{{ implode(' – ', array_filter([$edu['start_date'] ?? '', $edu['end_date'] ?? ''])) }}</div>
                            </div>
                        @endforeach
                    @endif
                    @if(!empty($vis['skills']) && !empty($skills))
                        <div style="font-size: 8.2pt; font-weight: bold; letter-spacing: 1.8px; text-transform: uppercase; color: #94A3B8; margin: 12px 0 8px 0;">Core Skills</div>
                        <div style="line-height: 1.9;">
                            @foreach($skills as $sk)<span style="display: inline-block; border: 1px solid #CBD5E1; border-radius: 10px; padding: 2px 8px; font-size: 8.4pt; font-weight: bold; color: #334155; margin: 0 4px 4px 0;">{{ $sk }}</span>@endforeach
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

{{-- =======================================================================
     5. EXECUTIVE BOLD
     ======================================================================= --}}
@elseif($tStyle === 'executive_bold')
    <table style="background-color: {{ $accent }}; color: #FFFFFF;">
        <tr>
            <td valign="middle" style="width: 65%; padding: 24px 30px;">
                <div class="{{ $font === 'serif' ? 'serif' : '' }}" style="font-size: 21pt; font-weight: bold; text-transform: uppercase; color: #FFFFFF;">{{ $fullName }}</div>
                <div style="font-size: 10pt; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; color: #CBD5E1; margin-top: 4px;">{{ $p['job_title'] ?? '' }}</div>
            </td>
            <td align="right" valign="middle" style="width: 35%; padding: 24px 30px; font-size: 8.6pt; color: #E2E8F0;">
                @if(!empty($p['email']))<div style="font-weight: bold; color: #FFFFFF;">{{ $p['email'] }}</div>@endif
                @if(!empty($p['phone']))<div>{{ $p['phone'] }}</div>@endif
                @if(!empty($p['location']))<div>{{ $p['location'] }}</div>@endif
            </td>
        </tr>
    </table>
    <div style="padding: 24px 30px;">
        @if(!empty($vis['summary']) && !empty($c['summary']))
            <div style="border-bottom: 2px solid #F1F5F9; padding-bottom: 12px; margin-bottom: 14px;">
                <div style="font-size: 8pt; font-weight: bold; letter-spacing: 1.6px; text-transform: uppercase; color: {{ $accent }}; margin-bottom: 4px;">Executive Mandates</div>
                <div style="color: #334155; font-weight: bold;">{{ $c['summary'] }}</div>
            </div>
        @endif
        <table>
            <tr>
                <td valign="top" style="width: 64%; padding-right: 18px;">
                    @if(!empty($vis['experience']) && !empty($exps))
                        <div style="font-size: 9pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.4px; color: {{ $accent }}; border-bottom: 1px solid {{ $accent }}; padding-bottom: 3px; margin-bottom: 10px;">Leadership Experience</div>
                        @foreach($exps as $exp)
                            @continue(empty($exp['role']) && empty($exp['company']))
                            <div style="margin-bottom: 12px;">
                                <table>
                                    <tr>
                                        <td style="font-weight: bold; font-size: 10.2pt;">{{ $exp['role'] ?: ($exp['company'] ?? '') }}</td>
                                        <td align="right" style="font-size: 8.5pt; color: #64748B;">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</td>
                                    </tr>
                                </table>
                                <div style="font-size: 9pt; font-weight: bold; color: {{ $accent }}; margin-bottom: 3px;">{{ implode(' • ', array_filter([$exp['company'] ?? '', $exp['location'] ?? ''])) }}</div>
                                <ul>@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                            </div>
                        @endforeach
                    @endif
                </td>
                <td valign="top" style="width: 36%; padding-left: 10px;">
                    @if(!empty($vis['skills']) && !empty($skills))
                        <div style="background-color: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 6px; padding: 12px; margin-bottom: 12px;">
                            <div style="font-size: 8.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.4px; color: {{ $accent }}; margin-bottom: 8px;">Core Competencies</div>
                            @foreach($skills as $sk)
                                <div style="font-size: 8.8pt; font-weight: bold; color: #1E293B; margin-bottom: 4px;"><span style="color: {{ $accent }};">&#9632;</span> {{ $sk }}</div>
                            @endforeach
                        </div>
                    @endif
                    @if(!empty($vis['education']) && !empty($edus))
                        <div style="border: 1px solid #CBD5E1; border-radius: 6px; padding: 12px;">
                            <div style="font-size: 8.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.4px; color: {{ $accent }}; margin-bottom: 6px;">Education</div>
                            @foreach($edus as $edu)
                                @continue(empty($edu['degree']) && empty($edu['school']))
                                <div style="margin-bottom: 6px;"><div style="font-weight: bold; font-size: 9pt;">{{ $edu['degree'] ?? '' }}</div><div style="font-size: 8.4pt; color: #475569;">{{ $edu['school'] ?? '' }}</div></div>
                            @endforeach
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

{{-- =======================================================================
     6. CREATIVE CARD
     ======================================================================= --}}
@elseif($tStyle === 'creative_card')
    <table style="height: 296mm;">
        <tr>
            <td valign="top" style="width: 34%; background-color: {{ $accentLight }}; border-right: 1px solid {{ $accentMid }}; padding: 22px 18px;">
                <div style="background-color: #FFFFFF; border: 1px solid {{ $accentMid }}; border-radius: 8px; padding: 14px; margin-bottom: 16px;">
                    @if($showPhoto)
                        <div style="width: 46px; height: 46px; border-radius: 8px; background-color: {{ $accent }}; color: #FFFFFF; line-height: 46px; text-align: center; font-weight: bold; font-size: 13pt; margin-bottom: 8px;">{{ $initials }}</div>
                    @endif
                    <div class="{{ $font === 'serif' ? 'serif' : '' }}" style="font-size: 14.5pt; font-weight: bold; color: #0F172A;">{{ $fullName }}</div>
                    <div style="font-size: 9pt; font-weight: bold; color: {{ $accent }}; margin-top: 3px;">{{ $p['job_title'] ?? '' }}</div>
                </div>
                <div style="font-size: 8.5pt; color: #334155; margin-bottom: 16px;">
                    <div style="font-size: 7.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; margin-bottom: 5px;">Reach Out</div>
                    @if(!empty($p['email']))<div style="font-weight: bold;">{{ $p['email'] }}</div>@endif
                    @if(!empty($p['phone']))<div>{{ $p['phone'] }}</div>@endif
                    @if(!empty($p['location']))<div>{{ $p['location'] }}</div>@endif
                    @if(!empty($p['website']))<div style="color: {{ $accent }}; font-weight: bold;">{{ $p['website'] }}</div>@endif
                </div>
                @if(!empty($vis['skills']) && !empty($skills))
                    <div style="border-top: 1px solid {{ $accentMid }}; padding-top: 10px;">
                        <div style="font-size: 7.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; margin-bottom: 7px;">Toolbox &amp; Craft</div>
                        <div style="line-height: 1.9;">
                            @foreach($skills as $sk)<span style="display: inline-block; background-color: #FFFFFF; border: 1px solid {{ $accentMid }}; border-radius: 10px; padding: 1px 7px; font-size: 8.2pt; font-weight: bold; color: {{ $accent }}; margin: 0 3px 4px 0;">{{ $sk }}</span>@endforeach
                        </div>
                    </div>
                @endif
            </td>
            <td valign="top" style="width: 66%; background-color: #FFFFFF; padding: 26px 24px;">
                @if(!empty($vis['summary']) && !empty($c['summary']))
                    <div style="font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; margin-bottom: 5px;">About Me</div>
                    <p style="margin: 0 0 14px 0; color: #334155;">{{ $c['summary'] }}</p>
                @endif
                @if(!empty($vis['experience']) && !empty($exps))
                    <div style="font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; border-bottom: 1px solid #E2E8F0; padding-bottom: 3px; margin-bottom: 10px;">Work History</div>
                    @foreach($exps as $exp)
                        @continue(empty($exp['role']) && empty($exp['company']))
                        <div style="margin-bottom: 11px;">
                            <table><tr><td style="font-weight: bold; font-size: 10pt;">{{ $exp['role'] ?: ($exp['company'] ?? '') }}</td><td align="right" style="font-size: 8.3pt; color: #64748B;">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</td></tr></table>
                            <div style="font-size: 9pt; font-weight: bold; color: {{ $accent }}; margin-bottom: 3px;">{{ implode(' — ', array_filter([$exp['company'] ?? '', $exp['location'] ?? ''])) }}</div>
                            <ul>@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                        </div>
                    @endforeach
                @endif
                @if(!empty($vis['projects']) && !empty($projects))
                    <div style="font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; border-bottom: 1px solid #E2E8F0; padding-bottom: 3px; margin: 12px 0 8px 0;">Featured Portfolio</div>
                    @foreach($projects as $proj)
                        @continue(empty($proj['name']))
                        <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; padding: 9px 11px; border-radius: 6px; margin-bottom: 7px;">
                            <strong>{{ $proj['name'] }}</strong> <span style="font-size: 8.2pt; color: {{ $accent }};">{{ $proj['link'] ?? '' }}</span>
                            @if(!empty($proj['description']))<div style="font-size: 8.7pt; color: #475569; margin-top: 2px;">{{ $proj['description'] }}</div>@endif
                        </div>
                    @endforeach
                @endif
            </td>
        </tr>
    </table>

{{-- =======================================================================
     7. TECH STACK
     ======================================================================= --}}
@elseif($tStyle === 'tech_stack')
    <div style="border-top: 5px solid {{ $accent }}; padding: 26px 32px;">
        <table style="border-bottom: 1px solid #CBD5E1; margin-bottom: 14px;">
            <tr>
                <td valign="top" style="padding-bottom: 12px;">
                    <span class="mono" style="display: inline-block; background-color: {{ $accentLight }}; color: {{ $accent }}; padding: 2px 7px; font-size: 8pt; font-weight: bold; margin-bottom: 5px;">$ whoami --role</span>
                    <div style="font-size: 19pt; font-weight: bold; color: #0F172A;">{{ $fullName }}</div>
                    <div class="mono" style="font-size: 9.5pt; font-weight: bold; color: {{ $accent }};">{{ $p['job_title'] ?? '' }}</div>
                </td>
                <td align="right" valign="middle" class="mono" style="font-size: 8.2pt; color: #475569; padding-bottom: 12px;">
                    @if(!empty($p['email']))<div style="font-weight: bold; color: #0F172A;">{{ $p['email'] }}</div>@endif
                    @if(!empty($p['website']))<div style="color: {{ $accent }}; font-weight: bold;">{{ $p['website'] }}</div>@endif
                    @if(!empty($p['linkedin']))<div>{{ $p['linkedin'] }}</div>@endif
                </td>
            </tr>
        </table>
        @if(!empty($vis['summary']) && !empty($c['summary']))
            <div class="mono" style="font-size: 8.2pt; font-weight: bold; color: {{ $accent }}; margin-bottom: 3px;">// 01. SYSTEM_SUMMARY</div>
            <p style="margin: 0 0 12px 0;">{{ $c['summary'] }}</p>
        @endif
        @if(!empty($vis['skills']) && !empty($skills))
            <div style="background-color: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 5px; padding: 10px 12px; margin-bottom: 14px;">
                <div class="mono" style="font-size: 8.2pt; font-weight: bold; color: {{ $accent }}; margin-bottom: 6px;">// 02. TECH_STACK</div>
                <div style="line-height: 1.9;">@foreach($skills as $sk)<span class="mono" style="display: inline-block; background-color: #FFFFFF; border: 1px solid #CBD5E1; padding: 2px 6px; font-size: 8.2pt; font-weight: bold; margin: 0 4px 4px 0;">{{ $sk }}</span>@endforeach</div>
            </div>
        @endif
        @if(!empty($vis['experience']) && !empty($exps))
            <div class="mono" style="font-size: 8.2pt; font-weight: bold; color: {{ $accent }}; margin-bottom: 8px;">// 03. PRODUCTION_EXPERIENCE</div>
            @foreach($exps as $exp)
                @continue(empty($exp['role']) && empty($exp['company']))
                <div style="margin-bottom: 11px;">
                    <table><tr><td style="font-weight: bold; font-size: 10pt;">{{ $exp['role'] ?? '' }} <span style="color: #94A3B8;">@</span> <span style="color: {{ $accent }}">{{ $exp['company'] ?? '' }}</span></td><td align="right" class="mono" style="font-size: 8pt; color: #64748B;">{{ implode(' -> ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</td></tr></table>
                    <ul>@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                </div>
            @endforeach
        @endif
        @if(!empty($vis['projects']) && !empty($projects))
            <div class="mono" style="font-size: 8.2pt; font-weight: bold; color: {{ $accent }}; margin: 10px 0 6px 0;">// 04. OPEN_SOURCE &amp; ARCHITECTURE</div>
            @foreach($projects as $proj)
                @continue(empty($proj['name']))
                <div style="border: 1px solid #E2E8F0; background-color: #F8FAFC; padding: 8px 10px; margin-bottom: 6px; border-radius: 4px;">
                    <span class="mono" style="font-weight: bold; font-size: 9pt;">{{ $proj['name'] }}</span> <span class="mono" style="font-size: 8pt; color: {{ $accent }};">{{ $proj['link'] ?? '' }}</span>
                    @if(!empty($proj['description']))<div style="font-size: 8.6pt; color: #475569; margin-top: 2px;">{{ $proj['description'] }}</div>@endif
                </div>
            @endforeach
        @endif
    </div>

{{-- =======================================================================
     8. MINIMAL SWISS
     ======================================================================= --}}
@elseif($tStyle === 'minimal_swiss')
    <div style="padding: 30px 34px;">
        <table style="border-top: 4px solid {{ $accent }}; border-bottom: 1px solid #CBD5E1; margin-bottom: 16px;">
            <tr>
                <td valign="bottom" style="width: 65%; padding: 14px 0;">
                    <div class="{{ $font === 'serif' ? 'serif' : '' }}" style="font-size: 21pt; font-weight: bold; color: #0F172A; line-height: 1.05;">{{ $fullName }}</div>
                    <div style="font-size: 10pt; font-weight: bold; color: {{ $accent }}; margin-top: 4px;">{{ $p['job_title'] ?? '' }}</div>
                </td>
                <td align="right" valign="bottom" style="width: 35%; padding: 14px 0; font-size: 8.6pt; color: #475569;">
                    @if(!empty($p['email']))<div style="font-weight: bold; color: #0F172A;">{{ $p['email'] }}</div>@endif
                    @if(!empty($p['phone']))<div>{{ $p['phone'] }}</div>@endif
                    @if(!empty($p['location']))<div>{{ $p['location'] }}</div>@endif
                </td>
            </tr>
        </table>
        @if(!empty($vis['summary']) && !empty($c['summary']))
            <table style="border-bottom: 1px solid #F1F5F9; margin-bottom: 12px;"><tr><td valign="top" style="width: 26%; padding-bottom: 10px; font-size: 8pt; font-weight: bold; letter-spacing: 1.6px; text-transform: uppercase; color: #94A3B8;">Profile</td><td valign="top" style="width: 74%; padding-bottom: 10px;">{{ $c['summary'] }}</td></tr></table>
        @endif
        @if(!empty($vis['experience']) && !empty($exps))
            <table style="border-bottom: 1px solid #F1F5F9; margin-bottom: 12px;">
                <tr>
                    <td valign="top" style="width: 26%; font-size: 8pt; font-weight: bold; letter-spacing: 1.6px; text-transform: uppercase; color: {{ $accent }};">Experience</td>
                    <td valign="top" style="width: 74%; padding-bottom: 10px;">
                        @foreach($exps as $exp)
                            @continue(empty($exp['role']) && empty($exp['company']))
                            <div style="margin-bottom: 10px;">
                                <table><tr><td style="font-weight: bold; font-size: 10pt;">{{ $exp['role'] ?: ($exp['company'] ?? '') }}</td><td align="right" style="font-size: 8.4pt; color: #64748B;">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</td></tr></table>
                                <div style="font-size: 9pt; color: #64748B; margin-bottom: 2px;">{{ implode(' / ', array_filter([$exp['company'] ?? '', $exp['location'] ?? ''])) }}</div>
                                <ul>@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                            </div>
                        @endforeach
                    </td>
                </tr>
            </table>
        @endif
        @if(!empty($vis['education']) && !empty($edus))
            <table style="border-bottom: 1px solid #F1F5F9; margin-bottom: 12px;">
                <tr>
                    <td valign="top" style="width: 26%; font-size: 8pt; font-weight: bold; letter-spacing: 1.6px; text-transform: uppercase; color: {{ $accent }};">Education</td>
                    <td valign="top" style="width: 74%; padding-bottom: 10px;">@foreach($edus as $edu)@continue(empty($edu['degree']) && empty($edu['school']))<div style="margin-bottom: 6px;"><strong>{{ $edu['degree'] ?? '' }}</strong><div style="font-size: 8.8pt; color: #475569;">{{ $edu['school'] ?? '' }}</div></div>@endforeach</td>
                </tr>
            </table>
        @endif
        @if(!empty($vis['skills']) && !empty($skills))
            <table><tr><td valign="top" style="width: 26%; font-size: 8pt; font-weight: bold; letter-spacing: 1.6px; text-transform: uppercase; color: {{ $accent }};">Capabilities</td><td valign="top" style="width: 74%; font-weight: bold;">{{ implode('   •   ', $skills) }}</td></tr></table>
        @endif
    </div>

{{-- =======================================================================
     9. ATS CLASSIC
     ======================================================================= --}}
@elseif($tStyle === 'ats_classic')
    <div style="padding: 28px 34px; color: #0F172A;">
        <div style="text-align: center; border-bottom: 2px solid #0F172A; padding-bottom: 10px; margin-bottom: 14px;">
            <div class="{{ $font === 'serif' ? 'serif' : '' }}" style="font-size: 18pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8px;">{{ $fullName }}</div>
            @if(!empty($p['job_title']))<div style="font-size: 10pt; font-weight: bold; color: #334155; margin-top: 2px;">{{ $p['job_title'] }}</div>@endif
            <div style="font-size: 8.6pt; color: #475569; margin-top: 4px;">{{ implode(' • ', array_filter([$p['location'] ?? '', $p['phone'] ?? '', $p['email'] ?? '', $p['linkedin'] ?? ''])) }}</div>
        </div>
        @if(!empty($vis['summary']) && !empty($c['summary']))
            <div style="font-size: 9pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.2px; border-bottom: 1px solid #94A3B8; padding-bottom: 2px; margin-bottom: 6px;">Professional Summary</div>
            <p style="margin: 0 0 12px 0;">{{ $c['summary'] }}</p>
        @endif
        @if(!empty($vis['experience']) && !empty($exps))
            <div style="font-size: 9pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.2px; border-bottom: 1px solid #94A3B8; padding-bottom: 2px; margin-bottom: 8px;">Professional Experience</div>
            @foreach($exps as $exp)
                @continue(empty($exp['role']) && empty($exp['company']))
                <div style="margin-bottom: 10px;">
                    <table>
                        <tr><td style="font-weight: bold;">{{ $exp['company'] ?: ($exp['role'] ?? '') }}</td><td align="right" style="font-weight: bold;">{{ $exp['location'] ?? '' }}</td></tr>
                        <tr><td style="font-style: italic;">{{ $exp['role'] ?? '' }}</td><td align="right" style="font-style: italic;">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</td></tr>
                    </table>
                    <ul>@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                </div>
            @endforeach
        @endif
        @if(!empty($vis['education']) && !empty($edus))
            <div style="font-size: 9pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.2px; border-bottom: 1px solid #94A3B8; padding-bottom: 2px; margin: 12px 0 8px 0;">Education</div>
            @foreach($edus as $edu)
                @continue(empty($edu['degree']) && empty($edu['school']))
                <div style="margin-bottom: 6px;">
                    <table><tr><td style="font-weight: bold;">{{ $edu['school'] ?: ($edu['degree'] ?? '') }}</td><td align="right">{{ implode(' – ', array_filter([$edu['start_date'] ?? '', $edu['end_date'] ?? ''])) }}</td></tr></table>
                    <div>{{ implode(' — ', array_filter([$edu['degree'] ?? '', $edu['details'] ?? ''])) }}</div>
                </div>
            @endforeach
        @endif
        @if(!empty($vis['skills']) && !empty($skills))
            <div style="font-size: 9pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.2px; border-bottom: 1px solid #94A3B8; padding-bottom: 2px; margin: 12px 0 6px 0;">Skills &amp; Additional Information</div>
            <div><strong>Core Competencies:</strong> {{ implode(', ', $skills) }}</div>
        @endif
    </div>

{{-- =======================================================================
     10. COMPACT ONE-PAGER
     ======================================================================= --}}
@else
    <div style="padding: 22px 28px;">
        <table style="border-bottom: 2px solid {{ $accent }}; margin-bottom: 10px;">
            <tr>
                <td style="padding-bottom: 8px;">
                    <div class="{{ $font === 'serif' ? 'serif' : '' }}" style="font-size: 18pt; font-weight: bold; color: #0F172A;">{{ $fullName }}</div>
                    <div style="font-size: 9.8pt; font-weight: bold; color: {{ $accent }};">{{ $p['job_title'] ?? '' }}</div>
                </td>
                <td align="right" valign="bottom" style="padding-bottom: 8px; font-size: 8pt; font-weight: bold; text-transform: uppercase; color: #64748B;">One-Page Executive Sheet</td>
            </tr>
        </table>
        <table style="background-color: #F8FAFC; border: 1px solid #CBD5E1; margin-bottom: 12px;">
            <tr>
                <td style="width: 34%; padding: 6px 10px; font-size: 8.5pt;"><strong>Email:</strong> {{ $p['email'] ?? '' }}</td>
                <td align="center" style="width: 33%; padding: 6px 10px; font-size: 8.5pt;"><strong>Phone:</strong> {{ $p['phone'] ?? '' }}</td>
                <td align="right" style="width: 33%; padding: 6px 10px; font-size: 8.5pt;"><strong>Loc:</strong> {{ $p['location'] ?? '' }}</td>
            </tr>
        </table>
        @if(!empty($vis['summary']) && !empty($c['summary']))
            <div style="background-color: {{ $accentLight }}; border: 1px solid {{ $accentMid }}; border-radius: 5px; padding: 9px 11px; margin-bottom: 12px; font-size: 9pt;">{{ $c['summary'] }}</div>
        @endif
        @if(!empty($vis['experience']) && !empty($exps))
            <div style="font-size: 8.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.4px; color: {{ $accent }}; margin-bottom: 6px;">Experience</div>
            @foreach($exps as $exp)
                @continue(empty($exp['role']) && empty($exp['company']))
                <div style="margin-bottom: 9px;">
                    <table><tr><td style="font-weight: bold; font-size: 9.5pt;">{{ $exp['role'] ?? '' }} — <span style="color: {{ $accent }}">{{ $exp['company'] ?? '' }}</span></td><td align="right" style="font-size: 8.2pt; color: #64748B;">{{ implode(' – ', array_filter([$exp['start_date'] ?? '', $exp['end_date'] ?? ''])) }}</td></tr></table>
                    <ul>@foreach($parseBullets($exp['bullets_text'] ?? '') as $line)<li>{{ $line }}</li>@endforeach</ul>
                </div>
            @endforeach
        @endif
        <table style="border-top: 1px solid #CBD5E1; margin-top: 10px; padding-top: 10px;">
            <tr>
                <td valign="top" style="width: 50%; padding: 10px 10px 0 0;">
                    @if(!empty($vis['skills']) && !empty($skills))
                        <div style="font-size: 8.2pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.4px; color: {{ $accent }}; margin-bottom: 6px;">Skills &amp; Domain Expertise</div>
                        <div style="line-height: 1.8;">@foreach($skills as $sk)<span style="display: inline-block; background-color: #F1F5F9; padding: 2px 6px; border-radius: 3px; font-size: 8.2pt; font-weight: bold; margin: 0 3px 3px 0;">{{ $sk }}</span>@endforeach</div>
                    @endif
                </td>
                <td valign="top" style="width: 50%; padding: 10px 0 0 10px;">
                    @if(!empty($vis['education']) && !empty($edus))
                        <div style="font-size: 8.2pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.4px; color: {{ $accent }}; margin-bottom: 6px;">Education &amp; Credentials</div>
                        @foreach($edus as $edu)
                            @continue(empty($edu['degree']) && empty($edu['school']))
                            <div style="margin-bottom: 5px; font-size: 8.8pt;"><strong>{{ $edu['degree'] ?? '' }}</strong><div style="color: #475569;">{{ implode(' • ', array_filter([$edu['school'] ?? '', $edu['end_date'] ?? ''])) }}</div></div>
                        @endforeach
                    @endif
                </td>
            </tr>
        </table>
    </div>
@endif

</body>
</html>
