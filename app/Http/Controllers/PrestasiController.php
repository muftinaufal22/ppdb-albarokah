<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
// UPDATE: Menggunakan Facade untuk Intervention Image Versi 3
use Intervention\Image\Laravel\Facades\Image;

class PrestasiController extends Controller
{
    /**
     * Display a listing of the resource (Backend Admin).
     */
    public function index()
    {
        $prestasi = Prestasi::latest()->paginate(10);
        
        // Statistics for dashboard cards
        $totalPrestasi = Prestasi::count();
        $activeCount = Prestasi::where('status', 'aktif')->count();
        $inactiveCount = Prestasi::where('status', 'nonaktif')->count();
        $thisYear = Prestasi::whereYear('tanggal_prestasi', date('Y'))->count();
        
        return view('backend.website.prestasi.index', compact(
            'prestasi', 
            'totalPrestasi', 
            'activeCount', 
            'inactiveCount', 
            'thisYear'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.website.prestasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:255',
            'kategori' => 'required|in:Akademik,Non_akademik',
            'tingkat' => 'required|in:Sekolah,Kecamatan,Kabupaten,Provinsi,Nasional,Internasional',
            'peraih' => 'required|string|max:50',
            'penyelenggara' => 'nullable|string|max:100',
            'tanggal_prestasi' => 'required|date',
            // Limit 2MB, user boleh upload jpg/png
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'judul.required' => 'Judul prestasi wajib diisi.',
            'judul.max' => 'Judul tidak boleh lebih dari 100 karakter.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'tingkat.required' => 'Tingkat prestasi wajib dipilih.',
            'peraih.required' => 'Nama peraih prestasi wajib diisi.',
            'peraih.max' => 'Nama peraih tidak boleh lebih dari 50 karakter.',
            'penyelenggara.max' => 'Penyelenggara tidak boleh lebih dari 100 karakter.',
            'tanggal_prestasi.required' => 'Tanggal prestasi wajib diisi.',
            'gambar.image' => 'File yang diupload harus berupa gambar.',
            'gambar.mimes' => 'Gambar harus berformat: jpeg, png, atau jpg.',
            'gambar.max' => 'Ukuran gambar maksimal adalah 2MB.',
            'status.required' => 'Status wajib dipilih.',
        ]);
        
        // Generate slug
        $validated['slug'] = $this->generateUniqueSlug($validated['judul']);
        
        // --- LOGIKA UPLOAD BARU (VERSI 3) ---
        if ($request->hasFile('gambar')) {
            $imageFile = $request->file('gambar');
            
            // 1. Nama file output .webp
            $imageName = time() . '_' . Str::slug($validated['judul']) . '.webp';
            // Path relatif terhadap storage/app/public
            $path = 'prestasi/' . $imageName;

            // 2. Baca Gambar (Gunakan 'read' bukan 'make' di V3)
            $image = Image::read($imageFile);

            // 3. Resize (Opsional: max lebar 800px)
            $image->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // 4. Encode ke WebP (Kualitas 80)
            $encoded = $image->toWebp(80);

            // 5. Simpan ke Storage Public
            Storage::disk('public')->put($path, $encoded);

            $validated['gambar'] = $path;
        }

        $validated['tanggal_prestasi'] = Carbon::parse($validated['tanggal_prestasi']);
        Prestasi::create($validated);

        return redirect()->route('backend-prestasi.index')
                         ->with('success', 'Prestasi berhasil ditambahkan (Auto Convert WebP).');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $prestasi = Prestasi::where('slug', $slug)->firstOrFail();
        return view('backend.website.prestasi.show', compact('prestasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $prestasi = Prestasi::where('slug', $slug)->firstOrFail();
        return view('backend.website.prestasi.edit', compact('prestasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $prestasi = Prestasi::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'judul' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:255',
            'kategori' => 'required|in:Akademik,Non_akademik',
            'tingkat' => 'required|in:Sekolah,Kecamatan,Kabupaten,Provinsi,Nasional,Internasional',
            'peraih' => 'required|string|max:50',
            'penyelenggara' => 'nullable|string|max:100',
            'tanggal_prestasi' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'status' => 'required|in:aktif,nonaktif', 
        ], [
            // Pesan error sama seperti store...
             'judul.required' => 'Judul prestasi wajib diisi.',
             // ... dst
        ]);

        // Update slug if title changed
        if ($validated['judul'] !== $prestasi->judul) {
            $validated['slug'] = $this->generateUniqueSlug($validated['judul'], $prestasi->id);
        }

        // Set status based on action
        if ($request->input('action') == 'draft') {
            $validated['status'] = 'nonaktif';
        } elseif ($request->input('action') == 'publish') {
            $validated['status'] = 'aktif';
        }

        // --- LOGIKA UPLOAD BARU (VERSI 3) ---
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($prestasi->gambar && Storage::disk('public')->exists($prestasi->gambar)) {
                Storage::disk('public')->delete($prestasi->gambar);
            }

            $imageFile = $request->file('gambar');
            
            // 1. Nama file output .webp
            $imageName = time() . '_' . Str::slug($validated['judul']) . '.webp';
            $path = 'prestasi/' . $imageName;

            // 2. Baca Gambar
            $image = Image::read($imageFile);

            // 3. Resize
            $image->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // 4. Encode & Simpan
            $encoded = $image->toWebp(80);
            Storage::disk('public')->put($path, $encoded);

            $validated['gambar'] = $path;
        }

        $validated['tanggal_prestasi'] = Carbon::parse($validated['tanggal_prestasi']);
        $prestasi->update($validated);

        $message = $request->input('action') == 'draft' 
            ? 'Prestasi berhasil disimpan sebagai draft' 
            : 'Prestasi berhasil diperbarui dan dipublikasikan';

        return redirect()->route('backend-prestasi.index')
                         ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        $prestasi = Prestasi::where('slug', $slug)->firstOrFail();
        
        if ($prestasi->gambar && Storage::disk('public')->exists($prestasi->gambar)) {
            Storage::disk('public')->delete($prestasi->gambar);
        }
        
        $prestasi->delete();
        
        return redirect()->route('backend-prestasi.index')
                         ->with('success', 'Prestasi berhasil dihapus');
    }
    
    public function toggleStatus(Request $request, $slug)
    {
        $prestasi = Prestasi::where('slug', $slug)->firstOrFail();
        
        $prestasi->update(['status' => $request->input('status')]);
        
        return response()->json([
            'success' => true,
            'message' => 'Status prestasi berhasil diperbarui'
        ]);
    }
    
    // ==========================================================================================
    // == PUBLIC METHODS (Frontend) ==
    // ==========================================================================================
    public function publicIndex(Request $request)
    {
        $query = Prestasi::where('status', 'aktif');

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        $prestasi = $query->latest('tanggal_prestasi')->paginate(12);

        $daftarTingkat = ['Sekolah', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional'];
        
        return view('frontend.content.prestasi', compact('prestasi', 'daftarTingkat'));
    }

    public function publicShow($slug)
    {
        $prestasi = Prestasi::where('slug', $slug)
                            ->where('status', 'aktif')
                            ->firstOrFail();
        
        $prestasi->increment('views');
        
        $relatedPrestasi = Prestasi::where('status', 'aktif')
                                   ->where('kategori', $prestasi->kategori)
                                   ->where('id', '!=', $prestasi->id)
                                   ->limit(3)
                                   ->get();
        
        return view('frontend.content.prestasi-detail', compact('prestasi', 'relatedPrestasi'));
    }

    // ==========================================================================================
    // == HELPER METHODS ==
    // ==========================================================================================
    
    public function statistics()
    {
        $stats = [
            'total' => Prestasi::count(),
            'aktif' => Prestasi::where('status', 'aktif')->count(),
            'nonaktif' => Prestasi::where('status', 'nonaktif')->count(),
            'tahun_ini' => Prestasi::whereYear('tanggal_prestasi', date('Y'))->count(),
            'by_kategori' => Prestasi::selectRaw('kategori, COUNT(*) as count')
                                     ->groupBy('kategori')
                                     ->pluck('count', 'kategori'),
            'by_tingkat' => Prestasi::selectRaw('tingkat, COUNT(*) as count')
                                    ->groupBy('tingkat')
                                    ->pluck('count', 'tingkat'),
        ];
        
        return response()->json($stats);
    }

    private function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while (true) {
            $query = Prestasi::where('slug', $slug);
            
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    public static function getForLandingPage($limit = 6)
    {
        return Prestasi::where('status', 'aktif')
                       ->latest('tanggal_prestasi')
                       ->limit($limit)
                       ->get();
    }

    public static function getStatsForLandingPage()
    {
        return [
            'total' => Prestasi::where('status', 'aktif')->count(),
            'nasional' => Prestasi::where('status', 'aktif')
                                  ->where('tingkat', 'Nasional')
                                  ->count(),
            'provinsi' => Prestasi::where('status', 'aktif')
                                  ->where('tingkat', 'Provinsi')
                                  ->count(),
            'tahun_ini' => Prestasi::where('status', 'aktif')
                                   ->whereYear('tanggal_prestasi', date('Y'))
                                   ->count()
        ];
    }
}