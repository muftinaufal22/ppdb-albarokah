<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileSettingsRequest; // Kita akan ganti ini
use App\Http\Requests\ChangePasswordRequest;
use ErrorException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator; // <-- TAMBAHKAN
use Illuminate\Support\Facades\Storage;   // <-- TAMBAHKAN

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profile = User::whereId(Auth::id())->first();
        return view('backend.profile.index', compact('profile'));
    }

    // ... (fungsi create, store, show, edit tidak dipakai) ...

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // Ganti 'ProfileSettingsRequest' menjadi 'Request'
    public function update(Request $request, $id)
    {
        // Pastikan user hanya bisa update profilnya sendiri
        if ($id != Auth::id()) {
            Session::flash('error','Anda tidak diizinkan melakukan aksi ini.');
            return back();
        }

        // --- Validasi Manual ---
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . $id,
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Nullable
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'foto_profile.image' => 'File harus berupa gambar.',
            'foto_profile.mimes' => 'Format foto harus JPEG, PNG, atau JPG.',
            'foto_profile.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        // Validasi tambahan untuk rasio foto 3:4 JIKA file baru diupload
        $validator->after(function ($validator) use ($request) {
            if ($request->hasFile('foto_profile')) {
                $image = $request->file('foto_profile');
                $imageInfo = getimagesize($image->getPathname());
                
                if ($imageInfo) {
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];
                    $ratio = $width / $height;
                    $expectedRatio = 3/4; // 0.75
                    $tolerance = 0.02; // Toleransi 2%
                    
                    if (abs($ratio - $expectedRatio) > $tolerance) {
                        $validator->errors()->add('foto_profile', "Rasio foto harus 3:4. File dari cropper tidak valid.");
                    }

                    // Cek dimensi minimum (output cropper kita 600x800)
                    if ($width < 590 || $height < 790) {
                        $validator->errors()->add('foto_profile', "Dimensi foto terlalu kecil (min 600x800). File dari cropper tidak valid.");
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->route('profile-settings.index') // Kembali ke halaman index
                            ->withErrors($validator)
                            ->withInput();
        }
        // --- Akhir Validasi ---


        try {
            $profile = User::find($id);
            $nama_image = $profile->foto_profile; // Default: pakai nama foto lama

            if ($request->hasFile('foto_profile')) {
                
                // PERBAIKAN BUG: Hapus foto lama jika ada
                if ($profile->foto_profile && Storage::exists('public/images/profile/' . $profile->foto_profile)) {
                    Storage::delete('public/images/profile/' . $profile->foto_profile);
                }

                // Simpan foto baru
                $image = $request->file('foto_profile');
                $nama_image = time()."_".$image->getClientOriginalName();
                $tujuan_upload = 'public/images/profile';
                $image->storeAs($tujuan_upload, $nama_image);
            }

            $profile->name  = $request->name;
            
            // Cek jika email diganti, reset verifikasi
            if ($profile->email != $request->email) {
                $profile->email = $request->email;
                $profile->email_verified_at = NULL;
            }

            $profile->foto_profile  = $nama_image; // Masukkan nama file (lama atau baru)
            $profile->save();

            Session::flash('success','Profile Berhasil diupdate !');
            return back();

        } catch (ErrorException $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    // Ubah Password (Tidak ada perubahan di sini, sudah benar)
    public function changePassword(ChangePasswordRequest $request, $id)
    {
       try {
            // Pastikan user hanya bisa update passwordnya sendiri
            if ($id != Auth::id()) {
                Session::flash('error','Anda tidak diizinkan melakukan aksi ini.');
                return back();
            }

            $profile = User::find($id);
            $profile->password  = bcrypt($request->password);
            $profile->save();

            Session::flash('success','Password Berhasil diupdate !');
            return back();

       } catch (ErrorException $e) {
           throw new ErrorException($e->getMessage());
       }
    }
}