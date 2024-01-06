<?php

namespace App\Orchid\Screens\Product;

use App\Models\Product\Product;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class ProductViewScreen extends Screen
{
    public $product;

    public function query(Product $product): iterable
    {
        $product->load(['shop', 'category', 'attachment']);

        return [
            'product' => $product,
        ];
    }

    public function name(): ?string
    {
        return 'Товар ' . $this->product->title;
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            Layout::tabs([
                'Основная информация' => [
                    Layout::legend('product', [
                        Sight::make('id','#'),
                        Sight::make('Категория товара')
                            ->render(fn (Product $product) => Link::make($product->category->name)->route('platform.category.show', $product->category)),
                        Sight::make('Магазин')
                            ->render(fn (Product $product) => Link::make($product->shop->name)->route('platform.shop.show', $product->shop)),
                        Sight::make('title','Наименование товара'),
                        Sight::make('description','Описание'),
                        Sight::make('price','Цена'),
                        Sight::make('sale_price','Цена со скидкой %'),
                    ]),
                ],
                'Изображения' => [
                    Layout::view('admin.gallery', ['images' => $this->product->attachment->toArray()])
                ]
            ])
        ];
    }
}
