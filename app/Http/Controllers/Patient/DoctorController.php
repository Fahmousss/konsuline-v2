<?php

namespace App\Http\Controllers\Patient;

use App\Enums\StatusKonsultasi;
use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::with(['user', 'specialty', 'review'])->orderByDesc('id')->paginate(10);

        return view('pasien.dokter.index', compact('doctors'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor->load(['specialty', 'doctorSchedule', 'user']);

        $slots = [];
        foreach ($doctor->doctorSchedule as $jadwal) {
            $start = Carbon::parse($jadwal->jam_mulai);
            $end = Carbon::parse($jadwal->jam_selesai);

            while ($start < $end) {
                $slots[$jadwal->hari->value][] = $start->format('H:i');
                $start->addHour();
            }
        }


        // Ambil slot yang sudah dibooking
        $booked = Consultation::where('doctor_id', $doctor->id)
            ->where('status', StatusKonsultasi::BERLANGSUNG)
            ->pluck('jam_konsultasi')
            ->toArray();

        return view('pasien.dokter.show', compact('doctor', 'slots', 'booked'));
    }
}
