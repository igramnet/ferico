<?php

namespace App\Http\Controllers;

use App\DataTables\EmployeesDataTable;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DataTables\CompaniesDataTable;

class CompanyController extends Controller
{

    public function index(CompaniesDataTable $dataTable): object
    {
        return $dataTable->render('companies.index', [
            'companies' => Company::all(),
        ]);
    }

    public function create(): object
    {
        return view('companies.create');
    }

    public function store(Request $request): object
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:companies,email',
            'phone' => 'required',
            'website' => 'required|url',
            'logo' => 'required|url',
            'note' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Возвращаем ошибки в JSON
        }

        // Создайте новую компанию
        $company = new Company();
        $company->name = $request->input('name');
        $company->email = $request->input('email');
        $company->phone = $request->input('phone');
        $company->website = $request->input('website');
        $company->note = $request->input('note');
        $company->logo = $request->input('logo');

        // Сохраните новую компанию
        $company->save();
        // Перенаправление после успешного сохранения
        return redirect()->route('companies.index')->with('success', 'Компания создана успешно');
    }

    public function show(int $id): object
    {
    }

    public function edit(int $id): object
    {
        return view('companies.edit', [
            'company' => Company::findOrFail($id),
        ]);
    }

    public function update(Request $request, int $id): object
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:companies,email,' . $id,
            'phone' => 'required',
            'website' => 'required|url',
            'logo' => 'required|url',
            'note' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Возвращаем ошибки в JSON
        }

        // Получите компанию для обновления
        $company = Company::find($id);

        if ($company) {
            // Обновить данные компании
            $company->name = $request->input('name');
            $company->email = $request->input('email');
            $company->phone = $request->input('phone');
            $company->website = $request->input('website');
            $company->note = $request->input('note');
            $company->logo = $request->input('logo');

            // Сохраните обновленные данные компании
            $company->save();
            // Перенаправление после успешного обновления
            return redirect()->route('companies.index')->with('success', 'Данные компании успешно обновлены');
        }

        return redirect()->route('companies.index')->with('error', 'Компания не найдена');
    }

    public function destroy(int $id): object
    {
        $company = Company::findOrFail($id);

        // Проверка, есть ли связанные сотрудники
        if ($company->employees->count() > 0) {
            return redirect()->route('companies.index')->with('error', 'Нельзя удалить компанию, у которой есть связанные сотрудники');
        }

        // Если связанных сотрудников нет, можно удалить компанию
        $company->delete();

        // Перенаправление после успешного удаления
        return redirect()->route('companies.index')->with('success', 'Компания удалена успешно');
    }
}
