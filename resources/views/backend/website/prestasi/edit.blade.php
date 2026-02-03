@extends('layouts.backend.app')

@section('title', 'Edit Prestasi')

@section('content')
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Edit Prestasi</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('backend-prestasi.index') }}">Prestasi</a></li>
                            <li class="breadcrumb-item active">Edit</li>
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
                    <div class="card-header">
                        <h4 class="card-title">Form Edit Prestasi</h4>
                        <div class="card-tools">
                            <a href="{{ route('backend-prestasi.index') }}" class="btn btn-outline-secondary">
                                <i data-feather="arrow-left" class="me-50"></i>
                                Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('backend-prestasi.update', $prestasi->slug) }}" method="POST" enctype="multipart/form-data" id="prestasiForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="judul" class="form-label">Judul Prestasi <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('judul') is-invalid @enderror" 
                                               id="judul" 
                                               name="judul" 
                                               value="{{ old('judul', $prestasi->judul) }}" 
                                               placeholder="Masukkan judul prestasi"
                                               required>
                                        @error('judul')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                                 id="deskripsi" 
                                                 name="deskripsi" 
                                                 rows="5" 
                                                 placeholder="Deskripsikan prestasi secara detail"
                                                 required>{{ old('deskripsi', $prestasi->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                                <select class="form-select @error('kategori') is-invalid @enderror" 
                                                        id="kategori" 
                                                        name="kategori" 
                                                        required>
                                                    <option value="">Pilih Kategori</option>
                                                    <option value="Akademik" {{ old('kategori', $prestasi->kategori) == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                                                    <option value="Non_akademik" {{ old('kategori', $prestasi->kategori) == 'Non_akademik' ? 'selected' : '' }}>Non akademik</option>
                                                
                                                </select>
                                                @error('kategori')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="tingkat" class="form-label">Tingkat <span class="text-danger">*</span></label>
                                                <select class="form-select @error('tingkat') is-invalid @enderror" 
                                                        id="tingkat" 
                                                        name="tingkat" 
                                                        required>
                                                    <option value="">Pilih Tingkat</option>
                                                    <option value="Sekolah" {{ old('tingkat', $prestasi->tingkat) == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                                                    <option value="Kecamatan" {{ old('tingkat', $prestasi->tingkat) == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                                                    <option value="Kabupaten" {{ old('tingkat', $prestasi->tingkat) == 'Kabupaten' ? 'selected' : '' }}>Kabupaten</option>
                                                    <option value="Provinsi" {{ old('tingkat', $prestasi->tingkat) == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                                                    <option value="Nasional" {{ old('tingkat', $prestasi->tingkat) == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                                    <option value="Internasional" {{ old('tingkat', $prestasi->tingkat) == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                                                </select>
                                                @error('tingkat')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="peraih" class="form-label">Peraih Prestasi <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('peraih') is-invalid @enderror" 
                                               id="peraih" 
                                               name="peraih" 
                                               value="{{ old('peraih', $prestasi->peraih) }}" 
                                               placeholder="Nama peraih prestasi"
                                               required>
                                        @error('peraih')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="penyelenggara" class="form-label">Penyelenggara</label>
                                        <input type="text" 
                                               class="form-control @error('penyelenggara') is-invalid @enderror" 
                                               id="penyelenggara" 
                                               name="penyelenggara" 
                                               value="{{ old('penyelenggara', $prestasi->penyelenggara) }}" 
                                               placeholder="Nama penyelenggara/lembaga">
                                        @error('penyelenggara')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="gambar" class="form-label">Gambar Prestasi</label>
                                        
                                        @if($prestasi->gambar)
                                        <div class="mb-3">
                                            <label class="form-label">Gambar Saat Ini:</label>
                                            <div class="current-image">
                                                <img src="{{ asset('storage/' . $prestasi->gambar) }}" 
                                                    alt="Current Image" 
                                                    class="img-fluid rounded" 
                                                    style="max-height: 200px;">
                                                <div class="mt-2">
                                                    <small class="text-muted">{{ basename($prestasi->gambar) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <input type="file" 
                                               class="form-control @error('gambar') is-invalid @enderror" 
                                               id="gambar" 
                                               name="gambar" 
                                               accept="image/*"
                                               onchange="validateAndPreviewImage(this)">
                                        <div class="form-text">
                                            <small class="text-muted">
                                                Format: JPG, JPEG, PNG. Maksimal 300kb<br>
                                                <strong>Gambar harus dalam orientasi landscape (horizontal) dengan aspek rasio minimal 4:3</strong><br>
                                                <strong>Kosongkan jika tidak ingin mengubah gambar</strong>
                                            </small>
                                        </div>
                                        @error('gambar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        <!-- Alert untuk validasi gambar -->
                                        <div id="imageValidationAlert" class="alert alert-danger mt-2" style="display: none;"></div>
                                        
                                        <div id="imagePreview" class="mt-3" style="display: none;">
                                            <label class="form-label">Preview Gambar Baru:</label>
                                            <img id="preview" src="#" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                            <div id="imageInfo" class="mt-2 text-muted small"></div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tanggal_prestasi" class="form-label">Tanggal Prestasi <span class="text-danger">*</span></label>
                                        <input type="date" 
                                               class="form-control @error('tanggal_prestasi') is-invalid @enderror" 
                                               id="tanggal_prestasi" 
                                               name="tanggal_prestasi" 
                                               value="{{ old('tanggal_prestasi', $prestasi->tanggal_prestasi ? \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->format('Y-m-d') : '') }}" 
                                               max="{{ date('Y-m-d') }}"
                                               required>
                                        @error('tanggal_prestasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" 
                                                name="status">
                                            <option value="aktif" {{ old('status', $prestasi->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="nonaktif" {{ old('status', $prestasi->status) == 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary me-1" id="updateBtn">Simpan Perubahan</button>
                            <a href="{{ route('backend-prestasi.index') }}" class="btn btn-outline-secondary">Batal</a>
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
    const updateBtn = document.getElementById('updateBtn');
    
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
                    <strong>Info Gambar Baru:</strong><br>
                    Ukuran: ${width} x ${height} pixels<br>
                    Aspek Rasio: ${aspectRatio.toFixed(2)}:1<br>
                    Ukuran File: ${(file.size / 1024).toFixed(2)} KB<br>
                    <span class="text-success"><i class="fas fa-check-circle"></i> Gambar valid</span>
                `;
                
                // Enable submit button
                updateBtn.disabled = false;
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        // No file selected, reset to valid state
        imageIsValid = true;
        updateBtn.disabled = false;
    }
}

function showImageError(message) {
    const alertDiv = document.getElementById('imageValidationAlert');
    const updateBtn = document.getElementById('updateBtn');
    
    imageIsValid = false;
    alertDiv.innerHTML = `<strong>Error:</strong> ${message}`;
    alertDiv.style.display = 'block';
    
    // Disable submit button
    updateBtn.disabled = true;
    
    // Clear file input
    document.getElementById('gambar').value = '';
}

// Form validation
$(document).ready(function() {
    $('#prestasiForm').on('submit', function(e) {
        let isValid = true;
        
        // Check if new image is uploaded and valid
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

.current-image img {
    border: 2px solid #e3e6f0;
    border-radius: 8px;
}
</style>
@endsection