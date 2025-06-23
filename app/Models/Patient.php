<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Patient
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $tanggal_lahir
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property \App\Models\User $user
 */
class Patient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'tanggal_lahir',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date'
    ];

    /**
     * Get the patient account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the consultation associated with the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function consultation(): HasOne
    {
        return $this->hasOne(Consultation::class);
    }
}
