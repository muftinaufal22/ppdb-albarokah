<?php
namespace App\Http\Controllers;

use App\Models\dataMurid;
use App\Models\User;
use ErrorException;
use Illuminate\Http\Request;
// FIX: Menggunakan Request yang benar sesuai komentar
use App\Http\Requests\BerkasMuridRequest;
use App\Http\Requests\DataMuridRequest;
use App\Http\Requests\DataOrtuRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BerkasMurid;
use App\Models\DataOrangTua;
use Illuminate\Support\Facades\Session;

class PendaftaranController extends Controller
{

    // Data Murid
    public function index()
    {
        $user = User::with('muridDetail','dataOrtu')->where('status','Aktif')->where('id',Auth::id())->first();

        // Jika data murid sudah lengkap dan bukan dari edit
        if ($user->muridDetail->agama && !request()->get('edit')) {
        return redirect('ppdb/form-data-orangtua');
        }
        return view('ppdb::backend.pendaftaran.index', compact('user'));
    }

    // Update Data Murid
    // FIX: Menggunakan DataMuridRequest
    public function update(DataMuridRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $user = User::with('muridDetail')->where('id',$id)->first();
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->update();

            if ($user) {
                $murid = dataMurid::where('user_id',$id)->first();
                $murid->tempat_lahir    = $request->tempat_lahir;
                $murid->tgl_lahir       = $request->tgl_lahir;
                $murid->agama           = $request->agama;
                $murid->telp            = $request->telp;
                $murid->whatsapp        = $request->whatsapp;
                $murid->alamat          = $request->alamat;
                $murid->jenis_kelamin   = $request->jenis_kelamin;
                $murid->update();

                // Hanya buat data orang tua baru jika belum ada
                if (!DataOrangTua::where('user_id', $id)->exists()) {
                    $ortu = new DataOrangTua;
                    $ortu->user_id  = $id;
                    $ortu->save();
                }
            }
            DB::commit();
            Session::flash('success','Success, Data Berhasil dikirim !');
            
            // Redirect ke form data orang tua
            return redirect('ppdb/form-data-orangtua');
        } catch (ErrorException $e) {
            DB::rollback();
            throw new ErrorException($e->getMessage());
        }
    }

    // Data Orang Tua
    public function dataOrtuView()
    {
        $ortu = DataOrangTua::where('user_id', Auth::id())->first();

        // Jika data orang tua masih empty
        if (!$ortu) {
            Session::flash('error','Data kamu belum lengkap !');
            return redirect('ppdb/form-pendaftaran');
        }

        // jika data orang tua sudah terisi dan bukan dari edit
        if ($ortu->nama_ayah && !request()->get('edit')) {
            Session::flash('success','Data orang tua sudah lengkap !');
            return redirect('ppdb/form-berkas');
        }
        return view('ppdb::backend.pendaftaran.dataOrtu', compact('ortu'));
    }

    // Update Data Orang Tua
    // FIX: Menggunakan DataOrtuRequest
    public function updateOrtu(DataOrtuRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $ortu = DataOrangTua::where('user_id', $id)->first();
            // Data Ayah
            $ortu->nama_ayah        = $request->nama_ayah;
            $ortu->pekerjaan_ayah   = $request->pekerjaan_ayah;
            $ortu->pendidikan_ayah  = $request->pendidikan_ayah;
            $ortu->alamat_ayah      = $request->alamat_ayah;

            // Data Ibu
            $ortu->nama_ibu         = $request->nama_ibu;
            $ortu->pekerjaan_ibu    = $request->pekerjaan_ibu;
            $ortu->pendidikan_ibu   = $request->pendidikan_ibu;
            $ortu->alamat_ibu       = $request->alamat_ibu;
            $ortu->update();

            if ($ortu) {
                // Hanya buat berkas baru jika belum ada
                if (!BerkasMurid::where('user_id', $id)->exists()) {
                    $berkas = new BerkasMurid();
                    $berkas->user_id    = $id;
                    $berkas->save();
                }
            }

            DB::commit();
            Session::flash('success','Success, Data Berhasil dikirim !');
            return redirect('/ppdb/form-berkas');
        } catch (ErrorException $e) {
            DB::rollback();
            throw new ErrorException($e->getMessage());
        }
    }

    // Berkas View
    public function berkasView()
    {
        $berkas = BerkasMurid::where('user_id', Auth::id())->first();
        
        // Jika belum ada berkas, redirect ke form data orang tua
        if (!$berkas) {
            Session::flash('error','Data kamu belum lengkap !');
            return redirect('ppdb/form-data-orangtua');
        }

        // Jika data berkas sudah terisi dan bukan dari edit
        if ($berkas->kartu_keluarga && !request()->get('edit')) {
            Session::flash('success','Data kamu sudah lengkap !');
            return redirect('/home');
        }
        return view('ppdb::backend.pendaftaran.berkas', compact('berkas'));
    }

   // Berkas Store
    // FIX: Gunakan BerkasMuridRequest sebagai tipe-hint
    public function berkasStore(BerkasMuridRequest $request, $id) 
    {
        try {
            DB::beginTransaction();
            $berkas = BerkasMurid::where('user_id', $id)->first();
            
            // Handle file uploads only if new files are provided
            if ($request->hasFile('kartu_keluarga')) {
                $imageKk = $request->file('kartu_keluarga');
                $kartuKeluarga = time() . "_" . $imageKk->getClientOriginalName();
                // FIX: Path storage yang benar
                $imageKk->storeAs('images/berkas_murid', $kartuKeluarga, 'public');
                $berkas->kartu_keluarga = $kartuKeluarga;
            }
            
            if ($request->hasFile('akte_kelahiran')) {
                $imageakte = $request->file('akte_kelahiran');
                $akteKelahiran = time() . "_" . $imageakte->getClientOriginalName();
                $imageakte->storeAs('images/berkas_murid', $akteKelahiran, 'public');
                $berkas->akte_kelahiran = $akteKelahiran;
            }

            // --- PERBAIKAN: Melengkapi kode KTP dan Foto ---
            if ($request->hasFile('ktp')) {
                $imageKtp = $request->file('ktp');
                $ktp = time() . "_" . $imageKtp->getClientOriginalName();
                $imageKtp->storeAs('images/berkas_murid', $ktp, 'public');
                $berkas->ktp = $ktp;
            }

            if ($request->hasFile('foto')) {
                $imageFoto = $request->file('foto');
                $foto = time() . "_" . $imageFoto->getClientOriginalName();
                $imageFoto->storeAs('images/berkas_murid', $foto, 'public');
                $berkas->foto = $foto;
            }
            // --- Akhir Perbaikan ---

            $berkas->save();
            DB::commit();
            Session::flash('success', 'Success, Data Berhasil dikirim !');
            return redirect('/home');
        } catch (ErrorException $e) {
            DB::rollback();
            throw new ErrorException($e->getMessage());
        }     
    }
}