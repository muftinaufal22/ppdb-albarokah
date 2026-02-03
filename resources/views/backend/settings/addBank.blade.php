<!-- Modal Tambah Bank -->
<div class="modal fade" id="addBankModal" tabindex="-1" role="dialog" aria-labelledby="addBankModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBankModalLabel">Tambah Akun Bank</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('backend.settings.bank.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        {{-- Menggunakan variabel $bank dari controller --}}
                        <select name="bank_name" class="form-control select2" required style="width: 100%;">
                            <option value="">-- Pilih Bank --</option>
                            @foreach ($bank as $b)
                                <option value="{{ $b->nama_bank }}">{{ $b->nama_bank }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nomor Rekening</label>
                        <input type="number" name="account_number" class="form-control" placeholder="Contoh: 1234567890" required />
                    </div>
                    <div class="form-group">
                        <label>Nama Pemilik Rekening</label>
                        <input type="text" name="account_name" class="form-control" placeholder="Contoh: Budi Sanjaya" required />
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="add_active" name="is_active" value="1" class="custom-control-input" checked />
                                <label class="custom-control-label" for="add_active">Aktif</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="add_notActive" name="is_active" value="0" class="custom-control-input" />
                                <label class="custom-control-label" for="add_notActive">Tidak Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach (Auth::user()->banks as $bank_account)
<div class="modal fade" id="editBankModal-{{$bank_account->id}}" tabindex="-1" role="dialog" aria-labelledby="editBankModalLabel-{{$bank_account->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBankModalLabel-{{$bank_account->id}}">Edit Akun Bank</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('backend.settings.bank.update', $bank_account->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        <select name="bank_name" class="form-control select2" required style="width: 100%;">
                            <option value="">-- Pilih Bank --</option>
                            @foreach ($bank as $b)
                                <option value="{{ $b->nama_bank }}" {{ $bank_account->bank_name == $b->nama_bank ? 'selected' : '' }}>
                                    {{ $b->nama_bank }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nomor Rekening</label>
                        <input type="number" name="account_number" class="form-control" value="{{ $bank_account->account_number }}" placeholder="Contoh: 1234567890" required />
                    </div>
                    <div class="form-group">
                        <label>Nama Pemilik Rekening</label>
                        <input type="text" name="account_name" class="form-control" value="{{ $bank_account->account_name }}" placeholder="Contoh: Budi Sanjaya" required />
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="edit_active_{{$bank_account->id}}" name="is_active" value="1" class="custom-control-input" {{ $bank_account->is_active == 1 ? 'checked' : '' }} />
                                <label class="custom-control-label" for="edit_active_{{$bank_account->id}}">Aktif</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="edit_notActive_{{$bank_account->id}}" name="is_active" value="0" class="custom-control-input" {{ $bank_account->is_active == 0 ? 'checked' : '' }} />
                                <label class="custom-control-label" for="edit_notActive_{{$bank_account->id}}">Tidak Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Tombol Hapus di sebelah kiri -->
                    <button type="button" class="btn btn-danger mr-auto" onclick="deleteBankAccount({{$bank_account->id}})">
                        <i data-feather="trash"></i> Hapus
                    </button>
                    
                    <!-- Tombol Batal dan Simpan di sebelah kanan -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteBankModal" tabindex="-1" role="dialog" aria-labelledby="deleteBankModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBankModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i data-feather="alert-triangle" class="text-warning mb-2" style="width: 48px; height: 48px;"></i>
                <h6>Apakah Anda yakin ingin menghapus akun bank ini?</h6>
                <small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteBankForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteBankAccount(bankId) {
    // Set action URL untuk form delete
    document.getElementById('deleteBankForm').action = '/backend/settings/bank/' + bankId;
    
    // Tutup modal edit dan buka modal konfirmasi hapus
    $('#editBankModal-' + bankId).modal('hide');
    $('#deleteBankModal').modal('show');
}

// Event handler untuk kembali ke modal edit jika user membatalkan hapus
$('#deleteBankModal').on('hidden.bs.modal', function () {
    // Jika ada modal edit yang terbuka sebelumnya, tampilkan kembali
    // (optional, tergantung UX yang diinginkan)
});
</script>