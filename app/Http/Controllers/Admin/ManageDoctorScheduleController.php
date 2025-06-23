<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Hari;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManageDoctorScheduleController extends Controller
{
    public function store(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'hari' => ['required', Rule::enum(Hari::class)],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required', 'after:jam_mulai'],
        ]);

        // Validasi bentrok
        $exists = $doctor->doctorSchedule()
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

        if ($exists) {
            return response()->json([
                'message' => 'Jadwal bertabrakan dengan jadwal yang sudah ada.'
            ], 422);
        }

        $jadwal = $doctor->doctorSchedule()->create($data);
        return response()->json($jadwal);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        $doctor->load(['doctorSchedule']);

        return view('admin.dokter.jadwal.edit', compact('doctor'));
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

        // Validasi bentrok
        $exists = $jadwal->doctor->doctorSchedule()
            ->where('id', '!=', $jadwal->id)
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

        if ($exists) {
            return response()->json([
                'message' => 'Jadwal bertabrakan dengan jadwal yang sudah ada.'
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
