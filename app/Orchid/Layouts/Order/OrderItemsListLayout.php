<?php

namespace App\Orchid\Layouts\Order;

use App\Models\Order\OrderItem;
use App\Models\Product\Product;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OrderItemsListLayout extends Table
{

    protected $target = 'orderItems';

    protected $title = 'Список товаров в заказе';

    protected function compact(): bool
    {
        return true;
    }

    protected function columns(): iterable
    {
        return [
            TD::make('Фотография')
                ->width('100')
                ->render(fn(OrderItem $item) =>  view('admin.thumbnail', [
                    'image' => $item->product->attachment->first()?->url,
                    'id' => $item->id])
                ),
            TD::make('id', '#'),
            TD::make( 'Наименование товара')
                ->render(fn (OrderItem $item) => Link::make($item->product->title)->route('platform.product.show', $item->product)),
            TD::make( 'Цена товара')->render(fn (OrderItem $item) => $item->product->price . ' руб.'),
        ];
    }
}
