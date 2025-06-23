<?php

namespace App\Observers;

use App\Models\Doctor;
use Illuminate\Support\Facades\Storage;

class DoctorObserver
{
    /**
     * Handle the Doctor "updated" event.
     */
    public function updated(Doctor $doctor): void
    {
        if ($doctor->isDirty('foto')) {
            Storage::disk('public')->delete($doctor->getOriginal('foto'));
        }
    }

    /**
     * Handle the Doctor "deleted" event.
     */
    public function deleted(Doctor $doctor): void
    {
        if (! is_null($doctor->foto)) {
            Storage::disk('public')->delete($doctor->foto);
        }
    }
}
