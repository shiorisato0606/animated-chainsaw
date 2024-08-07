@extends('layouts.app')

@section('content')
<div class="container">
    <h2>商品情報一覧画面</h2>
    <form action="{{ route('entities.products.index') }}" method="GET">
        @csrf
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
                            @isset($companies)
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name }} <!-- ここを修正 -->
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">検索</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫</th>
                <th>メーカー</th>
                <th>
                    <a href="{{ route('entities.products.create') }}" class="btn btn-success">新規登録</a>
                </th>
            </tr>
        </thead>
        <tbody>
            @isset($products)
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->img_path)
                                <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" style="width: 100px; height: auto;">
                            @else
                                <span>No Image</span>
                            @endif
                        </td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->company->company_name }}</td>
                        <td>
                            <a href="{{ route('entities.products.show', $product->id) }}" class="btn btn-info">詳細</a>
                            <form action="{{ route('entities.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7">商品が見つかりませんでした</td>
                </tr>
            @endisset
        </tbody>
    </table>
</div>
@endsection
