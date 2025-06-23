<?php

namespace App\Models\Scopes;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class PatientScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();
        if ($user->role === UserRole::PASIEN) {
            $builder->whereHas('patient', function ($query) use ($user) {
                $query->where('id', $user->patient->id);
            });
        }
    }
}
