<?php

namespace App\Jobs;

use App\Models\StoreInventory;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
    }
}
