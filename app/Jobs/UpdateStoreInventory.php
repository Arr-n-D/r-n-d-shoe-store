<?php

namespace App\Jobs;

use App\Models\StoreInventory;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\DiscordAlerts\Facades\DiscordAlert;

class UpdateStoreInventory implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $transaction;
    /**
     * Create a new job instance.
     */
    public function __construct(array $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        StoreInventory::updateOrCreate(
            ['store_id' => $this->transaction['store'], 'shoe_id' => $this->transaction['shoe']],
            ['quantity' => $this->transaction['quantity']]
        );

        // if quantity is lower than 10, send an alert to Discord
        if ($this->transaction['quantity'] <= 10) {
            DiscordAlert::to('low-inventory')->message(
                sprintf(
                    "Low inventory alert for store: %s, shoe model %s only has %s in stock ",
                    $this->transaction['store_name'],
                    $this->transaction['shoe_name'],
                    $this->transaction['quantity']
                )
            );
        }
    }
}
