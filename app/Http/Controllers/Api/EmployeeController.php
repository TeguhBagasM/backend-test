<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\GetEmployeesRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
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
        try {
            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
                'division_id' => $request->division_id, 
                'position' => $request->position,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('employees', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            Employee::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data karyawan berhasil ditambahkan',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem',
                'error' => $e->getMessage() 
            ], 500);
        }
    }
    
    public function update(UpdateEmployeeRequest $request, $id)
    {
        Log::info('Update method called', [
        'id' => $id,
        'request_data' => $request->all(),
    ]);
        try {
            $employee = Employee::findOrFail($id);

            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
                'division_id' => $request->division_id, 
                'position' => $request->position,
            ];

            if ($request->hasFile('image')) {
                if ($employee->image) {
                    Storage::disk('public')->delete($employee->image);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('employees', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            $employee->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data karyawan berhasil diperbarui',
                'data' => $employee->fresh() 
            ], 200);

        } catch (\Exception $e) {
        Log::error('Update error: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan sistem',
            'error' => $e->getMessage()
        ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);

            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            $employee->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data karyawan berhasil dihapus',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem',
            ], 500);
        }
    }
}