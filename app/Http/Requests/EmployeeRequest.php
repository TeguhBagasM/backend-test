<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'division' => 'required|exists:divisions,id',
            'position' => 'required|string|max:255',
        ];

        if ($this->isMethod('post')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'phone.required' => 'Nomor telepon wajib diisi',
            'division.required' => 'Divisi wajib dipilih',
            'division.exists' => 'Divisi tidak valid',
            'position.required' => 'Posisi wajib diisi',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}