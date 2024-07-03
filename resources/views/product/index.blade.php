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
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">検索</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>商品画像</th>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>在庫数</th>
                            <th>メーカー名</th>
                            <th>
                                <a href="{{ route('products.create') }}" class="btn btn-success">新規登録</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="商品画像" style="max-width: 100px;">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->company->company_name }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info" style="margin-right: 10px;">詳細</a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
