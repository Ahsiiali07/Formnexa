@extends('layouts.app')

<x-schema.tool
    name="Free Resignation Letter & Two Weeks Notice Generator"
    description="Write a polite resignation letter in under a minute. Pick your tone and last day, then download PDF or Word. Free, no watermark."
    category="BusinessApplication"
/>

@section('content')
<div class="min-h-screen bg-[#fafbfa] text-[#17211b] py-8 sm:py-12"
     x-data="resignationStudio('new_job', 'grateful', 14)"
     x-cloak>

    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        {{-- Visible Breadcrumbs + BreadcrumbList JSON-LD --}}
        <div class="mb-5 flex justify-center">
            <x-schema.breadcrumbs :items="[
                ['Home', url('/')],
                ['Career Tools', route('resume.builder')],
                ['Resignation Letter Generator', route('letter.resignation')],
            ]" />
        </div>

        {{-- Top Badge --}}
        <div class="flex items-center justify-center gap-2 mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e8f7ed] text-[#184f3a] border border-[#c8f3dc]">
                <span class="w-2 h-2 rounded-full bg-[#184f3a]"></span>
                100% Free &bull; Instant PDF &amp; Word Download &bull; Zero Watermark
            </span>
        </div>

        {{-- Header Copy --}}
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black font-display tracking-tight text-[#17211b] mb-3">
                Free Resignation Letter &amp; Two Weeks Notice Generator
            </h1>
            <p class="text-base sm:text-lg text-[#55635a] leading-relaxed">
                Write a polite resignation letter in under a minute. Pick your reason, tone, and last working day, edit any line in the live preview, and download a PDF or Word document with zero watermarks.
            </p>
        </div>

        {{-- Reusable Interactive Two-Column Studio --}}
        @include('letters._studio-widget', [
            'presetReason' => 'new_job',
            'presetTone' => 'grateful',
            'presetDays' => 14,
        ])

        {{-- EDUCATIONAL CONTENT SECTIONS 1-7 --}}
        <div class="mt-16 pt-12 border-t border-[#e2e8e3] space-y-12">

            {{-- 1. How to Use the Generator (4 Short Steps Matching the Form) --}}
            <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm">
                <span class="text-[11px] font-bold uppercase tracking-[0.14em] text-[#184f3a] block mb-1.5">How It Works</span>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] tracking-tight mb-6">
                    How to Write Your Resignation Letter in 4 Steps
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-4">
                        <div class="text-xs font-mono font-bold text-[#184f3a] mb-1">Step 1</div>
                        <h3 class="font-bold text-sm text-[#17211b] mb-1.5">Enter Your Information</h3>
                        <p class="text-xs text-[#536058] leading-relaxed">Fill in your full name, current job title, and optional personal email, phone, and city.</p>
                    </div>
                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-4">
                        <div class="text-xs font-mono font-bold text-[#184f3a] mb-1">Step 2</div>
                        <h3 class="font-bold text-sm text-[#17211b] mb-1.5">Add Employer Details</h3>
                        <p class="text-xs text-[#536058] leading-relaxed">Enter your manager&rsquo;s name, recipient title, and your company name for the formal salutation block.</p>
                    </div>
                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-4">
                        <div class="text-xs font-mono font-bold text-[#184f3a] mb-1">Step 3</div>
                        <h3 class="font-bold text-sm text-[#17211b] mb-1.5">Pick Reason, Tone &amp; Last Day</h3>
                        <p class="text-xs text-[#536058] leading-relaxed">Select from 8 reasons and 4 tones, click +2 Weeks or type your final day, and toggle handover options.</p>
                    </div>
                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-4">
                        <div class="text-xs font-mono font-bold text-[#184f3a] mb-1">Step 4</div>
                        <h3 class="font-bold text-sm text-[#17211b] mb-1.5">Edit &amp; Download</h3>
                        <p class="text-xs text-[#536058] leading-relaxed">Tweak any sentence in the live preview, then click Copy Text, Word (.doc), or Download PDF.</p>
                    </div>
                </div>
            </section>

            {{-- 2. What to Include & 3. How Much Notice to Give --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-4">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        What to Include in a Resignation Letter
                    </h2>
                    <ul class="space-y-2 text-sm text-[#536058] leading-relaxed">
                        <li><strong class="text-[#17211b]">Date:</strong> The calendar date you are submitting your written notice.</li>
                        <li><strong class="text-[#17211b]">Recipient details:</strong> Your manager&rsquo;s name, title, and the organization name.</li>
                        <li><strong class="text-[#17211b]">Clear statement of resignation:</strong> Direct confirmation that you are stepping down from your role.</li>
                        <li><strong class="text-[#17211b]">Last working day:</strong> Your exact final date of employment so payroll and HR records match.</li>
                        <li><strong class="text-[#17211b]">Optional thanks:</strong> A brief thank-you for mentorship, growth, or working with the team.</li>
                        <li><strong class="text-[#17211b]">Handover offer:</strong> Willingness to document workflows or train colleagues during your notice period.</li>
                        <li><strong class="text-[#17211b]">Signature block:</strong> A polite closing (&ldquo;Sincerely,&rdquo;) followed by your full name and title.</li>
                    </ul>
                </section>

                <div class="space-y-6">
                    <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-3">
                        <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                            How Much Notice Should You Give?
                        </h2>
                        <p class="text-sm text-[#536058] leading-relaxed">
                            Giving <strong>two weeks notice</strong> is the standard custom in the United States, but the exact period you owe depends on your written employment contract, your company&rsquo;s employee handbook, and local labor law.
                        </p>
                        <p class="text-sm text-[#536058] leading-relaxed">
                            Always <strong>check your signed contract</strong> before choosing your final date. Many countries (such as the UK, Europe, UAE, Pakistan, and India) and many senior or specialized roles commonly require <strong>one month (30 days) or longer</strong>.
                        </p>
                    </section>

                    {{-- 5. Practical Tips --}}
                    <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-3">
                        <h2 class="font-display text-xl font-extrabold text-[#17211b] tracking-tight">
                            Resignation Etiquette Tips
                        </h2>
                        <ul class="space-y-2 text-sm text-[#536058] leading-relaxed">
                            <li>&check; <strong>Keep it short:</strong> Three brief paragraphs are easier for HR files than a long explanation.</li>
                            <li>&check; <strong>Do not criticize your employer:</strong> Remember your letter becomes a permanent HR record.</li>
                            <li>&check; <strong>Deliver thoughtfully:</strong> Speak with your manager first when possible, then provide the letter in person or by email as well.</li>
                            <li>&check; <strong>Keep a copy:</strong> Save the PDF and any email confirmation for your personal records.</li>
                        </ul>
                    </section>
                </div>
            </div>

            {{-- 4. Three Sample Letters in Visible Text (DRAFT Copy, 100-150 words each) --}}
            <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-[0.14em] text-[#184f3a] block mb-1">Examples</span>
                        <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] tracking-tight">
                            Three Resignation Letter Samples (Copy &amp; Customize)
                        </h2>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-mono font-bold uppercase tracking-wider">DRAFT SAMPLES</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-sm text-[#17211b]">1. Two Weeks Notice / New Job</h3>
                            <a href="{{ route('letter.resignation.landing', 'new-job') }}" class="text-[11px] font-bold text-[#184f3a] hover:underline">Open preset &rarr;</a>
                        </div>
                        <p class="text-xs text-[#536058] leading-relaxed whitespace-pre-line">Dear [Manager Name],

Please accept this letter as formal notice that I am resigning from my position as [Job Title] at [Company Name] to take on a new opportunity. My last working day will be [Date].

Thank you for the opportunities and support I have received during my time here. Working alongside you and the team has been a great experience, and I truly appreciate your mentorship. I will do everything I can over the next two weeks to wrap up active work, organize documentation, and ensure a smooth transition for everyone taking over my duties.

Sincerely,
[Your Name]</p>
                    </div>

                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-sm text-[#17211b]">2. Personal Reasons (Private)</h3>
                            <a href="{{ route('letter.resignation.landing', 'personal-reasons') }}" class="text-[11px] font-bold text-[#184f3a] hover:underline">Open preset &rarr;</a>
                        </div>
                        <p class="text-xs text-[#536058] leading-relaxed whitespace-pre-line">Dear [Manager Name],

Please accept this letter as formal notification that I am resigning from my role as [Job Title] at [Company Name] due to personal and family reasons. My final working day will be [Date].

This was not an easy decision, and I want to thank you and the entire team for the support, flexibility, and kindness shown to me throughout my tenure. During my remaining time here, I am happy to prepare clear handover notes and assist with transferring my ongoing projects so there is minimal disruption to the team.

Sincerely,
[Your Name]</p>
                    </div>

                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-sm text-[#17211b]">3. Short Notice Resignation</h3>
                            <a href="{{ route('letter.resignation.landing', 'short-notice') }}" class="text-[11px] font-bold text-[#184f3a] hover:underline">Open preset &rarr;</a>
                        </div>
                        <p class="text-xs text-[#536058] leading-relaxed whitespace-pre-line">Dear [Manager Name],

Please accept this letter as formal notice that I must resign from my position as [Job Title] at [Company Name]. Because of unexpected personal circumstances, I cannot provide a full two-week notice window, and my last working day will be [Date].

I sincerely apologize for any inconvenience this shorter timeline creates. To support the team right away, I have compiled concise handover notes summarizing my open files, key deadlines, and login locations so work can continue without delay. Thank you for your understanding and support.

Sincerely,
[Your Name]</p>
                    </div>
                </div>
            </section>

            {{-- Dedicated Scenario & Reason Pages --}}
            <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm">
                <span class="text-[11px] font-bold uppercase tracking-[0.14em] text-[#184f3a] block mb-1.5">By Situation</span>
                <h2 class="font-display text-2xl font-extrabold text-[#17211b] tracking-tight mb-4">
                    Pre-Filled Generators by Resignation Scenario
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    @foreach(\App\Support\ResignationCatalog::landingPages() as $landing)
                        <a href="{{ route('letter.resignation.landing', $landing['slug']) }}"
                           class="p-4 rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] hover:border-[#184f3a] hover:bg-white transition block">
                            <div class="text-xs font-extrabold text-[#17211b] mb-1">{{ $landing['h1'] }}</div>
                            <span class="text-[11px] font-bold text-[#184f3a]">Use preset &rarr;</span>
                        </a>
                    @endforeach
                </div>
            </section>

            {{-- 6. FAQ Component (Single Source of Truth: Visible HTML + FAQPage JSON-LD) --}}
            @php
                $faqItems = [
                    [
                        'q' => 'Do I have to give a reason for resigning?',
                        'a' => 'No. You are not legally required to state why you are leaving in most roles. Keeping your letter brief—such as stating your position and final working day—is completely professional.',
                    ],
                    [
                        'q' => 'Can I resign by email?',
                        'a' => 'Yes, resigning by email is widely accepted, especially for remote workers or when submitting your letter as an attached PDF after a quick conversation with your manager.',
                    ],
                    [
                        'q' => 'What if I cannot give two weeks notice?',
                        'a' => 'Be honest, apologize briefly for the short timeline, state your final date clearly, and leave thorough written handover notes. Check your employment contract first to confirm any contractual notice clauses.',
                    ],
                    [
                        'q' => 'Should I mention my new job?',
                        'a' => 'You do not need to name your next employer or title in a resignation letter. Simply noting that you accepted a new opportunity—or omitting a reason altogether—keeps things tidy for HR files.',
                    ],
                    [
                        'q' => 'Can I withdraw my resignation?',
                        'a' => 'You can ask your manager and HR in writing as soon as possible, though employers are generally not obligated to accept a retraction once a resignation has been formally accepted.',
                    ],
                    [
                        'q' => 'What happens to my final pay and leave?',
                        'a' => 'Final paycheck timing and payout for unused vacation or leave days depend on your signed employment contract, company policy, and local labor law. Ask HR to confirm payout dates before your last day.',
                    ],
                ];
            @endphp
            <div class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm">
                <x-faq :items="$faqItems" heading="Frequently Asked Questions About Resignation Letters" eyebrow="Resignation FAQ" />
            </div>

            {{-- 7. CTAs & Related Tools --}}
            @php
                $relatedTools = [
                    [
                        'title' => 'Free ATS Resume Builder',
                        'description' => 'Update your resume with your latest achievements across ' . count(\App\Support\ResumeCatalog::templates()) . ' clean layouts.',
                        'url' => route('resume.builder'),
                        'badge' => count(\App\Support\ResumeCatalog::templates()) . ' Templates',
                        'cta' => 'Update Your Resume',
                    ],
                    [
                        'title' => 'Free ATS Resume Checker',
                        'description' => 'Scan your updated CV for ATS formatting issues and missing job keywords (0–100 score).',
                        'url' => route('resume.checker'),
                        'badge' => 'Free Scan',
                        'cta' => 'Check Your Resume',
                    ],
                    [
                        'title' => 'Two Weeks Notice Guide',
                        'description' => 'Step-by-step guide with examples and notice etiquette before handing in your notice.',
                        'url' => route('blog.show', 'how-to-write-two-weeks-notice-resignation-letter-guide'),
                        'badge' => 'Guide',
                        'cta' => 'Read Guide',
                    ],
                ];
                foreach (\App\Models\BlogPost::publishedBySlugs([
                    'how-to-write-a-resignation-letter',
                    'two-weeks-notice-letter-examples',
                    'how-to-resign-professionally-checklist',
                    'resignation-letter-for-new-job',
                ]) as $clusterPost) {
                    $relatedTools[] = [
                        'title' => $clusterPost->title,
                        'description' => \Illuminate\Support\Str::limit($clusterPost->excerpt, 110),
                        'url' => route('blog.show', $clusterPost->slug),
                        'badge' => 'Career Guide',
                        'cta' => 'Read Guide',
                    ];
                }
            @endphp
            <x-related-tools :tools="$relatedTools" heading="Next Steps for Your Career Move" eyebrow="Career Suite" />

        </div>

    </div>
</div>
@endsection
