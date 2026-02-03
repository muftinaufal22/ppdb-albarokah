@extends('layouts.backend.app')

@section('title')
    Settings
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

    @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <div class="alert-body">
            <strong>Gagal, Data yang dimasukan tidak valid !</strong>
            <ul class="mt-1">
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
                        <h2 class="content-header-title mb-0">Settings</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="page-account-settings">
                <div class="row">
                    <div class="col-md-3 mb-2 mb-md-0">
                        <ul class="nav nav-pills flex-column nav-left">
                            <li class="nav-item">
                                <a class="nav-link active" id="account-pill-bank" data-toggle="pill" href="#account-vertical-bank" aria-expanded="true">
                                    <i data-feather="credit-card" class="font-medium-3 mr-1"></i>
                                    <span class="font-weight-bold">Account Bank</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="spp-setting" data-toggle="pill" href="#sppsetting" aria-expanded="false">
                                    <i data-feather="dollar-sign" class="font-medium-3 mr-1"></i>
                                    <span class="font-weight-bold">Setting SPP</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="account-vertical-bank" aria-labelledby="account-pill-bank" aria-expanded="true">
                                        <div class="row">
                                            @foreach (Auth::user()->banks as $bank_account)
                                            <div class="col-md-4">
                                                <a data-toggle="modal" data-target="#editBankModal-{{$bank_account->id}}">
                                                    <div class="card bg-light-secondary">
                                                        <div class="card-body text-center">
                                                            <div class="card-title">
                                                                {{$bank_account->bank_name}}
                                                            </div>
                                                            <span>{{$bank_account->account_number}}</span> <br>
                                                            <small>{{$bank_account->account_name}}</small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            @endforeach
                                            <div class="col-md-4">
                                                <a data-toggle="modal" data-target="#addBankModal">
                                                    <div class="card bg-primary">
                                                        <div class="card-body text-center d-flex justify-content-center align-items-center" style="min-height: 110px;">
                                                            <div class="card-title text-white">
                                                                Tambah Akun Bank <i data-feather='plus'></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="sppsetting" role="tabpanel" aria-labelledby="spp-settings" aria-expanded="false">
                                        <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#addSppModal">
                                            <i data-feather="plus"></i> Tambah Setting SPP
                                        </button>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Kelas</th>
                                                        <th>Tahun Ajaran</th>
                                                        <th>Bulan</th>
                                                        <th>Biaya</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($spp as $item)
                                                    <tr>
                                                        <td>{{ $item->kelas->nama ?? 'N/A' }}</td>
                                                        <td>{{ $item->tahun_ajaran }}</td>
                                                        <td>{{ $item->bulan ?? 'Semua' }}</td>
                                                        <td>Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-success" 
                                                                    data-toggle="modal" 
                                                                    data-target="#editSppModal"
                                                                    data-id="{{ $item->id }}"
                                                                    data-kelas_id="{{ $item->kelas_id }}"
                                                                    data-tahun_ajaran="{{ $item->tahun_ajaran }}"
                                                                    data-bulan="{{ $item->bulan }}"
                                                                    data-amount="{{ $item->amount }}"
                                                                    data-url="{{ route('backend.settings.spp.update', $item->id) }}">
                                                                Edit
                                                            </button>
                                                            <form action="{{ route('backend.settings.spp.delete', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            @if ($selectedKelas)
                                                                Tidak ada data pengaturan SPP untuk kelas yang dipilih.
                                                            @else
                                                                Belum ada data pengaturan SPP.
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            @include('backend.settings.addBank')
            @include('backend.settings.addSpp')

            <div class="modal fade" id="editSppModal" tabindex="-1" role="dialog" aria-labelledby="editSppModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST" id="edit-spp-form">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Setting SPP</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="edit-kelas_id">Kelas</label>
                                    <select name="kelas_id" id="edit-kelas_id" class="form-control select2" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($kelas as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit-tahun_ajaran">Tahun Ajaran</label>
                                    <input type="text" name="tahun_ajaran" id="edit-tahun_ajaran" class="form-control" placeholder="Contoh: 2024/2025" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit-bulan">Bulan (Opsional)</label>
                                    <input type="text" name="bulan" id="edit-bulan" class="form-control" placeholder="Contoh: Juli">
                                </div>
                                <div class="form-group">
                                    <label for="edit-amount">Biaya SPP</label>
                                    <input type="number" name="amount" id="edit-amount" class="form-control" placeholder="Contoh: 150000" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
<script>
    $('#editSppModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var kelas_id = button.data('kelas_id');
        var tahun_ajaran = button.data('tahun_ajaran');
        var bulan = button.data('bulan');
        var amount = button.data('amount');
        var url = button.data('url');

        var modal = $(this);
        modal.find('#edit-kelas_id').val(kelas_id).trigger('change');
        modal.find('#edit-tahun_ajaran').val(tahun_ajaran);
        modal.find('#edit-bulan').val(bulan ?? '');
        modal.find('#edit-amount').val(amount);
        modal.find('#edit-spp-form').attr('action', url);
    });

    $('#edit-kelas_id').select2({
        dropdownParent: $('#editSppModal')
    });
</script>
@endpush