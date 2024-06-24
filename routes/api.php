<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;

Route::get('/store/{store}', [StoreController::class, 'show']);
Route::get('/inventory', [InventoryController::class, 'index']);