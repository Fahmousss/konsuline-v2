<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManagePatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patient = Patient::with('user')->orderByDesc('id')->paginate(10);

        return view('admin.pasien.index', compact('patient'));
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
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $patient->load('user');

        return view('admin.pasien.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:30'],
            'tanggal_lahir' => ['required', 'date', Rule::date()->beforeOrEqual(today()->subYear(7))],
        ]);

        $patient->user->update([
            'name' => $request->nama
        ]);

        $patient->update([
            'tanggal_lahir' => $request->tanggal_lahir
        ]);

        return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->user()->delete();

        return response()->json(['message' => 'Data pasien berhasil dihapus']);
    }
}
