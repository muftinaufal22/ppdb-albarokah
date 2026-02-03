<?php

namespace App\Http\Controllers\Backend\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\dataMurid;
use App\Models\User;
use App\Models\Kelas;
use App\Models\BerkasMurid;
use App\Models\DataOrangTua;
use App\Models\SppSetting; 
use App\Models\PaymentSpp; 
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MuridController extends Controller
{
    public function index()
    {
        // Eager load necessary relationships for index view
        $murid = User::with("kelas", "muridDetail")->whereIn("role", ["Murid"])->get();
        return view("backend.pengguna.murid.index", compact("murid"));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view("backend.pengguna.murid.create", compact("kelas"));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make(
                $request->all(),
                [
                    "name" => "required|string|max:255",
                    "email" => "required|email|unique:users,email",
                    "password" => "nullable|string|min:6",
                    "jenis_kelamin" => "required|in:L,P",
                    "whatsapp" => "nullable|string|max:15|unique:data_murids,whatsapp",
                    "kelas" => "required|exists:kelas,id",
                    "nis" => ["nullable", "string", "max:50", "unique:data_murids,nis"],
                    "nisn" => ["nullable", "string", "max:50", "unique:data_murids,nisn"],
                    "tempat_lahir" => "nullable|string|max:100",
                    "tgl_lahir" => "nullable|date",
                    "agama" => "nullable|string|max:50",
                    "alamat" => "nullable|string",
                    "foto_profile" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",

                    // Data Ortu validation
                    "nama_ayah" => "nullable|string|max:255",
                    "pendidikan_ayah" => "nullable|string|max:100",
                    "pekerjaan_ayah" => "nullable|string|max:100",
                    "alamat_ayah" => "nullable|string",
                    "nama_ibu" => "nullable|string|max:255",
                    "pendidikan_ibu" => "nullable|string|max:100",
                    "pekerjaan_ibu" => "nullable|string|max:100",
                    "alamat_ibu" => "nullable|string",

                    // Berkas validation
                    "kartu_keluarga" => "nullable|file|mimes:pdf,jpg,jpeg,png|max:5120",
                    "akte_kelahiran" => "nullable|file|mimes:pdf,jpg,jpeg,png|max:5120",
                    "ktp" => "nullable|file|mimes:pdf,jpg,jpeg,png|max:5120",
                    "foto" => "nullable|file|mimes:pdf,jpg,jpeg,png|max:5120",
                ],
                [
                    "name.required" => "Nama tidak boleh kosong.",
                    "email.required" => "Email tidak boleh kosong.",
                    "email.unique" => "Email sudah pernah digunakan.",
                    "email.email" => "Email yang dimasukan tidak valid.",
                    "password.min" => "Password minimal 6 karakter.",
                    "jenis_kelamin.required" => "Jenis kelamin harus dipilih.",
                    "jenis_kelamin.in" => "Jenis kelamin harus L atau P.",
                    "whatsapp.max" => "Nomor WhatsApp maksimal 15 karakter.",
                    "whatsapp.unique" => "Nomor WhatsApp sudah digunakan oleh murid lain.",
                    "kelas.required" => "Kelas harus dipilih.",
                    "kelas.exists" => "Kelas yang dipilih tidak valid.",
                    "nis.unique" => "NIS sudah digunakan.",
                    "nisn.unique" => "NISN sudah digunakan.",
                    "foto_profile.image" => "File foto profil harus berupa gambar.",
                    "foto_profile.mimes" => "Format foto profil harus jpeg, png, jpg, atau gif.",
                    "foto_profile.max" => "Ukuran foto profil maksimal 2MB.",
                    "kartu_keluarga.max" => "Ukuran file Kartu Keluarga maksimal 5MB.",
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $nama_img = null;
            if ($request->hasFile("foto_profile")) {
                $image = $request->file("foto_profile");
                $nama_img = time() . "_" . uniqid() . "." . $image->getClientOriginalExtension();
                $tujuan_upload = "public/images/profile";
                $path = $image->storeAs($tujuan_upload, $nama_img);
                if (!$path) {
                    throw new \Exception("Gagal menyimpan foto profil.");
                }
            }

            $username = $this->generateUniqueUsername($request->name);

            $murid = new User();
            $murid->name = $request->name;
            $murid->username = $username;
            $murid->email = $request->email;
            $murid->role = "Murid";
            $murid->status = "Aktif";
            $murid->foto_profile = $nama_img;
            $murid->password = bcrypt($request->password ?? "password");
            $murid->kelas_id = $request->kelas;
            $murid->save();

            // Create Murid Detail
            dataMurid::create([
                "user_id" => $murid->id,
                "jenis_kelamin" => $request->jenis_kelamin,
                "whatsapp" => $request->whatsapp,
                "nis" => $request->nis,
                "nisn" => $request->nisn,
                "tempat_lahir" => $request->tempat_lahir,
                "tgl_lahir" => $request->tgl_lahir,
                "agama" => $request->agama,
                "alamat" => $request->alamat,
            ]);

            // Create Data Ortu
            if ($request->filled(["nama_ayah", "nama_ibu"])) {
                DataOrangTua::create([
                    "user_id" => $murid->id,
                    "nama_ayah" => $request->nama_ayah,
                    "pendidikan_ayah" => $request->pendidikan_ayah,
                    "pekerjaan_ayah" => $request->pekerjaan_ayah,
                    "alamat_ayah" => $request->alamat_ayah,
                    "nama_ibu" => $request->nama_ibu,
                    "pendidikan_ibu" => $request->pendidikan_ibu,
                    "pekerjaan_ibu" => $request->pekerjaan_ibu,
                    "alamat_ibu" => $request->alamat_ibu,
                ]);
            }

            // Create Berkas Murid
            $hasBerkasFile = false;
            $berkasFields = ["kartu_keluarga", "akte_kelahiran", "ktp", "foto"];
            foreach ($berkasFields as $field) {
                if ($request->hasFile($field)) {
                    $hasBerkasFile = true;
                    break;
                }
            }

            if ($hasBerkasFile) {
                $berkasMurid = BerkasMurid::create(["user_id" => $murid->id]);
                $uploadPathBerkas = "public/images/berkas_murid";

                foreach ($berkasFields as $field) {
                    if ($request->hasFile($field)) {
                        $file = $request->file($field);
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeName = preg_replace("/[^A-Za-z0-9_\-\.]/", "_", $originalName);
                        $extension = $file->getClientOriginalExtension();
                        $nama_file_berkas = time() . "_" . $safeName . "." . $extension;

                        $pathBerkas = $file->storeAs($uploadPathBerkas, $nama_file_berkas);
                        if (!$pathBerkas) {
                            throw new \Exception("Gagal menyimpan berkas: " . $field);
                        }
                        $berkasMurid->$field = $nama_file_berkas;
                    }
                }
                $berkasMurid->save();
            }

            // --- FITUR BARU: GENERATE TAGIHAN SPP OTOMATIS ---
            // Cek apakah kelas yang dipilih sudah memiliki settingan SPP
            $sppSettings = SppSetting::where('kelas_id', $request->kelas)->get();

            foreach ($sppSettings as $setting) {
                // Pastikan tidak duplikat (meskipun user baru, good practice)
                $existingBill = PaymentSpp::where('user_id', $murid->id)
                    ->where('year', $setting->tahun_ajaran)
                    ->where('bulan', $setting->bulan)
                    ->exists();

                if (!$existingBill) {
                    PaymentSpp::create([
                        'user_id' => $murid->id,
                        'year' => $setting->tahun_ajaran,
                        'bulan' => $setting->bulan,
                        'amount' => $setting->amount, // Mengambil nominal dari settingan yang benar
                        'is_active' => false, // Status: Belum Lunas
                    ]);
                }
            }
            // ------------------------------------------------

            $murid->assignRole($murid->role);

            DB::commit();
            Session::flash("success", "Murid Berhasil disimpan!");
            return redirect()->route("backend-pengguna-murid.index");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error storing Murid: " . $e->getMessage() . " di baris " . $e->getLine());
            Session::flash("error", "Gagal menyimpan Murid: " . $e->getMessage() . " (Baris: " . $e->getLine() . ")");
            return redirect()->back()->withInput();
        }
    }

    /**
     * Helper function to generate a unique username.
     */
    protected function generateUniqueUsername($name)
    {
        $baseUsername = strtolower(str_replace(' ', '', preg_replace('/[^A-Za-z0-9 ]/', '', $name)));
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        return $username;
    }

    public function show($id)
    {
        $murid = User::with(["kelas", "muridDetail", "dataOrtu", "berkas"])->findOrFail($id);
        return view("backend.pengguna.murid.detail", compact("murid"));
    }

    public function edit($id)
    {
        $murid = User::with(["muridDetail", "kelas", "dataOrtu", "berkas"])
            ->whereIn("role", ["Guest", "Murid"])
            ->findOrFail($id);
        $kelas = Kelas::all();
        return view("backend.pengguna.murid.edit", compact("murid", "kelas"));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "name" => "required|max:255",
                "email" => [
                    "required",
                    "email",
                    Rule::unique("users")->ignore($id),
                ],
                "status" => [
                    "required",
                    Rule::in(["Aktif", "Tidak Aktif"]),
                ],
                "kelas" => "required|exists:kelas,id",
                "nis" => [
                    "nullable",
                    "string",
                    "max:50",
                    Rule::unique("data_murids", "nis")->ignore($id, "user_id"),
                ],
                "nisn" => [
                    "nullable",
                    "string",
                    "max:50",
                    Rule::unique("data_murids", "nisn")->ignore($id, "user_id"),
                ],
                "tahun_ajaran" => [
                    "nullable",
                    "string",
                    "max:50",
                ],
                "whatsapp" => [
                    "nullable",
                    "string",
                    "max:15",
                    Rule::unique("data_murids", "whatsapp")->ignore($id, "user_id"),
                ],
                "tempat_lahir" => "nullable|string|max:100",
                "tgl_lahir" => "nullable|date",
                "jenis_kelamin" => "required|in:L,P",
                "agama" => "nullable|string|max:50",
                "alamat" => "nullable|string",

                "foto_profile" => "nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048",
                "kartu_keluarga" => "nullable|file|mimes:pdf,jpg,jpeg,png|max:5120",
                "akte_kelahiran" => "nullable|file|mimes:pdf,jpg,jpeg,png|max:5120",
                "ktp" => "nullable|file|mimes:pdf,jpg,jpeg,png|max:5120",
                "foto" => "nullable|file|mimes:pdf,jpg,jpeg,png|max:5120",
                "nama_ayah" => "nullable|string|max:255",
                "pendidikan_ayah" => "nullable|string|max:100",
                "pekerjaan_ayah" => "nullable|string|max:100",
                "alamat_ayah" => "nullable|string",
                "nama_ibu" => "nullable|string|max:255",
                "pendidikan_ibu" => "nullable|string|max:100",
                "pekerjaan_ibu" => "nullable|string|max:100",
                "alamat_ibu" => "nullable|string",
            ],
            [
                "name.required" => "Nama tidak boleh kosong.",
                "email.required" => "Email tidak boleh kosong.",
                "email.email" => "Email yang dimasukan tidak valid.",
                "email.unique" => "Email sudah digunakan oleh pengguna lain.",
                "status.required" => "Status Murid harus dipilih.",
                "kelas.required" => "Kelas harus dipilih.",
                "kelas.exists" => "Kelas yang dipilih tidak valid.",
                "nis.unique" => "NIS sudah digunakan oleh murid lain.",
                "nisn.unique" => "NISN sudah digunakan oleh murid lain.",
                "whatsapp.unique" => "Nomor WhatsApp sudah digunakan oleh murid lain.",
                "jenis_kelamin.required" => "Jenis kelamin harus dipilih.",
                "foto_profile.max" => "Ukuran file maksimal 2MB.",
                "kartu_keluarga.max" => "Ukuran file Kartu Keluarga maksimal 5MB.",
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $murid = User::findOrFail($id);

            // --- Handle Foto Profile Upload --- 
            $nama_img = $murid->foto_profile;
            if ($request->hasFile("foto_profile")) {
                if ($murid->foto_profile && Storage::exists("public/images/profile/" . $murid->foto_profile)) {
                    Storage::delete("public/images/profile/" . $murid->foto_profile);
                }
                $file = $request->file("foto_profile");
                $nama_img = time() . "_" . $file->getClientOriginalName();
                $tujuan_upload = "public/images/profile";

                $path = $file->storeAs($tujuan_upload, $nama_img);
                if (!$path) {
                    throw new \Exception("Gagal menyimpan foto profil baru.");
                }
            }

            // --- Update User Data --- 
            $murid->name = $request->name;
            $murid->email = $request->email;
            $murid->status = $request->status;
            $murid->kelas_id = $request->kelas;
            $murid->foto_profile = $nama_img;
            if ($request->filled("password")) {
                $murid->password = bcrypt($request->password);
            }
            $murid->save();

            // --- Update dataMurid --- 
            $detailMurid = dataMurid::firstOrCreate(["user_id" => $murid->id]);
            $detailMurid->nis = $request->nis;
            $detailMurid->nisn = $request->nisn;
            $detailMurid->tahun_ajaran = $request->tahun_ajaran;
            $detailMurid->whatsapp = $request->whatsapp;
            $detailMurid->tempat_lahir = $request->tempat_lahir;
            $detailMurid->tgl_lahir = $request->tgl_lahir;
            $detailMurid->jenis_kelamin = $request->jenis_kelamin;
            $detailMurid->agama = $request->agama;
            $detailMurid->alamat = $request->alamat;
            $detailMurid->save();

            // --- Handle Berkas Upload --- 
            $berkasMurid = \App\Models\BerkasMurid::firstOrCreate(["user_id" => $murid->id]);
            $berkasFields = ["kartu_keluarga", "akte_kelahiran", "ktp", "foto"];
            $uploadPathBerkas = "public/images/berkas_murid";

            foreach ($berkasFields as $field) {
                if ($request->hasFile($field)) {
                    if ($berkasMurid->$field && Storage::exists($uploadPathBerkas . "/" . $berkasMurid->$field)) {
                        Storage::delete($uploadPathBerkas . "/" . $berkasMurid->$field);
                    }
                    $file = $request->file($field);
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeName = preg_replace("/[^A-Za-z0-9_\-\.]/", "_", $originalName);
                    $extension = $file->getClientOriginalExtension();
                    $nama_file_berkas = time() . "_" . $safeName . "." . $extension;

                    $pathBerkas = $file->storeAs($uploadPathBerkas, $nama_file_berkas);
                    if (!$pathBerkas) {
                        throw new \Exception("Gagal menyimpan berkas: " . $field);
                    }
                    $berkasMurid->$field = $nama_file_berkas;
                }
            }
            $berkasMurid->save();

            // --- Update Data Ortu --- 
            $dataOrtu = \App\Models\DataOrangTua::firstOrCreate(["user_id" => $murid->id]);
            $dataOrtu->nama_ayah = $request->nama_ayah;
            $dataOrtu->pendidikan_ayah = $request->pendidikan_ayah;
            $dataOrtu->pekerjaan_ayah = $request->pekerjaan_ayah;
            $dataOrtu->alamat_ayah = $request->alamat_ayah;
            $dataOrtu->nama_ibu = $request->nama_ibu;
            $dataOrtu->pendidikan_ibu = $request->pendidikan_ibu;
            $dataOrtu->pekerjaan_ibu = $request->pekerjaan_ibu;
            $dataOrtu->alamat_ibu = $request->alamat_ibu;
            $dataOrtu->save();

            DB::commit();
            Session::flash("success", "Data Murid Berhasil diupdate !");
            return redirect()->route("backend-pengguna-murid.index");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error updating Murid: " . $e->getMessage() . " di baris " . $e->getLine());
            Session::flash("error", "Gagal mengupdate Data Murid: " . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $murid = User::findOrFail($id);

            // Delete related data first
            dataMurid::where("user_id", $id)->delete();
            DataOrangTua::where("user_id", $id)->delete(); // Fixed: changed DataOrtuRequest to DataOrangTua

            // Delete Berkas files and record
            $berkas = BerkasMurid::where("user_id", $id)->first();
            if ($berkas) {
                $berkasFields = ["kartu_keluarga", "akte_kelahiran", "ktp", "foto"];
                $uploadPathBerkas = "public/images/berkas_murid";
                foreach ($berkasFields as $field) {
                    if ($berkas->$field && Storage::exists($uploadPathBerkas . "/" . $berkas->$field)) {
                        Storage::delete($uploadPathBerkas . "/" . $berkas->$field);
                    }
                }
                $berkas->delete();
            }

            // Hapus Tagihan SPP terkait murid ini (Opsional, aktifkan jika perlu)
            // PaymentSpp::where('user_id', $id)->delete();

            // Delete Foto Profile file
            if ($murid->foto_profile && Storage::exists("public/images/profile/" . $murid->foto_profile)) {
                Storage::delete("public/images/profile/" . $murid->foto_profile);
            }

            // Detach roles
            DB::table("model_has_roles")
                ->where("model_id", $id)
                ->delete();

            $murid->delete();

            DB::commit();
            Session::flash("success", "Data Murid berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error deleting Murid: " . $e->getMessage());
            Session::flash("error", "Gagal menghapus Data Murid.");
        }
        return redirect()->route("backend-pengguna-murid.index");
    }
}