<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetEmployeesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'division_id' => 'nullable|string|exists:divisions,id',
        ];
    }
}