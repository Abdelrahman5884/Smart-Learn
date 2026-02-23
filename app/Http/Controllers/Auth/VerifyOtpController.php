<?php

namespace App\Http\Controllers\Auth;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PasswordOtp;

class VerifyOtpController extends Controller
{
    public function verify(VerifyOtpRequest $request)
    {
        $record = PasswordOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record || now()->gt($record->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully.',
        ]);
    }
}
