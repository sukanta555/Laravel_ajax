<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index(){
        //$employees = Employee::all();
        $employees = Employee::paginate(3);
        return view('user.add_emp', compact('employees'));
    }

    public function create(Request $request){
        $employees = new Employee();
        $employees->name = $request->post('name');
        $employees->email = $request->post('email');
        $employees->mob = $request->post('mob');
        $employees->save();

        return response()->json(['success' => 'Employees added successfully!', 'data' => $employees]);
    }

    public function edit_emp($id){
        $employees = Employee::find($id);
        return response()->json($employees);
    }

    public function update_emp(Request $request, $id){
        $employees = Employee::find($id);
        $employees->name = $request->post('name');
        $employees->email = $request->post('email');
        $employees->mob = $request->post('mob');       
        $employees->save();

        return response()->json(['success' => 'Employees updated successfully!', 'data' => $employees]);
    }

    public function delete_emp($id){
        $employees = Employee::find($id);
        $employees->delete();

        return response()->json(['success' => 'Employees deleted successfully!']);
    }
}
