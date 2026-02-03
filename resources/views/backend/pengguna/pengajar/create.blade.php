@extends('layouts.backend.app')

@section('title')
    Tambah Pengajar
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
                    <h2> Pengajar</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header header-bottom">
                        <h4>Tambah Pengajar</h4>
                    </div>
                    <div class="card-body">
                        <form action=" {{route('backend-pengguna-pengajar.store')}} " method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Nama</label> <span class="text-danger">*</span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')}}" placeholder="Nama" />
                                        @error('name')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Email</label> <span class="text-danger">*</span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" placeholder="Email" />
                                        @error('email')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Mengajar</label> <span class="text-danger">*</span>
                                        <select name="mengajar" class="form-control @error('mengajar') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            <option value="Menghitung" {{ old('mengajar') == 'Menghitung' ? 'selected' : '' }}>Menghitung</option>
                                            <option value="Membaca" {{ old('mengajar') == 'Membaca' ? 'selected' : '' }}>Membaca</option>
                                            <option value="Menulis" {{ old('mengajar') == 'Menulis' ? 'selected' : '' }}>Menulis</option>
                                            <option value="Menggambar" {{ old('mengajar') == 'Menggambar' ? 'selected' : '' }}>Menggambar</option>
                                            <option value="Mengaji" {{ old('mengajar') == 'Mengaji' ? 'selected' : '' }}>Mengaji</option>
                                        </select>
                                        @error('mengajar')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">NIP (18 Digit)</label> <span class="text-danger">*</span>
                                        <input type="text" id="nip" class="form-control @error('nip') is-invalid @enderror" name="nip" value="{{old('nip')}}" placeholder="Masukkan 18 digit NIP" maxlength="18" />
                                        @error('nip')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted">NIP harus terdiri dari 18 digit angka</small>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Foto Profile (Rasio 3:4)</label>  <span class="text-danger">*</span>
                                        <input type="file" id="foto_profile" class="form-control @error('foto_profile') is-invalid @enderror" name="foto_profile" accept="image/jpeg,image/png,image/jpg"/>
                                        @error('foto_profile')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Upload foto dengan rasio 3:4 (min. 300x400 pixel). Format: JPEG, PNG, JPG. Maksimal 2MB.
                                        </small>
                                        <div id="preview-container" style="margin-top: 10px;"></div>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="basicInput">Website</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror" name="website" value="{{old('website')}}" placeholder="https://website.com" />
                                        @error('website')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="basicInput">LinkedIn</label>
                                        <input type="url" class="form-control @error('linkidln') is-invalid @enderror" name="linkidln" value="{{old('linkidln')}}" placeholder="https://linkedin.com/in/username" />
                                        @error('linkidln')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="basicInput">Instagram</label>
                                        <input type="text" class="form-control @error('instagram') is-invalid @enderror" name="instagram" value="{{old('instagram')}}" placeholder="@username" />
                                        @error('instagram')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="basicInput">Facebook</label>
                                        <input type="url" class="form-control @error('facebook') is-invalid @enderror" name="facebook" value="{{old('facebook')}}" placeholder="https://facebook.com/username" />
                                        @error('facebook')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="basicInput">Youtube</label>
                                        <input type="url" class="form-control @error('youtube') is-invalid @enderror" name="youtube" value="{{old('youtube')}}" placeholder="https://youtube.com/channel/..." />
                                        @error('youtube')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="basicInput">Twitter</label>
                                        <input type="url" class="form-control @error('twitter') is-invalid @enderror" name="twitter" value="{{old('twitter')}}" placeholder="https://twitter.com/username" />
                                        @error('twitter')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                              
                            </div>
                            <button class="btn btn-primary" type="submit">Tambah</button>
                            <a href="{{route('backend-pengguna-pengajar.index')}}" class="btn btn-warning">Batal</a>
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
    if (nipInput) {
        nipInput.addEventListener('input', function(e) {
            // Hanya izinkan angka
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Batasi maksimal 18 digit
            if (this.value.length > 18) {
                this.value = this.value.slice(0, 18);
            }
            
            // Tampilkan indikator jumlah digit
            const oldCounter = this.parentNode.querySelector('.digit-counter');
            if (oldCounter) {
                oldCounter.remove();
            }
            const digitCounter = document.createElement('small');
            digitCounter.className = 'digit-counter form-text text-info';
            digitCounter.textContent = `${this.value.length}/18 digit`;
            this.parentNode.appendChild(digitCounter);
        });
    }

    // ===========================================
    // 2. Fungsionalitas Cropper Foto
    // ===========================================
    const fotoInput = document.getElementById('foto_profile');
    const previewContainer = document.getElementById('preview-container');
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
             previewContainer.innerHTML = '';
        }
    });

    // Saat Modal ditampilkan, inisialisasi Cropper.js
    cropModal.on('shown.bs.modal', function() {
        if (cropper) {
            cropper.destroy(); // Hancurkan instance lama jika ada
        }
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 3 / 4, // Paksa rasio 3:4
            viewMode: 1,        // Batasi area crop agar tidak keluar dari kanvas
            background: false,
            responsive: true,
            restore: true,
            checkOrientation: true,
            guides: true,
            modal: true,
            highlight: true,
            movable: true,
            rotatable: true,
            scalable: true,
            zoomable: true,
        });
    });

    // Saat Modal ditutup (karena klik 'Batal' atau 'x'), reset input file
    cropModal.on('hidden.bs.modal', function(e) {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        // Jika modal ditutup tanpa menekan 'Potong & Simpan'
        // dan preview belum ada, reset input file
        if (!previewContainer.innerHTML) {
             fotoInput.value = '';
        }
    });

    // Saat tombol "Potong & Simpan" diklik
    cropButton.addEventListener('click', function() {
        if (cropper) {
            // Dapatkan kanvas yang dipotong
            // Kita set ukuran spesifik (misal 600x800) untuk konsistensi
            const canvas = cropper.getCroppedCanvas({
                width: 600,
                height: 800,
                imageSmoothingQuality: 'high',
            });

            // Ubah kanvas menjadi Blob (data file)
            canvas.toBlob(function(blob) {
                // Buat file baru dari Blob
                // Gunakan timestamp untuk nama file unik + ekstensi asli
                const fileExtension = originalFileName.split('.').pop();
                const newFileName = `cropped_${Date.now()}.${fileExtension}`;
                
                const newFile = new File([blob], newFileName, {
                    type: blob.type,
                });

                // Buat DataTransfer untuk menampung file baru
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(newFile);

                // Masukkan file baru ke input file
                fotoInput.files = dataTransfer.files;

                // Tampilkan preview dari gambar yang BARU (yang sudah dipotong)
                const previewReader = new FileReader();
                previewReader.onload = function(e) {
                    previewContainer.innerHTML = `
                        <div class="preview-info">
                            <img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">
                            <div class="mt-2">
                                <small class="text-success d-block">✓ Foto siap diupload (600 x 800 pixel)</small>
                            </div>
                        </div>
                    `;
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