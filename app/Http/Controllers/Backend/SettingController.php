<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Kelas;
use App\Models\Setting;
use App\Models\User;
use ErrorException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\SPPControllerSPP\Entities\BankAccount;
use App\Models\BankAccount as ModelsBankAccount;
use App\Models\PaymentSpp;
use App\Models\SppSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $user = User::whereId(Auth::id())->first();
        $bank = Bank::all();

        if ($user->role === 'murid') {
            $spp = SppSetting::with('kelas')
                ->where('kelas_id', $user->kelas_id)
                ->orderBy('created_at', 'desc')
                ->get();

            $kelas = Kelas::where('id', $user->kelas_id)->get();
            $selectedKelas = $user->kelas_id;
        } else {
            $kelas = Kelas::all();
            $selectedKelas = $request->get('kelas_id');

            $spp = SppSetting::with('kelas')
                ->when($selectedKelas, function ($query) use ($selectedKelas) {
                    return $query->where('kelas_id', $selectedKelas);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('backend.settings.index', compact('bank', 'spp', 'kelas', 'selectedKelas'));
    }

    public function addBank(Request $request)
    {
        try {
            ModelsBankAccount::create([
                'user_id' => Auth::id(),
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
                'bank_name' => $request->bank_name,
                'is_active' => $request->is_active ?? 1,
                'is_primary' => 1
            ]);
            Session::flash('success', 'Akun Bank Berhasil Ditambah.');
            return back();
        } catch (ErrorException $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    public function updateBank(Request $request, $id)
    {
        try {
            $bankAccount = ModelsBankAccount::findOrFail($id);
            $bankAccount->update([
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
                'bank_name' => $request->bank_name,
                'is_active' => $request->is_active ?? 0
            ]);
            Session::flash('success', 'Akun Bank Berhasil Diupdate.');
            return back();
        } catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    public function deleteBank($id)
    {
        try {
            $bankAccount = ModelsBankAccount::findOrFail($id);
            $bankAccount->delete();
            Session::flash('success', 'Akun Bank Berhasil Dihapus.');
            return back();
        } catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    public function storeSppSetting(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:0',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|integer',
            'bulan' => 'nullable|string|max:20'
        ]);

        try {
            $existingSetting = SppSetting::where('kelas_id', $request->kelas_id)
                ->where('tahun_ajaran', $request->tahun_ajaran)
                ->where(function ($query) use ($request) {
                    if ($request->bulan) {
                        $query->where('bulan', $request->bulan);
                    } else {
                        $query->whereNull('bulan');
                    }
                })
                ->first();

            if ($existingSetting) {
                Session::flash('error', 'Setting SPP untuk kelas, tahun ajaran, dan bulan tersebut sudah ada.');
                return back()->withInput();
            }

            DB::transaction(function () use ($request) {
                $sppSetting = SppSetting::create([
                    'amount' => $request->amount,
                    'kelas_id' => $request->kelas_id,
                    'tahun_ajaran' => $request->tahun_ajaran,
                    'bulan' => $request->bulan,
                    'update_by' => Auth::id(),
                    'is_active' => 1
                ]);

                $users = User::where('kelas_id', $sppSetting->kelas_id)->get();

                foreach ($users as $user) {
                    PaymentSpp::create([
                        'user_id' => $user->id,
                        'is_active' => false,
                        'year' => $sppSetting->tahun_ajaran,
                        'bulan' => $sppSetting->bulan,
                        'amount' => $request->amount
                    ]);
                }
            });

            Session::flash('success', 'Setting SPP berhasil ditambah. Sekarang murid dapat melihat tagihan SPP di halaman pembayaran mereka.');
            return back();
        } catch (Exception $e) {
            Log::error('Error storing SPP Setting: ' . $e->getMessage());
            Session::flash('error', 'Terjadi kesalahan saat menyimpan setting SPP: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function updateSppSetting(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|integer|min:0',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string',
            'bulan' => 'nullable|string'
        ]);

        try {
            $sppSetting = SppSetting::findOrFail($id);

            $existingSetting = SppSetting::where('kelas_id', $request->kelas_id)
                ->where('tahun_ajaran', $request->tahun_ajaran)
                ->where('id', '!=', $id)
                ->where(function ($query) use ($request) {
                    if ($request->bulan) {
                        $query->where('bulan', $request->bulan);
                    } else {
                        $query->whereNull('bulan');
                    }
                })
                ->first();

            if ($existingSetting) {
                Session::flash('error', 'Setting SPP untuk kelas, tahun ajaran, dan bulan tersebut sudah ada.');
                return back()->withInput();
            }

            DB::transaction(function () use ($sppSetting, $request) {
                PaymentSpp::where('year', $sppSetting->tahun_ajaran)
                    ->where('bulan', $sppSetting->bulan)
                    ->whereIn('user_id', User::where('kelas_id', $sppSetting->kelas_id)->pluck('id'))
                    ->delete();

                $sppSetting->update([
                    'amount' => $request->amount,
                    'kelas_id' => $request->kelas_id,
                    'tahun_ajaran' => $request->tahun_ajaran,
                    'bulan' => $request->bulan,
                    'update_by' => Auth::id()
                ]);

                $users = User::where('kelas_id', $request->kelas_id)->get();

                foreach ($users as $user) {
                    PaymentSpp::create([
                        'user_id' => $user->id,
                        'year' => $request->tahun_ajaran,
                        'is_active' => false,
                        'bulan' => $request->bulan,
                        'amount' => $request->amount
                    ]);
                }
            });

            Session::flash('success', 'Setting SPP Berhasil Diupdate.');
            return back();
        } catch (Exception $e) {
            Log::error('Error updating SPP Setting: ' . $e->getMessage());
            Session::flash('error', 'Terjadi kesalahan saat mengupdate setting SPP: ' . $e->getMessage());
            return back();
        }
    }

    public function deleteSppSetting($id)
    {
        try {
            $sppSetting = SppSetting::findOrFail($id);

            DB::transaction(function () use ($sppSetting) {
                PaymentSpp::where('year', $sppSetting->tahun_ajaran)
                    ->where('bulan', $sppSetting->bulan)
                    ->whereIn('user_id', User::where('kelas_id', $sppSetting->kelas_id)->pluck('id'))
                    ->delete();

                $sppSetting->delete();
            });

            Session::flash('success', 'Setting SPP Berhasil Dihapus.');
            return back();
        } catch (Exception $e) {
            Log::error('Error deleting SPP Setting: ' . $e->getMessage());
            Session::flash('error', 'Terjadi kesalahan saat menghapus setting SPP: ' . $e->getMessage());
            return back();
        }
    }


    public function notifications(Request $request)
    {
        try {
            $setting = Setting::where('user_id', Auth::id())->first();
            $setting->isEmail = $request->isEmail ?? 0;
            $setting->update();
            Session::flash('success', 'Setting Berhasil Diupdate.');
            return back();
        } catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    /**
     * Method untuk mengecek apakah ada setting SPP yang aktif untuk kelas tertentu
     */
    public function checkActiveSppSettings($kelasId)
    {
        $tahunAjaranAktif = '2024/2025'; // Bisa dibuat dinamis

        $settings = SppSetting::where('kelas_id', $kelasId)
            ->where('tahun_ajaran', $tahunAjaranAktif)
            ->where('is_active', 1)
            ->get();

        return response()->json([
            'success' => true,
            'count' => $settings->count(),
            'settings' => $settings
        ]);
    }
}
