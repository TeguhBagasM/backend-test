<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetDivisionsRequest;
use App\Models\Division;

class DivisionController extends Controller
{
    public function index(GetDivisionsRequest $request)
    {
        $query = Division::query();
        
        if ($request->has('name') && $request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        $divisions = $query->paginate(10);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Divisions retrieved successfully',
            'data' => [
                'divisions' => $divisions->items(),
            ],
            'pagination' => [
                'current_page' => $divisions->currentPage(),
                'last_page' => $divisions->lastPage(),
                'per_page' => $divisions->perPage(),
                'total' => $divisions->total(),
                'from' => $divisions->firstItem(),
                'to' => $divisions->lastItem(),
            ],
        ]);
    }
}