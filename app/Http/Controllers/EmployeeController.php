<?php
namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Employee::with('division');

            // Filter by name if provided
            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            // Filter by division if provided
            if ($request->filled('division_id')) {
                $query->where('division_id', $request->division_id);
            }

            $employees = $query->paginate(10);

            $employeeData = $employees->items();
            foreach ($employeeData as $employee) {
                $employee->image = $employee->image ? url('storage/' . $employee->image) : null;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data karyawan berhasil diambil',
                'data' => [
                    'employees' => $employeeData
                ],
                'pagination' => [
                    'current_page' => $employees->currentPage(),
                    'per_page' => $employees->perPage(),
                    'total' => $employees->total(),
                    'last_page' => $employees->lastPage(),
                    'from' => $employees->firstItem(),
                    'to' => $employees->lastItem(),
                    'has_more_pages' => $employees->hasMorePages(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem',
            ], 500);
        }
    }

    public function store(EmployeeRequest $request)
    {
        try {
            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
                'division_id' => $request->division,
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
            ], 500);
        }
    }

    public function update(EmployeeRequest $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);

            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
                'division_id' => $request->division,
                'position' => $request->position,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
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
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);

            // Delete image if exists
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