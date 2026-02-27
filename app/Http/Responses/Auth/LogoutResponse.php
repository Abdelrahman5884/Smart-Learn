<?php

namespace App\Http\Responses\Auth;

use Illuminate\Contracts\Support\Responsable;

class LogoutResponse implements Responsable
{
    public function toResponse($request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ], 200);
    }
}
