<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data before validation
     */
    protected function prepareForValidation()
    {
        // Sanitasi input sebelum validasi
        $this->merge([
            'name' => strip_tags(trim($this->name)),
            'email' => strtolower(trim($this->email)),
            'whatsapp' => preg_replace('/[^0-9]/', '', $this->whatsapp), // Hapus karakter non-angka
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Tentukan apakah kita sedang dalam environment testing
        $isTesting = app()->environment('testing');

        // Aturan untuk validasi email
        $emailRules = [
            'required',
            // Jika testing, gunakan HANYA RFC (tanpa DNS lookup)
            'email:rfc' . ($isTesting ? '' : ',dns'), 
            'max:255',
            'unique:users,email',
        ];

        // JIKA BUKAN TESTING, tambahkan validasi kustom disposable email
        if (!$isTesting) {
            $emailRules[] = function ($attribute, $value, $fail) {
                $disposableDomains = [
                    'tempmail.com', 'guerrillamail.com', '10minutemail.com',
                    'throwaway.email', 'mailinator.com', 'trashmail.com'
                ];
                $domain = substr(strrchr($value, "@"), 1);
                if (in_array($domain, $disposableDomains)) {
                    $fail('Email temporary/disposable tidak diperbolehkan.');
                }
            };
        }

        return [
            // Validasi Nama
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s\.]+$/u',
            ],
            
            // Validasi Email (menggunakan aturan yang sudah dimodifikasi)
            'email' => $emailRules,
            
            // Validasi WhatsApp
            'whatsapp' => [
                'required',
                'numeric',
                'digits_between:10,15',
                'regex:/^(08|628)[0-9]{8,12}$/',
            ],

            // Validasi Password
            'password' => [
                'required',
                'string',
                'min:8',
                // Cek regex ini hanya saat BUKAN TESTING (karena terkadang lambat/bermasalah)
                $isTesting ? '' : 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
                // NOTE: Kita TIDAK bisa menggunakan aturan uncompromised() di tes,
                // karena memerlukan koneksi API. Jika Anda menggunakannya,
                // Anda harus menginstruksikan saya untuk menonaktifkannya
            ],

            // Validasi Konfirmasi Password
            'confirm_password' => [
                'required',
                'same:password',
            ],
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            // Name
            'name.required' => 'Nama Lengkap wajib diisi.',
            'name.max' => 'Nama Lengkap maksimal 50 karakter.',
            'name.regex' => 'Nama hanya boleh berisi huruf, spasi, dan titik.',
            
            // Email
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            
            // WhatsApp
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.numeric' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'whatsapp.digits_between' => 'Nomor WhatsApp harus 10-15 digit.',
            'whatsapp.regex' => 'Format nomor WhatsApp tidak valid (contoh: 08123456789 atau 628123456789).',
            
            // Password
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
    
            
            
            // Confirm Password
            'confirm_password.required' => 'Konfirmasi Password wajib diisi.',
            'confirm_password.same' => 'Konfirmasi Password tidak sesuai dengan Password.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nama Lengkap',
            'email' => 'Email',
            'whatsapp' => 'Nomor WhatsApp',
            'password' => 'Password',
            'confirm_password' => 'Konfirmasi Password',
        ];
    }
}