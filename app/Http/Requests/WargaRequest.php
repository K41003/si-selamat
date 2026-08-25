<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isStaff() ?? false;
    }

    public function rules(): array
    {
        // Ambil id warga jika sedang update (route model binding: {warga})
        $wargaId = $this->route('warga')?->id;

        return [
            'nik' => [
                'required',
                'digits:16',
                Rule::unique('warga', 'nik')->ignore($wargaId),
            ],
            'no_kk' => ['nullable', 'digits:16'],
            'nama' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'jenis_kelamin' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'agama' => ['nullable', 'string', 'max:100'],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
            'status_perkawinan' => ['nullable', Rule::in(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'])],
            'alamat' => ['nullable', 'string'],
            'rt' => ['nullable', 'string', 'max:5'],
            'rw' => ['nullable', 'string', 'max:5'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar di sistem.',
            'no_kk.digits' => 'Nomor KK harus terdiri dari 16 digit angka.',
            'nama.required' => 'Nama wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir tidak valid.',
        ];
    }
}
