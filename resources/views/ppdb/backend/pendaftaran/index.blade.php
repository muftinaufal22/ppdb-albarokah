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
                        <h4>Data Murid</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{url('ppdb/form-pendaftaran', Auth::id())}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Nama Lengkap</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               name="name" value="{{old('name', $user->name)}}" placeholder="Nama Lengkap" />
                                        @error('name')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{old('email', $user->email)}}" placeholder="Email" />
                                        @error('email')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Tempat Lahir</label>
                                        <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                               name="tempat_lahir" value="{{old('tempat_lahir', $user->muridDetail->tempat_lahir ?? '')}}" placeholder="Tempat Lahir" />
                                        @error('tempat_lahir')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Tanggal Lahir</label>
                                        <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" 
                                               name="tgl_lahir" value="{{old('tgl_lahir', $user->muridDetail->tgl_lahir ?? '')}}" />
                                        @error('tgl_lahir')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            <option value="Laki-laki" {{old('jenis_kelamin', $user->muridDetail->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : ''}}>Laki-laki</option>
                                            <option value="Perempuan" {{old('jenis_kelamin', $user->muridDetail->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : ''}}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Agama</label>
                                        <select name="agama" class="form-control @error('agama') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            <option value="Islam" {{old('agama', $user->muridDetail->agama ?? '') == 'Islam' ? 'selected' : ''}}>Islam</option>
                                            <option value="Kristen" {{old('agama', $user->muridDetail->agama ?? '') == 'Kristen' ? 'selected' : ''}}>Kristen</option>
                                            <option value="Katolik" {{old('agama', $user->muridDetail->agama ?? '') == 'Katolik' ? 'selected' : ''}}>Katolik</option>
                                            <option value="Hindu" {{old('agama', $user->muridDetail->agama ?? '') == 'Hindu' ? 'selected' : ''}}>Hindu</option>
                                            <option value="Buddha" {{old('agama', $user->muridDetail->agama ?? '') == 'Buddha' ? 'selected' : ''}}>Buddha</option>
                                            <option value="Konghucu" {{old('agama', $user->muridDetail->agama ?? '') == 'Konghucu' ? 'selected' : ''}}>Konghucu</option>
                                        </select>
                                        @error('agama')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                              
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">No.whatsApp</label>
                                        <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" 
                                               name="whatsapp" value="{{old('whatsapp', $user->muridDetail->whatsapp ?? '')}}" placeholder="WhatsApp" />
                                        @error('whatsapp')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="basicInput">Alamat Lengkap</label>
                                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                                  cols="30" rows="3" placeholder="Alamat Lengkap">{{old('alamat', $user->muridDetail->alamat ?? '')}}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            {{-- PERUBAHAN DI SINI --}}
                            <div class="text-right mt-2">
                                {{-- Tombol Kembali jadi abu-abu (btn-secondary) --}}
                                <a href="/home" class="btn btn-secondary">Kembali</a>
                                
                                {{-- Tombol Simpan jadi hijau (btn-success) --}}
                                <button class="btn btn-success" type="submit">Simpan & Lanjutkan</button>
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