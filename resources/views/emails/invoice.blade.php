<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 24px;
            color: #1e293b;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #0f172a;
            color: #ffffff;
            padding: 32px;
            text-align: left;
        }
        .header h1 {
            margin: 0 0 6px;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #2dd4bf;
        }
        .header p {
            margin: 0;
            color: #94a3b8;
            font-size: 14px;
        }
        .body {
            padding: 32px;
        }
        .message-box {
            background-color: #f8fafc;
            border-left: 4px solid #0d9488;
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 4px;
            font-size: 15px;
            line-height: 1.6;
            color: #334155;
            white-space: pre-line;
        }
        .summary-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
            color: #64748b;
        }
        .summary-row:last-child {
            margin-bottom: 0;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            color: #0f172a;
            font-weight: 700;
            font-size: 16px;
        }
        .btn {
            display: inline-block;
            background-color: #0d9488;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
        }
        .btn:hover {
            background-color: #0f766e;
        }
        .footer {
            padding: 24px 32px;
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $invoice->from_name ?: config('app.name', 'Formnexa') }}</h1>
            <p>Invoice #{{ $invoice->invoice_number }}</p>
        </div>
        
        <div class="body">
            @if(!empty($customMessage))
                <div class="message-box">
                    {{ $customMessage }}
                </div>
            @else
                <p style="font-size: 15px; color: #334155; line-height: 1.6; margin-top: 0;">
                    Hello {{ $invoice->to_name ?: 'Valued Customer' }},<br><br>
                    Please find attached your invoice <strong>#{{ $invoice->invoice_number }}</strong> for 
                    <strong>{{ $invoice->currency_symbol }}{{ number_format($invoice->balance_due, 2) }}</strong>.
                </p>
            @endif

            <div class="summary-card">
                <div class="summary-row">
                    <span>Invoice Number</span>
                    <span>#{{ $invoice->invoice_number }}</span>
                </div>
                <div class="summary-row">
                    <span>Issue Date</span>
                    <span>{{ $invoice->issue_date ? $invoice->issue_date->format('M d, Y') : now()->format('M d, Y') }}</span>
                </div>
                @if($invoice->due_date)
                <div class="summary-row">
                    <span>Due Date</span>
                    <span>{{ $invoice->due_date->format('M d, Y') }}</span>
                </div>
                @endif
                <div class="summary-row">
                    <span>Amount Due</span>
                    <span>{{ $invoice->currency_symbol }}{{ number_format($invoice->balance_due, 2) }} {{ $invoice->currency }}</span>
                </div>
            </div>

            <div style="text-align: center; margin: 32px 0 16px;">
                <a href="{{ route('invoice.show', $invoice->uuid) }}" class="btn">View & Download Online</a>
            </div>
            
            <p style="font-size: 13px; color: #64748b; text-align: center; margin: 0;">
                A PDF copy is also attached to this email for your records.
            </p>
        </div>

        <div class="footer">
            Sent securely via <strong>Formnexa</strong> &bull; Smart Documents. Smarter Business.
        </div>
    </div>
</body>
</html>
