<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EntityRequest;

class EntityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 会社関連メソッド
    public function showCompanies()
    {
        $companies = Company::all();
        return view('company.index', compact('companies'));
    }

    public function showCompany($id)
    {
        $company = Company::findOrFail($id);
        return view('company.show', compact('company'));
    }

    public function createCompany()
    {
        return view('company.create');
    }

    public function storeCompany(EntityRequest $request)
    {
        try {
            DB::beginTransaction();

            $company = new Company([
                'name' => $request->name,
                'address' => $request->address,
                'representative' => $request->representative,
            ]);

            $company->save();

            DB::commit();

            return redirect()->route('entities.companies.index')->with('success', '会社情報を登録しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => '会社情報の登録中にエラーが発生しました。']);
        }
    }

    public function editCompany($id)
    {
        $company = Company::findOrFail($id);
        return view('company.edit', compact('company'));
    }

    public function updateCompany(EntityRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $company = Company::findOrFail($id);

            $company->name = $request->name;
            $company->address = $request->address;
            $company->representative = $request->representative;

            $company->save();

            DB::commit();

            return redirect()->route('entities.companies.show', ['id' => $company->id])->with('success', '会社情報を更新しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => '会社情報の更新中にエラーが発生しました。']);
        }
    }

    public function destroyCompany($id)
    {
        try {
            DB::beginTransaction();

            $company = Company::findOrFail($id);
            $company->delete();

            DB::commit();

            return redirect()->route('entities.companies.index')->with('success', '会社情報を削除しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '会社情報の削除中にエラーが発生しました。']);
        }
    }

    // 商品関連メソッド
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
    


    public function showProduct($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('product.show', compact('product'));
    }

    public function createProduct()
    {
        $companies = Company::all();
        return view('product.create', compact('companies'));
    }

    public function storeProduct(EntityRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = new Product([
                'product_name' => $request->product_name,
                'price' => $request->price,
                'stock' => $request->stock,
                'company_id' => $request->company_id,
                'comment' => $request->comment,
                'img_path' => null,
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images', 'public');
                $product->img_path = $path;
            }

            $product->save();

            DB::commit();

            return redirect()->route('entities.products.index')->with('success', '商品を登録しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('entities.products.create')->withInput()->withErrors(['error' => '商品の登録中にエラーが発生しました。']);
        }
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('product.edit', compact('product', 'companies'));
    }

    public function updateProduct(EntityRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            $product->product_name = $request->input('product_name');
            $product->company_id = $request->input('company_id');
            $product->price = $request->input('price');
            $product->stock = $request->input('stock');
            $product->comment = $request->input('comment');

            if ($request->hasFile('image')) {
                if ($product->img_path) {
                    \Storage::disk('public')->delete($product->img_path);
                }
                $path = $request->file('image')->store('images', 'public');
                $product->img_path = $path;
            }

            $product->save();

            DB::commit();

            return redirect()->route('entities.products.show', ['id' => $product->id])->with('success', '商品情報を更新しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('entities.products.edit', ['id' => $id])->withInput()->withErrors(['error' => '商品情報の更新中にエラーが発生しました。']);
        }
    }

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

            return redirect()->route('entities.products.index')->with('success', '商品を削除しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '商品の削除中にエラーが発生しました。']);
        }
    }
}
