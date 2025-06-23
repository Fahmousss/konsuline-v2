<?php

namespace App\Models;

use App\Enums\Hari;
use App\Models\Scopes\DoctorScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\DoctorSchedule
 *
 * @property int $id
 * @property int $doctor_id
 * @property \App\Enums\Hari $hari
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property \App\Models\Doctor $doctor
 */

#[ScopedBy([DoctorScope::class])]
class DoctorSchedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'doctor_id',
        'hari',
        'jam_mulai',
        'jam_selesai'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hari' => Hari::class,
        ];
    }

    /**
     * Get the doctor associated schedule.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
