<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\PasswordOtp;
use App\Models\user;
use App\Http\Requests\Auth\ResetPasswordRequest;
class ResetPasswordController extends Controller
{
    public function reset(ResetPasswordRequest $request)
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

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
        ]);
    }
}