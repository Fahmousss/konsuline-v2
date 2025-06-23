<?php

namespace App\Enums;

enum StatusKonsultasi: string
{
    case PENDING = 'pending';
    case SELESAI = 'selesai';
    case BERLANGSUNG = 'berlangsung';
    case VERIFIKASI = 'menunggu verifikasi';
}
