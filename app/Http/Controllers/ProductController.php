<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // 認証が必要な場合、全てのメソッドに適用されるミドルウェア
    }

    // 商品一覧表示
    public function index(Request $request)
    {
        $products = Product::query()
            ->with('company')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('product_name', 'like', '%' . $request->search . '%');
            })
            ->get();

        $companies = Company::all(); // companies を取得する

        return view('product.index', compact('products', 'companies')); // companies もビューに渡す
    }

    // 商品詳細表示
    public function show($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('product.show', compact('product'));
    }

    // 商品情報登録ページ表示
    public function create()
    {
        $companies = Company::all();
        return view('product.create', compact('companies'));
    }

    // 商品情報登録処理
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'company_id' => 'required|integer|exists:companies,id',
            'image' => 'nullable|image|max:2048',
        ]);

        // 商品情報を保存する処理
        $product = new Product($request->all());

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $product->image = $path;
        }

        $product->save();

        // 保存が完了した後に一覧画面にリダイレクトする
        return redirect()->route('products.index');
    }

    // 商品情報編集ページ表示
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('product.edit', compact('product', 'companies'));
    }

    // 商品情報更新処理
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'comment' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = Product::findOrFail($id);

        $product->product_name = $request->input('product_name');
        $product->company_id = $request->input('company_id');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->comment = $request->input('comment');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $product->img_path = $path;
        }

        $product->save();

        return redirect()->route('products.show', ['product' => $product->id])->with('success', '商品情報を更新しました。');
    }

    // 商品情報削除処理
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index');
    }
}
