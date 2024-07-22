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
    Route::get('entities/products/create', [EntityController::class, 'createProduct'])->name('entities.createProduct');
    Route::post('entities/products', [EntityController::class, 'storeProduct'])->name('entities.storeProduct');
    Route::get('entities/products/{id}/edit', [EntityController::class, 'editProduct'])->name('entities.editProduct');
    Route::put('entities/products/{id}', [EntityController::class, 'updateProduct'])->name('entities.updateProduct');
    Route::delete('entities/products/{id}', [EntityController::class, 'destroyProduct'])->name('entities.destroyProduct');
    Route::get('entities/products/{id}', [EntityController::class, 'showProduct'])->name('entities.showProduct');
    Route::get('entities/products', [EntityController::class, 'showProducts'])->name('entities.showProducts'); // 商品一覧

    // 会社関連
    Route::get('entities/companies', [EntityController::class, 'showCompanies'])->name('entities.showCompanies');
    Route::get('entities/companies/create', [EntityController::class, 'createCompany'])->name('entities.createCompany');
    Route::post('entities/companies', [EntityController::class, 'storeCompany'])->name('entities.storeCompany');
    Route::get('entities/companies/{id}/edit', [EntityController::class, 'editCompany'])->name('entities.editCompany');
    Route::put('entities/companies/{id}', [EntityController::class, 'updateCompany'])->name('entities.updateCompany');
    Route::delete('entities/companies/{id}', [EntityController::class, 'destroyCompany'])->name('entities.destroyCompany');
    Route::get('entities/companies/{id}', [EntityController::class, 'showCompany'])->name('entities.showCompany');
});
