<?php

namespace App\Listeners;

use App\Events\InventoryUpdatedEvent;
use App\Models\Shoe;
use App\Models\Store;
use Illuminate\Support\Facades\Cache;

class InventoryUpdatedEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InventoryUpdatedEvent $event): void
    {
        $store = Cache::remember($event->store, 60, function () use ($event) {
            return Store::firstWhere('name', $event->store);
        });

        $shoe = Cache::remember($event->shoe, 60, function () use ($event) {
            return Shoe::firstWhere('name', $event->shoe);
        });
        
        // add update to transactions of Redis queue    
        $transactions = Cache::get('store_inventory_transactions', []);

        $transactions[] = [
            'store' => $store->id,
            'shoe' => $shoe->id,
            'quantity' => $event->quantity
        ];

        Cache::put('store_inventory_transactions', $transactions);

    }
}
