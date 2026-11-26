<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $heading }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f8f8f3;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#17211b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8f8f3;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background-color:#ffffff;border:1px solid #dfe4df;border-radius:16px;overflow:hidden;">
                    <tr>
                        <td style="background-color:#184f3a;padding:24px 28px;color:#ffffff;">
                            <div style="font-size:20px;font-weight:800;letter-spacing:-0.03em;">formnexa</div>
                            <div style="font-size:12px;color:#c8f3dc;margin-top:4px;">Account Security &amp; Verification</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <h1 style="margin:0 0 12px;font-size:22px;font-weight:700;color:#17211b;letter-spacing:-0.02em;">{{ $heading }}</h1>
                            <p style="margin:0 0 14px;font-size:14px;line-height:1.6;color:#3f4a43;">
                                Hi {{ $recipientName }},
                            </p>
                            <p style="margin:0 0 22px;font-size:14px;line-height:1.6;color:#3f4a43;">
                                {{ $intro }}
                            </p>

                            <div style="background-color:#f8f8f3;border:1px solid #c6d1ca;border-radius:12px;padding:20px;text-align:center;margin-bottom:22px;">
                                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:#536058;margin-bottom:8px;">
                                    Your 6-Digit Verification Code
                                </div>
                                <div style="font-family:'SFMono-Regular',Consolas,'Liberation Mono',Menlo,monospace;font-size:34px;font-weight:800;letter-spacing:0.28em;color:#184f3a;">
                                    {{ $code }}
                                </div>
                                <div style="font-size:12px;color:#536058;margin-top:10px;">
                                    Expires in <strong>{{ $expiresMinutes }} minutes</strong>
                                </div>
                            </div>

                            <p style="margin:0;font-size:13px;line-height:1.6;color:#536058;">
                                {{ $actionNote }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f8f8f3;border-top:1px solid #dfe4df;padding:16px 28px;font-size:11px;color:#68736d;text-align:center;">
                            &copy; {{ date('Y') }} Formnexa &middot; Automated security message
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
