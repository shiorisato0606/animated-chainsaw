<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntityRequest extends FormRequest
{
    public function rules()
    {
        return [
            'product_name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'company_id' => 'required|exists:companies,id',
            'comment' => 'nullable|max:1000',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => '商品名は必須です。',
            'product_name.max' => '商品名は255文字以内で入力してください。',
            'price.required' => '価格は必須です。',
            'price.numeric' => '価格は数字で入力してください。',
            'price.min' => '価格は0以上で入力してください。',
            'stock.required' => '在庫数は必須です。',
            'stock.integer' => '在庫数は整数で入力してください。',
            'stock.min' => '在庫数は0以上で入力してください。',
            'company_id.required' => 'メーカー名は必須です。',
            'company_id.exists' => '指定されたメーカーが存在しません。',
            'comment.max' => 'コメントは1000文字以内で入力してください。',
            'image.image' => '画像は有効な画像ファイルである必要があります。',
            'image.max' => '画像は2MB以内でアップロードしてください。',
        ];
    }
    
    public function authorize()
    {
        return true;
    }
}
