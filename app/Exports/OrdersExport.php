<?php

namespace App\Exports;

use App\Models\Order\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Order::query()->with(['employee','point','items'])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Пункт выдачи',
            'Кто оформил',
            'Стоимость заказа',
            'Дата заказа',
        ];
    }

    public function map($row): array
    {
        /** @var Order $row */
        return [
            $row->id,
            $row->point->name,
            $row->employee->full_name,
            $row->total_amount,
            $row->created_at
        ];
    }
}
