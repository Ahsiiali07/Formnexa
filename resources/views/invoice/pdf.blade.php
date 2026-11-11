<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $invoice->invoice_title }} - {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 24px 28px;
            size: a4 portrait;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10.5px;
            color: #0f172a;
            line-height: 1.4;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }
        .cover-container {
            width: 100%;
            height: 110px;
            overflow: hidden;
            margin-bottom: 18px;
            border-radius: 8px;
        }
        .cover-img {
            width: 100%;
            height: 110px;
            object-fit: cover;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: top;
        }
        .logo {
            max-width: 140px;
            max-height: 60px;
        }
        .brand-name {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 3px 0;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: 800;
            color: {{ $invoice->accent_color ?: '#0d9488' }};
            text-align: right;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .invoice-meta {
            text-align: right;
            font-size: 10.5px;
            color: #475569;
        }
        .invoice-meta strong {
            color: #0f172a;
        }

        .meta-boxes {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .meta-boxes td {
            padding: 9px 12px;
            font-size: 10px;
            border-right: 1px solid #e2e8f0;
        }
        .meta-boxes td:last-child {
            border-right: none;
        }
        .meta-label {
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .meta-value {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
        }

        .parties-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .parties-table td {
            width: 50%;
            vertical-align: top;
            padding: 0 8px 0 0;
        }
        .parties-table td:last-child {
            padding: 0 0 0 8px;
        }
        .party-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            min-height: 85px;
        }
        .party-box h4 {
            margin: 0 0 5px 0;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            color: {{ $invoice->accent_color ?: '#0d9488' }};
            letter-spacing: 0.5px;
        }
        .party-box .name {
            font-size: 12.5px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .party-box .details {
            font-size: 9.5px;
            color: #475569;
            line-height: 1.4;
        }

        /* Items table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .items-table th {
            border-bottom: 2px solid #0f172a;
            color: #0f172a;
            font-size: 9.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 6px;
            text-align: left;
        }
        .items-table th.text-right {
            text-align: right;
        }
        .items-table th.text-center {
            text-align: center;
        }
        .items-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 10px;
            color: #334155;
        }
        .items-table tr:nth-child(even) td {
            background-color: #fafbfc;
        }
        .items-table td.item-desc {
            font-weight: 600;
            color: #0f172a;
        }
        .items-table td.text-right {
            text-align: right;
        }
        .items-table td.text-center {
            text-align: center;
        }

        /* Bottom section */
        .bottom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .bottom-table td {
            vertical-align: top;
        }
        .notes-column {
            width: 58%;
            padding-right: 20px;
        }
        .totals-column {
            width: 42%;
        }

        .section-box {
            margin-bottom: 12px;
        }
        .section-box-title {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }
        .section-box-content {
            font-size: 9.5px;
            color: #475569;
            line-height: 1.4;
            white-space: pre-line;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            border-radius: 6px;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .totals-table td {
            padding: 6px 12px;
            font-size: 10px;
            color: #475569;
        }
        .totals-table td.text-right {
            text-align: right;
            color: #0f172a;
            font-weight: 700;
        }
        .totals-table tr.total-row td {
            border-top: 1px solid #cbd5e1;
            font-size: 11.5px;
            font-weight: 800;
            color: #0f172a;
            padding-top: 8px;
            padding-bottom: 8px;
        }
        .totals-table tr.balance-row td {
            border-top: 2px solid {{ $invoice->accent_color ?: '#0d9488' }};
            background-color: #f0fdfa;
            font-size: 12.5px;
            font-weight: 800;
            color: {{ $invoice->accent_color ?: '#0d9488' }};
            padding: 8px 12px;
        }

        .signature-img {
            max-width: 140px;
            max-height: 45px;
            margin-top: 4px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8.5px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
        }

        /* Template Layout Style Variants */
        @php
            $style = $invoice->template_style ?: 'modern';
            $accent = $invoice->accent_color ?: '#0d9488';
        @endphp

        @if($style === 'classic')
            body { border-top: 5px solid {{ $accent }}; padding-top: 14px; }
            .meta-boxes, .party-box, .totals-table, .section-box-content { border-radius: 0; border-color: #94a3b8; background-color: #ffffff; }
            .header-table { border-bottom: 2px solid #0f172a; padding-bottom: 12px; }
            .items-table { border: 1px solid #94a3b8; }
            .items-table th { background-color: #f1f5f9; border-bottom: 2px solid #0f172a; border-right: 1px solid #cbd5e1; }
            .items-table td { border: 1px solid #cbd5e1; }
            .items-table tr:nth-child(even) td { background-color: #ffffff; }
        @elseif($style === 'bold')
            .bold-header { background-color: {{ $accent }}; color: #ffffff; padding: 20px 24px; margin: -24px -28px 20px -28px; }
            .bold-header .brand-name { color: #ffffff; }
            .bold-header .invoice-title { color: #ffffff; }
            .bold-header .invoice-meta, .bold-header .invoice-meta strong { color: #ffffff; }
            .items-table th { background-color: {{ $accent }}; color: #ffffff; border-bottom: none; padding: 9px 8px; }
        @elseif($style === 'minimal')
            .meta-boxes, .party-box, .totals-table, .section-box-content { background-color: transparent; border: none; border-radius: 0; }
            .meta-boxes { border-top: 1px solid #cbd5e1; border-bottom: 1px solid #cbd5e1; }
            .meta-boxes td { border-right: none; padding: 8px 6px; }
            .party-box { padding: 4px 0; min-height: auto; }
            .items-table th { border-bottom: 1px solid #0f172a; }
            .items-table tr:nth-child(even) td { background-color: transparent; }
            .totals-table tr.balance-row td { background-color: transparent; }
        @elseif($style === 'stripe')
            .stripe-wrapper { border-left: 7px solid {{ $accent }}; padding-left: 16px; }
            .items-table th { border-bottom: 2px solid {{ $accent }}; color: {{ $accent }}; }
        @elseif($style === 'compact')
            .meta-boxes td { padding: 6px 8px; }
            .party-box { padding: 8px 10px; min-height: 60px; }
            .items-table th { padding: 6px 5px; border-bottom: 2px solid {{ $accent }}; }
            .items-table td { padding: 5px 5px; font-size: 9.5px; }
        @endif
    </style>
</head>
<body>
    <div class="{{ $style === 'stripe' ? 'stripe-wrapper' : '' }}">
    @if(!empty($invoice->cover_image))
    <div class="cover-container">
        <img src="{{ $invoice->cover_image }}" class="cover-img" alt="Cover Header">
    </div>
    @endif

    <!-- Header -->
    @if($style === 'bold' && empty($invoice->cover_image))
    <div class="bold-header">
        <table class="header-table" style="margin-bottom: 0;">
            <tr>
                <td style="width: 55%;">
                    @if(!empty($invoice->logo_data))
                        <img src="{{ $invoice->logo_data }}" class="logo" style="background: #ffffff; padding: 4px; border-radius: 6px; margin-bottom: 4px;" alt="Logo" /><br>
                    @endif
                    <h1 class="brand-name">{{ $invoice->from_name ?: config('app.name', 'Formnexa') }}</h1>
                    <div style="color: #e2e8f0; font-size: 9.5px; line-height: 1.35;">
                        @if($invoice->from_email) {{ $invoice->from_email }} @endif
                        @if($invoice->from_phone) &bull; {{ $invoice->from_phone }} @endif
                        @if($invoice->from_tax_id) <br>Tax ID: {{ $invoice->from_tax_id }} @endif
                    </div>
                </td>
                <td style="width: 45%;">
                    <h2 class="invoice-title">{{ $invoice->invoice_title ?: 'INVOICE' }}</h2>
                    <div class="invoice-meta">
                        <div><strong># {{ $invoice->invoice_number }}</strong></div>
                        @if($invoice->status)
                            <div style="margin-top: 3px; text-transform: uppercase; font-size: 8.5px; font-weight: bold; opacity: 0.9;">
                                Status: {{ $invoice->status }}
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>
    @else
    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                @if(!empty($invoice->logo_data))
                    <img src="{{ $invoice->logo_data }}" class="logo" alt="Logo" /><br>
                @endif
                <h1 class="brand-name">{{ $invoice->from_name ?: config('app.name', 'Formnexa') }}</h1>
                <div style="color: #64748b; font-size: 9.5px; line-height: 1.35;">
                    @if($invoice->from_email) {{ $invoice->from_email }} @endif
                    @if($invoice->from_phone) &bull; {{ $invoice->from_phone }} @endif
                    @if($invoice->from_tax_id) <br>Tax ID: {{ $invoice->from_tax_id }} @endif
                </div>
            </td>
            <td style="width: 45%;">
                <h2 class="invoice-title">{{ $invoice->invoice_title ?: 'INVOICE' }}</h2>
                <div class="invoice-meta">
                    <div><strong># {{ $invoice->invoice_number }}</strong></div>
                    @if($invoice->status)
                        <div style="margin-top: 3px; text-transform: uppercase; font-size: 8.5px; font-weight: bold; color: {{ $invoice->status === 'paid' ? '#059669' : $accent }};">
                            Status: {{ $invoice->status }}
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    </table>
    @endif

    <!-- Quick Meta Bar -->
    <table class="meta-boxes">
        <tr>
            <td>
                <div class="meta-label">Invoice Date</div>
                <div class="meta-value">{{ $invoice->issue_date ? $invoice->issue_date->format('M d, Y') : now()->format('M d, Y') }}</div>
            </td>
            <td>
                <div class="meta-label">Payment Terms</div>
                <div class="meta-value">{{ $invoice->payment_terms ?: 'Due on receipt' }}</div>
            </td>
            <td>
                <div class="meta-label">Due Date</div>
                <div class="meta-value">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '—' }}</div>
            </td>
            <td>
                <div class="meta-label">Balance Due</div>
                <div class="meta-value" style="color: {{ $invoice->accent_color ?: '#0d9488' }};">
                    {{ $invoice->currency_symbol }}{{ number_format($invoice->balance_due, 2) }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Bill From & Bill To Cards -->
    <table class="parties-table">
        <tr>
            <td>
                <div class="party-box">
                    <h4>Billed From</h4>
                    <div class="name">{{ $invoice->from_name ?: 'Business Name' }}</div>
                    <div class="details">
                        @if($invoice->from_address) {!! nl2br(e($invoice->from_address)) !!}<br> @endif
                        @if($invoice->from_email) {{ $invoice->from_email }}<br> @endif
                        @if($invoice->from_phone) {{ $invoice->from_phone }} @endif
                    </div>
                </div>
            </td>
            <td>
                <div class="party-box">
                    <h4>Billed To (Client)</h4>
                    <div class="name">{{ $invoice->to_name ?: 'Client / Customer Name' }}</div>
                    <div class="details">
                        @if($invoice->to_address) {!! nl2br(e($invoice->to_address)) !!}<br> @endif
                        @if($invoice->to_email) {{ $invoice->to_email }}<br> @endif
                        @if($invoice->to_phone) {{ $invoice->to_phone }} @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Itemized List Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 53%;">Description</th>
                <th class="text-center" style="width: 12%;">Qty</th>
                <th class="text-right" style="width: 15%;">Rate ({{ $invoice->currency_symbol }})</th>
                <th class="text-right" style="width: 15%;">Amount ({{ $invoice->currency_symbol }})</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice->items as $index => $item)
            <tr>
                <td style="color: #94a3b8;">{{ $index + 1 }}</td>
                <td class="item-desc">{!! nl2br(e($item->description ?: 'Line item')) !!}</td>
                <td class="text-center">{{ number_format($item->quantity, 2) + 0 }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right" style="font-weight: 700;">{{ number_format($item->amount, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #94a3b8; padding: 14px;">No items specified</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Bottom: Notes/Terms & Summary Totals -->
    <table class="bottom-table">
        <tr>
            <td class="notes-column">
                @if(!empty($invoice->payment_link))
                <div class="section-box">
                    <div class="section-box-title">Pay Online</div>
                    <div class="section-box-content">
                        Click here to pay online: {{ $invoice->payment_link }}
                    </div>
                </div>
                @endif

                @if(!empty($invoice->notes))
                <div class="section-box">
                    <div class="section-box-title">Payment Instructions / Notes</div>
                    <div class="section-box-content">{{ $invoice->notes }}</div>
                </div>
                @endif

                @if(!empty($invoice->terms))
                <div class="section-box">
                    <div class="section-box-title">Terms & Conditions</div>
                    <div class="section-box-content">{{ $invoice->terms }}</div>
                </div>
                @endif

                @if(!empty($invoice->signature_data))
                <div class="section-box" style="margin-top: 14px;">
                    <div class="section-box-title">Authorized Signature</div>
                    <img src="{{ $invoice->signature_data }}" class="signature-img" alt="Signature">
                </div>
                @endif
            </td>
            <td class="totals-column">
                <table class="totals-table">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right">{{ $invoice->currency_symbol }}{{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    @if($invoice->discount_amount > 0)
                    <tr>
                        <td>
                            Discount 
                            @if($invoice->discount_type === 'percent') ({{ $invoice->discount_rate + 0 }}%) @endif
                        </td>
                        <td class="text-right" style="color: #dc2626;">- {{ $invoice->currency_symbol }}{{ number_format($invoice->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($invoice->tax_amount > 0)
                    <tr>
                        <td>Tax / VAT ({{ $invoice->tax_rate + 0 }}%)</td>
                        <td class="text-right">+ {{ $invoice->currency_symbol }}{{ number_format($invoice->tax_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($invoice->shipping_fee > 0)
                    <tr>
                        <td>Shipping</td>
                        <td class="text-right">+ {{ $invoice->currency_symbol }}{{ number_format($invoice->shipping_fee, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td>Total</td>
                        <td class="text-right">{{ $invoice->currency_symbol }}{{ number_format($invoice->total, 2) }}</td>
                    </tr>
                    @if($invoice->amount_paid > 0)
                    <tr>
                        <td>Amount Paid</td>
                        <td class="text-right" style="color: #059669;">- {{ $invoice->currency_symbol }}{{ number_format($invoice->amount_paid, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="balance-row">
                        <td>Balance Due</td>
                        <td class="text-right">{{ $invoice->currency_symbol }}{{ number_format($invoice->balance_due, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    </div>

    <div class="footer">
        Thank you for your business! Generated with {{ config('app.name', 'Formnexa') }}
    </div>
</body>
</html>
