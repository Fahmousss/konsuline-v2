<?php

namespace App\Http\Controllers\Doctor;

use App\Enums\StatusKonsultasi;
use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StartConsultationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Consultation $consultation)
    {
        // Pastikan hanya dokter terkait yang bisa mengubah status
        if ($consultation->doctor_id !== Auth::user()->doctor->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk memulai konsultasi ini.'
            ], 403);
        }

        // Pastikan status saat ini adalah 'menunggu_verifikasi'
        if ($consultation->status !== StatusKonsultasi::VERIFIKASI) {
            return response()->json([
                'success' => false,
                'message' => 'Konsultasi tidak dalam status menunggu verifikasi.'
            ], 422);
        }

        // Update status menjadi 'berlangsung'
        $consultation->update([
            'status' => StatusKonsultasi::BERLANGSUNG,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Konsultasi dimulai.',
            'status' => $consultation->status->value,
        ]);
    }
}
