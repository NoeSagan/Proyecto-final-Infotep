<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExtraController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\ClientProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

// Públicas
Route::get('/', function () {
    $categories = \App\Models\Category::orderBy('name')->get();
    $featured   = \App\Models\Vehicle::with('category')
        ->where('status', 'disponible')
        ->inRandomOrder()
        ->limit(8)
        ->get();
    $makes = \App\Models\Vehicle::where('status', 'disponible')
        ->select('brand')->distinct()->orderBy('brand')->pluck('brand');

    $imageService = app(\App\Services\CarImagesService::class);
    $brandLogos   = $makes->mapWithKeys(fn ($make) => [$make => $imageService->getBrandLogo($make)]);

    return view('welcome', compact('categories', 'featured', 'makes', 'brandLogos'));
})->name('inicio');

Route::get('/terminos', function () {
    return view('terminos');
})->name('terminos');

Route::get('/privacidad', function () {
    return view('privacidad');
})->name('privacidad');

Route::get('/contacto', [ContactController::class, 'show'])->name('contacto');
Route::post('/contacto', [ContactController::class, 'send'])->name('contacto.send');

// Catálogo público (sin necesidad de login)
Route::get('/vehiculos', [VehicleController::class, 'index'])->name('vehiculos.index');
// listing/{id} DEBE ir antes de {vehicle} para evitar conflicto de binding
Route::get('/vehiculos/listing/{listingId}', [VehicleController::class, 'showListing'])->name('vehiculos.listing');
Route::get('/vehiculos/{vehicle}', [VehicleController::class, 'show'])->name('vehiculos.show');

// Redirección post-login según rol
Route::get('/dashboard', function () {
    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('vehiculos.index');
})->middleware('auth')->name('dashboard');

// Cliente autenticado
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Perfil en español
    Route::get('/perfil', [ClientProfileController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [ClientProfileController::class, 'update'])->name('perfil.update');

    // Reservas
    Route::get('/vehiculos/{vehicle}/reservar', [ReservationController::class, 'create'])->name('vehiculos.reservar');
    Route::post('/vehiculos/{vehicle}/reservar', [ReservationController::class, 'store'])->name('vehiculos.reservar.store');
    Route::get('/mis-reservas', [ReservationController::class, 'index'])->name('mis-reservas.index');
    Route::get('/mis-reservas/{reservation}', [ReservationController::class, 'show'])->name('mis-reservas.show');
    Route::post('/mis-reservas/{reservation}/cancelar', [ReservationController::class, 'cancel'])->name('mis-reservas.cancel');
    Route::get('/mis-reservas/{reservation}/comprobante', [ReservationController::class, 'comprobante'])->name('mis-reservas.comprobante');

    // Pago
    Route::get('/reservas/{reservation}/pago', [PaymentController::class, 'show'])->name('reservas.pago');
    Route::post('/reservas/{reservation}/pago', [PaymentController::class, 'confirm'])->name('reservas.pago.confirm');

    // Favoritos
    Route::get('/favoritos', [FavoriteController::class, 'index'])->name('favoritos.index');
    Route::post('/favoritos/{vehicle}/toggle', [FavoriteController::class, 'toggle'])->name('favoritos.toggle');
});

// Administrador
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD básico
    Route::resource('categorias', CategoryController::class)
        ->parameters(['categorias' => 'category'])
        ->except(['show']);

    Route::get('vehiculos/vin/{vin}', [AdminVehicleController::class, 'vinLookup'])->name('vehiculos.vin');
    Route::resource('vehiculos', AdminVehicleController::class)
        ->parameters(['vehiculos' => 'vehicle'])
        ->except(['show']);

    Route::resource('extras', ExtraController::class)
        ->parameters(['extras' => 'extra'])
        ->except(['show']);

    // Reservas
    Route::get('reservas', [AdminReservationController::class, 'index'])->name('reservas.index');
    Route::get('reservas/exportar', [AdminReservationController::class, 'export'])->name('reservas.export');
    Route::get('reservas/{reservation}', [AdminReservationController::class, 'show'])->name('reservas.show');
    Route::patch('reservas/{reservation}/estado', [AdminReservationController::class, 'updateStatus'])->name('reservas.estado');

    // Usuarios
    Route::get('usuarios', [AdminUserController::class, 'index'])->name('usuarios.index');
    Route::get('usuarios/{user}', [AdminUserController::class, 'show'])->name('usuarios.show');
    Route::patch('usuarios/{user}/rol', [AdminUserController::class, 'updateRole'])->name('usuarios.rol');

    // Reportes
    Route::get('reportes', [ReportController::class, 'index'])->name('reportes.index');

    // Mantenimiento
    Route::get('vehiculos/{vehicle}/mantenimiento', [MaintenanceController::class, 'create'])->name('vehiculos.mantenimiento');
    Route::post('vehiculos/{vehicle}/mantenimiento', [MaintenanceController::class, 'store'])->name('vehiculos.mantenimiento.store');
});

require __DIR__.'/auth.php';
