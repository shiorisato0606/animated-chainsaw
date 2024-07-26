@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>新規商品登録</h2>
        <form action="{{ route('entities.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- 商品名 --}}
            <div class="form-group row mb-3">
                <label for="product_name" class="col-sm-2 col-form-label">商品名</label>
                <div class="col-sm-10">
                    <input type="text" name="product_name" id="product_name" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name') }}" required>
                    @error('product_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- メーカー名 --}}
            <div class="form-group row mb-3">
                <label for="company_id" class="col-sm-2 col-form-label">メーカー名</label>
                <div class="col-sm-10">
                    <select name="company_id" id="company_id" class="form-control @error('company_id') is-invalid @enderror" required>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- 価格 --}}
            <div class="form-group row mb-3">
                <label for="price" class="col-sm-2 col-form-label">価格</label>
                <div class="col-sm-10">
                    <input type="text" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                    @error('price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- 在庫数 --}}
            <div class="form-group row mb-3">
                <label for="stock" class="col-sm-2 col-form-label">在庫数</label>
                <div class="col-sm-10">
                    <input type="text" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" required>
                    @error('stock')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- コメント --}}
            <div class="form-group row mb-3">
                <label for="comment" class="col-sm-2 col-form-label">コメント</label>
                <div class="col-sm-10">
                    <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror" rows="4">{{ old('comment') }}</textarea>
                    @error('comment')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- 商品画像 --}}
            <div class="form-group row mb-3">
                <label for="image" class="col-sm-2 col-form-label">商品画像</label>
                <div class="col-sm-10">
                    <input type="file" name="image" id="image" class="form-control-file @error('image') is-invalid @enderror">
                    @error('image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
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
