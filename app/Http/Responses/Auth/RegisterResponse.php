<?php

namespace App\Http\Responses\Auth;

use Illuminate\Contracts\Support\Responsable;

class RegisterResponse implements Responsable
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
            'message' => 'Registered successfully',
            'data' => [
                'user' => $this->user,
                'token' => $this->token,
            ],
        ], 201);
    }
}
