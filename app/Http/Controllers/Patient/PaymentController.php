<?php

namespace App\Http\Controllers\Patient;

use App\Enums\StatusPembayaran;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $payment->load(['consultation.doctor.user']);

        return view('pasien.pembayaran.konfirmasi', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Simpan file bukti pembayaran
            $path = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $path = $file->store('bukti_pembayaran', 'public');
            }

            // Update payment
            $payment->update([
                'bukti_pembayaran' => $path,
                'status' => StatusPembayaran::VERIFIKASI,
            ]);

            DB::commit();

            return back()->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
