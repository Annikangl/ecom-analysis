<?php

namespace App\Http\Requests\admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'category.parent_id' => ['sometimes', 'exists:product_categories,id'],
            'category.name' => ['required', 'string', 'max:255'],
        ];
    }

}
