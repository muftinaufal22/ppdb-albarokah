<div class="disabled-backdrop-ex">
    <div class="modal fade text-left modal-primary" id="modalPembayaran" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel4">Konfirmasi Pembayaran</h4>
                    {{-- Tombol close di Bootstrap 5 menggunakan data-bs-dismiss --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_payment" id="id_payment">
                    <div class="row">
                        {{-- Typo 'div div' diperbaiki menjadi 'div' --}}
                        <div class="col-6">
                            <div class="form-group">
                                <label for="nisn">NISN</label>
                                <input type="text" class="form-control" id="nisn" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">NAMA</label>
                                <input type="text" class="form-control" id="name" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="month">Pembayaran Bulan</label>
                                <input type="text" class="form-control" id="month" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="amount">Jumlah</label>
                                <input type="text" class="form-control" id="amount" readonly>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <h5>Detail Bukti Transfer</h5>
                            <hr>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="sender">Nama Pengirim</label>
                                <input type="text" class="form-control" id="sender" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="banksender">Bank Pengirim</label>
                                <input type="text" class="form-control" id="banksender" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="datefile">Tanggal Transfer</label>
                                <input type="text" class="form-control" id="datefile" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="destinationbank">Bank Tujuan</label>
                                <input type="text" class="form-control" id="destinationbank" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- Tag </form> yang tidak perlu sudah dihapus --}}
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="konfirmasiPembayaran" class="btn btn-primary">Konfirmasi Pembayaran</button>
                </div>
            </div>
        </div>
    </div>
</div>