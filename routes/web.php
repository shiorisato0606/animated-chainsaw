<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    // ダッシュボード用のルート（HomeControllerのindexメソッド）
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // 商品一覧画面用のルート
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    
    // 商品登録フォームの表示と処理
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    
    // 商品詳細表示と編集フォームの表示と処理
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    
    // 商品削除処理
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});
