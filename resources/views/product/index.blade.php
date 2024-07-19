@extends('layouts.app')

@section('content')
<div class="container">
    <h2>商品情報一覧</h2>
    <form action="{{ route('products.index') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" name="search" id="keyword" class="form-control" placeholder="検索キーワード" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <select name="company" id="company" class="form-control">
                            <option value="">メーカー名</option>
                            @forelse($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                            @empty
                                <option value="">No companies available</option>
                            @endforelse
                        </select>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">検索</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- 商品一覧の表示部分を追加 -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫</th>
                <th>メーカー</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->company->company_name }}</td>
                    <td>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info">詳細</a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">編集</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">削除</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">商品が見つかりませんでした</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
