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
        Route::get('/', [EntityController::class, 'show'])->name('entities.products.index');
        Route::get('create', [EntityController::class, 'create'])->name('entities.products.create');
        Route::post('/', [EntityController::class, 'store'])->name('entities.products.store');
        Route::get('{id}/edit', [EntityController::class, 'edit'])->name('entities.products.edit');
        Route::put('{id}', [EntityController::class, 'update'])->name('entities.products.update');
        Route::delete('{id}', [EntityController::class, 'destroy'])->name('entities.products.destroy');
        Route::get('{id}', [EntityController::class, 'show'])->name('entities.products.show');
        Route::get('/', [EntityController::class, 'index'])->name('entities.products.index');
    });

    // 会社関連
    Route::prefix('companies')->group(function () {
        Route::get('/', [EntityController::class, 'show'])->name('entities.companies.index');
        Route::get('create', [EntityController::class, 'create'])->name('entities.companies.create');
        Route::post('/', [EntityController::class, 'store'])->name('entities.companies.store');
        Route::get('{id}/edit', [EntityController::class, 'edit'])->name('entities.companies.edit');
        Route::put('{id}', [EntityController::class, 'update'])->name('entities.companies.update');
        Route::delete('{id}', [EntityController::class, 'destroy'])->name('entities.companies.destroy');
        Route::get('{id}', [EntityController::class, 'show'])->name('entities.companies.show');
    });
});
