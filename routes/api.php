<?php

use App\Http\Controllers\MapController;
use App\Http\Controllers\QRController;
use Illuminate\Support\Facades\Route;

Route::get('/scan/product/{product}', [QRController::class, 'productApi'])->name('api.scan.product');
Route::get('/scan/location/{qrLocationCode}', [QRController::class, 'locationApi'])->name('api.scan.location');
Route::post('/scan/resolve', [QRController::class, 'resolve'])->name('api.scan.resolve');
Route::get('/map/stalls', [MapController::class, 'data'])->name('api.map.stalls');
