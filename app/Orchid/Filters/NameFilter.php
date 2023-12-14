<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;

class NameFilter extends Filter
{
    public function name(): string
    {
        return 'Поиск по названию';
    }

    public function parameters(): ?array
    {
        return [
            'name'
        ];
    }

    public function run(Builder $builder): Builder
    {
        return $builder->where('name', $this->request->get('name'));
    }

    public function display(): iterable
    {
        return [
            Input::make('name')
                ->placeholder('Введите название для поиска')
                ->value($this->request->get('name')),
        ];
    }
}
