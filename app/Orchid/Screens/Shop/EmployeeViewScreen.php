<?php

namespace App\Orchid\Screens\Shop;

use App\Models\Shop\Employee;
use Carbon\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EmployeeViewScreen extends Screen
{
    /**
     * @var Employee
     */
    public $employee;

    public function query(Employee $employee): iterable
    {
        return [
            'employee' => $employee
        ];
    }

    public function name(): ?string
    {
        return "Дело сотрудника № {$this->employee->id}";
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Уволить')
                ->type(Color::DANGER)
                ->icon('trash')
                ->method('dismiss', ['employee' => $this->employee])
            ->canSee(is_null($this->employee->dismissal_date))
        ];
    }

    public function dismiss(Employee $employee)
    {
        $employee->dismissal_date = Carbon::now();
        $employee->save();

        Toast::info('Сотрудник уволен текущим днем');
    }

    public function layout(): iterable
    {
        return [
            Layout::view('admin.valueMetric', [
                'title' => 'Информация о сотруднике',
                'subtitle' => 'Статистика сотрудника',
                'items' => $this->getStatistic()
            ]),

            Layout::legend('employee', [
                Sight::make('id', '№'),
                Sight::make('full_name', 'ФИО'),
                Sight::make('birthdate', 'Дата рождения'),
                Sight::make('passport_series', 'Серия и номер документа'),
                Sight::make('address', 'Адрес прописки'),
                Sight::make('employment_date', 'Дата принятия на работу'),
                Sight::make('dismissal_date', 'Дата увольнения'),
                Sight::make('Работает на пункте выдачи')
                    ->render(fn(Employee $employee) => Link::make($employee->point->name)->route('platform.point.show', $employee->point)),
            ]),
        ];
    }

    private function getStatistic(): array
    {
        return [
            [
                'title' => 'Всего продаж',
                'value' => $this->employee->orders->count(),
            ],
            [
                'title' => 'Максимально продаж в день',
                'value' => $this->employee->orders()
                    ->selectRaw('COUNT(*) as total_sales')
                    ->value('total_sales'),
            ],
            [
                'title' => 'Общая сумма продаж',
                'value' => $currentTotal = $this->employee->orders->max('total_amount'),
                'statDays' => 30,
                'statValue' => ($currentTotal - $this->employee
                        ->orders
                        ->where('created_at', '>', Carbon::now()->subDays(30))
                        ->max('total_amount'))
            ]
        ];
    }
}
