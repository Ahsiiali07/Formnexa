@extends('layouts.app')

<x-schema.tool
    name="Free ATS Resume Checker"
    description="Check your resume for ATS compatibility in seconds. Get a 0-100 score, missing keywords and formatting fixes. Free, no sign-up."
    category="BusinessApplication"
/>

@section('content')
<div class="min-h-screen bg-[#fafbfa] text-[#17211b] py-8 sm:py-12"
     x-data="atsCheckerStudio()"
     x-cloak>

    <div class="max-w-5xl mx-auto px-4 sm:px-6">

        {{-- Visible Breadcrumbs + BreadcrumbList JSON-LD --}}
        <div class="mb-5 flex justify-center">
            <x-schema.breadcrumbs :items="[
                ['Home', url('/')],
                ['Resume Builder', route('resume.builder')],
                ['ATS Resume Checker', route('resume.checker')],
            ]" />
        </div>

        {{-- Top Category Badge --}}
        <div class="flex items-center justify-center gap-2 mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e8f7ed] text-[#184f3a] border border-[#c8f3dc]">
                <span class="w-2 h-2 rounded-full bg-[#184f3a] animate-pulse"></span>
                100% Free &bull; Unlimited Scans &bull; No Sign-up Required
            </span>
        </div>

        {{-- Headline & Hero Copy --}}
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black font-display tracking-tight text-[#17211b] mb-3">
                Free ATS Resume Checker
            </h1>
            <p class="text-base sm:text-lg text-[#55635a] leading-relaxed">
                Check how cleanly automated hiring software reads your resume. Get an instant 0&ndash;100 score, spot missing job keywords, and fix parsing issues before applying.
            </p>
        </div>

        {{-- UPLOAD / INPUT CONTAINER --}}
        <div class="bg-white rounded-3xl border border-[#e2e8e3] shadow-[0_10px_35px_rgba(24,79,58,0.06)] p-6 sm:p-8 mb-10 transition-all">
            
            {{-- Tab selector: File Upload vs Paste Text --}}
            <div class="flex items-center justify-between border-b border-[#eef2ef] pb-4 mb-6 gap-2 flex-wrap">
                <div class="inline-flex p-1 rounded-2xl bg-[#f2f6f3] border border-[#e4eae5]">
                    <button type="button" @click="inputMode = 'file'"
                            class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition"
                            :class="inputMode === 'file' ? 'bg-white text-[#184f3a] shadow-sm' : 'text-[#55635a] hover:text-[#17211b]'">
                        Upload File (PDF / DOCX)
                    </button>
                    <button type="button" @click="inputMode = 'text'"
                            class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition"
                            :class="inputMode === 'text' ? 'bg-white text-[#184f3a] shadow-sm' : 'text-[#55635a] hover:text-[#17211b]'">
                        Paste Resume Text
                    </button>
                </div>

                <button type="button" @click="loadSampleResume()"
                        class="text-xs font-bold text-[#184f3a] hover:underline inline-flex items-center gap-1">
                    <x-icon name="sparkles" class="w-4 h-4 text-[#184f3a]" />
                    <span>Try with Sample Resume</span>
                </button>
            </div>

            {{-- 1. File Upload Mode --}}
            <div x-show="inputMode === 'file'" class="space-y-4">
                <label class="block border-2 border-dashed border-[#c8f3dc] hover:border-[#184f3a] bg-[#f8fbf9] hover:bg-[#e8f7ed]/40 rounded-2xl p-8 sm:p-10 text-center cursor-pointer transition group">
                    <input type="file" @change="onFileSelected($event)" accept=".pdf,.docx,.txt,.md,.json" class="hidden">
                    <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center group-hover:scale-110 transition">
                        <x-icon name="upload" class="w-7 h-7" />
                    </div>
                    <div class="text-base font-extrabold text-[#17211b] mb-1" x-text="fileName || 'Click to upload your resume (PDF, DOCX, or TXT)'"></div>
                    <p class="text-xs text-[#55635a]" x-show="!fileName">Supports PDF, Word (.docx), and plain text &bull; Max 10MB</p>
                    <p class="text-xs text-[#184f3a] font-bold" x-show="fileName">File loaded &bull; Ready to scan</p>
                </label>
            </div>

            {{-- 2. Paste Text Mode --}}
            <div x-show="inputMode === 'text'" class="space-y-2">
                <label class="block text-xs font-bold text-[#3f4a43] uppercase tracking-wider">Paste Resume Content</label>
                <textarea x-model="resumeText" rows="7"
                          placeholder="Paste your full resume text here (Work experience, education, skills, contact info)..."
                          class="w-full p-4 rounded-2xl border border-[#d6ded8] focus:border-[#184f3a] focus:ring-2 focus:ring-[#184f3a]/20 text-sm text-[#17211b] font-mono leading-relaxed resize-y"></textarea>
            </div>

            {{-- Optional Job Description Matcher Accordion --}}
            <div class="mt-6 pt-5 border-t border-[#eef2ef]" x-data="{ openJd: false }">
                <button type="button" @click="openJd = !openJd"
                        class="flex items-center justify-between w-full text-left py-1 text-sm font-bold text-[#17211b] hover:text-[#184f3a] transition">
                    <span class="inline-flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center text-xs">🎯</span>
                        <span>Compare with Target Job Description (Optional &bull; Keyword Match)</span>
                    </span>
                    <span class="text-xs font-semibold text-[#55635a]" x-text="openJd ? 'Collapse' : '+ Add Job Description'"></span>
                </button>

                <div x-show="openJd" x-transition class="mt-3">
                    <textarea x-model="jobDescription" rows="4"
                              placeholder="Paste the job description or role requirements here to check missing keywords..."
                              class="w-full p-3.5 rounded-xl border border-[#d6ded8] focus:border-[#184f3a] focus:ring-2 focus:ring-[#184f3a]/20 text-xs sm:text-sm text-[#17211b]"></textarea>
                    <p class="text-[11px] text-[#68736d] mt-1">Our algorithm extracts must-have skills from the posting and reveals exactly which ones your resume is missing.</p>
                </div>
            </div>

            {{-- Scan Button & Error Message --}}
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-[#55635a]">
                    <span class="font-bold text-[#184f3a]">&check; Private &amp; Secure:</span> Your resume is parsed in real time and never shared.
                </div>

                <button type="button" @click="runAtsCheck()"
                        :disabled="scanning || (inputMode === 'file' && !file) || (inputMode === 'text' && !resumeText.trim())"
                        class="w-full sm:w-auto px-8 py-3.5 rounded-full font-display font-extrabold text-sm sm:text-base text-white bg-[#184f3a] hover:bg-[#123e2d] disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <span x-show="!scanning">Check My ATS Score</span>
                    <span x-show="scanning" class="inline-flex items-center gap-2">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        Scanning ATS Filters...
                    </span>
                    <span aria-hidden="true" x-show="!scanning">&rarr;</span>
                </button>
            </div>

            <div x-show="errorMessage" x-cloak class="mt-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold" x-text="errorMessage"></div>
        </div>

        {{-- RESULTS SECTION (Appears when scan completes) --}}
        <div x-show="results" x-cloak class="space-y-8 animate-fadeIn" id="ats-results">
            
            {{-- Hero Score Banner --}}
            <div class="bg-gradient-to-br from-[#184f3a] to-[#0e3124] text-white rounded-3xl p-6 sm:p-8 shadow-xl">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                    
                    {{-- Circular Score Indicator --}}
                    <div class="md:col-span-4 text-center sm:text-left flex flex-col sm:flex-row items-center gap-5">
                        <div class="relative w-28 h-28 shrink-0 flex items-center justify-center">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                <path class="text-white/20" stroke-width="3.5" stroke="currentColor" fill="none"
                                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <path :class="scoreColorClass()" stroke-width="3.8"
                                      :stroke-dasharray="`${results?.overall_score || 0}, 100`" stroke-linecap="round"
                                      stroke="currentColor" fill="none"
                                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-black font-display tracking-tight" x-text="results?.overall_score"></span>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-white/70">/ 100</span>
                            </div>
                        </div>

                        <div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-white/15 text-white mb-1.5">
                                <span>Grade:</span>
                                <span x-text="results?.grade"></span>
                            </div>
                            <div class="text-lg font-extrabold text-white">ATS Pass Rating</div>
                            <div class="text-xs text-white/80 mt-1" x-text="results?.verdict"></div>
                        </div>
                    </div>

                    {{-- Metrics Summary --}}
                    <div class="md:col-span-5 grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-white/10 rounded-2xl p-3 border border-white/10">
                            <div class="text-white/70 font-semibold">Word Count</div>
                            <div class="text-base font-black text-white mt-0.5" x-text="`${results?.word_count || 0} words`"></div>
                            <div class="text-[10px] text-white/60">Target: 400-800</div>
                        </div>
                        <div class="bg-white/10 rounded-2xl p-3 border border-white/10">
                            <div class="text-white/70 font-semibold">Action Verbs</div>
                            <div class="text-base font-black text-white mt-0.5" x-text="`${results?.categories?.impact?.action_verbs_found?.length || 0} found`"></div>
                            <div class="text-[10px] text-white/60">Target: 6+ verbs</div>
                        </div>
                        <div class="bg-white/10 rounded-2xl p-3 border border-white/10">
                            <div class="text-white/70 font-semibold">Measurable Metrics</div>
                            <div class="text-base font-black text-white mt-0.5" x-text="`${results?.categories?.impact?.metrics_found?.length || 0} metrics`"></div>
                            <div class="text-[10px] text-white/60">Percentages / $ / KPIs</div>
                        </div>
                        <div class="bg-white/10 rounded-2xl p-3 border border-white/10">
                            <div class="text-white/70 font-semibold">Reading Time</div>
                            <div class="text-base font-black text-white mt-0.5" x-text="`${results?.reading_time_seconds || 30}s`"></div>
                            <div class="text-[10px] text-white/60">Estimated read @ 200 wpm</div>
                        </div>
                    </div>

                    {{-- 1-Click Fix CTA --}}
                    <div class="md:col-span-3 text-center md:text-right">
                        <a href="{{ route('resume.builder', ['import' => 1]) }}"
                           class="inline-flex items-center justify-center w-full px-5 py-3.5 rounded-2xl bg-white text-[#184f3a] font-display font-extrabold text-sm hover:bg-[#e8f7ed] shadow-lg transition">
                            <span>Fix in Resume Builder</span>
                            <span class="ml-1.5">&rarr;</span>
                        </a>
                        <div class="text-[11px] text-white/70 mt-2">Loads your content into 29 ATS layouts</div>
                    </div>

                </div>
            </div>

            {{-- 5-CATEGORY AUDIT BREAKDOWN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Category 1: Contact Information --}}
                <div class="bg-white rounded-3xl border border-[#e2e8e3] p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center font-bold text-sm">📇</span>
                            <h3 class="font-extrabold text-base text-[#17211b]">Contact Information</h3>
                        </div>
                        <span class="text-sm font-extrabold" :class="results?.categories?.contact?.score >= 80 ? 'text-[#184f3a]' : 'text-amber-600'" x-text="`${results?.categories?.contact?.score || 0}/100`"></span>
                    </div>

                    <div class="space-y-3">
                        <template x-for="check in results?.categories?.contact?.checks || []" :key="check.title">
                            <div class="flex items-start gap-3 p-3 rounded-2xl" :class="check.passed ? 'bg-[#f7faf8]' : 'bg-rose-50/60'">
                                <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs shrink-0 mt-0.5"
                                      :class="check.passed ? 'bg-[#c8f3dc] text-[#184f3a]' : 'bg-rose-200 text-rose-700'">
                                    <span x-text="check.passed ? '✓' : '!'"></span>
                                </span>
                                <div class="text-xs">
                                    <div class="font-bold text-[#17211b]" x-text="check.title"></div>
                                    <div class="text-[#55635a] mt-0.5" x-text="check.value"></div>
                                    <div x-show="!check.passed" class="text-rose-600 font-semibold mt-1" x-text="check.tip"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Category 2: Section Structure --}}
                <div class="bg-white rounded-3xl border border-[#e2e8e3] p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-[#f0ebff] text-[#6142be] flex items-center justify-center font-bold text-sm">📋</span>
                            <h3 class="font-extrabold text-base text-[#17211b]">Section Structure</h3>
                        </div>
                        <span class="text-sm font-extrabold" :class="results?.categories?.sections?.score >= 80 ? 'text-[#184f3a]' : 'text-amber-600'" x-text="`${results?.categories?.sections?.score || 0}/100`"></span>
                    </div>

                    <div class="space-y-3">
                        <template x-for="check in results?.categories?.sections?.checks || []" :key="check.title">
                            <div class="flex items-start gap-3 p-3 rounded-2xl" :class="check.passed ? 'bg-[#f7faf8]' : 'bg-rose-50/60'">
                                <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs shrink-0 mt-0.5"
                                      :class="check.passed ? 'bg-[#c8f3dc] text-[#184f3a]' : 'bg-rose-200 text-rose-700'">
                                    <span x-text="check.passed ? '✓' : '!'"></span>
                                </span>
                                <div class="text-xs">
                                    <div class="font-bold text-[#17211b]" x-text="check.title"></div>
                                    <div class="text-[#55635a] mt-0.5" x-text="check.value"></div>
                                    <div x-show="!check.passed" class="text-rose-600 font-semibold mt-1" x-text="check.tip"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Category 3: Action Verbs & Impact --}}
                <div class="bg-white rounded-3xl border border-[#e2e8e3] p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center font-bold text-sm">⚡</span>
                            <h3 class="font-extrabold text-base text-[#17211b]">Impact &amp; Action Verbs</h3>
                        </div>
                        <span class="text-sm font-extrabold" :class="results?.categories?.impact?.score >= 75 ? 'text-[#184f3a]' : 'text-amber-600'" x-text="`${results?.categories?.impact?.score || 0}/100`"></span>
                    </div>

                    <div class="space-y-3">
                        <template x-for="check in results?.categories?.impact?.checks || []" :key="check.title">
                            <div class="flex items-start gap-3 p-3 rounded-2xl" :class="check.passed ? 'bg-[#f7faf8]' : 'bg-rose-50/60'">
                                <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs shrink-0 mt-0.5"
                                      :class="check.passed ? 'bg-[#c8f3dc] text-[#184f3a]' : 'bg-rose-200 text-rose-700'">
                                    <span x-text="check.passed ? '✓' : '!'"></span>
                                </span>
                                <div class="text-xs">
                                    <div class="font-bold text-[#17211b]" x-text="check.title"></div>
                                    <div class="text-[#55635a] mt-0.5" x-text="check.value"></div>
                                    <div x-show="!check.passed" class="text-rose-600 font-semibold mt-1" x-text="check.tip"></div>
                                </div>
                            </div>
                        </template>

                        {{-- Action verb badges --}}
                        <div class="mt-3 pt-3 border-t border-[#eef2ef]" x-show="results?.categories?.impact?.action_verbs_found?.length">
                            <div class="text-[11px] font-bold text-[#55635a] mb-2">Detected Power Verbs:</div>
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="verb in results?.categories?.impact?.action_verbs_found || []" :key="verb">
                                    <span class="px-2.5 py-1 rounded-lg bg-[#e8f7ed] text-[#184f3a] font-mono text-[11px] font-semibold" x-text="verb"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Category 4: Formatting & Readability --}}
                <div class="bg-white rounded-3xl border border-[#e2e8e3] p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-[#fff0e8] text-[#b45309] flex items-center justify-center font-bold text-sm">📐</span>
                            <h3 class="font-extrabold text-base text-[#17211b]">ATS Formatting Safety</h3>
                        </div>
                        <span class="text-sm font-extrabold" :class="results?.categories?.formatting?.score >= 80 ? 'text-[#184f3a]' : 'text-amber-600'" x-text="`${results?.categories?.formatting?.score || 0}/100`"></span>
                    </div>

                    <div class="space-y-3">
                        <template x-for="check in results?.categories?.formatting?.checks || []" :key="check.title">
                            <div class="flex items-start gap-3 p-3 rounded-2xl" :class="check.passed ? 'bg-[#f7faf8]' : 'bg-rose-50/60'">
                                <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs shrink-0 mt-0.5"
                                      :class="check.passed ? 'bg-[#c8f3dc] text-[#184f3a]' : 'bg-rose-200 text-rose-700'">
                                    <span x-text="check.passed ? '✓' : '!'"></span>
                                </span>
                                <div class="text-xs">
                                    <div class="font-bold text-[#17211b]" x-text="check.title"></div>
                                    <div class="text-[#55635a] mt-0.5" x-text="check.value"></div>
                                    <div x-show="!check.passed" class="text-rose-600 font-semibold mt-1" x-text="check.tip"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            {{-- Category 5: Target Keyword Analysis (Full Width) --}}
            <div class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-9 h-9 rounded-xl bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center font-bold text-base">🎯</span>
                        <div>
                            <h3 class="font-extrabold text-base text-[#17211b]">Target Job Keyword Match</h3>
                            <div class="text-xs text-[#55635a]">Comparing your resume against role requirements</div>
                        </div>
                    </div>
                    <span x-show="results?.categories?.keywords?.match_percentage !== null"
                          class="text-sm font-extrabold text-[#184f3a]"
                          x-text="`${results?.categories?.keywords?.match_percentage}% Match`"></span>
                </div>

                {{-- Matched Keywords --}}
                <div class="mb-4" x-show="results?.categories?.keywords?.matched?.length">
                    <div class="text-xs font-bold text-[#184f3a] mb-2 flex items-center gap-1.5">
                        <span>&check;</span>
                        <span>Matched Keywords Found in Your Resume:</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="kw in results?.categories?.keywords?.matched || []" :key="kw">
                            <span class="px-3 py-1 rounded-xl bg-[#e8f7ed] text-[#184f3a] border border-[#c8f3dc] font-bold text-xs" x-text="kw"></span>
                        </template>
                    </div>
                </div>

                {{-- Missing Keywords --}}
                <div x-show="results?.categories?.keywords?.missing?.length">
                    <div class="text-xs font-bold text-rose-700 mb-2 flex items-center gap-1.5">
                        <span>&times;</span>
                        <span>Missing Essential Keywords (Add these to boost ranking):</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="kw in results?.categories?.keywords?.missing || []" :key="kw">
                            <span class="px-3 py-1 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 font-bold text-xs" x-text="kw"></span>
                        </template>
                    </div>
                </div>

                <div x-show="!results?.categories?.keywords?.missing?.length && !results?.categories?.keywords?.matched?.length"
                     class="p-4 rounded-2xl bg-[#f8fbf9] border border-[#e4eae5] text-xs text-[#55635a]">
                    No job description was entered. Paste a job description in the box above to analyze exact keyword gaps!
                </div>
            </div>

            {{-- Actionable Fixes Checklist --}}
            <div class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm">
                <h3 class="font-extrabold text-base text-[#17211b] mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center font-bold text-sm">💡</span>
                    <span>Actionable Recommendations to Improve Your Score</span>
                </h3>

                <div class="space-y-3">
                    <template x-for="(rec, idx) in results?.recommendations || []" :key="idx">
                        <div class="flex items-start gap-3 p-3.5 rounded-2xl border"
                             :class="rec.priority === 'high' ? 'bg-amber-50/50 border-amber-200' : 'bg-[#f9faf9] border-[#e8ece9]'">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase shrink-0 mt-0.5"
                                  :class="rec.priority === 'high' ? 'bg-amber-200 text-amber-900' : 'bg-slate-200 text-slate-800'"
                                  x-text="rec.priority"></span>
                            <div class="text-xs">
                                <div class="font-bold text-[#17211b]" x-text="rec.title"></div>
                                <div class="text-[#55635a] mt-0.5" x-text="rec.tip"></div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Final CTA --}}
                <div class="mt-8 p-6 rounded-2xl bg-[#e8f7ed] border border-[#c8f3dc] text-center">
                    <h4 class="text-lg font-black font-display text-[#184f3a] mb-2">Ready to fix these issues automatically?</h4>
                    <p class="text-xs sm:text-sm text-[#3f4a43] max-w-xl mx-auto mb-4">
                        Formnexa has {{ count(\App\Support\ResumeCatalog::templates()) }} ATS-friendly templates designed to help your resume parse cleanly in applicant tracking software. Export cleanly in vector PDF and editable Word with zero fees.
                    </p>
                    <a href="{{ route('resume.builder', ['import' => 1]) }}"
                       class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full font-display font-extrabold text-sm text-white bg-[#184f3a] hover:bg-[#123e2d] shadow-md hover:shadow-lg transition">
                        <span>Open My Resume in ATS Builder</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

        </div>

        {{-- EDUCATIONAL GUIDE SECTIONS 1-7 --}}
        <div class="mt-16 pt-12 border-t border-[#e2e8e3] space-y-12">

            {{-- 1. How the Score Works --}}
            <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm">
                <span class="text-[11px] font-bold uppercase tracking-[0.14em] text-[#184f3a] block mb-1.5">Scoring Breakdown</span>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] tracking-tight mb-3">
                    How the Formnexa ATS Score Works
                </h2>
                <p class="text-sm sm:text-[15px] text-[#536058] leading-relaxed mb-6">
                    This tool calculates <strong>Formnexa&rsquo;s own diagnostic score (10&ndash;100)</strong> based on structured parsing checks&mdash;it is not the proprietary ranking score used by any specific employer&rsquo;s ATS software. When you include a target job description, your score combines all five weighted categories below; when you run a general audit without a job description, the 20% keyword weight is redistributed across structure (30%), impact (30%), contact info (20%), and formatting (20%).
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-4">
                        <div class="text-xs font-mono font-bold text-[#184f3a] mb-1">15% Weight (20% General)</div>
                        <h3 class="font-bold text-sm text-[#17211b] mb-1.5">Contact Info</h3>
                        <p class="text-xs text-[#536058] leading-relaxed">Checks for full name, valid email address, phone number, city/country location, and LinkedIn or portfolio links.</p>
                    </div>
                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-4">
                        <div class="text-xs font-mono font-bold text-[#184f3a] mb-1">25% Weight (30% General)</div>
                        <h3 class="font-bold text-sm text-[#17211b] mb-1.5">Section Structure</h3>
                        <p class="text-xs text-[#536058] leading-relaxed">Verifies standard sections: Work Experience, Education, Core Skills (at least 3&ndash;6 items), and a Professional Summary.</p>
                    </div>
                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-4">
                        <div class="text-xs font-mono font-bold text-[#184f3a] mb-1">25% Weight (30% General)</div>
                        <h3 class="font-bold text-sm text-[#17211b] mb-1.5">Action Verbs &amp; Impact</h3>
                        <p class="text-xs text-[#536058] leading-relaxed">Scans for strong action verbs (target 6+) and measurable results like percentages, dollar amounts, or metrics (target 3+).</p>
                    </div>
                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-4">
                        <div class="text-xs font-mono font-bold text-[#184f3a] mb-1">15% Weight (20% General)</div>
                        <h3 class="font-bold text-sm text-[#17211b] mb-1.5">Formatting Safety</h3>
                        <p class="text-xs text-[#536058] leading-relaxed">Checks resume word count (penalizes under 200 or over 1,300 words), bullet point structure, and clean character encoding.</p>
                    </div>
                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-4">
                        <div class="text-xs font-mono font-bold text-[#184f3a] mb-1">20% Weight (With JD)</div>
                        <h3 class="font-bold text-sm text-[#17211b] mb-1.5">Keyword Match</h3>
                        <p class="text-xs text-[#536058] leading-relaxed">Extracts up to 24 key terms from your pasted job description and shows exact matched and missing terms.</p>
                    </div>
                </div>
            </section>

            {{-- 2. What is an ATS? & 3. Common Failure Reasons --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-4">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        What Is an Applicant Tracking System (ATS)?
                    </h2>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        An Applicant Tracking System (ATS) is software used by employers and recruiting teams to collect, store, and organize incoming job applications. Platforms such as Workday, Greenhouse, Lever, and iCIMS act as a central database where hiring teams review candidates.
                    </p>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        When you upload a resume, the ATS usually runs an automated parser that extracts plain text into structured profile fields&mdash;your name, contact details, job titles, employment dates, degrees, and skills. Recruiters then search and filter those parsed profiles by job title, experience, and keywords.
                    </p>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        If visual formatting blocks the parser from reading your text in order, important details can be skipped or scrambled before a human reviewer ever opens the file.
                    </p>
                </section>

                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-4">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        Common Reasons Resumes Fail ATS Parsing
                    </h2>
                    <ul class="space-y-2.5 text-sm text-[#536058] leading-relaxed">
                        <li><strong class="text-[#17211b]">Complex tables and multi-column grids:</strong> Older parsers sometimes read straight across columns, mixing skills or dates into the wrong job entry.</li>
                        <li><strong class="text-[#17211b]">Floating text boxes:</strong> Content placed inside Word or design-tool text boxes is often skipped entirely during extraction.</li>
                        <li><strong class="text-[#17211b]">Images of text &amp; skill bars:</strong> Graphic charts, star ratings, or exported canvas elements cannot be read as text keywords.</li>
                        <li><strong class="text-[#17211b]">Unusual section headings:</strong> Creative labels like &ldquo;My Journey&rdquo; instead of <em>Work Experience</em> or <em>Education</em> confuse automated section mapping.</li>
                        <li><strong class="text-[#17211b]">Headers and footers:</strong> Critical contact info tucked inside document headers or footers is frequently missed by parsers.</li>
                        <li><strong class="text-[#17211b]">Scanned PDFs:</strong> A PDF saved as a flat image has zero selectable characters. Use vector text or our <a href="{{ route('resume.ats_converter') }}" class="text-[#184f3a] font-semibold hover:underline">ATS Resume Converter</a>.</li>
                    </ul>
                </section>
            </div>

            {{-- 4. How to Use Keyword Match & 5. What a Good Score Means --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-3">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        How to Use the Job Description Keyword Match
                    </h2>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        Open the <strong>&ldquo;Compare with Target Job Description&rdquo;</strong> toggle inside the checker above and paste the requirements section of the role you are applying for.
                    </p>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        Review the green matched terms and red missing terms in your report. Add missing tools, certifications, or phrasing into your Skills section and work-history bullets <strong>only where they honestly reflect work you have actually done</strong>. Never copy hidden text or fabricate skills you do not hold.
                    </p>
                </section>

                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-3">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        What a Good ATS Score Actually Means
                    </h2>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        On Formnexa&rsquo;s 10&ndash;100 scale, an <strong>80+ score (Grade A or A+)</strong> means your resume contains clear contact details, standard section headings, measurable action-driven bullets, clean text encoding, and strong keyword alignment with your pasted job description.
                    </p>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        Keep expectations realistic: this score is a practical guide to help you catch missing elements and formatting traps before submitting. No third-party score can guarantee interviews or replicate every employer&rsquo;s internal hiring criteria.
                    </p>
                </section>
            </div>

            {{-- 6. FAQ Component (Single Source of Truth: Visible HTML + FAQPage JSON-LD) --}}
            @php
                $faqItems = [
                    [
                        'q' => 'What is a good ATS score?',
                        'a' => 'On Formnexa’s 10 to 100 scale, aiming for 80 or higher (Grade A or A+) indicates clean section structure, strong action verbs, measurable results, and solid keyword alignment. Treat the score as a checklist to fix parsing gaps rather than a universal pass or fail cutoff across different employers.',
                    ],
                    [
                        'q' => 'Can ATS read PDF resumes?',
                        'a' => 'Yes, modern Applicant Tracking Systems read PDF resumes well as long as the file contains real selectable text rather than scanned page images. Our checker tests whether text can be extracted cleanly from your PDF and notifies you if it appears to be an image-only scan.',
                    ],
                    [
                        'q' => 'Is a PDF or Word file better?',
                        'a' => 'Both clean vector PDFs and standard .docx files parse reliably in modern recruiting software. Always follow the employer’s application instructions: upload a .docx file when specifically requested, or choose a clean vector PDF when either format is accepted so your visual layout stays intact.',
                    ],
                    [
                        'q' => 'Do I need a different resume for each job?',
                        'a' => 'You do not need to rewrite your resume from scratch for every role, but tailoring your professional summary, core skills, and key bullet points to mirror each job description helps. Small targeted adjustments improve keyword coverage for roles you genuinely qualify for.',
                    ],
                    [
                        'q' => 'How do I add keywords without keyword stuffing?',
                        'a' => 'Place relevant technical tools, frameworks, and job terms naturally inside your dedicated Skills list and within real accomplishment bullets that show how you used them. Avoid repeating the same word unnaturally or hiding invisible white text, which human recruiters easily spot and reject.',
                    ],
                    [
                        'q' => 'Is my resume stored or shared?',
                        'a' => 'No file is saved to disk or stored in a database by the ATS checker, and your resume is never shared with third parties or recruiters. Parsed fields are temporarily placed into your browser session only so the Fix in Resume Builder button can pre-fill our editor if you choose to edit your layout.',
                    ],
                    [
                        'q' => 'Why did my resume score low?',
                        'a' => 'Low scores usually happen when standard headings like Work Experience or Education are missing, contact details sit inside unreadable headers, bullet points lack measurable numbers or action verbs, or the text falls below 200 words. Adding a target job description with missing key terms can also lower the keyword score until addressed.',
                    ],
                    [
                        'q' => 'What is the difference between the checker and the converter?',
                        'a' => 'The ATS Resume Checker audits your existing file or pasted text and gives you a diagnostic score, category feedback, and missing keyword list. The ATS Resume Converter extracts your text and loads it straight into a clean, single-column template inside our builder so you can edit and download a fresh PDF.',
                    ],
                    [
                        'q' => 'Does every company use ATS?',
                        'a' => 'No. Small businesses, startups, and direct email referrals often review resumes manually without automated parsing software. However, mid-sized and large organizations commonly rely on an ATS, and writing a clearly structured resume makes reading easier for both software and people.',
                    ],
                ];
            @endphp
            <div class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm">
                <x-faq :items="$faqItems" heading="Frequently Asked Questions About ATS Resume Scanning" eyebrow="ATS Help" />
            </div>

            {{-- 7. Next-Step CTAs & Related Tools --}}
            @php
                $relatedTools = [
                    [
                        'title' => 'Open in Resume Builder',
                        'description' => 'Fix formatting warnings right away in our free editor across ' . count(\App\Support\ResumeCatalog::templates()) . ' clean layouts.',
                        'url' => route('resume.builder', ['import' => 1]),
                        'badge' => 'Free Studio',
                        'cta' => 'Open Resume Builder',
                    ],
                    [
                        'title' => 'ATS Resume Converter',
                        'description' => 'Upload an older PDF or Word CV and convert it into a linear, machine-readable layout.',
                        'url' => route('resume.ats_converter'),
                        'badge' => 'PDF & DOCX',
                        'cta' => 'Convert Resume',
                    ],
                    [
                        'title' => 'ATS Resume Templates',
                        'description' => 'Browse ' . count(\App\Support\ResumeCatalog::templates()) . ' free ATS-friendly single-column and modern resume designs.',
                        'url' => route('resume.templates'),
                        'badge' => count(\App\Support\ResumeCatalog::templates()) . ' Designs',
                        'cta' => 'Browse Templates',
                    ],
                    [
                        'title' => 'What Is an ATS Resume?',
                        'description' => 'Plain-English guide explaining how applicant tracking software parses and filters resumes.',
                        'url' => route('blog.show', 'what-is-an-ats-based-resume-guide'),
                        'badge' => 'Guide',
                        'cta' => 'Read Guide',
                    ],
                    [
                        'title' => 'How to Write an ATS-Friendly Resume',
                        'description' => 'Step-by-step formatting rules, standard headings, and bullet examples.',
                        'url' => route('blog.show', 'how-to-write-ats-friendly-resume-guide'),
                        'badge' => 'Guide',
                        'cta' => 'Read Guide',
                    ],
                ];
                foreach (\App\Models\BlogPost::publishedBySlugs([
                    'ats-resume-score-explained',
                    'how-to-check-if-your-resume-is-ats-friendly',
                    'ats-resume-keywords-how-to-match-a-job-description',
                ]) as $clusterPost) {
                    $relatedTools[] = [
                        'title' => $clusterPost->title,
                        'description' => \Illuminate\Support\Str::limit($clusterPost->excerpt, 110),
                        'url' => route('blog.show', $clusterPost->slug),
                        'badge' => 'ATS Guide',
                        'cta' => 'Read Guide',
                    ];
                }
            @endphp
            <x-related-tools :tools="$relatedTools" heading="Next Steps After Checking Your Score" eyebrow="Continue Your Application" />

        </div>

    </div>
</div>

<script>
function atsCheckerStudio() {
    return {
        inputMode: 'file',
        file: null,
        fileName: '',
        resumeText: '',
        jobDescription: '',
        scanning: false,
        errorMessage: '',
        results: null,

        onFileSelected(event) {
            const f = event.target.files[0];
            if (!f) return;
            this.file = f;
            this.fileName = f.name;
            this.errorMessage = '';
        },

        loadSampleResume() {
            this.inputMode = 'text';
            this.resumeText = `Jordan Michaels
Senior Full-Stack Software Engineer
San Francisco, CA • jordan.michaels@example.com • (555) 234-5678 • linkedin.com/in/jordanmichaels

PROFESSIONAL SUMMARY
Results-driven Senior Full-Stack Engineer with 7+ years of experience architecting high-traffic web applications, microservices, and modern cloud infrastructure. Spearheaded performance optimizations reducing latency by 45%.

WORK EXPERIENCE
Lead Software Engineer | Apex Cloud Solutions (2021 – Present)
• Architected and deployed microservices architecture using Python, Laravel, and PostgreSQL, increasing system uptime to 99.98%.
• Led a cross-functional team of 8 engineers delivering features across 12 sprint cycles.
• Optimized database indexing and Redis caching, cutting page load speeds by 180ms across 4.2 million daily users.
• Spearheaded automated CI/CD pipelines reducing deployment failure rate by 34%.

Senior Web Developer | Horizon Digital Agency (2018 – 2021)
• Engineered 24+ customer-facing web applications using Vue.js, Tailwind CSS, and RESTful APIs.
• Mentored 5 junior developers on unit testing, clean code principles, and Git workflows.
• Automated billing workflows saving the accounting department 15 hours per week.

EDUCATION
Bachelor of Science in Computer Science
University of California, Berkeley (2014 – 2018)

TECHNICAL SKILLS
Languages: PHP, JavaScript, TypeScript, Python, SQL, HTML5, CSS3
Frameworks: Laravel, Vue.js, React, Node.js, Tailwind CSS
Tools & Cloud: Docker, AWS, Git, CI/CD, Redis, PostgreSQL`;

            this.jobDescription = `Looking for a Senior Software Engineer with strong proficiency in Python, Laravel, TypeScript, Docker, and AWS. Must have experience optimizing database performance, leading engineering teams, and building automated CI/CD pipelines.`;
            this.errorMessage = '';
        },

        async runAtsCheck() {
            this.errorMessage = '';
            this.scanning = true;

            const fd = new FormData();
            if (this.inputMode === 'file' && this.file) {
                fd.append('resume_file', this.file);
            } else if (this.inputMode === 'text' && this.resumeText.trim()) {
                fd.append('resume_text', this.resumeText.trim());
            }

            if (this.jobDescription.trim()) {
                fd.append('job_description', this.jobDescription.trim());
            }

            try {
                const response = await fetch('/api/resume/check', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                    },
                    body: fd,
                });

                const data = await response.json();
                if (!response.ok || !data.success) {
                    this.errorMessage = data.message || 'Could not analyze resume. Please try again.';
                    this.scanning = false;
                    return;
                }

                this.results = data;
                this.scanning = false;

                // Smooth scroll to results
                this.$nextTick(() => {
                    document.getElementById('ats-results')?.scrollIntoView({ behavior: 'smooth' });
                });
            } catch (err) {
                this.errorMessage = 'Network error while analyzing resume. Please try pasting your text directly.';
                this.scanning = false;
            }
        },

        scoreColorClass() {
            const s = this.results?.overall_score || 0;
            if (s >= 80) return 'text-[#38e498]';
            if (s >= 65) return 'text-amber-400';
            return 'text-rose-400';
        }
    };
}
</script>
@endsection
