@extends('layouts.backend.app')
@section('title', 'Data Pembayaran Murid')
@section('content')
{{-- Notifikasi --}}

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
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tahun Ajaran</th>
                            <th>Bulan</th>
                            <th>Jumlah</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $key => $payment)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{$payment->user->name}}</td>
                            <td>{{$payment->bulan}}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                @if ($payment->bukti_pembayaran)
                                <a href="{{ asset('storage/images/bukti_payment/' . $payment->bukti_pembayaran) }}" target="_blank"><img src="{{ asset('storage/images/bukti_payment/' . $payment->bukti_pembayaran) }}" alt="Bukti Pembayaran" width="100"></a>
                                @else
                                <span class="text-muted">Belum Upload</span>
                                @endif
                            </td>

                            @php
                            $statusLunas = $payment->amount > 0 && $payment->bukti_pembayaran !== null && $payment->is_active;
                            $statusMenunggu = $payment->amount > 0 && $payment->bukti_pembayaran !== null && !$payment->is_active;
                            $statusBelumLunas = !$statusLunas && !$statusMenunggu;
                            @endphp

                            @if ($statusMenunggu)
                            <td class="text-center">
                                <span class="badge bg-info">MENUNGGU PROSES</span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('spp.payment.accept', $payment->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Terima</button>
                                </form>

                                <form action="{{ route('spp.payment.reject', $payment->id) }}" method="POST" style="display:inline-block; margin-left: 4px;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                </form>
                            </td>
                            @else
                            <td class="text-center" colspan="2">
                                @if ($statusLunas)
                                <span class="badge bg-success">LUNAS</span>
                                @else
                                <span class="badge bg-warning">BELUM LUNAS</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection