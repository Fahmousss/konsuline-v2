<?php

namespace App\Models;

use App\Enums\MetodePembayaran;
use App\Enums\StatusPembayaran;
use App\Models\Scopes\PatientScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property int $consultation_id
 * @property int $patient_id
 * @property \App\Enums\MetodePembayaran $metode_pembayaran
 * @property string|null $bukti_pembayaran
 * @property string|null $kode_pembayaran
 * @property int $jumlah
 * @property \App\Enums\StatusPembayaran $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property \App\Models\Consultation $consultation
 * @property \App\Models\Patient $patient
 */

#[ScopedBy(PatientScope::class)]
class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'consultation_id',
        'patient_id',
        'metode_pembayaran',
        'bukti_pembayaran',
        'kode_pembayaran',
        'jumlah',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts()
    {
        return [
            'metode_pembayaran' => MetodePembayaran::class,
            'jumlah' => 'integer',
            'status' => StatusPembayaran::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the consultation related to the payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    /**
     * Get the patient who made the payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
