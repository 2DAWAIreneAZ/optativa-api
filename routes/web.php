<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StyleController;
use App\Http\Controllers\ValorationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rutas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/my-profile', [ProfileController::class, 'show'])->name('profile.show');
		Route::get('/login', [ProfileController::class, 'login'])->name('profile.login');
		Route::get('/register', [ProfileController::class, 'register'])->name('profile.register');
		Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de productos
    Route::get('/products/api/by-category', [ProductController::class, 'getApiProductsByCategory'])->name('products.getApiProducts');
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/buy', [ProductController::class, 'buy'])->name('products.buy');
    Route::post('/products/{product}/valoration', [ProductController::class, 'addValoration'])->name('products.valoration');
    
    // Rutas de estilos
    Route::resource('styles', StyleController::class)->except(['show']);
    
    // Rutas de valoraciones
    Route::delete('/valorations/{valoration}', [ValorationController::class, 'destroy'])->name('valorations.destroy');
});

// Rutas de la tienda (usuarios)
Route::middleware('auth')->group(function () {
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');
    Route::post('/shop/{product}/valoration', [ShopController::class, 'addValoration'])->name('shop.valoration');
    Route::post('/shop/{product}/buy', [ShopController::class, 'buy'])->name('shop.buy');
});

// Rutas de administración (solo admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', AdminProductController::class);
});

require __DIR__.'/auth.php';