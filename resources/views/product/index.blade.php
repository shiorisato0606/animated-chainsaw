@extends('layouts.app')

@section('content')
<div class="container">
    <h2>商品情報一覧画面</h2>

    <!-- 検索フォーム -->
    <form id="searchForm" action="{{ route('entities.products.index') }}" method="GET">
        @csrf
        <div class="row">
            <!-- 1行目 -->
            <div class="col-md-4">
                <input type="text" name="search" id="keyword" class="form-control" placeholder="検索キーワード" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="company" id="company" class="form-control">
                    <option value="">メーカー名</option>
                    @isset($companies)
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>
                                {{ $company->company_name }} 
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>
        <div class="row mt-2">
            <!-- 2行目 -->
            <div class="col-md-4">
                <div class="input-group">
                    <input type="number" name="min_price" id="min_price" class="form-control" placeholder="最低価格" value="{{ request('min_price') }}">
                    <span class="input-group-text">〜</span>
                    <input type="number" name="max_price" id="max_price" class="form-control" placeholder="最高価格" value="{{ request('max_price') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="number" name="min_stock" id="min_stock" class="form-control" placeholder="最低在庫数" value="{{ request('min_stock') }}">
                    <span class="input-group-text">〜</span>
                    <input type="number" name="max_stock" id="max_stock" class="form-control" placeholder="最高在庫数" value="{{ request('max_stock') }}">
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">検索</button>
            </div>
        </div>
    </form>
    
    <!-- 商品一覧 -->
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th><a href="#" class="sort" data-order="{{ request('order') === 'asc' ? 'desc' : 'asc' }}" data-column="id">ID</a></th>
                <th>商品画像</th>
                <th><a href="#" class="sort" data-order="{{ request('order') === 'asc' ? 'desc' : 'asc' }}" data-column="product_name">商品名</a></th>
                <th><a href="#" class="sort" data-order="{{ request('order') === 'asc' ? 'desc' : 'asc' }}" data-column="price">価格</a></th>
                <th><a href="#" class="sort" data-order="{{ request('order') === 'asc' ? 'desc' : 'asc' }}" data-column="stock">在庫</a></th>
                <th>メーカー</th>
                <th>
                    <a href="{{ route('entities.products.create') }}" class="btn btn-success">新規登録</a>
                </th>
            </tr>
        </thead>
        <tbody id="productList">
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
                        <button class="btn btn-danger delete" data-url="{{ route('entities.products.destroy', $product->id) }}">削除</button>
                        <!-- 購入ボタンを追加 -->
                        <button class="btn btn-primary purchase-button" data-product-id="{{ $product->id }}">購入</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- jQuery and Ajax for Search, Sort, Delete, and Purchase -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // 非同期検索
    $('#searchForm').on('submit', function(event) {
        event.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: "{{ route('entities.products.index') }}?" + formData,
            type: "GET",
            success: function(response) {
                $('#productList').html($(response.html).find('#productList').html()); // 商品リストのみ更新
            }
        });
    });

    // ソート
    $('.sort').on('click', function(event) {
        event.preventDefault();
        let column = $(this).data('column');
        let order = $(this).data('order');
        let formData = $('#searchForm').serialize() + `&sort_by=${column}&order=${order}`;
        $.ajax({
            url: "{{ route('entities.products.index') }}?" + formData,
            type: "GET",
            success: function(response) {
                $('#productList').html($(response.html).find('#productList').html()); // 商品リストのみ更新
            }
        });
        $(this).data('order', order === 'asc' ? 'desc' : 'asc');
    });

    // 非同期削除
    $('#productList').on('click', '.delete', function(event) {
        event.preventDefault();
        if (!confirm('本当に削除しますか？')) {
            return;
        }
        let deleteUrl = $(this).data('url');
        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                alert(response.success);
                location.reload();
            }
        });
    });

   // 購入ボタンの処理
$('#productList').on('click', '.purchase-button', function(event) {
    event.preventDefault();
    let productId = $(this).data('product-id');
    let $stockCell = $(this).closest('tr').find('td').eq(4); // 在庫のセルを取得
    $.ajax({
        url: '{{ url("/api/purchase") }}',  // ここを修正
        type: 'POST',
        data: {
            product_id: productId,
            quantity: 1 // 必要に応じて数量を設定
        },
        success: function(response) {
            if (response.success) {
                alert('購入が完了しました');
                // 在庫数の更新
                let newStock = parseInt($stockCell.text()) - 1; // 在庫数を減らす
                $stockCell.text(newStock);

                // 在庫が0になったら、購入ボタンを無効にする
                if (newStock <= 0) {
                    $stockCell.closest('tr').find('.purchase-button').prop('disabled', true);
                }
            } else {
                alert('エラー: ' + response.error);
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('エラーが発生しました');
        }
    });
});

});
</script>
@endsection
