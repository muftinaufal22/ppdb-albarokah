<?php

namespace App\Http\Controllers;

use App\Models\dataMurid;
use App\Models\Events;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    $role = Auth::user()->role;

    if (Auth::check()) {
      // DASHBOARD ADMIN \\
      if ($role == 'Admin') {

        $guru = User::where('role', 'Guru')->where('status', 'Aktif')->count();
        $murid = User::where('role', 'Murid')->where('status', 'Aktif')->count();
        $alumni = User::where('role', 'Alumni')->where('status', 'Aktif')->count();
        $acara = Events::where('is_active', '0')->count();
        $totalKegiatans = Kegiatan::count();

        // Ambil event berdasarkan jenis_event
        $event = Events::where('is_active', '0')
          ->where('jenis_event', '1')
          ->orderBy('created_at', 'desc')
          ->first();

        $event2 = Events::where('is_active', '0')
          ->where('jenis_event', '2')
          ->orderBy('created_at', 'desc')
          ->first();

        $event3 = Events::where('is_active', '0')
          ->where('jenis_event', '3')
          ->orderBy('created_at', 'desc')
          ->first();

        // PENTING: Pastikan semua variable ada di compact
        return view('backend.website.home', compact(
          'guru',
          'murid',
          'alumni',
          'event',
          'event2',
          'event3',
          'acara',
          'totalKegiatans'
        ));
      }
      // DASHBOARD MURID \\
      elseif ($role == 'Murid') {
        $auth = Auth::id();

        // PERBAIKAN: Ambil event berdasarkan jenis_event (sama seperti Admin & Guru)
        $event = Events::where('is_active', '0')
          ->where('jenis_event', '1')
          ->orderBy('created_at', 'desc')
          ->first();

        $event2 = Events::where('is_active', '0')
          ->where('jenis_event', '2')
          ->orderBy('created_at', 'desc')
          ->first();

        $event3 = Events::where('is_active', '0')
          ->where('jenis_event', '3')
          ->orderBy('created_at', 'desc')
          ->first();

        // PENTING: Pastikan semua variable event ada di compact
        return view('murid::index', compact('event', 'event2', 'event3'));
      } elseif ($role == 'Guru' || $role == 'Staf') {

        // Data statistik (sama seperti Admin)
        $guru = User::where('role', 'Guru')->where('status', 'Aktif')->count();
        $murid = User::where('role', 'Murid')->where('status', 'Aktif')->count();
        $alumni = User::where('role', 'Alumni')->where('status', 'Aktif')->count();
        $acara = Events::where('is_active', '0')->count();
        
        // ===> PERBAIKAN: Tambahkan variabel yang hilang <===
        $totalKegiatans = Kegiatan::count(); // Tambah ini
        $member = 0; // Atau ambil dari model yang sesuai jika ada

        // Ambil event berdasarkan jenis_event (sama seperti Admin)
        $event = Events::where('is_active', '0')
          ->where('jenis_event', '1')
          ->orderBy('created_at', 'desc')
          ->first();

        $event2 = Events::where('is_active', '0')
          ->where('jenis_event', '2')
          ->orderBy('created_at', 'desc')
          ->first();

        $event3 = Events::where('is_active', '0')
          ->where('jenis_event', '3')
          ->orderBy('created_at', 'desc')
          ->first();

        // ===> PERBAIKAN: Perbaiki nama variabel di compact <===
        return view('backend.website.home', compact(
          'guru',
          'murid',
          'alumni',
          'event',
          'event2',
          'event3',
          'acara',
          'totalKegiatans', // Perbaiki dari 'totalkegiatans' ke 'totalKegiatans'
          'member'
        ));
      }
      // DASHBOARD PPDB & PENDAFTAR \\
      elseif ($role == 'Guest' || $role == 'PPDB') {

        $register = dataMurid::whereNotIn('proses', ['Murid', 'Ditolak'])->whereYear('created_at', Carbon::now())->count();
        $needVerif = dataMurid::whereNotNull(['tempat_lahir', 'tgl_lahir', 'agama'])->whereNull('nisn')->count();
        return view('ppdb::backend.index', compact('register', 'needVerif'));
      }

      // DASHBOARD BENDAHARA \\
      elseif ($role == 'Bendahara') {
        return view('spp::index');
      }
    }
  }
}