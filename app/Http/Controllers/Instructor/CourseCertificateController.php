<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Course;
use App\Models\CourseCertificate;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\StoreCertificateRequest;
use App\Http\Responses\Certificate\CertificateResponse;

class CourseCertificateController extends Controller
{
    public function store(StoreCertificateRequest $request, $courseId)
    {
        $course = Course::find($courseId);

        if (!$course || $course->instructor_id !== $request->user()->id) {
            return CertificateResponse::error('Unauthorized.',403);
        }

        $data = $request->validated();

        if ($request->hasFile('template')) {

            $data['template'] = $request
                ->file('template')
                ->store('certificates','public');
        }

        $data['course_id'] = $course->id;

        $certificate = CourseCertificate::updateOrCreate(
            ['course_id' => $course->id],
            $data
        );

        return CertificateResponse::success(
            $certificate,
            'Certificate template saved successfully.',
            201
        );
    }
}