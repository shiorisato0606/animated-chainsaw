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
        if ($this->routeIs('entities.storeCompany') || $this->routeIs('entities.updateCompany')) {
            return [
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'representative' => 'nullable|string|max:255',
            ];
        }

        if ($this->routeIs('entities.storeProduct') || $this->routeIs('entities.updateProduct')) {
            return [
                'product_name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'company_id' => 'required|integer|exists:companies,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'comment' => 'nullable|string|max:255',
            ];
        }

        return [];
    }
}
