<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        try {
            // トランザクションを使用して処理を管理
            DB::transaction(function () use ($validatedData) {
                $product = Product::find($validatedData['product_id']);

                if (!$product) {
                    throw new \Exception('商品が見つかりません');
                }

                if ($product->stock < 1) {
                    throw new \Exception('在庫が不足しています');
                }

                // 購入処理を行う
                Sale::create([
                    'product_id' => $product->id,
                ]);

                // 在庫を減算
                $product->decrement('stock');
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // エラーログを記録
            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
