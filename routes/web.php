<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StyleController;
use App\Http\Controllers\ValorationController;
use Illuminate\Support\Facades\Route;

// Página de inicio
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Todas las rutas que requieren autenticación
Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/my-profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/login', [ProfileController::class, 'login'])->name('profile.login');
    Route::get('/register', [ProfileController::class, 'register'])->name('profile.register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // API FakeStore para traer productos por categoría
    Route::middleware(['auth'])->group(function () {
				Route::get('/products/api/by-category', [ProductController::class, 'getApiProductsByCategory'])->name('products.getApiProducts');
		});

				
				// Acciones adicionales de productos
				Route::post('/products/{product}/buy', [ProductController::class, 'buy'])->name('products.buy');
				Route::post('/products/{product}/valoration', [ProductController::class, 'addValoration'])->name('products.valoration');
				
				// Productos (Resource)
				Route::resource('products', ProductController::class);

    // Estilos
    Route::resource('styles', StyleController::class)->except(['show']);

    // Valoraciones
    Route::delete('/valorations/{valoration}', [ValorationController::class, 'destroy'])->name('valorations.destroy');
});

require __DIR__.'/auth.php';
