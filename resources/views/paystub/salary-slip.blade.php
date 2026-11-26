@extends('layouts.app')

<x-schema.tool
    name="Free Salary Slip Generator for Pakistan, India and UAE"
    description="Create employee salary slips in PKR, INR, AED, or SAR online. Add allowances, deductions, and YTD totals, then download a clean PDF."
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
                ['Paystub Generator', route('paystub.studio')],
                ['Salary Slip Generator', route('paystub.salary_slip')],
            ]" />
        </div>

        {{-- Top Badge --}}
        <div class="flex items-center justify-center gap-2 mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e8f7ed] text-[#184f3a] border border-[#c8f3dc]">
                <span class="w-2 h-2 rounded-full bg-[#184f3a]"></span>
                PKR &bull; INR &bull; AED &bull; SAR Presets &bull; Free PDF Download
            </span>
        </div>

        {{-- Unique Header Copy --}}
        <div class="text-center max-w-3xl mx-auto mb-6 sm:mb-8">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black font-display tracking-tight text-[#17211b] mb-3">
                Free Salary Slip Generator for Pakistan, India &amp; UAE
            </h1>
            <p class="text-base sm:text-lg text-[#55635a] leading-relaxed mb-4">
                Create clean monthly salary slips with basic pay, house rent and medical allowances, and manual deductions in PKR, INR, AED, or SAR.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-2 text-xs">
                <button type="button" @click="currency.symbol = 'PKR '; pay_period.frequency = 'Monthly'"
                        class="px-3 py-1.5 rounded-full border font-bold transition cursor-pointer"
                        :class="currency.symbol === 'PKR ' ? 'bg-[#184f3a] text-white border-[#184f3a]' : 'bg-white border-[#d6ded8] text-[#17211b] hover:border-[#184f3a]'">
                    PKR (Pakistan)
                </button>
                <button type="button" @click="currency.symbol = 'INR '; pay_period.frequency = 'Monthly'"
                        class="px-3 py-1.5 rounded-full border font-bold transition cursor-pointer"
                        :class="currency.symbol === 'INR ' ? 'bg-[#184f3a] text-white border-[#184f3a]' : 'bg-white border-[#d6ded8] text-[#17211b] hover:border-[#184f3a]'">
                    INR (India)
                </button>
                <button type="button" @click="currency.symbol = 'AED '; pay_period.frequency = 'Monthly'"
                        class="px-3 py-1.5 rounded-full border font-bold transition cursor-pointer"
                        :class="currency.symbol === 'AED ' ? 'bg-[#184f3a] text-white border-[#184f3a]' : 'bg-white border-[#d6ded8] text-[#17211b] hover:border-[#184f3a]'">
                    AED (UAE)
                </button>
                <button type="button" @click="currency.symbol = 'SAR '; pay_period.frequency = 'Monthly'"
                        class="px-3 py-1.5 rounded-full border font-bold transition cursor-pointer"
                        :class="currency.symbol === 'SAR ' ? 'bg-[#184f3a] text-white border-[#184f3a]' : 'bg-white border-[#d6ded8] text-[#17211b] hover:border-[#184f3a]'">
                    SAR (Saudi Arabia)
                </button>
                <a href="{{ route('paystub.studio') }}" class="px-3 py-1.5 text-[#184f3a] font-bold hover:underline">
                    Switch to US/UK Paystub Maker &rarr;
                </a>
            </div>
        </div>

        {{-- Shared Studio Widget with Regional Labels --}}
        @include('paystub._studio-widget', [
            'defaultData' => $defaultData,
            'labels' => [
                'ein_label' => 'Company Tax / NTN / GST / TRN #',
                'ein_short' => 'Tax / Reg #',
                'id_label' => 'CNIC / PAN / Emirates ID',
                'id_short' => 'Emp ID / Nat ID',
                'statement_title' => 'Monthly Salary Slip',
                'download_button' => 'Download PDF Salary Slip',
                'preview_title' => 'Live Salary Slip Preview',
                'pay_to_label' => 'Employee Name',
            ],
        ])

        {{-- UNIQUE REGIONAL SALARY SLIP CONTENT (MARKED DRAFT FOR REVIEW) --}}
        <div class="mt-16 pt-12 border-t border-[#e2e8e3] space-y-12">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-3">
                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-mono font-bold uppercase tracking-wider">DRAFT</span>
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        Monthly Salary Slip Format Across South Asia &amp; the Gulf
                    </h2>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        Employers in Pakistan, India, the UAE, and Saudi Arabia typically issue monthly salary slips rather than bi-weekly paystubs. Compensation is commonly broken down into <strong>Basic Salary</strong> plus structured allowances such as <strong>House Rent Allowance (HRA)</strong>, <strong>Medical Allowance</strong>, and <strong>Conveyance / Transport Allowance</strong>.
                    </p>
                    <p class="text-sm text-[#536058] leading-relaxed">
                        Statutory deductions and labor requirements differ by country and company size (for example, salary tax withholdings or provident contributions—<em>TODO: verify local statutory rules before citing specific mandates</em>). Use the manual deduction lines to match your company&rsquo;s exact payroll breakdown.
                    </p>
                </section>

                <section class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm space-y-3">
                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-mono font-bold uppercase tracking-wider">DRAFT</span>
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                        Common Regional Fields You Can Customize
                    </h2>
                    <ul class="space-y-2 text-sm text-[#536058] leading-relaxed">
                        <li><strong class="text-[#17211b]">Pakistan (PKR):</strong> Company NTN, masked employee CNIC (optional), Basic Pay, House Rent Allowance, Utility/Medical Allowances, and Income Tax or EOBI lines.</li>
                        <li><strong class="text-[#17211b]">India (INR):</strong> Company PAN/GST, Employee Code, Basic Salary, HRA, Special Allowance, PF (Provident Fund), and TDS/Professional Tax entries.</li>
                        <li><strong class="text-[#17211b]">UAE &amp; Saudi Arabia (AED / SAR):</strong> Basic Salary, Housing Allowance, Transport Allowance, Bank Transfer reference, and any approved advances or deductions.</li>
                    </ul>
                </section>
            </div>

            {{-- Regional FAQ --}}
            @php
                $regionalFaqs = [
                    [
                        'q' => 'How is a salary slip different from a US paystub?',
                        'a' => 'Both record wages paid and deductions withheld for an employee. Salary slips in Pakistan, India, and the Gulf are typically issued monthly and itemize Basic Salary alongside House Rent, Medical, and Transport allowances rather than hourly overtime alone.',
                    ],
                    [
                        'q' => 'Can I switch between PKR, INR, AED, and SAR?',
                        'a' => 'Yes. Click any of the currency preset buttons above the form or use the Currency dropdown to switch symbols cleanly before downloading your PDF.',
                    ],
                    [
                        'q' => 'Does this tool compute FBR, TDS, or local labor deductions automatically?',
                        'a' => 'No. You enter each tax, provident fund, or allowance figure manually based on your company payroll ledger, and Formnexa totals gross earnings minus deductions.',
                    ],
                    [
                        'q' => 'Should I include full national ID numbers on a PDF salary slip?',
                        'a' => 'We recommend masking sensitive identifiers (for example showing only the last 4 digits of a CNIC, PAN, or Emirates ID) unless your internal employer policy specifies otherwise.',
                    ],
                    [
                        'q' => 'Is any payroll data stored on Formnexa servers?',
                        'a' => 'No. Inputs remain in browser memory while editing, and the PDF is generated in memory upon download without saving salary records to a database.',
                    ],
                ];
            @endphp
            <div class="bg-white rounded-3xl border border-[#e2e8e3] p-6 sm:p-8 shadow-sm">
                <x-faq :items="$regionalFaqs" heading="Frequently Asked Questions About Salary Slips (DRAFT)" eyebrow="Regional FAQ" />
            </div>

            {{-- Related Tools --}}
            @php
                $relatedTools = [
                    [
                        'title' => 'US / International Paystub Generator',
                        'description' => 'Standard weekly & bi-weekly USD/EUR/GBP paystub format with hours, overtime, and deductions.',
                        'url' => route('paystub.studio'),
                        'badge' => 'Paystub Maker',
                        'cta' => 'Open Paystub Generator',
                    ],
                    [
                        'title' => 'Free Invoice Generator',
                        'description' => 'Send clean PKR, INR, AED, SAR, or USD invoices to local and international clients.',
                        'url' => route('invoice.studio'),
                        'badge' => 'Billing',
                        'cta' => 'Open Invoice Generator',
                    ],
                    [
                        'title' => 'Free Timesheet Generator',
                        'description' => 'Track monthly or weekly team hours and project rates before generating payroll records.',
                        'url' => route('invoice.generator', 'timesheet-generator'),
                        'badge' => 'Time Tracking',
                        'cta' => 'Open Timesheet Maker',
                    ],
                    [
                        'title' => 'Free Receipt Generator',
                        'description' => 'Create instant payment receipts for freelance retainers and cash or bank transfers.',
                        'url' => route('invoice.generator', 'receipt-generator'),
                        'badge' => 'Receipts',
                        'cta' => 'Open Receipt Generator',
                    ],
                ];
                foreach (\App\Models\BlogPost::publishedBySlugs([
                    'salary-slip-format-pakistan-india-uae',
                    'paystub-vs-payslip-vs-salary-slip',
                    'gross-pay-vs-net-pay-explained',
                    'how-to-create-paystub-online-free-guide',
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
            <x-related-tools :tools="$relatedTools" heading="More Free Billing & Payroll Tools" eyebrow="Business Suite" />

        </div>

    </div>
</div>
@endsection
