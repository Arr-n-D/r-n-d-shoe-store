<?php

namespace App\Http\Controllers;

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
        $store = Cache::remember($store->name . 'inventory', 30, function () {
            return Store::with('inventory')->get();
        });

        return response()->json($store);
    }
}
