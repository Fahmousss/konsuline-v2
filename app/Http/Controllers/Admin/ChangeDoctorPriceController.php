<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class ChangeDoctorPriceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Doctor $doctor)
    {
        $request->validate([
            'harga' => ['required', 'numeric', 'min:0']
        ]);

        $doctor->update(['harga_konsultasi' => $request->harga]);

        return response()->json(['success' => true]);
    }
}
