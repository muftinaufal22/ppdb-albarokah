@extends('layouts.backend.app')

@section('title', 'Kelola Prestasi')

@section('content')
@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <div class="alert-body d-flex align-items-center">
        <i data-feather="check-circle" class="me-2"></i>
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
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Data Prestasi</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i data-feather="home" class="me-1"></i>Dashboard</a></li>
                            <li class="breadcrumb-item active">Prestasi</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <section>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0">Daftar Prestasi</h4>
                                    <a href="{{ route('backend-prestasi.create') }}" class="btn btn-primary">
                                        <i data-feather="plus-circle" class="me-1"></i>
                                        <span>Tambah Prestasi</span>
                                    </a>
                                </div>
                                
                                @if ($prestasi->count() > 0)
                                <div class="card-datatable p-2">
                                    <table class="dt-responsive table table-hover table-bordered" id="prestasi-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%"></th>
                                                <th width="5%">No</th>
                                                <th>Judul</th>
                                                <th>Gambar</th>
                                                <th>Kategori</th>
                                                <th>Tingkat</th>
                                                <th width="10%">Status</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($prestasi as $key => $item)
                                            <tr>
                                                <td></td>
                                                <td class="text-center">{{ $prestasi->firstItem() + $key }}</td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0">{{ $item->judul }}</h6>
                                                        <small class="text-muted">{{ Str::limit($item->deskripsi, 50) }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($item->gambar)
                                                        <img src="{{ asset('storage/' . $item->gambar) }}" 
                                                             alt="Prestasi" 
                                                             class="img-fluid rounded" 
                                                             style="max-width: 60px; max-height: 60px; object-fit: cover;">
                                                    @else
                                                        <div class="avatar bg-light-secondary rounded">
                                                            <div class="avatar-content">
                                                                <i data-feather="image" class="font-medium-4"></i>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $item->kategori }}</td>
                                                <td>{{ $item->tingkat }}</td>
                                                <td class="text-center">
                                                    @if($item->status == 'aktif')
                                                    <span class="badge bg-success"><i data-feather="check-circle" class="me-1"></i>Aktif</span>
                                                    @else
                                                    <span class="badge bg-danger"><i data-feather="x-circle" class="me-1"></i>Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <a href="{{ route('backend-prestasi.edit', $item->slug) }}"
                                                           class="btn btn-sm btn-success"
                                                           data-bs-toggle="tooltip"
                                                           title="Edit">
                                                            <i data-feather="edit-2"></i>
                                                        </a>
                                                        {{-- [PERUBAHAN] Mengganti onclick ke fungsi SweetAlert --}}
                                                        <button type="button"
                                                                class="btn btn-sm btn-danger"
                                                                data-bs-toggle="tooltip"
                                                                title="Hapus"
                                                                onclick="showDeleteConfirmation('{{ $item->slug }}', '{{ $item->judul }}')">
                                                            <i data-feather="trash-2"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="p-1">
                                      {{ $prestasi->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="card-body">
                                    <div class="text-center py-5">
                                        <i data-feather="inbox" class="font-large-1 text-muted mb-2"></i>
                                        <h5 class="text-muted">Belum ada data prestasi.</h5>
                                        <p class="text-muted">Tambahkan prestasi Anak didik anda.</p>
                                      
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

{{-- [PERUBAHAN] Form ini digunakan oleh SweetAlert untuk mengirim request hapus --}}
<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
{{-- [PERUBAHAN] Memuat pustaka SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // [PERUBAHAN] Fungsi baru untuk menampilkan konfirmasi hapus dengan SweetAlert
    function showDeleteConfirmation(slug, title) {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            html: `Anda akan menghapus prestasi: <br><strong>"${title}"</strong>`,
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
                const form = document.getElementById('delete-form');
                form.action = `/backend-prestasi/${slug}`;
                form.submit();
            }
        });
    }
    
    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endsection
