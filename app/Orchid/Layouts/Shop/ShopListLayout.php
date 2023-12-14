<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\Shop\Shop;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ShopListLayout extends Table
{
    protected $target = 'shops';

    protected function columns(): iterable
    {
        return [
            TD::make('Логотип')
                ->width('100')
                ->render(fn(Shop $shop) => view('admin.thumbnail', [
                    'image' => $shop->attachment->first()->url ?? '',
                    'id' => $shop->id])
                ),
            TD::make('id', '№')->sort(),
            TD::make('name', 'Магазин')->sort(),
            TD::make('company_name', 'Компания'),
            TD::make('Действия')->render(fn(Shop $shop) => DropDown::make()
                ->icon('bs.three-dots-vertical')
                ->list([
                    Link::make('Подробнее')
                        ->icon('eye')
                        ->route('platform.shop.show', $shop),

                    ModalToggle::make(('Изменить'))
                        ->modal('editShopModal')
                        ->method('updateShop')
                        ->asyncParameters([
                            'shop' => $shop->id
                        ])
                        ->icon('pencil'),

                    Button::make(('Delete'))
                        ->icon('trash')
                        ->confirm(('Вы действительно хотите удалить: ' . $shop->name))
                        ->method('deleteShop', [
                            'shop' => $shop->id,
                        ])
                ])),
        ];
    }
}
