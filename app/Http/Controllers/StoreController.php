<?php

namespace App\Http\Controllers;

use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StoreController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        // Cache remember for 30 seconds for this specific store
        $ourStore = Cache::remember($store->id, 30, function () use ($store) {
            return Store::with('inventory')->find($store->id);
        });

        return new StoreResource($ourStore);
    }
}
