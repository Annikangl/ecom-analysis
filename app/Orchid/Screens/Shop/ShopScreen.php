<?php

namespace App\Orchid\Screens\Shop;

use App\Http\Requests\admin\Shop\ShopRequest;
use App\Models\Shop\Shop;
use App\Orchid\Layouts\Shop\ShopListLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\ModalToggle;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ShopScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'shops' => Shop::all(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Список магазинов';
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить магазин')
                ->modal('createShopModal')
                ->method('createShop')
                ->icon('bs.plus'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ShopListLayout::class,

            Layout::modal('createShopModal', [
                Layout::rows([
                    Input::make('name')->title('Название')->required(),
                    Input::make('company_name')->title('Компания')->required(),
                    Input::make('website')->title('Ссылка на интернет ресурс')->type('url')->required(),
                    Select::make('countries')
                        ->options([
                            'Российская Федерация' => 'Российская Федерация',
                            'Республика Казахстан' => 'Республика Казахстан',
                            'Республика Беларусь' => 'Республика Беларусь',
                        ])
                        ->title('Страны, где работает магазин')
                        ->multiple(),
                    Upload::make('image')->title('Изображение магазина, логотип')
                        ->maxFiles(1)
                        ->maxFileSize(5)
                        ->acceptedFiles('image/*')
                        ->help('Загрузите изображение не более 5 МБ'),
                ]),

            ])->title('Добавление магазина')->applyButton('Добавить'),
        ];
    }

    public function createShop(ShopRequest $request): void
    {
        $validatedData = collect($request->validated())->except(['_token', 'image'])->toArray();

        $shop = Shop::query()->create($validatedData);
        $shop->attachment()->syncWithoutDetaching($request->input('image'));

        Toast::success("Магазин $shop->name успешно добавлен");
    }
}
