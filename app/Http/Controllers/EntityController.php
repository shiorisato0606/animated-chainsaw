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

    // 商品一覧表示 (index)
    public function index(Request $request)
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

    // 商品詳細表示 (show)
    public function show($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('product.show', compact('product'));
    }

    // 商品作成 (create)
    public function create()
    {
        $companies = Company::all();
        return view('product.create', compact('companies'));
    }

    // 商品編集 (edit)
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('product.edit', compact('product', 'companies'));
    }

    // 商品登録 (store)
    public function store(EntityRequest $request)
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

    // 商品更新 (update)
    public function update(EntityRequest $request, $id)
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

    // 商品削除 (destroy)
    public function destroy($id)
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
