<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class CertificateController extends Controller
{
    public function index(): View
    {
        $certificates = auth()->user()
            ->certificates()
            ->with('course')
            ->latest('issued_at')
            ->get();

        return view('certificates.index', compact('certificates'));
    }

    public function download(Certificate $certificate): Response
    {
        if ($certificate->user_id !== auth()->id()) {
            abort(403);
        }

        $pdf = Pdf::loadView('certificates.pdf', [
            'certificate' => $certificate,
            'user' => $certificate->user,
            'course' => $certificate->course,
        ])->setPaper('a4', 'landscape');

        $filename = 'WhyFinder-Certificate-' . $certificate->certificate_number . '.pdf';

        return $pdf->download($filename);
    }

    public function verify(string $certificateNumber): View
    {
        $certificate = Certificate::with(['user', 'course'])
            ->where('certificate_number', $certificateNumber)
            ->first();

        return view('certificates.verify', compact('certificate', 'certificateNumber'));
    }
}
