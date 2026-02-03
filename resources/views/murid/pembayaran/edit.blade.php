@extends('layouts.backend.app')
@section('title', 'Konfirmasi Ulang Pembayaran')
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-body">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-success">
                        <h4 class="card-title mb-0 text-white">Konfirmasi Ulang Pembayaran</h4>
                    </div>
                    <div class="card-body">
                        
                        {{-- Info Tagihan --}}
                        <div class="alert alert-success mt-2" role="alert">
                            <h5 class="alert-heading fw-bold">Detail Tagihan:</h5>
                            <hr>
                            <table class="table table-borderless table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <td style="width: 150px;">Bulan</td>
                                        <td>: <strong>{{ $payment->bulan }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Tahun Ajaran</td>
                                        <td>: <strong>{{ $payment->year }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Nominal</td>
                                        <td>: <strong class="text-dark">Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Pesan Error Validasi --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <hr>
                        
                        {{-- FORM UPDATE --}}
                        <form action="{{ route('murid.pembayaran.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT') {{-- PENTING: Agar dikenali sebagai request UPDATE --}}

                            <div class="form-group mb-2">
                                <label for="nama_pengirim" class="form-label">Nama Pengirim</label>
                                <input type="text" name="nama_pengirim" class="form-control" value="{{ old('nama_pengirim', $user->name) }}" required>
                            </div>
                        
                            <div class="form-group mb-2">
                                <label for="bukti_pembayaran" class="form-label">Unggah Ulang Bukti Transfer</label>
                                <input type="file" class="form-control" name="bukti_pembayaran" required accept="image/*,application/pdf">
                                <small class="form-text text-muted">Format: JPG, PNG, PDF (Max: 2MB)</small>
                            </div>

                            <div class="form-group mt-3">
                                <button class="btn btn-success" type="submit">Kirim Ulang Konfirmasi</button>
                                <a href="{{ route('murid.pembayaran.index') }}" class="btn btn-outline-secondary">Kembali</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection