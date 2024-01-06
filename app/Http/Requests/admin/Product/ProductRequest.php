<?php

namespace App\Http\Requests\admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'product.shop_id' => ['required', 'exists:shops,id'],
            'product.product_category_id' => ['required', 'exists:product_categories,id'],
            'product.title' => ['required', 'string', 'max:255'],
            'product.description' => ['required', 'string', 'max:999'],
            'product.price' => ['required', 'integer'],
            'product.sale_price' => ['required', 'integer'],
            'attachment' => ['sometimes', 'array']
        ];
    }
}
