<?php

namespace App\Http\Requests\admin\Shop;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }


    public function rules(): array
    {
        return [
            'shop.name' => ['required', 'string', 'max:255'],
            'shop.company_name' => ['required', 'string', 'max:255'],
            'shop.website' => ['required', 'string', 'url'],
            'shop.countries' => ['sometimes', 'array'],
            'attachment' => ['sometimes', 'array']
        ];
    }
}
