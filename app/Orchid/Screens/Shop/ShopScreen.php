<?php

namespace App\Orchid\Screens\Shop;

use App\Http\Requests\admin\Shop\ShopRequest;
use App\Models\Shop\Shop;
use App\Orchid\Filters\NameFilter;
use App\Orchid\Layouts\Selection\ShopSelection;
use App\Orchid\Layouts\Shop\ShopListLayout;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ShopScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'shops' => Shop::with(['attachment'])
                ->filtersApplySelection(ShopSelection::class)
                ->defaultSort('id')
                ->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Список магазинов';
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить магазин')
                ->modal('createShopModal')
                ->method('createShop')
                ->icon('bs.plus-circle'),
        ];
    }

    public function layout(): iterable
    {
        return [
            ShopSelection::class,
            ShopListLayout::class,

            Layout::modal('createShopModal', [
                Layout::rows([
                    Input::make('shop.name')->title('Название')->required(),
                    Input::make('shop.company_name')->title('Компания')->required(),
                    Input::make('shop.website')->title('Ссылка на интернет ресурс')->type('url')->required(),
                    Select::make('shop.countries')
                        ->options([
                            'Российская Федерация' => 'Российская Федерация',
                            'Республика Казахстан' => 'Республика Казахстан',
                            'Республика Беларусь' => 'Республика Беларусь',
                        ])
                        ->title('Страны, где работает магазин')
                        ->multiple(),
                    Upload::make('shop.image')->title('Изображение магазина, логотип')
                        ->maxFiles(1)
                        ->maxFileSize(5)
                        ->acceptedFiles('image/*')
                        ->help('Загрузите изображение не более 5 МБ'),
                ]),
            ])->title('Добавление магазина')->applyButton('Добавить'),

            Layout::modal('editShopModal', [
                Layout::rows([
                    Input::make('shop.name')->title('Название')->required(),
                    Input::make('shop.company_name')->title('Компания')->required(),
                    Input::make('shop.website')->title('Ссылка на интернет ресурс')->type('url')->required(),
                    Input::make('shop.id')->hidden(),
                    Upload::make('shop.attachment')->title('Изображение магазина, логотип')
                        ->maxFiles(1)
                        ->maxFileSize(5)
                        ->acceptedFiles('image/*')
                        ->help('Загрузите изображение не более 5 МБ'),
                ]),

            ])->title('Редактирование данных магазина')
                ->async('asyncGetShop')
                ->applyButton('Сохранить изменения'),
        ];
    }

    public function createShop(ShopRequest $request): void
    {
        $validatedData = collect($request->validated('shop'))->except(['_token', 'image'])->toArray();

        $shop = Shop::query()->create($validatedData);
        $shop->attachment()->syncWithoutDetaching($request->input('shop.image')[0]);

        Toast::success("Магазин $shop->name успешно добавлен");
    }

    public function deleteShop(Shop $shop): RedirectResponse
    {
        $shop->delete();
        Toast::info('Магазин удален успешно');

        return redirect()->back();
    }

    public function updateShop(ShopRequest $request): void
    {
        $validatedData = collect($request->validated('shop'))->except(['_token', 'image'])->toArray();

        $shop = Shop::find($request->input('shop.id'));

        $shop->update($validatedData);

        if ($request->input('shop.attachment')) {
            $shop->attachment()->syncWithoutDetaching($request->input('shop.attachment')[0]);
        }

        Toast::success('Магазин успешно обновлен');
    }

    public function asyncGetShop(Shop $shop): array
    {
        return [
            'shop' => $shop
        ];
    }

}
