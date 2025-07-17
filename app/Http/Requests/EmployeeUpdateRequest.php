<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'nullable|image|max:2048',
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'division' => 'sometimes|uuid|exists:divisions,id',
            'position' => 'sometimes|string|max:255',
        ];
    }
}
