<?php

namespace App\Orchid\Screens\Shop;

use App\Models\Shop\Shop;
use Orchid\Screen\Screen;

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
        return 'Магазин: ' . $this->shop->name;
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [];
    }
}
