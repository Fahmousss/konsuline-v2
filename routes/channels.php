<?php

use App\Models\Consultation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('consultation.{id}', function ($user, $id) {
    $consultation = Consultation::with(['patient.user', 'doctor.user'])->find($id);

    if (!$consultation) {
        return false;
    }

    return $consultation->patient?->user_id === $user->id
        || $consultation->doctor?->user_id === $user->id;
});
