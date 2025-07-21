<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class SearchDoctorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $search = $request->query('q');
        $specialtyId = $request->query('specialty_id');

        $doctors = Doctor::with(['specialty', 'user', 'review'])
            ->when($specialtyId, function ($q) use ($specialtyId) {
                return $q->where('specialty_id', $specialtyId);
            })
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->limit(10)
            ->get();

        return response()->json($doctors->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->user->name,
                'email' => $doctor->user->email,
                'specialty' => $doctor->specialty->nama,
                'foto' => $doctor->foto ? asset('storage/' . $doctor->foto) : null,
                'harga_konsultasi' => $doctor->harga_konsultasi,
            ];
        }));
    }
}
