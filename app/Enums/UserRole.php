<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case DOKTER = 'dokter';
    case PASIEN = 'pasien';

    public function label(): string
    {
        return match ($this) {
            self::DOKTER => 'Dokter',
            self::PASIEN => 'Pasien',
            self::ADMIN => 'Administrator',
        };
    }

    public function redirectPath(): string
    {
        return match ($this) {
            self::DOKTER => 'dokter.dashboard',
            self::ADMIN => 'admin.dashboard',
            self::PASIEN => 'pasien.dashboard',
            default => 'welcome',
        };
    }
}
