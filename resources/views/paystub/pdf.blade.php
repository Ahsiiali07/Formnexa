<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Earnings Statement - {{ $stub['employee']['name'] }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: -0.3px;
        }
        .company-meta {
            font-size: 8.5pt;
            color: #475569;
            margin-top: 3px;
            line-height: 1.3;
        }
        .statement-title {
            text-align: right;
            font-size: 16pt;
            font-weight: 900;
            color: #184f3a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .statement-sub {
            text-align: right;
            font-size: 8.5pt;
            font-weight: bold;
            color: #64748b;
        }
        .meta-grid {
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
        }
        .meta-grid td {
            padding: 6px 10px;
            vertical-align: top;
            font-size: 8.5pt;
            border-right: 1px solid #e2e8f0;
        }
        .meta-grid td:last-child {
            border-right: none;
        }
        .meta-label {
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 2px;
        }
        .meta-value {
            font-size: 9pt;
            font-weight: bold;
            color: #0f172a;
        }
        .tables-row {
            width: 100%;
            margin-bottom: 15px;
        }
        .tables-row td {
            vertical-align: top;
        }
        .stub-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }
        .stub-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 6px 8px;
            text-align: left;
        }
        .stub-table th.num { text-align: right; }
        .stub-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }
        .stub-table td.num {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }
        .stub-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .summary-box {
            width: 100%;
            border: 2px solid #0f172a;
            background-color: #f1f5f9;
            margin-bottom: 18px;
        }
        .summary-box td {
            padding: 8px 12px;
            text-align: center;
            border-right: 1px solid #cbd5e1;
        }
        .summary-box td:last-child {
            border-right: none;
            background-color: #e8f7ed;
        }
        .sum-title {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 3px;
        }
        .sum-amount {
            font-size: 12pt;
            font-weight: 900;
            color: #0f172a;
        }
        .net-amount {
            font-size: 14pt;
            font-weight: 900;
            color: #184f3a;
        }
        .check-stub {
            border-top: 1px dashed #94a3b8;
            padding-top: 15px;
            margin-top: 20px;
        }
        .check-border {
            border: 1px solid #cbd5e1;
            padding: 12px;
            background-color: #fafafa;
        }
        .security-badge {
            font-size: 7.5pt;
            color: #64748b;
            text-align: center;
            margin-top: 12px;
        }
    </style>
</head>
<body>
    @php
        $sym = $stub['currency']['symbol'] ?? '$';
        $fmt = fn($val) => $sym . number_format((float)$val, 2);
    @endphp

    {{-- Header Table --}}
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="vertical-align: top; width: 60%;">
                <div class="company-name">{{ $stub['company']['name'] }}</div>
                <div class="company-meta">
                    {!! nl2br(e($stub['company']['address'])) !!}
                    @if(!empty($stub['company']['ein']))
                        <div style="margin-top: 2px;"><strong>EIN / Tax ID:</strong> {{ $stub['company']['ein'] }}</div>
                    @endif
                </div>
            </td>
            <td style="vertical-align: top; width: 40%; text-align: right;">
                <div class="statement-title">Earnings Statement</div>
                <div class="statement-sub">Pay Frequency: {{ $stub['pay_period']['frequency'] }}</div>
                <div style="font-size: 8pt; color: #64748b; margin-top: 3px;">
                    Pay Date: <strong>{{ $stub['pay_period']['pay_date'] }}</strong>
                </div>
            </td>
        </tr>
    </table>

    {{-- Employee & Pay Period Meta Grid --}}
    <table class="meta-grid" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 28%;">
                <div class="meta-label">Employee Name &amp; Title</div>
                <div class="meta-value">{{ $stub['employee']['name'] }}</div>
                <div style="font-size: 8pt; color: #475569;">{{ $stub['employee']['title'] }}</div>
            </td>
            <td style="width: 20%;">
                <div class="meta-label">Employee ID</div>
                <div class="meta-value">{{ $stub['employee']['id'] ?: 'N/A' }}</div>
                <div class="meta-label" style="margin-top: 4px;">SSN / ID</div>
                <div style="font-size: 8pt; color: #475569;">{{ $stub['employee']['ssn_last4'] ?: '***-**-****' }}</div>
            </td>
            <td style="width: 26%;">
                <div class="meta-label">Pay Period</div>
                <div class="meta-value">{{ $stub['pay_period']['start_date'] }}</div>
                <div style="font-size: 8pt; color: #475569;">to {{ $stub['pay_period']['end_date'] }}</div>
            </td>
            <td style="width: 26%;">
                <div class="meta-label">Payment Method</div>
                <div class="meta-value">{{ $stub['payment']['method'] }}</div>
                <div style="font-size: 8pt; color: #475569;">Account: {{ $stub['payment']['account_last4'] }}</div>
            </td>
        </tr>
    </table>

    {{-- Side by Side Earnings and Deductions Tables --}}
    <table class="tables-row" cellpadding="0" cellspacing="0">
        <tr>
            {{-- Earnings (Left) --}}
            <td style="width: 52%; padding-right: 8px;">
                <div style="font-size: 9pt; font-weight: bold; color: #0f172a; margin-bottom: 5px; text-transform: uppercase;">
                    Earnings
                </div>
                <table class="stub-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="num" style="width: 15%;">Hours</th>
                            <th class="num" style="width: 15%;">Rate</th>
                            <th class="num" style="width: 22%;">Current</th>
                            <th class="num" style="width: 22%;">YTD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stub['earnings'] as $earn)
                            <tr>
                                <td><strong>{{ $earn['description'] }}</strong></td>
                                <td class="num">{{ $earn['hours'] > 0 ? number_format($earn['hours'], 1) : '-' }}</td>
                                <td class="num">{{ $earn['rate'] > 0 ? $fmt($earn['rate']) : '-' }}</td>
                                <td class="num font-bold">{{ $fmt($earn['amount']) }}</td>
                                <td class="num" style="color: #64748b;">{{ $fmt($earn['ytd']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>

            {{-- Deductions (Right) --}}
            <td style="width: 48%; padding-left: 8px;">
                <div style="font-size: 9pt; font-weight: bold; color: #0f172a; margin-bottom: 5px; text-transform: uppercase;">
                    Taxes &amp; Deductions
                </div>
                <table class="stub-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="num" style="width: 28%;">Current</th>
                            <th class="num" style="width: 28%;">YTD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stub['deductions'] as $ded)
                            <tr>
                                <td>{{ $ded['description'] }}</td>
                                <td class="num font-bold">{{ $fmt($ded['amount']) }}</td>
                                <td class="num" style="color: #64748b;">{{ $fmt($ded['ytd']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    {{-- Grand Totals Summary Box --}}
    <table class="summary-box" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 24%;">
                <div class="sum-title">Total Gross Pay</div>
                <div class="sum-amount">{{ $fmt($stub['totals']['gross_pay']) }}</div>
                <div style="font-size: 7.5pt; color: #64748b; margin-top: 2px;">YTD: {{ $fmt($stub['totals']['ytd_gross']) }}</div>
            </td>
            <td style="width: 24%;">
                <div class="sum-title">Total Deductions</div>
                <div class="sum-amount" style="color: #b91c1c;">-{{ $fmt($stub['totals']['deductions']) }}</div>
                <div style="font-size: 7.5pt; color: #64748b; margin-top: 2px;">YTD: {{ $fmt($stub['totals']['ytd_deductions']) }}</div>
            </td>
            <td style="width: 52%;">
                <div class="sum-title" style="color: #184f3a; font-weight: 800;">Net Pay (Take Home)</div>
                <div class="net-amount">{{ $fmt($stub['totals']['net_pay']) }}</div>
                <div style="font-size: 8pt; color: #184f3a; font-weight: bold; margin-top: 2px;">
                    YTD Net Pay: {{ $fmt($stub['totals']['ytd_net_pay']) }}
                </div>
            </td>
        </tr>
    </table>

    {{-- Optional Check Stub Preview Section --}}
    <div class="check-stub">
        <div class="check-border">
            <table style="width: 100%;" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width: 65%;">
                        <div style="font-size: 8pt; color: #64748b; text-transform: uppercase; font-weight: bold;">Pay To The Order Of:</div>
                        <div style="font-size: 11pt; font-weight: bold; color: #0f172a; margin-top: 2px;">{{ $stub['employee']['name'] }}</div>
                        <div style="font-size: 8pt; color: #475569;">{!! nl2br(e($stub['employee']['address'])) !!}</div>
                    </td>
                    <td style="width: 35%; text-align: right; vertical-align: top;">
                        <div style="font-size: 8pt; color: #64748b; text-transform: uppercase; font-weight: bold;">Net Amount Paid</div>
                        <div style="font-size: 13pt; font-weight: 900; color: #184f3a; margin-top: 2px;">{{ $fmt($stub['totals']['net_pay']) }}</div>
                        <div style="font-size: 8pt; color: #475569; margin-top: 3px;">Date: {{ $stub['pay_period']['pay_date'] }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="security-badge">
        This document serves as an authentic earnings and tax statement. Retain for personal tax records. Generated via Formnexa.
    </div>
</body>
</html>
