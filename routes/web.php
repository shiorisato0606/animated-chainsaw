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

    // 商品関連ルート
    Route::prefix('products')->group(function () {
        // 表示用
        Route::get('/', [EntityController::class, 'index'])->name('entities.products.index'); // 商品一覧表示
        Route::get('create', [EntityController::class, 'create'])->name('entities.products.create'); // 新規商品登録画面
        Route::get('{id}', [EntityController::class, 'show'])->name('entities.products.show'); // 商品詳細表示

        // 編集用
        Route::get('{id}/edit', [EntityController::class, 'edit'])->name('entities.products.edit'); // 商品編集画面

        // 更新用
        Route::post('/', [EntityController::class, 'store'])->name('entities.products.store'); // 商品登録
        Route::put('{id}', [EntityController::class, 'update'])->name('entities.products.update'); // 商品更新
        Route::delete('{id}', [EntityController::class, 'destroy'])->name('entities.products.destroy'); // 商品削除
    });
});
