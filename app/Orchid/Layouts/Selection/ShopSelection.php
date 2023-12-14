<?php

namespace App\Orchid\Layouts\Selection;

use App\Orchid\Filters\NameFilter;
use App\Orchid\Filters\Shop\CountryFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class ShopSelection extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [
            NameFilter::class,
            CountryFilter::class,
        ];
    }
}
