<?php

namespace App\Exports;

use App\Models\Product\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProductExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Product::query()->with(['category','orders', 'shop'])->get();
    }

    public function map($product): array
    {
        /** @var Product $product */

        return [
            $product->id,
            $product->shop->name,
            $product->title,
            $product->description,
            $product->price,
            $product->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Магазин',
            'Наименование товара',
            'Описание',
            'Цена',
            'Дата поставки'
        ];
    }
}
