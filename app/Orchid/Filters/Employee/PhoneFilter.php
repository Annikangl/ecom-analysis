<?php

namespace App\Orchid\Filters\Employee;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class PhoneFilter extends Filter
{
    public function name(): string
    {
        return 'Поиск по номеру телефона';
    }

    public function parameters(): ?array
    {
        return [
            'phone'
        ];
    }

    public function run(Builder $builder): Builder
    {
        $phone = $this->request->get('phone');

        return $builder->where('phone', str_replace(' ', '', $phone));
    }

    public function display(): iterable
    {
        return [
            Input::make('phone')
                ->placeholder('Введите номер телефона')
                ->mask('+ 9 (999) 999-9999')
                ->value($this->request->get('phone')),
        ];
    }
}
