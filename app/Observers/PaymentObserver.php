<?php

namespace App\Observers;

use App\Models\Payment;
use Illuminate\Support\Facades\Storage;

class PaymentObserver
{
    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        if ($payment->isDirty('bukti_pembayaran')) {
            Storage::disk('public')->delete($payment->getOriginal('bukti_pembayaran'));
        }
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        if (! is_null($payment->foto)) {
            Storage::disk('public')->delete($payment->bukti_pembayaran);
        }
    }
}
