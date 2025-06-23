<?php

namespace App\Http\Controllers\Doctor;

use App\Enums\Hari;
use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwal = DoctorSchedule::all();

        return view('dokter.jadwal.index', compact('jadwal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'hari' => ['required', Rule::enum(Hari::class)],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required', 'after:jam_mulai'],
        ]);

        $existing = Auth::user()->doctor->doctorSchedule()
            ->where('hari', $data['hari'])
            ->where(function ($query) use ($data) {
                $query
                    ->whereBetween('jam_mulai', [$data['jam_mulai'], $data['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$data['jam_mulai'], $data['jam_selesai']])
                    ->orWhere(function ($q) use ($data) {
                        $q->where('jam_mulai', '<', $data['jam_mulai'])
                            ->where('jam_selesai', '>', $data['jam_selesai']);
                    });
            })
            ->exists();

        if ($existing) {
            return response()->json([
                'message' => 'Jadwal bentrok dengan yang sudah ada.',
            ], 422);
        }

        $jadwal = Auth::user()->doctor->doctorSchedule()->create($data);
        return response()->json($jadwal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DoctorSchedule $jadwal)
    {
        $data = $request->validate([
            'hari' => ['required', Rule::enum(Hari::class)],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required', 'after:jam_mulai'],
        ]);

        $existing = Auth::user()->doctor->doctorSchedule()
            ->where('hari', $data['hari'])
            ->where('id', '!=', $jadwal->id)
            ->where(function ($query) use ($data) {
                $query
                    ->whereBetween('jam_mulai', [$data['jam_mulai'], $data['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$data['jam_mulai'], $data['jam_selesai']])
                    ->orWhere(function ($q) use ($data) {
                        $q->where('jam_mulai', '<', $data['jam_mulai'])
                            ->where('jam_selesai', '>', $data['jam_selesai']);
                    });
            })
            ->exists();

        if ($existing) {
            return response()->json([
                'message' => 'Jadwal bentrok dengan yang sudah ada.',
            ], 422);
        }

        $jadwal->update($data);
        return response()->json($jadwal);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DoctorSchedule $jadwal)
    {
        $jadwal->delete();

        return response()->json(['message' => 'Data pasien berhasil dihapus']);
    }
}
