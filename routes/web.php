<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    event(new App\Events\MyEvent('hello world'));
    return view('welcome');
});
