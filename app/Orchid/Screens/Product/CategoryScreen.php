<?php

namespace App\Orchid\Screens\Product;

use App\Models\Product\ProductCategory;
use App\Orchid\Layouts\Product\CategoryListLayout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class CategoryScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'categories' => ProductCategory::query()
                ->with(['parent', 'product'])
                ->latest()
                ->paginate(25),
        ];
    }

    public function name(): ?string
    {
        return 'Список категорий товаров';
    }

    public function commandBar(): iterable
    {
        return [];
    }


    public function layout(): iterable
    {
        return [
            CategoryListLayout::class,
        ];
    }

    public function deleteCategory(ProductCategory $category): void
    {
        $category->delete();

        Toast::info('Категория удалена успешно');
    }
}
