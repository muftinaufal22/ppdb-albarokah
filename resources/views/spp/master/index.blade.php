@extends('layouts.backend.app')

@section('title')
    Master SPP
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="alert-body d-flex align-items-center">
                <i data-feather="check-circle" class="me-2"></i>
                <strong>{{ $message }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @elseif($message = Session::get('error'))
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
                        <h2 class="content-header-title float-left mb-0">Master SPP</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i data-feather="home" class="me-1"></i>Dashboard</a></li>
                                <li class="breadcrumb-item active">Master SPP</li>
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
                        <div class="card">
                            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Master SPP</h4>
                                <a href="{{ route('spp.master-spp.create') }}" class="btn btn-primary">
                                    <i data-feather="plus-circle" class="me-1"></i>
                                    <span>Tambah SPP Baru</span>
                                </a>
                            </div>
                            <div class="card-datatable p-2">
                                <table class="dt-responsive table table-hover table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th></th>
                                            <th>No</th>
                                            <th>Kelas</th>
                                            <th>Nominal SPP</th>
                                            <th>Tahun Ajaran</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($spp as $index => $item)
                                            <tr>
                                                <td></td>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ $item->kelas->nama ?? 'Kelas Tidak Ditemukan' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong class="text-success">
                                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        {{ $item->tahun_ajaran }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-inline-flex">
                                                        <a href="{{ route('spp.master-spp.edit', $item->id) }}" class="btn btn-sm btn-icon btn-success me-1" data-bs-toggle="tooltip" title="Edit">
                                                            <i data-feather="edit"></i>
                                                        </a>
                                                        <form action="{{ route('spp.master-spp.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data SPP untuk kelas {{ $item->kelas->nama ?? 'ini' }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-icon btn-danger" data-bs-toggle="tooltip" title="Hapus">
                                                                <i data-feather="trash-2"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i data-feather="inbox" class="mb-3" style="width: 3em; height: 3em;"></i>
                                                        <h5 class="text-muted">Belum Ada Data SPP</h5>
                                                        <p class="text-muted">Silakan tambahkan data SPP terlebih dahulu</p>
                                                        <a href="{{ route('spp.master-spp.create') }}" class="btn btn-primary">
                                                            <i data-feather="plus-circle" class="me-1"></i>
                                                            <span>Tambah SPP Baru</span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection