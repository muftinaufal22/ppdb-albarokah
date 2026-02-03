<?php

namespace App\Http\Controllers;

use App\Models\dataMurid;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\PpdbSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only(['registerView', 'registerStore']);
    }

    public function registerView()
    {
        if (!PpdbSetting::isOpen()) {
            return view('ppdb.auth.closed', [
                'message' => PpdbSetting::getClosedMessage(),
            ]);
        }
        return view('ppdb.auth.register');
    }

    public function registerStore(RegisterRequest $request)
    {
        if (!PpdbSetting::isOpen()) {
            Session::flash('error', PpdbSetting::getClosedMessage() ?? 'Pendaftaran PPDB saat ini ditutup.');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            $username = explode(' ', trim($request->name))[0];

            $register = new User();
            $register->name     = $request->name;
            $register->username = $username;
            $register->email    = $request->email;
            $register->role     = 'Guest';
            $register->status   = 'Aktif';
            $register->password = bcrypt($request->password);
            $register->save();

            if ($register) {
                $murid = new dataMurid();
                $murid->user_id       = $register->id;
                $murid->whatsapp      = $request->whatsapp;
                $murid->jenis_kelamin = $request->jenis_kelamin;
                $murid->save();
            }

            $register->assignRole('Guest');

            DB::commit();

            Session::flash('success', 'Success, Data Berhasil dikirim!');
            return redirect()->route('login');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Registrasi Gagal: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->except('password', 'confirm_password'),
            ]);

            // Jika sedang testing, kita lemparkan agar error terlihat
            if (App::environment('testing')) {
                throw $e;
            }

            Session::flash('error', 'Terjadi kesalahan saat proses registrasi. Silakan coba lagi.');
            return redirect()->back()->withInput($request->except('password', 'confirm_password'));
        }
    }
}