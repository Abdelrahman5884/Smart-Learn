<?php

namespace App\Http\Responses\Auth;

use Illuminate\Contracts\Support\Responsable;

class LoginResponse implements Responsable
{
    protected $user;
    protected $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function toResponse($request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $this->user,
                'token' => $this->token
            ]
        ], 200);
    }
}