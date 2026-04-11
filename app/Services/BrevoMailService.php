<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrevoMailService
{
    public function sendOtp($to, $otp)
    {
        return Http::withHeaders([
            'api-key' => env('BREVO_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => config('mail.from.name'),
                'email' => config('mail.from.address'),
            ],
            'to' => [
                [
                    'email' => $to,
                ]
            ],
            'subject' => 'Reset Your Password - Smart Learn',
            'htmlContent' => "
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>OTP</title>
</head>

<body style='margin:0;padding:0;background:linear-gradient(135deg,#4f46e5,#7c3aed);font-family:Arial,sans-serif;'>

<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td align='center'>

<table width='520' cellpadding='0' cellspacing='0' style='background:#ffffff;margin:40px auto;border-radius:16px;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,0.2);'>

<!-- Header -->
<tr>
<td style='background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:25px;text-align:center;color:#fff;'>

<h1 style='margin:0;font-size:22px;'>Smart Learn</h1>
<p style='margin:5px 0 0;font-size:13px;opacity:0.9;'>Secure Learning Platform</p>

</td>
</tr>

<!-- Body -->
<tr>
<td style='padding:40px;text-align:center;'>

<h2 style='margin:0;color:#111;'>Reset Your Password</h2>

<p style='color:#555;font-size:15px;margin-top:15px;line-height:1.6;'>
We received a request to reset your password.<br>
Use the OTP below to continue.
</p>

<!-- OTP Box -->
<div style='margin:35px 0;'>

<span style='display:inline-block;
background:linear-gradient(135deg,#eef2ff,#e0e7ff);
padding:18px 40px;
font-size:32px;
font-weight:bold;
letter-spacing:10px;
border-radius:12px;
color:#1e1b4b;
box-shadow:0 5px 15px rgba(0,0,0,0.1);'>
{$otp}
</span>

</div>

<p style='color:#888;font-size:13px;'>
This code will expire in <b>10 minutes</b>.
</p>

<!-- Divider -->
<hr style='margin:30px 0;border:none;border-top:1px solid #eee;'>

<p style='font-size:12px;color:#999;line-height:1.6;'>
If you didn’t request this, you can safely ignore this email.<br>
Your account security is our priority.
</p>

</td>
</tr>

<!-- Footer -->
<tr>
<td style='background:#f9fafb;padding:20px;text-align:center;font-size:12px;color:#999;'>

© " . date('Y') . " Smart Learn. All rights reserved.

</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>
"
        ]);
    }
}