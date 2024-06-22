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

        /** 
         * @TODO: #1 Bundle up the transactions into a UPDATE query for the database to avoid hitting it with a query for each transaction
         * Currently we're hitting the database with a query for each transaction. This is not efficient.
         * We could use DB::raw to write more efficient queries, but given that we do not have hundreds of thousands of transactions
         * we can continue to use individual queries for now, beware of premature optimization.
         * */
        foreach ($transactions as &$transaction) {
            $chain[] = new UpdateStoreInventory($transaction);
        }

        
        // Bus batch the jobs
        Bus::batch($chain)
            ->then(function (Batch $batch) use ($transactions) {
                $existingTransactions = Cache::get('store_inventory_transactions', []);
                $newTransactions = array_diff_key($existingTransactions, $transactions);
                Cache::put('store_inventory_transactions', $newTransactions);
            
                  
            })
            ->dispatch();   
    }
}
