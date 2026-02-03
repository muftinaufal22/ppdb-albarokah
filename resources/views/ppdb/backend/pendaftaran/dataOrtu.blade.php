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
                        <h4>Data Orang Tua</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{url('ppdb/form-data-orangtua', Auth::id())}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <h5>Data Ayah</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Nama Ayah</label>
                                        <input type="text" class="form-control @error('nama_ayah') is-invalid @enderror" 
                                               name="nama_ayah" value="{{old('nama_ayah', $ortu->nama_ayah ?? '')}}" placeholder="Nama Ayah" />
                                        @error('nama_ayah')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Pendidikan Ayah</label>
                                        <select name="pendidikan_ayah" class="form-control @error('pendidikan_ayah') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            <option value="SD" {{old('pendidikan_ayah', $ortu->pendidikan_ayah ?? '') == 'SD' ? 'selected' : ''}}>SD</option>
                                            <option value="SMP" {{old('pendidikan_ayah', $ortu->pendidikan_ayah ?? '') == 'SMP' ? 'selected' : ''}}>SMP</option>
                                            <option value="SMA/SMK" {{old('pendidikan_ayah', $ortu->pendidikan_ayah ?? '') == 'SMA/SMK' ? 'selected' : ''}}>SMA/SMK</option>
                                            <option value="S1" {{old('pendidikan_ayah', $ortu->pendidikan_ayah ?? '') == 'S1' ? 'selected' : ''}}>S1</option>
                                            <option value="S2" {{old('pendidikan_ayah', $ortu->pendidikan_ayah ?? '') == 'S2' ? 'selected' : ''}}>S2</option>
                                            <option value="S3" {{old('pendidikan_ayah', $ortu->pendidikan_ayah ?? '') == 'S3' ? 'selected' : ''}}>S3</option>
                                        </select>
                                        @error('pendidikan_ayah')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Pekerjaan Ayah</label>
                                        <select name="pekerjaan_ayah" class="form-control @error('pekerjaan_ayah') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            <option value="Wiraswasta" {{old('pekerjaan_ayah', $ortu->pekerjaan_ayah ?? '') == 'Wiraswasta' ? 'selected' : ''}}>Wiraswasta</option>
                                            <option value="Wirausaha" {{old('pekerjaan_ayah', $ortu->pekerjaan_ayah ?? '') == 'Wirausaha' ? 'selected' : ''}}>Wirausaha</option>
                                            <option value="ASN" {{old('pekerjaan_ayah', $ortu->pekerjaan_ayah ?? '') == 'ASN' ? 'selected' : ''}}>ASN</option>
                                            <option value="Buruh" {{old('pekerjaan_ayah', $ortu->pekerjaan_ayah ?? '') == 'Buruh' ? 'selected' : ''}}>Buruh</option>
                                        </select>
                                        @error('pekerjaan_ayah')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="basicInput">Alamat Lengkap Ayah</label>
                                        <textarea name="alamat_ayah" class="form-control @error('alamat_ayah') is-invalid @enderror" 
                                                  cols="30" rows="3" placeholder="Alamat Lengkap Ayah">{{old('alamat_ayah', $ortu->alamat_ayah ?? '')}}</textarea>
                                        @error('alamat_ayah')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h5>Data Ibu</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Nama Ibu</label>
                                        <input type="text" class="form-control @error('nama_ibu') is-invalid @enderror" 
                                               name="nama_ibu" value="{{old('nama_ibu', $ortu->nama_ibu ?? '')}}" placeholder="Nama Ibu" />
                                        @error('nama_ibu')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Pendidikan Ibu</label>
                                        <select name="pendidikan_ibu" class="form-control @error('pendidikan_ibu') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            <option value="SD" {{old('pendidikan_ibu', $ortu->pendidikan_ibu ?? '') == 'SD' ? 'selected' : ''}}>SD</option>
                                            <option value="SMP" {{old('pendidikan_ibu', $ortu->pendidikan_ibu ?? '') == 'SMP' ? 'selected' : ''}}>SMP</option>
                                            <option value="SMA/SMK" {{old('pendidikan_ibu', $ortu->pendidikan_ibu ?? '') == 'SMA/SMK' ? 'selected' : ''}}>SMA/SMK</option>
                                            <option value="S1" {{old('pendidikan_ibu', $ortu->pendidikan_ibu ?? '') == 'S1' ? 'selected' : ''}}>S1</option>
                                            <option value="S2" {{old('pendidikan_ibu', $ortu->pendidikan_ibu ?? '') == 'S2' ? 'selected' : ''}}>S2</option>
                                            <option value="S3" {{old('pendidikan_ibu', $ortu->pendidikan_ibu ?? '') == 'S3' ? 'selected' : ''}}>S3</option>
                                        </select>
                                        @error('pendidikan_ibu')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Pekerjaan Ibu</label>
                                        <select name="pekerjaan_ibu" class="form-control @error('pekerjaan_ibu') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            <option value="Ibu Rumah Tangga" {{old('pekerjaan_ibu', $ortu->pekerjaan_ibu ?? '') == 'Ibu Rumah Tangga' ? 'selected' : ''}}>Ibu Rumah Tangga</option>
                                            <option value="Wiraswasta" {{old('pekerjaan_ibu', $ortu->pekerjaan_ibu ?? '') == 'Wiraswasta' ? 'selected' : ''}}>Wiraswasta</option>
                                            <option value="Wirausaha" {{old('pekerjaan_ibu', $ortu->pekerjaan_ibu ?? '') == 'Wirausaha' ? 'selected' : ''}}>Wirausaha</option>
                                            <option value="ASN" {{old('pekerjaan_ibu', $ortu->pekerjaan_ibu ?? '') == 'ASN' ? 'selected' : ''}}>ASN</option>
                                            <option value="Buruh" {{old('pekerjaan_ibu', $ortu->pekerjaan_ibu ?? '') == 'Buruh' ? 'selected' : ''}}>Buruh</option>
                                        </select>
                                        @error('pekerjaan_ibu')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="basicInput">Alamat Lengkap Ibu</label>
                                        <textarea name="alamat_ibu" class="form-control @error('alamat_ibu') is-invalid @enderror" 
                                                  cols="30" rows="3" placeholder="Alamat Lengkap Ibu">{{old('alamat_ibu', $ortu->alamat_ibu ?? '')}}</textarea>
                                        @error('alamat_ibu')
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
                                <a href="{{url('ppdb/form-pendaftaran?edit=1')}}" class="btn btn-secondary">Kembali</a>
                                
                                {{-- Tombol Simpan jadi hijau --}}
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