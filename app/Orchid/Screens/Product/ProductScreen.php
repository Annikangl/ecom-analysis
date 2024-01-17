<?php

namespace App\Orchid\Screens\Product;

use App\Exports\ProductExport;
use App\Http\Requests\admin\Product\ProductRequest;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Shop\Shop;
use App\Orchid\Layouts\Product\ProductListLayout;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'products' => Product::query()
                ->has('category')
                ->with(['category','shop', 'attachment'])
                ->latest()
                ->paginate(10),
        ];
    }

    public function name(): ?string
    {
        return 'Список товаров';
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить товар')
                ->modal('createProductModal')
                ->method('createProduct')
                ->icon('bs.plus-circle'),

            Button::make('Экспорт')->icon('download')->method('export')->rawClick(),
        ];
    }

    /**
     * @throws BindingResolutionException
     */
    public function layout(): iterable
    {
        return [
            ProductListLayout::class,

            Layout::modal('createProductModal', [
                Layout::rows([
                    Group::make([
                        Relation::make('product.shop_id')
                            ->fromModel(Shop::class, 'name')
                            ->title('Выберите магазин'),

                        Relation::make('product.product_category_id')
                            ->fromModel(ProductCategory::class, 'name')
                            ->title('Выберите категорию'),
                    ]),

                    Input::make('product.title')->title('Наименование товара')->required(),

                    TextArea::make('product.description')->title('Описание')->required(),

                    Group::make([
                        Input::make('product.price')->title('Цена')
                            ->type('number')
                            ->required(),

                        Input::make('product.sale_price')->title('Цена со скидкой')
                            ->type('number')
                            ->required(),
                    ]),

                    Upload::make('product.images')->title('Изображение товара')
                        ->maxFiles(5)
                        ->maxFileSize(5)
                        ->acceptedFiles('image/*')
                        ->help('Загрузите не более 5 изображений, размером не более 5 МБ'),

                ]),
            ])->title('Добавление товара')
                ->applyButton('Добавить')
                ->size(Modal::SIZE_LG),

            Layout::modal('editProductModal', [
                Layout::rows([
                    Group::make([
                        Relation::make('product.shop_id')
                            ->fromModel(Shop::class, 'name')
                            ->title('Выберите магазин'),

                        Relation::make('product.product_category_id')
                            ->fromModel(ProductCategory::class, 'name')
                            ->title('Выберите категорию'),
                    ]),
                    Input::make('product.title')->title('Наименование товара')->required(),

                    TextArea::make('product.description')->title('Описание')->required(),

                    Group::make([
                        Input::make('product.price')->title('Цена')
                            ->type('number')
                            ->required(),

                        Input::make('product.sale_price')->title('Цена со скидкой')
                            ->type('number')
                            ->required(),
                    ]),

                    Upload::make('product.attachment')->title('Изображение товара')
                        ->maxFiles(5)
                        ->maxFileSize(5)
                        ->acceptedFiles('image/*')
                        ->help('Загрузите не более 5 изображений, размером не более 5 МБ'),

                    Input::make('product.id')->hidden(),
                ]),

            ])->title('Редактирование данных товара')
                ->async('asyncGetProduct')
                ->applyButton('Сохранить изменения'),
        ];
    }

    public function createProduct(ProductRequest $request): RedirectResponse
    {
        $product = Product::query()->create($request->validated('product'));

        $product->attachment()->syncWithoutDetaching($request->input('product.images'));

        Toast::success('Товар успешно добавлен');

        return redirect()->back();
    }

    public function updateProduct(ProductRequest $request)
    {
        $validatedData = collect($request->validated('product'))->except(['_token', 'image'])->toArray();

        $product = Product::find($request->input('product.id'));

        $product->update($validatedData);

        if ($request->input('shop.attachment')) {
            $product->attachment()->syncWithoutDetaching($request->input('product.attachment'));
        }

        Toast::success('Товар успешно обновлен');
    }

    public function deleteProduct(Product $product): void
    {
        $product->delete();

        Toast::info('Товар удалена успешно из всех магазинов');
    }

    public function asyncGetProduct(Product $product): array
    {
        return [
            'product' => $product,
        ];
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(new ProductExport(), 'products.xlsx');
    }
}
