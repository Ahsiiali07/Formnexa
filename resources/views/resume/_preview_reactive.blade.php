{{-- Live Reactive A4 Resume Sheet — Modern FlowCV, Figma Community & ATS Engines --}}
<div id="resume-paper"
     class="w-full max-w-[794px] mx-auto shadow-2xl rounded-sm overflow-hidden transition-all duration-300 border border-slate-200/80 text-slate-800 print:shadow-none print:border-0"
     :class="{
         'bg-[#FAF8F4] text-[#1F1C18]': template_style === 'editorial_figma',
         'bg-white': template_style !== 'editorial_figma',
         'font-sans': font_family === 'sans',
         'font-serif': font_family === 'serif',
         'font-tech-mono': font_family === 'mono',
         'text-[11px] leading-[1.48]': density === 'compact',
         'text-[12px] leading-[1.55]': density !== 'compact'
     }"
     style="min-height: 1122px;">

    {{-- =======================================================================
         1. FLOW SIDEBAR (FlowCV Flagship — Dark Portrait Sidebar + Icon Pill Bars)
         ======================================================================= --}}
    <template x-if="template_style === 'flow_sidebar'">
        <div class="grid grid-cols-12 min-h-[1122px]">
            {{-- Dark Portrait Left Rail --}}
            <aside class="col-span-4 text-white flex flex-col justify-between transition-colors"
                   :class="density === 'compact' ? 'p-6 space-y-4' : 'p-7 space-y-5'"
                   :style="`background-color: ${accent_color};`">
                <div class="space-y-5">
                    <div>
                        <h1 class="text-[22px] font-black tracking-tight leading-[1.12] text-white break-words"
                            :class="font_family === 'serif' ? 'font-editorial font-bold' : ''"
                            x-text="content.personal?.full_name || 'Your Name'"></h1>
                        <p class="text-[12px] font-medium text-white/80 mt-1" x-text="content.personal?.job_title || 'Role / Headline'"></p>

                        <template x-if="content.personal?.show_photo">
                            <div class="w-28 h-28 rounded-full mx-auto mt-4 border-[3px] border-white/25 bg-white/10 flex items-center justify-center shadow-lg overflow-hidden">
                                <img x-show="avatarUrl()"
                                     :src="avatarUrl()"
                                     alt="Portrait"
                                     class="w-full h-full object-cover">
                                <span x-show="!avatarUrl()"
                                      class="text-2xl font-black tracking-tight text-white/95" x-text="initials()"></span>
                            </div>
                        </template>
                    </div>

                    {{-- Crisp SVG Icon Contact List --}}
                    <div class="space-y-2 text-[10.5px] text-white/90 pt-2">
                        <div x-show="content.personal?.email" class="flex items-center gap-2 break-all">
                            <svg class="w-3.5 h-3.5 text-white/70 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            <span x-text="content.personal.email"></span>
                        </div>
                        <div x-show="content.personal?.phone" class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-white/70 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            <span x-text="content.personal.phone"></span>
                        </div>
                        <div x-show="content.personal?.location" class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-white/70 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            <span x-text="content.personal.location"></span>
                        </div>
                        <div x-show="content.personal?.linkedin" class="flex items-center gap-2 break-all">
                            <span class="w-3.5 h-3.5 rounded-[3px] bg-white/20 text-white text-[8.5px] font-black flex items-center justify-center shrink-0">in</span>
                            <span x-text="content.personal.linkedin"></span>
                        </div>
                        <div x-show="content.personal?.website" class="flex items-center gap-2 break-all">
                            <svg class="w-3.5 h-3.5 text-white/70 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582"/></svg>
                            <span x-text="content.personal.website"></span>
                        </div>
                    </div>

                    {{-- Frosted FlowCV Sidebar Section Pills --}}
                    <div x-show="content.section_visibility?.summary && content.summary">
                        <div class="flex items-center gap-2 px-2.5 py-1 rounded bg-white/10 text-[9.5px] font-extrabold uppercase tracking-[0.16em] text-white mb-2.5">
                            <svg class="w-3 h-3 text-white/80 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            <span>Profile</span>
                        </div>
                        <p class="text-[10.8px] leading-relaxed text-white/85 whitespace-pre-line" x-text="content.summary"></p>
                    </div>

                    <div x-show="content.section_visibility?.languages && (content.languages?.length || 0) > 0">
                        <div class="flex items-center gap-2 px-2.5 py-1 rounded bg-white/10 text-[9.5px] font-extrabold uppercase tracking-[0.16em] text-white mb-2.5">
                            <svg class="w-3 h-3 text-white/80 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3"/></svg>
                            <span>Languages</span>
                        </div>
                        <div class="space-y-2">
                            <template x-for="(lang, idx) in content.languages" :key="'fs-lang-' + idx">
                                <div class="flex items-center justify-between gap-2 text-[10.8px]" x-show="lang.name">
                                    <div>
                                        <span class="font-semibold text-white" x-text="lang.name"></span>
                                        <span class="text-[9.5px] text-white/60 ml-1" x-text="lang.level ? '(' + lang.level + ')' : ''"></span>
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0">
                                        <template x-for="dot in 5" :key="dot">
                                            <span class="w-2 h-2 rounded-full transition"
                                                  :class="dot <= (lang.score || 4) ? 'bg-white' : 'bg-white/20'"></span>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-show="content.section_visibility?.awards && (content.awards?.length || 0) > 0">
                        <div class="flex items-center gap-2 px-2.5 py-1 rounded bg-white/10 text-[9.5px] font-extrabold uppercase tracking-[0.16em] text-white mb-2.5">
                            <svg class="w-3 h-3 text-white/80 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497"/></svg>
                            <span>Awards</span>
                        </div>
                        <div class="space-y-2">
                            <template x-for="(aw, idx) in content.awards" :key="'fs-aw-' + idx">
                                <div x-show="aw.title">
                                    <div class="font-semibold text-white text-[10.8px]" x-text="aw.title"></div>
                                    <div class="text-[9.5px] text-white/70" x-text="[aw.issuer, aw.date].filter(Boolean).join(' • ')"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="pt-3 text-[8.5px] text-white/30 tracking-widest uppercase">Curriculum Vitae</div>
            </aside>

            {{-- Right White Main Column --}}
            <main class="col-span-8 bg-white" :class="density === 'compact' ? 'p-6 space-y-4' : 'p-8 space-y-5'">
                <section x-show="content.section_visibility?.experience && (content.experience?.length || 0) > 0">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded bg-slate-100 text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-800 mb-3.5">
                        <svg class="w-3.5 h-3.5 shrink-0" :style="`color: ${accent_color}`" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387"/></svg>
                        <span>Work Experience</span>
                    </div>
                    <div class="space-y-4">
                        <template x-for="(exp, idx) in content.experience" :key="'fs-exp-' + idx">
                            <div x-show="exp.role || exp.company">
                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                    <h3 class="font-extrabold text-[13px] text-slate-900" x-text="exp.company || exp.role"></h3>
                                    <span class="text-[10px] font-semibold text-slate-500 whitespace-nowrap"
                                          x-text="[exp.start_date, exp.end_date].filter(Boolean).join(' – ') + (exp.location ? ' | ' + exp.location : '')"></span>
                                </div>
                                <div class="text-[11px] font-semibold mb-1.5" :style="`color: ${accent_color}`" x-text="exp.role"></div>
                                <ul class="space-y-1 text-slate-700 pl-3.5 list-disc marker:text-slate-400">
                                    <template x-for="(line, bIdx) in parseBullets(exp.bullets_text)" :key="bIdx">
                                        <li x-text="line"></li>
                                    </template>
                                </ul>
                            </div>
                        </template>
                    </div>
                </section>

                <section x-show="content.section_visibility?.education && (content.education?.length || 0) > 0">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded bg-slate-100 text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-800 mb-3">
                        <svg class="w-3.5 h-3.5 shrink-0" :style="`color: ${accent_color}`" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342"/></svg>
                        <span>Education</span>
                    </div>
                    <div class="space-y-2.5">
                        <template x-for="(edu, idx) in content.education" :key="'fs-edu-' + idx">
                            <div x-show="edu.degree || edu.school">
                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                    <h3 class="font-bold text-[12.5px] text-slate-900" x-text="edu.degree"></h3>
                                    <span class="text-[10px] font-semibold text-slate-500"
                                          x-text="[edu.start_date, edu.end_date].filter(Boolean).join(' – ')"></span>
                                </div>
                                <div class="text-[11px] text-slate-600 font-medium"
                                     x-text="[edu.school, edu.location].filter(Boolean).join(', ')"></div>
                                <p x-show="edu.details" class="text-[10.5px] text-slate-500 mt-0.5" x-text="edu.details"></p>
                            </div>
                        </template>
                    </div>
                </section>

                <section x-show="content.section_visibility?.skills && (content.skills?.length || 0) > 0">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded bg-slate-100 text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-800 mb-3">
                        <svg class="w-3.5 h-3.5 shrink-0" :style="`color: ${accent_color}`" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                        <span>Skills</span>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="(sk, idx) in content.skills" :key="'fs-sk-' + idx">
                            <span class="px-2.5 py-1 rounded-md text-[10.5px] font-semibold bg-slate-100 text-slate-800 border border-slate-200/80" x-text="sk"></span>
                        </template>
                    </div>
                </section>

                <section x-show="content.section_visibility?.projects && (content.projects?.length || 0) > 0">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded bg-slate-100 text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-800 mb-3">
                        <svg class="w-3.5 h-3.5 shrink-0" :style="`color: ${accent_color}`" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                        <span>Key Projects</span>
                    </div>
                    <div class="space-y-2.5">
                        <template x-for="(proj, idx) in content.projects" :key="'fs-pr-' + idx">
                            <div x-show="proj.name">
                                <div class="flex items-baseline justify-between gap-2">
                                    <span class="font-bold text-slate-900" x-text="proj.name"></span>
                                    <span class="text-[10px] text-slate-500" x-text="proj.link"></span>
                                </div>
                                <p x-show="proj.description" class="text-slate-600 mt-0.5" x-text="proj.description"></p>
                            </div>
                        </template>
                    </div>
                </section>
            </main>
        </div>
    </template>

    {{-- =======================================================================
         2. EDITORIAL FIGMA (Cream Archival Canvas, Playfair Display, Hairlines)
         ======================================================================= --}}
    <template x-if="template_style === 'editorial_figma'">
        <div class="min-h-[1122px] bg-[#FAF8F4] text-[#1F1C18]" :class="density === 'compact' ? 'p-8 space-y-5' : 'p-10 space-y-6'">
            <header class="border-b pb-6 flex items-start justify-between gap-6" style="border-color: rgba(31,28,24,0.16);">
                <div class="flex items-center gap-5 min-w-0">
                    <template x-if="content.personal?.show_photo">
                        <div class="w-20 h-20 rounded-full border shrink-0 flex items-center justify-center text-xl font-bold tracking-tight overflow-hidden shadow-sm"
                             :style="`border-color: ${accent_color}; color: ${accent_color}; background-color: #EFECE5;`">
                            <img x-show="avatarUrl()" :src="avatarUrl()" alt="Portrait" class="w-full h-full object-cover">
                            <span x-show="!avatarUrl()" x-text="initials()"></span>
                        </div>
                    </template>
                    <div class="min-w-0">
                        <div class="text-[9.5px] font-bold uppercase tracking-[0.26em] mb-1" :style="`color: ${accent_color}`">Curriculum Vitae</div>
                        <h1 class="font-editorial text-[32px] font-bold tracking-tight leading-[1.08] text-[#1A1815]"
                            x-text="content.personal?.full_name || 'Your Name'"></h1>
                        <p class="text-[13.5px] italic font-editorial text-[#59534A] mt-1" x-text="content.personal?.job_title || 'Title'"></p>
                    </div>
                </div>
                <div class="text-right text-[10.5px] text-[#59534A] space-y-1 shrink-0 pt-1">
                    <div x-show="content.personal?.email" class="font-medium text-[#1A1815]" x-text="content.personal.email"></div>
                    <div x-show="content.personal?.phone" x-text="content.personal.phone"></div>
                    <div x-show="content.personal?.location" x-text="content.personal.location"></div>
                    <div x-show="content.personal?.website" class="underline decoration-stone-300" x-text="content.personal.website"></div>
                    <div x-show="content.personal?.linkedin" x-text="content.personal.linkedin"></div>
                </div>
            </header>

            <section x-show="content.section_visibility?.summary && content.summary" class="border-b pb-5" style="border-color: rgba(31,28,24,0.12);">
                <div class="grid grid-cols-12 gap-6 items-start">
                    <div class="col-span-3 text-[9.5px] font-bold uppercase tracking-[0.22em] pt-0.5" :style="`color: ${accent_color}`">Profile</div>
                    <p class="col-span-9 text-[12px] leading-[1.65] text-[#35302A] whitespace-pre-line" x-text="content.summary"></p>
                </div>
            </section>

            <div class="grid grid-cols-12 gap-8 items-start">
                <div class="col-span-8 space-y-6 pr-2 border-r" style="border-color: rgba(31,28,24,0.10);">
                    <section x-show="content.section_visibility?.experience && (content.experience?.length || 0) > 0">
                        <div class="flex items-center justify-between border-b pb-1.5 mb-4" style="border-color: rgba(31,28,24,0.16);">
                            <h2 class="font-editorial text-[14px] font-bold tracking-wide text-[#1A1815]">Experience</h2>
                            <span class="text-[9px] uppercase tracking-[0.2em]" :style="`color: ${accent_color}`">Career History</span>
                        </div>
                        <div class="space-y-4">
                            <template x-for="(exp, idx) in content.experience" :key="'ed-exp-' + idx">
                                <div x-show="exp.role || exp.company">
                                    <div class="flex items-baseline justify-between gap-2">
                                        <h3 class="font-bold text-[12.5px] text-[#1A1815]" x-text="exp.role || exp.company"></h3>
                                        <span class="text-[10px] italic font-editorial text-[#6B645A]"
                                              x-text="[exp.start_date, exp.end_date].filter(Boolean).join(' – ')"></span>
                                    </div>
                                    <div class="text-[11px] font-semibold mb-1.5" :style="`color: ${accent_color}`"
                                         x-text="[exp.company, exp.location].filter(Boolean).join(' — ')"></div>
                                    <ul class="space-y-1 text-[#3D372F] pl-4 list-disc">
                                        <template x-for="(line, bIdx) in parseBullets(exp.bullets_text)" :key="bIdx">
                                            <li x-text="line"></li>
                                        </template>
                                    </ul>
                                </div>
                            </template>
                        </div>
                    </section>

                    <section x-show="content.section_visibility?.projects && (content.projects?.length || 0) > 0">
                        <div class="flex items-center justify-between border-b pb-1.5 mb-3" style="border-color: rgba(31,28,24,0.16);">
                            <h2 class="font-editorial text-[14px] font-bold tracking-wide text-[#1A1815]">Selected Works</h2>
                        </div>
                        <div class="space-y-2.5">
                            <template x-for="(proj, idx) in content.projects" :key="'ed-pr-' + idx">
                                <div x-show="proj.name">
                                    <div class="flex items-baseline justify-between gap-2">
                                        <span class="font-bold text-[12px] text-[#1A1815]" x-text="proj.name"></span>
                                        <span class="text-[10px] underline decoration-stone-300 text-[#6B645A]" x-text="proj.link"></span>
                                    </div>
                                    <p x-show="proj.description" class="text-[#3D372F] mt-0.5" x-text="proj.description"></p>
                                </div>
                            </template>
                        </div>
                    </section>
                </div>

                <div class="col-span-4 space-y-5">
                    <section x-show="content.section_visibility?.education && (content.education?.length || 0) > 0">
                        <h2 class="font-editorial text-[13.5px] font-bold border-b pb-1.5 mb-3 text-[#1A1815]" style="border-color: rgba(31,28,24,0.16);">Education</h2>
                        <div class="space-y-3">
                            <template x-for="(edu, idx) in content.education" :key="'ed-edu-' + idx">
                                <div x-show="edu.degree || edu.school">
                                    <div class="font-bold text-[11.5px] text-[#1A1815]" x-text="edu.degree"></div>
                                    <div class="text-[11px] font-medium" :style="`color: ${accent_color}`" x-text="edu.school"></div>
                                    <div class="text-[10px] text-[#6B645A]" x-text="[edu.start_date, edu.end_date].filter(Boolean).join(' – ')"></div>
                                    <p x-show="edu.details" class="text-[10px] text-[#59534A] mt-0.5" x-text="edu.details"></p>
                                </div>
                            </template>
                        </div>
                    </section>

                    <section x-show="content.section_visibility?.skills && (content.skills?.length || 0) > 0">
                        <h2 class="font-editorial text-[13.5px] font-bold border-b pb-1.5 mb-3 text-[#1A1815]" style="border-color: rgba(31,28,24,0.16);">Expertise</h2>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="(sk, idx) in content.skills" :key="'ed-sk-' + idx">
                                <span class="px-2 py-0.5 rounded-sm border text-[10.5px] font-medium text-[#2E2A24]"
                                      style="border-color: rgba(31,28,24,0.22); background-color: rgba(255,255,255,0.65);" x-text="sk"></span>
                            </template>
                        </div>
                    </section>

                    <section x-show="content.section_visibility?.languages && (content.languages?.length || 0) > 0">
                        <h2 class="font-editorial text-[13.5px] font-bold border-b pb-1.5 mb-2.5 text-[#1A1815]" style="border-color: rgba(31,28,24,0.16);">Languages</h2>
                        <div class="space-y-1.5 text-[11px]">
                            <template x-for="(lang, idx) in content.languages" :key="'ed-lg-' + idx">
                                <div class="flex justify-between" x-show="lang.name">
                                    <span class="font-semibold text-[#1A1815]" x-text="lang.name"></span>
                                    <span class="italic font-editorial text-[#6B645A]" x-text="lang.level"></span>
                                </div>
                            </template>
                        </div>
                    </section>

                    <section x-show="content.section_visibility?.awards && (content.awards?.length || 0) > 0">
                        <h2 class="font-editorial text-[13.5px] font-bold border-b pb-1.5 mb-2.5 text-[#1A1815]" style="border-color: rgba(31,28,24,0.16);">Recognition</h2>
                        <div class="space-y-2 text-[11px]">
                            <template x-for="(aw, idx) in content.awards" :key="'ed-aw-' + idx">
                                <div x-show="aw.title">
                                    <div class="font-semibold text-[#1A1815]" x-text="aw.title"></div>
                                    <div class="text-[10px] text-[#6B645A]" x-text="[aw.issuer, aw.date].filter(Boolean).join(' • ')"></div>
                                </div>
                            </template>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </template>

    {{-- =======================================================================
         3. TIMELINE PRO (Career Track Spine + Sleek Progress Bars)
         ======================================================================= --}}
    <template x-if="template_style === 'timeline_pro'">
        <div class="min-h-[1122px] bg-white">
            <header class="border-b border-slate-200 px-8 py-6 flex items-center justify-between gap-6"
                    :style="`background: linear-gradient(90deg, ${accent_color}0D 0%, #FFFFFF 65%);`">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-1.5 self-stretch rounded-full shrink-0" :style="`background-color: ${accent_color}`"></div>
                    <template x-if="content.personal?.show_photo">
                        <div class="w-16 h-16 rounded-2xl shrink-0 flex items-center justify-center text-white font-black text-lg shadow-sm overflow-hidden"
                             :style="`background-color: ${accent_color}`">
                            <img x-show="avatarUrl()" :src="avatarUrl()" alt="Portrait" class="w-full h-full object-cover">
                            <span x-show="!avatarUrl()" x-text="initials()"></span>
                        </div>
                    </template>
                    <div class="min-w-0">
                        <h1 class="text-[24px] font-black tracking-tight text-slate-900 leading-tight"
                            :class="font_family === 'serif' ? 'font-editorial' : ''"
                            x-text="content.personal?.full_name || 'Your Name'"></h1>
                        <p class="text-[13px] font-bold mt-0.5" :style="`color: ${accent_color}`"
                           x-text="content.personal?.job_title || 'Title'"></p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-1 text-right text-[10.5px] text-slate-600 shrink-0">
                    <div x-show="content.personal?.email" class="font-semibold text-slate-800" x-text="content.personal.email"></div>
                    <div x-show="content.personal?.phone" x-text="content.personal.phone"></div>
                    <div x-show="content.personal?.location" x-text="content.personal.location"></div>
                    <div x-show="content.personal?.linkedin || content.personal?.website" x-text="content.personal.linkedin || content.personal.website"></div>
                </div>
            </header>

            <div class="grid grid-cols-12 gap-7" :class="density === 'compact' ? 'p-6 gap-5' : 'p-8 gap-7'">
                <div class="col-span-8 space-y-6">
                    <section x-show="content.section_visibility?.summary && content.summary">
                        <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-2 flex items-center gap-2" :style="`color: ${accent_color}`">
                            <span class="w-5 h-0.5 rounded-full" :style="`background-color: ${accent_color}`"></span> Executive Summary
                        </h2>
                        <p class="text-slate-700 leading-relaxed whitespace-pre-line" x-text="content.summary"></p>
                    </section>

                    <section x-show="content.section_visibility?.experience && (content.experience?.length || 0) > 0">
                        <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-4 flex items-center gap-2" :style="`color: ${accent_color}`">
                            <span class="w-5 h-0.5 rounded-full" :style="`background-color: ${accent_color}`"></span> Career Timeline
                        </h2>
                        <div class="relative pl-5 space-y-5 border-l-2 ml-1.5" :style="`border-color: ${accent_color}30`">
                            <template x-for="(exp, idx) in content.experience" :key="'tl-exp-' + idx">
                                <div class="relative" x-show="exp.role || exp.company">
                                    <span class="absolute -left-[27px] top-1 w-3 h-3 rounded-full border-2 bg-white shadow-sm"
                                          :style="`border-color: ${accent_color}`"></span>
                                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                                        <h3 class="font-extrabold text-[13px] text-slate-900" x-text="exp.role || exp.company"></h3>
                                        <span class="px-2 py-0.5 rounded-full text-[9.5px] font-bold"
                                              :style="`background-color: ${accent_color}14; color: ${accent_color}`"
                                              x-text="[exp.start_date, exp.end_date].filter(Boolean).join(' – ')"></span>
                                    </div>
                                    <div class="text-[11px] font-semibold text-slate-600 mb-1.5"
                                         x-text="[exp.company, exp.location].filter(Boolean).join(' • ')"></div>
                                    <ul class="space-y-1 text-slate-700 pl-3.5 list-disc">
                                        <template x-for="(line, bIdx) in parseBullets(exp.bullets_text)" :key="bIdx">
                                            <li x-text="line"></li>
                                        </template>
                                    </ul>
                                </div>
                            </template>
                        </div>
                    </section>

                    <section x-show="content.section_visibility?.projects && (content.projects?.length || 0) > 0">
                        <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-3 flex items-center gap-2" :style="`color: ${accent_color}`">
                            <span class="w-5 h-0.5 rounded-full" :style="`background-color: ${accent_color}`"></span> Key Initiatives
                        </h2>
                        <div class="grid grid-cols-1 gap-2.5">
                            <template x-for="(proj, idx) in content.projects" :key="'tl-pr-' + idx">
                                <div class="p-3 rounded-lg border border-slate-200 bg-slate-50/70" x-show="proj.name">
                                    <div class="flex items-baseline justify-between gap-2">
                                        <span class="font-bold text-slate-900" x-text="proj.name"></span>
                                        <span class="text-[10px] text-slate-500" x-text="proj.link"></span>
                                    </div>
                                    <p x-show="proj.description" class="text-slate-600 mt-0.5" x-text="proj.description"></p>
                                </div>
                            </template>
                        </div>
                    </section>
                </div>

                <aside class="col-span-4 space-y-5 bg-slate-50/85 p-4 rounded-xl border border-slate-100 h-fit">
                    <section x-show="content.section_visibility?.skills && (content.skills?.length || 0) > 0">
                        <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-3" :style="`color: ${accent_color}`">Skill Proficiency</h2>
                        <div class="space-y-2.5">
                            <template x-for="(sk, idx) in content.skills" :key="'tl-sk-' + idx">
                                <div>
                                    <div class="flex justify-between text-[10.5px] font-semibold text-slate-800 mb-1">
                                        <span x-text="sk"></span>
                                        <span class="text-[9.5px] text-slate-400" x-text="(96 - ((idx * 6) % 22)) + '%'"></span>
                                    </div>
                                    <div class="h-1.5 w-full rounded-full bg-slate-200 overflow-hidden">
                                        <div class="h-full rounded-full transition-all"
                                             :style="`width: ${96 - ((idx * 6) % 22)}%; background-color: ${accent_color};`"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </section>

                    <section x-show="content.section_visibility?.education && (content.education?.length || 0) > 0" class="pt-3 border-t border-slate-200">
                        <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-2.5" :style="`color: ${accent_color}`">Education</h2>
                        <div class="space-y-2.5">
                            <template x-for="(edu, idx) in content.education" :key="'tl-ed-' + idx">
                                <div x-show="edu.degree || edu.school">
                                    <div class="font-bold text-[11px] text-slate-900" x-text="edu.degree"></div>
                                    <div class="text-[10.5px] text-slate-600" x-text="edu.school"></div>
                                    <div class="text-[9.5px] text-slate-400" x-text="[edu.start_date, edu.end_date].filter(Boolean).join(' – ')"></div>
                                </div>
                            </template>
                        </div>
                    </section>

                    <section x-show="content.section_visibility?.languages && (content.languages?.length || 0) > 0" class="pt-3 border-t border-slate-200">
                        <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-2.5" :style="`color: ${accent_color}`">Languages</h2>
                        <div class="space-y-1.5 text-[11px]">
                            <template x-for="(lang, idx) in content.languages" :key="'tl-lg-' + idx">
                                <div class="flex justify-between" x-show="lang.name">
                                    <span class="font-semibold text-slate-800" x-text="lang.name"></span>
                                    <span class="text-[10px] font-semibold" :style="`color: ${accent_color}`" x-text="lang.level"></span>
                                </div>
                            </template>
                        </div>
                    </section>

                    <section x-show="content.section_visibility?.awards && (content.awards?.length || 0) > 0" class="pt-3 border-t border-slate-200">
                        <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-2" :style="`color: ${accent_color}`">Awards</h2>
                        <div class="space-y-1.5 text-[10.5px]">
                            <template x-for="(aw, idx) in content.awards" :key="'tl-aw-' + idx">
                                <div x-show="aw.title">
                                    <div class="font-bold text-slate-800" x-text="aw.title"></div>
                                    <div class="text-[9.5px] text-slate-500" x-text="[aw.issuer, aw.date].filter(Boolean).join(' • ')"></div>
                                </div>
                            </template>
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </template>

    {{-- =======================================================================
         4. LUXE SPLIT (FlowCV Split Header + Minimal Nordic Whitespace)
         ======================================================================= --}}
    <template x-if="template_style === 'luxe_split'">
        <div class="min-h-[1122px] bg-white" :class="density === 'compact' ? 'p-8 space-y-5' : 'p-10 space-y-6'">
            <header class="flex items-center justify-between gap-6 pb-6 border-b border-slate-200">
                <div class="flex items-center gap-5 min-w-0">
                    <div class="w-16 h-16 rounded-2xl border-2 shrink-0 flex items-center justify-center font-black text-xl tracking-tight overflow-hidden"
                         :style="`border-color: ${accent_color}; color: ${accent_color}`">
                        <img x-show="content.personal?.show_photo && avatarUrl()" :src="avatarUrl()" alt="Portrait" class="w-full h-full object-cover">
                        <span x-show="!content.personal?.show_photo || !avatarUrl()" x-text="initials()"></span>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-[26px] font-black tracking-tight text-slate-900 leading-tight"
                            :class="font_family === 'serif' ? 'font-editorial' : ''"
                            x-text="content.personal?.full_name || 'Your Name'"></h1>
                        <p class="text-[13px] font-semibold tracking-wide text-slate-500 mt-0.5"
                           x-text="content.personal?.job_title || 'Title'"></p>
                    </div>
                </div>
                <div class="text-right text-[10.5px] text-slate-500 space-y-0.5 shrink-0">
                    <div x-show="content.personal?.email" class="font-semibold text-slate-900" x-text="content.personal.email"></div>
                    <div x-show="content.personal?.phone" x-text="content.personal.phone"></div>
                    <div x-show="content.personal?.location" x-text="content.personal.location"></div>
                    <div x-show="content.personal?.website || content.personal?.linkedin" x-text="content.personal.website || content.personal.linkedin"></div>
                </div>
            </header>

            <section x-show="content.section_visibility?.summary && content.summary"
                     class="p-4 rounded-xl bg-slate-50 border-l-4" :style="`border-left-color: ${accent_color}`">
                <p class="text-[11.5px] leading-relaxed text-slate-700 whitespace-pre-line" x-text="content.summary"></p>
            </section>

            <div class="grid grid-cols-12 gap-8 items-start">
                <div class="col-span-7 space-y-6">
                    <section x-show="content.section_visibility?.experience && (content.experience?.length || 0) > 0">
                        <h2 class="text-[10.5px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-3.5">Experience</h2>
                        <div class="space-y-4">
                            <template x-for="(exp, idx) in content.experience" :key="'lx-exp-' + idx">
                                <div x-show="exp.role || exp.company">
                                    <div class="font-bold text-[13px] text-slate-900" x-text="exp.role || exp.company"></div>
                                    <div class="flex items-center justify-between text-[11px] font-semibold mb-1.5" :style="`color: ${accent_color}`">
                                        <span x-text="[exp.company, exp.location].filter(Boolean).join(' • ')"></span>
                                        <span class="text-slate-400 font-normal" x-text="[exp.start_date, exp.end_date].filter(Boolean).join(' – ')"></span>
                                    </div>
                                    <ul class="space-y-1 text-slate-700 pl-4 list-disc">
                                        <template x-for="(line, bIdx) in parseBullets(exp.bullets_text)" :key="bIdx">
                                            <li x-text="line"></li>
                                        </template>
                                    </ul>
                                </div>
                            </template>
                        </div>
                    </section>

                    <section x-show="content.section_visibility?.projects && (content.projects?.length || 0) > 0">
                        <h2 class="text-[10.5px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-3">Projects</h2>
                        <div class="space-y-2.5">
                            <template x-for="(proj, idx) in content.projects" :key="'lx-pr-' + idx">
                                <div x-show="proj.name">
                                    <div class="font-bold text-slate-900" x-text="proj.name"></div>
                                    <div class="text-[10px] text-slate-400" x-text="proj.link"></div>
                                    <p x-show="proj.description" class="text-slate-600 mt-0.5" x-text="proj.description"></p>
                                </div>
                            </template>
                        </div>
                    </section>
                </div>

                <div class="col-span-5 space-y-5">
                    <section x-show="content.section_visibility?.education && (content.education?.length || 0) > 0">
                        <h2 class="text-[10.5px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-3">Education</h2>
                        <div class="space-y-3">
                            <template x-for="(edu, idx) in content.education" :key="'lx-ed-' + idx">
                                <div class="p-3 rounded-lg border border-slate-100 bg-slate-50/60" x-show="edu.degree || edu.school">
                                    <div class="font-bold text-[11.5px] text-slate-900" x-text="edu.degree"></div>
                                    <div class="text-[11px] font-medium" :style="`color: ${accent_color}`" x-text="edu.school"></div>
                                    <div class="text-[10px] text-slate-400 mt-0.5" x-text="[edu.start_date, edu.end_date].filter(Boolean).join(' – ')"></div>
                                    <p x-show="edu.details" class="text-[10px] text-slate-500 mt-1" x-text="edu.details"></p>
                                </div>
                            </template>
                        </div>
                    </section>

                    <section x-show="content.section_visibility?.skills && (content.skills?.length || 0) > 0">
                        <h2 class="text-[10.5px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-2.5">Core Skills</h2>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="(sk, idx) in content.skills" :key="'lx-sk-' + idx">
                                <span class="px-2.5 py-1 rounded-full border border-slate-200 text-[10.5px] font-semibold text-slate-700" x-text="sk"></span>
                            </template>
                        </div>
                    </section>

                    <section x-show="content.section_visibility?.languages && (content.languages?.length || 0) > 0">
                        <h2 class="text-[10.5px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-2">Languages</h2>
                        <div class="space-y-1.5">
                            <template x-for="(lang, idx) in content.languages" :key="'lx-lg-' + idx">
                                <div class="flex justify-between text-[11px]" x-show="lang.name">
                                    <span class="font-semibold text-slate-800" x-text="lang.name"></span>
                                    <span class="text-slate-500" x-text="lang.level"></span>
                                </div>
                            </template>
                        </div>
                    </section>

                    <section x-show="content.section_visibility?.awards && (content.awards?.length || 0) > 0">
                        <h2 class="text-[10.5px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-2">Awards</h2>
                        <div class="space-y-1.5 text-[10.5px]">
                            <template x-for="(aw, idx) in content.awards" :key="'lx-aw-' + idx">
                                <div x-show="aw.title">
                                    <div class="font-bold text-slate-800" x-text="aw.title"></div>
                                    <div class="text-slate-500" x-text="[aw.issuer, aw.date].filter(Boolean).join(' • ')"></div>
                                </div>
                            </template>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </template>

    {{-- =======================================================================
         5. EXECUTIVE BOLD (FlowCV Rounded Obsidian Hero Card + Contact Pill Strip)
         ======================================================================= --}}
    <template x-if="template_style === 'executive_bold'">
        <div class="min-h-[1122px] bg-white pt-6 px-6 pb-8 space-y-5">
            <header class="rounded-2xl text-white px-7 py-6 shadow-sm space-y-4" :style="`background-color: ${accent_color}`">
                <div class="flex items-center justify-between gap-5">
                    <div class="min-w-0">
                        <h1 class="text-[27px] font-black tracking-tight leading-none text-white"
                            :class="font_family === 'serif' ? 'font-editorial text-[30px]' : ''"
                            x-text="content.personal?.full_name || 'Your Name'"></h1>
                        <p class="text-[13px] font-semibold text-white/80 mt-1.5"
                           x-text="content.personal?.job_title || 'Executive Title'"></p>
                    </div>
                    <template x-if="content.personal?.show_photo">
                        <div class="w-16 h-16 rounded-2xl border-2 border-white/25 bg-white/10 shrink-0 overflow-hidden flex items-center justify-center font-black text-lg text-white">
                            <img x-show="avatarUrl()" :src="avatarUrl()" alt="Portrait" class="w-full h-full object-cover">
                            <span x-show="!avatarUrl()" x-text="initials()"></span>
                        </div>
                    </template>
                </div>
                <div class="flex flex-wrap items-center gap-x-5 gap-y-1.5 px-3.5 py-2 rounded-xl bg-white/10 text-[10.5px] text-white/95">
                    <span x-show="content.personal?.email" class="font-semibold" x-text="content.personal.email"></span>
                    <span x-show="content.personal?.phone" x-text="content.personal.phone"></span>
                    <span x-show="content.personal?.location" x-text="content.personal.location"></span>
                    <span x-show="content.personal?.linkedin" class="text-white/80" x-text="content.personal.linkedin"></span>
                </div>
            </header>

            <div class="px-2" :class="density === 'compact' ? 'space-y-4' : 'space-y-5'">
                <section x-show="content.section_visibility?.summary && content.summary" class="pb-4 border-b border-slate-200">
                    <div class="text-[10px] font-black uppercase tracking-[0.2em] mb-1.5" :style="`color: ${accent_color}`">Executive Summary</div>
                    <p class="text-[12px] leading-relaxed text-slate-700 font-medium whitespace-pre-line" x-text="content.summary"></p>
                </section>

                <div class="grid grid-cols-12 gap-7 items-start">
                    <div class="col-span-8 space-y-5">
                        <section x-show="content.section_visibility?.experience && (content.experience?.length || 0) > 0">
                            <h2 class="text-[11px] font-black uppercase tracking-[0.18em] pb-1.5 border-b mb-3.5"
                                :style="`color: ${accent_color}; border-color: ${accent_color}`">Leadership Experience</h2>
                            <div class="space-y-4">
                                <template x-for="(exp, idx) in content.experience" :key="'ex-exp-' + idx">
                                    <div x-show="exp.role || exp.company">
                                        <div class="flex items-baseline justify-between gap-2">
                                            <h3 class="font-extrabold text-[13px] text-slate-900" x-text="exp.role || exp.company"></h3>
                                            <span class="text-[10.5px] font-bold text-slate-500" x-text="[exp.start_date, exp.end_date].filter(Boolean).join(' – ')"></span>
                                        </div>
                                        <div class="text-[11px] font-bold mb-1.5" :style="`color: ${accent_color}`"
                                             x-text="[exp.company, exp.location].filter(Boolean).join(' • ')"></div>
                                        <ul class="space-y-1 text-slate-700 pl-4 list-disc">
                                            <template x-for="(line, bIdx) in parseBullets(exp.bullets_text)" :key="bIdx">
                                                <li x-text="line"></li>
                                            </template>
                                        </ul>
                                    </div>
                                </template>
                            </div>
                        </section>

                        <section x-show="content.section_visibility?.projects && (content.projects?.length || 0) > 0">
                            <h2 class="text-[11px] font-black uppercase tracking-[0.18em] pb-1.5 border-b mb-3"
                                :style="`color: ${accent_color}; border-color: ${accent_color}`">Strategic Initiatives</h2>
                            <div class="space-y-2.5">
                                <template x-for="(proj, idx) in content.projects" :key="'ex-pr-' + idx">
                                    <div x-show="proj.name">
                                        <div class="font-bold text-slate-900" x-text="proj.name"></div>
                                        <p x-show="proj.description" class="text-slate-600 mt-0.5" x-text="proj.description"></p>
                                    </div>
                                </template>
                            </div>
                        </section>
                    </div>

                    <aside class="col-span-4 space-y-5">
                        <section x-show="content.section_visibility?.skills && (content.skills?.length || 0) > 0"
                                 class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                            <h2 class="text-[10.5px] font-black uppercase tracking-[0.18em] mb-2.5" :style="`color: ${accent_color}`">Core Competencies</h2>
                            <ul class="space-y-1.5 text-[11px] font-semibold text-slate-800">
                                <template x-for="(sk, idx) in content.skills" :key="'ex-sk-' + idx">
                                    <li class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" :style="`background-color: ${accent_color}`"></span>
                                        <span x-text="sk"></span>
                                    </li>
                                </template>
                            </ul>
                        </section>

                        <section x-show="content.section_visibility?.education && (content.education?.length || 0) > 0" class="p-4 rounded-xl border border-slate-200">
                            <h2 class="text-[10.5px] font-black uppercase tracking-[0.18em] mb-2.5" :style="`color: ${accent_color}`">Education</h2>
                            <div class="space-y-2.5">
                                <template x-for="(edu, idx) in content.education" :key="'ex-ed-' + idx">
                                    <div x-show="edu.degree || edu.school">
                                        <div class="font-bold text-[11.5px] text-slate-900" x-text="edu.degree"></div>
                                        <div class="text-[10.5px] text-slate-600" x-text="edu.school"></div>
                                        <div class="text-[9.5px] text-slate-400" x-text="[edu.start_date, edu.end_date].filter(Boolean).join(' – ')"></div>
                                    </div>
                                </template>
                            </div>
                        </section>

                        <section x-show="content.section_visibility?.awards && (content.awards?.length || 0) > 0">
                            <h2 class="text-[10.5px] font-black uppercase tracking-[0.18em] mb-2" :style="`color: ${accent_color}`">Honors</h2>
                            <div class="space-y-1.5 text-[10.5px]">
                                <template x-for="(aw, idx) in content.awards" :key="'ex-aw-' + idx">
                                    <div x-show="aw.title">
                                        <div class="font-bold text-slate-800" x-text="aw.title"></div>
                                        <div class="text-slate-500" x-text="[aw.issuer, aw.date].filter(Boolean).join(' • ')"></div>
                                    </div>
                                </template>
                            </div>
                        </section>
                    </aside>
                </div>
            </div>
        </div>
    </template>

    {{-- =======================================================================
         6. CREATIVE CARD (FlowCV Soft Pastel Duo Sidebar + Framed Portrait)
         ======================================================================= --}}
    <template x-if="template_style === 'creative_card'">
        <div class="grid grid-cols-12 min-h-[1122px] bg-white">
            <aside class="col-span-4 border-r p-6 space-y-5"
                   :style="`background-color: ${accent_color}12; border-color: ${accent_color}25;`">
                <div class="p-4 rounded-2xl bg-white shadow-sm border" :style="`border-color: ${accent_color}22;`">
                    <template x-if="content.personal?.show_photo">
                        <div class="w-20 h-20 rounded-2xl mb-3 flex items-center justify-center text-white font-black text-xl shadow-sm overflow-hidden"
                             :style="`background-color: ${accent_color}`">
                            <img x-show="avatarUrl()" :src="avatarUrl()" alt="Portrait" class="w-full h-full object-cover">
                            <span x-show="!avatarUrl()" x-text="initials()"></span>
                        </div>
                    </template>
                    <h1 class="text-[20px] font-black leading-tight text-slate-900"
                        :class="font_family === 'serif' ? 'font-editorial' : ''"
                        x-text="content.personal?.full_name || 'Your Name'"></h1>
                    <p class="text-[11.5px] font-bold mt-1" :style="`color: ${accent_color}`"
                       x-text="content.personal?.job_title || 'Title'"></p>
                </div>

                <div class="space-y-1.5 text-[10.5px] text-slate-700 px-1">
                    <div class="text-[9.5px] font-extrabold uppercase tracking-[0.18em] mb-1.5" :style="`color: ${accent_color}`">Reach Out</div>
                    <div x-show="content.personal?.email" class="font-semibold text-slate-900 break-all" x-text="content.personal.email"></div>
                    <div x-show="content.personal?.phone" x-text="content.personal.phone"></div>
                    <div x-show="content.personal?.location" x-text="content.personal.location"></div>
                    <div x-show="content.personal?.website" class="font-semibold underline" :style="`color: ${accent_color}`" x-text="content.personal.website"></div>
                    <div x-show="content.personal?.linkedin" class="break-all" x-text="content.personal.linkedin"></div>
                </div>

                <section x-show="content.section_visibility?.skills && (content.skills?.length || 0) > 0" class="px-1 pt-2 border-t" :style="`border-color: ${accent_color}20;`">
                    <div class="text-[9.5px] font-extrabold uppercase tracking-[0.18em] mb-2" :style="`color: ${accent_color}`">Toolbox &amp; Craft</div>
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="(sk, idx) in content.skills" :key="'cr-sk-' + idx">
                            <span class="px-2.5 py-0.5 rounded-full bg-white text-[10px] font-bold shadow-sm border"
                                  :style="`border-color: ${accent_color}30; color: ${accent_color};`" x-text="sk"></span>
                        </template>
                    </div>
                </section>

                <section x-show="content.section_visibility?.languages && (content.languages?.length || 0) > 0" class="px-1 pt-2 border-t" :style="`border-color: ${accent_color}20;`">
                    <div class="text-[9.5px] font-extrabold uppercase tracking-[0.18em] mb-2" :style="`color: ${accent_color}`">Languages</div>
                    <div class="space-y-1.5 text-[10.5px]">
                        <template x-for="(lang, idx) in content.languages" :key="'cr-lg-' + idx">
                            <div class="flex justify-between items-center" x-show="lang.name">
                                <span class="font-bold text-slate-800" x-text="lang.name"></span>
                                <span class="text-[9.5px] text-slate-500" x-text="lang.level"></span>
                            </div>
                        </template>
                    </div>
                </section>

                <section x-show="content.section_visibility?.awards && (content.awards?.length || 0) > 0" class="px-1 pt-2 border-t" :style="`border-color: ${accent_color}20;`">
                    <div class="text-[9.5px] font-extrabold uppercase tracking-[0.18em] mb-2" :style="`color: ${accent_color}`">Recognition</div>
                    <div class="space-y-1.5 text-[10.5px]">
                        <template x-for="(aw, idx) in content.awards" :key="'cr-aw-' + idx">
                            <div x-show="aw.title">
                                <div class="font-bold text-slate-800" x-text="aw.title"></div>
                                <div class="text-[9.5px] text-slate-500" x-text="[aw.issuer, aw.date].filter(Boolean).join(' • ')"></div>
                            </div>
                        </template>
                    </div>
                </section>
            </aside>

            <main class="col-span-8 space-y-5" :class="density === 'compact' ? 'p-6 space-y-4' : 'p-8 space-y-6'">
                <section x-show="content.section_visibility?.summary && content.summary">
                    <div class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-2" :style="`color: ${accent_color}`">About Me</div>
                    <p class="text-slate-700 leading-relaxed whitespace-pre-line" x-text="content.summary"></p>
                </section>

                <section x-show="content.section_visibility?.experience && (content.experience?.length || 0) > 0">
                    <div class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-3 pb-1 border-b border-slate-200" :style="`color: ${accent_color}`">Work History</div>
                    <div class="space-y-4">
                        <template x-for="(exp, idx) in content.experience" :key="'cr-exp-' + idx">
                            <div x-show="exp.role || exp.company">
                                <div class="flex items-baseline justify-between gap-2">
                                    <h3 class="font-extrabold text-[13px] text-slate-900" x-text="exp.role || exp.company"></h3>
                                    <span class="text-[10px] font-semibold text-slate-500" x-text="[exp.start_date, exp.end_date].filter(Boolean).join(' – ')"></span>
                                </div>
                                <div class="text-[11px] font-bold mb-1" :style="`color: ${accent_color}`"
                                     x-text="[exp.company, exp.location].filter(Boolean).join(' — ')"></div>
                                <ul class="space-y-1 text-slate-700 pl-3.5 list-disc">
                                    <template x-for="(line, bIdx) in parseBullets(exp.bullets_text)" :key="bIdx">
                                        <li x-text="line"></li>
                                    </template>
                                </ul>
                            </div>
                        </template>
                    </div>
                </section>

                <section x-show="content.section_visibility?.projects && (content.projects?.length || 0) > 0">
                    <div class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-3 pb-1 border-b border-slate-200" :style="`color: ${accent_color}`">Featured Portfolio</div>
                    <div class="grid grid-cols-1 gap-2.5">
                        <template x-for="(proj, idx) in content.projects" :key="'cr-pr-' + idx">
                            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/60" x-show="proj.name">
                                <div class="flex items-baseline justify-between gap-2">
                                    <span class="font-bold text-slate-900" x-text="proj.name"></span>
                                    <span class="text-[10px] font-semibold" :style="`color: ${accent_color}`" x-text="proj.link"></span>
                                </div>
                                <p x-show="proj.description" class="text-slate-600 mt-1" x-text="proj.description"></p>
                            </div>
                        </template>
                    </div>
                </section>

                <section x-show="content.section_visibility?.education && (content.education?.length || 0) > 0">
                    <div class="text-[10px] font-extrabold uppercase tracking-[0.18em] mb-2.5 pb-1 border-b border-slate-200" :style="`color: ${accent_color}`">Education</div>
                    <div class="space-y-2.5">
                        <template x-for="(edu, idx) in content.education" :key="'cr-ed-' + idx">
                            <div class="flex items-baseline justify-between gap-2" x-show="edu.degree || edu.school">
                                <div>
                                    <div class="font-bold text-slate-900" x-text="edu.degree"></div>
                                    <div class="text-slate-500 text-[10.5px]" x-text="edu.school"></div>
                                </div>
                                <span class="text-[10px] text-slate-400 shrink-0" x-text="[edu.start_date, edu.end_date].filter(Boolean).join(' – ')"></span>
                            </div>
                        </template>
                    </div>
                </section>
            </main>
        </div>
    </template>

    {{-- =======================================================================
         7. TECH STACK (Silicon Valley Dev)
         ======================================================================= --}}
    <template x-if="template_style === 'tech_stack'">
        <div class="min-h-[1122px] bg-white border-t-[5px]" :style="`border-top-color: ${accent_color}`" :class="density === 'compact' ? 'p-7 space-y-4' : 'p-9 space-y-5'">
            <header class="flex flex-wrap items-start justify-between gap-4 pb-4 border-b border-slate-200">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded font-tech-mono text-[10px] font-semibold mb-1.5"
                         :style="`background-color: ${accent_color}14; color: ${accent_color}`">
                        <span>$ whoami --role</span>
                    </div>
                    <h1 class="text-[25px] font-black tracking-tight text-slate-900"
                        x-text="content.personal?.full_name || 'Your Name'"></h1>
                    <p class="font-tech-mono text-[12px] font-semibold mt-0.5" :style="`color: ${accent_color}`"
                       x-text="content.personal?.job_title || 'Software Engineer'"></p>
                </div>
                <div class="font-tech-mono text-[10px] text-slate-600 space-y-1 text-right">
                    <div x-show="content.personal?.email" class="font-bold text-slate-900" x-text="content.personal.email"></div>
                    <div x-show="content.personal?.website" class="font-semibold" :style="`color: ${accent_color}`" x-text="content.personal.website"></div>
                    <div x-show="content.personal?.linkedin" x-text="content.personal.linkedin"></div>
                    <div x-show="content.personal?.phone || content.personal?.location"
                         x-text="[content.personal.phone, content.personal.location].filter(Boolean).join(' | ')"></div>
                </div>
            </header>

            <section x-show="content.section_visibility?.summary && content.summary">
                <div class="font-tech-mono text-[10px] font-bold uppercase tracking-wider mb-1" :style="`color: ${accent_color}`">// 01. SYSTEM_SUMMARY</div>
                <p class="text-slate-700 leading-relaxed whitespace-pre-line" x-text="content.summary"></p>
            </section>

            <section x-show="content.section_visibility?.skills && (content.skills?.length || 0) > 0"
                     class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                <div class="font-tech-mono text-[10px] font-bold uppercase tracking-wider mb-2" :style="`color: ${accent_color}`">// 02. TECH_STACK</div>
                <div class="flex flex-wrap gap-1.5">
                    <template x-for="(sk, idx) in content.skills" :key="'ts-sk-' + idx">
                        <span class="px-2 py-0.5 rounded bg-white border border-slate-300 font-tech-mono text-[10.5px] font-semibold text-slate-800 shadow-sm" x-text="sk"></span>
                    </template>
                </div>
            </section>

            <section x-show="content.section_visibility?.experience && (content.experience?.length || 0) > 0">
                <div class="font-tech-mono text-[10px] font-bold uppercase tracking-wider mb-3" :style="`color: ${accent_color}`">// 03. PRODUCTION_EXPERIENCE</div>
                <div class="space-y-4">
                    <template x-for="(exp, idx) in content.experience" :key="'ts-exp-' + idx">
                        <div x-show="exp.role || exp.company">
                            <div class="flex flex-wrap items-baseline justify-between gap-2">
                                <h3 class="font-extrabold text-[13px] text-slate-900">
                                    <span x-text="exp.role"></span>
                                    <span class="font-normal text-slate-400"> @ </span>
                                    <span :style="`color: ${accent_color}`" x-text="exp.company"></span>
                                </h3>
                                <span class="font-tech-mono text-[10px] text-slate-500"
                                      x-text="[exp.start_date, exp.end_date].filter(Boolean).join(' -> ')"></span>
                            </div>
                            <ul class="space-y-1 text-slate-700 pl-4 list-disc mt-1">
                                <template x-for="(line, bIdx) in parseBullets(exp.bullets_text)" :key="bIdx">
                                    <li x-text="line"></li>
                                </template>
                            </ul>
                        </div>
                    </template>
                </div>
            </section>

            <section x-show="content.section_visibility?.projects && (content.projects?.length || 0) > 0">
                <div class="font-tech-mono text-[10px] font-bold uppercase tracking-wider mb-2.5" :style="`color: ${accent_color}`">// 04. OPEN_SOURCE &amp; ARCHITECTURE</div>
                <div class="grid grid-cols-2 gap-3">
                    <template x-for="(proj, idx) in content.projects" :key="'ts-pr-' + idx">
                        <div class="p-3 rounded-lg border border-slate-200 bg-slate-50/50" x-show="proj.name">
                            <div class="font-tech-mono font-bold text-[11px] text-slate-900" x-text="proj.name"></div>
                            <div x-show="proj.link" class="font-tech-mono text-[9.5px] mb-1 truncate" :style="`color: ${accent_color}`" x-text="proj.link"></div>
                            <p x-show="proj.description" class="text-[10.5px] text-slate-600" x-text="proj.description"></p>
                        </div>
                    </template>
                </div>
            </section>

            <div class="grid grid-cols-2 gap-6 pt-2 border-t border-slate-200">
                <section x-show="content.section_visibility?.education && (content.education?.length || 0) > 0">
                    <div class="font-tech-mono text-[10px] font-bold uppercase tracking-wider mb-2" :style="`color: ${accent_color}`">// 05. EDUCATION</div>
                    <template x-for="(edu, idx) in content.education" :key="'ts-ed-' + idx">
                        <div class="mb-2" x-show="edu.degree || edu.school">
                            <div class="font-bold text-slate-900" x-text="edu.degree"></div>
                            <div class="text-[10.5px] text-slate-600" x-text="[edu.school, [edu.start_date, edu.end_date].filter(Boolean).join('–')].filter(Boolean).join(' • ')"></div>
                        </div>
                    </template>
                </section>
                <section x-show="content.section_visibility?.awards && (content.awards?.length || 0) > 0">
                    <div class="font-tech-mono text-[10px] font-bold uppercase tracking-wider mb-2" :style="`color: ${accent_color}`">// 06. HONORS</div>
                    <template x-for="(aw, idx) in content.awards" :key="'ts-aw-' + idx">
                        <div class="mb-1.5 text-[10.5px]" x-show="aw.title">
                            <span class="font-bold text-slate-900" x-text="aw.title"></span>
                            <span class="text-slate-500" x-text="' — ' + [aw.issuer, aw.date].filter(Boolean).join(', ')"></span>
                        </div>
                    </template>
                </section>
            </div>
        </div>
    </template>

    {{-- =======================================================================
         8. MINIMAL SWISS (Zurich Swiss Grid)
         ======================================================================= --}}
    <template x-if="template_style === 'minimal_swiss'">
        <div class="min-h-[1122px] bg-white" :class="density === 'compact' ? 'p-8 space-y-5' : 'p-10 space-y-6'">
            <header class="border-t-[4px] pt-5 pb-5 border-b border-slate-200 grid grid-cols-12 gap-4 items-end"
                    :style="`border-top-color: ${accent_color}`">
                <div class="col-span-8">
                    <h1 class="text-[30px] font-black tracking-tight leading-none text-slate-900"
                        :class="font_family === 'serif' ? 'font-editorial' : ''"
                        x-text="content.personal?.full_name || 'Your Name'"></h1>
                    <p class="text-[13px] font-semibold mt-2" :style="`color: ${accent_color}`"
                       x-text="content.personal?.job_title || 'Title'"></p>
                </div>
                <div class="col-span-4 text-right text-[10.5px] text-slate-600 space-y-0.5">
                    <div x-show="content.personal?.email" class="font-semibold text-slate-900" x-text="content.personal.email"></div>
                    <div x-show="content.personal?.phone" x-text="content.personal.phone"></div>
                    <div x-show="content.personal?.location" x-text="content.personal.location"></div>
                    <div x-show="content.personal?.website" x-text="content.personal.website"></div>
                </div>
            </header>

            <section x-show="content.section_visibility?.summary && content.summary" class="grid grid-cols-12 gap-5 border-b border-slate-100 pb-4">
                <div class="col-span-3 text-[9.5px] font-extrabold uppercase tracking-[0.2em] text-slate-400 pt-0.5">Profile</div>
                <p class="col-span-9 text-slate-700 leading-relaxed whitespace-pre-line" x-text="content.summary"></p>
            </section>

            <section x-show="content.section_visibility?.experience && (content.experience?.length || 0) > 0" class="grid grid-cols-12 gap-5 border-b border-slate-100 pb-5">
                <div class="col-span-3 text-[9.5px] font-extrabold uppercase tracking-[0.2em] pt-0.5" :style="`color: ${accent_color}`">Experience</div>
                <div class="col-span-9 space-y-4">
                    <template x-for="(exp, idx) in content.experience" :key="'sw-exp-' + idx">
                        <div x-show="exp.role || exp.company">
                            <div class="flex items-baseline justify-between gap-2">
                                <h3 class="font-extrabold text-[13px] text-slate-900" x-text="exp.role || exp.company"></h3>
                                <span class="text-[10.5px] font-medium text-slate-500 shrink-0"
                                      x-text="[exp.start_date, exp.end_date].filter(Boolean).join(' – ')"></span>
                            </div>
                            <div class="text-[11px] font-semibold text-slate-500 mb-1.5"
                                 x-text="[exp.company, exp.location].filter(Boolean).join(' / ')"></div>
                            <ul class="space-y-1 text-slate-700 pl-3.5 list-disc">
                                <template x-for="(line, bIdx) in parseBullets(exp.bullets_text)" :key="bIdx">
                                    <li x-text="line"></li>
                                </template>
                            </ul>
                        </div>
                    </template>
                </div>
            </section>

            <section x-show="content.section_visibility?.education && (content.education?.length || 0) > 0" class="grid grid-cols-12 gap-5 border-b border-slate-100 pb-4">
                <div class="col-span-3 text-[9.5px] font-extrabold uppercase tracking-[0.2em] pt-0.5" :style="`color: ${accent_color}`">Education</div>
                <div class="col-span-9 space-y-2.5">
                    <template x-for="(edu, idx) in content.education" :key="'sw-ed-' + idx">
                        <div class="flex items-baseline justify-between gap-2" x-show="edu.degree || edu.school">
                            <div>
                                <div class="font-bold text-slate-900" x-text="edu.degree"></div>
                                <div class="text-[11px] text-slate-600" x-text="[edu.school, edu.details].filter(Boolean).join(' • ')"></div>
                            </div>
                            <span class="text-[10px] text-slate-400 shrink-0" x-text="[edu.start_date, edu.end_date].filter(Boolean).join(' – ')"></span>
                        </div>
                    </template>
                </div>
            </section>

            <section x-show="content.section_visibility?.skills && (content.skills?.length || 0) > 0" class="grid grid-cols-12 gap-5 border-b border-slate-100 pb-4">
                <div class="col-span-3 text-[9.5px] font-extrabold uppercase tracking-[0.2em] pt-0.5" :style="`color: ${accent_color}`">Capabilities</div>
                <div class="col-span-9 text-slate-800 font-medium leading-relaxed" x-text="(content.skills || []).join('   •   ')"></div>
            </section>

            <section x-show="content.section_visibility?.languages && (content.languages?.length || 0) > 0" class="grid grid-cols-12 gap-5">
                <div class="col-span-3 text-[9.5px] font-extrabold uppercase tracking-[0.2em] pt-0.5" :style="`color: ${accent_color}`">Languages</div>
                <div class="col-span-9 flex flex-wrap gap-x-6 gap-y-1">
                    <template x-for="(lang, idx) in content.languages" :key="'sw-lg-' + idx">
                        <span x-show="lang.name"><strong x-text="lang.name"></strong> <span class="text-slate-500" x-text="'(' + lang.level + ')'"></span></span>
                    </template>
                </div>
            </section>
        </div>
    </template>

    {{-- =======================================================================
         9. ATS CLASSIC (Wall Street Double-Rule)
         ======================================================================= --}}
    <template x-if="template_style === 'ats_classic'">
        <div class="min-h-[1122px] bg-white text-slate-900" :class="density === 'compact' ? 'p-8 space-y-3.5' : 'p-10 space-y-4'">
            <header class="text-center pb-3 border-b-2 border-slate-900">
                <h1 class="text-[24px] font-bold tracking-wide uppercase text-slate-900"
                    :class="font_family === 'serif' ? 'font-editorial tracking-normal' : ''"
                    x-text="content.personal?.full_name || 'YOUR NAME'"></h1>
                <div class="text-[11.5px] font-semibold text-slate-700 mt-0.5" x-text="content.personal?.job_title"></div>
                <div class="text-[10.5px] text-slate-600 mt-1.5 flex flex-wrap items-center justify-center gap-x-2.5 gap-y-0.5">
                    <span x-show="content.personal?.location" x-text="content.personal.location"></span>
                    <span x-show="content.personal?.phone">&bull; <span x-text="content.personal.phone"></span></span>
                    <span x-show="content.personal?.email">&bull; <span x-text="content.personal.email"></span></span>
                    <span x-show="content.personal?.linkedin">&bull; <span x-text="content.personal.linkedin"></span></span>
                </div>
            </header>

            <section x-show="content.section_visibility?.summary && content.summary">
                <h2 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-900 border-b border-slate-400 pb-0.5 mb-1.5">Professional Summary</h2>
                <p class="text-[11.5px] leading-relaxed text-slate-800 whitespace-pre-line" x-text="content.summary"></p>
            </section>

            <section x-show="content.section_visibility?.experience && (content.experience?.length || 0) > 0">
                <h2 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-900 border-b border-slate-400 pb-0.5 mb-2">Professional Experience</h2>
                <div class="space-y-3">
                    <template x-for="(exp, idx) in content.experience" :key="'ats-exp-' + idx">
                        <div x-show="exp.role || exp.company">
                            <div class="flex justify-between font-bold text-[12px]">
                                <span x-text="exp.company || exp.role"></span>
                                <span x-text="exp.location"></span>
                            </div>
                            <div class="flex justify-between italic text-[11.5px] text-slate-800 mb-1">
                                <span x-text="exp.role"></span>
                                <span x-text="[exp.start_date, exp.end_date].filter(Boolean).join(' – ')"></span>
                            </div>
                            <ul class="space-y-0.5 text-slate-800 pl-4 list-disc">
                                <template x-for="(line, bIdx) in parseBullets(exp.bullets_text)" :key="bIdx">
                                    <li x-text="line"></li>
                                </template>
                            </ul>
                        </div>
                    </template>
                </div>
            </section>

            <section x-show="content.section_visibility?.education && (content.education?.length || 0) > 0">
                <h2 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-900 border-b border-slate-400 pb-0.5 mb-2">Education</h2>
                <div class="space-y-2">
                    <template x-for="(edu, idx) in content.education" :key="'ats-ed-' + idx">
                        <div x-show="edu.degree || edu.school">
                            <div class="flex justify-between font-bold text-[11.5px]">
                                <span x-text="edu.school || edu.degree"></span>
                                <span x-text="[edu.start_date, edu.end_date].filter(Boolean).join(' – ')"></span>
                            </div>
                            <div class="text-[11px] text-slate-800" x-text="[edu.degree, edu.details].filter(Boolean).join(' — ')"></div>
                        </div>
                    </template>
                </div>
            </section>

            <section x-show="content.section_visibility?.skills && (content.skills?.length || 0) > 0">
                <h2 class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-900 border-b border-slate-400 pb-0.5 mb-1.5">Skills &amp; Additional Information</h2>
                <p class="text-[11px] text-slate-800">
                    <strong>Technical &amp; Core Competencies:</strong>
                    <span x-text="(content.skills || []).join(', ')"></span>
                </p>
                <p x-show="content.section_visibility?.awards && (content.awards?.length || 0) > 0" class="text-[11px] text-slate-800 mt-1">
                    <strong>Certifications &amp; Honors:</strong>
                    <template x-for="(aw, idx) in content.awards" :key="'ats-aw-' + idx">
                        <span x-text="(idx > 0 ? '; ' : '') + aw.title + (aw.date ? ' (' + aw.date + ')' : '')"></span>
                    </template>
                </p>
            </section>
        </div>
    </template>

    {{-- =======================================================================
         10. COMPACT ONE-PAGER (High Density)
         ======================================================================= --}}
    <template x-if="template_style === 'compact_onepage'">
        <div class="min-h-[1122px] bg-white p-7 space-y-4">
            <header class="flex items-end justify-between gap-4 border-b-2 pb-3" :style="`border-color: ${accent_color}`">
                <div>
                    <h1 class="text-[24px] font-black tracking-tight text-slate-900 leading-none"
                        :class="font_family === 'serif' ? 'font-editorial' : ''"
                        x-text="content.personal?.full_name || 'Your Name'"></h1>
                    <p class="text-[12px] font-bold mt-1" :style="`color: ${accent_color}`"
                       x-text="content.personal?.job_title || 'Title'"></p>
                </div>
                <div class="text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider">One-Page Executive Sheet</div>
            </header>

            <div class="grid grid-cols-3 gap-2 px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-[10.5px] text-slate-700">
                <div class="truncate"><strong class="text-slate-900">Email:</strong> <span x-text="content.personal?.email"></span></div>
                <div class="truncate text-center"><strong class="text-slate-900">Phone:</strong> <span x-text="content.personal?.phone"></span></div>
                <div class="truncate text-right"><strong class="text-slate-900">Loc:</strong> <span x-text="content.personal?.location"></span></div>
            </div>

            <section x-show="content.section_visibility?.summary && content.summary"
                     class="p-3 rounded-lg border text-[11px] leading-snug text-slate-700"
                     :style="`border-color: ${accent_color}30; background-color: ${accent_color}06;`">
                <p class="whitespace-pre-line" x-text="content.summary"></p>
            </section>

            <section x-show="content.section_visibility?.experience && (content.experience?.length || 0) > 0">
                <h2 class="text-[10.5px] font-extrabold uppercase tracking-[0.16em] mb-2" :style="`color: ${accent_color}`">Experience</h2>
                <div class="space-y-3">
                    <template x-for="(exp, idx) in content.experience" :key="'cp-exp-' + idx">
                        <div x-show="exp.role || exp.company">
                            <div class="flex items-baseline justify-between gap-2">
                                <span class="font-bold text-[12px] text-slate-900">
                                    <span x-text="exp.role"></span> — <span :style="`color: ${accent_color}`" x-text="exp.company"></span>
                                </span>
                                <span class="text-[10px] font-semibold text-slate-500 shrink-0"
                                      x-text="[exp.start_date, exp.end_date].filter(Boolean).join(' – ')"></span>
                            </div>
                            <ul class="space-y-0.5 text-[11px] text-slate-700 pl-3.5 list-disc mt-0.5">
                                <template x-for="(line, bIdx) in parseBullets(exp.bullets_text)" :key="bIdx">
                                    <li x-text="line"></li>
                                </template>
                            </ul>
                        </div>
                    </template>
                </div>
            </section>

            <div class="grid grid-cols-12 gap-5 pt-2 border-t border-slate-200">
                <section class="col-span-6" x-show="content.section_visibility?.skills && (content.skills?.length || 0) > 0">
                    <h2 class="text-[10px] font-extrabold uppercase tracking-[0.16em] mb-2" :style="`color: ${accent_color}`">Skills &amp; Domain Expertise</h2>
                    <div class="flex flex-wrap gap-1">
                        <template x-for="(sk, idx) in content.skills" :key="'cp-sk-' + idx">
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-[10px] font-semibold text-slate-800" x-text="sk"></span>
                        </template>
                    </div>
                </section>

                <section class="col-span-6" x-show="content.section_visibility?.education && (content.education?.length || 0) > 0">
                    <h2 class="text-[10px] font-extrabold uppercase tracking-[0.16em] mb-2" :style="`color: ${accent_color}`">Education &amp; Credentials</h2>
                    <div class="space-y-1.5">
                        <template x-for="(edu, idx) in content.education" :key="'cp-ed-' + idx">
                            <div x-show="edu.degree || edu.school" class="text-[10.5px]">
                                <div class="font-bold text-slate-900" x-text="edu.degree"></div>
                                <div class="text-slate-600" x-text="[edu.school, edu.end_date].filter(Boolean).join(' • ')"></div>
                            </div>
                        </template>
                    </div>
                </section>
            </div>
        </div>
    </template>
</div>
