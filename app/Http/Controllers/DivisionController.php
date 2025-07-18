<?php

namespace App\Http\Controllers;

use App\Http\Requests\DivisionRequest;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index(DivisionRequest $request)
    {
        try {
            $query = Division::query();

            // Filter by name if provided
            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            $divisions = $query->paginate(10);

            return response()->json([
                'status' => 'success',
                'message' => 'Data divisi berhasil diambil',
                'data' => [
                    'divisions' => $divisions->items()
                ],
                'pagination' => [
                    'current_page' => $divisions->currentPage(),
                    'per_page' => $divisions->perPage(),
                    'total' => $divisions->total(),
                    'last_page' => $divisions->lastPage(),
                    'from' => $divisions->firstItem(),
                    'to' => $divisions->lastItem(),
                    'has_more_pages' => $divisions->hasMorePages(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem',
            ], 500);
        }
    }
}