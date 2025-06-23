<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the doctor profile associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Get the patient profile associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }

    /**
     * Determine if the user is a doctor by checking role and doctor relation.
     *
     * @return bool
     */
    public function isDoctor(): bool
    {
        return $this->role === UserRole::DOKTER && $this->doctor()->exists();
    }

    /**
     * Determine if the user is an admin by checking role and admin relation.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    /**
     * Determine if the user is a patient by checking role and patient relation.
     *
     * @return bool
     */
    public function isPatient(): bool
    {
        return $this->role === UserRole::PASIEN && $this->patient()->exists();
    }

    /**
     * Get the related role model instance based on the user's role.
     *
     * @return \App\Models\Doctor|\App\Models\Patient|null
     */
    public function getRoleModel()
    {
        return match ($this->role) {
            UserRole::DOKTER => $this->doctor,
            UserRole::PASIEN => $this->patient,
            default => null
        };
    }
}
