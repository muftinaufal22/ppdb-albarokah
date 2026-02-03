@extends('layouts.backend.app')

@section('title')
    Form Pendaftaran
@endsection

@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2>Form Pendaftaran PPDB RA Al Barokah</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header header-bottom">
                        <h4>Berkas Pendaftaran</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{url('ppdb/form-berkas', $berkas->user_id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Kartu Keluarga</label>
                                        <input type="file" class="form-control @error('kartu_keluarga') is-invalid @enderror" name="kartu_keluarga" />
                                        @if($berkas->kartu_keluarga)
                                            <small class="text-success">File sudah diupload: {{ $berkas->kartu_keluarga }}</small>
                                        @endif
                                        @error('kartu_keluarga')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Akte Kelahiran</label>
                                        <input type="file" class="form-control @error('akte_kelahiran') is-invalid @enderror" name="akte_kelahiran" />
                                        @if($berkas->akte_kelahiran)
                                            <small class="text-success">File sudah diupload: {{ $berkas->akte_kelahiran }}</small>
                                        @endif
                                        @error('akte_kelahiran')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">KTP</label>
                                        <input type="file" class="form-control @error('ktp') is-invalid @enderror" name="ktp" />
                                        <small class="text-danger">Foto Orang Tua Ktp Ayah/Ibu</small>
                                        @if($berkas->ktp)
                                            <small class="text-success d-block">File sudah diupload: {{ $berkas->ktp }}</small>
                                        @endif
                                        @error('ktp')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Foto</label>
                                        <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto" />
                                        <small class="text-danger">Dapat menyusul.</small>
                                        @if($berkas->foto)
                                            <small class="text-success d-block">File sudah diupload: {{ $berkas->foto }}</small>
                                        @endif
                                        @error('foto')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            {{-- PERUBAHAN DI SINI --}}
                            <div class="text-right mt-2">
                                {{-- Tombol Kembali jadi abu-abu --}}
                                <a href="{{url('ppdb/form-data-orangtua?edit=1')}}" class="btn btn-secondary">Kembali</a>
                                
                                {{-- Tombol Simpan jadi hijau --}}
                                <button class="btn btn-success" type="submit">Simpan & Selesai</button>
                            </div>
                            {{-- AKHIR PERUBAHAN --}}

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection