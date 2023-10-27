<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

LaravelLocalization::setLocale('en');

/** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localizationRedirect', 'localeViewPath']
    ], function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home', function () {
        return redirect('/', 301);
    });

    Route::resource('companies', CompanyController::class);
    Route::resource('employees', EmployeeController::class);

    Route::post('companies', [CompanyController::class, 'store'])->name('companies.store.ajax');
    Route::put('companies/{id}', [CompanyController::class, 'update'])->name('companies.update.ajax');

    Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store.ajax');
    Route::put('employees/{id}', [EmployeeController::class, 'update'])->name('employees.update.ajax');

});
