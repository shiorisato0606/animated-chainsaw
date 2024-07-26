<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->routeIs('entities.products.store') || $this->routeIs('entities.products.update')) {
            return [
                'product_name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'company_id' => 'required|integer|exists:companies,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'comment' => 'nullable|string|max:255',
            ];
        }

        if ($this->routeIs('entities.companies.store') || $this->routeIs('entities.companies.update')) {
            return [
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'representative' => 'nullable|string|max:255',
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            'product_name.required' => '商品名は必須です。',
            'product_name.string' => '商品名は文字列でなければなりません。',
            'product_name.max' => '商品名は255文字以内でなければなりません。',
            'price.required' => '価格は必須です。',
            'price.numeric' => '価格は数値でなければなりません。',
            'stock.required' => '在庫数は必須です。',
            'stock.integer' => '在庫数は整数でなければなりません。',
            'company_id.required' => 'メーカー名は必須です。',
            'company_id.integer' => 'メーカー名は整数でなければなりません。',
            'company_id.exists' => '指定されたメーカーが存在しません。',
            'image.mimes' => '画像の形式はjpeg, png, jpg, gif, svgのいずれかでなければなりません。',
            'image.max' => '画像のサイズは最大2MBまでです。',
            'comment.string' => 'コメントは文字列でなければなりません。',
            'comment.max' => 'コメントは255文字以内でなければなりません。',
            'name.required' => '会社名は必須です。',
            'name.string' => '会社名は文字列でなければなりません。',
            'name.max' => '会社名は255文字以内でなければなりません。',
            'address.string' => '住所は文字列でなければなりません。',
            'representative.string' => '代表者名は文字列でなければなりません。',
        ];
    }
}
