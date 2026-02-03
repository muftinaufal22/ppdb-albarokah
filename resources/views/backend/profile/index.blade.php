@extends('layouts.backend.app')

@section('title')
    Profile Settings
@endsection

{{-- Kita tidak perlu @section('css') lagi karena sudah dipindah ke app.blade.php --}}

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

   @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <div class="alert-body">
            <strong>Gagal, Data yang dimasukan tidak valid !</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    </div>
   @endif
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title mb-0">Profile Settings</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="page-account-settings">
                <div class="row">
                    <div class="col-md-3 mb-2 mb-md-0">
                        {{-- KODE MENU YANG HILANG - SEKARANG SUDAH ADA LAGI --}}
                        <ul class="nav nav-pills flex-column nav-left">
                            <li class="nav-item">
                                <a class="nav-link active" id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="true">
                                    <i data-feather="user" class="font-medium-3 mr-1"></i>
                                    <span class="font-weight-bold">General</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="account-pill-password" data-toggle="pill" href="#account-vertical-password" aria-expanded="false">
                                    <i data-feather="lock" class="font-medium-3 mr-1"></i>
                                    <span class="font-weight-bold">Change Password</span>
                                </a>
                            </li>
                        </ul>
                        {{-- AKHIR KODE MENU YANG HILANG --}}
                    </div>
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="account-vertical-general" aria-labelledby="account-pill-general" aria-expanded="true">
                                        
                                        <form class="validate-form mt-2" action=" {{route('profile-settings.update', $profile->id)}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="media">
                                                <a href="javascript:void(0);" class="mr-25">
                                                    @if (Auth::user()->foto_profile == NULL)
                                                        <img class="round" id="profile-image-preview" src="{{asset('Assets/Backend/images/user.png')}}" alt="avatar" height="80" width="80">
                                                    @else
                                                        <img class="round" id="profile-image-preview" src="{{ asset('storage/images/profile/' . Auth::user()->foto_profile) }}" alt="avatar" height="80" width="80">
                                                    @endif
                                                </a>
                                                <div class="media-body mt-75 ml-1">
                                                    {{-- PERUBAHAN 1: Tombol Upload jadi Hijau --}}
                                                    <label for="foto_profile_input" class="btn btn-sm btn-success mb-75 mr-75">Upload Foto</label>
                                                    
                                                    <input type="file" id="foto_profile_input" name="foto_profile" hidden accept="image/jpeg,image/png,image/jpg" />
                                                    <p>Rasio 3:4. Format: JPG, PNG, JPEG. Maks 2MB.</p>
                                                    @error('foto_profile')
                                                        <small class="text-danger d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <hr>

                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-nama">Nama</label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nama" value="{{ old('name', $profile->name) }}" />
                                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-e-mail">E-mail</label>
                                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ old('email', $profile->email) }}" />
                                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="col-12">
                                                    {{-- PERUBAHAN 2: Tombol Update jadi Hijau --}}
                                                    <button type="submit" class="btn btn-success mt-2 mr-1">Update</button>
                                                    <a href="/home" class="btn btn-outline-secondary mt-2">Batal</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    {{-- KODE FORM PASSWORD YANG HILANG - SEKARANG SUDAH ADA LAGI --}}
                                    <div class="tab-pane fade" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                        <form class="validate-form" action=" {{route('profile.change-password', $profile->id)}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-old-password">Password Saat Ini</label>
                                                        <div class="input-group form-password-toggle input-group-merge">
                                                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" placeholder="Password Saat Ini" />
                                                            <div class="input-group-append">
                                                                <div class="input-group-text cursor-pointer">
                                                                    <i data-feather="eye"></i>
                                                                </div>
                                                            </div>
                                                            @error('current_password')
                                                                <div class="invalid-feedback">
                                                                    <strong>{{ $message }}</strong>
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-new-password">Password Baru</label>
                                                        <div class="input-group form-password-toggle input-group-merge">
                                                            <input type="password"  name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password Baru" />
                                                            <div class="input-group-append">
                                                                <div class="input-group-text cursor-pointer">
                                                                    <i data-feather="eye"></i>
                                                                </div>
                                                            </div>
                                                            @error('password')
                                                                <div class="invalid-feedback">
                                                                    <strong>{{ $message }}</strong>
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-retype-new-password">Masukan Ulang Password Baru</label>
                                                        <div class="input-group form-password-toggle input-group-merge">
                                                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Masukan Ulang Password Baru" />
                                                            <div class="input-group-append">
                                                                <div class="input-group-text cursor-pointer"><i data-feather="eye"></i></div>
                                                            </div>
                                                            @error('password_confirmation')
                                                                <div class="invalid-feedback">
                                                                    <strong>{{ $message }}</strong>
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    {{-- PERUBAHAN 3: Tombol Update Password jadi Hijau --}}
                                                    <button type="submit" class="btn btn-success mr-1 mt-1">Update</button>
                                                    <button type="reset" class="btn btn-outline-secondary mt-1">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    {{-- AKHIR KODE FORM PASSWORD YANG HILANG --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

{{-- 
=============================================
BAGIAN TAMBAHAN: Modal & Script untuk Cropper
============================================= 
--}}

<div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- PERUBAHAN 4: Header Modal jadi Hijau --}}
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white" id="modalLabel">Sesuaikan Foto (Rasio 3:4)</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
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
                {{-- PERUBAHAN 5: Tombol Simpan jadi Hijau --}}
                <button type="button" class="btn btn-success" id="cropButton">Potong & Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Menggunakan @push('js') agar sesuai dengan layout app.blade.php Anda --}}
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cek apakah elemen ada sebelum menambahkan listener
    const fotoInput = document.getElementById('foto_profile_input');
    const imagePreview = document.getElementById('profile-image-preview'); // Preview foto di halaman
    const imageToCrop = document.getElementById('imageToCrop'); // Gambar di dalam modal
    const cropModal = $('#cropModal'); // Modal
    const cropButton = document.getElementById('cropButton');
    
    // Pastikan semua elemen penting ada
    if (!fotoInput || !imagePreview || !imageToCrop || !cropModal.length || !cropButton) {
        console.error("Elemen cropper tidak ditemukan. Pastikan ID sudah benar.");
        return; // Hentikan eksekusi jika ada elemen yang hilang
    }

    let cropper;
    let originalFileName;
    const originalImageSrc = imagePreview.src; // Simpan src gambar asli

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
                    cropModal.modal('show'); // <-- Ini membutuhkan jQuery dan Bootstrap.js
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        
        } else {
            // Jika user membatalkan pilihan
            imagePreview.src = originalImageSrc; // Kembalikan ke gambar asli
        }
    });

    // Saat Modal ditampilkan, inisialisasi Cropper.js
    cropModal.on('shown.bs.modal', function() {
        if (cropper) cropper.destroy();
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
        // Jika modal ditutup tanpa 'Potong', reset input file
        if (imagePreview.src === originalImageSrc) {
             fotoInput.value = '';
        }
    });

    // Saat tombol "Potong & Simpan" diklik
    cropButton.addEventListener('click', function() {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({
                width: 600,
                height: 800,
                imageSmoothingQuality: 'high',
            });

            canvas.toBlob(function(blob) {
                const fileExtension = originalFileName.split('.').pop();
                const newFileName = `cropped_${Date.now()}.${fileExtension}`;
                const newFile = new File([blob], newFileName, { type: blob.type });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(newFile);
                fotoInput.files = dataTransfer.files;

                const previewReader = new FileReader();
                previewReader.onload = function(e) {
                    imagePreview.src = e.target.result; 
                };
                previewReader.readAsDataURL(blob);

                cropModal.modal('hide');

            }, 'image/jpeg', 0.95);
        }
    });
});
</script>
@endpush