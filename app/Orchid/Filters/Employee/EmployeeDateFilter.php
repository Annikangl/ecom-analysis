<?php

namespace App\Orchid\Filters\Employee;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;

class EmployeeDateFilter extends Filter
{
    public function name(): string
    {
        return 'Принят на работу';
    }

    public function parameters(): ?array
    {
        return [
            'employment_date'
        ];
    }

    public function run(Builder $builder): Builder
    {
        return $builder->where('employment_date', $this->request->get('employment_date'));
    }

    public function display(): iterable
    {
        return [
            DateTimer::make('employment_date')
                ->placeholder('Дата принятия на работу')
                ->value($this->request->get('employment_date')),
        ];
    }
}
