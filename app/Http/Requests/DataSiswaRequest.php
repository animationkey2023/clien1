<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        $id = $this->route('id');

        return [
        'nama' => 'required|string|max:100',
        'kelas' => 'required|string',
        'nis' => [
            'required',
            'digits:18',
            'starts_with:131232750027',
            'unique:data_siswa,nis'
        ],
    ];
    
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama siswa wajib diisi.',
            'nis.required' => 'NIS tidak boleh kosong.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'nis.digits' => 'NIS harus berupa 7 digit angka.',
            'kelas.required' => 'Silakan pilih kelas siswa.',
            'kelas.in' => 'Kelas tidak valid.',
        ];
    }
}
