<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ManageDoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::with(['user', 'specialty'])->orderByDesc('id')->paginate(10);

        return view('admin.dokter.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $spesialis = Specialty::all();
        return view('admin.dokter.create', compact('spesialis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'username' => ['required', 'string', 'min:3', 'alpha_dash', 'max:30', 'unique:' . User::class],
            'specialis_id' => ['nullable', 'exists:specialties,id'],
            'password' => ['required', Rules\Password::defaults()],
            'foto' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => UserRole::DOKTER->value,
        ]);
        $user->markEmailAsVerified();

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('foto_dokter', 'public'); // simpan ke storage/app/public/foto_dokter
        }

        $user->doctor()->create([
            'specialty_id' => $request->spesialis_id,
            'foto' => $foto
        ]);

        return redirect()->route('admin.dokter.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'specialty', 'doctorSchedule']);

        return view('admin.dokter.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        $doctor->load(['user', 'specialty']);
        $specialties = Specialty::all();
        return view('admin.dokter.edit', compact(['doctor', 'specialties']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:30'],
            'spesialis_id' => ['required', 'exists:specialties,id'],
            'foto' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048']
        ]);

        $doctor->user->update([
            'name' => $request->nama
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('foto_dokter', 'public'); // simpan ke storage/app/public/foto_dokter
        }


        $doctor->update([
            'specialty_id' => $request->spesialis_id,
            'foto' => $foto ?? null
        ]);

        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {

        $doctor->user()->delete();

        return response()->json(['message' => 'Data pasien berhasil dihapus']);
    }
}
