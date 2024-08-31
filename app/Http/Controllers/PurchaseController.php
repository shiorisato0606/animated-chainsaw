<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // 商品の取得
            $product = Product::findOrFail($validated['product_id']);

            // 在庫の確認
            if ($product->stock < $validated['quantity']) {
                return response()->json(['error' => '在庫が不足しています。'], 400);
            }

            // salesテーブルにレコードを追加
            Sales::create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'total_price' => $product->price * $validated['quantity'],
            ]);

            // productsテーブルの在庫数を減算
            $product->stock -= $validated['quantity'];
            $product->save();

            DB::commit();

            return response()->json(['success' => '購入処理が完了しました。'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '購入処理中にエラーが発生しました。'], 500);
        }
    }
}
