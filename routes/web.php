<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\HppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortfolioController;

use App\Http\Controllers\SaleController;
// ...existing code...

Route::get('/', [MenuController::class, 'index'])->name('home');
Route::get('/menu/{category:slug}', [MenuController::class, 'show'])->name('menu.show');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');

Route::middleware('auth')->group(function () {
    // Product Category CRUD
    Route::resource('categories', App\Http\Controllers\ProductCategoryController::class)->except(['show']);
    // Product CRUD
    Route::resource('products', App\Http\Controllers\ProductController::class)
    ->except(['show', 'create', 'index']);

    // Create product WITH category context
    Route::get('/products/create/{category:slug}',
        [App\Http\Controllers\ProductController::class, 'create']
        )->name('products.create.byCategory');

    // HPP Calculator routes
    Route::get('/hpp', [HppController::class, 'index'])->name('hpp.index');
    Route::get('/hpp/export', [HppController::class, 'export'])->name('hpp.export');
    Route::get('/hpp/show/{calculation}', [HppController::class, 'show'])->name('hpp.show');
    Route::get('/hpp/edit/{calculation}', [HppController::class, 'edit'])->name('hpp.edit');
    Route::put('/hpp/{calculation}', [HppController::class, 'update'])->name('hpp.update');
    Route::delete('/hpp/{calculation}', [HppController::class, 'destroy'])->name('hpp.destroy');
    Route::get('/hpp/{category}/{item}', [HppController::class, 'form'])->name('hpp.form');
    Route::post('/hpp/{category}/{item}', [HppController::class, 'store'])->name('hpp.store');

    // Sales routes
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/export', [SaleController::class, 'export'])->name('sales.export');
    Route::get('/sales/create/{calculation}', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales/{calculation}', [SaleController::class, 'store'])->name('sales.store');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');

    // ...existing code...
});
