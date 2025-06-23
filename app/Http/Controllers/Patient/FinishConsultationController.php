<?php

namespace App\Http\Controllers\Patient;

use App\Enums\StatusKonsultasi;
use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FinishConsultationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Consultation $consultation)
    {
        // Pastikan konsultasi milik pasien yang login
        if ($consultation->patient->user->id !== Auth::id()) {
            return response()->json([
                'message' => 'Konsultasi ini tidak milik Anda.'
            ], Response::HTTP_FORBIDDEN);
        }

        // Pastikan status konsultasi sedang berlangsung
        if ($consultation->status !== StatusKonsultasi::BERLANGSUNG) {
            return response()->json([
                'message' => 'Konsultasi hanya bisa diselesaikan jika sedang berlangsung.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Ubah status menjadi selesai
        $consultation->update([
            'status' => StatusKonsultasi::SELESAI,
        ]);

        $consultation->messages()->delete();

        return response()->json([
            'message' => 'Konsultasi berhasil diselesaikan.',
        ]);
    }
}
