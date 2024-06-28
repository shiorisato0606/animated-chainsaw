<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company; // Companyモデルをインポートする

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $products = Product::query()
            ->with('company')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('product_name', 'like', '%' . $request->search . '%');
            })
            ->get();

        // 企業名のリストを取得
        $companies = Company::all(); // あるいは必要に応じて適切なクエリを使用

        return view('product.index', compact('products', 'companies'));
    }
}
