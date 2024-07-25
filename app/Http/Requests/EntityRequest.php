<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntityRequest extends FormRequest
{
    public function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => '商品名は必須です。',
            'company_id.required' => 'メーカー名は必須です。',
            'price.required' => '価格は必須です。',
            'stock.required' => '在庫数は必須です。',
            'comment.max' => 'コメントは255文字以内で入力してください。',
            'image.image' => '画像ファイルをアップロードしてください。',
            'image.mimes' => '画像ファイルは jpeg, png, jpg, gif, または svg でなければなりません。',
            'image.max' => '画像ファイルのサイズは最大2048KBです。',
        ];
    }
}
