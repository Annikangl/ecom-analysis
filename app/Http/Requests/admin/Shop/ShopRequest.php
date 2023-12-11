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
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'website' => ['required', 'string', 'url'],
            'countries' => ['required', 'array']
        ];
    }
}
