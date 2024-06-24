<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryResource;
use App\Models\StoreInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventory = Cache::remember('inventory', 30, function () {
            return StoreInventory::with('store', 'shoe')
                ->orderBy('store_id')
                ->orderBy('quantity')
                ->get();
        });

        return InventoryResource::collection($inventory);
    }

}
