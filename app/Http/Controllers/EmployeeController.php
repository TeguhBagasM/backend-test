<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('division');
        
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        if ($request->has('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        $employees = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Employees retrieved successfully',
            'data' => [
                'employees' => $employees->items()->map(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'image' => $employee->image ? Storage::url($employee->image) : null,
                        'name' => $employee->name,
                        'phone' => $employee->phone,
                        'division' => [
                            'id' => $employee->division->id,
                            'name' => $employee->division->name,
                        ],
                        'position' => $employee->position,
                    ];
                })->toArray(),
            ],
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
                'last_page' => $employees->lastPage(),
            ]
        ]);
    }

    public function store(EmployeeStoreRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('employees', 'public');
        }

        $data['division_id'] = $data['division'];
        unset($data['division']);

        Employee::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Employee created successfully'
        ]);
    }

    public function update(EmployeeUpdateRequest $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            $data['image'] = $request->file('image')->store('employees', 'public');
        }

        if (isset($data['division'])) {
            $data['division_id'] = $data['division'];
            unset($data['division']);
        }

        $employee->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Employee updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        
        if ($employee->image) {
            Storage::disk('public')->delete($employee->image);
        }
        
        $employee->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Employee deleted successfully'
        ]);
    }
}