<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\MasterSpp;
use Carbon\Carbon;

class MasterSppController extends Controller
{
    /**
     * Menampilkan daftar setting SPP
     */
    public function index()
    {
        $masterSpp = MasterSpp::with('kelas')->latest()->get();
        return view('spp::master.index', compact('masterSpp'));
    }

    /**
     * Menampilkan form untuk membuat setting SPP baru
     */
    public function create()
    {
        $kelas = Kelas::all();
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        
        // Generate tahun ajaran default
        $tahunAjaran = ($currentMonth >= 7) ? $currentYear . '/' . ($currentYear + 1) : ($currentYear - 1) . '/' . $currentYear;
        
        return view('spp::master.create', compact('kelas', 'tahunAjaran'));
    }

    /**
     * Menyimpan setting SPP baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'bulan_mulai' => 'required|integer|between:1,12',
            'bulan_selesai' => 'required|integer|between:1,12',
        ]);

        // Cek apakah sudah ada setting untuk kelas dan tahun ajaran yang sama
        $exists = MasterSpp::where('kelas_id', $request->kelas_id)
                           ->where('tahun_ajaran', $request->tahun_ajaran)
                           ->exists();

        if ($exists) {
            return redirect()->back()
                           ->withErrors(['kelas_id' => 'Setting SPP untuk kelas dan tahun ajaran ini sudah ada.'])
                           ->withInput();
        }

        MasterSpp::create([
            'kelas_id' => $request->kelas_id,
            'tahun_ajaran' => $request->tahun_ajaran,
            'nominal' => $request->nominal,
            'bulan_mulai' => $request->bulan_mulai,
            'bulan_selesai' => $request->bulan_selesai,
            'status' => 'Aktif',
        ]);

        return redirect()->route('spp.master-spp.index')
                        ->with('success', 'Setting SPP berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit setting SPP
     */
    public function edit($id)
    {
        $masterSpp = MasterSpp::findOrFail($id);
        $kelas = Kelas::all();
        
        return view('spp::master.edit', compact('masterSpp', 'kelas'));
    }

    /**
     * Update setting SPP
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'bulan_mulai' => 'required|integer|between:1,12',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $masterSpp = MasterSpp::findOrFail($id);

        // Cek duplikasi kecuali data yang sedang diedit
        $exists = MasterSpp::where('kelas_id', $request->kelas_id)
                           ->where('tahun_ajaran', $request->tahun_ajaran)
                           ->where('id', '!=', $id)
                           ->exists();

        if ($exists) {
            return redirect()->back()
                           ->withErrors(['kelas_id' => 'Setting SPP untuk kelas dan tahun ajaran ini sudah ada.'])
                           ->withInput();
        }

        $masterSpp->update([
            'kelas_id' => $request->kelas_id,
            'tahun_ajaran' => $request->tahun_ajaran,
            'nominal' => $request->nominal,
            'bulan_mulai' => $request->bulan_mulai,
            'bulan_selesai' => $request->bulan_selesai,
            'status' => $request->status,
        ]);

        return redirect()->route('spp.master-spp.index')
                        ->with('success', 'Setting SPP berhasil diperbarui.');
    }

    /**
     * Hapus setting SPP
     */
    public function destroy($id)
    {
        $masterSpp = MasterSpp::findOrFail($id);
        
        // Cek apakah ada pembayaran yang sudah terkait
        $hasPayments = $masterSpp->detailPayments()->exists();
        
        if ($hasPayments) {
            return redirect()->back()
                           ->with('error', 'Tidak dapat menghapus setting SPP karena sudah ada pembayaran terkait.');
        }

        $masterSpp->delete();

        return redirect()->route('spp.master-spp.index')
                        ->with('success', 'Setting SPP berhasil dihapus.');
    }
}