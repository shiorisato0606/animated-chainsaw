@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>新規商品登録</h2>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- 商品名 --}}
            <div class="form-group row mb-3">
                <label for="product_name" class="col-sm-2 col-form-label">商品名</label>
                <div class="col-sm-10">
                    <input type="text" name="product_name" id="product_name" class="form-control" required>
                </div>
            </div>

            {{-- メーカー名 --}}
            <div class="form-group row mb-3">
                <label for="company_id" class="col-sm-2 col-form-label">メーカー名</label>
                <div class="col-sm-10">
                    <select name="company_id" id="company_id" class="form-control" required>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- 価格 --}}
            <div class="form-group row mb-3">
                <label for="price" class="col-sm-2 col-form-label">価格</label>
                <div class="col-sm-10">
                    <input type="text" name="price" id="price" class="form-control" required>
                </div>
            </div>

            {{-- 在庫数 --}}
            <div class="form-group row mb-3">
                <label for="stock" class="col-sm-2 col-form-label">在庫数</label>
                <div class="col-sm-10">
                    <input type="text" name="stock" id="stock" class="form-control" required>
                </div>
            </div>

            {{-- 商品画像 --}}
            <div class="form-group row mb-3">
                <label for="image" class="col-sm-2 col-form-label">商品画像</label>
                <div class="col-sm-10">
                    <input type="file" name="image" id="image" class="form-control-file">
                </div>
            </div>

            {{-- ボタン群 --}}
            <div class="form-group row mb-3">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">登録</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary ml-2">戻る</a>
                </div>
            </div>
        </form>
    </div>
@endsection
