<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $emailSubject }}</title>
</head>

<body style="margin:0; padding:0; background-color:#f8f7f4; font-family: 'Segoe UI', Tahoma, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8f7f4; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border:1px solid #e7e4dc; border-radius:12px; overflow:hidden;">
                    <tr>
                        <td style="background-color:#1b1b18; padding:28px 32px;" align="right">
                            <span style="display:inline-block; width:40px; height:40px; line-height:40px; text-align:center; background-color:#f3e9cd; color:#1b1b18; font-weight:700; font-size:16px; border-radius:8px;">
                                {{ mb_substr(setting('site_name', 'ش'), 0, 1) }}
                            </span>
                            <div style="margin-top:12px; color:#f3e9cd; font-size:16px; font-weight:600;">
                                {{ setting('site_name', config('app.name')) }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;" align="right">
                            <p style="margin:0 0 4px; color:#6e6c66; font-size:13px;">مرحبًا {{ $toName }}،</p>
                            <h1 style="margin:0 0 20px; color:#1b1b18; font-size:20px; font-weight:700;">{{ $emailSubject }}</h1>
                            <div style="color:#1b1b18; font-size:15px; line-height:1.9; white-space:pre-line;">{{ $body }}</div>

                            <div style="margin-top:32px; padding-top:20px; border-top:1px solid #e7e4dc; color:#6e6c66; font-size:13px;">
                                {{ $sender->name }}<br>
                                {{ setting('site_name', config('app.name')) }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
