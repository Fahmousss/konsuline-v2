<?php

namespace App\Observers;

use App\Enums\StatusKonsultasi;
use App\Models\Consultation;

class ConsultationObserver
{
    /**
     * Handle the Consultation "updated" event.
     */
    public function updated(Consultation $consultation): void
    {
        if ($consultation->status === StatusKonsultasi::BERLANGSUNG) {
            $consultation->started_at = now();
            $consultation->save();
        }

        if ($consultation->status === StatusKonsultasi::SELESAI) {
            $consultation->messages()->delete();
        }
    }
}
