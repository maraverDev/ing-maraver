<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\AcousticStudyController;

use App\Http\Controllers\InstallationFileController;


// Auth routes
require __DIR__ . '/auth.php';


Route::middleware(['auth'])->group(function () {

    // PLACES
    Route::middleware('role:admin,technician,viewer')->group(function () {
        Route::get('/places', [PlaceController::class, 'index'])->name('places.index');
        Route::get('/places/{place}', [PlaceController::class, 'show'])->name('places.show');
    });

    Route::middleware('role:admin,technician')->group(function () {
        Route::post('/places', [PlaceController::class, 'store'])->name('places.store');
    });

    // INSTALLATIONS
    Route::middleware('role:admin,technician')->group(function () {
        Route::get('/installations', [InstallationController::class, 'index'])
            ->name('installations.index');

        Route::post('/installations', [InstallationController::class, 'store'])
            ->name('installations.store');
    });

    Route::middleware('role:admin,technician,viewer')->group(function () {
        Route::get('/installations/{installation}', [InstallationController::class, 'show'])
            ->name('installations.show');
    });

    // DOWNLOADS
    Route::middleware('role:admin,technician,viewer')->group(function () {
        Route::get('/acoustic-studies/{study}/download', [AcousticStudyController::class, 'download'])
            ->name('acoustic-studies.download');

        Route::get('/installation-files/{file}/download', [InstallationFileController::class, 'download'])
            ->name('installation-files.download');
    });
    Route::middleware('role:admin,technician')->group(function () {
        Route::patch('/installations/{installation}/notes', [InstallationController::class, 'updateNotes'])
            ->name('installations.notes.update');
    });
});

