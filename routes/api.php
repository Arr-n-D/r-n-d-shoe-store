<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Resources\InventoryResource;
use App\Models\StoreInventory;
use Illuminate\Support\Facades\Cache;

Route::get('/store/{store}', [StoreController::class, 'show']);
Route::get('/inventory', [InventoryController::class, 'index']);
Route::get('/', function () {
    $inventory = Cache::remember('inventory', 30, function () {
        return StoreInventory::with('store', 'shoe')
            ->orderBy('store_id')
            ->orderBy('quantity')
            ->get();
    });

    $inventory = InventoryResource::collection($inventory);

    // make an inventory array for each store
    $inventoryByStore = $inventory->groupBy('store_id');

    // make a map of embeds for each store, using the store name, the shoe model and the quantity for that shoe model
    $embeds = $inventoryByStore->map(function ($inventory, $storeId) {
        // Assuming StoreInventory model has 'store' relation with 'name' attribute
        $storeName = $inventory->first()->store->name ?? 'Store ' . $storeId;

        $description = $inventory->map(function ($item) {
            // Assuming 'shoe' relation has 'name' attribute and 'quantity' is directly accessible
            return $item->shoe->name . ' (Quantity: ' . $item->quantity . ')';
        })->implode("\n"); // Changed from ', ' to "\n" for new lines

        return [
            'title' => $storeName,
            'description' => $description,
        ];
    });

    return $embeds->toArray();
});