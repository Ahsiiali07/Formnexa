@props([
    'presetReason' => 'new_job',
    'presetTone' => 'grateful',
    'presetDays' => 14,
])

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    {{-- LEFT COLUMN: FORM CONTROLS & OPTIONS --}}
    <div class="lg:col-span-6 space-y-6">

        {{-- Card 1: Your Details --}}
        <div class="bg-white rounded-3xl border border-[#e2e8e3] p-5 sm:p-6 shadow-sm">
            <h2 class="text-sm font-extrabold text-[#17211b] mb-4 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center font-bold text-xs">1</span>
                <span>Your Information (Employee)</span>
            </h2>

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
            <h2 class="text-sm font-extrabold text-[#17211b] mb-4 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-[#f0ebff] text-[#6142be] flex items-center justify-center font-bold text-xs">2</span>
                <span>Employer Details</span>
            </h2>

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
            <h2 class="text-sm font-extrabold text-[#17211b] mb-4 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-[#fff0e8] text-[#b45309] flex items-center justify-center font-bold text-xs">3</span>
                <span>Resignation Reason, Tone &amp; Dates</span>
            </h2>

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
                                class="p-2.5 rounded-xl border text-center transition font-bold cursor-pointer"
                                :class="tone === 'grateful' ? 'bg-[#e8f7ed] border-[#184f3a] text-[#184f3a]' : 'border-[#d6ded8] text-[#55635a] hover:bg-[#f8fbf9]'">
                            Grateful &amp; Warm
                        </button>
                        <button type="button" @click="tone = 'formal'; regenerateLetter()"
                                class="p-2.5 rounded-xl border text-center transition font-bold cursor-pointer"
                                :class="tone === 'formal' ? 'bg-[#e8f7ed] border-[#184f3a] text-[#184f3a]' : 'border-[#d6ded8] text-[#55635a] hover:bg-[#f8fbf9]'">
                            Formal
                        </button>
                        <button type="button" @click="tone = 'short'; regenerateLetter()"
                                class="p-2.5 rounded-xl border text-center transition font-bold cursor-pointer"
                                :class="tone === 'short' ? 'bg-[#e8f7ed] border-[#184f3a] text-[#184f3a]' : 'border-[#d6ded8] text-[#55635a] hover:bg-[#f8fbf9]'">
                            Short &amp; Direct
                        </button>
                        <button type="button" @click="tone = 'urgent'; regenerateLetter()"
                                class="p-2.5 rounded-xl border text-center transition font-bold cursor-pointer"
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
                            <button type="button" @click="setNoticeDays(14)" class="text-[10px] text-[#184f3a] font-bold hover:underline cursor-pointer">+2 Weeks</button>
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
                        class="px-3.5 py-1.5 rounded-xl border border-[#d6ded8] hover:bg-[#f8fbf9] text-xs font-bold text-[#17211b] transition inline-flex items-center gap-1.5 cursor-pointer">
                    <span x-text="copied ? 'Copied!' : 'Copy Text'"></span>
                </button>

                <button type="button" @click="downloadWord()"
                        class="px-3.5 py-1.5 rounded-xl border border-[#c8f3dc] bg-[#e8f7ed] text-[#184f3a] hover:bg-[#d8f2e3] text-xs font-bold transition inline-flex items-center gap-1 cursor-pointer">
                    <span>Word (.doc)</span>
                </button>

                <button type="button" @click="downloadPdf()"
                        class="px-4 py-1.5 rounded-xl bg-[#184f3a] text-white hover:bg-[#123e2d] text-xs font-bold shadow-sm transition inline-flex items-center gap-1 cursor-pointer">
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
                          aria-label="Editable resignation letter body"
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

<script>
function resignationStudio(presetReason = @js($presetReason), presetTone = @js($presetTone), presetDays = {{ (int) $presetDays }}) {
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
        reason: presetReason || 'new_job',
        tone: presetTone || 'grateful',
        include_transition: true,
        include_contact: true,
        letter_body: '',
        copied: false,

        init() {
            this.setNoticeDays(presetDays || 14);
        },

        setNoticeDays(days) {
            const d = new Date();
            d.setDate(d.getDate() + days);
            this.last_day = d.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            this.regenerateLetter();
        },

        regenerateLetter() {
            const role = this.employee_title || 'my position';
            const rawCompany = this.company_name || 'the company';
            const company = rawCompany.replace(/\.+$/, '');
            const date = this.last_day || 'two weeks from today';

            let p1 = `Please accept this letter as formal notice that I am resigning from my position as ${role} at ${company}. My last working day will be ${date}.`;
            if (this.tone === 'urgent') {
                p1 = `Please accept this letter as formal notification that I am resigning from my role as ${role} at ${company}. Due to unforeseen circumstances requiring a shorter notice period, my final working day will be ${date}.`;
            } else if (this.tone === 'short') {
                p1 = `Please accept this letter as formal notice of my resignation from my position as ${role} at ${company}, effective with my final day on ${date}.`;
            }

            let p2 = '';
            if (this.tone === 'short') {
                p2 = `Thank you for the opportunities I have had during my time with ${company}.`;
            } else if (this.tone === 'urgent') {
                p2 = `I apologize for any inconvenience this shorter timeline causes and appreciate your understanding during this transition.`;
            } else {
                if (this.reason === 'new_job') {
                    p2 = this.tone === 'formal'
                        ? `I have accepted a new position elsewhere. I appreciate the professional opportunities and experience gained during my tenure at ${company}.`
                        : `I have accepted a new position that aligns closely with my career goals. I am sincerely grateful for the opportunities, guidance, and support you and the team have given me during my time at ${company}.`;
                } else if (this.reason === 'career_growth') {
                    p2 = `I have decided to pursue a new step in my professional journey. Working alongside dedicated colleagues at ${company} has been a valuable experience.`;
                } else if (this.reason === 'personal') {
                    p2 = this.tone === 'formal'
                        ? `Due to personal and family commitments, I am stepping down from my position. Thank you for your understanding and professionalism.`
                        : `Due to personal and family commitments, I have made the difficult decision to step down. Thank you sincerely for your kindness and support at ${company}.`;
                } else if (this.reason === 'relocation') {
                    p2 = `Because I am relocating to a new area, I will no longer be able to continue in this role. I am truly grateful for the collaborative projects and friendships we built at ${company}.`;
                } else if (this.reason === 'education') {
                    p2 = `I am returning to full-time academic study. Thank you for the mentorship and practical experience I gained while working with your team.`;
                } else if (this.reason === 'health') {
                    p2 = `To focus on health and personal well-being, I have decided to step away from my current role. I appreciate your flexibility and support during my tenure.`;
                } else if (this.reason === 'retirement') {
                    p2 = `After much consideration, I am retiring from my role. Serving alongside this team at ${company} has been a rewarding part of my career.`;
                } else {
                    p2 = this.tone === 'formal'
                        ? `Thank you for the opportunity to contribute to ${company}.`
                        : `I want to thank you sincerely for the support and opportunities I have experienced during my time with ${company}.`;
                }
            }

            let p3 = '';
            if (this.include_transition) {
                p3 = this.tone === 'urgent'
                    ? `I have organized written handover notes summarizing my open projects and key files so the team can pick up smoothly.`
                    : `During my remaining time, I will do everything I can to ensure a smooth handover, document workflows, and wrap up priority deliverables.`;
            }

            let p4 = '';
            if (this.tone !== 'short') {
                if (this.include_contact && (this.employee_email || this.employee_phone)) {
                    p4 = `I wish you and ${company} continued success. Please feel free to stay in touch at ${this.employee_email || this.employee_phone}.`;
                } else {
                    p4 = `I wish you and the entire team at ${company} continued success in the future.`;
                }
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
