<?php

namespace App\Orchid\Screens\Shop;

use App\Models\Shop\Employee;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class EmployeeViewScreen extends Screen
{
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
        return [];
    }

    public function layout(): iterable
    {
        return [
            Layout::legend('employee', [
                Sight::make('id', '№'),
                Sight::make('full_name', 'ФИО'),
                Sight::make('birthdate', 'Дата рождения'),
                Sight::make('passport_series', 'Серия и номер документа'),
                Sight::make('address', 'Адрес прописки'),
                Sight::make('employment_date', 'Дата принятия на работу'),
                Sight::make('dismissal_date', 'Дата увольнения'),
                Sight::make('Работает на пункте выдачи')
                    ->render(fn (Employee $employee) => Link::make($employee->point->name)->route('platform.point.show', $employee->point)),
            ]),
        ];
    }
}
