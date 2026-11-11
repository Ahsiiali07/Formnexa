<?php

namespace App\Support;

class TemplateCatalog
{
    /**
     * All 6 customizable visual layout styles supported across Studio, Public View, and PDF Export.
     */
    public static function styles(): array
    {
        return [
            'modern' => [
                'id' => 'modern',
                'name' => 'Modern Studio',
                'tagline' => 'Cover banner, rounded cards & soft slate UI',
                'badge' => 'Most Popular',
            ],
            'classic' => [
                'id' => 'classic',
                'name' => 'Classic Corporate',
                'tagline' => 'Structured grid borders & formal business header',
                'badge' => 'Traditional',
            ],
            'bold' => [
                'id' => 'bold',
                'name' => 'Bold Executive',
                'tagline' => 'Full-bleed colored header band & high contrast',
                'badge' => 'High Impact',
            ],
            'minimal' => [
                'id' => 'minimal',
                'name' => 'Minimalist Editorial',
                'tagline' => 'Clean whitespace, hairline rules & Swiss typography',
                'badge' => 'Editorial',
            ],
            'stripe' => [
                'id' => 'stripe',
                'name' => 'Accent Ribbon',
                'tagline' => 'Signature left accent stripe & tinted highlights',
                'badge' => 'Creative',
            ],
            'compact' => [
                'id' => 'compact',
                'name' => 'Compact Ledger',
                'tagline' => 'Dense, itemized accounting format for long lists',
                'badge' => 'Detailed',
            ],
        ];
    }

    /**
     * Curated brand color swatches.
     */
    public static function colors(): array
    {
        return [
            ['name' => 'Formnexa Blue', 'hex' => '#0066FF'],
            ['name' => 'Emerald Teal', 'hex' => '#0d9488'],
            ['name' => 'Obsidian Dark', 'hex' => '#0f172a'],
            ['name' => 'Royal Indigo', 'hex' => '#4f46e5'],
            ['name' => 'Forest Green', 'hex' => '#059669'],
            ['name' => 'Crimson Rose', 'hex' => '#e11d48'],
            ['name' => 'Amber Gold', 'hex' => '#d97706'],
            ['name' => 'Deep Violet', 'hex' => '#7c3aed'],
        ];
    }

    /**
     * Template gallery filter categories.
     */
    public static function categories(): array
    {
        return [
            'all' => 'All Templates',
            'blank' => 'Blank Layouts',
            'freelance' => 'Freelance & Creative',
            'consulting' => 'Consulting & Agency',
            'contractor' => 'Contractor & Trades',
            'tech' => 'SaaS & Tech',
            'retail' => 'Retail & Wholesale',
            'medical' => 'Medical & Wellness',
        ];
    }

    /**
     * Curated library of industry and blank invoice templates.
     */
    public static function templates(): array
    {
        return [
            // --- BLANK LAYOUTS ---
            [
                'slug' => 'blank-modern-studio',
                'name' => 'Blank Modern Invoice',
                'category' => 'blank',
                'category_label' => 'Blank Layouts',
                'description' => 'Clean editorial layout with customizable header cover, rounded party cards, and soft accents.',
                'template_style' => 'modern',
                'accent_color' => '#0d9488',
                'invoice_title' => 'INVOICE',
                'prefix' => 'INV-',
                'payment_terms' => 'Net 14',
                'tax_rate' => 0,
                'popular' => true,
                'from_name' => '',
                'from_email' => '',
                'from_address' => '',
                'to_name' => '',
                'to_email' => '',
                'to_address' => '',
                'items' => [
                    ['description' => 'Professional Services / Item Description', 'quantity' => 1, 'unit_price' => 0],
                ],
                'notes' => 'Thank you for your business!',
                'terms' => 'Please remit payment within the specified payment terms.',
            ],
            [
                'slug' => 'blank-classic-corporate',
                'name' => 'Blank Classic Corporate',
                'category' => 'blank',
                'category_label' => 'Blank Layouts',
                'description' => 'Formal boxed corporate template with full grid tables and structured business headers.',
                'template_style' => 'classic',
                'accent_color' => '#0f172a',
                'invoice_title' => 'INVOICE',
                'prefix' => 'INV-',
                'payment_terms' => 'Net 30',
                'tax_rate' => 0,
                'popular' => true,
                'from_name' => '',
                'from_email' => '',
                'from_address' => '',
                'to_name' => '',
                'to_email' => '',
                'to_address' => '',
                'items' => [
                    ['description' => 'Itemized Service or Product Line', 'quantity' => 1, 'unit_price' => 0],
                ],
                'notes' => 'Please include invoice number on all remittance correspondence.',
                'terms' => 'Net 30 days from invoice issue date.',
            ],
            [
                'slug' => 'blank-bold-executive',
                'name' => 'Blank Bold Executive',
                'category' => 'blank',
                'category_label' => 'Blank Layouts',
                'description' => 'High-contrast full-bleed colored header banner designed for standout client presentation.',
                'template_style' => 'bold',
                'accent_color' => '#0066FF',
                'invoice_title' => 'INVOICE',
                'prefix' => 'INV-',
                'payment_terms' => 'Net 14',
                'tax_rate' => 0,
                'popular' => true,
                'from_name' => '',
                'from_email' => '',
                'from_address' => '',
                'to_name' => '',
                'to_email' => '',
                'to_address' => '',
                'items' => [
                    ['description' => 'Project Milestone / Deliverable', 'quantity' => 1, 'unit_price' => 0],
                ],
                'notes' => 'Thank you for partnering with us.',
                'terms' => 'Payment due upon receipt unless otherwise agreed.',
            ],
            [
                'slug' => 'blank-minimal-editorial',
                'name' => 'Blank Minimalist Editorial',
                'category' => 'blank',
                'category_label' => 'Blank Layouts',
                'description' => 'Ultra-clean Swiss-inspired whitespace layout with crisp hairlines and zero box clutter.',
                'template_style' => 'minimal',
                'accent_color' => '#0f172a',
                'invoice_title' => 'INVOICE',
                'prefix' => 'INV-',
                'payment_terms' => 'Due on receipt',
                'tax_rate' => 0,
                'popular' => false,
                'from_name' => '',
                'from_email' => '',
                'from_address' => '',
                'to_name' => '',
                'to_email' => '',
                'to_address' => '',
                'items' => [
                    ['description' => 'Consulting or Creative Deliverable', 'quantity' => 1, 'unit_price' => 0],
                ],
                'notes' => 'Thank you.',
                'terms' => 'Due upon receipt.',
            ],

            // --- FREELANCE & CREATIVE ---
            [
                'slug' => 'freelance-web-developer',
                'name' => 'Freelance Web Developer',
                'category' => 'freelance',
                'category_label' => 'Freelance & Creative',
                'description' => 'Tailored for full-stack developers, UI engineers, and software freelancers billing sprints or milestones.',
                'template_style' => 'bold',
                'accent_color' => '#0066FF',
                'invoice_title' => 'INVOICE',
                'prefix' => 'DEV-',
                'payment_terms' => 'Net 14',
                'tax_rate' => 0,
                'popular' => true,
                'from_name' => 'Apex Code Studio',
                'from_email' => 'billing@apexcode.dev',
                'from_address' => "420 Tech Park Way, Suite 210\nAustin, TX 78701",
                'to_name' => 'Northstar Ventures LLC',
                'to_email' => 'accounts@northstar.io',
                'to_address' => "880 Market Street\nSan Francisco, CA 94102",
                'items' => [
                    ['description' => 'Frontend UI Architecture & Tailwind Component Library', 'quantity' => 24, 'unit_price' => 95],
                    ['description' => 'Laravel REST API & Database Schema Implementation', 'quantity' => 18, 'unit_price' => 110],
                    ['description' => 'Production Deployment & Automated Test Suite', 'quantity' => 1, 'unit_price' => 450],
                ],
                'notes' => 'Source code repository and staging access delivered upon milestone sign-off.',
                'terms' => 'Net 14 days via ACH, Wire, or online card link.',
            ],
            [
                'slug' => 'graphic-design-studio',
                'name' => 'Graphic & Brand Design',
                'category' => 'freelance',
                'category_label' => 'Freelance & Creative',
                'description' => 'Minimalist editorial layout for brand designers, illustrators, and art directors.',
                'template_style' => 'stripe',
                'accent_color' => '#7c3aed',
                'invoice_title' => 'INVOICE',
                'prefix' => 'DES-',
                'payment_terms' => 'Net 14',
                'tax_rate' => 0,
                'popular' => true,
                'from_name' => 'Studio Kroma Design',
                'from_email' => 'hello@studiokroma.co',
                'from_address' => "114 Crosby Street\nNew York, NY 10012",
                'to_name' => 'Lumina Botanicals',
                'to_email' => 'finance@luminabotanicals.com',
                'to_address' => "752 Abbot Kinney Blvd\nVenice, CA 90291",
                'items' => [
                    ['description' => 'Visual Brand Identity System & Logo Suite', 'quantity' => 1, 'unit_price' => 2400],
                    ['description' => 'Custom Packaging & Print-Ready Typography Guidelines', 'quantity' => 1, 'unit_price' => 950],
                    ['description' => 'Social Media Launch Asset Kit (20 Templates)', 'quantity' => 1, 'unit_price' => 600],
                ],
                'notes' => 'Vector AI, SVG, and high-resolution PNG files shared via brand portal.',
                'terms' => 'Full commercial usage rights transfer upon final invoice settlement.',
            ],
            [
                'slug' => 'photography-video-production',
                'name' => 'Photography & Video Shoot',
                'category' => 'freelance',
                'category_label' => 'Freelance & Creative',
                'description' => 'Built for photographers, videographers, and production crews billing shoot days and post-production.',
                'template_style' => 'minimal',
                'accent_color' => '#0f172a',
                'invoice_title' => 'INVOICE',
                'prefix' => 'CAM-',
                'payment_terms' => 'Due on receipt',
                'tax_rate' => 8.25,
                'popular' => false,
                'from_name' => 'Frame & Light Productions',
                'from_email' => 'studio@frameandlight.tv',
                'from_address' => "2910 Sunset Blvd\nLos Angeles, CA 90026",
                'to_name' => 'Horizon Hospitality Group',
                'to_email' => 'ap@horizonhotels.com',
                'to_address' => "500 Ocean Drive\nMiami, FL 33139",
                'items' => [
                    ['description' => 'Full-Day Commercial Shoot (8 Hours + Lighting Kit)', 'quantity' => 2, 'unit_price' => 1400],
                    ['description' => '4K Color Grading & Post-Production Editing', 'quantity' => 12, 'unit_price' => 85],
                    ['description' => 'Licensed Music Track & High-Res Photo Retouching', 'quantity' => 1, 'unit_price' => 350],
                ],
                'notes' => 'RAW archival backups retained for 90 days post-delivery.',
                'terms' => '50% deposit applied; remaining balance due upon final gallery release.',
            ],
            [
                'slug' => 'copywriting-content-marketing',
                'name' => 'Copywriting & Content',
                'category' => 'freelance',
                'category_label' => 'Freelance & Creative',
                'description' => 'Clean layout for freelance writers, SEO content strategists, and editors.',
                'template_style' => 'modern',
                'accent_color' => '#0d9488',
                'invoice_title' => 'INVOICE',
                'prefix' => 'WRT-',
                'payment_terms' => 'Net 14',
                'tax_rate' => 0,
                'popular' => false,
                'from_name' => 'Clarity Editorial LLC',
                'from_email' => 'words@clarityeditorial.com',
                'from_address' => "642 Hawthorne Ave\nPortland, OR 97214",
                'to_name' => 'Veloce Cloud Software',
                'to_email' => 'marketing@velocecloud.com',
                'to_address' => "2100 Westlake Ave\nSeattle, WA 98121",
                'items' => [
                    ['description' => 'Long-Form Technical Whitepaper (3,000 Words + Research)', 'quantity' => 1, 'unit_price' => 1200],
                    ['description' => 'SEO Pillar Blog Articles (1,500 Words each)', 'quantity' => 4, 'unit_price' => 325],
                    ['description' => 'Product Launch Email Sequence (5 Emails)', 'quantity' => 1, 'unit_price' => 550],
                ],
                'notes' => 'Includes two rounds of editorial revisions.',
                'terms' => 'Net 14 days from delivery date.',
            ],

            // --- CONSULTING & AGENCY ---
            [
                'slug' => 'management-consulting-retainer',
                'name' => 'Business Strategy Consulting',
                'category' => 'consulting',
                'category_label' => 'Consulting & Agency',
                'description' => 'Executive corporate format for management consultants, fractional CFOs, and advisors.',
                'template_style' => 'classic',
                'accent_color' => '#0f172a',
                'invoice_title' => 'INVOICE',
                'prefix' => 'CNS-',
                'payment_terms' => 'Net 30',
                'tax_rate' => 0,
                'popular' => true,
                'from_name' => 'Meridian Advisory Partners',
                'from_email' => 'billing@meridianpartners.com',
                'from_address' => "155 N Wacker Drive, Suite 4200\nChicago, IL 60606",
                'to_name' => 'Vanguard Logistics Corp',
                'to_email' => 'payables@vanguardlogistics.com',
                'to_address' => "300 Peachtree St NE\nAtlanta, GA 30308",
                'items' => [
                    ['description' => 'Executive Strategy Monthly Retainer (Q4 Operations)', 'quantity' => 1, 'unit_price' => 4500],
                    ['description' => 'Supply Chain Efficiency Audit & Board Presentation', 'quantity' => 12, 'unit_price' => 225],
                    ['description' => 'On-Site Leadership Workshop Travel & Lodging', 'quantity' => 1, 'unit_price' => 640],
                ],
                'notes' => 'Detailed time logs attached separately per Master Services Agreement.',
                'terms' => 'Net 30 days via ACH or corporate wire transfer.',
            ],
            [
                'slug' => 'digital-marketing-agency',
                'name' => 'Digital Marketing Agency',
                'category' => 'consulting',
                'category_label' => 'Consulting & Agency',
                'description' => 'Vibrant stripe-accented agency template for ad management, SEO retainers, and creative campaigns.',
                'template_style' => 'stripe',
                'accent_color' => '#4f46e5',
                'invoice_title' => 'INVOICE',
                'prefix' => 'AGY-',
                'payment_terms' => 'Net 14',
                'tax_rate' => 0,
                'popular' => false,
                'from_name' => 'Pulse Growth Digital',
                'from_email' => 'accounts@pulsegrowth.co',
                'from_address' => "901 Congress Ave\nAustin, TX 78701",
                'to_name' => 'Urban Thread Apparel',
                'to_email' => 'billing@urbanthread.shop',
                'to_address' => "340 Greene St\nNew York, NY 10013",
                'items' => [
                    ['description' => 'Paid Search & Meta Ads Management Retainer', 'quantity' => 1, 'unit_price' => 2800],
                    ['description' => 'Conversion Rate Optimization & Landing Page A/B Testing', 'quantity' => 1, 'unit_price' => 1250],
                    ['description' => 'Monthly Analytics Dashboard & Attribution Reporting', 'quantity' => 1, 'unit_price' => 400],
                ],
                'notes' => 'Ad spend is billed directly to client payment method on file.',
                'terms' => 'Net 14 days from issue date.',
            ],
            [
                'slug' => 'legal-services-retainer',
                'name' => 'Legal & Law Firm Billing',
                'category' => 'consulting',
                'category_label' => 'Consulting & Agency',
                'description' => 'Formal classic ledger format designed for law firms, attorneys, and paralegal billing.',
                'template_style' => 'classic',
                'accent_color' => '#0f172a',
                'invoice_title' => 'LEGAL STATEMENT',
                'prefix' => 'LAW-',
                'payment_terms' => 'Net 30',
                'tax_rate' => 0,
                'popular' => false,
                'from_name' => 'Sterling & Vance LLP',
                'from_email' => 'accounting@sterlingvance.law',
                'from_address' => "200 Federal Street, Floor 18\nBoston, MA 02110",
                'to_name' => 'Bayside Biotech Inc.',
                'to_email' => 'legal@baysidebio.com',
                'to_address' => "450 Kendall Square\nCambridge, MA 02142",
                'items' => [
                    ['description' => 'Corporate IP Licensing Agreement Review (Partner Hours)', 'quantity' => 6.5, 'unit_price' => 420],
                    ['description' => 'Trademark Filing & USPTO Correspondence (Associate)', 'quantity' => 4, 'unit_price' => 260],
                    ['description' => 'Official Regulatory Filing & Courier Fees', 'quantity' => 1, 'unit_price' => 350],
                ],
                'notes' => 'Privileged & Confidential Attorney-Client Billing Record.',
                'terms' => 'Payable within 30 days. Trust account balance applied where applicable.',
            ],

            // --- CONTRACTOR & TRADES ---
            [
                'slug' => 'general-construction-contractor',
                'name' => 'Construction & Remodeling',
                'category' => 'contractor',
                'category_label' => 'Contractor & Trades',
                'description' => 'Structured itemized invoice for general contractors, builders, and renovation teams.',
                'template_style' => 'compact',
                'accent_color' => '#d97706',
                'invoice_title' => 'CONTRACTOR INVOICE',
                'prefix' => 'CON-',
                'payment_terms' => 'Net 14',
                'tax_rate' => 7.0,
                'popular' => true,
                'from_name' => 'Ironwood Builders & Remodeling',
                'from_email' => 'estimates@ironwoodbuilders.com',
                'from_address' => "1840 Industrial Pkwy\nDenver, CO 80216",
                'to_name' => 'Sarah & David Mitchell',
                'to_email' => 'mitchell.home@email.com',
                'to_address' => "742 Evergreen Terrace\nBoulder, CO 80302",
                'items' => [
                    ['description' => 'Kitchen Framing, Drywall & Structural Prep (Labor)', 'quantity' => 42, 'unit_price' => 75],
                    ['description' => 'Custom White Oak Cabinetry & Hardware Materials', 'quantity' => 1, 'unit_price' => 4850],
                    ['description' => 'Quartz Countertop Fabrication & Installation', 'quantity' => 1, 'unit_price' => 2400],
                    ['description' => 'City Permit Filing & Debris Disposal', 'quantity' => 1, 'unit_price' => 480],
                ],
                'notes' => 'Licensed & Insured Contractor #CO-994821. Workmanship guaranteed for 2 years.',
                'terms' => 'Progress draw due within 14 days of inspection approval.',
            ],
            [
                'slug' => 'plumbing-electrical-service',
                'name' => 'Plumbing, HVAC & Electrical',
                'category' => 'contractor',
                'category_label' => 'Contractor & Trades',
                'description' => 'Clear breakdown of service call fees, parts, and hourly technician labor.',
                'template_style' => 'bold',
                'accent_color' => '#0d9488',
                'invoice_title' => 'SERVICE INVOICE',
                'prefix' => 'SRV-',
                'payment_terms' => 'Due on receipt',
                'tax_rate' => 6.5,
                'popular' => false,
                'from_name' => 'VoltFlow Mechanical & Electrical',
                'from_email' => 'dispatch@voltflowpro.com',
                'from_address' => "890 Service Center Rd\nPhoenix, AZ 85004",
                'to_name' => 'Oakridge Property Management',
                'to_email' => 'ap@oakridgepm.com',
                'to_address' => "1200 Camelback Rd\nScottsdale, AZ 85251",
                'items' => [
                    ['description' => 'Emergency Diagnostic & Dispatch Callout Fee', 'quantity' => 1, 'unit_price' => 125],
                    ['description' => '200A Main Breaker Panel Upgrade (Master Electrician Labor)', 'quantity' => 6, 'unit_price' => 115],
                    ['description' => 'Square D 200A Load Center & Copper Grounding Kit', 'quantity' => 1, 'unit_price' => 490],
                ],
                'notes' => 'All circuits tested and certified to NEC 2023 code.',
                'terms' => 'Due upon job completion.',
            ],
            [
                'slug' => 'auto-repair-mechanic',
                'name' => 'Auto Repair & Detailing',
                'category' => 'contractor',
                'category_label' => 'Contractor & Trades',
                'description' => 'Compact ledger format listing parts, shop labor hours, and diagnostic inspection fees.',
                'template_style' => 'compact',
                'accent_color' => '#e11d48',
                'invoice_title' => 'REPAIR INVOICE',
                'prefix' => 'AUT-',
                'payment_terms' => 'Due on receipt',
                'tax_rate' => 8.0,
                'popular' => false,
                'from_name' => 'Precision Auto Works',
                'from_email' => 'service@precisionautoworks.com',
                'from_address' => "312 Motor Ave\nDetroit, MI 48201",
                'to_name' => 'Marcus Vance',
                'to_email' => 'marcus.v@example.com',
                'to_address' => "441 Maple Grove Ln\nRoyal Oak, MI 48067",
                'items' => [
                    ['description' => 'Front & Rear Ceramic Brake Pads + Rotors (OEM Parts)', 'quantity' => 1, 'unit_price' => 380],
                    ['description' => 'Brake System Replacement & Fluid Flush Labor', 'quantity' => 2.5, 'unit_price' => 110],
                    ['description' => 'Full Synthetic 5W-30 Oil Service & 24-Point Inspection', 'quantity' => 1, 'unit_price' => 85],
                ],
                'notes' => '12-month / 12,000-mile parts and labor warranty included.',
                'terms' => 'Payment due upon vehicle pickup.',
            ],

            // --- SAAS & TECH ---
            [
                'slug' => 'software-development-sprint',
                'name' => 'Software Agency & SaaS',
                'category' => 'tech',
                'category_label' => 'SaaS & Tech',
                'description' => 'Sleek Formnexa-blue modern template for SaaS subscriptions, cloud infrastructure, and agile sprints.',
                'template_style' => 'modern',
                'accent_color' => '#0066FF',
                'invoice_title' => 'INVOICE',
                'prefix' => 'SAAS-',
                'payment_terms' => 'Net 30',
                'tax_rate' => 0,
                'popular' => true,
                'from_name' => 'NexaCloud Systems Inc.',
                'from_email' => 'billing@nexacloud.io',
                'from_address' => "548 Market St, PMB 712\nSan Francisco, CA 94104",
                'to_name' => 'BrightPath FinTech',
                'to_email' => 'finance@brightpath.co',
                'to_address' => "101 Bishopsgate\nLondon EC2N 3BQ, UK",
                'items' => [
                    ['description' => 'Enterprise Platform License (50 Active Seats — Annual)', 'quantity' => 50, 'unit_price' => 48],
                    ['description' => 'Dedicated SSO / SAML & Custom Webhook Integration', 'quantity' => 1, 'unit_price' => 1500],
                    ['description' => 'Priority 24/7 SLA Support Package', 'quantity' => 1, 'unit_price' => 800],
                ],
                'notes' => 'Subscription auto-renews annually unless cancelled 30 days prior.',
                'terms' => 'Net 30 days via Wire or Stripe Invoice Link.',
            ],
            [
                'slug' => 'it-managed-services',
                'name' => 'IT Support & Cybersecurity',
                'category' => 'tech',
                'category_label' => 'SaaS & Tech',
                'description' => 'Clean stripe template for MSPs, network administrators, and cybersecurity audits.',
                'template_style' => 'stripe',
                'accent_color' => '#0d9488',
                'invoice_title' => 'INVOICE',
                'prefix' => 'IT-',
                'payment_terms' => 'Net 14',
                'tax_rate' => 0,
                'popular' => false,
                'from_name' => 'ShieldNet Managed IT',
                'from_email' => 'billing@shieldnet.tech',
                'from_address' => "400 Spectrum Center Dr\nIrvine, CA 92618",
                'to_name' => 'Clearview Dental Group',
                'to_email' => 'admin@clearviewdental.com',
                'to_address' => "780 Harbor Blvd\nCosta Mesa, CA 92627",
                'items' => [
                    ['description' => 'Managed Endpoint Protection & Patching (25 Workstations)', 'quantity' => 25, 'unit_price' => 45],
                    ['description' => 'Encrypted Cloud Backup & Disaster Recovery (2TB)', 'quantity' => 1, 'unit_price' => 290],
                    ['description' => 'Quarterly Network Penetration Audit', 'quantity' => 1, 'unit_price' => 750],
                ],
                'notes' => '99.98% uptime achieved for billing period.',
                'terms' => 'Net 14 days.',
            ],

            // --- RETAIL & WHOLESALE ---
            [
                'slug' => 'wholesale-product-order',
                'name' => 'Wholesale & Supply Order',
                'category' => 'retail',
                'category_label' => 'Retail & Wholesale',
                'description' => 'Compact accounting template built for bulk SKUs, shipping fees, and B2B wholesale distribution.',
                'template_style' => 'compact',
                'accent_color' => '#059669',
                'invoice_title' => 'COMMERCIAL INVOICE',
                'prefix' => 'WHL-',
                'payment_terms' => 'Net 30',
                'tax_rate' => 0,
                'popular' => false,
                'from_name' => 'Pacific Roasters Wholesale',
                'from_email' => 'orders@pacificroasters.com',
                'from_address' => "1420 Harbor Island Way\nSeattle, WA 98134",
                'to_name' => 'Cornerstone Cafe & Bakery',
                'to_email' => 'purchasing@cornerstonecafe.com',
                'to_address' => "215 Pike Place\nSeattle, WA 98101",
                'items' => [
                    ['description' => 'Single-Origin Espresso Roast (5lb Bulk Bags)', 'quantity' => 20, 'unit_price' => 54],
                    ['description' => 'Organic House Blend Medium Roast (5lb Bulk Bags)', 'quantity' => 15, 'unit_price' => 48],
                    ['description' => 'Compostable 12oz Hot Cups & Lids (Case of 1,000)', 'quantity' => 4, 'unit_price' => 92],
                ],
                'notes' => 'Delivered via local pallet courier. Packing slip #PS-8832 enclosed.',
                'terms' => 'Net 30 days. 1.5% monthly late fee applies on overdue balances.',
            ],
            [
                'slug' => 'event-catering-hospitality',
                'name' => 'Catering & Event Services',
                'category' => 'retail',
                'category_label' => 'Retail & Wholesale',
                'description' => 'Warm modern layout for caterers, event planners, and florists billing per-guest packages.',
                'template_style' => 'modern',
                'accent_color' => '#d97706',
                'invoice_title' => 'EVENT INVOICE',
                'prefix' => 'EVT-',
                'payment_terms' => 'Net 7',
                'tax_rate' => 8.5,
                'popular' => false,
                'from_name' => 'Saffron & Sage Catering',
                'from_email' => 'events@saffronandsage.com',
                'from_address' => "608 Culinary Lane\nNashville, TN 37203",
                'to_name' => 'Summit Tech Conference',
                'to_email' => 'events@summitconf.org',
                'to_address' => "201 Fifth Ave S\nNashville, TN 37203",
                'items' => [
                    ['description' => 'Executive Buffet Luncheon (Per Guest Package)', 'quantity' => 120, 'unit_price' => 38],
                    ['description' => 'Artisanal Coffee & Pastry Service Station', 'quantity' => 120, 'unit_price' => 12],
                    ['description' => 'On-Site Hospitality Staff & Tableware Setup (4 Staff)', 'quantity' => 1, 'unit_price' => 650],
                ],
                'notes' => 'Dietary accommodations (vegan & gluten-free) included as confirmed.',
                'terms' => 'Final balance due 7 days prior to event date.',
            ],

            // --- MEDICAL & WELLNESS ---
            [
                'slug' => 'medical-clinic-consultation',
                'name' => 'Medical & Therapy Clinic',
                'category' => 'medical',
                'category_label' => 'Medical & Wellness',
                'description' => 'Calm, trustworthy minimalist template for private practitioners, therapists, and wellness clinics.',
                'template_style' => 'minimal',
                'accent_color' => '#0d9488',
                'invoice_title' => 'MEDICAL RECEIPT',
                'prefix' => 'MED-',
                'payment_terms' => 'Due on receipt',
                'tax_rate' => 0,
                'popular' => false,
                'from_name' => 'Harborview Wellness & Therapy',
                'from_email' => 'care@harborviewclinic.org',
                'from_address' => "330 Medical Plaza, Suite 305\nSan Diego, CA 92101",
                'to_name' => 'Elena Rostova',
                'to_email' => 'elena.r@example.com',
                'to_address' => "1190 Coronado Ave\nSan Diego, CA 92118",
                'items' => [
                    ['description' => 'Initial Comprehensive Clinical Consultation (60 Min)', 'quantity' => 1, 'unit_price' => 185],
                    ['description' => 'Diagnostic Assessment & Personalized Care Plan', 'quantity' => 1, 'unit_price' => 95],
                ],
                'notes' => 'NPI #1928374650. Eligible for HSA/FSA reimbursement.',
                'terms' => 'Paid in full at time of visit.',
            ],
            [
                'slug' => 'fitness-personal-training',
                'name' => 'Fitness & Personal Training',
                'category' => 'medical',
                'category_label' => 'Medical & Wellness',
                'description' => 'High-energy bold template for personal trainers, coaches, and athletic studios.',
                'template_style' => 'bold',
                'accent_color' => '#e11d48',
                'invoice_title' => 'INVOICE',
                'prefix' => 'FIT-',
                'payment_terms' => 'Due on receipt',
                'tax_rate' => 0,
                'popular' => false,
                'from_name' => 'Peak Performance Coaching',
                'from_email' => 'coach@peaktraining.fit',
                'from_address' => "55 Athletic Way\nMiami, FL 33131",
                'to_name' => 'Jordan Blake',
                'to_email' => 'jordan.b@example.com',
                'to_address' => "220 Brickell Key Dr\nMiami, FL 33131",
                'items' => [
                    ['description' => '1-on-1 Personal Training Package (10 Sessions)', 'quantity' => 1, 'unit_price' => 850],
                    ['description' => 'Custom Macro & Nutrition Programming (Monthly)', 'quantity' => 1, 'unit_price' => 150],
                ],
                'notes' => 'Sessions valid for 90 days from purchase date.',
                'terms' => 'Due upon package activation.',
            ],
        ];
    }

    /**
     * Find a single template by slug.
     */
    public static function find(string $slug): ?array
    {
        foreach (self::templates() as $tpl) {
            if ($tpl['slug'] === $slug) {
                return $tpl;
            }
        }
        return null;
    }

    /**
     * All 12 supported invoice/document generators.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function generators(): array
    {
        return \App\Http\Controllers\InvoiceController::$generators;
    }

    /**
     * All 24 supported billing currencies matching the web studio.
     *
     * @return array<int, array{code: string, symbol: string, label: string, pair: string}>
     */
    public static function currencies(): array
    {
        $list = [
            ['USD', '$'],
            ['EUR', '€'],
            ['GBP', '£'],
            ['CAD', '$'],
            ['AUD', '$'],
            ['INR', '₹'],
            ['PKR', 'Rs'],
            ['AED', 'AED'],
            ['SAR', 'SAR'],
            ['JPY', '¥'],
            ['CNY', '¥'],
            ['CHF', 'CHF'],
            ['SGD', '$'],
            ['NZD', '$'],
            ['BRL', 'R$'],
            ['MXN', '$'],
            ['ZAR', 'R'],
            ['TRY', '₺'],
            ['SEK', 'kr'],
            ['NOK', 'kr'],
            ['THB', '฿'],
            ['IDR', 'Rp'],
            ['MYR', 'RM'],
            ['PHP', '₱'],
        ];

        return array_map(static fn (array $item) => [
            'code' => $item[0],
            'symbol' => $item[1],
            'label' => "{$item[0]} ({$item[1]})",
            'pair' => "{$item[0]}|{$item[1]}",
        ], $list);
    }

    /**
     * Supported standard payment terms options.
     *
     * @return array<int, array{value: string, label: string, days: int}>
     */
    public static function paymentTerms(): array
    {
        return [
            ['value' => 'Due on receipt', 'label' => 'Due on receipt', 'days' => 0],
            ['value' => 'Net 7', 'label' => 'Net 7 Days', 'days' => 7],
            ['value' => 'Net 14', 'label' => 'Net 14 Days', 'days' => 14],
            ['value' => 'Net 30', 'label' => 'Net 30 Days', 'days' => 30],
            ['value' => 'Net 60', 'label' => 'Net 60 Days', 'days' => 60],
        ];
    }

    /**
     * Preset cover photo options from the Invoice Studio.
     *
     * @return array<int, array{key: string, name: string, url: string}>
     */
    public static function coverPresets(): array
    {
        return [
            [
                'key' => 'emerald',
                'name' => 'Emerald',
                'url' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'key' => 'abstract',
                'name' => 'Abstract',
                'url' => 'https://images.unsplash.com/photo-1634017839464-5c339ebe3cb4?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'key' => 'dark',
                'name' => 'Obsidian',
                'url' => 'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?auto=format&fit=crop&w=1200&q=80',
            ],
        ];
    }
}
