<?php

namespace App\Http\Controllers\Patient;

use App\Enums\Hari;
use App\Enums\MetodePembayaran;
use App\Enums\StatusKonsultasi;
use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consultations = Consultation::with(['doctor.user', 'payment'])
            ->whereNot('status', StatusKonsultasi::SELESAI)
            ->orderByDesc('created_at')
            ->get();
        return view('pasien.konsultasi.index', compact('consultations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($consultation = Consultation::find($request->input('consultation_id'))) {
            return view('pasien.konsultasi.create', [
                'consultation' => $consultation->load(['doctor.user', 'doctor.specialty', 'doctor.doctorSchedule'])
            ]);
        }

        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'hari' => ['required', Rule::enum(Hari::class)],
            'jam_konsultasi' => ['required'],
        ]);

        $doctorId = $validated['doctor_id'];
        $hari = $validated['hari'];
        $jamKonsultasi = $validated['jam_konsultasi'];

        $jadwalValid = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('hari', $hari)
            ->where('jam_mulai', '<=', $jamKonsultasi)
            ->where('jam_selesai', '>=', $jamKonsultasi)
            ->exists();

        if (!$jadwalValid) {
            return back()->with('error', 'Jadwal tidak valid untuk dokter yang dipilih.');
        }

        $sudahDibooking = Consultation::where('doctor_id', $doctorId)
            ->where('jam_konsultasi', $jamKonsultasi)
            ->where('status', 'berhasil')
            ->exists();

        if ($sudahDibooking) {
            return back()->with('error', 'Jam tersebut sudah dibooking oleh pasien lain.');
        }
        $consultation = Auth::user()->patient->consultation()->firstOrCreate([
            'doctor_id' => $doctorId,
            'hari' => $hari,
            'jam_konsultasi' => $jamKonsultasi,
        ]);

        if ($consultation->payment) {
            abort(419, 'Halaman sudah kadaluarsa atau tidak valid.');
        }


        return view('pasien.konsultasi.create', [
            'consultation' => $consultation->load(['doctor.user', 'doctor.specialty', 'doctor.doctorSchedule'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'consultation_id' => ['required', 'exists:consultations,id'],
            'metode_pembayaran' => ['required', Rule::enum(MetodePembayaran::class)],
        ]);

        $doctorId = $validated['doctor_id'];
        $metodePembayaran = $validated['metode_pembayaran'];
        $consultationId = $validated['consultation_id'];

        try {
            DB::beginTransaction();

            $consultation = Consultation::findOrFail($consultationId);
            $dokter = Doctor::findOrFail($doctorId);
            $harga_konsultasi = $dokter->harga_konsultasi ?? 0;
            $biaya_layanan = 3000;
            $total = $harga_konsultasi + $biaya_layanan;
            $kodeTransaksi = date('YmdHis') . Auth::id() . Auth::user()->getRoleModel()->id;

            $payment = $consultation->payment()->create([
                'patient_id' => Auth::user()->patient->id,
                'metode_pembayaran' => $metodePembayaran,
                'kode_pembayaran' => $kodeTransaksi,
                'jumlah' => $total,
            ]);

            DB::commit();


            return redirect()->route('pasien.pembayaran.edit', ['payment' => $payment->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan konsultasi atau pembayaran: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Consultation $consultation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consultation $consultation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consultation $consultation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consultation $consultation)
    {
        try {
            DB::beginTransaction();

            $consultation->delete();

            DB::commit();

            return back()->with('success', 'Berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error jika diperlukan
            // Log::error('Gagal menghapus konsultasi: '.$e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
