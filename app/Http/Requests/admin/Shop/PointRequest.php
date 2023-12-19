<?php

namespace App\Http\Requests\admin\Shop;

use Illuminate\Foundation\Http\FormRequest;

class PointRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'point.shop_id' => ['sometimes', 'exists:shops,id'],
            'point.name' => ['required', 'string', 'max:120'],
            'point.address' => ['required', 'string', 'max:255'],
            'point.schedule' => ['required', 'string', 'max:100'],
            'point.phone' => ['required', 'string', 'max:25'],
            'point.is_open' => ['required', 'bool'],
        ];
    }


}
