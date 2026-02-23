<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ForgetPasswordRequest;use App\Models\User;
use App\Models\PasswordOtp;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;

class ForgetPasswordController extends Controller
{
    public function sendOtp(ForgetPasswordRequest $request)
    {
        $otp = rand(100000, 999999);

        PasswordOtp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
            ]
        );

      Mail::to($request->email)->send(new SendOtpMail($otp));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.',
        ]);
    }
}