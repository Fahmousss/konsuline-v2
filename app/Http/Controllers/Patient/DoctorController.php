<?php

namespace App\Http\Controllers\Patient;

use App\Enums\StatusKonsultasi;
use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $specialties = Specialty::orderBy('nama')->get();

        $doctors = collect(); // Empty collection initially
        $selectedSpecialty = null;

        if ($request->has('specialty_id') && $request->specialty_id) {
            $selectedSpecialty = Specialty::find($request->specialty_id);
            $doctors = Doctor::with(['user', 'specialty', 'review'])
                ->where('specialty_id', $request->specialty_id)
                ->orderByDesc('id')
                ->paginate(10);
        }

        return view('pasien.dokter.index', compact('doctors', 'specialties', 'selectedSpecialty'));
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
