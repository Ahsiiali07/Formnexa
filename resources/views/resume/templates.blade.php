@extends('layouts.app')

@section('content')
<div x-data="resumeGalleryPage()" class="min-h-screen bg-white">

    {{-- Modern Electric Precision Hero Banner --}}
    <section class="relative overflow-hidden bg-[#f8f8f3] border-b border-border-subtle pt-7 sm:pt-10 pb-10 sm:pb-12 px-4 sm:px-6">
        <div class="max-w-[1280px] mx-auto">
            <div class="mb-4">
                <x-schema.breadcrumbs :items="[['Home', url('/')], ['Resume Builder', route('resume.builder')], ['Resume Templates', route('resume.templates')]]" />
                <x-schema.tool name="Free ATS CV Templates & Builder" description="Download {{ count($templates) }} best ATS CV templates in PDF and Word DOCX format. Built-in ATS CV checker, convert CV to ATS format for free." />
            </div>
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div class="max-w-3xl">
                    <div class="inline-flex flex-wrap items-center gap-2 px-3 py-1 rounded-full bg-white border border-border-subtle text-emerald-700 font-mono text-[11px] font-semibold uppercase tracking-wider mb-4 shadow-sm">
                        {{ count($templates) }} Modern &amp; ATS CV Templates &bull; {{ count($styles) }} Layouts &bull; PDF &amp; Word (DOCX)
                    </div>
                    <h1 class="font-display text-3xl sm:text-5xl font-extrabold tracking-[-0.03em] text-[#17211b] leading-[1.08]">
                        Free ATS CV Templates &bull; Best ATS Resume Templates for PDF &amp; Word
                    </h1>
                    <p class="mt-4 text-sm sm:text-lg text-[#536058] max-w-2xl leading-relaxed">
                        Download the best ATS CV templates and basic resume templates online for free. Convert your CV to ATS format in 60 seconds with our built-in ATS CV checker, or export clean vector PDFs and editable Word (.docx) files with zero sign-up or watermarks.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2.5 sm:gap-3 shrink-0">
                    <a href="{{ route('resume.builder', ['import' => 1]) }}" rel="nofollow"
                       class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-primary-container hover:bg-electric-blue-hover text-white text-sm font-bold shadow-sm transition">
                        <span>Convert CV to ATS Format Free &rarr;</span>
                    </a>
                    <a href="{{ route('resume.builder', ['blank' => 1]) }}" rel="nofollow"
                       class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-white border border-border-strong text-[#17211b] text-sm font-bold hover:border-[#17211b] transition">
                        <span>+ Start Blank ATS CV</span>
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

                            @include('resume._template_miniature', ['tpl' => $tpl])

                            {{-- Hover Action Veil --}}
                            <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/45 transition-all duration-200 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <div class="flex items-center gap-2.5 px-3">
                                    <button type="button"
                                            @click.stop="openPreview('{{ $tpl['slug'] }}')"
                                            class="px-3.5 py-2 rounded-xl bg-white text-slate-900 text-xs font-extrabold shadow-lg hover:bg-slate-50 transition">
                                        Live Preview
                                    </button>
                                    <a href="{{ route('resume.builder', ['template' => $tpl['slug']]) }}"
                                       rel="nofollow"
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
                                        <span class="text-[10px] font-semibold text-emerald-700">ATS Verified &bull; PDF &amp; Word</span>
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
                                   rel="nofollow"
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

    {{-- Educational Guide & Conversion Section for Top Keywords --}}
    <section class="border-t border-[#dfe4df] bg-[#f8f8f3] py-14 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto space-y-12">

            {{-- 3 Value Pillars for Target Keywords --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl border border-[#dfe4df] p-6 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg">&#9889;</div>
                    <h3 class="font-display text-lg font-extrabold text-[#17211b]">Convert CV to ATS Format Free</h3>
                    <p class="text-xs sm:text-sm text-[#536058] leading-relaxed">
                        Already have an existing CV in Word or PDF? Use our free parser to instantly restructure your experience, education, and skills into an ATS-compliant template in under 60 seconds.
                    </p>
                    <a href="{{ route('resume.builder', ['import' => 1]) }}" class="inline-flex items-center text-xs font-bold text-[#184f3a] hover:underline">
                        Convert My CV Now &rarr;
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-[#dfe4df] p-6 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-lg">&#128196;</div>
                    <h3 class="font-display text-lg font-extrabold text-[#17211b]">Word ATS CV Template &amp; PDF</h3>
                    <p class="text-xs sm:text-sm text-[#536058] leading-relaxed">
                        Need an editable Word ATS CV template? All templates export as crisp vector PDFs and can be converted to Word (.docx) format anytime with layout kept as closely as possible.
                    </p>
                    <a href="{{ route('convert.tool', 'pdf-to-word') }}" class="inline-flex items-center text-xs font-bold text-[#184f3a] hover:underline">
                        Word DOCX Converter &rarr;
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-[#dfe4df] p-6 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center font-bold text-lg">&#128269;</div>
                    <h3 class="font-display text-lg font-extrabold text-[#17211b]">Real-Time ATS CV Checker</h3>
                    <p class="text-xs sm:text-sm text-[#536058] leading-relaxed">
                        Test your resume structure in real time. Our live builder validates heading hierarchy, bullet readability, and keyword density for common screening software.
                    </p>
                    <a href="{{ route('resume.builder') }}" class="inline-flex items-center text-xs font-bold text-[#184f3a] hover:underline">
                        Test in Resume Builder &rarr;
                    </a>
                </div>
            </div>

            {{-- What makes the Best ATS CV Template --}}
            <div class="bg-white rounded-[26px] border border-[#dfe4df] p-6 sm:p-10 shadow-sm grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-[#e8f7ed] border border-[#c8f3dc] text-[#184f3a] text-xs font-mono font-bold uppercase tracking-wider">
                        ATS Optimization Benchmark
                    </div>
                    <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] tracking-tight">
                        What is the best ATS CV template?
                    </h2>
                    <p class="text-xs sm:text-sm text-[#536058] leading-relaxed">
                        The <strong>best ATS CV template</strong> uses a predictable, linear structure with standard typography (such as Inter, Arial, or Calibri) and 100% selectable text. Unlike Canva or graphical Photoshop templates that confuse Applicant Tracking Systems with nested tables, floating text boxes, and image-based skill meters, Formnexa templates ensure your credentials parse accurately into recruiter databases.
                    </p>
                    <ul class="space-y-2 text-xs sm:text-sm text-[#17211b] font-medium">
                        <li class="flex items-center gap-2">
                            <span class="w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px]">&check;</span>
                            <span>Standard headings: Summary, Experience, Education, Skills, and Projects.</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px]">&check;</span>
                            <span>Reverse-chronological timeline with clear employment dates (MM/YYYY).</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px]">&check;</span>
                            <span>Direct free ATS CV template download in vector PDF and editable Word (DOCX).</span>
                        </li>
                    </ul>
                </div>

                {{-- Mini comparison table --}}
                <div class="rounded-2xl border border-[#dfe4df] overflow-hidden bg-[#fafaf8] text-xs">
                    <div class="p-3.5 bg-[#17211b] text-white font-bold flex justify-between items-center">
                        <span>ATS CV Format Comparison</span>
                        <span class="text-emerald-400 font-mono text-[11px]">Structured Layout</span>
                    </div>
                    <div class="divide-y divide-[#dfe4df]">
                        <div class="p-3 grid grid-cols-2 gap-2">
                            <span class="font-bold text-slate-700">Formnexa ATS Templates</span>
                            <span class="text-emerald-700 font-semibold">&check; 100% Machine Readable</span>
                        </div>
                        <div class="p-3 grid grid-cols-2 gap-2">
                            <span class="font-bold text-slate-700">Word ATS CV Template (.docx)</span>
                            <span class="text-emerald-700 font-semibold">&check; Fully Supported via Converter</span>
                        </div>
                        <div class="p-3 grid grid-cols-2 gap-2">
                            <span class="font-bold text-slate-700">Free ATS CV Download</span>
                            <span class="text-emerald-700 font-semibold">&check; Instant Watermark-Free PDF</span>
                        </div>
                        <div class="p-3 grid grid-cols-2 gap-2">
                            <span class="font-bold text-slate-700">Convert CV to ATS Free</span>
                            <span class="text-emerald-700 font-semibold">&check; Built-in 60s Parser</span>
                        </div>
                        <div class="p-3 grid grid-cols-2 gap-2">
                            <span class="font-bold text-slate-700">Canva / Photoshop Resumes</span>
                            <span class="text-rose-600 font-semibold">&cross; Frequently Fail Parsers</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FAQ Section with Automatic Schema.org FAQPage --}}
            <div class="bg-white rounded-[26px] border border-[#dfe4df] p-6 sm:p-10 shadow-sm">
                <x-faq :items="[
                    [
                        'q' => 'What is the best ATS CV template?',
                        'a' => 'The best ATS CV template is a clean, single-column or linear layout that uses standard system typography, reverse-chronological experience dates, and clear headings (Summary, Experience, Education, Skills). Formnexa\'s \'Wall Street Classic ATS\', \'Atlantic Flow\', and \'Linear Founding Engineer\' templates are engineered with clean single-column structure for enterprise ATS checkers.'
                    ],
                    [
                        'q' => 'Where can I find a basic resume template that passes ATS screening?',
                        'a' => 'Formnexa provides classic basic resume templates—including our Wall Street Double-Rule, Atlantic Flow, and Zurich Swiss designs—engineered with single-column linear layout, standard system fonts, and zero confusing graphic bars. These basic resume templates download directly as watermark-free PDFs or editable Word DOCX files.'
                    ],
                    [
                        'q' => 'What is the ATS CV scale and how does scoring work?',
                        'a' => 'The ATS CV scale is a match percentage from 0% to 100% computed by candidate screening algorithms (like Workday, Taleo, and Greenhouse). Resumes scoring 80%+ generally pass the preliminary automated gate and reach human recruiters. Formnexa ATS CV templates are structured for high readability on the ATS scale by preserving selectable text, correct heading tags, standard date formats, and clean keyword placement.'
                    ],
                    [
                        'q' => 'How do I convert my CV to an ATS format for free?',
                        'a' => 'To convert your CV to an ATS format for free on Formnexa: 1) Click \'Convert CV to ATS Format Free\' at the top of the page, 2) Upload your current PDF or Word (.docx) resume or paste raw text, and 3) Formnexa automatically extracts your contact details, work history, and competencies into an ATS-friendly layout. You can then download an ATS-compliant PDF with zero watermark.'
                    ],
                    [
                        'q' => 'Can I download an ATS CV template in Word (DOCX) and PDF?',
                        'a' => 'Yes. Every ATS CV template on Formnexa exports as a crisp, vector A4 PDF. If your recruiter specifically requests a Word ATS CV template (.docx), simply use our integrated free PDF to Word tool to convert your CV into an editable Microsoft Word document while keeping layout as closely as possible.'
                    ],
                    [
                        'q' => 'Does Formnexa have a free ATS CV checker?',
                        'a' => 'Yes. Formnexa\'s builder incorporates a real-time ATS CV checker that verifies heading compliance, bullet structure, date formatting, and keyword density as you type. It alerts you if formatting elements might trigger parsing errors in corporate applicant tracking software.'
                    ],
                    [
                        'q' => 'Are Formnexa\'s free ATS CV template downloads really free with no watermark?',
                        'a' => 'Yes, 100%. All ' . count($templates) . ' modern and ATS CV templates are completely free to customize and download. There are no watermarks, no paywalls, and no credit card or account registration required.'
                    ],
                    [
                        'q' => 'What is the difference between a CV ATS and a traditional graphic resume?',
                        'a' => 'A CV ATS is formatted with linear reading order and selectable vector text that ATS bots can parse into applicant fields like \'Job Title\' and \'Years of Experience\'. Graphic resumes built in design software often place text inside multi-layer frames, floating text boxes, or graphic icons that bot parsers fail to read, causing immediate automatic rejection.'
                    ],
                ]" heading="Free ATS CV Templates — Frequently Asked Questions" eyebrow="ATS CV Guide &amp; FAQ" />
            </div>

            <x-related-tools :tools="[
                [
                    'title' => 'Free ATS Resume Checker',
                    'description' => 'Upload your resume and check your 0-100 compatibility score and keyword coverage before applying.',
                    'url' => route('resume.checker'),
                    'badge' => 'Free Scan',
                    'cta' => 'Check ATS Score',
                ],
                [
                    'title' => 'ATS Resume Builder',
                    'description' => 'Customize any template live in your browser and export watermark-free PDF or Word files.',
                    'url' => route('resume.builder'),
                    'badge' => 'Free Studio',
                    'cta' => 'Open Resume Builder',
                ],
                [
                    'title' => 'ATS Resume Converter',
                    'description' => 'Already have a PDF or DOCX resume? Parse and rebuild it into any single-column layout.',
                    'url' => route('resume.ats_converter'),
                    'badge' => 'PDF & Word',
                    'cta' => 'Convert Existing CV',
                ],
            ]" heading="Next Steps for Your Resume" eyebrow="Career Tools" />

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

                    {{-- Switch Layout Engine ({{ count($styles) }} Styles) --}}
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Layout Engine ({{ count($styles) }})</label>
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
                    <a href="{{ route('resume.builder') }}"
                       :href="buildLaunchUrl(false)"
                       rel="nofollow"
                       class="block w-full text-center py-3 rounded-xl bg-[#184f3a] text-white text-sm font-extrabold shadow-sm hover:bg-[#123e2d] transition">
                        Use This Design &rarr;
                    </a>
                    <a href="{{ route('resume.builder', ['import' => 1]) }}"
                       :href="buildLaunchUrl(true)"
                       rel="nofollow"
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
