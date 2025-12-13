<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallationController;



// Auth routes
require __DIR__ . '/auth.php';


Route::middleware(['auth'])->group(function () {
    // Places routes
    Route::middleware('role:admin,technician,viewer')->group(function () {
        Route::get('/places', [PlaceController::class, 'index'])
            ->name('places.index');
    });
    // Places store route
    Route::middleware('role:admin,technician')->group(function () {
        Route::post('/places', [PlaceController::class, 'store'])
            ->name('places.store');
    });
    // Installations routes
    Route::middleware(['auth', 'role:admin,technician'])->group(function () {
        Route::get('/installations', [InstallationController::class, 'index'])
            ->name('installations.index');
        // 
        Route::post('/installations', [InstallationController::class, 'store'])
            ->name('installations.store');
    });

});


