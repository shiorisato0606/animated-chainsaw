@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品編集画面</h1>

    <form action="{{ route('entities.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group row mb-3">
            <label for="product_name" class="col-sm-2 col-form-label">商品名 <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" name="product_name" id="product_name" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name', $product->product_name) }}">
                @error('product_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="company_id" class="col-sm-2 col-form-label">メーカー名 <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <select name="company_id" id="company_id" class="form-control @error('company_id') is-invalid @enderror">
                    <option value="">メーカーを選択してください</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id', $product->company_id) == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
                @error('company_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="price" class="col-sm-2 col-form-label">価格 <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="stock" class="col-sm-2 col-form-label">在庫数 <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}">
                @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="comment" class="col-sm-2 col-form-label">コメント</label>
            <div class="col-sm-10">
                <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror">{{ old('comment', $product->comment) }}</textarea>
                @error('comment')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="image" class="col-sm-2 col-form-label">画像</label>
            <div class="col-sm-10">
                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('entities.products.show', $product->id) }}" class="btn btn-secondary ml-2">戻る</a>
    </form>
</div>
@endsection
