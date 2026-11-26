@extends('layouts.app')

<x-schema.tool
    name="Free Paystub & Salary Slip Generator"
    description="Create paystubs and salary slips for your employees online. Add earnings, deductions and YTD totals, then download a PDF."
    category="BusinessApplication"
/>

@section('content')
<div class="min-h-screen bg-[#fafbfa] text-[#17211b] py-8 sm:py-12"
     x-data="paystubStudio(@js($defaultData))"
     x-cloak>

    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- Visible Breadcrumbs + BreadcrumbList JSON-LD --}}
        <div class="mb-5 flex justify-center">
            <x-schema.breadcrumbs :items="[
                ['Home', url('/')],
                ['Documents', route('templates.index')],
                ['Paystub Generator', route('paystub.studio')],
            ]" />
        </div>

        {{-- Top Badge --}}
        <div class="flex items-center justify-center gap-2 mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e8f7ed] text-[#184f3a] border border-[#c8f3dc]">
                <span class="w-2 h-2 rounded-full bg-[#184f3a]"></span>
                100% Free &bull; Instant PDF Download &bull; Zero Watermark
            </span>
        </div>

        {{-- Headline & Hero Copy --}}
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black font-display tracking-tight text-[#17211b] mb-3">
                Free Paystub &amp; Salary Slip Generator
            </h1>
            <p class="text-base sm:text-lg text-[#55635a] leading-relaxed mb-3">
                Create formatted employee paystubs and salary slips online. Enter earnings and manual deductions, preview Year-to-Date (YTD) totals, and download a watermark-free PDF.
            </p>
            <a href="{{ route('paystub.salary_slip') }}"
               class="inline-flex items-center gap-1 text-xs font-bold text-[#184f3a] hover:underline">
                <span>Need a PKR, INR, AED, or SAR preset? Try our Regional Salary Slip Generator &rarr;</span>
            </a>
        </div>

        {{-- Shared Paystub Studio Widget --}}
        @include('paystub._studio-widget', ['defaultData' => $defaultData])

        {{-- EDUCATIONAL CONTENT SECTIONS 1-8 --}}
        <div class="mt-16 pt-12 border-t border-[#e2e8e3] space-y-12">

            {{-- 1. What is a paystub? & 2. What a pay stub includes --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-3">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        What Is a Paystub?
                    </h2>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        A paystub (also called a pay stub, check stub, or earnings statement) is a written payroll summary given by an employer to an employee or contractor alongside each paycheck or direct deposit.
                    </p>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        It itemizes how net take-home pay was reached for a specific pay period: hours worked or fixed salary, bonuses or overtime, withheld taxes and benefit deductions, and cumulative year-to-date (YTD) running totals.
                    </p>
                </section>

                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-3">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        What a Pay Stub Includes
                    </h2>
                    <ul class="space-y-2 text-sm text-[#536058] leading-relaxed">
                        <li><strong class="text-[#17211b]">Employer &amp; employee details:</strong> Company name, address, tax/employer ID, employee name, title, and optional masked ID or last 4 digits.</li>
                        <li><strong class="text-[#17211b]">Pay period &amp; frequency:</strong> Start date, end date, payment date, and cycle (weekly, bi-weekly, semi-monthly, or monthly).</li>
                        <li><strong class="text-[#17211b]">Gross pay:</strong> Regular wages, hourly pay (hours &times; rate), overtime, and bonuses before deductions.</li>
                        <li><strong class="text-[#17211b]">Deductions &amp; withholdings:</strong> Entered income taxes, social contributions, health insurance, or retirement deductions.</li>
                        <li><strong class="text-[#17211b]">Net pay:</strong> Final take-home amount after total deductions are subtracted from gross pay.</li>
                        <li><strong class="text-[#17211b]">Year-to-date (YTD) totals:</strong> Cumulative earnings, deductions, and net pay across the calendar year so far.</li>
                    </ul>
                </section>
            </div>

            {{-- 3. How YTD totals work & 4. Paystub vs Payslip vs Salary Slip --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-4">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        How Year-to-Date (YTD) Totals Work
                    </h2>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        <strong>YTD (Year-to-Date)</strong> tracks the total amount earned and deducted from January 1 through the current pay date.
                    </p>
                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-4 text-xs text-[#536058] space-y-1.5">
                        <div class="font-bold text-[#17211b]">Simple Worked Example:</div>
                        <p>Suppose an employee earns <strong>$2,500.00</strong> gross per bi-weekly paycheck with <strong>$450.00</strong> in total deductions (net pay: <strong>$2,050.00</strong>).</p>
                        <p>After <strong>4 paychecks</strong> in the calendar year: <strong>YTD Gross</strong> = $10,000.00, <strong>YTD Deductions</strong> = $1,800.00, and <strong>YTD Net Pay</strong> = $8,200.00.</p>
                    </div>
                    <p class="text-xs text-[#68736d] leading-relaxed">
                        Note: In this studio preview, each line&rsquo;s YTD column defaults to 10&times; the current line amount as a convenient placeholder preview and totals gross/deductions automatically.
                    </p>
                </section>

                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-4">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        Paystub vs. Payslip vs. Salary Slip
                    </h2>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        All three terms describe the exact same payroll document&mdash;only regional business terminology differs:
                    </p>
                    <ul class="space-y-2 text-sm text-[#536058] leading-relaxed">
                        <li><strong class="text-[#17211b]">Paystub (or Pay Stub):</strong> Standard term in the United States and Canada.</li>
                        <li><strong class="text-[#17211b]">Payslip:</strong> Common across the United Kingdom, Europe, Australia, and New Zealand.</li>
                        <li><strong class="text-[#17211b]">Salary Slip:</strong> Widely used across Pakistan, India, the UAE, Saudi Arabia, and neighboring markets.</li>
                    </ul>
                    <a href="{{ route('paystub.salary_slip') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#184f3a] hover:underline">
                        <span>Open the Regional Salary Slip Generator (PKR, INR, AED, SAR) &rarr;</span>
                    </a>
                </section>
            </div>

            {{-- 5. Who Should Use This Tool & 6. How to Use the Generator --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-3">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        Who Should Use This Paystub Maker
                    </h2>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        This tool is designed for <strong>small business owners</strong>, <strong>freelancers paying subcontractors or assistants</strong>, and <strong>household employers</strong> who want a clean PDF record of wages paid and deductions withheld.
                    </p>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        <strong>Important notice:</strong> Legal requirements for issuing pay stubs, required statutory line items, and tax withholdings vary by state, province, and country. This generator is a formatting utility&mdash;it does not replace full payroll software, accounting systems, or qualified tax advice.
                    </p>
                </section>

                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-3">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        How to Generate a Paystub PDF
                    </h2>
                    <ol class="list-decimal list-inside space-y-2 text-sm text-[#536058] leading-relaxed">
                        <li>Select your currency (`$`, `€`, `£`, `PKR`, `INR`, `AED`, `SAR`) and pay frequency.</li>
                        <li>Fill in your company details, employee information, and optional masked ID.</li>
                        <li>Set your pay period start, period end, and payment date.</li>
                        <li>Add earnings lines (hours &times; rate multiplies automatically, or enter fixed amounts).</li>
                        <li>Use <strong>+ Add Deduction</strong> to enter any taxes or benefit deductions manually.</li>
                        <li>Verify the live preview totals and click <strong>Download PDF Paystub</strong>.</li>
                    </ol>
                </section>
            </div>

            {{-- 7. FAQ Component (Single Source of Truth: Visible HTML + FAQPage JSON-LD) --}}
            @php
                $faqItems = [
                    [
                        'q' => 'Are employers required to give pay stubs?',
                        'a' => 'Rules vary significantly by state, province, and country. Some jurisdictions require written or electronic itemized pay statements on every payday, while others have different rules or do not mandate them uniformly—check your local labor authority or accountant for your location.',
                    ],
                    [
                        'q' => 'What is the difference between gross pay and net pay?',
                        'a' => 'Gross pay is total compensation earned before anything is subtracted, including base salary, hourly wages, overtime, and bonuses. Net pay (take-home pay) is the final amount remaining after income taxes and other deductions are subtracted.',
                    ],
                    [
                        'q' => 'What does YTD mean?',
                        'a' => 'YTD stands for Year-to-Date and represents cumulative gross pay, deductions, and net wages from January 1 up through the current pay date. Our live studio calculates a 10x placeholder YTD estimate for quick previews.',
                    ],
                    [
                        'q' => 'Does this calculate taxes?',
                        'a' => 'No. This generator does not automatically compute federal, state, or local tax brackets. You enter taxes and deductions manually using the + Add Deduction button, and Formnexa sums those deductions and subtracts them from gross pay.',
                    ],
                    [
                        'q' => 'Can I make a salary slip in PKR, INR or AED?',
                        'a' => 'Yes. Choose PKR, INR, Rs, AED, or SAR directly in the Currency dropdown above, or use our dedicated Salary Slip Generator page preset specifically for Pakistan, India, and Gulf currencies.',
                    ],
                    [
                        'q' => 'Is my data stored?',
                        'a' => 'No. All company, employee, SSN last-4, and pay fields stay in your browser memory while editing. When you click Download PDF Paystub, the form posts once to generate the PDF in memory and sends it straight back without storing records in a database.',
                    ],
                    [
                        'q' => 'Can I use this for payroll compliance?',
                        'a' => 'No, this is a document formatting tool rather than certified payroll or tax-filing software. It does not remit taxes, track statutory brackets, or file government payroll reports.',
                    ],
                ];
            @endphp
            <div class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm">
                <x-faq :items="$faqItems" heading="Frequently Asked Questions About Paystubs" eyebrow="Paystub FAQ" />
            </div>

            {{-- 8. CTAs & Related Tools --}}
            @php
                $relatedTools = [
                    [
                        'title' => 'Salary Slip Generator (PKR, INR, AED)',
                        'description' => 'Preset monthly salary slip layout with regional labels for Pakistan, India, UAE, and Saudi Arabia.',
                        'url' => route('paystub.salary_slip'),
                        'badge' => 'Regional Preset',
                        'cta' => 'Open Salary Slip Maker',
                    ],
                    [
                        'title' => 'Free Invoice Generator',
                        'description' => 'Create professional client invoices with taxes, logo, and instant PDF download.',
                        'url' => route('invoice.studio'),
                        'badge' => 'Billing',
                        'cta' => 'Open Invoice Generator',
                    ],
                    [
                        'title' => 'Free Timesheet Generator',
                        'description' => 'Track weekly hours, overtime rates, and contractor time logs before running payroll.',
                        'url' => route('invoice.generator', 'timesheet-generator'),
                        'badge' => 'Time Tracking',
                        'cta' => 'Open Timesheet Maker',
                    ],
                    [
                        'title' => 'Free Receipt Generator',
                        'description' => 'Issue clean payment receipts for completed contractor or client payments.',
                        'url' => route('invoice.generator', 'receipt-generator'),
                        'badge' => 'Receipts',
                        'cta' => 'Open Receipt Generator',
                    ],
                ];
                foreach (\App\Models\BlogPost::publishedBySlugs([
                    'how-to-create-paystub-online-free-guide',
                    'what-is-on-a-pay-stub',
                    'paystub-vs-payslip-vs-salary-slip',
                    'gross-pay-vs-net-pay-explained',
                    'how-to-create-pay-stubs-for-employees',
                    'salary-slip-format-pakistan-india-uae',
                ]) as $clusterPost) {
                    $relatedTools[] = [
                        'title' => $clusterPost->title,
                        'description' => \Illuminate\Support\Str::limit($clusterPost->excerpt, 110),
                        'url' => route('blog.show', $clusterPost->slug),
                        'badge' => 'Payroll Guide',
                        'cta' => 'Read Guide',
                    ];
                }
            @endphp
            <x-related-tools :tools="$relatedTools" heading="Related Payroll & Billing Tools" eyebrow="Business Suite" />

        </div>

    </div>
</div>
@endsection
