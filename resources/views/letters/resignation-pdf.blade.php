<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Resignation Letter - {{ $letter['employee_name'] }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 25mm 20mm 25mm 20mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }
        .sender-block {
            margin-bottom: 24pt;
        }
        .sender-name {
            font-size: 16pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 4pt;
        }
        .sender-meta {
            font-size: 10pt;
            color: #475569;
            line-height: 1.4;
        }
        .date-line {
            font-size: 10.5pt;
            font-weight: 600;
            color: #334155;
            margin-bottom: 20pt;
        }
        .recipient-block {
            font-size: 10.5pt;
            color: #1e293b;
            line-height: 1.4;
            margin-bottom: 24pt;
        }
        .salutation {
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 14pt;
        }
        .letter-content {
            font-size: 11pt;
            color: #1a1a1a;
            line-height: 1.65;
            margin-bottom: 28pt;
        }
        .letter-content p {
            margin: 0 0 12pt 0;
        }
        .closing {
            font-size: 11pt;
            margin-bottom: 36pt;
        }
        .signature-name {
            font-size: 12pt;
            font-weight: bold;
            color: #0f172a;
        }
        .signature-title {
            font-size: 10pt;
            color: #64748b;
        }
        .accent-bar {
            height: 3px;
            background-color: #184f3a;
            width: 48px;
            margin: 8pt 0 16pt 0;
        }
    </style>
</head>
<body>
    <div class="sender-block">
        <div class="sender-name">{{ $letter['employee_name'] }}</div>
        @if(!empty($letter['employee_title']))
            <div style="font-size: 11pt; color: #184f3a; font-weight: 600; margin-bottom: 4pt;">{{ $letter['employee_title'] }}</div>
        @endif
        <div class="accent-bar"></div>
        <div class="sender-meta">
            @if(!empty($letter['employee_address']))<div>{{ $letter['employee_address'] }}</div>@endif
            @if(!empty($letter['employee_email']))<div>Email: {{ $letter['employee_email'] }}</div>@endif
            @if(!empty($letter['employee_phone']))<div>Phone: {{ $letter['employee_phone'] }}</div>@endif
        </div>
    </div>

    <div class="date-line">
        {{ $letter['date'] }}
    </div>

    <div class="recipient-block">
        <div style="font-weight: bold;">{{ $letter['manager_name'] }}</div>
        @if(!empty($letter['manager_title']))<div>{{ $letter['manager_title'] }}</div>@endif
        <div style="font-weight: 600;">{{ $letter['company_name'] }}</div>
        @if(!empty($letter['company_address']))<div>{{ $letter['company_address'] }}</div>@endif
    </div>

    <div class="salutation">
        Dear {{ $letter['manager_name'] }},
    </div>

    <div class="letter-content">
        {!! nl2br(e($letter['letter_body'])) !!}
    </div>

    <div class="closing">
        Sincerely,
        <div style="height: 28pt;"></div>
        <div class="signature-name">{{ $letter['employee_name'] }}</div>
        @if(!empty($letter['employee_title']))
            <div class="signature-title">{{ $letter['employee_title'] }}</div>
        @endif
    </div>
</body>
</html>
