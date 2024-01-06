<?php

namespace App\Orchid\Layouts\Product;

use App\Models\Product\Product;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProductListLayout extends Table
{
    protected $target = 'products';

    protected function columns(): iterable
    {
        return [
            TD::make('Фотография')
                ->width('100')
                ->render(fn(Product $product) =>  view('admin.thumbnail', [
                    'image' => $product->attachment->first()?->url,
                    'id' => $product->id])
                ),
            TD::make('id', '#'),
            TD::make('title', 'Название'),
            TD::make( 'Магазин')
                ->render(fn(Product $product) => Link::make($product->shop->name)->route('platform.shop.show', $product->shop)),
            TD::make( 'Категория')
                ->render(fn(Product $product) => Link::make($product->category->name)->route('platform.category.show', $product->category)),
            TD::make('price', 'Цена'),
            TD::make('Действия')->render(fn(Product $product) => DropDown::make()
                ->icon('bs.three-dots-vertical')
                ->list([
                    Link::make('Подробнее')
                        ->icon('eye')
                        ->route('platform.product.show', $product),

                    ModalToggle::make(('Изменить'))
                        ->modal('editProductModal')
                        ->method('updateProduct')
                        ->asyncParameters([
                            'product' => $product->id
                        ])
                        ->icon('pencil'),

                    Button::make(('Delete'))
                        ->icon('trash')
                        ->confirm(('Вы действительно хотите удалить: ' . $product->title))
                        ->method('deleteProduct', [
                            'product' => $product->id,
                        ])
                ])),
        ];
    }
}
