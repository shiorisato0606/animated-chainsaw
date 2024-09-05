<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntityController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    // /home ルートをリダイレクト
    Route::get('/home', function () {
        return redirect()->route('entities.products.index');
    })->name('home');
    
    // 商品関連ルート
    Route::prefix('products')->group(function () {
        // 表示用
        Route::get('/', [EntityController::class, 'index'])->name('entities.products.index'); 
        Route::get('create', [EntityController::class, 'create'])->name('entities.products.create'); 
        Route::get('{id}', [EntityController::class, 'show'])->name('entities.products.show'); 
        
        // 編集用
        Route::get('{id}/edit', [EntityController::class, 'edit'])->name('entities.products.edit'); 

        // 更新用
        Route::post('/', [EntityController::class, 'store'])->name('entities.products.store'); 
        Route::put('{id}', [EntityController::class, 'update'])->name('entities.products.update'); 
        Route::delete('{id}', [EntityController::class, 'destroy'])->name('entities.products.destroy'); 
    });
});
