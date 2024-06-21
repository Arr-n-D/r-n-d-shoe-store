<?php

namespace App\Jobs;

use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessInventoryUpdates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct()
    {
    }

    public function handle(): void
    {
        Log::info('Processing inventory updates');
        $transactions = Cache::get('store_inventory_transactions', []);
        $chain = [];

        if (count($transactions) < 1) {
            return;
        }

        foreach ($transactions as &$transaction) {
            $chain[] = new UpdateStoreInventory($transaction);
        }

        // Bus batch the jobs
        Bus::batch($chain)
            ->then(function (Batch $batch) use ($transactions) {
                $existingTransactions = Cache::get('store_inventory_transactions', []);
                // print the first 3 existing transactions
                var_dump(array_slice($existingTransactions, 0, 3));
                // print the first 3 transactions
                var_dump(array_slice($transactions, 0, 3));



                $newTransactions = array_diff_key($existingTransactions, $transactions);
                Cache::put('store_inventory_transactions', $newTransactions);
            
                  
            })
            ->dispatch();   
    }
}
