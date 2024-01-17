<?php

namespace App\Orchid\Layouts\Selection;

use App\Orchid\Filters\Employee\EmployeeDateFilter;
use App\Orchid\Filters\Employee\FullNameFilter;
use App\Orchid\Filters\Employee\PhoneFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class EmployeeSelection extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [
            FullNameFilter::class,
            PhoneFilter::class,
            EmployeeDateFilter::class,
        ];
    }
}
