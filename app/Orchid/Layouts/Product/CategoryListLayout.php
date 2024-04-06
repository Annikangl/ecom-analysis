<?php

namespace App\Orchid\Layouts\Product;

use App\Models\Product\ProductCategory;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CategoryListLayout extends Table
{
    protected $target = 'categories';

    protected function compact(): bool
    {
        return true;
    }

    protected function columns(): iterable
    {
        return [
            TD::make('id', '#'),
            TD::make('name', 'Категория'),
            TD::make( 'parent.name', 'Родительская категория'),
            TD::make('Действия')->render(fn(ProductCategory $category) => DropDown::make()
                ->icon('bs.three-dots-vertical')
                ->list([
                    Link::make('Подробнее')
                        ->icon('eye')
                        ->route('platform.category.show', $category),

                    Button::make(('Delete'))
                        ->icon('trash')
                        ->confirm(('Вы действительно хотите удалить: ' . $category->name))
                        ->method('deleteCategory', [
                            'category' => $category->id,
                        ])
                ])),
        ];
    }
}
