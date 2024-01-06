<?php

namespace App\Orchid\Screens\Product;

use App\Models\Product\ProductCategory;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class CategoryViewScreen extends Screen
{
    public $productCategory;

    public function query(ProductCategory $productCategory): iterable
    {
        return [
            'productCategory' => $productCategory
        ];
    }

    public function name(): ?string
    {
        return 'Категория ' . $this->productCategory->name;
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            Layout::legend('productCategory', [
                Sight::make('id', '№'),
                Sight::make('name', 'Категория'),
                Sight::make('Родительская категория')->render(function (ProductCategory $category) {
                    return $category->parent ?
                        Link::make($category->parent->name)->route('platform.category.show', $category->parent)
                        : '-';
                }),
            ]),
        ];
    }
}
