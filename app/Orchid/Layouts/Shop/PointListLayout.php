<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\Shop\Point;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PointListLayout extends Table
{
    protected $target = 'points';

    protected function columns(): iterable
    {
        return [
            TD::make('Логотип')
                ->width('100')
                ->render(fn(Point $point) => view('admin.thumbnail', [
                    'image' => $point->load('shop')->shop->attachment->first()->url ?? '',
                    'id' => $point->id])
                ),
            TD::make('shop.name', 'Магазин'),
            TD::make('id', 'Идентификатор')->sort(),
            TD::make('name', 'Пункт'),
            TD::make('address', 'Адрес'),
            TD::make('Сейчас работает?')->render(fn(Point $point) => $point->is_open ? 'Да' : 'Нет'),
            TD::make('Действия')->render(fn(Point $point) => DropDown::make()
                ->icon('bs.three-dots-vertical')
                ->list([
                    Link::make('Подробнее')
                        ->icon('eye')
                        ->route('platform.point.show', $point),

                    ModalToggle::make(('Изменить'))
                        ->modal('editPointModal')
                        ->method('updatePoint')
                        ->asyncParameters([
                            'point' => $point->id
                        ])
                        ->icon('pencil'),

                    Button::make(('Delete'))
                        ->icon('trash')
                        ->confirm(('Вы действительно хотите удалить: ' . $point->name))
                        ->method('deletePoint', [
                            'point' => $point->id,
                        ])
                ])),
        ];
    }
}
