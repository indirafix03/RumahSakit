<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->id;
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique(User::class)->ignore($userId)
            ],
            'no_telepon' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+]+$/'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ];

        // Only add spesialisasi rule for dokter role
        if ($this->user()->isDokter()) {
            $rules['spesialisasi'] = ['nullable', 'string', 'max:100'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'no_telepon.max' => 'Nomor telepon maksimal 20 karakter.',
            'no_telepon.regex' => 'Format nomor telepon tidak valid. Hanya angka dan tanda + yang diperbolehkan.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',
            'spesialisasi.max' => 'Spesialisasi maksimal 100 karakter.',
            'bio.max' => 'Bio maksimal 1000 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama lengkap',
            'email' => 'alamat email',
            'no_telepon' => 'nomor telepon',
            'alamat' => 'alamat',
            'spesialisasi' => 'spesialisasi',
            'bio' => 'bio',
        ];
    }
}