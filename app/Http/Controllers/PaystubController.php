<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaystubController extends Controller
{
    /**
     * Interactive Paystub & Salary Slip Maker Studio.
     */
    public function index(): View
    {
        $defaultData = $this->defaultPaystubData();
        return view('paystub.studio', compact('defaultData'));
    }

    /**
     * Regional Salary Slip Maker Studio for Pakistan, India, UAE & Saudi Arabia.
     */
    public function salarySlip(): View
    {
        $defaultData = $this->defaultSalarySlipData();
        return view('paystub.salary-slip', compact('defaultData'));
    }

    /**
     * Download crisp vector PDF Paystub / Salary Slip without watermarks.
     */
    public function downloadPdf(Request $request)
    {
        $payload = $this->normalizePaystubPayload($request);

        $pdf = Pdf::loadView('paystub.pdf', [
            'stub' => $payload,
        ])->setPaper('a4', 'portrait')->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'sans-serif',
            'dpi' => 150,
        ]);

        $employeeSlug = Str::slug($payload['employee']['name'] ?? 'employee') ?: 'employee';
        $fileName = "paystub-{$employeeSlug}-" . ($payload['pay_period']['end_date'] ?? date('Y-m-d')) . '.pdf';

        if ($request->boolean('stream')) {
            return $pdf->stream($fileName);
        }

        return $pdf->download($fileName);
    }

    /**
     * Normalize & calculate paystub numbers.
     */
    protected function normalizePaystubPayload(Request $request): array
    {
        $raw = $request->all();

        $company = [
            'name' => (string) ($raw['company']['name'] ?? 'Acme Global Technologies Inc.'),
            'address' => (string) ($raw['company']['address'] ?? "100 Tech Boulevard, Suite 400\nSan Francisco, CA 94107"),
            'ein' => (string) ($raw['company']['ein'] ?? '12-3456789'),
        ];

        $employee = [
            'name' => (string) ($raw['employee']['name'] ?? 'Alex Morgan'),
            'address' => (string) ($raw['employee']['address'] ?? "742 Evergreen Terrace\nSpringfield, OR 97477"),
            'id' => (string) ($raw['employee']['id'] ?? 'EMP-8942'),
            'title' => (string) ($raw['employee']['title'] ?? 'Senior Software Engineer'),
            'ssn_last4' => (string) ($raw['employee']['ssn_last4'] ?? '***-**-5821'),
        ];

        $payPeriod = [
            'frequency' => (string) ($raw['pay_period']['frequency'] ?? 'Bi-Weekly'),
            'start_date' => (string) ($raw['pay_period']['start_date'] ?? now()->subWeeks(2)->format('Y-m-d')),
            'end_date' => (string) ($raw['pay_period']['end_date'] ?? now()->format('Y-m-d')),
            'pay_date' => (string) ($raw['pay_period']['pay_date'] ?? now()->format('Y-m-d')),
        ];

        $currency = [
            'symbol' => (string) ($raw['currency']['symbol'] ?? '$'),
            'code' => (string) ($raw['currency']['code'] ?? 'USD'),
        ];

        // Process Earnings lines
        $earnings = [];
        $totalGross = 0;
        $totalYtdGross = 0;

        $rawEarnings = is_array($raw['earnings'] ?? null) ? $raw['earnings'] : $this->defaultPaystubData()['earnings'];
        foreach ($rawEarnings as $item) {
            $desc = trim((string) ($item['description'] ?? ''));
            if ($desc === '') continue;

            $hours = (float) ($item['hours'] ?? 0);
            $rate = (float) ($item['rate'] ?? 0);
            $amount = (float) ($item['amount'] ?? ($hours > 0 && $rate > 0 ? $hours * $rate : 0));
            $ytd = (float) ($item['ytd'] ?? ($amount * 10)); // realistic default YTD estimation

            $totalGross += $amount;
            $totalYtdGross += $ytd;

            $earnings[] = [
                'description' => $desc,
                'hours' => $hours,
                'rate' => $rate,
                'amount' => $amount,
                'ytd' => $ytd,
            ];
        }

        // Process Deductions lines
        $deductions = [];
        $totalDeductions = 0;
        $totalYtdDeductions = 0;

        $rawDeductions = is_array($raw['deductions'] ?? null) ? $raw['deductions'] : $this->defaultPaystubData()['deductions'];
        foreach ($rawDeductions as $item) {
            $desc = trim((string) ($item['description'] ?? ''));
            if ($desc === '') continue;

            $amount = (float) ($item['amount'] ?? 0);
            $ytd = (float) ($item['ytd'] ?? ($amount * 10));

            $totalDeductions += $amount;
            $totalYtdDeductions += $ytd;

            $deductions[] = [
                'description' => $desc,
                'amount' => $amount,
                'ytd' => $ytd,
            ];
        }

        $netPay = max(0, $totalGross - $totalDeductions);
        $ytdNetPay = max(0, $totalYtdGross - $totalYtdDeductions);

        return [
            'statement_title' => (string) ($raw['statement_title'] ?? 'Earnings Statement'),
            'style' => (string) ($raw['style'] ?? 'corporate'),
            'currency' => $currency,
            'company' => $company,
            'employee' => $employee,
            'pay_period' => $payPeriod,
            'earnings' => $earnings,
            'deductions' => $deductions,
            'totals' => [
                'gross_pay' => $totalGross,
                'ytd_gross' => $totalYtdGross,
                'deductions' => $totalDeductions,
                'ytd_deductions' => $totalYtdDeductions,
                'net_pay' => $netPay,
                'ytd_net_pay' => $ytdNetPay,
            ],
            'payment' => [
                'method' => (string) ($raw['payment']['method'] ?? 'Direct Deposit'),
                'account_last4' => (string) ($raw['payment']['account_last4'] ?? '**** 7729'),
                'check_number' => (string) ($raw['payment']['check_number'] ?? 'CHK-10492'),
            ],
            'notes' => (string) ($raw['notes'] ?? 'Thank you for your service and dedication to the company.'),
        ];
    }

    /**
     * Default realistic initial paystub sample data.
     */
    protected function defaultPaystubData(): array
    {
        return [
            'style' => 'corporate',
            'currency' => ['symbol' => '$', 'code' => 'USD'],
            'company' => [
                'name' => 'Acme Global Technologies Inc.',
                'address' => "100 Tech Boulevard, Suite 400\nSan Francisco, CA 94107",
                'ein' => '12-3456789',
            ],
            'employee' => [
                'name' => 'Alex Morgan',
                'address' => "742 Evergreen Terrace\nSpringfield, OR 97477",
                'id' => 'EMP-8942',
                'title' => 'Senior Software Engineer',
                'ssn_last4' => '***-**-5821',
            ],
            'pay_period' => [
                'frequency' => 'Bi-Weekly',
                'start_date' => now()->subDays(14)->format('Y-m-d'),
                'end_date' => now()->format('Y-m-d'),
                'pay_date' => now()->format('Y-m-d'),
            ],
            'earnings' => [
                ['description' => 'Regular Earnings', 'hours' => 80.0, 'rate' => 45.00, 'amount' => 3600.00, 'ytd' => 36000.00],
                ['description' => 'Overtime (1.5x)', 'hours' => 5.0, 'rate' => 67.50, 'amount' => 337.50, 'ytd' => 2025.00],
                ['description' => 'Performance Bonus', 'hours' => 0, 'rate' => 0, 'amount' => 250.00, 'ytd' => 1500.00],
            ],
            'deductions' => [
                ['description' => 'Federal Income Tax (FIT)', 'amount' => 512.40, 'ytd' => 5124.00],
                ['description' => 'Social Security (FICA 6.2%)', 'amount' => 259.62, 'ytd' => 2450.55],
                ['description' => 'Medicare (1.45%)', 'amount' => 60.72, 'ytd' => 573.05],
                ['description' => 'State Income Tax (SIT)', 'amount' => 188.44, 'ytd' => 1884.40],
                ['description' => 'Health & Dental Insurance (Pre-Tax)', 'amount' => 125.00, 'ytd' => 1250.00],
                ['description' => '401(k) Retirement Plan', 'amount' => 209.37, 'ytd' => 1976.25],
            ],
            'totals' => [
                'gross_pay' => 4187.50,
                'ytd_gross' => 39525.00,
                'deductions' => 1355.55,
                'ytd_deductions' => 13258.25,
                'net_pay' => 2831.95,
                'ytd_net_pay' => 26266.75,
            ],
            'payment' => [
                'method' => 'Direct Deposit',
                'account_last4' => '**** 7729',
                'check_number' => 'CHK-10492',
            ],
            'notes' => 'Thank you for your service and dedication to the company.',
        ];
    }

    /**
     * Regional monthly salary slip defaults for Pakistan, India, and Gulf markets.
     */
    protected function defaultSalarySlipData(): array
    {
        return [
            'style' => 'corporate',
            'currency' => ['symbol' => 'PKR ', 'code' => 'PKR'],
            'company' => [
                'name' => 'Crescent Digital Systems (Pvt.) Ltd.',
                'address' => "Office 402, Business Avenue\nLahore / Karachi / Dubai",
                'ein' => 'NTN-4829103-7',
            ],
            'employee' => [
                'name' => 'Saad Ahmed',
                'address' => "House 18, Street 4\nLahore",
                'id' => 'EMP-2041',
                'title' => 'Senior Software Engineer',
                'ssn_last4' => 'ID: ****-****123-1',
            ],
            'pay_period' => [
                'frequency' => 'Monthly',
                'start_date' => now()->startOfMonth()->format('Y-m-d'),
                'end_date' => now()->endOfMonth()->format('Y-m-d'),
                'pay_date' => now()->endOfMonth()->format('Y-m-d'),
            ],
            'earnings' => [
                ['description' => 'Basic Salary', 'hours' => 0, 'rate' => 0, 'amount' => 140000.00, 'ytd' => 1400000.00],
                ['description' => 'House Rent Allowance', 'hours' => 0, 'rate' => 0, 'amount' => 35000.00, 'ytd' => 350000.00],
                ['description' => 'Medical & Conveyance Allowance', 'hours' => 0, 'rate' => 0, 'amount' => 15000.00, 'ytd' => 150000.00],
            ],
            'deductions' => [
                ['description' => 'Income Tax Withheld', 'amount' => 12500.00, 'ytd' => 125000.00],
                ['description' => 'Provident Fund / Social Security', 'amount' => 5000.00, 'ytd' => 50000.00],
            ],
            'totals' => [
                'gross_pay' => 190000.00,
                'ytd_gross' => 1900000.00,
                'deductions' => 17500.00,
                'ytd_deductions' => 175000.00,
                'net_pay' => 172500.00,
                'ytd_net_pay' => 1725000.00,
            ],
            'payment' => [
                'method' => 'Bank Transfer',
                'account_last4' => '**** 4820',
                'check_number' => 'TXN-80912',
            ],
            'notes' => 'Monthly payroll summary for internal employer and employee records.',
        ];
    }
}
