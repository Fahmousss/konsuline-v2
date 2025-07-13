<?php

namespace App\Jobs;

use App\Enums\StatusKonsultasi;
use App\Models\Consultation;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class AutoCompleteMessageJob implements ShouldQueue
{
    use Queueable,
        \Illuminate\Queue\InteractsWithQueue,
        \Illuminate\Queue\SerializesModels,
        Dispatchable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $messages = Consultation::where('status', StatusKonsultasi::BERLANGSUNG)
            ->where('started_at', '<=', now()->subMinutes(30))->get();

        if ($messages->isEmpty()) {
            return;
        }
        foreach ($messages as $message) {
            $message->status = StatusKonsultasi::SELESAI;
            $message->messages()->delete();
            $message->save();
        }
    }
}
