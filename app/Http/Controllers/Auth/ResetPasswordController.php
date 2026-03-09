<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\PasswordOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function reset(ResetPasswordRequest $request)
    {

        $record = PasswordOtp::where('email', $request->email)
            ->where('verified', true)
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'OTP not verified.',
            ], 403);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

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