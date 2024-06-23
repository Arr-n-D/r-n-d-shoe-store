<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;

// make a route for the /store/{store} endpoint
Route::get('/store/{store}', [StoreController::class, 'show']);