@extends('layouts.backend.app')

@section('title', 'Edit Master SPP')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Edit Master SPP
                    </h4>
                    <small class="text-muted">
                        Mengedit data SPP untuk kelas: <strong>{{ $spp->kelas->nama ?? 'N/A' }}</strong>
                    </small>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('spp.master-spp.update', $spp->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kelas_id" class="form-label">
                                        <i class="fas fa-chalkboard-teacher"></i> Pilih Kelas <span class="text-danger">*</span>
                                    </label>
                                    <select name="kelas_id" id="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($kelas as $item)
                                            <option value="{{ $item->id }}" 
                                                {{ (old('kelas_id') ?? $spp->kelas_id) == $item->id ? 'selected' : '' }}>
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
                                           value="{{ old('tahun_ajaran') ?? $spp->tahun_ajaran }}" 
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
                                               value="{{ old('nominal') ?? $spp->nominal }}" 
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
                                <div class="alert alert-light border" id="preview-nominal">
                                    <strong>Preview Nominal:</strong> 
                                    <span id="preview-text" class="text-success">
                                        Rp {{ number_format($spp->nominal, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Info Data Sebelumnya --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Data Sebelumnya:</h6>
                                    <ul class="mb-0">
                                        <li><strong>Kelas:</strong> {{ $spp->kelas->nama ?? 'N/A' }}</li>
                                        <li><strong>Nominal:</strong> Rp {{ number_format($spp->nominal, 0, ',', '.') }}</li>
                                        <li><strong>Tahun Ajaran:</strong> {{ $spp->tahun_ajaran }}</li>
                                        <li><strong>Terakhir Diupdate:</strong> {{ $spp->updated_at->format('d M Y H:i') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('spp.master-spp.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-warning me-2" onclick="resetForm()">
                                            <i class="fas fa-undo"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Data SPP
                                        </button>
                                    </div>
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
        } else {
            previewText.textContent = 'Rp 0';
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
});

// Function untuk reset form ke nilai asli
function resetForm() {
    if (confirm('Yakin ingin mereset form ke data asli?')) {
        document.getElementById('kelas_id').value = '{{ $spp->kelas_id }}';
        document.getElementById('nominal').value = '{{ $spp->nominal }}';
        document.getElementById('tahun_ajaran').value = '{{ $spp->tahun_ajaran }}';
        
        // Update preview
        document.getElementById('preview-text').textContent = 'Rp {{ number_format($spp->nominal, 0, ',', '.') }}';
    }
}
</script>
@endpush
