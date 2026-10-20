<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Owner\StationController as OwnerStationController;
use App\Http\Controllers\Admin\ModerationController as AdminModerationController;
use App\Http\Controllers\Driver\StationCatalogController;

Route::get('/', function () {
    return redirect()->route('driver.stations.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Стандартные маршруты профиля Breeze (их не хватало)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// CRUD станций для владельца (Owner)
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::resource('stations', OwnerStationController::class);

    // Управление разъемами конкретной станции
    Route::get('/stations/{station}/connectors', [\App\Http\Controllers\Owner\ConnectorController::class, 'index'])->name('stations.connectors.index');
    Route::post('/stations/{station}/connectors', [\App\Http\Controllers\Owner\ConnectorController::class, 'store'])->name('stations.connectors.store');
    Route::delete('/stations/{station}/connectors/{connector}', [\App\Http\Controllers\Owner\ConnectorController::class, 'destroy'])->name('stations.connectors.destroy');
});

// Модерация для администратора (Admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/moderation', [AdminModerationController::class, 'index'])->name('moderation.index');
    Route::post('/moderation/{station}/approve', [AdminModerationController::class, 'approve'])->name('moderation.approve');
    Route::post('/moderation/{station}/reject', [AdminModerationController::class, 'reject'])->name('moderation.reject');
});

Route::prefix('catalog')->name('driver.stations.')->group(function () {
    Route::get('/', [StationCatalogController::class, 'index'])->name('index');
    Route::get('/{station}', [StationCatalogController::class, 'show'])->name('show');
});

require __DIR__.'/auth.php';