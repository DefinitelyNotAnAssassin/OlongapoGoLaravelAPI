<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accounts;
use App\Http\Controllers\Rides;

Route::get('/', function () {
    return view('index');
});

Route::post('/accounts/register', [Accounts::class, 'register']);

Route::post('/accounts/login', [Accounts::class, 'login']);


Route::post('/rides/getRides', [Rides::class, 'getRides']);
Route::post('/rides/create', [Rides::class, 'createRide']);
Route::post('/rides/search', [Rides::class, 'search']);
Route::post('/rides/acceptRide', [Rides::class, 'acceptRide']);
Route::post('/rides/updateRideStatus', [Rides::class, 'updateRideStatus']);


