<?php

namespace App\Support;

class ResumeCatalog
{
    /**
     * 10 visually distinct CV/Resume layout engines inspired by Figma Community, FlowCV & Template.net.
     *
     * @return array<string, array<string, string>>
     */
    public static function styles(): array
    {
        return [
            'flow_sidebar' => [
                'id' => 'flow_sidebar',
                'name' => 'Atlantic Sidebar',
                'tagline' => 'Signature dark portrait sidebar with circular photo, dot language meters & crisp right main feed',
                'badge' => 'FlowCV Style',
            ],
            'editorial_figma' => [
                'id' => 'editorial_figma',
                'name' => 'Figma Editorial Serif',
                'tagline' => 'Warm archival cream canvas (#FAF8F4), Playfair Display masthead, hairline rules & balanced 2-column grid',
                'badge' => 'Figma Community',
            ],
            'timeline_pro' => [
                'id' => 'timeline_pro',
                'name' => 'Chrono Timeline & Bars',
                'tagline' => 'Vertical career spine with milestone nodes, split contact ribbon & horizontal skill mastery bars',
                'badge' => 'Template.net',
            ],
            'luxe_split' => [
                'id' => 'luxe_split',
                'name' => 'Nordic Asymmetric Split',
                'tagline' => 'Left floating monogram header block paired with soft surface summary panel & generous whitespace',
                'badge' => 'Figma Minimal',
            ],
            'executive_bold' => [
                'id' => 'executive_bold',
                'name' => 'Executive Banner',
                'tagline' => 'High-contrast edge-to-edge leadership masthead with KPI pill badges and two-column governance layout',
                'badge' => 'C-Suite',
            ],
            'creative_card' => [
                'id' => 'creative_card',
                'name' => 'Sage Studio Portfolio',
                'tagline' => 'Warm tinted studio sidebar with framed portrait, project links & visual craft tags for designers',
                'badge' => 'Designer Pick',
            ],
            'tech_stack' => [
                'id' => 'tech_stack',
                'name' => 'Silicon Valley Stack',
                'tagline' => 'Git-inspired engineering sheet with monospace section tokens, grouped stack matrix & project impact',
                'badge' => 'Engineering',
            ],
            'minimal_swiss' => [
                'id' => 'minimal_swiss',
                'name' => 'Zurich Swiss Grid',
                'tagline' => 'Architectural 28% / 72% left-rail date & section spine modeled on International Typographic Style',
                'badge' => 'Swiss Grid',
            ],
            'ats_classic' => [
                'id' => 'ats_classic',
                'name' => 'Wall Street Double-Rule',
                'tagline' => 'Traditional black-ink single column with double-ruled headers for maximum ATS parser & recruiter scan speed',
                'badge' => 'ATS 100%',
            ],
            'compact_onepage' => [
                'id' => 'compact_onepage',
                'name' => 'Hyper-Compact One-Pager',
                'tagline' => '3-column contact ribbon, compact 2-column skills/projects footer & tightened spacing for dense senior CVs',
                'badge' => 'High Density',
            ],
        ];
    }

    /**
     * 8 curated designer ink & canvas accent colors.
     *
     * @return array<int, array<string, string>>
     */
    public static function colors(): array
    {
        return [
            ['name' => 'Atlantic Navy', 'hex' => '#1B3142'],
            ['name' => 'Obsidian Slate', 'hex' => '#0F172A'],
            ['name' => 'Emerald Pine', 'hex' => '#0F5132'],
            ['name' => 'Cobalt Pulse', 'hex' => '#0052CC'],
            ['name' => 'Burgundy Serif', 'hex' => '#7C1D3A'],
            ['name' => 'Terracotta Clay', 'hex' => '#B45309'],
            ['name' => 'Deep Plum', 'hex' => '#4C1D95'],
            ['name' => 'Teal Pacific', 'hex' => '#0F766E'],
        ];
    }

    /**
     * Template gallery filter categories.
     *
     * @return array<string, string>
     */
    public static function categories(): array
    {
        return [
            'all' => 'All Templates',
            'modern' => 'FlowCV Sidebars',
            'editorial' => 'Figma Editorial',
            'creative' => 'Creative & Timeline',
            'tech' => 'Tech & Product',
            'ats' => 'Executive & ATS',
            'compact' => 'Swiss & Compact',
        ];
    }

    /**
     * Default populated resume dataset used as fallback and starting baseline.
     *
     * @return array<string, mixed>
     */
    public static function defaultContent(): array
    {
        return [
            'personal' => [
                'full_name' => 'Brian T. Wayne',
                'job_title' => 'Business Development Consultant',
                'email' => 'brian@wayneconsult.com',
                'phone' => '+1 415 734 3920',
                'location' => 'San Francisco, CA',
                'website' => 'wayneconsult.com',
                'linkedin' => 'linkedin.com/in/briantwayne',
                'show_photo' => true,
                'preset_avatar' => 'images/avatars/avatar-consultant.jpg',
            ],
            'summary' => 'Strategic Business Development Consultant with 8+ years scaling B2B SaaS revenue pipelines, forging enterprise partnerships across North America and EMEA, and turning market intelligence into repeatable growth playbooks.',
            'experience' => [
                [
                    'role' => 'Senior Business Development Consultant',
                    'company' => 'Appleseed Cloud Inc.',
                    'location' => 'San Francisco, CA',
                    'start_date' => '2022',
                    'end_date' => 'Present',
                    'bullets_text' => "- Architected enterprise GTM partnerships generating \$4.2M incremental ARR (30% YoY pipeline lift).\n- Led executive discovery workshops with Fortune 500 procurement and product leaders.\n- Built regional channel enablement playbooks adopted by 24 field account executives.",
                ],
                [
                    'role' => 'Enterprise Growth Manager',
                    'company' => 'Aexus B2B Solutions',
                    'location' => 'Los Angeles, CA',
                    'start_date' => '2018',
                    'end_date' => '2022',
                    'bullets_text' => "- Managed 18 strategic SaaS vendor launches across fintech, AI tooling, and logistics verticals.\n- Exceeded quarterly new-logo quota for 14 consecutive quarters (118% average attainment).\n- Established RevOps telemetry reducing enterprise deal cycle duration from 112 to 74 days.",
                ],
            ],
            'education' => [
                [
                    'degree' => 'Master of Business Administration (MBA)',
                    'school' => 'Haas School of Business, UC Berkeley',
                    'location' => 'Berkeley, CA',
                    'start_date' => '2016',
                    'end_date' => '2018',
                    'details' => 'Strategy & Venture Commercialization Concentration • Dean’s Honor List',
                ],
                [
                    'degree' => 'B.S. in Economics & International Trade',
                    'school' => 'University of Washington',
                    'location' => 'Seattle, WA',
                    'start_date' => '2012',
                    'end_date' => '2016',
                    'details' => 'Cum Laude • Varsity Rowing Captain',
                ],
            ],
            'skills' => [
                'Enterprise SaaS GTM',
                'Strategic Partnerships',
                'Revenue Modeling',
                'Executive Negotiation',
                'HubSpot & Salesforce',
                'Account-Based Marketing',
                'Cross-Border Expansion',
                'Board Presentations',
            ],
            'languages' => [
                ['name' => 'English', 'level' => 'Native', 'score' => 5],
                ['name' => 'Spanish', 'level' => 'Fluent', 'score' => 4],
                ['name' => 'German', 'level' => 'Conversational', 'score' => 3],
            ],
            'awards' => [
                ['title' => 'President’s Club Top 1% Earner', 'issuer' => 'Appleseed Cloud Inc.', 'date' => '2024'],
                ['title' => 'EMEA Channel Partner of the Year', 'issuer' => 'SaaS Europe Forum', 'date' => '2021'],
            ],
            'projects' => [
                [
                    'name' => 'Enterprise Pricing & Packaging Overhaul',
                    'role' => 'Commercial Lead',
                    'link' => 'wayneconsult.com/case-study',
                    'description' => 'Redesigned 3-tier usage pricing model lifting net dollar retention from 108% to 124% within two quarters.',
                ],
            ],
            'section_visibility' => [
                'summary' => true,
                'experience' => true,
                'education' => true,
                'skills' => true,
                'languages' => true,
                'awards' => true,
                'projects' => true,
            ],
        ];
    }

    /**
     * Blank structure for starting a fresh CV from scratch.
     *
     * @return array<string, mixed>
     */
    public static function blankContent(): array
    {
        return [
            'personal' => [
                'full_name' => '',
                'job_title' => '',
                'email' => '',
                'phone' => '',
                'location' => '',
                'website' => '',
                'linkedin' => '',
                'show_photo' => true,
                'preset_avatar' => '',
            ],
            'summary' => '',
            'experience' => [
                [
                    'role' => '',
                    'company' => '',
                    'location' => '',
                    'start_date' => '',
                    'end_date' => '',
                    'bullets_text' => '',
                ],
            ],
            'education' => [
                [
                    'degree' => '',
                    'school' => '',
                    'location' => '',
                    'start_date' => '',
                    'end_date' => '',
                    'details' => '',
                ],
            ],
            'skills' => [],
            'languages' => [
                ['name' => 'English', 'level' => 'Native', 'score' => 5],
            ],
            'awards' => [],
            'projects' => [],
            'section_visibility' => [
                'summary' => true,
                'experience' => true,
                'education' => true,
                'skills' => true,
                'languages' => true,
                'awards' => false,
                'projects' => false,
            ],
        ];
    }

    /**
     * 15 curated designer templates combining FlowCV, Figma Community & Template.net aesthetics.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function templates(): array
    {
        $base = self::defaultContent();

        $build = static function (array $override) use ($base): array {
            $merged = array_replace_recursive($base, $override);
            foreach (['experience', 'education', 'skills', 'languages', 'awards', 'projects'] as $listKey) {
                if (array_key_exists($listKey, $override)) {
                    $merged[$listKey] = $override[$listKey];
                }
            }
            if (array_key_exists('section_visibility', $override)) {
                $merged['section_visibility'] = array_merge($base['section_visibility'], $override['section_visibility']);
            }
            return $merged;
        };

        $categories = self::categories();
        $rawTemplates = [
            [
                'slug' => 'atlantic-flow',
                'name' => 'Atlantic Flow',
                'role' => 'Business & Strategy',
                'category' => 'modern',
                'style' => 'flow_sidebar',
                'accent_color' => '#1B3142',
                'font_family' => 'sans',
                'density' => 'comfortable',
                'show_photo' => true,
                'popular' => true,
                'badge' => 'FlowCV Flagship',
                'tagline' => 'Signature deep slate-navy sidebar with circular portrait, dot language meters & clean white work history column.',
                'description' => 'FlowCV-inspired 2-column sidebar layout with high recruiter legibility and built-in language dots.',
                'tags' => ['Sidebar', 'Photo', 'Dot Meters'],
                'preset_content' => $base,
            ],
            [
                'slug' => 'atelier-serif',
                'name' => 'Atelier Editorial Serif',
                'role' => 'Product Design & Editorial',
                'category' => 'editorial',
                'style' => 'editorial_figma',
                'accent_color' => '#7C1D3A',
                'font_family' => 'serif',
                'density' => 'comfortable',
                'show_photo' => false,
                'popular' => true,
                'badge' => 'Figma Community',
                'tagline' => 'Warm archival cream canvas (#FAF8F4), Playfair Display masthead, delicate hairlines & editorial 2-column rhythm.',
                'description' => 'Inspired by top Figma Community editorial resumes with luxury print typography and balanced columns.',
                'tags' => ['Cream Canvas', 'Playfair Serif', 'Editorial'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Camille Laurent',
                        'job_title' => 'Principal Product Designer',
                        'email' => 'camille@atelierlaurent.co',
                        'phone' => '+1 646 512 8894',
                        'location' => 'New York, NY',
                        'website' => 'atelierlaurent.co',
                        'linkedin' => 'linkedin.com/in/camillelaurent',
                        'show_photo' => false,
                    ],
                    'summary' => 'Design Director & Principal Systems Architect crafting tactile digital flagships, editorial design languages, and multi-brand component libraries for consumer fintech and publishing platforms.',
                    'experience' => [
                        [
                            'role' => 'Principal Product Designer',
                            'company' => 'Monocle Digital Studio',
                            'location' => 'New York, NY',
                            'start_date' => '2021',
                            'end_date' => 'Present',
                            'bullets_text' => "- Directed visual overhaul across web and iOS flagship increasing subscriber retention by 28%.\n- Authored tokenized Figma system uniting typography, color contrast, and motion specs across 11 squads.\n- Partnered with Editorial VP to ship interactive longform story formats winning 2 Webby Honors.",
                        ],
                        [
                            'role' => 'Senior UX Designer',
                            'company' => 'Mercury Treasury',
                            'location' => 'San Francisco, CA',
                            'start_date' => '2018',
                            'end_date' => '2021',
                            'bullets_text' => "- Designed commercial treasury onboarding flow raising completion rate from 61% to 89%.\n- Conducted 75+ contextual interviews with startup CFOs to refine cash-flow forecasting dashboards.",
                        ],
                    ],
                    'education' => [
                        [
                            'degree' => 'B.F.A. in Communication Design',
                            'school' => 'Rhode Island School of Design (RISD)',
                            'location' => 'Providence, RI',
                            'start_date' => '2014',
                            'end_date' => '2018',
                            'details' => 'Honors Studio • Typography Teaching Fellow',
                        ],
                    ],
                    'skills' => ['Design Systems', 'Editorial Typography', 'Figma Tokens', 'Prototyping', 'Brand Direction', 'UX Research', 'Accessibility WCAG', 'Motion Design'],
                    'languages' => [
                        ['name' => 'English', 'level' => 'Native', 'score' => 5],
                        ['name' => 'French', 'level' => 'Bilingual', 'score' => 5],
                    ],
                    'awards' => [
                        ['title' => 'Type Directors Club Distinction', 'issuer' => 'TDC New York', 'date' => '2023'],
                    ],
                    'projects' => [
                        ['name' => 'Serif Variable Type Kit', 'role' => 'Creator', 'link' => 'figma.com/@camille', 'description' => 'Open-source Figma UI & editorial pairing kit downloaded by 48,000+ product designers.'],
                    ],
                ]),
            ],
            [
                'slug' => 'chrono-timeline',
                'name' => 'Chrono Career Timeline',
                'role' => 'Marketing & Operations',
                'category' => 'creative',
                'style' => 'timeline_pro',
                'accent_color' => '#0052CC',
                'font_family' => 'sans',
                'density' => 'comfortable',
                'show_photo' => true,
                'popular' => true,
                'badge' => 'Template.net Style',
                'tagline' => 'Connected vertical career timeline spine with node markers paired with percentage skill mastery bars.',
                'description' => 'Template.net style modern CV featuring milestone nodes along the experience axis and visual skill bars.',
                'tags' => ['Timeline Spine', 'Skill Bars', 'Photo'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Sophia Chen-Miller',
                        'job_title' => 'VP of Growth & Lifecycle Marketing',
                        'email' => 'sophia@growthledger.io',
                        'phone' => '+1 312 889 4012',
                        'location' => 'Chicago, IL',
                        'website' => 'growthledger.io',
                        'linkedin' => 'linkedin.com/in/sophiachenmiller',
                        'show_photo' => true,
                    ],
                    'summary' => 'Data-driven marketing executive scaling demand generation, brand positioning, and automated lifecycle funnels for high-velocity SaaS companies from Series A through IPO readiness.',
                    'experience' => [
                        [
                            'role' => 'VP of Growth Marketing',
                            'company' => 'Verve Cloud Analytics',
                            'location' => 'Chicago, IL',
                            'start_date' => '2022',
                            'end_date' => 'Present',
                            'bullets_text' => "- Scaled qualified pipeline 3.4x while lowering blended CAC by 22% across paid & organic channels.\n- Managed 14-person team across Demand Gen, Product Marketing, Brand Creative, and Marketing Ops.",
                        ],
                        [
                            'role' => 'Director of Demand Generation',
                            'company' => 'Northstar Pay',
                            'location' => 'Austin, TX',
                            'start_date' => '2019',
                            'end_date' => '2022',
                            'bullets_text' => "- Launched ABM engine capturing 40+ Fortune 1000 logos with \$2.8M first-year contract value.\n- Rebuilt attribution stack connecting Marketo, Segment, and Looker executive dashboards.",
                        ],
                    ],
                    'skills' => ['Demand Generation', 'Lifecycle CRM', 'Product Positioning', 'SQL & Looker BI', 'Paid Acquisition', 'SEO & Content', 'ABM Playbooks', 'Team Leadership'],
                ]),
            ],
            [
                'slug' => 'nordic-asymmetric',
                'name' => 'Nordic Asymmetric',
                'role' => 'Architecture & UX',
                'category' => 'editorial',
                'style' => 'luxe_split',
                'accent_color' => '#0F172A',
                'font_family' => 'sans',
                'density' => 'comfortable',
                'show_photo' => false,
                'popular' => false,
                'badge' => 'Figma Minimal',
                'tagline' => 'Architectural monogram masthead, subtle shaded bio block, generous margins & structured asymmetry.',
                'description' => 'Clean Scandinavian layout modeled after architectural Figma portfolios with calm hierarchy.',
                'tags' => ['Monogram', 'Asymmetric', 'Whitespace'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Henrik Lindqvist',
                        'job_title' => 'Staff Interaction Architect',
                        'email' => 'henrik@nordicgrid.se',
                        'phone' => '+46 70 412 8890',
                        'location' => 'Stockholm, Sweden',
                        'website' => 'nordicgrid.se',
                        'linkedin' => 'linkedin.com/in/henriklindqvist',
                        'show_photo' => false,
                    ],
                    'summary' => 'Interaction Architect specializing in spatial software, hardware-software industrial interfaces, and calm high-clarity productivity tools for global engineering teams.',
                    'skills' => ['Interaction Architecture', 'Industrial UI', 'Figma & Principle', 'Design Tokens', 'Spatial Computing', 'Prototyping', 'User Testing', 'Accessibility'],
                    'languages' => [
                        ['name' => 'English', 'level' => 'Native / Fluent', 'score' => 5],
                        ['name' => 'Swedish', 'level' => 'Native', 'score' => 5],
                        ['name' => 'Danish', 'level' => 'Proficient', 'score' => 4],
                    ],
                ]),
            ],
            [
                'slug' => 'silicon-engineer',
                'name' => 'Silicon Valley Dev',
                'role' => 'Software & Systems Engineering',
                'category' => 'tech',
                'style' => 'tech_stack',
                'accent_color' => '#0F766E',
                'font_family' => 'mono',
                'density' => 'compact',
                'show_photo' => false,
                'popular' => true,
                'badge' => 'FAANG Ready',
                'tagline' => 'Developer-first sheet with monospace role tokens, grouped tech stack badges & open-source project cards.',
                'description' => 'Crafted for staff engineers, ML researchers & full-stack builders with clean code-inspired visual structure.',
                'tags' => ['Monospace', 'Tech Matrix', 'Projects'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Arjun Mehta',
                        'job_title' => 'Staff Distributed Systems Engineer',
                        'email' => 'arjun@mehtadev.io',
                        'phone' => '+1 206 942 1187',
                        'location' => 'Seattle, WA',
                        'website' => 'github.com/arjunmehta',
                        'linkedin' => 'linkedin.com/in/arjunmehta-eng',
                        'show_photo' => false,
                    ],
                    'summary' => 'Systems engineer with 9 years building multi-region streaming ingestion, low-latency Go/Rust microservices, and Kubernetes control planes serving 2.4M requests/sec at 99.995% SLA.',
                    'experience' => [
                        [
                            'role' => 'Staff Software Engineer (Core Data)',
                            'company' => 'Streamline Infrastructure',
                            'location' => 'Seattle, WA',
                            'start_date' => '2021',
                            'end_date' => 'Present',
                            'bullets_text' => "- Re-engineered Kafka-to-ClickHouse ingestion pipeline cutting p99 write latency from 420ms to 38ms.\n- Led migration to Rust edge proxy saving \$680k/year in AWS EC2 compute overhead.\n- Mentored 6 senior engineers through architecture RFC reviews and on-call reliability clinics.",
                        ],
                        [
                            'role' => 'Senior Backend Engineer',
                            'company' => 'Payscale Cloud',
                            'location' => 'San Francisco, CA',
                            'start_date' => '2017',
                            'end_date' => '2021',
                            'bullets_text' => "- Built zero-downtime PostgreSQL sharding layer handling 14TB financial ledger workloads.\n- Designed idempotent webhook delivery queue processing 90M daily events reliably.",
                        ],
                    ],
                    'education' => [
                        [
                            'degree' => 'M.S. in Computer Science',
                            'school' => 'University of Illinois Urbana-Champaign',
                            'location' => 'Champaign, IL',
                            'start_date' => '2015',
                            'end_date' => '2017',
                            'details' => 'Distributed Systems & Consensus Thesis',
                        ],
                    ],
                    'skills' => ['Go (Golang)', 'Rust', 'TypeScript', 'PostgreSQL', 'Apache Kafka', 'Kubernetes', 'Terraform', 'AWS / GCP', 'gRPC & Protobuf', 'Redis'],
                    'projects' => [
                        [
                            'name' => 'raft-lite (3.4k GitHub Stars)',
                            'role' => 'Maintainer',
                            'link' => 'github.com/arjunmehta/raft-lite',
                            'description' => 'Embeddable deterministic Raft consensus library in pure Go with chaos-testing harness.',
                        ],
                        [
                            'name' => 'pg-shardctl CLI',
                            'role' => 'Author',
                            'link' => 'github.com/arjunmehta/pg-shardctl',
                            'description' => 'Automated zero-downtime Postgres logical replication orchestrator used in production by 30+ startups.',
                        ],
                    ],
                ]),
            ],
            [
                'slug' => 'sage-studio',
                'name' => 'Sage Studio Creative',
                'role' => 'Creative Director & Brand',
                'category' => 'creative',
                'style' => 'creative_card',
                'accent_color' => '#0F5132',
                'font_family' => 'sans',
                'density' => 'comfortable',
                'show_photo' => true,
                'popular' => true,
                'badge' => 'Creative Hit',
                'tagline' => 'Soft botanical tinted rail, framed portrait badge, crisp typographic hierarchy & featured portfolio block.',
                'description' => 'Ideal for art directors, brand strategists, and digital designers who want personality without clutter.',
                'tags' => ['Botanical Tint', 'Framed Photo', 'Portfolio'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Maya Lin-Vance',
                        'job_title' => 'Creative Director & Brand Lead',
                        'email' => 'maya@sagestudio.co',
                        'phone' => '+1 310 558 7210',
                        'location' => 'Los Angeles, CA',
                        'website' => 'sagestudio.co',
                        'linkedin' => 'linkedin.com/in/mayalinvance',
                        'show_photo' => true,
                    ],
                    'summary' => 'Multidisciplinary Creative Director shaping category-defining visual identities, packaging systems, and digital launch campaigns for venture-backed consumer and climate brands.',
                    'skills' => ['Brand Identity', 'Art Direction', 'Packaging Print', 'Figma & Adobe CC', '3D Motion Direction', 'Webflow & Framer', 'Campaign Strategy', 'Copy Direction'],
                    'projects' => [
                        [
                            'name' => 'Solstice Clean Energy Rebrand',
                            'role' => 'Lead Art Director',
                            'link' => 'sagestudio.co/solstice',
                            'description' => 'Full visual overhaul across web, fleet EV livery, and national OOH billboard rollout.',
                        ],
                    ],
                ]),
            ],
            [
                'slug' => 'zurich-swiss',
                'name' => 'Zurich Swiss Grid',
                'role' => 'Architecture & Industrial Design',
                'category' => 'compact',
                'style' => 'minimal_swiss',
                'accent_color' => '#0F172A',
                'font_family' => 'sans',
                'density' => 'comfortable',
                'show_photo' => false,
                'popular' => false,
                'badge' => 'Swiss Style',
                'tagline' => 'Architectural 28%/72% left-column index rail with surgical alignment, zero ornament, maximum precision.',
                'description' => 'International Typographic Style layout putting dates and section titles cleanly in the left structural gutter.',
                'tags' => ['Swiss Grid', '28/72 Split', 'Zero Noise'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Lukas Weber',
                        'job_title' => 'Senior Systems Designer & FE Lead',
                        'email' => 'lukas@zurichform.ch',
                        'phone' => '+41 44 580 2219',
                        'location' => 'Zurich, Switzerland',
                        'website' => 'zurichform.ch',
                        'linkedin' => 'linkedin.com/in/lukasweber-ch',
                        'show_photo' => false,
                    ],
                    'summary' => 'Hybrid design engineer bridging Swiss grid visual rigor with production TypeScript/React interfaces, design tokens, and high-frequency financial visualization.',
                    'skills' => ['Swiss Grid Typography', 'React & TypeScript', 'D3.js Visualization', 'Design Systems', 'CSS Architecture', 'Figma Plugins', 'Accessible UI'],
                    'languages' => [
                        ['name' => 'German', 'level' => 'Native', 'score' => 5],
                        ['name' => 'English', 'level' => 'Fluent', 'score' => 5],
                        ['name' => 'French', 'level' => 'Proficient', 'score' => 4],
                    ],
                ]),
            ],
            [
                'slug' => 'harvard-wallstreet',
                'name' => 'Wall Street Double-Rule',
                'role' => 'Finance, Banking & Legal',
                'category' => 'ats',
                'style' => 'ats_classic',
                'accent_color' => '#0F172A',
                'font_family' => 'serif',
                'density' => 'compact',
                'show_photo' => false,
                'popular' => true,
                'badge' => 'ATS 100%',
                'tagline' => 'Gold-standard institutional black-ink single column with serif headings and crisp double-ruled section dividers.',
                'description' => 'Engineered to score 100/100 on Workday, Greenhouse, Lever & Taleo ATS parsers for finance and law.',
                'tags' => ['ATS Gold', 'Double Rule', 'Single Column'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Jonathan Sterling, CFA',
                        'job_title' => 'Vice President — TMT Investment Banking',
                        'email' => 'j.sterling@sterlingcapital.com',
                        'phone' => '+1 212 690 4820',
                        'location' => 'New York, NY',
                        'website' => '',
                        'linkedin' => 'linkedin.com/in/jonathansterlingcfa',
                        'show_photo' => false,
                    ],
                    'summary' => 'Chartered Financial Analyst with 7+ years executing M&A advisories, leveraged buyouts, and IPO debt/equity financings totaling \$18.5B+ in cumulative transaction volume across Software and FinTech.',
                    'experience' => [
                        [
                            'role' => 'Vice President, TMT M&A Advisory',
                            'company' => 'Lazard Freres & Co.',
                            'location' => 'New York, NY',
                            'start_date' => '2021',
                            'end_date' => 'Present',
                            'bullets_text' => "- Quarterbacked \$3.4B cross-border SaaS take-private transaction from valuation through Board Fairness Opinion.\n- Built 3-statement LBO, DCF, and accretion/dilution models presented directly to Fortune 500 CEOs.\n- Managed diligence teams of 6 associates and analysts across legal, tax, and commercial workstreams.",
                        ],
                        [
                            'role' => 'Investment Banking Associate',
                            'company' => 'Morgan Stanley',
                            'location' => 'New York, NY',
                            'start_date' => '2018',
                            'end_date' => '2021',
                            'bullets_text' => "- Executed 9 sell-side M&A and Nasdaq IPO mandates totaling \$6.2B in enterprise value.\n- Authored S-1 registration business narratives and institutional roadshow investor decks.",
                        ],
                    ],
                    'education' => [
                        [
                            'degree' => 'MBA in Finance & Accounting',
                            'school' => 'Wharton School, University of Pennsylvania',
                            'location' => 'Philadelphia, PA',
                            'start_date' => '2016',
                            'end_date' => '2018',
                            'details' => 'Palmer Scholar (Top 5%) • CFA Charterholder (2020)',
                        ],
                    ],
                    'skills' => ['M&A Valuation', 'LBO & DCF Modeling', 'Capital Markets / IPO', 'Bloomberg & Capital IQ', 'Due Diligence', 'SPA Negotiation', 'Board Advisory', 'Excel VBA'],
                    'languages' => [
                        ['name' => 'English', 'level' => 'Native', 'score' => 5],
                    ],
                    'awards' => [
                        ['title' => 'CFA Charterholder (#214908)', 'issuer' => 'CFA Institute', 'date' => '2020'],
                    ],
                    'projects' => [],
                    'section_visibility' => [
                        'summary' => true,
                        'experience' => true,
                        'education' => true,
                        'skills' => true,
                        'languages' => false,
                        'awards' => true,
                        'projects' => false,
                    ],
                ]),
            ],
            [
                'slug' => 'executive-berlin',
                'name' => 'Executive Leadership Banner',
                'role' => 'C-Suite, VP & General Management',
                'category' => 'ats',
                'style' => 'executive_bold',
                'accent_color' => '#0F172A',
                'font_family' => 'sans',
                'density' => 'comfortable',
                'show_photo' => false,
                'popular' => false,
                'badge' => 'Executive',
                'tagline' => 'Commanding full-bleed header banner paired with a balanced 65/35 main-experience and competencies split.',
                'description' => 'High-impact boardroom layout emphasizing P&L scale, leadership scope, and strategic outcomes.',
                'tags' => ['Full Header', '2-Column', 'P&L Focus'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Marcus Vance',
                        'job_title' => 'Chief Operating Officer (COO)',
                        'email' => 'marcus@vanceops.com',
                        'phone' => '+1 415 390 1102',
                        'location' => 'San Francisco, CA',
                        'website' => 'vanceops.com',
                        'linkedin' => 'linkedin.com/in/marcusvance-coo',
                        'show_photo' => false,
                    ],
                    'summary' => 'Operating executive with 14 years scaling multi-product global technology organizations from \$25M to \$240M ARR, improving EBITDA margin by 19 points, and guiding post-merger integrations.',
                    'skills' => ['P&L Ownership (\$240M+)', 'Global Operations', 'M&A Integration', 'Board Governance', 'OKR Alignment', 'Supply Chain & Cloud', 'Org Scaling (450+ FTE)'],
                ]),
            ],
            [
                'slug' => 'hyper-compact-onepager',
                'name' => 'Hyper-Compact One-Pager',
                'role' => 'Product Management & Consulting',
                'category' => 'compact',
                'style' => 'compact_onepage',
                'accent_color' => '#0052CC',
                'font_family' => 'sans',
                'density' => 'compact',
                'show_photo' => false,
                'popular' => true,
                'badge' => 'High Density',
                'tagline' => 'Information-dense single-page engine with 3-column contact strip, crisp boxed summary & 2-col bottom skills grid.',
                'description' => 'Fits 10+ years of senior PM or consulting impact comfortably on one crisp A4 sheet without shrinking body text.',
                'tags' => ['3-Col Strip', 'One-Page Fit', 'PM Favorite'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Priya Nair',
                        'job_title' => 'Group Product Manager — AI Platform',
                        'email' => 'priya@nairproduct.com',
                        'phone' => '+1 650 712 4409',
                        'location' => 'Palo Alto, CA',
                        'website' => 'nairproduct.com',
                        'linkedin' => 'linkedin.com/in/priyanair-pm',
                        'show_photo' => false,
                    ],
                    'summary' => 'Group Product Manager leading 4 core AI workflow teams (38 engineers & designers). Shipped copilot automation suites adopted by 1.2M weekly active seats and driving \$32M net expansion ARR.',
                    'skills' => ['AI / LLM Product Strategy', '0-to-1 Platform Launches', 'PLG Funnel Optimization', 'Pricing & Packaging', 'SQL & Experimentation', 'Executive Roadmaps', 'API & Developer UX', 'Enterprise Security'],
                ]),
            ],
            [
                'slug' => 'merlot-figma-editorial',
                'name' => 'Bordeaux Figma Folio',
                'role' => 'Architecture, Fashion & Media',
                'category' => 'editorial',
                'style' => 'editorial_figma',
                'accent_color' => '#B45309',
                'font_family' => 'serif',
                'density' => 'comfortable',
                'show_photo' => true,
                'popular' => false,
                'badge' => 'Warm Editorial',
                'tagline' => 'Terracotta-accented Figma editorial spread with warm alabaster paper tone, serif titles & optional circular portrait.',
                'description' => 'Editorial warmth crafted for PR directors, architecture leads, content strategists, and curators.',
                'tags' => ['Terracotta Ink', 'Cream Sheet', 'Serif Header'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Elena Rossi',
                        'job_title' => 'Global Communications & Brand PR Lead',
                        'email' => 'elena@rossimedia.eu',
                        'phone' => '+39 02 9184 3310',
                        'location' => 'Milan & London',
                        'website' => 'rossimedia.eu',
                        'linkedin' => 'linkedin.com/in/elenarossi-pr',
                        'show_photo' => true,
                    ],
                    'summary' => 'International Communications Lead partnering with luxury hospitality, design houses, and cultural institutions across Milan, Paris, and London to orchestrate global press launches and editorial partnerships.',
                    'skills' => ['Global PR Strategy', 'Media Relations', 'Crisis Communications', 'Executive Ghostwriting', 'Cultural Partnerships', 'Keynote Staging', 'Brand Storytelling'],
                    'languages' => [
                        ['name' => 'Italian', 'level' => 'Native', 'score' => 5],
                        ['name' => 'English', 'level' => 'Bilingual', 'score' => 5],
                        ['name' => 'French', 'level' => 'Fluent', 'score' => 4],
                    ],
                ]),
            ],
            [
                'slug' => 'emerald-flow',
                'name' => 'Emerald Pine Flow',
                'role' => 'Healthcare, Biotech & Climate',
                'category' => 'modern',
                'style' => 'flow_sidebar',
                'accent_color' => '#0F5132',
                'font_family' => 'sans',
                'density' => 'comfortable',
                'show_photo' => true,
                'popular' => false,
                'badge' => 'Clean Flow',
                'tagline' => 'Forest emerald FlowCV sidebar pairing organic warmth with structured medical, sustainability, or policy credentials.',
                'description' => 'Distinguished pine-green sidebar template suited to clinical leads, climate fellows, and policy researchers.',
                'tags' => ['Emerald Sidebar', 'Clean Flow', 'Photo'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Dr. Amara Okafor, MPH',
                        'job_title' => 'Clinical Operations & Health Tech Lead',
                        'email' => 'amara@okaforhealth.org',
                        'phone' => '+1 617 440 8291',
                        'location' => 'Boston, MA',
                        'website' => 'okaforhealth.org',
                        'linkedin' => 'linkedin.com/in/amaraokafor-mph',
                        'show_photo' => true,
                    ],
                    'summary' => 'Clinical Operations Director bridging hospital EHR workflows and remote patient monitoring platforms across 14 academic medical centers, reducing hospital readmissions by 19%.',
                    'skills' => ['Clinical Trials Ops', 'HIPAA Compliance', 'EHR Interoperability', 'HL7 FHIR', 'Health Economics', 'Grant Stewardship', 'Cross-Site Training'],
                ]),
            ],
            [
                'slug' => 'cobalt-product-timeline',
                'name' => 'Cobalt Product Roadmap',
                'role' => 'Product & Program Management',
                'category' => 'tech',
                'style' => 'timeline_pro',
                'accent_color' => '#1B3142',
                'font_family' => 'sans',
                'density' => 'comfortable',
                'show_photo' => false,
                'popular' => false,
                'badge' => 'Timeline Pro',
                'tagline' => 'Connected navy experience timeline with quantified delivery bars for PMs, TPMs, and transformation directors.',
                'description' => 'Highlights progressive career promotion path via connected timeline nodes and skill bar meters.',
                'tags' => ['Career Track', 'Bar Meters', 'No Photo'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Daniel Park',
                        'job_title' => 'Senior Technical Program Manager',
                        'email' => 'daniel@parkdelivery.io',
                        'phone' => '+1 425 601 9920',
                        'location' => 'Bellevue, WA',
                        'website' => 'parkdelivery.io',
                        'linkedin' => 'linkedin.com/in/danielpark-tpm',
                        'show_photo' => false,
                    ],
                    'summary' => 'Senior Technical Program Manager synchronizing multi-org cloud migrations, PCI-DSS Level 1 security certifications, and mobile release trains across 120+ distributed engineers.',
                    'skills' => ['Program Governance', 'Cloud Migration AWS', 'SOC2 / PCI Compliance', 'Agile / SAFe Delivery', 'Risk Mitigation', 'SQL & Jira Analytics', 'Vendor Contracting'],
                ]),
            ],
            [
                'slug' => 'plum-ux-portfolio',
                'name' => 'Deep Plum UX Card',
                'role' => 'UX Research & Product Design',
                'category' => 'creative',
                'style' => 'creative_card',
                'accent_color' => '#4C1D95',
                'font_family' => 'sans',
                'density' => 'comfortable',
                'show_photo' => true,
                'popular' => false,
                'badge' => 'Research Pick',
                'tagline' => 'Deep violet-tinted craft sidebar tailored for UX researchers, service designers, and design ops specialists.',
                'description' => 'Expressive plum tint highlights research case studies, usability methods, and collaborative impact.',
                'tags' => ['Violet Rail', 'Case Studies', 'UX Focused'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Nadia Al-Mansoor',
                        'job_title' => 'Lead UX Researcher',
                        'email' => 'nadia@researchcraft.co',
                        'phone' => '+44 7700 900412',
                        'location' => 'London, UK',
                        'website' => 'researchcraft.co',
                        'linkedin' => 'linkedin.com/in/nadiaalmansoor',
                        'show_photo' => true,
                    ],
                    'summary' => 'Mixed-methods UX Research Lead informing multi-market product strategy across UK, EU, and MENA digital banking apps serving 6.5M everyday consumers.',
                    'skills' => ['Mixed-Methods Research', 'Usability Testing', 'Jobs-To-Be-Done', 'Survey Psychometrics', 'Journey Mapping', 'Synthesis Workshops', 'Dovetail & Maze'],
                ]),
            ],
            [
                'slug' => 'teal-stack-graduate',
                'name' => 'Pacific Cloud & Data',
                'role' => 'Data Science & AI Engineering',
                'category' => 'tech',
                'style' => 'tech_stack',
                'accent_color' => '#0052CC',
                'font_family' => 'mono',
                'density' => 'comfortable',
                'show_photo' => false,
                'popular' => false,
                'badge' => 'AI & Data',
                'tagline' => 'Structured monospace badge matrix showcasing Python, PyTorch, LLM evals, and production ML deployments.',
                'description' => 'Clean hierarchy tailored for ML engineers, analytics engineers, and quantitative researchers.',
                'tags' => ['ML Stack', 'Monospace', 'Open Source'],
                'preset_content' => $build([
                    'personal' => [
                        'full_name' => 'Kenji Takahashi',
                        'job_title' => 'Senior Applied ML Engineer',
                        'email' => 'kenji@tensorcraft.ai',
                        'phone' => '+1 415 802 6610',
                        'location' => 'San Francisco, CA',
                        'website' => 'github.com/kenjitakahashi',
                        'linkedin' => 'linkedin.com/in/kenjitakahashi-ml',
                        'show_photo' => false,
                    ],
                    'summary' => 'Applied Machine Learning Engineer productionizing retrieval-augmented search, fine-tuned domain embeddings, and low-cost GPU inference pipelines handling 50M queries daily.',
                    'skills' => ['Python', 'PyTorch', 'vLLM & CUDA', 'RAG & Vector DBs', 'Ray Distributed', 'Airflow / dbt', 'Snowflake', 'Docker & K8s'],
                    'projects' => [
                        [
                            'name' => 'eval-harness-oss',
                            'role' => 'Lead Author',
                            'link' => 'github.com/kenjitakahashi/eval-harness',
                            'description' => 'Lightweight automated CI regression benchmark for enterprise RAG grounding accuracy.',
                        ],
                    ],
                ]),
            ],
        ];

        $directorSlugs = ['chrono-timeline', 'sage-studio', 'merlot-figma-editorial', 'emerald-flow', 'plum-ux-portfolio'];

        return array_map(static function (array $tpl) use ($categories, $directorSlugs): array {
            $tpl['preset_content']['personal']['show_photo'] = !empty($tpl['show_photo']);
            if (!empty($tpl['show_photo'])) {
                $tpl['preset_content']['personal']['preset_avatar'] = in_array($tpl['slug'], $directorSlugs, true)
                    ? 'images/avatars/avatar-director.jpg'
                    : 'images/avatars/avatar-consultant.jpg';
            } else {
                $tpl['preset_content']['personal']['preset_avatar'] = '';
            }
            $tpl['template_style'] = $tpl['style'];
            $tpl['category_label'] = $categories[$tpl['category']] ?? ucfirst($tpl['category']);
            $tpl['content'] = $tpl['preset_content'];
            return $tpl;
        }, $rawTemplates);
    }

    public static function find(string $slug): ?array
    {
        foreach (self::templates() as $tpl) {
            if ($tpl['slug'] === $slug) {
                return $tpl;
            }
        }
        return null;
    }
}
