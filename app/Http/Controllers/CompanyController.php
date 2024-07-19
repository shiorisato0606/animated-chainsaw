<?php
namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyUpdateRequest;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // 認証が必要な場合、全てのメソッドに適用されるミドルウェア
    }

    // 会社一覧表示
    public function index()
    {
        $companies = Company::all();
        return view('company.index', compact('companies'));
    }

    // 会社詳細表示
    public function show($id)
    {
        $company = Company::findOrFail($id);
        return view('company.show', compact('company'));
    }

    // 会社登録ページ表示
    public function create()
    {
        return view('company.create');
    }

    // 会社登録処理
    public function store(CompanyCreateRequest $request)
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

            return redirect()->route('companies.index')->with('success', '会社情報を登録しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => '会社情報の登録中にエラーが発生しました。']);
        }
    }

    // 会社編集ページ表示
    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('company.edit', compact('company'));
    }

    // 会社更新処理
    public function update(CompanyUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $company = Company::findOrFail($id);

            $company->name = $request->name;
            $company->address = $request->address;
            $company->representative = $request->representative;

            $company->save();

            DB::commit();

            return redirect()->route('companies.show', ['company' => $company->id])->with('success', '会社情報を更新しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => '会社情報の更新中にエラーが発生しました。']);
        }
    }

    // 会社削除処理
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $company = Company::findOrFail($id);
            $company->delete();

            DB::commit();

            return redirect()->route('companies.index')->with('success', '会社情報を削除しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '会社情報の削除中にエラーが発生しました。']);
        }
    }
}
