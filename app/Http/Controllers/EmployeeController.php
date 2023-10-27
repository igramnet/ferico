<?php

namespace App\Http\Controllers;

use App\DataTables\EmployeesDataTable;
use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{

    public function index(EmployeesDataTable $dataTable): object
    {
        return $dataTable->render('employees.index');
    }

    public function create(): object
    {
        return view('employees.create', [
            'companies' => Company::all(), // Получите список компаний для выпадающего списка
        ]);
    }

    public function store(Request $request): object
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'company_id' => 'required|exists:companies,id',
            'email' => 'required|email:rfc,dns|unique:employees,email',
            'phone' => 'required',
            'note' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Возвращаем ошибки в JSON
        }

        // Создайте нового сотрудника
        $employee = new Employees();
        $employee->first_name = $request->input('first_name');
        $employee->last_name = $request->input('last_name');
        $employee->company_id = $request->input('company_id');
        $employee->email = $request->input('email');
        $employee->phone = $request->input('phone');
        $employee->note = $request->input('note');

        // Сохраните нового сотрудника
        $employee->save();
        // Перенаправление после успешного сохранения
        return redirect()->route('employees.index')->with('success', 'Сотрудник успешно создан');
    }

    public function show(int $id): object
    {
    }

    public function edit(int $id): object
    {
        return view('employees.edit', [
            'employee' => Employees::findOrFail($id),
            'companies' => Company::all()
        ]);
    }

    public function update(Request $request, int $id): object
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'company_id' => 'required|exists:companies,id',
            'email' => 'required|email:rfc,dns|unique:employees,email,' . $id,
            'phone' => 'required',
            'note' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Возвращаем ошибки в JSON
        }

        $employee = Employees::find($id);

        if ($employee) {
            $employee->first_name = $request->input('first_name');
            $employee->last_name = $request->input('last_name');
            $employee->company_id = $request->input('company_id');
            $employee->email = $request->input('email');
            $employee->phone = $request->input('phone');
            $employee->note = $request->input('note');

            $employee->save();

            return redirect()->route('employees.index')->with('success', 'Данные сотрудника успешно обновлены');
        }

        return redirect()->route('employees.index')->with('error', 'Сотрудник не найден');
    }

    public function destroy($id): object
    {
        $employee = Employees::find($id);

        if ($employee) {
            $employee->delete();
            return redirect()->route('employees.index')->with('success', 'Сотрудник успешно удален');
        }

        return redirect()->route('employees.index')->with('error', 'Сотрудник не найден');
    }

}
