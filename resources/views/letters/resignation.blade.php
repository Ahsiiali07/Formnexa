@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#fafbfa] text-[#17211b] py-8 sm:py-12"
     x-data="resignationStudio()"
     x-cloak>

    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        {{-- Top Badge --}}
        <div class="flex items-center justify-center gap-2 mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e8f7ed] text-[#184f3a] border border-[#c8f3dc]">
                <span class="w-2 h-2 rounded-full bg-[#184f3a]"></span>
                Free Resignation Letter Generator &bull; Instant PDF &amp; Word Download
            </span>
        </div>

        {{-- Header Copy --}}
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black font-display tracking-tight text-[#17211b] mb-3">
                Free Resignation Letter &amp; <span class="text-[#184f3a]">Two Weeks Notice Maker</span>
            </h1>
            <p class="text-base sm:text-lg text-[#55635a] leading-relaxed">
                Generate a formal, polite resignation letter in under 60 seconds. Choose your tone, set your last working day, and download ready-to-sign PDF or Word documents with zero watermarks.
            </p>
        </div>

        {{-- MAIN TWO-COLUMN SPLIT STUDIO --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- LEFT COLUMN: FORM CONTROLS & OPTIONS --}}
            <div class="lg:col-span-6 space-y-6">

                {{-- Card 1: Your Details --}}
                <div class="bg-white rounded-3xl border border-[#e2e8e3] p-5 sm:p-6 shadow-sm">
                    <h3 class="text-sm font-extrabold text-[#17211b] mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center font-bold text-xs">👤</span>
                        <span>Your Information (Employee)</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                        <div>
                            <label class="block font-bold text-[#3f4a43] mb-1">Your Full Name *</label>
                            <input type="text" x-model="employee_name" @input="regenerateLetter()" placeholder="Alex Morgan"
                                   class="w-full p-2.5 rounded-xl border border-[#d6ded8] focus:border-[#184f3a] focus:ring-1 focus:ring-[#184f3a] text-xs">
                        </div>
                        <div>
                            <label class="block font-bold text-[#3f4a43] mb-1">Your Job Title *</label>
                            <input type="text" x-model="employee_title" @input="regenerateLetter()" placeholder="Software Engineer"
                                   class="w-full p-2.5 rounded-xl border border-[#d6ded8] focus:border-[#184f3a] focus:ring-1 focus:ring-[#184f3a] text-xs">
                        </div>
                        <div>
                            <label class="block font-bold text-[#3f4a43] mb-1">Personal Email</label>
                            <input type="email" x-model="employee_email" @input="regenerateLetter()" placeholder="alex.morgan@gmail.com"
                                   class="w-full p-2.5 rounded-xl border border-[#d6ded8] focus:border-[#184f3a] focus:ring-1 focus:ring-[#184f3a] text-xs">
                        </div>
                        <div>
                            <label class="block font-bold text-[#3f4a43] mb-1">Personal Phone</label>
                            <input type="text" x-model="employee_phone" @input="regenerateLetter()" placeholder="(555) 345-6789"
                                   class="w-full p-2.5 rounded-xl border border-[#d6ded8] focus:border-[#184f3a] focus:ring-1 focus:ring-[#184f3a] text-xs">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block font-bold text-[#3f4a43] mb-1">Your Address (City, State / Country)</label>
                            <input type="text" x-model="employee_address" @input="regenerateLetter()" placeholder="San Francisco, CA"
                                   class="w-full p-2.5 rounded-xl border border-[#d6ded8] focus:border-[#184f3a] focus:ring-1 focus:ring-[#184f3a] text-xs">
                        </div>
                    </div>
                </div>

                {{-- Card 2: Employer / Company Details --}}
                <div class="bg-white rounded-3xl border border-[#e2e8e3] p-5 sm:p-6 shadow-sm">
                    <h3 class="text-sm font-extrabold text-[#17211b] mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-[#f0ebff] text-[#6142be] flex items-center justify-center font-bold text-xs">🏢</span>
                        <span>Employer Details</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                        <div>
                            <label class="block font-bold text-[#3f4a43] mb-1">Manager / Recipient Name *</label>
                            <input type="text" x-model="manager_name" @input="regenerateLetter()" placeholder="Sarah Jenkins"
                                   class="w-full p-2.5 rounded-xl border border-[#d6ded8] focus:border-[#184f3a] focus:ring-1 focus:ring-[#184f3a] text-xs">
                        </div>
                        <div>
                            <label class="block font-bold text-[#3f4a43] mb-1">Manager Title</label>
                            <input type="text" x-model="manager_title" @input="regenerateLetter()" placeholder="VP of Engineering"
                                   class="w-full p-2.5 rounded-xl border border-[#d6ded8] focus:border-[#184f3a] focus:ring-1 focus:ring-[#184f3a] text-xs">
                        </div>
                        <div>
                            <label class="block font-bold text-[#3f4a43] mb-1">Company Name *</label>
                            <input type="text" x-model="company_name" @input="regenerateLetter()" placeholder="Acme Technologies Inc."
                                   class="w-full p-2.5 rounded-xl border border-[#d6ded8] focus:border-[#184f3a] focus:ring-1 focus:ring-[#184f3a] text-xs">
                        </div>
                        <div>
                            <label class="block font-bold text-[#3f4a43] mb-1">Company Office Address</label>
                            <input type="text" x-model="company_address" @input="regenerateLetter()" placeholder="100 Market St, Suite 400"
                                   class="w-full p-2.5 rounded-xl border border-[#d6ded8] focus:border-[#184f3a] focus:ring-1 focus:ring-[#184f3a] text-xs">
                        </div>
                    </div>
                </div>

                {{-- Card 3: Resignation Options, Tone & Reason --}}
                <div class="bg-white rounded-3xl border border-[#e2e8e3] p-5 sm:p-6 shadow-sm">
                    <h3 class="text-sm font-extrabold text-[#17211b] mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-[#fff0e8] text-[#b45309] flex items-center justify-center font-bold text-xs">✍️</span>
                        <span>Resignation Reason &amp; Dates</span>
                    </h3>

                    <div class="space-y-4 text-xs">
                        {{-- Reason Selector --}}
                        <div>
                            <label class="block font-bold text-[#3f4a43] mb-1.5">Reason for Leaving</label>
                            <select x-model="reason" @change="regenerateLetter()"
                                    class="w-full p-2.5 rounded-xl border border-[#d6ded8] bg-white font-semibold text-[#17211b] focus:border-[#184f3a] text-xs">
                                <option value="new_job">Accepting a New Position / Better Opportunity</option>
                                <option value="career_growth">Career Growth &amp; Professional Development</option>
                                <option value="personal">Personal / Family Reasons</option>
                                <option value="relocation">Relocation / Moving to a New Area</option>
                                <option value="education">Returning to School / Further Education</option>
                                <option value="health">Health &amp; Well-being</option>
                                <option value="retirement">Retirement</option>
                                <option value="no_reason">Standard / No Specific Reason Given</option>
                            </select>
                        </div>

                        {{-- Tone Selector --}}
                        <div>
                            <label class="block font-bold text-[#3f4a43] mb-1.5">Letter Tone &amp; Style</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                <button type="button" @click="tone = 'grateful'; regenerateLetter()"
                                        class="p-2.5 rounded-xl border text-center transition font-bold"
                                        :class="tone === 'grateful' ? 'bg-[#e8f7ed] border-[#184f3a] text-[#184f3a]' : 'border-[#d6ded8] text-[#55635a] hover:bg-[#f8fbf9]'">
                                    Grateful &amp; Warm
                                </button>
                                <button type="button" @click="tone = 'formal'; regenerateLetter()"
                                        class="p-2.5 rounded-xl border text-center transition font-bold"
                                        :class="tone === 'formal' ? 'bg-[#e8f7ed] border-[#184f3a] text-[#184f3a]' : 'border-[#d6ded8] text-[#55635a] hover:bg-[#f8fbf9]'">
                                    Formal
                                </button>
                                <button type="button" @click="tone = 'short'; regenerateLetter()"
                                        class="p-2.5 rounded-xl border text-center transition font-bold"
                                        :class="tone === 'short' ? 'bg-[#e8f7ed] border-[#184f3a] text-[#184f3a]' : 'border-[#d6ded8] text-[#55635a] hover:bg-[#f8fbf9]'">
                                    Short &amp; Direct
                                </button>
                                <button type="button" @click="tone = 'urgent'; regenerateLetter()"
                                        class="p-2.5 rounded-xl border text-center transition font-bold"
                                        :class="tone === 'urgent' ? 'bg-[#e8f7ed] border-[#184f3a] text-[#184f3a]' : 'border-[#d6ded8] text-[#55635a] hover:bg-[#f8fbf9]'">
                                    Short Notice
                                </button>
                            </div>
                        </div>

                        {{-- Dates --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                            <div>
                                <label class="block font-bold text-[#3f4a43] mb-1">Letter Date</label>
                                <input type="text" x-model="letter_date" @input="regenerateLetter()"
                                       class="w-full p-2.5 rounded-xl border border-[#d6ded8] text-xs">
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block font-bold text-[#3f4a43]">Last Working Day *</label>
                                    <button type="button" @click="setTwoWeeksNotice()" class="text-[10px] text-[#184f3a] font-bold hover:underline">+2 Weeks</button>
                                </div>
                                <input type="text" x-model="last_day" @input="regenerateLetter()"
                                       class="w-full p-2.5 rounded-xl border border-[#d6ded8] text-xs">
                            </div>
                        </div>

                        {{-- Helper Toggles --}}
                        <div class="pt-3 border-t border-[#eef2ef] space-y-2.5">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" x-model="include_transition" @change="regenerateLetter()"
                                       class="w-4 h-4 rounded text-[#184f3a] focus:ring-[#184f3a] border-gray-300">
                                <span class="text-xs text-[#3f4a43] font-semibold">Offer transition assistance &amp; handover documentation</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" x-model="include_contact" @change="regenerateLetter()"
                                       class="w-4 h-4 rounded text-[#184f3a] focus:ring-[#184f3a] border-gray-300">
                                <span class="text-xs text-[#3f4a43] font-semibold">Include personal email/phone to keep in touch</span>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN: LIVE A4 PREVIEW & EXPORT ACTIONS --}}
            <div class="lg:col-span-6 lg:sticky lg:top-24 space-y-4">

                {{-- Action Bar --}}
                <div class="bg-white rounded-2xl border border-[#e2e8e3] p-3 shadow-sm flex items-center justify-between gap-2 flex-wrap">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-[#184f3a]"></span>
                        <span class="text-xs font-bold text-[#17211b]">Live A4 Letter Preview</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" @click="copyLetterText()"
                                class="px-3.5 py-1.5 rounded-xl border border-[#d6ded8] hover:bg-[#f8fbf9] text-xs font-bold text-[#17211b] transition inline-flex items-center gap-1.5">
                            <span x-text="copied ? 'Copied!' : 'Copy Text'"></span>
                        </button>

                        <button type="button" @click="downloadWord()"
                                class="px-3.5 py-1.5 rounded-xl border border-[#c8f3dc] bg-[#e8f7ed] text-[#184f3a] hover:bg-[#d8f2e3] text-xs font-bold transition inline-flex items-center gap-1">
                            <span>Word (.doc)</span>
                        </button>

                        <button type="button" @click="downloadPdf()"
                                class="px-4 py-1.5 rounded-xl bg-[#184f3a] text-white hover:bg-[#123e2d] text-xs font-bold shadow-sm transition inline-flex items-center gap-1">
                            <span>Download PDF</span>
                        </button>
                    </div>
                </div>

                {{-- Hidden Export Form for PDF & Word --}}
                <form id="letterExportForm" method="POST" action="" class="hidden">
                    @csrf
                    <input type="hidden" name="employee_name" :value="employee_name">
                    <input type="hidden" name="employee_title" :value="employee_title">
                    <input type="hidden" name="employee_email" :value="employee_email">
                    <input type="hidden" name="employee_phone" :value="employee_phone">
                    <input type="hidden" name="employee_address" :value="employee_address">
                    <input type="hidden" name="manager_name" :value="manager_name">
                    <input type="hidden" name="manager_title" :value="manager_title">
                    <input type="hidden" name="company_name" :value="company_name">
                    <input type="hidden" name="company_address" :value="company_address">
                    <input type="hidden" name="date" :value="letter_date">
                    <input type="hidden" name="last_day" :value="last_day">
                    <input type="hidden" name="reason" :value="reason">
                    <input type="hidden" name="tone" :value="tone">
                    <input type="hidden" name="letter_body" :value="letter_body">
                </form>

                {{-- Real-Time A4 Letter Sheet --}}
                <div class="bg-white rounded-3xl border border-[#dfe4df] p-8 sm:p-10 shadow-lg min-h-[600px] text-xs sm:text-sm text-[#17211b] font-sans leading-relaxed relative selection:bg-[#184f3a] selection:text-white">
                    
                    {{-- Sender Info --}}
                    <div class="mb-6">
                        <div class="text-xl font-bold font-display text-[#0f172a]" x-text="employee_name || 'Your Name'"></div>
                        <div class="text-xs font-bold text-[#184f3a]" x-text="employee_title || 'Your Job Title'"></div>
                        <div class="w-10 h-0.5 bg-[#184f3a] my-2"></div>
                        <div class="text-xs text-[#55635a] space-y-0.5">
                            <div x-show="employee_address" x-text="employee_address"></div>
                            <div x-show="employee_email" x-text="`Email: ${employee_email}`"></div>
                            <div x-show="employee_phone" x-text="`Phone: ${employee_phone}`"></div>
                        </div>
                    </div>

                    {{-- Date --}}
                    <div class="mb-5 font-bold text-[#334155]" x-text="letter_date"></div>

                    {{-- Recipient Info --}}
                    <div class="mb-5 text-xs text-[#1e293b] leading-tight space-y-1">
                        <div class="font-bold text-sm" x-text="manager_name || 'Manager Name'"></div>
                        <div x-show="manager_title" class="text-[#55635a]" x-text="manager_title"></div>
                        <div class="font-semibold" x-text="company_name || 'Company Name'"></div>
                        <div x-show="company_address" class="text-[#55635a]" x-text="company_address"></div>
                    </div>

                    {{-- Salutation --}}
                    <div class="font-bold mb-4">
                        Dear <span x-text="manager_name || 'Hiring Manager'"></span>,
                    </div>

                    {{-- Editable Letter Body Preview --}}
                    <div class="space-y-3 mb-6">
                        <textarea x-model="letter_body" rows="9"
                                  class="w-full p-2.5 rounded-xl border border-transparent hover:border-[#c8f3dc] focus:border-[#184f3a] focus:bg-[#fbfdfc] text-xs sm:text-sm leading-relaxed text-[#17211b] font-sans resize-y transition"></textarea>
                        <p class="text-[10px] text-[#68736d] italic">&bull; You can edit any sentence directly in the box above before exporting.</p>
                    </div>

                    {{-- Closing & Signature --}}
                    <div class="mt-6 text-xs sm:text-sm">
                        <div>Sincerely,</div>
                        <div class="h-8"></div>
                        <div class="font-bold text-sm text-[#0f172a]" x-text="employee_name || 'Your Name'"></div>
                        <div class="text-xs text-[#55635a]" x-text="employee_title"></div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<script>
function resignationStudio() {
    return {
        employee_name: 'Alex Morgan',
        employee_title: 'Senior Software Engineer',
        employee_email: 'alex.morgan@example.com',
        employee_phone: '(555) 234-5678',
        employee_address: 'San Francisco, CA',

        manager_name: 'Sarah Jenkins',
        manager_title: 'VP of Engineering',
        company_name: 'Acme Technologies Inc.',
        company_address: '100 Market St, Suite 400',

        letter_date: new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }),
        last_day: '',
        reason: 'new_job',
        tone: 'grateful',
        include_transition: true,
        include_contact: true,
        letter_body: '',
        copied: false,

        init() {
            this.setTwoWeeksNotice();
            this.regenerateLetter();
        },

        setTwoWeeksNotice() {
            const d = new Date();
            d.setDate(d.getDate() + 14);
            this.last_day = d.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            this.regenerateLetter();
        },

        regenerateLetter() {
            const role = this.employee_title || 'my position';
            const company = this.company_name || 'the company';
            const date = this.last_day || 'two weeks from today';

            let p1 = `Please accept this letter as formal notification that I am resigning from my position as ${role} at ${company}. My last working day will be ${date}.`;

            let p2 = '';
            if (this.reason === 'new_job') {
                p2 = `I have accepted a new position that aligns closely with my long-term career aspirations. I am grateful for the opportunities I have had during my time with ${company}, and I appreciate the support and mentorship you and the team have provided.`;
            } else if (this.reason === 'career_growth') {
                p2 = `After careful consideration, I have decided to pursue a new step in my professional journey that allows me to expand into new technical domains. Working alongside such talented colleagues has been a highlight of my career.`;
            } else if (this.reason === 'personal') {
                p2 = `Due to personal and family circumstances, I have made the difficult decision to step down from my role. I want to express my deepest gratitude for your understanding and for the positive environment at ${company}.`;
            } else if (this.reason === 'relocation') {
                p2 = `I am relocating to a new area and will therefore be unable to continue in my role. Thank you for the wonderful experiences and collaborative projects we have shared together.`;
            } else if (this.reason === 'education') {
                p2 = `I have decided to return to school full-time to pursue further academic studies. I am deeply thankful for the practical knowledge and leadership skills I acquired while working with your team.`;
            } else if (this.reason === 'health') {
                p2 = `For health and personal well-being reasons, I have determined it is best for me to take time away from my professional duties. Thank you for your kindness, flexibility, and support throughout my tenure.`;
            } else if (this.reason === 'retirement') {
                p2 = `After much thought, I have decided to retire. It has been a true privilege serving this organization, and I look back on our achievements and camaraderie with great pride.`;
            } else {
                p2 = `I want to express my sincere thanks for the opportunity to work with ${company}. I have learned a great deal and have genuinely enjoyed collaborating with you and the rest of the team.`;
            }

            let p3 = '';
            if (this.include_transition) {
                p3 = `During my remaining time, I am fully committed to ensuring a smooth and seamless handover of my responsibilities. I am glad to assist in documenting current workflows, training team members, or completing priority deliverables.`;
            }

            let p4 = '';
            if (this.include_contact && (this.employee_email || this.employee_phone)) {
                p4 = `I wish you and ${company} continued success in all future endeavors. Please feel free to stay in touch with me at ${this.employee_email || this.employee_phone}.`;
            } else {
                p4 = `I wish you and the entire team at ${company} continued growth and success in the future.`;
            }

            this.letter_body = [p1, p2, p3, p4].filter(Boolean).join("\n\n");
        },

        copyLetterText() {
            const fullText = `${this.employee_name}\n${this.employee_title}\n${this.employee_address}\n${this.employee_email} | ${this.employee_phone}\n\n${this.letter_date}\n\n${this.manager_name}\n${this.manager_title}\n${this.company_name}\n${this.company_address}\n\nDear ${this.manager_name},\n\n${this.letter_body}\n\nSincerely,\n\n${this.employee_name}\n${this.employee_title}`;
            navigator.clipboard.writeText(fullText);
            this.copied = true;
            setTimeout(() => this.copied = false, 2500);
        },

        downloadPdf() {
            const form = document.getElementById('letterExportForm');
            form.action = '/resignation-letter/pdf';
            form.submit();
        },

        downloadWord() {
            const form = document.getElementById('letterExportForm');
            form.action = '/resignation-letter/doc';
            form.submit();
        }
    };
}
</script>
@endsection
