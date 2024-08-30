<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 商品一覧表示 (index)
    public function index(Request $request)
    {
        $search = $request->input('search');
        $company = $request->input('company');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $minStock = $request->input('min_stock');
        $maxStock = $request->input('max_stock');
        $sortBy = $request->input('sort_by') ?? 'id';
        $order = $request->input('order') ?? 'desc';

        // 商品データの取得
        $products = Product::with('company')
            ->when($search, function($query, $search) {
                return $query->where('product_name', 'like', "%{$search}%");
            })
            ->when($company, function($query, $company) {
                return $query->where('company_id', $company);
            })
            ->when($minPrice, function($query, $minPrice) {
                return $query->where('price', '>=', $minPrice);
            })
            ->when($maxPrice, function($query, $maxPrice) {
                return $query->where('price', '<=', $maxPrice);
            })
            ->when($minStock, function($query, $minStock) {
                return $query->where('stock', '>=', $minStock);
            })
            ->when($maxStock, function($query, $maxStock) {
                return $query->where('stock', '<=', $maxStock);
            })
            ->orderBy($sortBy, $order)
            ->get();

        // メーカーリストの取得
        $companies = Company::all();

        if ($request->ajax()) {
            return view('product.index', compact('products'))->render();
        }

        return view('product.index', compact('products', 'companies'));
    }

    // 商品詳細表示 (show)
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product.show', compact('product'));
    }

    // 商品新規作成 (create)
    public function create()
    {
        $companies = Company::all();
        return view('product.create', compact('companies'));
    }

    // 商品新規登録処理 (store)
    public function store(Request $request)
    {
        // 商品の登録処理
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'comment' => 'nullable|string|max:1000',
            'img_path' => 'nullable|image|max:1024',
        ]);

        $product = Product::create($validated);

        return redirect()->route('entities.products.index')->with('success', '商品が登録されました');
    }

    // 商品削除処理 (destroy)
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['success' => '商品が削除されました']);
    }
}
