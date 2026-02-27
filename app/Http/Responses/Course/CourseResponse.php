<?php

namespace App\Http\Responses\Course;

use Illuminate\Contracts\Support\Responsable;

class CourseResponse implements Responsable
{
    protected $data;

    protected $message;

    protected $status;

    public function __construct($data, $message = null, $status = 200)
    {
        $this->data = $data;
        $this->message = $message;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'success' => true,
            'message' => $this->message,
            'data' => $this->data,
        ], $this->status);
    }
}
