<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($validatedData['product_id']);

        if ($product->stock < $validatedData['quantity']) {
            return response()->json(['success' => false, 'error' => '在庫が不足しています'], 400);
        }

        DB::transaction(function () use ($product, $validatedData) {
            // salesテーブルにデータを追加
            Sale::create([
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity'],
                'total_price' => $product->price * $validatedData['quantity'],
            ]);

            // 在庫を減算
            $product->stock -= $validatedData['quantity'];
            $product->save();
        });

        return response()->json(['success' => true]);
    }
}
