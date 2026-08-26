<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ExtraController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\ProfileController;
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
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categorias', CategoryController::class)
        ->parameters(['categorias' => 'category'])
        ->except(['show']);

    Route::resource('vehiculos', VehicleController::class)
        ->parameters(['vehiculos' => 'vehicle'])
        ->except(['show']);

    Route::resource('extras', ExtraController::class)
        ->parameters(['extras' => 'extra'])
        ->except(['show']);
});

require __DIR__.'/auth.php';
