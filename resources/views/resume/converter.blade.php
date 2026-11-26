@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">

    {{-- Breadcrumbs & Schema --}}
    <div class="max-w-[1180px] mx-auto px-4 sm:px-6 pt-6 sm:pt-8">
        <x-schema.breadcrumbs :items="[
            ['Home', url('/')],
            ['Resume Builder', route('resume.builder')],
            ['Convert CV to ATS Format', route('resume.ats_converter')],
        ]" />
        <x-schema.tool
            name="ATS Resume Converter"
            description="Upload your PDF or Word CV and rebuild it in a clean, ATS-friendly layout. Edit online and download a free PDF. No watermark."
            category="BusinessApplication" />
    </div>

    {{-- Hero & Converter Tool Area --}}
    <section class="max-w-[1180px] mx-auto px-4 sm:px-6 pt-6 sm:pt-8 pb-14 sm:pb-20">
        <div class="text-center max-w-3xl mx-auto space-y-3.5 mb-10">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#e8f7ed] border border-[#c8f3dc] text-[#184f3a] font-mono text-[11px] font-bold uppercase tracking-wider">
                Free Online CV Parser &amp; Rebuilder
            </span>
            <h1 class="font-display text-3xl sm:text-5xl font-extrabold text-[#17211b] tracking-[-0.04em] leading-[1.08]">
                {{ request()->is('convert/pdf-to-ats') ? 'PDF to ATS Converter' : 'Convert Your CV to ATS Format for Free' }}
            </h1>
            <p class="text-sm sm:text-base text-[#536058] leading-relaxed max-w-2xl mx-auto">
                Upload your existing PDF or Word CV—or paste plain text—to automatically parse your sections into a clean, single-column ATS layout you can edit online and download with zero watermark. Convert a PDF resume or Word resume to ATS format in a few minutes.
            </p>
        </div>

        {{-- Interactive ATS Converter Card --}}
        <div x-data="atsConverter()" class="max-w-3xl mx-auto bg-white rounded-3xl border border-[#dfe4df] shadow-xl shadow-[#17211b]/5 p-6 sm:p-9 space-y-6">
            
            {{-- Mode Switcher Tabs --}}
            <div class="flex items-center justify-between flex-wrap gap-3 pb-2 border-b border-[#dfe4df]">
                <div class="inline-flex p-1 bg-[#f4f7f5] rounded-xl border border-[#dfe4df]">
                    <button type="button" @click="mode = 'file'"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition cursor-pointer"
                            :class="mode === 'file' ? 'bg-white text-[#17211b] shadow-sm' : 'text-[#68736d] hover:text-[#17211b]'">
                        Upload PDF or Word File
                    </button>
                    <button type="button" @click="mode = 'text'"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition cursor-pointer"
                            :class="mode === 'text' ? 'bg-white text-[#17211b] shadow-sm' : 'text-[#68736d] hover:text-[#17211b]'">
                        Paste Plain Text
                    </button>
                </div>

                <div class="flex items-center gap-2 text-xs text-[#68736d]">
                    <span class="w-2 h-2 rounded-full bg-[#184f3a]"></span>
                    <span>100% Free &bull; No Sign-Up</span>
                </div>
            </div>

            {{-- Mode 1: File Upload Dropzone --}}
            <div x-show="mode === 'file'" class="space-y-3">
                <label class="block p-8 sm:p-12 rounded-2xl border-2 border-dashed border-[#c6d1ca] hover:border-[#184f3a] bg-[#fbfbf7] hover:bg-[#e8f7ed]/30 text-center cursor-pointer transition group">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-white border border-[#dfe4df] flex items-center justify-center text-[#184f3a] group-hover:scale-105 transition-transform shadow-sm">
                        <x-icon name="upload" class="w-6 h-6" />
                    </div>
                    <div class="text-sm sm:text-base font-bold text-[#17211b]" x-text="fileName || 'Click to select or drag & drop your CV file'"></div>
                    <p class="text-xs text-[#68736d] mt-1.5" x-show="!fileName">
                        Supports PDF (.pdf), Word (.docx), Plain Text (.txt), or JSON Resume (.json) up to 5MB
                    </p>
                    <p class="text-xs text-[#184f3a] font-semibold mt-1.5" x-show="fileName" x-text="'Selected file size: ' + fileSize"></p>
                    <input type="file" @change="onFileSelect($event)" accept=".pdf,.docx,.txt,.md,.json" class="hidden">
                </label>
            </div>

            {{-- Mode 2: Paste Text Area --}}
            <div x-show="mode === 'text'" x-cloak class="space-y-2.5">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-[#17211b] uppercase tracking-wider">Paste your CV or LinkedIn profile text</label>
                    <button type="button" @click="fillSample()" class="text-xs font-bold text-[#184f3a] hover:underline cursor-pointer">
                        Insert Sample CV Text
                    </button>
                </div>
                <textarea x-model="rawText" @input="markStarted()" rows="8"
                          placeholder="Paste your resume content here (Name, Contact Details, Summary, Work History, Education, Skills)..."
                          class="w-full p-4 rounded-2xl bg-[#fbfbf7] border border-[#dfe4df] text-xs sm:text-sm text-[#17211b] leading-relaxed focus:bg-white focus:border-[#184f3a] focus:ring-1 focus:ring-[#184f3a] focus:outline-none"></textarea>
            </div>

            {{-- Target Template Selector --}}
            <div class="p-4 rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <label for="targetStyleSelect" class="text-xs font-bold text-[#17211b] block">Target ATS Layout Theme</label>
                    <span class="text-[11px] text-[#68736d]">Rebuilds your parsed information into this structure:</span>
                </div>
                <select id="targetStyleSelect" x-model="targetStyle" @change="onStyleChange($event)" class="px-3.5 py-2 rounded-xl bg-white border border-[#c6d1ca] font-semibold text-xs text-[#17211b] focus:border-[#184f3a] focus:outline-none">
                    <option value="ats_classic">Wall Street ATS Classic (100% Linear Single-Column)</option>
                    <option value="ribbon_centered">Classic Top-Band Centered (ResumeBuilder Pro)</option>
                    <option value="banner_split_date">Colored Banner &amp; Split Dates (ResumeBuilder Pro)</option>
                    <option value="bento_modern">Bento Modular Grid (2026 Modern)</option>
                    <option value="stripe_clean">Precision Top-Band (Modern Minimal)</option>
                    <option value="aurora_pill">Aurora Pill &amp; Bar (Modern Pro)</option>
                    <option value="obsidian_header">Obsidian Header Grid (Dark Masthead)</option>
                    <option value="minimal_swiss">Zurich Swiss Grid (Linear Hierarchy)</option>
                    <option value="tech_stack">Silicon Valley Stack (Engineering Monospace)</option>
                    <option value="compact_onepage">Hyper-Compact One-Pager (High Density)</option>
                    <option value="flow_sidebar">Linear Left-Rail (Clean ATS-Safe)</option>
                </select>
            </div>

            {{-- Error Message Display --}}
            <div x-show="errorMessage" x-cloak class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium flex items-start gap-2">
                <span class="font-bold shrink-0">&bull;</span>
                <span x-text="errorMessage"></span>
            </div>

            {{-- CTA Submit Button --}}
            <button type="button" @click="convert()" :disabled="converting"
                    class="w-full py-4 rounded-xl bg-[#17211b] hover:bg-[#184f3a] disabled:bg-[#a7bab0] text-white font-bold text-sm sm:text-base transition-colors flex items-center justify-center gap-2 shadow-sm cursor-pointer">
                <template x-if="!converting">
                    <span class="inline-flex items-center gap-2">
                        <span>Convert CV to ATS Format Free</span>
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </span>
                </template>
                <template x-if="converting">
                    <span class="inline-flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Extracting &amp; Rebuilding in ATS Layout...</span>
                    </span>
                </template>
            </button>

            {{-- Trust & Feature Highlights --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 text-center text-[11px] text-[#68736d]">
                <div class="p-2 rounded-xl bg-[#fbfbf7] border border-[#dfe4df]">
                    <strong class="block text-[#17211b]">No Sign-Up</strong>
                    <span>Guest mode enabled</span>
                </div>
                <div class="p-2 rounded-xl bg-[#fbfbf7] border border-[#dfe4df]">
                    <strong class="block text-[#17211b]">Zero Watermarks</strong>
                    <span>Clean output</span>
                </div>
                <div class="p-2 rounded-xl bg-[#fbfbf7] border border-[#dfe4df]">
                    <strong class="block text-[#17211b]">Selectable Text</strong>
                    <span>100% Vector PDF</span>
                </div>
                <div class="p-2 rounded-xl bg-[#fbfbf7] border border-[#dfe4df]">
                    <strong class="block text-[#17211b]">Live Studio</strong>
                    <span>Edit before export</span>
                </div>
            </div>
        </div>
    </section>

    {{-- In-Depth Editorial Guide & Educational Context --}}
    <section class="max-w-[1180px] mx-auto px-4 sm:px-6 pb-20 space-y-12">

        {{-- 3-Step Workflow --}}
        <div class="bg-[#fbfbf7] rounded-3xl border border-[#dfe4df] p-6 sm:p-10 space-y-8">
            <div class="max-w-2xl">
                <span class="text-xs font-mono font-bold uppercase tracking-wider text-[#184f3a]">Simple 3-Step Process</span>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] mt-1.5">
                    How Formnexa converts your CV into an ATS-friendly layout
                </h2>
                <p class="text-xs sm:text-sm text-[#536058] mt-2 leading-relaxed">
                    Many applicant tracking systems read a CV line by line and ignore visual styling. Formnexa eliminates formatting traps while preserving your career details.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-[#dfe4df] space-y-3 shadow-xs">
                    <div class="w-8 h-8 rounded-lg bg-[#e8f7ed] text-[#184f3a] font-bold text-xs flex items-center justify-center font-mono">01</div>
                    <h3 class="font-bold text-[#17211b] text-sm sm:text-base">Upload or Paste Your CV</h3>
                    <p class="text-xs text-[#536058] leading-relaxed">
                        Provide your current resume in PDF, Word (.docx), or plain text. Uploaded files are processed in temporary memory to extract text and are deleted immediately after parsing. Your parsed resume text is saved to your session so you can edit and download your PDF. See our <a href="{{ route('privacy') }}" class="text-[#184f3a] font-bold hover:underline">Privacy Policy</a>.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-[#dfe4df] space-y-3 shadow-xs">
                    <div class="w-8 h-8 rounded-lg bg-[#e8f7ed] text-[#184f3a] font-bold text-xs flex items-center justify-center font-mono">02</div>
                    <h3 class="font-bold text-[#17211b] text-sm sm:text-base">Rebuilt in a Single-Column Layout</h3>
                    <p class="text-xs text-[#536058] leading-relaxed">
                        Your career data is reorganized into standard chronological hierarchy with machine-readable headings. Multi-column tables, floating text boxes, and unreadable graphics are converted into clean, linear sections.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-[#dfe4df] space-y-3 shadow-xs">
                    <div class="w-8 h-8 rounded-lg bg-[#e8f7ed] text-[#184f3a] font-bold text-xs flex items-center justify-center font-mono">03</div>
                    <h3 class="font-bold text-[#17211b] text-sm sm:text-base">Review, Edit &amp; Export Free</h3>
                    <p class="text-xs text-[#536058] leading-relaxed">
                        Fine-tune your bullet points, adjust font density, or switch template styles in Formnexa's live builder. When satisfied, download an instant, watermark-free vector PDF ready for job applications.
                    </p>
                </div>
            </div>
        </div>

        {{-- ATS Compliance Rules & Manual Check Advice --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- What makes a CV ATS-friendly --}}
            <div class="bg-white rounded-3xl border border-[#dfe4df] p-6 sm:p-9 space-y-4">
                <span class="text-xs font-mono font-bold uppercase tracking-wider text-[#184f3a]">Design Standards</span>
                <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b]">
                    What makes a CV truly ATS-friendly?
                </h2>
                <p class="text-xs sm:text-sm text-[#536058] leading-relaxed">
                    Automated hiring software often reads documents from left to right and top to bottom. Four foundational layout rules ensure your resume passes algorithmic screening:
                </p>
                <ul class="space-y-3 text-xs sm:text-sm text-[#46534b] pt-1">
                    <li class="flex items-start gap-2.5">
                        <span class="text-[#184f3a] font-bold shrink-0">&check;</span>
                        <span><strong>Single-Column Flow:</strong> Multi-column sidebars frequently cause parser bots to merge left and right text lines, corrupting job titles and company dates.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-[#184f3a] font-bold shrink-0">&check;</span>
                        <span><strong>Standard Section Headings:</strong> Bots search for standard terminology: "Experience", "Education", "Skills", and "Summary". Creative titles like "My Journey" may not be recognized.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-[#184f3a] font-bold shrink-0">&check;</span>
                        <span><strong>No Text Inside Graphics or Tables:</strong> Rating meters, skill bars, icons, and nested tables cannot be extracted by search bots and appear blank in recruiter applicant summaries.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-[#184f3a] font-bold shrink-0">&check;</span>
                        <span><strong>Selectable Vector Typography:</strong> Text must be embedded as selectable characters, never rasterized into a flat image or scanned photo.</span>
                    </li>
                </ul>
            </div>

            {{-- What may need a manual check --}}
            <div class="bg-white rounded-3xl border border-[#dfe4df] p-6 sm:p-9 space-y-4">
                <span class="text-xs font-mono font-bold uppercase tracking-wider text-[#b45309]">Review Checklist</span>
                <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b]">
                    What to check manually after conversion
                </h2>
                <p class="text-xs sm:text-sm text-[#536058] leading-relaxed">
                    Most CVs convert cleanly, but always review the result in the editor before you download:
                </p>
                <ul class="space-y-3 text-xs sm:text-sm text-[#46534b] pt-1">
                    <li class="flex items-start gap-2.5">
                        <span class="text-[#b45309] font-bold shrink-0">&bull;</span>
                        <span><strong>Employment Dates &amp; Roles:</strong> If your previous document omitted company names or used informal date ranges (e.g., "Fall 2021"), verify that start and end dates align with each position.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-[#b45309] font-bold shrink-0">&bull;</span>
                        <span><strong>Extracted Skills List:</strong> If your original resume displayed skill proficiencies with star icons or progress bars, verify that every important competency appears as clean text.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-[#b45309] font-bold shrink-0">&bull;</span>
                        <span><strong>Scanned PDFs:</strong> Scanned PDFs (photos or images of paper) usually cannot be read because this tool does not run OCR. Paste your CV text directly or upload a file with selectable text.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-[#b45309] font-bold shrink-0">&bull;</span>
                        <span><strong>Bullet Point Action Verbs:</strong> Verify that each accomplishment begins with a strong active verb and includes quantifiable outcomes (percentages, revenue, team scale).</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- FAQ Component --}}
        <div class="bg-white rounded-3xl border border-[#dfe4df] p-6 sm:p-10 shadow-sm">
            <x-faq :items="[
                [
                    'q' => 'How does this free ATS CV converter work?',
                    'a' => 'You upload your existing PDF or Word (.docx) resume, or paste its text. Formnexa extracts your personal info, work experience, education, and skills, then maps them into a clean, single-column ATS layout that screening bots can parse accurately.'
                ],
                [
                    'q' => 'Will converting my CV change my wording or experience?',
                    'a' => 'Formnexa keeps your original wording, but parsing is automatic, so check every section before you download.'
                ],
                [
                    'q' => 'Is my CV stored?',
                    'a' => 'Uploaded files (PDF or DOCX) are processed in temporary memory and deleted immediately after text extraction. The extracted resume content is saved to your browser session (or your account if logged in) so you can edit and download your document. We never sell, share, or advertise with your data. See our <a href=\"' . route('privacy') . '\" class=\"text-[#184f3a] font-bold hover:underline\">Privacy Policy</a>.'
                ],
                [
                    'q' => 'Is this ATS converter truly free with no watermark?',
                    'a' => 'Yes. You can convert your CV, edit all sections in our live builder, and download a print-ready vector PDF completely free with zero watermark and no mandatory account sign-up.'
                ],
                [
                    'q' => 'Which ATS template layout should I choose?',
                    'a' => 'Single-column layouts such as Wall Street ATS Classic are generally the safest choice for automated screening.'
                ],
                [
                    'q' => 'Can I edit my resume after conversion?',
                    'a' => 'Yes. Upon conversion, your CV opens directly in Formnexa\'s live resume editor. You can reorder bullet points, add certifications, adjust typography, and switch template themes before downloading.'
                ],
                [
                    'q' => 'Can I convert my resume back to Word (DOCX) later?',
                    'a' => 'Yes. After exporting your vector PDF, you can use Formnexa\'s free <a href=\"' . route('convert.tool', 'pdf-to-word') . '\" class=\"text-[#184f3a] font-bold hover:underline\">PDF to Word tool</a> to generate an editable Microsoft Word (.docx) version whenever needed.'
                ],
            ]" heading="ATS Resume Converter — Frequently Asked Questions" />
        </div>

        {{-- Related Tools & Guides Cross-Navigation --}}
        <div class="bg-[#fbfbf7] rounded-3xl border border-[#dfe4df] p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-1.5 text-center md:text-left">
                <h3 class="font-bold text-[#17211b] text-base">Explore companion resume tools &amp; guides</h3>
                <p class="text-xs text-[#536058]">Explore our full gallery of CV templates, document converters, or read our in-depth guides.</p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-2.5 shrink-0">
                <a href="{{ route('resume.checker') }}" class="px-4 py-2 rounded-xl bg-[#e8f7ed] border border-[#c8f3dc] hover:border-[#184f3a] text-xs font-bold text-[#184f3a] transition">
                    Free ATS Resume Checker &rarr;
                </a>
                <a href="{{ route('resume.templates') }}" class="px-4 py-2 rounded-xl bg-white border border-[#dfe4df] hover:border-[#184f3a] text-xs font-bold text-[#17211b] transition">
                    Browse {{ count($templates) }} CV Templates &rarr;
                </a>
                <a href="{{ route('resume.builder') }}" class="px-4 py-2 rounded-xl bg-white border border-[#dfe4df] hover:border-[#184f3a] text-xs font-bold text-[#17211b] transition">
                    Live Resume Studio &rarr;
                </a>
                <a href="{{ route('convert.tool', 'pdf-to-word') }}" class="px-4 py-2 rounded-xl bg-white border border-[#dfe4df] hover:border-[#184f3a] text-xs font-bold text-[#17211b] transition">
                    PDF to Word &rarr;
                </a>
                <a href="{{ route('convert.tool', 'word-to-pdf') }}" class="px-4 py-2 rounded-xl bg-white border border-[#dfe4df] hover:border-[#184f3a] text-xs font-bold text-[#17211b] transition">
                    Word to PDF &rarr;
                </a>
                <a href="{{ route('blog.show', 'convert-cv-to-ats-format-free-guide') }}" class="px-4 py-2 rounded-xl bg-[#17211b] hover:bg-[#184f3a] text-white text-xs font-bold transition">
                    Read Step-by-Step ATS Guide &rarr;
                </a>
            </div>
        </div>

    </section>
</div>
@endsection

@push('scripts')
<script>
function atsConverter() {
    return {
        mode: 'file',
        file: null,
        fileName: '',
        fileSize: '',
        rawText: '',
        targetStyle: 'ats_classic',
        converting: false,
        errorMessage: '',
        toolStartedTracked: false,

        markStarted() {
            if (!this.toolStartedTracked) {
                this.toolStartedTracked = true;
                if (typeof window.track === 'function') {
                    window.track('tool_started', { tool_name: 'ats_resume_converter' });
                }
            }
        },

        onStyleChange(event) {
            const sel = event.target;
            const styleName = sel?.options[sel.selectedIndex]?.text || this.targetStyle;
            if (typeof window.track === 'function') {
                window.track('template_selected', { template_name: styleName });
            }
        },

        onFileSelect(event) {
            const f = event.target.files[0];
            if (!f) return;
            if (f.size > 5 * 1024 * 1024) {
                this.errorMessage = 'File exceeds the 5MB limit. Please choose a smaller file or paste plain text.';
                return;
            }
            this.markStarted();
            this.file = f;
            this.fileName = f.name;
            this.fileSize = (f.size / 1024).toFixed(1) + ' KB';
            this.errorMessage = '';

            const fileExt = (f.name.split('.').pop() || 'pdf').toLowerCase();
            if (typeof window.track === 'function') {
                window.track('file_uploaded', { tool_name: 'ats_resume_converter', file_type: fileExt });
            }
        },

        fillSample() {
            this.markStarted();
            this.mode = 'text';
            this.rawText = `Jordan Michaels
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
            this.errorMessage = '';
        },

        async convert() {
            this.errorMessage = '';
            if (this.mode === 'file' && !this.file) {
                this.errorMessage = 'Please select a PDF, DOCX, TXT, or JSON resume file first.';
                return;
            }
            if (this.mode === 'text' && !this.rawText.trim()) {
                this.errorMessage = 'Please paste your CV text or click "Insert Sample CV Text".';
                return;
            }

            this.converting = true;
            try {
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

                let importResponse;
                const importEndpoint = [window.location.origin, 'api', 'resumes', 'import'].join('/');
                if (this.mode === 'file') {
                    const fd = new FormData();
                    fd.append('cv_file', this.file);
                    importResponse = await fetch(importEndpoint, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: fd,
                    });
                } else {
                    importResponse = await fetch(importEndpoint, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify({ cv_text: this.rawText }),
                    });
                }

                const data = await importResponse.json();
                if (!importResponse.ok || !data.success) {
                    this.errorMessage = data.message || 'Could not extract text from this CV. If this is a scanned image PDF, please paste your CV text instead.';
                    this.converting = false;
                    return;
                }

                if (typeof window.track === 'function') {
                    window.track('conversion_completed', { tool_name: 'ats_resume_converter' });
                }

                const parsed = data.parsed;
                const chosenStyle = this.targetStyle || 'ats_classic';
                const resumeTitle = (parsed.personal && parsed.personal.full_name)
                    ? parsed.personal.full_name + ' ATS Resume'
                    : 'ATS Resume';

                // Save via POST /api/resumes to register session/guest UUID
                let redirectUrl = '{{ route("resume.builder") }}?converted=1';
                try {
                    const saveEndpoint = [window.location.origin, 'api', 'resumes'].join('/');
                    const saveResponse = await fetch(saveEndpoint, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify({
                            title: resumeTitle,
                            template_style: chosenStyle,
                            accent_color: '#0F172A',
                            font_family: 'sans',
                            content: parsed,
                        }),
                    });

                    if (saveResponse.ok) {
                        const saveData = await saveResponse.json();
                        if (saveData.uuid) {
                            redirectUrl = '{{ route("resume.builder") }}?load=' + encodeURIComponent(saveData.uuid) + '&converted=1';
                        }
                    }
                } catch (saveErr) {
                    console.warn('API save fallback to localStorage:', saveErr);
                }

                // Also populate local draft storage as a robust fallback
                localStorage.setItem('formnexa_resume_draft', JSON.stringify({
                    title: resumeTitle,
                    template_style: chosenStyle,
                    accent_color: '#0F172A',
                    font_family: 'sans',
                    density: 'comfortable',
                    photo_data: '',
                    hasCustomCv: true,
                    content: parsed,
                }));

                window.location.href = redirectUrl;
            } catch (err) {
                this.errorMessage = 'Network error during conversion. Please check your connection and try again.';
                this.converting = false;
            }
        }
    };
}
</script>
@endpush
