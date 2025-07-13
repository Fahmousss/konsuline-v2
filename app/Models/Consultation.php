<?php

namespace App\Models;

use App\Enums\Hari;
use App\Enums\StatusKonsultasi;
use App\Enums\StatusPembayaran;
use App\Models\Scopes\DoctorScope;
use App\Models\Scopes\PatientScope;
use App\Observers\ConsultationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Consultation
 *
 * @property int $id
 * @property int $doctor_id
 * @property int $patient_id
 * @property \App\Enums\StatusKonsultasi $status
 * @property \App\Enums\Hari $hari
 * @property string $jam_konsultasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property \App\Models\Doctor $doctor
 * @property \App\Models\Patient $patient
 */

#[ScopedBy([PatientScope::class, DoctorScope::class])]
#[ObservedBy(ConsultationObserver::class)]
class Consultation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'status',
        'hari',
        'jam_konsultasi'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => StatusKonsultasi::class,
            'hari' => Hari::class,
        ];
    }

    /**
     * Get the doctor associated with the consultation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the patient associated with the consultation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the payment associated with the consultation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    #[Scope]
    public function sudahDiBayar(Builder $query): void
    {
        $query->whereHas('payment', function ($q) {
            $q->where('status', StatusPembayaran::BERHASIL);
        });
    }
}
