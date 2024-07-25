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

    protected function handleEntity($entity, $id = null, $request = null)
    {
        $model = $this->getModel($entity);
        $data = [];

        if ($id) {
            $entityInstance = $model::findOrFail($id);
        } else {
            $entityInstance = new $model;
        }

        if ($request) {
            if ($entity === 'products' && $request->hasFile('image')) {
                if ($id && $entityInstance->img_path) {
                    \Storage::disk('public')->delete($entityInstance->img_path);
                }
                $path = $request->file('image')->store('images', 'public');
                $data['img_path'] = $path;
            }

            $entityInstance->fill($request->all() + $data);
            $entityInstance->save();
        }

        return $entityInstance;
    }

    public function showCompanies()
    {
        $companies = Company::all();
        return view('company.index', compact('companies'));
    }

    public function showCompany($id)
    {
        $company = $this->handleEntity('companies', $id);
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
            $this->handleEntity('companies', null, $request);
            DB::commit();
            return redirect()->route('entities.showCompanies')->with('success', '会社情報を登録しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => '会社情報の登録中にエラーが発生しました。']);
        }
    }

    public function editCompany($id)
    {
        $company = $this->handleEntity('companies', $id);
        return view('company.edit', compact('company'));
    }

    public function updateCompany(EntityRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $this->handleEntity('companies', $id, $request);
            DB::commit();
            return redirect()->route('entities.showCompany', ['id' => $id])->with('success', '会社情報を更新しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => '会社情報の更新中にエラーが発生しました。']);
        }
    }

    public function destroyCompany($id)
    {
        try {
            DB::beginTransaction();
            $company = $this->handleEntity('companies', $id);
            $company->delete();
            DB::commit();
            return redirect()->route('entities.showCompanies')->with('success', '会社情報を削除しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '会社情報の削除中にエラーが発生しました。']);
        }
    }

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

        // 商品の新規登録
        $product = $this->handleEntity('products', null, $request);

        DB::commit();

        // 登録した商品の詳細画面にリダイレクト
        return redirect()->route('entities.products.index', ['id' => $product->id])
                         ->with('success', '商品が正常に登録されました。');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->withErrors(['error' => '商品登録中にエラーが発生しました。']);
    }
}



    public function editProduct($id)
    {
        $product = $this->handleEntity('products', $id);
        $companies = Company::all();
        return view('product.edit', compact('product', 'companies'));
    }

    public function updateProduct(EntityRequest $request, $id)
{
    try {
        DB::beginTransaction();
        $product = $this->handleEntity('products', $id, $request);
        DB::commit();
        return redirect()->route('entities.products.show', ['id' => $product->id])
                         ->with('success', '商品情報が更新されました。');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->withErrors(['error' => '商品情報の更新中にエラーが発生しました。']);
    }
}


    public function destroyProduct($id)
    {
        try {
            DB::beginTransaction();
            $product = $this->handleEntity('products', $id);
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

    protected function getModel($entity)
    {
        switch ($entity) {
            case 'products':
                return Product::class;
            case 'companies':
                return Company::class;
            default:
                abort(404, 'Entity not found.');
        }
    }
}
