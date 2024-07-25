<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // 商品関連
    Route::prefix('products')->group(function () {
        Route::get('/', [EntityController::class, 'showProducts'])->name('entities.products.index');
        Route::get('create', [EntityController::class, 'createProduct'])->name('entities.products.create');
        Route::post('/', [EntityController::class, 'storeProduct'])->name('entities.products.store');
        Route::get('{id}/edit', [EntityController::class, 'editProduct'])->name('entities.products.edit');
        Route::put('{id}', [EntityController::class, 'updateProduct'])->name('entities.products.update');
        Route::delete('{id}', [EntityController::class, 'destroyProduct'])->name('entities.products.destroy');
        Route::get('{id}', [EntityController::class, 'showProduct'])->name('entities.products.show');
    });

    // 会社関連
    Route::prefix('companies')->group(function () {
        Route::get('/', [EntityController::class, 'showCompanies'])->name('entities.companies.index');
        Route::get('create', [EntityController::class, 'createCompany'])->name('entities.companies.create');
        Route::post('/', [EntityController::class, 'storeCompany'])->name('entities.companies.store');
        Route::get('{id}/edit', [EntityController::class, 'editCompany'])->name('entities.companies.edit');
        Route::put('{id}', [EntityController::class, 'updateCompany'])->name('entities.companies.update');
        Route::delete('{id}', [EntityController::class, 'destroyCompany'])->name('entities.companies.destroy');
        Route::get('{id}', [EntityController::class, 'showCompany'])->name('entities.companies.show');
    });
});
