@extends('layouts.backend.app')

@section('title')
    Edit Pengajar
@endsection

@section('content')

@if ($message = Session::get('success'))
    <div class="alert alert-success" role="alert">
        <div class="alert-body">
            <strong>{{ $message }}</strong>
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    </div>
@elseif($message = Session::get('error'))
    <div class="alert alert-danger" role="alert">
        <div class="alert-body">
            <strong>{{ $message }}</strong>
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    </div>
@endif

{{-- Alert untuk menampilkan semua error validasi --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="alert-body">
            <strong>Terjadi kesalahan!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>
        </div>
    </div>
@endif

<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2>Pengajar</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header header-bottom">
                        <h4>Edit Pengajar</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('backend-pengguna-pengajar.update', $pengajar->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                {{-- Nama --}}
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Nama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', $pengajar->name) }}" placeholder="Nama">
                                        @error('name')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email', $pengajar->email) }}" placeholder="Email">
                                        @error('email')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Mengajar --}}
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Mengajar <span class="text-danger">*</span></label>
                                        <select name="mengajar" class="form-control @error('mengajar') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            @foreach(['Menghitung','Membaca','Menulis','Menggambar','Mengaji'] as $mapel)
                                                <option value="{{ $mapel }}" 
                                                    {{ old('mengajar', $pengajar->userDetail?->mengajar) == $mapel ? 'selected' : '' }}>
                                                    {{ $mapel }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('mengajar')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- NIP --}}
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>NIP (18 Digit) <span class="text-danger">*</span></label>
                                        <input type="text" id="nip" class="form-control @error('nip') is-invalid @enderror"
                                            name="nip" value="{{ old('nip', $pengajar->userDetail?->nip) }}" placeholder="Masukkan 18 digit NIP" maxlength="18">
                                        @error('nip')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                        <small class="form-text text-muted">NIP harus terdiri dari 18 digit angka</small>
                                    </div>
                                </div>

                                {{-- Foto --}}
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Foto Profile (Rasio 3:4)</label>
                                        <input type="file" id="foto_profile" class="form-control @error('foto_profile') is-invalid @enderror" 
                                               name="foto_profile" accept="image/jpeg,image/png,image/jpg">
                                        <small class="text-danger">Kosongkan jika tidak ingin mengubah.</small>
                                        <small class="form-text text-muted d-block">
                                            Upload foto dengan rasio 3:4 (min. 300x400 pixel). Format: JPEG, PNG, JPG. Maksimal 2MB.
                                        </small>
                                        @error('foto_profile')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                        
                                        {{-- Preview foto saat ini --}}
                                        <div class="current-photo mt-2">
                                            @if($pengajar->foto_profile)
                                                <small class="text-muted d-block">Foto saat ini:</small>
                                                <img src="{{ asset('storage/images/profile/' . $pengajar->foto_profile) }}" 
                                                     class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                            @else
                                                 <small class="text-muted d-block">Belum ada foto.</small>
                                            @endif
                                        </div>
                                        
                                        {{-- Container untuk preview foto BARU yang di-crop --}}
                                        <div id="preview-container" style="margin-top: 10px;"></div>
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                                            <option value="Aktif" {{ old('status', $pengajar->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="Tidak Aktif" {{ old('status', $pengajar->status) == 'Tidak AktIF' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Website --}}
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Website</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror"
                                            name="website" 
                                            value="{{ old('website', $pengajar->userDetail?->website) }}" 
                                            placeholder="https://website.com">
                                        @error('website')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- LinkedIn --}}
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>LinkedIn</label>
                                        <input type="url" class="form-control @error('linkidln') is-invalid @enderror"
                                            name="linkidln" 
                                            value="{{ old('linkidln', $pengajar->userDetail?->linkidln) }}" 
                                            placeholder="https://linkedin.com/in/username">
                                        @error('linkidln')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Instagram --}}
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Instagram</label>
                                        <input type="text" class="form-control @error('instagram') is-invalid @enderror"
                                            name="instagram" 
                                            value="{{ old('instagram', $pengajar->userDetail?->instagram) }}" 
                                            placeholder="@username">
                                        @error('instagram')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Facebook --}}
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Facebook</label>
                                        <input type="url" class="form-control @error('facebook') is-invalid @enderror"
                                            name="facebook" 
                                            value="{{ old('facebook', $pengajar->userDetail?->facebook) }}" 
                                            placeholder="https://facebook.com/username">
                                        @error('facebook')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- YouTube --}}
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>YouTube</label>
                                        <input type="url" class="form-control @error('youtube') is-invalid @enderror"
                                            name="youtube" 
                                            value="{{ old('youtube', $pengajar->userDetail?->youtube) }}" 
                                            placeholder="https://youtube.com/channel/...">
                                        @error('youtube')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Twitter --}}
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Twitter</label>
                                        <input type="url" class="form-control @error('twitter') is-invalid @enderror"
                                            name="twitter" 
                                            value="{{ old('twitter', $pengajar->userDetail?->twitter) }}" 
                                            placeholder="https://twitter.com/username">
                                        @error('twitter')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary" type="submit">Update</button>
                            <a href="{{ route('backend-pengguna-pengajar.index') }}" class="btn btn-warning">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 
=============================================
BAGIAN TAMBAHAN: Library & Modal untuk Cropper.js
============================================= 
--}}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />

<div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Sesuaikan Foto (Rasio 3:4)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container" style="max-height: 500px;">
                    <img id="imageToCrop" src="" style="width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="cropButton">Potong & Simpan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

{{-- JavaScript untuk validasi dan Cropper --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ===========================================
    // 1. Validasi NIP
    // ===========================================
    const nipInput = document.getElementById('nip');
    
    // Fungsi untuk menampilkan counter digit
    function showDigitCounter(input) {
        // Hapus counter lama
        const counter = input.parentNode.querySelector('.digit-counter');
        if (counter) {
            counter.remove();
        }
        
        // Tambah counter baru
        const digitCounter = document.createElement('small');
        digitCounter.className = 'digit-counter form-text text-info';
        digitCounter.textContent = `${input.value.length}/18 digit`;
        input.parentNode.appendChild(digitCounter);
    }
    
    // Tampilkan counter untuk nilai NIP yang sudah ada saat halaman dimuat
    if (nipInput && nipInput.value) {
        showDigitCounter(nipInput);
    }
    
    // Tambahkan listener untuk input NIP
    if (nipInput) {
        nipInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 18) {
                this.value = this.value.slice(0, 18);
            }
            showDigitCounter(this);
        });
    }

    // ===========================================
    // 2. Fungsionalitas Cropper Foto
    // ===========================================
    const fotoInput = document.getElementById('foto_profile');
    const previewContainer = document.getElementById('preview-container');
    const currentPhotoPreview = document.querySelector('.current-photo'); // Preview foto lama
    const imageToCrop = document.getElementById('imageToCrop');
    const cropModal = $('#cropModal'); // Kita pakai jQuery di sini krn Bootstrap Modal
    const cropButton = document.getElementById('cropButton');
    let cropper;
    let originalFileName;

    fotoInput.addEventListener('change', function(e) {
        const files = e.target.files;
        
        if (files && files.length > 0) {
            const file = files[0];
            originalFileName = file.name;

            // Validasi Ukuran (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                this.value = '';
                return;
            }

            // Validasi Tipe
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak valid! Gunakan JPEG, PNG, atau JPG.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    // Validasi Dimensi Minimum
                    if (this.width < 300 || this.height < 400) {
                        alert(`Dimensi foto terlalu kecil! Minimal 300x400 pixel. Dimensi Anda: ${this.width}x${this.height} pixel.`);
                        fotoInput.value = '';
                        return;
                    }

                    // Muat gambar ke modal dan tampilkan
                    imageToCrop.src = e.target.result;
                    cropModal.modal('show');
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        
        } else {
            // Jika user membatalkan pilihan file
            previewContainer.innerHTML = ''; // Hapus preview baru
            if (currentPhotoPreview) {
                currentPhotoPreview.style.display = 'block'; // Tampilkan lagi preview lama
            }
        }
    });

    // Saat Modal ditampilkan, inisialisasi Cropper.js
    cropModal.on('shown.bs.modal', function() {
        if (cropper) {
            cropper.destroy(); // Hancurkan instance lama jika ada
        }
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 3 / 4, // Paksa rasio 3:4
            viewMode: 1,
            background: false,
            responsive: true,
        });
    });

    // Saat Modal ditutup (karena klik 'Batal' atau 'x')
    cropModal.on('hidden.bs.modal', function(e) {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        // Jika modal ditutup tanpa menekan 'Potong & Simpan'
        if (!previewContainer.innerHTML) {
             fotoInput.value = ''; // Reset input file
             if (currentPhotoPreview) {
                currentPhotoPreview.style.display = 'block'; // Tampilkan lagi foto lama
            }
        }
    });

    // Saat tombol "Potong & Simpan" diklik
    cropButton.addEventListener('click', function() {
        if (cropper) {
            // Dapatkan kanvas yang dipotong (target 600x800)
            const canvas = cropper.getCroppedCanvas({
                width: 600,
                height: 800,
                imageSmoothingQuality: 'high',
            });

            // Ubah kanvas menjadi Blob (data file)
            canvas.toBlob(function(blob) {
                // Buat file baru dari Blob
                const fileExtension = originalFileName.split('.').pop();
                const newFileName = `cropped_${Date.now()}.${fileExtension}`;
                const newFile = new File([blob], newFileName, { type: blob.type });

                // Buat DataTransfer untuk menampung file baru
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(newFile);

                // Masukkan file baru ke input file
                fotoInput.files = dataTransfer.files;

                // Tampilkan preview dari gambar BARU
                const previewReader = new FileReader();
                previewReader.onload = function(e) {
                    previewContainer.innerHTML = `
                        <div class="preview-info mt-2">
                            <small class="text-muted d-block">Preview foto baru:</small>
                            <img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">
                            <div class="mt-2">
                                <small class="text-success d-block">✓ Foto baru siap diupload (600 x 800 pixel)</small>
                            </div>
                        </div>
                    `;
                    
                    // Sembunyikan preview foto LAMA
                    if (currentPhotoPreview) {
                        currentPhotoPreview.style.display = 'none';
                    }
                };
                previewReader.readAsDataURL(blob);

                // Tutup modal
                cropModal.modal('hide');

            }, 'image/jpeg', 0.95); // Simpan sebagai JPEG kualitas 95%
        }
    });
});
</script>

@endsection