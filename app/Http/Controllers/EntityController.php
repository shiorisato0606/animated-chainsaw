<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\EntityRequest;

class EntityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 会社関連

    public function showCompanies()
    {
        return view('company.index', ['companies' => Company::all()]);
    }

    public function showCompany($id)
    {
        return view('company.show', ['company' => Company::findOrFail($id)]);
    }

    public function createCompany()
    {
        return view('company.create');
    }

    public function storeCompany(EntityRequest $request)
    {
        return $this->storeEntity(Company::class, $request, '会社情報を登録しました。', '会社情報の登録中にエラーが発生しました。');
    }

    public function editCompany($id)
    {
        return view('company.edit', ['company' => Company::findOrFail($id)]);
    }

    public function updateCompany(EntityRequest $request, $id)
    {
        return $this->updateEntity(Company::class, $request, $id, '会社情報を更新しました。', '会社情報の更新中にエラーが発生しました。');
    }

    public function destroyCompany($id)
    {
        return $this->destroyEntity(Company::class, $id, '会社情報を削除しました。', '会社情報の削除中にエラーが発生しました。');
    }

    // 商品関連

    public function showProducts(Request $request)
    {
        $products = Product::with('company')
            ->when($request->input('search'), fn($query, $search) => $query->where('product_name', 'like', "%{$search}%"))
            ->when($request->input('company'), fn($query, $company) => $query->where('company_id', $company))
            ->get();
        
        return view('product.index', [
            'products' => $products,
            'companies' => Company::all()
        ]);
    }

    public function showProduct($id)
    {
        return view('product.show', ['product' => Product::with('company')->findOrFail($id)]);
    }

    public function createProduct()
    {
        return view('product.create', ['companies' => Company::all()]);
    }

    public function storeProduct(EntityRequest $request)
    {
        return $this->storeEntity(Product::class, $request, '商品を登録しました。', '商品情報の登録中にエラーが発生しました。', ['file' => 'image']);
    }

    public function editProduct($id)
    {
        return view('product.edit', [
            'product' => Product::findOrFail($id),
            'companies' => Company::all()
        ]);
    }

    public function updateProduct(EntityRequest $request, $id)
    {
        return $this->updateEntity(Product::class, $request, $id, '商品情報を更新しました。', '商品情報の更新中にエラーが発生しました。', ['file' => 'image']);
    }

    public function destroyProduct($id)
    {
        return $this->destroyEntity(Product::class, $id, '商品を削除しました。', '商品情報の削除中にエラーが発生しました。');
    }

    // 共通メソッド

    protected function storeEntity($model, Request $request, $successMessage, $errorMessage, $additionalData = [])
    {
        try {
            DB::beginTransaction();
            $entity = $model::create($request->only($model::getFillable()));
            if (isset($additionalData['file']) && $request->hasFile($additionalData['file'])) {
                $path = $request->file($additionalData['file'])->store('images', 'public');
                $entity->img_path = $path;
                $entity->save();
            }
            DB::commit();
            return redirect()->route('entities.' . strtolower(class_basename($model)) . '.index')->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Entity store error: ' . $e->getMessage());
            return back()->withInput()->withErrors($errorMessage);
        }
    }

    protected function updateEntity($model, Request $request, $id, $successMessage, $errorMessage, $additionalData = [])
    {
        try {
            DB::beginTransaction();
            $entity = $model::findOrFail($id);
            $entity->update($request->only($model::getFillable()));
            if (isset($additionalData['file']) && $request->hasFile($additionalData['file'])) {
                if ($entity->img_path) {
                    Storage::disk('public')->delete($entity->img_path);
                }
                $path = $request->file($additionalData['file'])->store('images', 'public');
                $entity->img_path = $path;
                $entity->save();
            }
            DB::commit();
            return redirect()->route('entities.' . strtolower(class_basename($model)) . '.show', ['id' => $entity->id])->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Entity update error: ' . $e->getMessage());
            return redirect()->route('entities.' . strtolower(class_basename($model)) . '.edit', ['id' => $id])->withInput()->withErrors($errorMessage);
        }
    }

    protected function destroyEntity($model, $id, $successMessage, $errorMessage)
    {
        try {
            DB::beginTransaction();
            $entity = $model::findOrFail($id);
            if ($entity->img_path) {
                Storage::disk('public')->delete($entity->img_path);
            }
            $entity->delete();
            DB::commit();
            return redirect()->route('entities.' . strtolower(class_basename($model)) . '.index')->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Entity destroy error: ' . $e->getMessage());
            return back()->withErrors($errorMessage);
        }
    }
}
