<?php

namespace App\Http\Controllers\Patient;

use App\Enums\StatusKonsultasi;
use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Consultation $consultation)
    {
        // Validasi status harus selesai
        if ($consultation->status !== StatusKonsultasi::SELESAI) {
            return response()->json(['message' => 'Konsultasi belum selesai.'], 422);
        }

        // Validasi input
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $consultation->doctor->review()->updateOrCreate([
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);
        return response()->json(['message' => 'Ulasan berhasil disimpan.']);
    }
}
