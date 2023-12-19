<?php

namespace App\Orchid\Screens\Shop;

use App\Http\Requests\admin\Shop\EditEmployeeRequest;
use App\Http\Requests\admin\Shop\EmployeeRequest;
use App\Models\Shop\Employee;
use App\Models\Shop\Point;
use App\Orchid\Layouts\Shop\EmployeeListLayout;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EmployeeScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'employees' => Employee::query()
                ->with(['point', 'orders', 'attachment'])
                ->withCount('orders')
                ->defaultSort('id', 'desc')
                ->paginate(25),
        ];
    }

    public function name(): ?string
    {
        return 'Штат сотрудников';
    }

    public function description(): ?string
    {
        return 'Список сотрудников, работающих в пунктах выдачи';
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить сотрудника')
                ->modal('createEmployeeModal')
                ->method('createEmployee')
                ->icon('bs.plus-circle'),
        ];
    }

    /**
     * @throws BindingResolutionException
     */
    public function layout(): iterable
    {
        return [
            EmployeeListLayout::class,

            Layout::modal('createEmployeeModal', [
                Layout::rows([
                    Input::make('employee.full_name')->title('ФИО')->required(),
                    Relation::make('employee.point_id')
                        ->fromModel(Point::class, 'name')
                        ->title('Выберите пункт выдачи'),
                    Input::make('employee.passport_series')->title('Серия и номер документа')->required(),

                    Input::make('employee.address')->title('Адрес прописки')->required(),

                    Input::make('employee.phone')->title('Контактный номер телефона')
                        ->mask('+ 9 (999) 999-9999')
                        ->required(),

                    Group::make([
                        DateTimer::make('employee.birthdate')
                            ->title('Дата рождения')
                            ->placeholder('Выберите дату')
                            ->allowInput(),

                        DateTimer::make('employee.employment_date')
                            ->title('Дата принятия на работу')
                            ->placeholder('Выберите дату')
                            ->allowInput(),
                    ]),

                    Upload::make('employee.image')->title('Фотография сотрудника')
                        ->maxFiles(1)
                        ->maxFileSize(5)
                        ->acceptedFiles('image/*')
                        ->help('Загрузите изображение не более 5 МБ'),

                ]),
            ])->title('Новый сотрудник')->applyButton('Добавить'),

            Layout::modal('editEmployeeModal', [
                Layout::rows([
                    Input::make('employee.full_name')->title('ФИО')->required(),
                    Relation::make('employee.point_id')
                        ->fromModel(Point::class, 'name')
                        ->title('Выберите пункт выдачи'),
                    Input::make('employee.passport_series')->title('Серия и номер документа')->required(),

                    Input::make('employee.address')->title('Адрес прописки')->required(),

                    Input::make('employee.phone')->title('Контактный номер телефона')
                        ->mask('+ 9 (999) 999-9999')
                        ->required(),

                    Input::make('employee.id')->hidden(),
                ]),

            ])->title('Редактирование данных сотрудника')
                ->async('asyncGetEmployee')
                ->applyButton('Сохранить изменения'),
        ];
    }

    public function createEmployee(EmployeeRequest $request): void
    {
        $birthdate = Carbon::parse($request->validated('employee.birthdate'));
        $employmentDate = Carbon::parse($request->validated('employee.employment_date'));

//        if ($employmentDate->diffInYears($birthdate) < 18) {
//           Toast::error('Возврат принятия сотрудника на работу не может быть меньше 18 лет');
//           return redirect()->back()->withErrors('Возврат принятия сотрудника на работу не может быть меньше 18 лет.');
//        }

        $validatedData = collect($request->validated('employee'))->except(['_token', 'image'])->toArray();

        $employee = Employee::query()->create($validatedData);
        $employee->attachment()->syncWithoutDetaching($request->input('employee.image')[0]);

        Toast::success("Сотрудник $employee->full_name успешно добавлен и привязан к пункту выдачи");
    }

    public function updateEmployee(EditEmployeeRequest $request): void
    {
        $birthdate = Carbon::parse($request->validated('employee.birthdate'));
        $employmentDate = Carbon::parse($request->validated('employee.employment_date'));

        $employee = Employee::findOrFail($request->input('employee.id'));

        $validatedData = collect($request->validated('employee'))->except(['_token', 'image'])->toArray();

        $employee->update($validatedData);

        Toast::success("Данные сотрудника $employee->full_name успешно изменены");
    }

    public function deleteEmployee(Employee $employee): RedirectResponse
    {
        $employee->delete();

        Toast::info('Данные сотрудника удалены');

        return redirect()->back();
    }

    public function asyncGetEmployee(Employee $employee): array
    {
        return [
            'employee' => $employee,
        ];
    }
}
