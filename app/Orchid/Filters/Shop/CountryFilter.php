<?php

namespace App\Orchid\Filters\Shop;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class CountryFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Страна деятельности';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return [
            'countries'
        ];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereJsonContains('countries', $this->request->get('countries'));
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Select::make('countries')
                ->options([
                    'Российская Федерация' => 'Российская Федерация',
                    'Республика Казахстан' => 'Республика Казахстан',
                    'Республика Беларусь' => 'Республика Беларусь',
                ])
                ->multiple()
                ->empty('Не выбрано')
                ->title('Страны, где работает магазин')
        ];
    }
}
