<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDriverProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;
        $currentPhone = $this->user()->phone_num;

        $rules = [
            'name' => 'required|string|max:255',
            'license_plate' => [
                'required',
                'regex:/^[A-Z]{1,2}[0-9]{1,4}[A-Z]{1,3}$/'
            ],
            'profile_pic' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ];

        if ($this->phone_num !== $currentPhone) {
            $rules['phone_num'] = [
                'required',
                'digits_between:8,15',
                Rule::unique('users', 'phone_num')->ignore($userId)
            ];
        } else {
            $rules['phone_num'] = 'required|digits_between:8,15';
        }

        if ($this->filled('password')) {
            $rules['password'] = [
                'required',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/'
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => __('request_register.validation.name.required'),
            'name.max' => __('request_register.validation.name.max'),
            'password.required' => __('request_register.validation.password.required'),
            'password.min' => __('request_register.validation.password.min'),
            'password.regex' => __('request_register.validation.password.regex'),
            'phone_num.required' => __('request_register.validation.phone_num.required'),
            'phone_num.digits_between' => __('request_register.validation.phone_num.digits_between'),
            'phone_num.unique' => __('request_register.validation.phone_num.unique'),
            'license_plate.required' => 'Nomor plat wajib diisi.',
            'license_plate.string' => 'Nomor plat harus berupa teks.',
            'profile_pic.image' => 'File foto profil harus berupa gambar.',
            'profile_pic.mimes' => 'Format gambar harus jpeg, jpg, png, atau webp.',
            'profile_pic.max' => 'Ukuran gambar maksimal 2MB.',
            'license_plate.regex' => 'Format plat nomor tidak valid. Contoh: B1234XYZ',
        ];
    }
}
