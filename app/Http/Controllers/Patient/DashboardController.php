<?php

namespace App\Http\Controllers\Patient;

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
        $user = Auth::user()->patient;

        // Tambahkan data lain jika perlu, misalnya jumlah konsultasi, review, dll
        $summary = [
            'total_konsultasi' => $user->consultation()->count(), // Asumsi relasi
        ];

        return view('pasien.dashboard', compact('user', 'summary'));
    }
}
