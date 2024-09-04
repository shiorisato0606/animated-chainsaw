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
        ]);

        $product = Product::find($validatedData['product_id']);

        if ($product->stock < 1) {
            return response()->json(['success' => false, 'error' => '在庫が不足しています'], 400);
        }

        DB::transaction(function () use ($product) {
            Sale::create([
                'product_id' => $product->id,
            ]);

            // 在庫を減算
            $product->stock -= 1;
            $product->save();
        });

        return response()->json(['success' => true]);
    }
}
