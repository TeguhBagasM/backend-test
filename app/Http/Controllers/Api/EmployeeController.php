<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\GetEmployeesRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(GetEmployeesRequest $request)
    {
        $query = Employee::with('division');
        
        if ($request->has('name') && $request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        if ($request->has('division_id') && $request->division_id) {
            $query->where('division_id', $request->division_id);
        }
        
        $employees = $query->paginate(10);
        
        $employeeData = $employees->items();
        foreach ($employeeData as $employee) {
            $employee->image = $employee->image ? url('storage/' . $employee->image) : null;
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Employees retrieved successfully',
            'data' => [
                'employees' => $employeeData,
            ],
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
                'from' => $employees->firstItem(),
                'to' => $employees->lastItem(),
            ],
        ]);
    }
    
    public function store(CreateEmployeeRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('employees', 'public');
            $data['image'] = $imagePath;
        }
        
        $data['division_id'] = $data['division'];
        unset($data['division']);
        
        Employee::create($data);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Employee created successfully',
        ], 201);
    }
    
    public function update(UpdateEmployeeRequest $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            
            $imagePath = $request->file('image')->store('employees', 'public');
            $data['image'] = $imagePath;
        }
        
        $data['division_id'] = $data['division'];
        unset($data['division']);
        
        $employee->update($data);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Employee updated successfully',
        ]);
    }
    
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        
        // Delete image if exists
        if ($employee->image) {
            Storage::disk('public')->delete($employee->image);
        }
        
        $employee->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Employee deleted successfully',
        ]);
    }
}