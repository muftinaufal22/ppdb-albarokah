@extends('layouts.backend.app')

@section('title', 'Tambah Master SPP')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-plus"></i> Tambah Master SPP Baru
                    </h4>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('spp.master-spp.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kelas_id" class="form-label">
                                        <i class="fas fa-chalkboard-teacher"></i> Pilih Kelas <span class="text-danger">*</span>
                                    </label>
                                    <select name="kelas_id" id="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($kelas as $item)
                                            <option value="{{ $item->id }}" {{ old('kelas_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tahun_ajaran" class="form-label">
                                        <i class="fas fa-calendar-alt"></i> Tahun Ajaran <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="tahun_ajaran" 
                                           id="tahun_ajaran" 
                                           class="form-control @error('tahun_ajaran') is-invalid @enderror" 
                                           placeholder="Contoh: 2024/2025" 
                                           value="{{ old('tahun_ajaran') }}" 
                                           required>
                                    @error('tahun_ajaran')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        Format: YYYY/YYYY (contoh: 2024/2025)
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="nominal" class="form-label">
                                        <i class="fas fa-money-bill-wave"></i> Nominal SPP <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" 
                                               name="nominal" 
                                               id="nominal" 
                                               class="form-control @error('nominal') is-invalid @enderror" 
                                               placeholder="Masukkan nominal SPP" 
                                               value="{{ old('nominal') }}" 
                                               min="0" 
                                               step="1000"
                                               required>
                                        @error('nominal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> Masukkan angka tanpa titik atau koma
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Preview Nominal --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-light border" id="preview-nominal" style="display: none;">
                                    <strong>Preview Nominal:</strong> 
                                    <span id="preview-text" class="text-success"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('spp.master-spp.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Data SPP
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nominalInput = document.getElementById('nominal');
    const previewDiv = document.getElementById('preview-nominal');
    const previewText = document.getElementById('preview-text');
    
    // Format number dengan separator ribuan
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Event listener untuk preview nominal
    nominalInput.addEventListener('input', function() {
        const value = this.value;
        
        if (value && value > 0) {
            previewText.textContent = 'Rp ' + formatNumber(value);
            previewDiv.style.display = 'block';
        } else {
            previewDiv.style.display = 'none';
        }
    });
    
    // Auto format tahun ajaran
    const tahunAjaranInput = document.getElementById('tahun_ajaran');
    
    tahunAjaranInput.addEventListener('blur', function() {
        let value = this.value.replace(/\D/g, ''); // Hapus semua karakter non-digit
        
        if (value.length >= 4) {
            const tahunPertama = value.substring(0, 4);
            const tahunKedua = value.length >= 8 
                ? value.substring(4, 8) 
                : (parseInt(tahunPertama) + 1).toString();
            
            this.value = tahunPertama + '/' + tahunKedua;
        }
    });
    
    // Trigger preview jika ada old value
    if (nominalInput.value) {
        nominalInput.dispatchEvent(new Event('input'));
    }
});
</script>
@endpush
