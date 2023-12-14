<?php

namespace App\Orchid\Screens\Shop;

use App\Models\Shop\Shop;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class ShopViewScreen extends Screen
{
    public $shop;

    public function query(Shop $shop): iterable
    {
        return [
            'shop' => $shop
        ];
    }

    public function name(): ?string
    {
        return 'Информация о магазине: ' . $this->shop->name;
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            Layout::legend('shop', [
                Sight::make('id','№'),
                Sight::make('name','Название магазина'),
                Sight::make('company_name','Компания'),
                Sight::make('Страны доставки')->render(fn (Shop $shop) => implode(', ', $shop->countries)),
                Sight::make('Ссылка на сайт')
                    ->render(fn (Shop $shop) => Link::make($shop->website)->href($shop->website)->target('blank')),
                Sight::make('Изображение')
                    ->render(fn (Shop $shop) => view('admin.thumbnail', ['image' => $shop->attachment->first()->url]))
            ]),
        ];
    }
}
