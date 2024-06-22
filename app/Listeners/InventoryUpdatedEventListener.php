<?php

namespace App\Listeners;

use App\Events\InventoryUpdatedEvent;
use App\Models\Shoe;
use App\Models\Store;
use Illuminate\Support\Facades\Cache;

class InventoryUpdatedEventListener
{
    public function __construct()
    {
        //
    }

    public function handle(InventoryUpdatedEvent $event): void
    {
        $store = Cache::remember($event->store, 120, function () use ($event) {
            return Store::firstWhere('name', $event->store);
        });

        $shoe = Cache::remember($event->shoe, 120, function () use ($event) {
            return Shoe::firstWhere('name', $event->shoe);
        });

        // add update to transactions of Redis queue    
        $transactions = Cache::get('store_inventory_transactions', []);

        $transactions[] = [
            'store' => $store->id,
            'shoe' => $shoe->id,
            'quantity' => $event->quantity
        ];

        /** 
         * If duplicate store AND shoe are found in the transactions, only keep the last transaction when store and shoe are the same
         * Duplicates are actually okay to remove as since we're filling out the database with the transactions, we'd only get the latest transaction in the database. 
         * */

        $uniqueTransactions = collect($transactions)->reverse()
            ->unique(function ($transaction) {
                return $transaction['store'] . $transaction['shoe'];
            })->reverse()->values()->toArray();

        Cache::put('store_inventory_transactions', $uniqueTransactions);
    }
}
