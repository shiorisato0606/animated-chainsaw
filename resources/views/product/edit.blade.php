@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="card-header">商品編集</h1>
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group row mb-4">
                            <label for="id" class="col-md-4 col-form-label text-md-right">ID</label>
                            <div class="col-md-6">
                                <input id="id" type="text" class="form-control-plaintext" name="id" value="{{ $product->id }}" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="product_name" class="col-md-4 col-form-label text-md-right">商品名<span style="color: red;">※</span></label>
                            <div class="col-md-6">
                                <input id="product_name" type="text" class="form-control @error('product_name') is-invalid @enderror" name="product_name" value="{{ old('product_name', $product->product_name) }}" required autocomplete="product_name" autofocus>
                                @error('product_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="company_id" class="col-md-4 col-form-label text-md-right">メーカー名<span style="color: red;">※</span></label>
                            <div class="col-md-6">
                                <select id="company_id" class="form-control @error('company_id') is-invalid @enderror" name="company_id" required>
                                    <option value="">選択してください</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id', $product->company_id) == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="price" class="col-md-4 col-form-label text-md-right">価格<span style="color: red;">※</span></label>
                            <div class="col-md-6">
                                <input id="price" type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $product->price) }}" required autocomplete="price">
                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="stock" class="col-md-4 col-form-label text-md-right">在庫数<span style="color: red;">※</span></label>
                            <div class="col-md-6">
                                <input id="stock" type="text" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock', $product->stock) }}" required autocomplete="stock">
                                @error('stock')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="comment" class="col-md-4 col-form-label text-md-right">コメント</label>
                            <div class="col-md-6">
                                <textarea id="comment" class="form-control @error('comment') is-invalid @enderror" name="comment" rows="4">{{ old('comment', $product->comment) }}</textarea>
                                @error('comment')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="image" class="col-md-4 col-form-label text-md-right">商品画像</label>
                            <div class="col-md-6">
                                <input id="image" type="file" class="form-control-file @error('image') is-invalid @enderror" name="image">
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                @if ($product->img_path)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->product_name }}" style="max-width: 100%; height: auto;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    更新する
                                </button>
                                <a href="{{ route('products.show', ['id' => $product->id]) }}" class="btn btn-secondary">
                                 戻る
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
