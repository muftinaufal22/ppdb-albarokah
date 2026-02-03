<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function __construct()
    {
        // Hanya guest yang bisa akses register
        $this->middleware('guest');
        
    }

    /**
     * Tampilkan form registrasi
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi
     */
    public function store(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            
            // Generate username otomatis dari email
            $username = $this->generateUniqueUsername($request->email);
            
            // Generate activation token
            $activationToken = Str::random(64);
            
            // Buat user baru
            $user = User::create([
                'name' => $request->name,
                'username' => $username,
                'email' => $request->email,
                'whatsapp' => $request->whatsapp,
                'password' => Hash::make($request->password),
                'role' => 'Orang Tua', // Default role
                'status' => 'Aktif', // Harus aktivasi email dulu
                'registration_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'activation_token' => $activationToken,
                'token_expires_at' => now()->addHours(24), // Token berlaku 24 jam
            ]);
            
            DB::commit();
            
            // Trigger event untuk kirim email verifikasi
            event(new Registered($user));
            
            // Log aktivitas
            Log::info('New user registered', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            // Redirect dengan pesan sukses
            return redirect()
                ->route('login')
                ->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk aktivasi akun.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);
            
            return redirect()
                ->back()
                ->withInput($request->except('password', 'confirm_password'))
                ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
        }
    }
    
    /**
     * Generate username unik dari email
     */
    private function generateUniqueUsername($email)
    {
        $baseUsername = explode('@', $email)[0];
        $username = $baseUsername;
        $counter = 1;
        
        // Cek apakah username sudah ada, tambahkan angka jika perlu
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
}