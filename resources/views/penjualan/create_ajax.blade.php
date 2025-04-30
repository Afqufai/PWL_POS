<form action="{{ url('/penjualan/') }}" method="POST" id="form-tambah">
    @csrf
    <div id="myModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    {{--- Alert Info --}}
                    <i class="fas fa-info-circle"></i>
                    <strong>Perhatian!</strong><br>
                    Pastikan semua data yang dimasukkan sudah benar.
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Pembeli</label>
                        <input type="text" name="pembeli" id="pembeli" class="form-control" required>
                        <small id="error-pembeli" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Kode Penjualan</label>
                        <input type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" required>
                        <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <hr>
                <h5>Detail Barang</h5>
                <div id="detail-barang-wrapper">
                    <div class="row barang-item">
                        <div class="form-group col-md-4">
                            <label>Nama Barang</label>
                            <select class="form-control barang-select" name="barang_id[]" required>
                                <option value="">- Pilih Barang -</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->barang_id }}">
                                        {{ $item->barang->barang_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <label>Stok</label>
                            <input type="text" name="stok_tersedia[]" class="form-control stok-field" value="0"
                                readonly>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Harga Satuan</label>
                            <input type="text" name="harga_satuan[]" class="form-control satuan-field" value="0"
                                readonly>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Jumlah</label>
                            <input type="number" name="barang_jumlah[]" class="form-control jumlah-field" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Harga</label>
                            <input type="number" name="barang_harga[]" class="form-control total-field" readonly
                                required>
                        </div>
                        <div class="form-group col-md-1">
                            <label>Aksi</label>
                            <button type="button" id="tambah-barang" class="btn btn-success">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        const barangList = @json($barang->keyBy('barang_id'));

        function updateFields(parent) {
            const id = parent.find('.barang-select').val();
            const stok = barangList[id]?.stok_jumlah || 0;
            const satuan = barangList[id]?.barang?.harga_jual || 0;
            let jumlah = parseInt(parent.find('.jumlah-field').val()) || 0;

            // batasi stok
            if (jumlah > stok) {
                jumlah = stok;
                parent.find('.jumlah-field').val(stok);
                Swal.fire('Jumlah melebihi stok!', `Hanya ada ${stok} item yang ada di inventori!`, 'warning');
            }
            if (jumlah < 0) {
                jumlah = 0;
                parent.find('.jumlah-field').val(0);
                Swal.fire('Error', 'Jumlah tidak boleh negatif', 'error');
            }

            // isi ke kolom
            parent.find('.stok-field').val(stok);
            parent.find('.satuan-field').val(satuan);
            parent.find('.total-field').val((satuan * jumlah));
        }

        // trigger saat dipilih
        $(document).on('change', '.barang-select', function () {
            updateFields($(this).closest('.barang-item'));
        });

        // trigger saat jumlah diubah
        $(document).on('input', '.jumlah-field', function () {
            updateFields($(this).closest('.barang-item'));
        });

        function createBarangRow() {
            return `
            <div class="row barang-item">
                <div class="form-group col-md-4">
                        <select class="form-control barang-select" name="barang_id[]" required>
                            <option value="">- Pilih Barang -</option>
                            @foreach($barang as $item)
                                <option value="{{ $item->barang_id }}">
                                    {{ $item->barang->barang_nama }}
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
                    <input type="number" name="barang_jumlah[]" class="form-control jumlah-field" required>
                </div>
                <div class="form-group col-md-2">
                    <input type="number" name="barang_harga[]" class="form-control total-field" readonly required>
                </div>
                <div class="form-group col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-barang">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>`;
        }

        //Tambah/Hapus Barang, Tampilan diatas.
        $('#tambah-barang').on('click', function () {
            $('#detail-barang-wrapper').append(createBarangRow());
        });
        $(document).on('click', '.remove-barang', function () {
            $(this).closest('.barang-item').remove();
        });

        // Validate + Ajax Submit
        $("#form-tambah").validate({
            rules: {
                pembeli: { required: true },
                penjualan_kode: { required: true }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        $('.error-text').text('');
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', response.message, 'success');
                            tablePenjualan.ajax.reload();
                        } else {
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire('Terjadi Kesalahan', response.message, 'error');
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>