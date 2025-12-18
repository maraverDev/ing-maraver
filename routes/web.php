<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\AcousticStudyController;
use App\Http\Controllers\InstallationIssueController;
use App\Http\Controllers\InstallationFileController;

// Auth routes
require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    // Route::get('/dashboard', function () {
    //     return redirect()->route('places.index');
    // })->name('dashboard');

    Route::get('/', fn() => view('dashboard'))->name('dashboard');

    /**
     * READ-ONLY ACCESS
     * Roles: Admin, Technician, Viewer
     */
    Route::middleware('role:admin,technician,viewer')->group(function () {
        // Places
        Route::get('/places', [PlaceController::class, 'index'])->name('places.index');
        Route::get('/places/{place}', [PlaceController::class, 'show'])->name('places.show');
        Route::get('/places/{place}/sub-sites', [PlaceController::class, 'subSites']);

        // Installations
        Route::get('/installations/{installation}', [InstallationController::class, 'show'])
            ->name('installations.show');
        Route::put('/installations/{installation}', [InstallationController::class, 'update'])
            ->name('installations.update');
        Route::post(
            '/installations/{installation}/files/{subSite}',
            [InstallationController::class, 'updateFiles']
        )->name('installations.files.update');

        // ELIMINAR archivo (DELETE REAL)
        Route::delete(
            '/installation-files/{file}',
            [InstallationFileController::class, 'destroy']
        )->name('installation-files.destroy');

        // Downloads
        Route::get('/acoustic-studies/{study}/download', [AcousticStudyController::class, 'download'])
            ->name('acoustic-studies.download');

        Route::get('/installation-files/{file}/download', [InstallationFileController::class, 'download'])
            ->name('installation-files.download');
    });

    /**
     * WRITE ACCESS
     * Roles: Admin, Technician
     */
    Route::middleware('role:admin,technician')->group(function () {
        // Places
        Route::post('/places', [PlaceController::class, 'store'])->name('places.store');

        // Installations
        Route::get('/installations', [InstallationController::class, 'index'])
            ->name('installations.index');

        Route::post('/installations', [InstallationController::class, 'store'])
            ->name('installations.store');

        Route::patch('/installations/{installation}/notes', [InstallationController::class, 'updateNotes'])
            ->name('installations.notes.update');

        // Installation Issues
        Route::post('/installations/{installation}/issues', [InstallationIssueController::class, 'store'])
            ->name('installations.issues.store');

        Route::patch('/installation-issues/{issue}/close', [InstallationIssueController::class, 'close'])
            ->name('installation-issues.close');
    });
});
