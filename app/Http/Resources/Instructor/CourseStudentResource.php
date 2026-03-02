<?php

namespace App\Http\Resources\Instructor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseStudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'university_id' => $this->university_id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->pivot->status,
            'requested_at' => $this->pivot->created_at,

            'progress_percentage' => $this->progress_percentage ?? 0,
            'completed_lectures' => $this->completed_lectures ?? 0,
            'total_lectures' => $this->total_lectures ?? 0,
        ];
    }
}