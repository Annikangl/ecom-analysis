<?php

namespace App\Orchid\Filters\Employee;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class FullNameFilter extends Filter
{
    public function name(): string
    {
        return 'Поиск по ФИО';
    }

    public function parameters(): ?array
    {
        return [
            'full_name'
        ];
    }

    public function run(Builder $builder): Builder
    {
        return $builder->where('full_name', 'LIKE', "%" . $this->request->get('full_name') . "%");
    }

    public function display(): iterable
    {
        return [
            Input::make('full_name')
                ->placeholder('Введите фамилию, имя или отчество')
                ->value($this->request->get('full_name')),
        ];
    }
}
