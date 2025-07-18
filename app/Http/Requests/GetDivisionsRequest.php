<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetDivisionsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'nullable|string',
        ];
    }
}