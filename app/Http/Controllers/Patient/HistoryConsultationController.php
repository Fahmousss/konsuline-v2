<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class HistoryConsultationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $consultation = Consultation::with(['doctor.review', 'doctor.user', 'doctor.specialty', 'payment'])
            ->latest()
            ->get();

        return view('pasien.konsultasi.riwayat', compact('consultation'));
    }
}
