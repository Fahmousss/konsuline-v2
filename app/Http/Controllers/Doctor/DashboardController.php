<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $dokter = Auth::user()->doctor; // Asumsi user punya relasi `dokter`

        $summary = [
            'total_konsultasi' => $dokter->consultations->count(),
            'total_review'     => $dokter->reviews->count(),
            'total_pendapatan' => $dokter->consultations()
                ->whereHas('payment') // hanya konsultasi yang memiliki pembayaran
                ->with('payment')     // pastikan eager load agar efisien (opsional)
                ->get()
                ->sum(fn($c) => $c->payment->jumlah ?? 0),
        ];

        return view('dokter.dashboard', compact('dokter', 'summary'));
    }
}
