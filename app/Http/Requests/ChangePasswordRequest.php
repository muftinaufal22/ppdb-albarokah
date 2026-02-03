<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password; // Import class Password

class ChangePasswordRequest extends FormRequest
{
    /**
     * Izinkan user yang login untuk request ganti password.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Ini adalah aturan validasi untuk form ubah password.
     */
    public function rules(): array
    {
        return [
            // PERBAIKAN UNTUK TC-002:
            // Aturan 'current_password' bawaan Laravel
            // akan mengecek apakah password ini cocok dengan user yg sedang login.
            'current_password' => ['required', 'current_password'],

            // ATURAN UNTUK PASSWORD BARU
            'password' => [
                'required',
                'min:8',                // Memenuhi TC-004 (Minimal 8 karakter)
                'confirmed',            // Memenuhi TC-005 (Cocok dengan 'password_confirmation')
                'different:current_password' // PERBAIKAN UNTUK TC-003 (Harus BEDA dari 'current_password')
            ],

            // Aturan untuk field konfirmasi
            'password_confirmation' => ['required'],
        ];
    }
}