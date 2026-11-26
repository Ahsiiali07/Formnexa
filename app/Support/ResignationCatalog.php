<?php

namespace App\Support;

class ResignationCatalog
{
    /**
     * Unique landing page definitions for high-intent resignation scenarios.
     * Draft copy is marked with [DRAFT] for owner review as requested.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function landingPages(): array
    {
        return [
            'new-job' => [
                'slug' => 'new-job',
                'path' => 'resignation-letter/new-job',
                'title' => 'Resignation Letter for a New Job (Free Template)',
                'description' => 'Write a polite resignation letter for a new job. Customize your last day, download PDF or Word, and keep things professional.',
                'h1' => 'Resignation Letter Generator for a New Job',
                'badge' => 'New Opportunity • Grateful Tone',
                'subtitle' => 'Leave on positive terms when stepping into a new role. Pre-loaded with appreciative phrasing and clean handover commitments.',
                'preset_reason' => 'new_job',
                'preset_tone' => 'grateful',
                'preset_days' => 14,
                'draft_note' => 'DRAFT — Please review and approve final copy for the New Job landing page.',
                'when_to_use' => [
                    'heading' => 'Should You Mention Your New Job in Writing?',
                    'paragraphs' => [
                        'You do not have to name your new employer or reveal your next salary in a formal resignation letter. Most HR teams only need your official end date and confirmation that you are leaving voluntarily.',
                        'Saying simply that you have accepted a new opportunity is polite and standard. If your current employer asks during an exit conversation, you can decide how much detail you feel comfortable sharing verbally.',
                    ],
                ],
                'sample_heading' => 'Sample Resignation Letter for a New Job (DRAFT)',
                'sample_letter' => "Dear [Manager Name],\n\nPlease accept this letter as formal notice that I am resigning from my role as [Job Title] at [Company Name] to accept a new career opportunity. My last working day will be [Date].\n\nThank you sincerely for the guidance, trust, and opportunities for growth you have given me during my time here. Working with this team has taught me a great deal that I will carry forward.\n\nOver my remaining two weeks, I will document my active projects and help train whoever takes over my responsibilities so the transition stays smooth.\n\nSincerely,\n[Your Name]",
                'tips' => [
                    'Wait until your new offer letter is signed and background checks clear before handing in your notice.',
                    'Avoid comparing your new pay or perks to your current workplace inside your resignation letter.',
                    'Speak to your direct manager one-on-one before sending the written letter to HR.',
                    'Save personal portfolio samples (without confidential company data) before IT access ends.',
                ],
                'faqs' => [
                    [
                        'q' => 'Do I need to name my new employer in my resignation letter?',
                        'a' => 'No. Writing that you are pursuing a new opportunity is completely sufficient for your official HR file. Keeping your next company private avoids unnecessary questions or non-compete friction.',
                    ],
                    [
                        'q' => 'When should I hand in my resignation after accepting a new job?',
                        'a' => 'Give notice only after you have a signed written offer and any required contingencies, such as background or reference checks, are officially cleared.',
                    ],
                    [
                        'q' => 'What if my current manager makes a counteroffer?',
                        'a' => 'Thank them professionally and take a day to consider it calmly, but remember that accepting a counteroffer often leaves the original reasons you looked elsewhere unresolved.',
                    ],
                    [
                        'q' => 'Can I adjust the tone before downloading?',
                        'a' => 'Yes. Select Grateful & Warm, Formal, or Short & Direct in the generator above, edit any line in the live A4 preview, and export as PDF or Word.',
                    ],
                ],
            ],

            'two-weeks-notice' => [
                'slug' => 'two-weeks-notice',
                'path' => 'resignation-letter/two-weeks-notice',
                'title' => 'Two Weeks Notice Letter Generator (Free PDF & Word)',
                'description' => 'Create a clean two weeks notice letter in under a minute. Auto-calculates 14 days out with instant PDF and Word download.',
                'h1' => 'Two Weeks Notice Letter Generator',
                'badge' => 'Standard 14-Day Notice • Formal Tone',
                'subtitle' => 'Automatically calculates 14 calendar days from today for your final working date. Ready for US-style standard two weeks notice.',
                'preset_reason' => 'no_reason',
                'preset_tone' => 'formal',
                'preset_days' => 14,
                'draft_note' => 'DRAFT — Please review and approve final copy for the Two Weeks Notice landing page.',
                'when_to_use' => [
                    'heading' => 'How to Count Your Two Weeks Notice Correctly',
                    'paragraphs' => [
                        'In the United States, two weeks notice usually means 14 calendar days (10 standard business days) from the day you submit your letter. For example, giving notice on a Monday typically places your last working day two Fridays later, or the following Monday depending on team schedules.',
                        'Check your employment agreement, offer letter, and company handbook first—senior roles, union contracts, or employers outside the US frequently require 30 days, one month, or longer notice periods.',
                    ],
                ],
                'sample_heading' => 'Standard Two Weeks Notice Letter Sample (DRAFT)',
                'sample_letter' => "Dear [Manager Name],\n\nPlease accept this letter as formal notice that I am resigning from my position as [Job Title] at [Company Name]. In accordance with standard two weeks notice, my final day of employment will be [Date].\n\nI appreciate the support and experience I have gained while working with the team. During the next two weeks, I will wrap up my pending assignments, update documentation, and support a smooth handover.\n\nPlease let me know how I can best assist with transition tasks before my departure date.\n\nSincerely,\n[Your Name]",
                'tips' => [
                    'Include the exact calendar date of your final working day so payroll and HR have zero ambiguity.',
                    'Submit your notice early in the week if possible so handover planning can begin promptly.',
                    'Keep the body concise—two or three short paragraphs are ideal for HR records.',
                    'Ask HR to confirm your final paycheck date and how unused accrued PTO is handled under your contract.',
                ],
                'faqs' => [
                    [
                        'q' => 'Is two weeks notice required by law?',
                        'a' => 'In at-will US employment, two weeks is a professional custom rather than a universal legal rule, unless your written contract specifies a notice period. Outside the US, statutory notice periods of one month or more are common—check your contract and local labor law.',
                    ],
                    [
                        'q' => 'Does two weeks notice include weekends?',
                        'a' => 'Yes, two weeks equals 14 calendar days, which covers 10 Monday-to-Friday business days. Always confirm your exact final date on the calendar so both you and HR agree.',
                    ],
                    [
                        'q' => 'What if my employer lets me go immediately after giving two weeks notice?',
                        'a' => 'Some companies in sensitive roles prefer an immediate departure while paying out part or all of the notice window, depending on company policy and local labor law. Keep personal copies of your resignation submission and any HR replies.',
                    ],
                    [
                        'q' => 'Should I send my two weeks notice as a PDF or Word file?',
                        'a' => 'A PDF preserves clean formatting across devices and is easy to attach to an email or sign. Word (.doc) is useful if you want to make extensive formatting tweaks locally first.',
                    ],
                ],
            ],

            'relocation' => [
                'slug' => 'relocation',
                'path' => 'resignation-letter/relocation',
                'title' => 'Resignation Letter for Moving or Relocation (Free)',
                'description' => 'Write a thoughtful resignation letter when relocating or moving away. Pick your tone and download a free PDF or Word document.',
                'h1' => 'Resignation Letter Generator for Relocation',
                'badge' => 'Moving Away • Grateful Tone',
                'subtitle' => 'Step away gracefully when moving to a new city, state, or country—while keeping the door open for future collaboration or remote work.',
                'preset_reason' => 'relocation',
                'preset_tone' => 'grateful',
                'preset_days' => 14,
                'draft_note' => 'DRAFT — Please review and approve final copy for the Relocation landing page.',
                'when_to_use' => [
                    'heading' => 'Handling a Relocation Resignation Professionally',
                    'paragraphs' => [
                        'Leaving because you are moving is one of the easiest transitions to explain because it is rooted in geography rather than dissatisfaction with the workplace. Sharing that you are relocating often helps preserve strong manager references.',
                        'If you would be open to working remotely from your new city, consider mentioning that in person before submitting your written notice—occasionally an employer may offer a hybrid or remote arrangement instead.',
                    ],
                ],
                'sample_heading' => 'Sample Resignation Letter for Moving / Relocation (DRAFT)',
                'sample_letter' => "Dear [Manager Name],\n\nPlease accept this letter as formal notice of my resignation from my position as [Job Title] at [Company Name], effective [Date]. I am relocating to a new city and will be unable to continue working on-site.\n\nThank you very much for your mentorship, support, and collaboration during my time on the team. I truly value the skills and friendships I have built here.\n\nI will work diligently through my final day to document my current projects and train colleagues on ongoing workflows so the transition stays effortless.\n\nSincerely,\n[Your Name]",
                'tips' => [
                    'Mention your move clearly without needing to reveal your new street address right away.',
                    'Give extra notice if your moving timeline is flexible and you want to help recruit or train a replacement.',
                    'Update HR with your forwarding email or phone number so tax forms (like W-2 or P60) reach you cleanly.',
                    'Ask your manager for a written recommendation or LinkedIn endorsement before leaving.',
                ],
                'faqs' => [
                    [
                        'q' => 'Should I state that I am relocating in my resignation letter?',
                        'a' => 'Yes, briefly mentioning relocation is helpful because it signals that you enjoyed working with the team and are leaving strictly due to a move.',
                    ],
                    [
                        'q' => 'How far in advance should I resign before moving?',
                        'a' => 'While two weeks is typical minimum custom in the US, offering three or four weeks when a move is planned in advance is often appreciated if your contract allows it.',
                    ],
                    [
                        'q' => 'Can I ask about remote work before submitting my letter?',
                        'a' => 'Yes. Speak with your manager first to ask whether remote work is possible from your new location before submitting your final written resignation.',
                    ],
                    [
                        'q' => 'Remember to update your forwarding address with HR?',
                        'a' => 'Yes—make sure HR has an active forwarding mailing address and personal email so year-end tax statements and final pay records arrive without delay.',
                    ],
                ],
            ],

            'personal-reasons' => [
                'slug' => 'personal-reasons',
                'path' => 'resignation-letter/personal-reasons',
                'title' => 'Resignation Letter for Personal Reasons (Private)',
                'description' => 'Write a respectful resignation letter for personal or family reasons without oversharing private details. Free PDF & Word.',
                'h1' => 'Resignation Letter for Personal Reasons',
                'badge' => 'Discreet & Respectful • Formal Tone',
                'subtitle' => 'Step back to focus on family, caregiving, or personal commitments while keeping sensitive private matters completely confidential.',
                'preset_reason' => 'personal',
                'preset_tone' => 'formal',
                'preset_days' => 14,
                'draft_note' => 'DRAFT — Please review and approve final copy for the Personal Reasons landing page.',
                'when_to_use' => [
                    'heading' => 'Keeping Personal Matters Private in HR Records',
                    'paragraphs' => [
                        'When resigning for family, caregiving, health, or private personal matters, you are not required to disclose specific medical or household details inside a formal letter. Remember that resignation letters are saved permanently in HR personnel files.',
                        'Using brief, dignified phrasing such as "due to personal and family commitments" communicates seriousness and professionalism while fully protecting your privacy.',
                    ],
                ],
                'sample_heading' => 'Sample Resignation Letter for Personal Reasons (DRAFT)',
                'sample_letter' => "Dear [Manager Name],\n\nPlease accept this letter as formal notice that I must resign from my position as [Job Title] at [Company Name] due to personal and family commitments. My final working day will be [Date].\n\nI want to thank you and the team for your understanding, guidance, and support throughout my employment here. This decision was not made lightly.\n\nDuring my remaining time, I will do my utmost to organize project files, document key tasks, and ensure a smooth handover for the team.\n\nSincerely,\n[Your Name]",
                'tips' => [
                    'Keep private medical, legal, or family specifics out of the written document.',
                    'Verify whether temporary leave, FMLA, or unpaid sabbatical options are available before resigning permanently.',
                    'Leave a personal email or phone number if you would like to stay open to rejoining in the future.',
                    'Stay calm and courteous in exit discussions so your professional reputation remains strong.',
                ],
                'faqs' => [
                    [
                        'q' => 'Do I have to explain what my personal reasons are?',
                        'a' => 'No. Simply stating "personal reasons" or "family commitments" is completely acceptable and standard across HR departments.',
                    ],
                    [
                        'q' => 'Should I include medical details if stepping away for health?',
                        'a' => 'No. Unless you are applying for a specific benefit or disability accommodation that requires documentation through HR, keep your resignation letter brief and general.',
                    ],
                    [
                        'q' => 'Can I leave the door open to return later?',
                        'a' => 'Yes. Adding a polite line thanking your manager and sharing your personal contact email helps preserve goodwill if you apply again once personal circumstances settle.',
                    ],
                    [
                        'q' => 'Should I ask about unpaid leave first?',
                        'a' => 'If your situation is temporary, consider asking HR about unpaid personal leave or flexible hours before handing in a permanent resignation.',
                    ],
                ],
            ],

            'short-notice' => [
                'slug' => 'short-notice',
                'path' => 'resignation-letter/short-notice',
                'title' => 'Short Notice & Immediate Resignation Letter Maker',
                'description' => 'Draft a professional short-notice or immediate resignation letter when you cannot give two weeks. Download free PDF or Word.',
                'h1' => 'Short Notice Resignation Letter Generator',
                'badge' => 'Short Notice / Immediate • Urgent Tone',
                'subtitle' => 'Built for unavoidable situations when you need to step down with fewer than two weeks notice—respectful, direct, and focused on quick handover.',
                'preset_reason' => 'no_reason',
                'preset_tone' => 'urgent',
                'preset_days' => 2,
                'draft_note' => 'DRAFT — Please review and approve final copy for the Short Notice landing page.',
                'when_to_use' => [
                    'heading' => 'How to Handle Resigning With Short Notice',
                    'paragraphs' => [
                        'Sometimes emergencies, health concerns, safety issues, or urgent family needs make a full two-week notice impossible. When leaving early, acknowledge the short timeline briefly, stay polite, and immediately provide a written list of active files, logins, and tasks so your team is not left guessing.',
                        'Check your written employment agreement before submitting an immediate resignation: some contracts specify notice obligations or bonus/PTO payout terms tied to notice length under local employment rules.',
                    ],
                ],
                'sample_heading' => 'Sample Short Notice Resignation Letter (DRAFT)',
                'sample_letter' => "Dear [Manager Name],\n\nPlease accept this letter as formal notice that I am resigning from my role as [Job Title] at [Company Name]. Due to unexpected personal circumstances, I am unable to provide the standard two weeks notice, and my final working day will be [Date].\n\nI sincerely apologize for any inconvenience this short timeline creates for the team. I have prepared clear handover notes summarizing my open deliverables and file locations so the transition remains as smooth as possible.\n\nThank you for your understanding and for the opportunities I have had here.\n\nSincerely,\n[Your Name]",
                'tips' => [
                    'Prepare a clean 1-page handover summary (current tasks, client contacts, key document paths) before sending your letter.',
                    'Apologize briefly once for the short timeline—do not over-explain or sound defensive.',
                    'Deliver the news directly by phone or in person if possible, then send the PDF immediately as confirmation.',
                    'Return company hardware, badges, and keys promptly and ask for a receipt of return.',
                ],
                'faqs' => [
                    [
                        'q' => 'Can I quit without giving two weeks notice?',
                        'a' => 'In at-will US employment without a fixed-term contract clause, employees can usually resign with short notice, though it may affect rehire eligibility. Always check your signed employment contract and local labor law first, especially outside the US.',
                    ],
                    [
                        'q' => 'How should I phrase an immediate resignation?',
                        'a' => 'Keep it factual, respectful, and brief. State your immediate or near-term final working date, express regret for the short notice, and list where your handover notes can be found.',
                    ],
                    [
                        'q' => 'Will giving short notice affect my final paycheck?',
                        'a' => 'In most jurisdictions, employers must still pay wages already earned for hours worked, though payout rules for unused vacation or bonuses vary widely by state, country, and company policy.',
                    ],
                    [
                        'q' => 'How can I leave on decent terms despite short notice?',
                        'a' => 'Document every active task before departing, leave passwords/files cleanly transferred to your manager, and stay courteous in all written messages.',
                    ],
                ],
            ],
        ];
    }

    /**
     * Find a landing page by slug.
     *
     * @return array<string, mixed>|null
     */
    public static function find(string $slug): ?array
    {
        return self::landingPages()[$slug] ?? null;
    }
}
