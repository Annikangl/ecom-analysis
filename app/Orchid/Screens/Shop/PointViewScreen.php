<?php

namespace App\Orchid\Screens\Shop;

use App\Models\Order\Order;
use App\Models\Shop\Point;
use App\Orchid\Layouts\Charts\OrdersByDateLineChart;
use App\Orchid\Layouts\Order\OrderListLayout;
use App\Orchid\Layouts\Shop\EmployeeListLayout;
use Carbon\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PointViewScreen extends Screen
{
    /**
     * @var Point
     */
    public $point;

    public function query(Point $point): iterable
    {
        $point->load([
            'employee',
            'employee.attachment',
            'employee.point',
            'employee.orders',
            'shop',
            'orders',
            'orders.point',
            'orders.employee'
        ]);

        return [
            'point' => $point,
            'employees' => $point->employee,
            'orders' => $point->orders,

            'ordersDynamic' => [
                $point->orders()->countByDays(
                    Carbon::now()->subDays(30),
                    Carbon::now(),
                    'created_at'
                )->toChart('Заказов')
            ],

            'averageByDays' => [
                $point->orders()->averageByDays('total_amount')->toChart('Средняя стоимость заказа'),
                $point->orders()->minByDays('total_amount')->toChart('Минимальная стоимость заказа'),
                $point->orders()->maxByDays('total_amount')->toChart('Максимальная стоимость заказа'),
            ]
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
            Layout::view('admin.valueMetric', [
                'title' => 'Статистика по продажам',
                'subtitle' => 'Статистика продаж на данном пункте выдачи',
                'items' => $this->getSalesStatistic()
            ]),

            Layout::view('admin.valueMetric', [
                'title' => 'Информация по пункту выдачи',
                'subtitle' => '',
                'items' => $this->getPointStatistic()
            ]),

            Layout::view('admin.valueMetric', [
                'title' => 'Статистика по сотрудникам',
                'subtitle' => 'Статистика по сотрудникам пункта выдачи',
                'items' => $this->getEmployeeStatistic()
            ]),

            Layout::legend('point', [
                Sight::make('id', '№'),
                Sight::make('name', 'Название'),
                Sight::make('address', 'Адрес'),
                Sight::make('schedule', 'Расписание'),
                Sight::make('phone', 'Номер телефона'),
                Sight::make('Магазин')
                    ->render(fn(Point $point) => Link::make($point->shop->name)->route('platform.shop.show', $point->shop)),
                Sight::make('Открыт сейчас')->render(fn(Point $point) => $point->is_open ? 'Да' : 'Нет'),
                Sight::make('created_at', 'Дата открытия пункта'),
            ]),

            Layout::columns([
                OrdersByDateLineChart::make('ordersDynamic', 'Динамика заказов за последние 30 дней'),
                OrdersByDateLineChart::make('averageByDays', 'Динамика стоимости заказов за последние 30 дней'),
            ]),

            EmployeeListLayout::class,
            OrderListLayout::class,
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

    public function getAvgEmployeeCheck(): float|int
    {
        $startDate = Carbon::now()->subMonth();
        $endDate = Carbon::now();

        $orders = $this->point->orders()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $ordersByDate = $orders->groupBy(function ($order) {
            return $order->created_at;
        });

        $totalAmount = 0;
        $daysWithOrders = $ordersByDate->count();

        foreach ($ordersByDate as $date => $orders) {
            $totalAmount += $orders->sum('total_amount');
        }

        if ($daysWithOrders > 0) {
            $averageCheckPerEmployeePerDay = $totalAmount / $daysWithOrders;
        } else {
            $averageCheckPerEmployeePerDay = 0;
        }

        return ceil($averageCheckPerEmployeePerDay);
    }


    private function getSalesStatistic(): array
    {
        $now = Carbon::now();

        return [
            [
                'title' => 'Общая сумма продаж за последний год, руб',
                'value' => $this->getTotalSalesForDateRange($now->copy()->subYear(), $now),
            ],
            [
                'title' => 'Сумма продаж за последний квартал, руб.',
                'value' => $this->getTotalSalesForDateRange($now->copy()->subMonths(3), $now),
            ],
            [
                'title' => 'Сумма продаж за последние 30 дней, руб.',
                'value' => $this->getTotalSalesForDateRange($now->copy()->subMonth(), $now),
            ]
        ];
    }

    private function getEmployeeStatistic(): array
    {
        $employeeCounts = $this->point->employee->count();
        $ordersAmount = $this->point->orders->sum('total_amount');

        $ordersAmountByLastMonth = $this->point->orders()
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->sum('total_amount');

        return [
            [
                'title' => 'Средний чек одного сотрудника за все время, руб',
                'value' => ceil($ordersAmount / $employeeCounts),
            ],
            [
                'title' => 'Средний чек одного сотрудника за месяц, руб',
                'value' => ceil($ordersAmountByLastMonth / $employeeCounts),
            ],
            [
                'title' => 'Средний чек сотрудника за день, руб.',
                'value' => $this->getAvgEmployeeCheck(),
            ],
        ];
    }

    public function getTotalSalesForDateRange(Carbon|string $startDate, Carbon|string $endDate): int
    {
        $orders = $this->point->orders()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return $orders->sum('total_amount');
    }

    private function getPointStatistic(): array
    {
        $employeeCounts = $this->point->employee->count();

        return [
            [
                'title' => 'Работает сотрудников',
                'value' => $employeeCounts,
            ],
            [
                'title' => 'Выполнено заказов',
                'value' => $this->point->orders->count(),
                'statDays' => 7,
                'statValue' => $this->point
                    ->orders()
                    ->where('created_at', '>=', Carbon::now()
                        ->subDays(7))->count(),
            ],
        ];
    }

}
