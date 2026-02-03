@extends('layouts.backend.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Edit Foto Struktur Organisasi</h3>
                    <a href="{{ route('backend-struktur-organisasi.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
                <form action="{{ route('backend-struktur-organisasi.update', $backendStrukturOrganisasi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        {{-- Input untuk Unggah Foto --}}
                        <div class="form-group mb-3">
                            <label for="foto" class="form-label fw-bold">Ganti Foto Saat Ini:</label>
                            
                            @if($backendStrukturOrganisasi->foto)
                            <div class="mb-3">
                                <img src="{{ $backendStrukturOrganisasi->foto_url }}" alt="Foto Saat Ini" class="img-thumbnail rounded" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            @endif
                            
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*" onchange="previewImage(this)">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG. Maksimal 500 kb.</small>
                            
                            <div class="mt-2" id="imagePreview" style="display: none;">
                                <label class="form-label text-muted small">Preview Foto Baru:</label><br>
                                <img id="preview" src="#" alt="Preview" class="img-thumbnail rounded" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>

                        {{-- Tombol Tautan ke Canva (DITAMBAHKAN) --}}
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Template Desain</label>
                            <div>
                                <a href="https://www.canva.com/design/DAG1REpjlbQ/rEyF9lYbZLpXjvmo4uZcug/edit?utm_content=DAG1REpjlbQ&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton" class="btn btn-success" target="_blank">
                                    Lihat & Edit Desain di Canva
                                </a>
                            </div>
                            <small class="form-text text-muted">Klik tombol di atas untuk membuka template</small>
                            <small class="form-text text-muted">Desain Struktur Organisasi.</small>
                        </div>

                    </div>
                    <div class="card-footer bg-light text-end">
                        <button type="submit" class="btn btn-primary">Update Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush