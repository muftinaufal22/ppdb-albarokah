@extends('layouts.backend.app')
@section('title', 'Data Pembayaran Murid')
@section('content')

{{-- Tampilkan Pesan Sukses/Error --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <div class="alert-body">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <div class="alert-body">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif

<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <h2 class="content-header-title float-left mb-0">Data Pembayaran Murid</h2>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-datatable p-2">
                <table class="dt-responsive table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama</th>
                            <th>Tahun Ajaran</th>
                            <th>Bulan</th>
                            <th>Jumlah</th>
                            <th>Bukti Transfer</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $key => $payment)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $payment->user->name ?? 'User Terhapus' }}</td>
                            <td>{{ $payment->year }}</td>
                            <td>{{ $payment->bulan }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            
                            {{-- KOLOM BUKTI --}}
                            <td>
                                @if ($payment->bukti_pembayaran)
                                    <a href="{{ asset('storage/images/bukti_payment/' . $payment->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i data-feather="eye"></i> Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-muted font-italic">Belum Upload</span>
                                @endif
                            </td>

                            {{-- KOLOM STATUS --}}
                            <td class="text-center">
                                @if ($payment->is_active == 1)
                                    <span class="badge badge-light-success">LUNAS</span>
                                @elseif ($payment->status == 'Ditolak')
                                    <span class="badge badge-light-danger">DITOLAK</span>
                                @elseif ($payment->bukti_pembayaran != null)
                                    <span class="badge badge-light-warning">MENUNGGU KONFIRMASI</span>
                                @else
                                    <span class="badge badge-light-secondary">BELUM BAYAR</span>
                                @endif
                            </td>

                            {{-- KOLOM AKSI --}}
                            <td class="text-center">
                                {{-- Tampilkan tombol TERIMA/TOLAK hanya jika:
                                     1. Ada Bukti Pembayaran
                                     2. Status Belum Lunas (is_active == 0)
                                     3. Status Bukan Ditolak --}}
                                @if ($payment->bukti_pembayaran && $payment->is_active == 0 && $payment->status != 'Ditolak')
                                    <div class="d-flex justify-content-center">
                                        {{-- Tombol TERIMA --}}
                                        <form action="{{ route('spp.payment.accept', $payment->id) }}" method="POST" class="mr-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Yakin ingin menerima pembayaran ini?')">
                                                <i data-feather="check"></i> Terima
                                            </button>
                                        </form>

                                        {{-- Tombol TOLAK --}}
                                        <form action="{{ route('spp.payment.reject', $payment->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menolak pembayaran ini? Bukti akan dihapus dan murid harus upload ulang.')">
                                                <i data-feather="x"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                
                                {{-- Jika sudah lunas --}}
                                @elseif ($payment->is_active == 1)
                                    <i data-feather="check-circle" class="text-success font-medium-3"></i>
                                
                                {{-- Jika ditolak --}}
                                @elseif ($payment->status == 'Ditolak')
                                    <span class="text-danger font-small-3">Menunggu Upload Ulang</span>
                                
                                {{-- Jika belum bayar --}}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection