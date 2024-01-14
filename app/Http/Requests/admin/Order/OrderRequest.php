<?php

namespace App\Http\Requests\admin\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'order.point_id' => ['required', 'exists:points,id'],
            'order.employee_id' => ['required', 'exists:employees,id'],
            'order.total_amount' => ['required', 'int'],
            'order_items' => ['required', 'array']
        ];
    }
}
