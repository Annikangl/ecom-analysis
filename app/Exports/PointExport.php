<?php

namespace App\Exports;

use App\Models\Shop\Point;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class PointExport implements FromCollection
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Point::all();
    }
}
