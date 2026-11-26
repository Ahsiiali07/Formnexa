<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head>
<meta charset="utf-8">
<title>Resignation Letter - {{ $letter['employee_name'] }}</title>
<!--[if gte mso 9]>
<xml>
 <w:WordDocument>
  <w:View>Print</w:View>
  <w:Zoom>100</w:Zoom>
  <w:DoNotOptimizeForBrowser/>
 </w:WordDocument>
</xml>
<![endif]-->
<style>
body {
    font-family: Calibri, Arial, sans-serif;
    font-size: 11pt;
    line-height: 1.5;
    color: #1a1a1a;
}
p { margin-bottom: 12pt; }
.header { margin-bottom: 24pt; }
.sender-name { font-size: 16pt; font-weight: bold; color: #0f172a; margin-bottom: 2pt; }
</style>
</head>
<body>
<div class="header">
    <div class="sender-name">{{ $letter['employee_name'] }}</div>
    @if(!empty($letter['employee_title']))<div><strong>{{ $letter['employee_title'] }}</strong></div>@endif
    @if(!empty($letter['employee_address']))<div>{{ $letter['employee_address'] }}</div>@endif
    @if(!empty($letter['employee_email']))<div>Email: {{ $letter['employee_email'] }}</div>@endif
    @if(!empty($letter['employee_phone']))<div>Phone: {{ $letter['employee_phone'] }}</div>@endif
</div>

<p><strong>{{ $letter['date'] }}</strong></p>

<p>
<strong>{{ $letter['manager_name'] }}</strong><br>
@if(!empty($letter['manager_title'])){{ $letter['manager_title'] }}<br>@endif
<strong>{{ $letter['company_name'] }}</strong><br>
@if(!empty($letter['company_address'])){{ $letter['company_address'] }}<br>@endif
</p>

<p><strong>Dear {{ $letter['manager_name'] }},</strong></p>

{!! nl2br(e($letter['letter_body'])) !!}

<p style="margin-top: 24pt;">
Sincerely,<br><br><br>
<strong>{{ $letter['employee_name'] }}</strong><br>
@if(!empty($letter['employee_title']))<span>{{ $letter['employee_title'] }}</span>@endif
</p>
</body>
</html>
