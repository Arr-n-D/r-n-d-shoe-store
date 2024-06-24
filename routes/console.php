<?php

use App\Http\Resources\InventoryResource;
use Spatie\DiscordAlerts\Facades\DiscordAlert;
use App\Jobs\ProcessInventoryUpdates;
use App\Models\StoreInventory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schedule;


$inventory = Cache::remember('inventory', 30, function () {
    return StoreInventory::with('store', 'shoe')
        ->orderBy('store_id')
        ->orderBy('quantity')
        ->get();
});

$inventory = InventoryResource::collection($inventory);

// make an inventory array for each store
$inventoryByStore = $inventory->groupBy('store_id');


$embeds = $inventoryByStore->map(function ($inventory, $storeId) {

    // fix this, the store name should be gotten in the second map 
    $storeName = $inventory->first()->store->name ?? 'Store ' . $storeId;

    $description = $inventory->map(function ($item) {
        return $item->shoe->name . ' (Quantity: ' . $item->quantity . ')';
    })->implode("\n"); // Changed from ', ' to "\n" for new lines

    return [
        'title' => $storeName,
        'description' => $description,
    ];
});

$embedsArray = $embeds->toArray();
$array = [];
foreach ($embeds as $embed) {
    $array[] = $embed;
}


Schedule::job(new ProcessInventoryUpdates)->everyThirtySeconds();

// foreach ($embeds as $embed) {
    Schedule::job(function () use ($array) {
        DiscordAlert::message('Inventory status', $array);
    })->everyThirtySeconds();
// }
