<?php

namespace App\Exports;

use App\Models\Shop\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Employee::query()->with(['point','orders'])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'ФИО',
            'Телефон',
            'Пункт выдачи',
            'Принят на работу',
            'Кол-во заказов',
        ];
    }

    public function map($row): array
    {
        /** @var Employee $row */
        return [
            $row->id,
            $row->full_name,
            $row->phone,
            $row->point->name,
            $row->employment_date,
            $row->orders->count()
        ];
    }
}
