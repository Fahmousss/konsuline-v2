<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $totalPasien = Patient::count();
        $totalDokter = Doctor::count();

        // Hitung permintaan konsultasi
        // $permintaanKonsultasi = Pembayaran::where('status', 'menunggu verifikasi')->count();
        $permintaanKonsultasi = 10;
        // Hitung pendaftaran pasien baru
        $pasienBaruHariIni = Patient::whereDate('created_at', now()->toDateString())->count();
        // $konsultasiBaru = Pembayaran::where('status', 'menunggu verifikasi')->count();
        $konsultasiBaru = 10;
        //
        $aktivitasTerbaru = [
            [
                'ikon' => 'fa-user-edit',
                'pesan' => 'Anda memperbarui data pasien <strong>#1001</strong>',
                'waktu' => '30 menit lalu',
            ],
            [
                'ikon' => 'fa-calendar-alt',
                'pesan' => 'Jadwal <strong>dr. Budi</strong> diperbarui',
                'waktu' => '1 jam lalu',
            ]
        ];

        return view('admin.dashboard', compact(
            'pasienBaruHariIni',
            'konsultasiBaru',
            'totalPasien',
            'totalDokter',
            'aktivitasTerbaru',
            'permintaanKonsultasi'
        ));
    }
}
