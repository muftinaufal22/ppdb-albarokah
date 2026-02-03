<?php

namespace App\Http\Controllers;

use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// UPDATE: Panggil library Image
use Intervention\Image\Laravel\Facades\Image;

class StrukturOrganisasiController extends Controller
{
    public function index()
    {
        // Gunakan first() karena kita hanya mengharapkan satu atau nol hasil
        $strukturOrganisasi = StrukturOrganisasi::first(); 
        return view('backend.website.struktur-organisasi.index', compact('strukturOrganisasi'));
    }

    public function create()
    {
        // Cek apakah sudah ada foto
        if (StrukturOrganisasi::count() > 0) {
            return redirect()->route('backend-struktur-organisasi.index')
                ->with('info', 'Hanya boleh ada satu foto. Silakan edit atau hapus yang sudah ada.');
        }
        return view('backend.website.struktur-organisasi.create');
    }

    public function store(Request $request)
    {
        if (StrukturOrganisasi::count() > 0) {
            return redirect()->route('backend-struktur-organisasi.index')
                ->with('success', 'Foto struktur organisasi berhasil ditambahkan.');
        }

        // --- validasi ukuran dan pesan kustom ---
        // SAYA UBAH: Max jadi 2048 (2MB) agar user leluasa upload gambar HD, nanti kita compress
        $request->validate(
            [
                'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048' 
            ],
            [
                'foto.required' => 'Kolom foto wajib diisi.',
                'foto.image'    => 'File yang diunggah harus berupa gambar.',
                'foto.mimes'    => 'Format gambar harus jpeg, png, atau jpg.',
                'foto.max'      => 'Ukuran foto tidak boleh melebihi 2 MB.',
            ]
        );

        $data = [];
        
        // --- LOGIKA UPLOAD WEBP ---
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            
            // 1. Buat nama file bersih .webp
            $filename = time() . '_struktur_organisasi.webp';
            $path = 'struktur-organisasi/' . $filename;

            // 2. Baca Gambar
            $image = Image::read($file);

            // 3. Resize (Lebar 1200px agar tulisan di bagan tetap terbaca)
            $image->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // 4. Encode ke WebP (Kualitas 80)
            $encoded = $image->toWebp(80);

            // 5. Simpan ke Storage Public
            Storage::disk('public')->put($path, $encoded);

            $data['foto'] = $path;
        }

        StrukturOrganisasi::create($data);
        return redirect()->route('backend-struktur-organisasi.index')
            ->with('success', 'Foto struktur organisasi berhasil ditambahkan (Auto WebP).');
    }

    public function edit() 
    {
        $backendStrukturOrganisasi = StrukturOrganisasi::firstOrFail(); 
        return view('backend.website.struktur-organisasi.edit', compact('backendStrukturOrganisasi'));
    }

    public function update(Request $request) 
    {
        $backendStrukturOrganisasi = StrukturOrganisasi::firstOrFail();
        
        // --- Menambahkan validasi ukuran dan pesan kustom ---
        // SAYA UBAH: Max jadi 2048 (2MB)
        $request->validate(
            [
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ],
            [
                'foto.required' => 'Kolom foto wajib diisi.',
                'foto.image'    => 'File yang diunggah harus berupa gambar.',
                'foto.mimes'    => 'Format gambar harus jpeg, png, atau jpg.',
                'foto.max'      => 'Ukuran foto tidak boleh melebihi 2 MB.',
            ]
        );
        
        // --- LOGIKA UPLOAD WEBP ---
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($backendStrukturOrganisasi->foto && Storage::disk('public')->exists($backendStrukturOrganisasi->foto)) {
                Storage::disk('public')->delete($backendStrukturOrganisasi->foto);
            }

            $file = $request->file('foto');
            
            // 1. Buat nama file bersih .webp
            $filename = time() . '_struktur_organisasi.webp';
            $path = 'struktur-organisasi/' . $filename;

            // 2. Baca Gambar
            $image = Image::read($file);

            // 3. Resize (Lebar 1200px)
            $image->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // 4. Encode ke WebP
            $encoded = $image->toWebp(80);

            // 5. Simpan
            Storage::disk('public')->put($path, $encoded);
            
            // Update data array
            $backendStrukturOrganisasi->update(['foto' => $path]);
        }
        
        return redirect()->route('backend-struktur-organisasi.index')
            ->with('success', 'Foto struktur organisasi berhasil diperbarui (Auto WebP).');
    }

    public function destroy()
    {
        $backendStrukturOrganisasi = StrukturOrganisasi::firstOrFail();
        if ($backendStrukturOrganisasi->foto && Storage::disk('public')->exists($backendStrukturOrganisasi->foto)) {
            Storage::disk('public')->delete($backendStrukturOrganisasi->foto);
        }
        $backendStrukturOrganisasi->delete();
        return redirect()->route('backend-struktur-organisasi.index')
            ->with('success', 'Foto struktur organisasi berhasil dihapus.');
    }

    public function publicView()
    {
        // Ambil foto pertama untuk ditampilkan di halaman depan
        $strukturOrganisasi = StrukturOrganisasi::first(); 
        return view('frontend.content.event.struktur-organisasi', compact('strukturOrganisasi'));
    }
}