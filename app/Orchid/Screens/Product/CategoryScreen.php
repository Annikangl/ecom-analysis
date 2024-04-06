<?php

namespace App\Orchid\Screens\Product;

use App\Http\Requests\admin\Product\CategoryRequest;
use App\Models\Product\ProductCategory;
use App\Orchid\Layouts\Product\CategoryListLayout;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
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
        return [
            ModalToggle::make('Добавить категорию ')
                ->modal('createCategoryModal')
                ->method('createCategory')
                ->icon('bs.plus-circle'),
        ];
    }


    public function layout(): iterable
    {
        return [
            Layout::modal('createCategoryModal', [
                Layout::rows([
                    Group::make([
                        Relation::make('category.patent_id')
                            ->fromModel(ProductCategory::class, 'name')
                            ->title('Родительская категория'),
                    ]),

                    Input::make('category.name')->title('Наименование категории')->required(),
                ]),
            ])->title('Добавление категории')
                ->applyButton('Добавить'),
            CategoryListLayout::class,
        ];
    }

    public function createCategory(CategoryRequest $request)
    {
        $category = ProductCategory::query()->create($request->validated('category'));

        Toast::success('Категория успешно создана');
    }

    public function deleteCategory(ProductCategory $category): void
    {
        $category->delete();

        Toast::info('Категория удалена успешно');
    }
}
