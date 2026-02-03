<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\SppSetting;
use App\Models\DetailPaymentSpp;
use App\Models\PaymentSpp;
use App\Models\BankAccount;
use App\Http\Requests\ConfirmPaymentRequest;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Menampilkan daftar tagihan SPP untuk murid berdasarkan setting SPP yang dibuat TU.
     */
    public function index()
    {
        try {
            $murid = Auth::user();
            $tahunAjaranAktif = '2024/2025';

            // Validasi apakah murid memiliki kelas
            if (!$murid->kelas_id) {
                return view('murid::pembayaran.index', [
                    'sppSettings' => collect(),
                    'payments' => collect(),
                    'tahunAjaranAktif' => $tahunAjaranAktif,
                    'message' => 'Data kelas Anda tidak ditemukan. Silakan hubungi Administrator.'
                ]);
            }

            // Ambil setting SPP berdasarkan kelas murid dan tahun ajaran aktif
            $sppSettings = SppSetting::with('kelas')
                ->where('kelas_id', $murid->kelas_id)
                ->where('tahun_ajaran', $tahunAjaranAktif)
                ->where('is_active', 1) // Hanya yang aktif
                ->orderBy('bulan')
                ->get();

            // Jika tidak ada setting SPP untuk kelas ini
            if ($sppSettings->isEmpty()) {
                return view('murid::pembayaran.index', [
                    'sppSettings' => collect(),
                    'payments' => collect(),
                    'tahunAjaranAktif' => $tahunAjaranAktif,
                    'message' => 'Setting SPP untuk kelas Anda belum tersedia. Silakan hubungi Administrator untuk mengatur pembayaran SPP.'
                ]);
            }

            // Ambil data pembayaran yang sudah ada
            $payments = DetailPaymentSpp::with('payment')
                ->where('user_id', $murid->id)
                ->whereHas('payment', function ($query) use ($tahunAjaranAktif) {
                    $query->where('year', $tahunAjaranAktif);
                })
                ->get()
                ->keyBy('month');

            // Ambil data rekening bank untuk pembayaran
            $bankAccounts = BankAccount::where('is_active', 1)->get();

            return view('murid::pembayaran.index', compact(
                'sppSettings',
                'payments',
                'tahunAjaranAktif',
                'bankAccounts'
            ));
        } catch (\Exception $e) {
            Log::error('Error in PembayaranController@index: ' . $e->getMessage());
            return view('murid::pembayaran.index', [
                'sppSettings' => collect(),
                'payments' => collect(),
                'tahunAjaranAktif' => '2024/2025',
                'error' => 'Terjadi kesalahan saat memuat data pembayaran. Silakan refresh halaman.'
            ]);
        }
    }

    /**
     * Membuat record pembayaran baru saat tombol "Bayar" ditekan.
     * Diperbaiki untuk menggunakan setting SPP dari TU
     */
    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'spp_setting_id' => 'required|exists:spp_settings,id',
            'year' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $murid = Auth::user();

            // Validasi setting SPP milik kelas murid
            $sppSetting = SppSetting::where('id', $request->spp_setting_id)
                ->where('kelas_id', $murid->kelas_id)
                ->where('is_active', 1)
                ->first();

            if (!$sppSetting) {
                return back()->with('error', 'Setting SPP tidak valid atau tidak tersedia untuk kelas Anda.');
            }

            // Cek apakah sudah ada pembayaran untuk bulan ini
            $existingPayment = DetailPaymentSpp::where('user_id', $murid->id)
                ->where('month', $request->month)
                ->whereHas('payment', function ($query) use ($request) {
                    $query->where('year', $request->year);
                })
                ->first();

            if ($existingPayment) {
                if ($existingPayment->status === 'paid') {
                    return back()->with('error', 'Pembayaran untuk bulan ' . $request->month . ' sudah lunas.');
                } else {
                    // Jika status pending atau rejected, redirect ke edit
                    return redirect()->route('pembayaran.edit', $existingPayment->id)
                        ->with('info', 'Anda sudah memiliki tagihan untuk bulan ini. Silakan lengkapi konfirmasi pembayaran.');
                }
            }

            // Buat atau ambil parent payment
            $parentPayment = PaymentSpp::firstOrCreate(
                [
                    'year' => $request->year,
                    'user_id' => $murid->id
                ],
                [
                    'is_active' => 1,
                    'total_amount' => 0,
                    'status' => 'pending'
                ]
            );

            // Buat detail pembayaran baru
            $newPayment = DetailPaymentSpp::create([
                'payment_id' => $parentPayment->id,
                'user_id' => $murid->id,
                'month' => $request->month,
                'amount' => $sppSetting->amount, // Gunakan amount dari setting SPP
                'status' => 'unpaid',
                'spp_setting_id' => $sppSetting->id // Simpan referensi ke setting SPP
            ]);

            // Update total amount di parent payment
            $parentPayment->increment('total_amount', $sppSetting->amount);

            DB::commit();

            return redirect()->route('pembayaran.edit', $newPayment->id)
                ->with('success', 'Tagihan berhasil dibuat. Silakan lanjutkan konfirmasi pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in PembayaranController@store: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuat tagihan pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan form konfirmasi pembayaran.
     */
    public function edit($id)
    {
        try {
            $user = Auth::user();

            // Ambil data pembayaran milik user saat ini dan berdasarkan ID setting (jika perlu)
            $payment = PaymentSpp::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$payment) {
                return redirect()->route('pembayaran.index')
                    ->with('error', 'Data pembayaran tidak ditemukan atau bukan milik Anda.');
            }

            // Cek status pembayaran
            if ($payment->amount > 0 && $payment->bukti_pembayaran != null) {
                return redirect()->route('pembayaran.index')
                    ->with('error', 'Pembayaran untuk bulan ini sudah lunas.');
            }

            // Ambil rekening tujuan
            $accountbanks = BankAccount::where('is_active', 1)->get();

            // Fallback jika tidak ada bank account di modul SPP
            if ($accountbanks->isEmpty()) {
                $adminUser = User::where('role', 'Admin')->with('banks')->first();
                if ($adminUser && $adminUser->banks) {
                    $accountbanks = $adminUser->banks;
                }
            }

            // Validasi apakah ada rekening tujuan
            if ($accountbanks->isEmpty()) {
                return back()->with('error', 'Rekening tujuan pembayaran tidak ditemukan. Hubungi Administrator.');
            }

            $sppSetting = SppSetting::where('kelas_id', $user->kelas_id)
                ->where('bulan', $payment->bulan)
                ->where('tahun_ajaran', $payment->year)
                ->first();

            // Ambil daftar bank untuk dropdown pengirim
            $bank = Bank::all();

            return view('murid::pembayaran.edit', compact('payment', 'sppSetting', 'user', 'accountbanks', 'bank'));
        } catch (\Exception $e) {
            Log::error('Error in PembayaranController@edit: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengambil data pembayaran.');
        }
    }

    /**
     * Update konfirmasi pembayaran dari murid
     */
    public function update(ConfirmPaymentRequest $request, DetailPaymentSpp $pembayaran)
    {
        try {
            DB::beginTransaction();

            // Pastikan pembayaran milik user yang login
            if ($pembayaran->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access');
            }

            // Handle file upload
            $file_payment = $pembayaran->file; // Keep existing file as default
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $file_payment = 'payment-' . time() . '-' . Auth::id() . "." . $file->getClientOriginalExtension();
                $tujuan_upload = 'public/images/bukti_payment';
                $file->storeAs($tujuan_upload, $file_payment);

                // Hapus file lama jika ada
                if ($pembayaran->file && Storage::exists('public/images/bukti_payment/' . $pembayaran->file)) {
                    Storage::delete('public/images/bukti_payment/' . $pembayaran->file);
                }
            }
            // Update data pembayaran
            $pembayaran->update([
                'file' => $file_payment,
                'sender' => $request->sender,
                'status' => 'pending',
                'admin_note' => null,
                'approved_by' => null,
                'approved_at' => null
            ]);

            DB::commit();

            Session::flash('success', 'Bukti pembayaran berhasil dikirim ulang. Mohon tunggu konfirmasi dari Administrator.');
            return redirect()->route('murid.pembayaran.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in PembayaranController@update: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengirim bukti pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Hapus pembayaran (opsional)
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $payment = DetailPaymentSpp::where('user_id', Auth::id())->findOrFail($id);

            // Hanya bisa dihapus jika status masih unpaid atau rejected
            if (!in_array($payment->status, ['unpaid', 'rejected'])) {
                return back()->with('error', 'Pembayaran dengan status ' . $payment->status . ' tidak dapat dihapus.');
            }

            // Hapus file bukti jika ada
            if ($payment->file && Storage::exists('public/images/bukti_payment/' . $payment->file)) {
                Storage::delete('public/images/bukti_payment/' . $payment->file);
            }

            // Update total amount di parent payment
            $parentPayment = $payment->payment;
            if ($parentPayment) {
                $parentPayment->decrement('total_amount', $payment->amount);
            }

            $payment->delete();

            DB::commit();

            return redirect()->route('murid.pembayaran.index')
                ->with('success', 'Tagihan pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in PembayaranController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus tagihan.');
        }
    }
}
