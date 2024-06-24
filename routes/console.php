<?php

use App\Http\Resources\InventoryResource;
use Spatie\DiscordAlerts\Facades\DiscordAlert;
use App\Jobs\ProcessInventoryUpdates;
use App\Models\StoreInventory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schedule;


$inventory = Cache::remember('inventory', 10, function () {
    return StoreInventory::with('store', 'shoe')
        ->orderBy('store_id')
        ->orderBy('quantity')
        ->get();
});

$inventory = InventoryResource::collection($inventory);

// make an inventory array for each store
$inventoryByStore = $inventory->groupBy('store_id');


$embeds = $inventoryByStore->map(function ($inventory, $storeId) {
    $storeName = $inventory->first()->store->name ?? 'Store ' . $storeId;

    $description = $inventory->map(function ($item) {
        return $item->shoe->name . ' (Quantity: ' . $item->quantity . ')';
    })->implode("\n");

    return [
        'title' => $storeName,
        'description' => $description,
    ];
});

// this is some whack issue
$embedsArray = $embeds->toArray();
$array = [];
foreach ($embeds as $embed) {
    $array[] = $embed;
}


Schedule::job(new ProcessInventoryUpdates)->everyFifteenSeconds();

// if array is not empty, send a message
if (!empty($array)) {
    Schedule::job(function () use ($array) {
        $now = now();
        DiscordAlert::message("Inventory status for $now", $array);
    })->everyThirtySeconds();
}
