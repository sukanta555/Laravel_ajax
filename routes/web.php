<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/view', [EmployeeController::class, 'index'])->name('employee.view_emp');
Route::post('/add', [EmployeeController::class, 'create'])->name('employee.add_employee');

Route::get('/employee/{id}/edit', [EmployeeController::class, 'edit_emp'])->name('employee.edit');
Route::post('/employee/{id}', [EmployeeController::class, 'update_emp'])->name('employee.update');
Route::delete('/employee/{id}', [EmployeeController::class, 'delete_emp'])->name('employee.delete');