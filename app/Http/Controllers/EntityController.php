<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EntityRequest;

class EntityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 会社一覧表示
    public function showCompanies()
    {
        $companies = Company::all();
        return view('company.index', compact('companies'));
    }

    // 会社詳細表示
    public function showCompany($id)
    {
        $company = Company::findOrFail($id);
        return view('company.show', compact('company'));
    }

    // 会社登録ページ表示
    public function createCompany()
    {
        return view('company.create');
    }

    // 会社登録処理
    public function storeCompany(EntityRequest $request)
    {
        try {
            DB::beginTransaction();

            $company = Company::create($request->only(['name', 'address', 'representative']));

            DB::commit();

            return redirect()->route('entities.showCompanies')->with('success', '会社情報を登録しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => '会社情報の登録中にエラーが発生しました。']);
        }
    }

    // 会社編集ページ表示
    public function editCompany($id)
    {
        $company = Company::findOrFail($id);
        return view('company.edit', compact('company'));
    }

    // 会社更新処理
    public function updateCompany(EntityRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $company = Company::findOrFail($id);
            $company->update($request->only(['name', 'address', 'representative']));

            DB::commit();

            return redirect()->route('entities.showCompany', ['id' => $company->id])->with('success', '会社情報を更新しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => '会社情報の更新中にエラーが発生しました。']);
        }
    }

    // 会社削除処理
    public function destroyCompany($id)
    {
        try {
            DB::beginTransaction();

            $company = Company::findOrFail($id);
            $company->delete();

            DB::commit();

            return redirect()->route('entities.showCompanies')->with('success', '会社情報を削除しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '会社情報の削除中にエラーが発生しました。']);
        }
    }

    // 編集ページ表示
    public function edit($id, $type)
    {
        if ($type == 'product') {
            $entity = Product::findOrFail($id);
            $view = 'product.edit';
        } elseif ($type == 'company') {
            $entity = Company::findOrFail($id);
            $view = 'company.edit';
        } else {
            abort(404, 'Entity type not found.');
        }

        return view($view, [
            'entity' => $entity,
            'type' => $type
        ]);
    }

    // 商品一覧表示
    public function showProducts(Request $request)
    {
        $search = $request->input('search');
        $company = $request->input('company');
        
        $products = Product::with('company')
            ->when($search, function($query, $search) {
                return $query->where('product_name', 'like', "%{$search}%");
            })
            ->when($company, function($query, $company) {
                return $query->where('company_id', $company);
            })
            ->get();
        
        $companies = Company::all();

        return view('product.index', compact('products', 'companies'));
    }

// 商品詳細表示
public function showProduct($id)
{
    $entity = Product::with('company')->findOrFail($id);
    return view('product.show', compact('entity'));
}


    // 商品情報登録ページ表示
    public function createProduct()
    {
        $companies = Company::all();
        return view('product.create', compact('companies'));
    }

    // 商品情報登録処理
    public function storeProduct(EntityRequest $request)
{
    try {
        DB::beginTransaction();

        $product = Product::create($request->only(['product_name', 'price', 'stock', 'company_id', 'comment']));

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $product->img_path = $path;
            $product->save(); // 画像パスを保存
        }

        DB::commit();

        return redirect()->route('entities.showProducts')->with('success', '商品を登録しました。');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('entities.createProduct')->withInput()->withErrors(['error' => '商品の登録中にエラーが発生しました。']);
    }
}

    // 商品情報編集ページ表示
public function editProduct($id)
{
    $entity = Product::findOrFail($id);
    $companies = Company::all();
    return view('product.edit', compact('entity', 'companies'));
}



    // 商品情報更新処理
    public function updateProduct(EntityRequest $request, $id)
{
    try {
        DB::beginTransaction();

        $product = Product::findOrFail($id);
        $product->update($request->only(['product_name', 'price', 'stock', 'company_id', 'comment']));

        if ($request->hasFile('image')) {
            // 古い画像が存在する場合、削除する
            if ($product->img_path) {
                \Storage::disk('public')->delete($product->img_path);
            }
            // 新しい画像を保存する
            $path = $request->file('image')->store('images', 'public');
            $product->img_path = $path;
            $product->save(); // 画像パスを保存
        }

        DB::commit();

        return redirect()->route('entities.showProduct', ['id' => $product->id])->with('success', '商品情報を更新しました。');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('entities.editProduct', ['id' => $id])->withInput()->withErrors(['error' => '商品情報の更新中にエラーが発生しました。']);
    }
}

    // 商品情報削除処理
    public function destroyProduct($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            if ($product->img_path) {
                \Storage::disk('public')->delete($product->img_path);
            }

            $product->delete();

            DB::commit();

            return redirect()->route('entities.showProducts')->with('success', '商品を削除しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '商品の削除中にエラーが発生しました。']);
        }
    }
}
