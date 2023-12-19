<?php

namespace App\Http\Requests\admin\Shop;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee.full_name' => ['required', 'string', 'max:255'],
            'employee.point_id' => ['required', 'exists:points,id'],
            'employee.passport_series' => ['required', 'string', 'max:30'],
            'employee.address' => ['required', 'string', 'max:255'],
            'employee.phone' => ['required', 'string'],
            'employee.birthdate' => ['required', 'date'],
            'employee.employment_date' => ['required', 'date'],
            'attachment' => ['sometimes', 'array'],
        ];
    }


}
