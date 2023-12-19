<?php

namespace App\Orchid\Screens\Shop;

use App\Models\Shop\Point;
use App\Orchid\Layouts\Shop\EmployeeListLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PointViewScreen extends Screen
{
    public $point;

    public function query(Point $point): iterable
    {
        return [
            'point' => $point,
        ];
    }

    public function name(): ?string
    {
        return "Пункт выдачи {$this->point->name} магазина {$this->point->shop->name}";
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Закрыть пункт')
                ->type(Color::DANGER)
                ->method('closePoint', ['model' => $this->point])
                ->canSee($this->point->is_open),
            Button::make('Открыть пункт')
                ->type(Color::PRIMARY)
                ->method('openPoint', ['model' => $this->point])
                ->canSee(!$this->point->is_open),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::legend('point', [
                Sight::make('id', '№'),
                Sight::make('name', 'Название'),
                Sight::make('address', 'Адрес'),
                Sight::make('schedule', 'Расписание'),
                Sight::make('phone', 'Номер телефона'),
                Sight::make('Магазин')
                    ->render(fn (Point $point) => Link::make($point->shop->name)->route('platform.shop.show', $point->shop)),
                Sight::make('Открыт сейчас')->render(fn(Point $point) => $point->is_open ? 'Да' : 'Нет'),
                Sight::make('created_at', 'Дата открытия пункта'),
            ]),

           EmployeeListLayout::class,
        ];
    }

    public function closePoint(Point $point): void
    {
        $point->is_open = false;
        $point->save();

        Toast::info('Пункт выдачи закрыт');
    }

    public function openPoint(Point $point): void
    {
        $point->is_open = true;
        $point->save();

        Toast::info('Пункт выдачи закрыт');
    }
}
