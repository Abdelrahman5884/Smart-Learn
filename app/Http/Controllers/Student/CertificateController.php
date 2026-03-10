<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentCertificate;
use Illuminate\Support\Str;

class CertificateController extends Controller
{

    public function myCertificates()
    {

        $certificates = StudentCertificate::with('course')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $certificates
        ]);
    }

    public function issue($courseId)
    {

        $exists = StudentCertificate::where('user_id', auth()->id())
            ->where('course_id', $courseId)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Certificate already issued'
            ]);
        }

        $certificate = StudentCertificate::create([
            'user_id' => auth()->id(),
            'course_id' => $courseId,
            'certificate_number' => 'CRT-' . strtoupper(Str::random(8)),
            'issued_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $certificate
        ]);
    }
}