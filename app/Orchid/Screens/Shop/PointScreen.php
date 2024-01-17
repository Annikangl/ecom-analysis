<?php

namespace App\Orchid\Screens\Shop;

use App\Exports\PointExport;
use App\Http\Requests\admin\Shop\PointRequest;
use App\Models\Shop\Point;
use App\Models\Shop\Shop;
use App\Orchid\Layouts\Shop\PointListLayout;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PointScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'points' => Point::query()
                ->with(['shop', 'employee', 'orders'])
                ->latest()
                ->paginate(25),
        ];
    }

    public function name(): ?string
    {
        return 'Пункты выдачи';
    }

    public function description(): ?string
    {
        return 'Список пунктов выдачи магазинов';
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить пункт выдачи')
                ->modal('createPointModal')
                ->method('createPoint')
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
            PointListLayout::class,

            Layout::modal('createPointModal', [
                Layout::rows([
                    Input::make('point.name')->title('Название')->required(),
                    Relation::make('point.shop_id')
                        ->fromModel(Shop::class, 'name')
                        ->title('Выберите магазин'),
                    Input::make('point.address')->title('Адрес')->required(),
                    Input::make('point.schedule')->title('График работы')->required()
                        ->help('Например, Пн - Пт. С 9:00 до 18:00'),
                    Input::make('point.phone')->title('Контактный номер телефона')
                        ->mask('+ 9 (999) 999-9999')
                        ->required(),
                    CheckBox::class::make('point.is_open')
                        ->title('Статус пункта выдачи')
                        ->placeholder('Открытый пункт выдачи')
                        ->sendTrueOrFalse(),
                ]),
            ])->title('Новый пункт выдачи')->applyButton('Добавить'),

            Layout::modal('editPointModal', [
                Layout::rows([
                    Input::make('point.name')->title('Название')->required(),
                    Input::make('point.address')->title('Адрес')->required(),
                    Input::make('point.schedule')->title('График работы')->required(),
                    Input::make('point.phone')->title('Контактный номер телефона')->required(),
                    CheckBox::class::make('point.is_open')
                        ->title('Статус пункта выдачи')
                        ->placeholder('Открытый пункт выдачи')
                        ->sendTrueOrFalse(),
                    Input::make('point.id')->hidden(),
                ]),

            ])->title('Редактирование данных пункта')
                ->async('asyncGetPoint')
                ->applyButton('Сохранить изменения'),
        ];
    }

    public function createPoint(PointRequest $request): void
    {
        $validatedData = collect($request->validated('point'))->toArray();

        $point = Point::query()->create($validatedData);

        Toast::success("Пункт $point->name успешно добавлен");
    }

    public function updatePoint(PointRequest $request): void
    {
        $validatedData = collect($request->validated('point'))->toArray();

        $point = Point::findOrFail($request->input('point.id'));

        $point->update($validatedData);

        Toast::success("Пункт $point->name успешно обновлен");
    }

    public function deletePoint(Point $point): RedirectResponse
    {
        $point->delete();
        Toast::info('Пункт выдачи удален успешно');

        return redirect()->back();
    }

    public function asyncGetPoint(Point $point): array
    {
        return [
            'point' => $point
        ];
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(new PointExport(), 'points.xlsx');
    }

}
