<?php

namespace App\Enums;

enum StatusPembayaran: string
{
    case BERHASIL = 'berhasil';
    case GAGAL = 'gagal';
    case PENDING = 'pending';
    case VERIFIKASI = 'menunggu verifikasi';
}
