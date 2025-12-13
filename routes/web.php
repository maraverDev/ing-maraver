<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


// Auth routes
require __DIR__ . '/auth.php';


Route::middleware(['auth'])->group(function () {

    Route::middleware('role:admin,technician,viewer')->group(function () {
        Route::get('/places', [PlaceController::class, 'index'])
            ->name('places.index');
    });

    Route::middleware('role:admin,technician')->group(function () {
        Route::post('/places', [PlaceController::class, 'store'])
            ->name('places.store');
    });

});


