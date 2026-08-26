<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ExtraController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Catálogo
    Route::get('/vehiculos', [VehicleController::class, 'index'])->name('vehiculos.index');
    Route::get('/vehiculos/{vehicle}', [VehicleController::class, 'show'])->name('vehiculos.show');

    // Reservas
    Route::get('/vehiculos/{vehicle}/reservar', [ReservationController::class, 'create'])->name('vehiculos.reservar');
    Route::post('/vehiculos/{vehicle}/reservar', [ReservationController::class, 'store'])->name('vehiculos.reservar.store');
    Route::get('/mis-reservas', [ReservationController::class, 'index'])->name('mis-reservas.index');
    Route::get('/mis-reservas/{reservation}', [ReservationController::class, 'show'])->name('mis-reservas.show');

    // Pago
    Route::get('/reservas/{reservation}/pago', [PaymentController::class, 'show'])->name('reservas.pago');
    Route::post('/reservas/{reservation}/pago', [PaymentController::class, 'confirm'])->name('reservas.pago.confirm');

    // Favoritos
    Route::get('/favoritos', [FavoriteController::class, 'index'])->name('favoritos.index');
    Route::post('/favoritos/{vehicle}', [FavoriteController::class, 'store'])->name('favoritos.store');
    Route::delete('/favoritos/{vehicle}', [FavoriteController::class, 'destroy'])->name('favoritos.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categorias', CategoryController::class)
        ->parameters(['categorias' => 'category'])
        ->except(['show']);

    Route::resource('vehiculos', AdminVehicleController::class)
        ->parameters(['vehiculos' => 'vehicle'])
        ->except(['show']);

    Route::resource('extras', ExtraController::class)
        ->parameters(['extras' => 'extra'])
        ->except(['show']);
});

require __DIR__.'/auth.php';
