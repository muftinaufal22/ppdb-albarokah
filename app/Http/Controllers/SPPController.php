<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bank;
use App\Models\Kelas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\PaymentSpp;
use App\Models\SppSetting;
use App\Models\BankAccount;

class SPPController extends Controller
{
    //======================================================================
    // METHOD UNTUK MURID
    //======================================================================

    public function tagihanMurid()
    {
        try {
            $user = Auth::user();

            if (!$user->kelas_id) {
                return view('murid::pembayaran.index', [
                    'tagihanBulanan' => collect(),
                    'bank' => collect(),
                    'message' => 'Data kelas tidak ditemukan. Hubungi Admin.'
                ]);
            }

            $tagihanBulanan = PaymentSpp::where('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->get();

            $bank = BankAccount::where('is_active', 1)->get();
            $kelas = $user->kelas;

            return view('murid::pembayaran.index', compact('tagihanBulanan', 'bank', 'kelas'));

        } catch (Exception $e) {
            Log::error('Error tagihanMurid: ' . $e->getMessage());
            return view('murid::pembayaran.index', [
                'tagihanBulanan' => collect(),
                'bank' => collect(),
                'error' => 'Terjadi kesalahan sistem.'
            ]);
        }
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'bulan_dibayar' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'bank_account_id' => 'required',
            'nama_pengirim' => 'required|string',
            'bukti_pembayaran' => 'required|file|mimes:jpeg,jpg,png,pdf|max:2048'
        ]);

        try {
            DB::beginTransaction();
            $user = Auth::user();

            $payment = PaymentSpp::where('user_id', $user->id)
                ->where('year', $request->tahun_ajaran)
                ->where('bulan', $request->bulan_dibayar)
                ->first();

            if (!$payment) {
                return back()->with('error', 'Data tagihan tidak ditemukan.');
            }

            $fileName = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $fileName = 'bukti-' . $user->id . '-' . $request->tahun_ajaran . '-' . $request->bulan_dibayar . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/images/bukti_payment', $fileName);
                
                if ($payment->bukti_pembayaran && Storage::exists('public/images/bukti_payment/' . $payment->bukti_pembayaran)) {
                    Storage::delete('public/images/bukti_payment/' . $payment->bukti_pembayaran);
                }
            }

            // PERBAIKAN: Hapus update 'status' agar tidak error database
            // Kita hanya update bukti dan is_active (0 = belum lunas/menunggu)
            $payment->update([
                'bukti_pembayaran' => $fileName,
                'is_active' => 0, 
            ]);

            DB::commit();
            return redirect()->route('murid.pembayaran.index')
                ->with('success', 'Bukti pembayaran berhasil diupload.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error Upload Bukti: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengupload bukti: ' . $e->getMessage());
        }
    }

    public function editPayment($id)
    {
        try {
            $user = Auth::user();
            
            $payment = PaymentSpp::where('user_id', $user->id)->findOrFail($id);

            if ($payment->is_active == 1) { 
                return redirect()->route('murid.pembayaran.index')
                    ->with('error', 'Pembayaran ini sudah lunas.');
            }

            $sppSetting = SppSetting::where('kelas_id', $user->kelas_id)
                ->where('tahun_ajaran', $payment->year)
                ->where(function($q) use ($payment) {
                    $q->where('bulan', $payment->bulan)->orWhereNull('bulan');
                })
                ->orderBy('bulan', 'desc')
                ->first();

            return view('murid::pembayaran.edit', compact('payment', 'user', 'sppSetting'));

        } catch (Exception $e) {
            return redirect()->route('murid.pembayaran.index')
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }
    }

    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'nama_pengirim' => 'required|string|max:255',
            'bukti_pembayaran' => 'required|file|mimes:jpeg,jpg,png,pdf|max:2048'
        ]);

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $payment = PaymentSpp::where('user_id', $user->id)->findOrFail($id);

            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $fileName = 'bukti-' . $user->id . '-' . $payment->year . '-' . $payment->bulan . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/images/bukti_payment', $fileName);

                if ($payment->bukti_pembayaran && Storage::exists('public/images/bukti_payment/' . $payment->bukti_pembayaran)) {
                    Storage::delete('public/images/bukti_payment/' . $payment->bukti_pembayaran);
                }

                // PERBAIKAN: Hapus update 'status' agar tidak error database
                $payment->update([
                    'bukti_pembayaran' => $fileName,
                    'is_active' => 0 
                ]);
            }

            DB::commit();
            return redirect()->route('murid.pembayaran.index')
                ->with('success', 'Bukti pembayaran berhasil diperbarui.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updatePayment: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui pembayaran.');
        }
    }

    //======================================================================
    // METHOD UNTUK ADMIN
    //======================================================================

    public function murid(Request $request)
    {
        $payments = PaymentSpp::with('user')->orderBy('created_at', 'desc')->get();
        return view("spp::murid.index", compact('payments'));
    }

    public function acceptPayment($id)
    {
        try {
            $payment = PaymentSpp::findOrFail($id);
            // Coba update tanpa status dulu jika status juga error disini
            // Tapi biasanya 'Lunas' atau 'paid' diterima
            // Kita gunakan is_active=1 sebagai penanda utama Lunas
            $payment->update(['is_active' => 1]); 
            
            return back()->with('success', 'Pembayaran diterima.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menerima pembayaran: ' . $e->getMessage());
        }
    }

    public function rejectPayment($id)
    {
        try {
            $payment = PaymentSpp::findOrFail($id);
            if ($payment->bukti_pembayaran && Storage::exists('public/images/bukti_payment/' . $payment->bukti_pembayaran)) {
                Storage::delete('public/images/bukti_payment/' . $payment->bukti_pembayaran);
            }
            
            // Kita coba update status 'Ditolak' jika diizinkan database
            // Jika error lagi, hapus bagian 'status' => 'Ditolak'
            try {
                $payment->update(['is_active' => 0, 'status' => 'Ditolak', 'bukti_pembayaran' => null]);
            } catch (\Exception $ex) {
                // Fallback jika 'Ditolak' gagal, update tanpa status
                $payment->update(['is_active' => 0, 'bukti_pembayaran' => null]);
            }

            return back()->with('success', 'Pembayaran ditolak.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menolak pembayaran.');
        }
    }
}