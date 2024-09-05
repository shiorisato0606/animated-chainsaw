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
                    <td class="stock">{{ $product->stock }}</td>
                    <td>{{ $product->company->company_name }}</td>
                    <td>
                        <a href="{{ route('entities.products.show', $product->id) }}" class="btn btn-info">詳細</a>
                        <button class="btn btn-danger delete" data-url="{{ route('entities.products.destroy', $product->id) }}">削除</button>
                        <button class="btn btn-primary purchase-button" data-product-id="{{ $product->id }}">購入</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#searchForm').on('submit', function(event) {
        event.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: "{{ route('entities.products.index') }}?" + formData,
            type: "GET",
            success: function(response) {
                $('#productList').html($(response.html).find('#productList').html());
            }
        });
    });

    $('.sort').on('click', function(event) {
        event.preventDefault();
        let column = $(this).data('column');
        let order = $(this).data('order');
        let formData = $('#searchForm').serialize() + `&sort_by=${column}&order=${order}`;
        $.ajax({
            url: "{{ route('entities.products.index') }}?" + formData,
            type: "GET",
            success: function(response) {
                $('#productList').html($(response.html).find('#productList').html());
            }
        });
        $(this).data('order', order === 'asc' ? 'desc' : 'asc');
    });

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
                $(event.target).closest('tr').remove();
            }
        });
    });

    $('#productList').on('click', '.purchase-button', function(event) {
        event.preventDefault();
        let button = $(this);
        let productId = button.data('product-id');
        $.ajax({
            url: "{{ url('/api/purchase') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: productId
            },
            success: function(response) {
                if (response.success) {
                    let row = button.closest('tr');
                    let stockCell = row.find('.stock');
                    let newStock = parseInt(stockCell.text()) - 1;
                    if (newStock < 0) newStock = 0;
                    stockCell.text(newStock);

                    alert('購入が成功しました');
                } else {
                    alert('購入に失敗しました');
                }
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : '購入に失敗しました';
                alert(errorMessage);
            }
        });
    });
});
</script>
@endsection
