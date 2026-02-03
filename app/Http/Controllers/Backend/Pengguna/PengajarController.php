<?php

namespace App\Http\Controllers\Backend\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UsersDetail;
use Illuminate\Http\Request;
use App\Http\Requests\PengajarRequest;
use ErrorException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PengajarController extends Controller
{
    /**
     * Menampilkan daftar pengajar
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pengajar = User::with('userDetail')->where('role','Guru')->get();
        return view('backend.pengguna.pengajar.index', compact('pengajar'));
    }

    /**
     * Menampilkan form tambah pengajar baru
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.pengguna.pengajar.create');
    }

    /**
     * Menyimpan pengajar baru ke database
     *
     * @param  \App\Http\Requests\PengajarRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi manual dengan pesan bahasa Indonesia
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|digits:18|unique:users_details,nip',
            'mengajar' => 'required|string|in:Menghitung,Membaca,Menulis,Menggambar,Mengaji',
            'foto_profile' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
            'linkidln' => 'nullable|url',
            'instagram' => 'nullable|string|max:255',
            'website' => 'nullable|url',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'youtube' => 'nullable|url',
        ], [
            'name.required' => 'Nama pengajar wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 50 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP harus terdiri dari tepat 18 digit angka.',
            'nip.unique' => 'NIP sudah terdaftar di sistem.',
            'mengajar.required' => 'Mata pelajaran yang diajar wajib dipilih.',
            'mengajar.in' => 'Mata pelajaran tidak valid.',
            'foto_profile.required' => 'Foto profil wajib diupload.',
            'foto_profile.image' => 'File harus berupa gambar.',
            'foto_profile.mimes' => 'Foto harus berformat JPEG, PNG, atau JPG.',
            'foto_profile.max' => 'Ukuran foto maksimal 2MB.',
            'linkidln.url' => 'Format URL LinkedIn tidak valid.',
            'instagram.string' => 'Instagram harus berupa teks.',
            'instagram.max' => 'Instagram maksimal 255 karakter.',
            'website.url' => 'Format URL website tidak valid.',
            'facebook.url' => 'Format URL Facebook tidak valid.',
            'twitter.url' => 'Format URL Twitter tidak valid.',
            'youtube.url' => 'Format URL YouTube tidak valid.',
        ]);

        // Validasi tambahan untuk rasio foto 3:4 dan dimensi minimum
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
                    
                    // Cek Rasio
                    if (abs($ratio - $expectedRatio) > $tolerance) {
                        $validator->errors()->add(
                            'foto_profile',
                            "Rasio foto harus 3:4. Rasio saat ini: " . number_format($ratio, 2) . 
                            ". File yang diupload (mungkin dari cropper) tidak sesuai."
                        );
                    }
                    
                    // Cek Dimensi Minimum (PENTING)
                    // Kita cek 600x800 karena cropper kita diset ke 600x800
                    // Beri toleransi kecil jika ada pembulatan
                    if ($width < 590 || $height < 790) {
                         $validator->errors()->add(
                            'foto_profile',
                            "Dimensi foto terlalu kecil. Minimal 600x800 pixel. Dimensi saat ini: {$width}x{$height} pixel."
                        );
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->route('backend-pengguna-pengajar.create')
                            ->withErrors($validator)
                            ->withInput();
        }

        try {
            DB::beginTransaction();

            // Proses upload foto
            $nama_img = null;
            if ($request->hasFile('foto_profile')) {
                $image = $request->file('foto_profile');
                // Gunakan nama file unik
                $nama_img = time() . "_" . $image->getClientOriginalName();
                $tujuan_upload = 'public/images/profile';
                $image->storeAs($tujuan_upload, $nama_img);
            }

            // Buat username dari nama (kata pertama + detik saat ini)
            $kalimatKe = "1";
            $username = implode(" ", array_slice(explode(" ", $request->name), 0, $kalimatKe));

            // Simpan data user
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = strtolower($username) . date("s");
            $user->role = 'Guru';
            $user->status = 'Aktif';
            $user->foto_profile = $nama_img;
            $user->password = bcrypt('12345678'); // Password default
            $user->save();

            // Simpan detail user
            if ($user) {
                $userDetail = new UsersDetail();
                $userDetail->user_id = $user->id;
                $userDetail->role = $user->role;
                $userDetail->mengajar = $request->mengajar;
                $userDetail->nip = $request->nip;
                $userDetail->email = $request->email;
                $userDetail->linkidln = $request->linkidln;
                $userDetail->instagram = $request->instagram;
                $userDetail->website = $request->website;
                $userDetail->facebook = $request->facebook;
                $userDetail->twitter = $request->twitter;
                $userDetail->youtube = $request->youtube;
                $userDetail->save();
            }

            // Assign role jika menggunakan spatie/laravel-permission
            if (method_exists($user, 'assignRole')) {
                $user->assignRole($user->role);
            }

            DB::commit();
            Session::flash('success', 'Data pengajar berhasil ditambahkan!');
            return redirect()->route('backend-pengguna-pengajar.index');

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Gagal menambah pengajar: ' . $e->getMessage());
            return redirect()->route('backend-pengguna-pengajar.create')->withInput();
        }
    }
    /**
     * Menampilkan detail pengajar
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pengajar = User::with('userDetail')->where('role','Guru')->findOrFail($id);
        return view('backend.pengguna.pengajar.show', compact('pengajar'));
    }

    /**
     * Menampilkan form edit pengajar
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pengajar = User::with('userDetail')->where('role','Guru')->findOrFail($id);
        return view('backend.pengguna.pengajar.edit', compact('pengajar'));
    }

    /**
     * Memperbarui data pengajar
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi untuk update dengan pesan bahasa Indonesia
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . $id,
            'nip' => 'required|digits:18|unique:users_details,nip,' . $id . ',user_id',
            'mengajar' => 'required|string|in:Menghitung,Membaca,Menulis,Menggambar,Mengaji',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'linkidln' => 'nullable|url',
            'instagram' => 'nullable|string|max:255',
            'website' => 'nullable|url',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'youtube' => 'nullable|url',
        ], [
            'name.required' => 'Nama pengajar wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 50 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP harus terdiri dari tepat 18 digit angka.',
            'nip.unique' => 'NIP sudah terdaftar di sistem.',
            'mengajar.required' => 'Mata pelajaran yang diajar wajib dipilih.',
            'mengajar.in' => 'Mata pelajaran tidak valid.',
            'foto_profile.image' => 'File harus berupa gambar.',
            'foto_profile.mimes' => 'Foto harus berformat JPEG, PNG, atau JPG.',
            'foto_profile.max' => 'Ukuran foto maksimal 2MB.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status hanya boleh Aktif atau Tidak Aktif.',
            'linkidln.url' => 'Format URL LinkedIn tidak valid.',
            'instagram.string' => 'Instagram harus berupa teks.',
            'instagram.max' => 'Instagram maksimal 255 karakter.',
            'website.url' => 'Format URL website tidak valid.',
            'facebook.url' => 'Format URL Facebook tidak valid.',
            'twitter.url' => 'Format URL Twitter tidak valid.',
            'youtube.url' => 'Format URL YouTube tidak valid.',
        ]);

        // Validasi tambahan untuk rasio foto 3:4 jika ada file yang diupload
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
                        $validator->errors()->add(
                            'foto_profile',
                            "Rasio foto harus 3:4. Rasio saat ini: " . number_format($ratio, 2) . 
                            ". Contoh dimensi yang benar: 300x400, 450x600, atau 600x800 pixel."
                        );
                    }
                    
                    // Validasi minimum dimensi
                    if ($width < 300 || $height < 400) {
                        $validator->errors()->add(
                            'foto_profile',
                            "Dimensi foto terlalu kecil. Minimal 300x400 pixel. Dimensi saat ini: {$width}x{$height} pixel."
                        );
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->route('backend-pengguna-pengajar.edit', $id)
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $nama_img = $user->foto_profile; // Simpan foto lama sebagai default

            // Proses upload foto baru jika ada
            if ($request->hasFile('foto_profile')) {
                // Hapus foto lama jika ada
                if ($user->foto_profile && Storage::exists('public/images/profile/' . $user->foto_profile)) {
                    Storage::delete('public/images/profile/' . $user->foto_profile);
                }

                $image = $request->file('foto_profile');
                $nama_img = time() . "_" . $image->getClientOriginalName();
                $tujuan_upload = 'public/images/profile';
                $image->storeAs($tujuan_upload, $nama_img);
            }

            // Update data user
            $user->name = $request->name;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->foto_profile = $nama_img;
            $user->save();

            // Update atau buat user detail
            $userDetail = UsersDetail::where('user_id', $id)->first();
            if ($userDetail) {
                $userDetail->mengajar = $request->mengajar;
                $userDetail->nip = $request->nip;
                $userDetail->email = $request->email;
                $userDetail->linkidln = $request->linkidln;
                $userDetail->instagram = $request->instagram;
                $userDetail->website = $request->website;
                $userDetail->facebook = $request->facebook;
                $userDetail->twitter = $request->twitter;
                $userDetail->youtube = $request->youtube;
                $userDetail->is_active = $user->status == 'Aktif' ? '0' : '1';
                $userDetail->save();
            } else {
                // Buat user detail baru jika belum ada
                $userDetail = new UsersDetail();
                $userDetail->user_id = $user->id;
                $userDetail->role = $user->role;
                $userDetail->mengajar = $request->mengajar;
                $userDetail->nip = $request->nip;
                $userDetail->email = $request->email;
                $userDetail->linkidln = $request->linkidln;
                $userDetail->instagram = $request->instagram;
                $userDetail->website = $request->website;
                $userDetail->facebook = $request->facebook;
                $userDetail->twitter = $request->twitter;
                $userDetail->youtube = $request->youtube;
                $userDetail->is_active = $user->status == 'Aktif' ? '0' : '1';
                $userDetail->save();
            }

            DB::commit();
            Session::flash('success', 'Data pengajar berhasil diperbarui!');
            return redirect()->route('backend-pengguna-pengajar.index');

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Gagal memperbarui pengajar: ' . $e->getMessage());
            return redirect()->route('backend-pengguna-pengajar.edit', $id)->withInput();
        }
    }

    /**
     * Menghapus pengajar dari database
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            // Hapus detail user terlebih dahulu
            UsersDetail::where('user_id', $user->id)->delete();

            // Hapus file foto profil jika ada
            if ($user->foto_profile && Storage::exists('public/images/profile/' . $user->foto_profile)) {
                Storage::delete('public/images/profile/' . $user->foto_profile);
            }

            // Hapus role assignments jika menggunakan spatie/laravel-permission
            if (method_exists($user, 'removeRole')) {
                $user->removeRole($user->role);
            }

            // Hapus user utama
            $user->delete();

            DB::commit();
            Session::flash('success', 'Data pengajar berhasil dihapus!');
            return redirect()->route('backend-pengguna-pengajar.index');
            
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Gagal menghapus pengajar: ' . $e->getMessage());
            return redirect()->route('backend-pengguna-pengajar.index');
        }
    }

    /**
     * Method tambahan untuk validasi foto via AJAX (opsional)
     */
    public function validateImage(Request $request)
    {
        if ($request->hasFile('foto_profile')) {
            $image = $request->file('foto_profile');
            $imageInfo = getimagesize($image->getPathname());
            
            if ($imageInfo) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];
                $ratio = $width / $height;
                $expectedRatio = 3/4;
                $tolerance = 0.02;
                
                $response = [
                    'width' => $width,
                    'height' => $height,
                    'ratio' => number_format($ratio, 2),
                    'valid' => abs($ratio - $expectedRatio) <= $tolerance && $width >= 300 && $height >= 400,
                    'message' => ''
                ];
                
                if (!$response['valid']) {
                    if ($width < 300 || $height < 400) {
                        $response['message'] = "Dimensi terlalu kecil. Minimal 300x400 pixel.";
                    } else {
                        $response['message'] = "Rasio tidak sesuai 3:4. Rasio saat ini: " . $response['ratio'];
                    }
                } else {
                    $response['message'] = "Foto valid dengan rasio 3:4";
                }
                
                return response()->json($response);
            }
        }
        
        return response()->json(['valid' => false, 'message' => 'File tidak valid']);
    }
}