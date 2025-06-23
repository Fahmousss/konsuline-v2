<?php

namespace App\Models;

use App\Observers\DoctorObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Doctor
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $specialty_id
 * @property int $harga_konsultasi
 * @property string $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property \App\Models\User $user
 * @property \App\Models\Specialty|null $specialty
 */

#[ObservedBy(DoctorObserver::class)]
class Doctor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'specialty_id',
        'harga_konsultasi',
        'foto',
    ];

    /**
     * Get the doctor account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the specialty associated with the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * Get the schedule associated with the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function doctorSchedule(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
    }


    /**
     * Get the review associated with the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get all the review associated with the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the consultation associated with the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    /**
     * Get the full profile attribute as an array containing
     * user's name, email, specialty, consultation price, and photo.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function fullProfile(): Attribute
    {
        return Attribute::make(
            get: fn() => [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'specialty' => $this->specialty,
                'harga_konsultasi' => $this->harga_konsultasi,
                'foto' => $this->foto,
            ],
        );
    }
}
