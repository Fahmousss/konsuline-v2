<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusKonsultasi;
use App\Enums\StatusPembayaran;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ManagePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayaran = Payment::with([
            'patient',
            'consultation.doctor.user',
            'patient.user',
        ])->orderBy('created_at', 'desc')->get();

        // dd($pembayaran);
        return view('admin.pembayaran.index', compact('pembayaran'));
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(StatusPembayaran::class)],
        ]);

        try {
            DB::beginTransaction();

            $payment->update(
                ['status' => $validated['status']]
            );

            $payment->consultation->update([
                'status' => StatusKonsultasi::VERIFIKASI
            ]);

            DB::commit();

            return back()->with('success', 'Status pembayaran berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
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
