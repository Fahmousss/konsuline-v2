<?php

namespace App\Enums;

enum MetodePembayaran: string
{
    case QRIS = 'qris';
    case TRANSFER = 'transfer';
}
