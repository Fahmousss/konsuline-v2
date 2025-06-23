<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ChangeDoctorPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Doctor $doctor)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $doctor->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['success' => true]);
    }
}
