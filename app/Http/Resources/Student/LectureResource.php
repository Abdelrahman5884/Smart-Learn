<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class LectureResource extends JsonResource
{
    public function toArray($request)
    {
        return [

            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'video' => [
                'url' =>
                    $this->video_url ??
                    asset('storage/' . $this->video_path),
                'duration' => $this->video_duration
            ],
            'attachment' =>
                $this->attachment
                    ? asset('storage/' . $this->attachment)
                    : null,

            'instructor' => [
                'id' =>
                    $this->course->instructor->id,
                'name' =>
                    $this->course->instructor->name
            ],

            'student_progress' => [
                'is_completed' =>
                    $this->is_completed ?? false,
                'completed_at' =>
                    $this->completed_at
            ],
            'notes' => $this->notes
        ];
    }
}