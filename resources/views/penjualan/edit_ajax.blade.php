@empty($penjualan)
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-ban"></i> Data tidak ditemukan.
                </div>
            </div>
        </div>
    </div>
@else
    <form id="form-edit" action="{{ url('/penjualan/' . $penjualan->penjualan_id . '/update') }}" method="POST">
        @csrf @method('PUT')
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Penjualan #{{ $penjualan->penjualan_id }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    {{-- Header --}}
                    <div class="form-group">
                        <label>Pembeli</label>
                        <input type="text" name="pembeli" value="{{ $penjualan->pembeli }}" class="form-control" required>
                        <small class="text-danger error-text" id="error-pembeli"></small>
                    </div>
                    <div class="form-group">
                        <label>Kode Penjualan</label>
                        <input type="text" name="penjualan_kode" value="{{ $penjualan->penjualan_kode }}"
                            class="form-control" required>
                        <small class="text-danger error-text" id="error-penjualan_kode"></small>
                    </div>

                    <hr>
                    <h5>Detail Barang</h5>
                    <div id="detail-barang-wrapper">
                        @foreach($penjualan->detail as $det)
                                        <div class="row barang-item">
                                            {{-- simpan detail_id utk update/delete --}}
                                            <input type="hidden" name="detail_id[]" value="{{ $det->detail_id }}">

                                            <div class="form-group col-md-4">
                                                <label>Barang</label>
                                                <select name="barang_id[]" class="form-control barang-select" required>
                                                    <option value="">– Pilih –</option>
                                                    @foreach($barang as $st)
                                                        <option value="{{ $st->barang_id }}" {{ $det->barang_id == $st->barang_id ? 'selected' : '' }}>
                                                            {{ $st->barang->barang_nama }} (Stok: {{ $st->stok_jumlah }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-md-1">
                                                <label>Stok</label>
                                                {{-- cari stok dari koleksi $barang --}}
                                                @php
                                                    $stokT = $barang->firstWhere('barang_id', $det->barang_id)->stok_jumlah ?? 0;
                                                @endphp
                                                <input type="text" name="stok_tersedia[]" class="form-control stok-field"
                                                    value="{{ $stokT }}" readonly>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>Harga Satuan</label>
                                                <input type="text" name="harga_satuan[]" class="form-control satuan-field"
                                                    value="{{ $det->barang->harga_jual }}" readonly>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>Jumlah</label>
                                                <input type="number" name="barang_jumlah[]" class="form-control jumlah-field"
                                                    value="{{ $det->jumlah }}" min="1" required>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>Subtotal</label>
                                                <input type="number" name="barang_harga[]" class="form-control total-field"
                                                    value="{{ $det->harga }}" readonly>
                                            </div>

                                            <div class="form-group col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger remove-barang">–</button>
                                            </div>
                                        </div>
                        @endforeach
                    </div>

                    <button type="button" id="tambah-barang" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Tambah Barang
                    </button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function () {
            const barangList = @json($barang->keyBy('barang_id'));

            function updateFields($row) {
                const id = $row.find('.barang-select').val();
                const stok = barangList[id]?.stok_jumlah || 0;
                const satuan = barangList[id]?.barang.harga_jual || 0;
                let jumlah = parseInt($row.find('.jumlah-field').val()) || 0;

                // koreksi stok
                if (jumlah > stok) {
                    jumlah = stok;
                    $row.find('.jumlah-field').val(stok);
                    Swal.fire('Stok terbatas', `Max ${stok}`, 'warning');
                }
                if (jumlah < 1) {
                    jumlah = 1;
                    $row.find('.jumlah-field').val(1);
                }

                $row.find('.stok-field').val(stok);
                $row.find('.satuan-field').val(satuan);
                $row.find('.total-field').val(satuan * jumlah);
            }

            // trigger perubahan
            $(document).on('change', '.barang-select', function () {
                updateFields($(this).closest('.barang-item'));
            });
            $(document).on('input', '.jumlah-field', function () {
                updateFields($(this).closest('.barang-item'));
            });

            // tambah row
            function createRow() {
                return `
          <div class="row barang-item">
            <input type="hidden" name="detail_id[]" value="">
            <div class="form-group col-md-4">
              <select name="barang_id[]" class="form-control barang-select" required>
                <option value="">– Pilih –</option>
                @foreach($barang as $st)
                      <option value="{{ $st->barang_id }}">
                        {{ $st->barang->barang_nama }} (Stok: {{ $st->stok_jumlah }})
                      </option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-1">
              <input type="text" name="stok_tersedia[]" class="form-control stok-field" value="0" readonly>
            </div>
            <div class="form-group col-md-2">
              <input type="text" name="harga_satuan[]" class="form-control satuan-field" value="0" readonly>
            </div>
            <div class="form-group col-md-2">
              <input type="number" name="barang_jumlah[]" class="form-control jumlah-field" min="1" value="1" required>
            </div>
            <div class="form-group col-md-2">
              <input type="number" name="barang_harga[]" class="form-control total-field" value="0" readonly>
            </div>
            <div class="form-group col-md-1 d-flex align-items-end">
              <button type="button" class="btn btn-danger remove-barang">–</button>
            </div>
          </div>`;
            }

            $('#tambah-barang').click(function () {
                $('#detail-barang-wrapper').append(createRow());
            });
            $(document).on('click', '.remove-barang', function () {
                $(this).closest('.barang-item').remove();
            });

            // jquery-validate + ajax submit ke update_ajax
            $('#form-edit').validate({
                submitHandler(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success(response) {
                            if (response.status) {
                                $('#modal-tambah').modal('hide');
                                Swal.fire('Sukses', response.message, 'success');
                                tablePenjualan.ajax.reload();
                            } else {
                                for (let f in response.msgField) {
                                    $(`#error-${f}`).text(response.msgField[f][0]);
                                }
                                Swal.fire('Error', 'Periksa input', 'error');
                            }
                        }
                    });
                    return false;
                }
            });
        });
    </script>
@endempty