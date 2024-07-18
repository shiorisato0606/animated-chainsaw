<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品情報詳細画面</title>
    <style>
        .title-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .title {
            font-size: 2em;
            margin: 20px 0;
            padding-left: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .product-image {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .product-image label {
            font-weight: bold;
            margin-right: 10px;
        }
        .product-image img {
            max-width: 100%;
            height: auto;
        }
        .product-details {
            margin-bottom: 20px;
        }
        .product-details label {
            font-weight: bold;
            margin-right: 10px;
        }
        .buttons {
            text-align: center;
            margin-top: 20px;
        }
        .buttons a {
            margin: 0 10px;
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }
        .buttons a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="title-container">
        <h1 class="title">商品詳細画面</h1>
    </div>
    <div class="container">
        <div class="product-details">
        @if(isset($product))
    <p><label>ID:</label>{{ $product->id }}</p>
    @if($product->img_path)
        <div class="product-image">
            <label>商品画像:</label>
            <img src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->product_name }}" style="max-width: 100px; height: auto;">
        </div>
    @else
        <p><label>商品画像:</label>No Image</p>
    @endif
    <p><label>商品名:</label>{{ $product->product_name }}</p>
    @if($product->company)
        <p><label>メーカー名:</label>{{ $product->company->company_name }}</p>
    @endif
    <p><label>価格:</label>{{ $product->price }}円</p>
    <p><label>在庫数:</label>{{ $product->stock }}個</p>
    <p><label>コメント:</label>{{ $product->comment }}</p>
@else
    <p>商品情報が見つかりませんでした。</p>
@endif

        </div>

        <div class="buttons">
        @if(isset($product))
    <a href="{{ route('products.edit', ['id' => $product->id]) }}">編集</a>
@endif

            <a href="{{ route('products.index') }}">戻る</a>
        </div>
    </div>
</body>
</html>
