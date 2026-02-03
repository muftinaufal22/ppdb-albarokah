{{-- resources/views/backend/prestasi/create.blade.php --}}
@extends('layouts.backend.app')

@section('title', 'Tambah Prestasi')

@section('content')

    {{-- Alert Messages --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">
            <div class="alert-body">
                <strong>{{ $message }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @elseif($message = Session::get('error'))
        <div class="alert alert-danger" role="alert">
            <div class="alert-body">
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
                    <h2>Prestasi</h2>
                     <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('backend-prestasi.index')}}">Prestasi</a></li>
                            <li class="breadcrumb-item active">Tambah</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header header-bottom">
                        <h4>Tambah Prestasi</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('backend-prestasi.store') }}" method="POST" enctype="multipart/form-data" id="prestasiForm">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="judul">Judul Prestasi <span class="text-danger">*</span></label>
                                        <input type="text" id="judul" class="form-control @error('judul') is-invalid @enderror" name="judul" value="{{ old('judul') }}" placeholder="Masukkan judul prestasi" required>
                                        @error('judul')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
                                        <textarea id="deskripsi" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="5" placeholder="Deskripsikan prestasi secara detail" required>{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kategori">Kategori <span class="text-danger">*</span></label>
                                        <select id="kategori" name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="Akademik" {{ old('kategori') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                                            <option value="Non_akademik" {{ old('kategori') == 'Non_akademik' ? 'selected' : '' }}>Non_akademik</option>
                                        
                                        </select>
                                        @error('kategori')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tingkat">Tingkat <span class="text-danger">*</span></label>
                                        <select id="tingkat" name="tingkat" class="form-select @error('tingkat') is-invalid @enderror" required>
                                            <option value="">Pilih Tingkat</option>
                                            <option value="Sekolah" {{ old('tingkat') == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                                            <option value="Kecamatan" {{ old('tingkat') == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                                            <option value="Kabupaten" {{ old('tingkat') == 'Kabupaten' ? 'selected' : '' }}>Kabupaten</option>
                                            <option value="Provinsi" {{ old('tingkat') == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                                            <option value="Nasional" {{ old('tingkat') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                            <option value="Internasional" {{ old('tingkat') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                                        </select>
                                        @error('tingkat')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="peraih">Peraih Prestasi <span class="text-danger">*</span></label>
                                        <input type="text" id="peraih" class="form-control @error('peraih') is-invalid @enderror" name="peraih" value="{{ old('peraih') }}" placeholder="Nama peraih prestasi" required>
                                        @error('peraih')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="penyelenggara">Penyelenggara</label>
                                        <input type="text" id="penyelenggara" class="form-control @error('penyelenggara') is-invalid @enderror" name="penyelenggara" value="{{ old('penyelenggara') }}" placeholder="Nama penyelenggara/lembaga">
                                        @error('penyelenggara')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_prestasi">Tanggal Prestasi <span class="text-danger">*</span></label>
                                        <input type="date" id="tanggal_prestasi" class="form-control @error('tanggal_prestasi') is-invalid @enderror" name="tanggal_prestasi" value="{{ old('tanggal_prestasi') }}" max="{{ date('Y-m-d') }}" required>
                                        @error('tanggal_prestasi')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                            <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="gambar">Gambar Prestasi</label>
                                        <input type="file" id="gambar" class="form-control @error('gambar') is-invalid @enderror" name="gambar" accept="image/*" onchange="validateAndPreviewImage(this)">
                                        <small class="form-text text-muted">
                                            Format: JPG, JPEG, PNG. Maksimal 300kb<br>
                                            <strong>Gambar harus dalam orientasi landscape (horizontal) dengan aspek rasio minimal 4:3</strong>
                                        </small>
                                        @error('gambar')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                        
                                        <!-- Alert untuk validasi gambar -->
                                        <div id="imageValidationAlert" class="alert alert-danger mt-2" style="display: none;"></div>
                                        
                                        <div id="imagePreview" class="mt-2" style="display: none;">
                                            <img id="preview" src="#" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                            <div id="imageInfo" class="mt-2 text-muted small"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="action" value="publish" class="btn btn-primary" id="publishBtn">Simpan & Publikasikan</button>
                            <a href="{{ route('backend-prestasi.index') }}" class="btn btn-warning">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let imageIsValid = true;

// Validate and preview uploaded image
function validateAndPreviewImage(input) {
    const alertDiv = document.getElementById('imageValidationAlert');
    const previewDiv = document.getElementById('imagePreview');
    const preview = document.getElementById('preview');
    const infoDiv = document.getElementById('imageInfo');
    const publishBtn = document.getElementById('publishBtn');
    const draftBtn = document.getElementById('draftBtn');
    
    // Reset state
    alertDiv.style.display = 'none';
    previewDiv.style.display = 'none';
    imageIsValid = true;
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Check file size (2MB = 2097152 bytes)
        if (file.size > 2097152) {
            showImageError('Ukuran file terlalu besar. Maksimal 2MB.');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            
            // Create image object to get dimensions
            const img = new Image();
            img.onload = function() {
                const width = this.naturalWidth;
                const height = this.naturalHeight;
                const aspectRatio = width / height;
                const minAspectRatio = 4/3; // 1.333...
                
                // Check if landscape
                if (width <= height) {
                    showImageError('Gambar harus dalam orientasi landscape (horizontal). Lebar gambar harus lebih besar dari tinggi.');
                    return;
                }
                
                // Check aspect ratio
                if (aspectRatio < minAspectRatio) {
                    showImageError(
                        `Aspek rasio gambar minimal 4:3 (landscape). ` +
                        `Gambar saat ini memiliki rasio ${aspectRatio.toFixed(2)}:1. ` +
                        `Silakan gunakan gambar dengan rasio yang lebih lebar.`
                    );
                    return;
                }
                
                // Image is valid
                imageIsValid = true;
                previewDiv.style.display = 'block';
                infoDiv.innerHTML = `
                    <strong>Info Gambar:</strong><br>
                    Ukuran: ${width} x ${height} pixels<br>
                    Aspek Rasio: ${aspectRatio.toFixed(2)}:1<br>
                    Ukuran File: ${(file.size / 1024).toFixed(2)} KB<br>
                    <span class="text-success"><i class="fas fa-check-circle"></i> Gambar valid</span>
                `;
                
                // Enable submit buttons
                publishBtn.disabled = false;
                draftBtn.disabled = false;
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function showImageError(message) {
    const alertDiv = document.getElementById('imageValidationAlert');
    const publishBtn = document.getElementById('publishBtn');
    const draftBtn = document.getElementById('draftBtn');
    
    imageIsValid = false;
    alertDiv.innerHTML = `<strong>Error:</strong> ${message}`;
    alertDiv.style.display = 'block';
    
    // Disable submit buttons
    publishBtn.disabled = true;
    draftBtn.disabled = true;
    
    // Clear file input
    document.getElementById('gambar').value = '';
}

// Form validation
$(document).ready(function() {
    $('#prestasiForm').on('submit', function(e) {
        let isValid = true;
        
        // Check if image is uploaded and valid
        const fileInput = document.getElementById('gambar');
        if (fileInput.files.length > 0 && !imageIsValid) {
            e.preventDefault();
            alert('Mohon perbaiki masalah dengan gambar sebelum menyimpan.');
            return false;
        }
        
        // Check required fields
        const requiredFields = ['judul', 'deskripsi', 'kategori', 'tingkat', 'peraih', 'tanggal_prestasi'];
        
        requiredFields.forEach(function(field) {
            const fieldElement = document.getElementById(field);
            if (!fieldElement.value.trim()) {
                fieldElement.classList.add('is-invalid');
                isValid = false;
            } else {
                fieldElement.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi (*)');
            return false;
        }
        
        return true;
    });
    
    // Auto generate slug preview
    $('#judul').on('input', function() {
        const title = $(this).val();
        const slug = title.toLowerCase()
                          .replace(/[^a-z0-9\s-]/g, '')
                          .replace(/\s+/g, '-')
                          .replace(/-+/g, '-')
                          .trim('-');
        
        if (title) {
            console.log('Generated slug:', slug);
        }
    });
    
    // Remove validation error when user starts typing
    $('.form-control, .form-select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>

{{-- Optional: Add some custom CSS for better UX --}}
<style>
#imageValidationAlert {
    font-size: 0.875rem;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

#imageInfo {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 4px;
    border-left: 3px solid #28a745;
}
</style>
@endsection