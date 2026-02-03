@extends('layouts.backend.app')

@section('title', 'Manajemen Struktur Organisasi')

@section('content')

{{-- Menampilkan notifikasi dengan penanganan khusus untuk pesan tertentu --}}
@if ($message = Session::get('success'))
    {{-- Jika pesan sukses adalah notifikasi bahwa foto sudah ada, tampilkan sebagai info --}}
    @if ($message == 'Foto struktur organisasi sudah ada.')
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <div class="alert-body d-flex align-items-center">
                <i data-feather="info" class="me-2"></i>
                <strong>{{ $message }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @else
    {{-- Jika pesan sukses lainnya, tampilkan seperti biasa --}}
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="alert-body d-flex align-items-center">
                <i data-feather="check-circle" class="me-2"></i>
                <strong>{{ $message }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
@elseif ($message = Session::get('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <div class="alert-body d-flex align-items-center">
        <i data-feather="info" class="me-2"></i>
        <strong>{{ $message }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@elseif ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <div class="alert-body d-flex align-items-center">
        <i data-feather="alert-circle" class="me-2"></i>
        <strong>{{ $message }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

<div class="content-wrapper container-xxl p-0">
    {{-- Breadcrumbs --}}
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Foto Struktur Organisasi</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i data-feather="home" class="me-1"></i>Dashboard</a></li>
                            <li class="breadcrumb-item active">Struktur Organisasi</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Isi Konten --}}
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Daftar Foto</h4>
                        {{-- Tombol "Tambah" hanya muncul jika tidak ada foto --}}
                        @if(!$strukturOrganisasi)
                            <a href="{{ route('backend-struktur-organisasi.create') }}" class="btn btn-primary">
                                <i data-feather="plus-circle" class="me-1"></i>
                                <span>Tambah Foto Baru</span>
                            </a>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        @if($strukturOrganisasi)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="struktur-organisasi-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 15%;">Preview Foto</th>
                                        <th>Tanggal Upload</th>
                                        <th style="width: 15%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <img src="{{ $strukturOrganisasi->foto_url }}" alt="Foto Struktur Organisasi" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        </td>
                                        <td>
                                            {{ $strukturOrganisasi->created_at->format('d F Y, H:i') }} WIB
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-start gap-1">
                                                <a href="{{ route('backend-struktur-organisasi.edit') }}" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Edit">
                                                    <i data-feather="edit-2"></i>
                                                </a>
                                                {{-- Tombol hapus dengan SweetAlert --}}
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus" onclick="showDeleteConfirmation()">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @else
                        {{-- Tampilan jika tidak ada data --}}
                        <div class="text-center py-5">
                            <i data-feather="inbox" class="font-large-1 text-muted mb-2"></i>
                            <h5 class="text-muted">Belum ada foto struktur organisasi.</h5>
                            <p class="text-muted">Silakan tambahkan satu foto untuk ditampilkan.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Form ini digunakan oleh SweetAlert untuk mengirim request hapus --}}
<form id="delete-form" action="{{ route('backend-struktur-organisasi.destroy') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

{{-- [PERUBAHAN] Mengganti @push menjadi @section --}}
@section('scripts')
{{-- Memuat pustaka SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // [PERUBAHAN] Membungkus script di dalam event listener untuk memastikan DOM sudah siap
    document.addEventListener('DOMContentLoaded', function () {
        // Fungsi untuk menampilkan konfirmasi hapus dengan SweetAlert
        window.showDeleteConfirmation = function () {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Anda akan menghapus foto ini secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, kirim form hapus
                    document.getElementById('delete-form').submit();
                }
            });
        }
        
        // Initialize Feather Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection

