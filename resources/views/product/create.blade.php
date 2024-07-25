@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>新規商品登録</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('entities.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- 商品名 --}}
            <div class="form-group row mb-3">
                <label for="product_name" class="col-sm-2 col-form-label">商品名</label>
                <div class="col-sm-10">
                    <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name') }}" required>
                    @if ($errors->has('product_name'))
                        <p class="text-danger">{{ $errors->first('product_name') }}</p>
                    @endif
                </div>
            </div>

            {{-- メーカー名 --}}
            <div class="form-group row mb-3">
                <label for="company_id" class="col-sm-2 col-form-label">メーカー名</label>
                <div class="col-sm-10">
                    <select name="company_id" id="company_id" class="form-control" required>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->company_name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('company_id'))
                        <p class="text-danger">{{ $errors->first('company_id') }}</p>
                    @endif
                </div>
            </div>

            {{-- 価格 --}}
            <div class="form-group row mb-3">
                <label for="price" class="col-sm-2 col-form-label">価格</label>
                <div class="col-sm-10">
                    <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
                    @if ($errors->has('price'))
                        <p class="text-danger">{{ $errors->first('price') }}</p>
                    @endif
                </div>
            </div>

            {{-- 在庫数 --}}
            <div class="form-group row mb-3">
                <label for="stock" class="col-sm-2 col-form-label">在庫数</label>
                <div class="col-sm-10">
                    <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock') }}" required>
                    @if ($errors->has('stock'))
                        <p class="text-danger">{{ $errors->first('stock') }}</p>
                    @endif
                </div>
            </div>

            {{-- コメント --}}
            <div class="form-group row mb-3">
                <label for="comment" class="col-sm-2 col-form-label">コメント</label>
                <div class="col-sm-10">
                    <textarea name="comment" id="comment" class="form-control" rows="3">{{ old('comment') }}</textarea>
                    @if ($errors->has('comment'))
                        <p class="text-danger">{{ $errors->first('comment') }}</p>
                    @endif
                </div>
            </div>

            {{-- 商品画像 --}}
            <div class="form-group row mb-3">
                <label for="image" class="col-sm-2 col-form-label">商品画像</label>
                <div class="col-sm-10">
                    <input type="file" name="image" id="image" class="form-control-file">
                    @if ($errors->has('image'))
                        <p class="text-danger">{{ $errors->first('image') }}</p>
                    @endif
                </div>
            </div>

            {{-- ボタン群 --}}
            <div class="form-group row mb-3">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">登録</button>
                    <a href="{{ route('entities.products.index') }}" class="btn btn-secondary ml-2">戻る</a>
                </div>
            </div>
        </form>
    </div>
@endsection
