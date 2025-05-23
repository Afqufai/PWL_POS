@extends('layouts.template')
@section('content')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/barang/import') }}')" class="btn btn-sm btn-info">Import
                    Barang</button>
                    <a href="{{ url('/barang/export_excel') }}" class="btn btn-primary"><i class="fa fa-fileexcel"></i> Export
                    Barang</a>
                <a href="{{ url('/barang/export_pdf') }}" class="btn btn-warning"><i class="fa fa-filepdf"></i> Export
                    Barang (PDF)</a>
                <button onclick="modalAction('{{ url('/barang/create_ajax') }}')"
                    class="btn btn-sm btn-success mt-1">Tambah</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="kategori_id" name="kategori_id" required>
                                <option value="">- Semua -</option>
                                @foreach($kategori as $item)
                                    <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kategori Barang</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Barang Kode</th>
                        <th>Nama Barang</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>

            <div id="modal-tambah" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static"
                data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection
        @push('css')
        @endpush
        @push('js')
            <script>
                function modalAction(url) {
                    $('#modal-tambah').load(url, function () {
                        $(this).modal('show');
                    });
                }
                var tableBarang;
                $(document).ready(function () {
                    tableBarang = $('#table_barang').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            "url": "{{ url('barang/list') }}",
                            "dataType": "json",
                            "type": "POST",
                            "data": function (d) {
                                if ($('#kategori_id').length) {
                                    d.kategori_id = $('#kategori_id').val();
                                } else {
                                    console.log('kategori_id tidak ada');
                                    d.kategori_id = null;
                                }
                            }
                        },
                        columns: [{
                            data: "DT_RowIndex",
                            className: "text-center",
                            width: "5%",
                            orderable: false,
                            searchable: false
                        }, {
                            data: "kategori.kategori_nama",
                            className: "",
                            width: "14%",
                            orderable: true,
                            searchable: false
                        }, {
                            data: "barang_kode",
                            className: "",
                            width: "10%",
                            orderable: true,
                            searchable: true
                        }, {
                            data: "barang_nama",
                            className: "",
                            width: "37%",
                            orderable: true,
                            searchable: true,
                        }, {
                            data: "harga_beli",
                            className: "",
                            width: "10%",
                            orderable: true,
                            searchable: false,
                            render: function (data, type, row) {
                                return new Intl.NumberFormat('id-ID').format(data);
                            }
                        }, {
                            data: "harga_jual",
                            className: "",
                            width: "10%",
                            orderable: true,
                            searchable: false,
                            render: function (data, type, row) {
                                return new Intl.NumberFormat('id-ID').format(data);
                            }
                        }, {
                            data: "aksi",
                            className: "text-center",
                            width: "14%",
                            orderable: false,
                            searchable: false
                        }
                        ]
                    });

                    $('#kategori_id').change(function () {
                        tableBarang.ajax.reload();
                    });
                });
            </script>
        @endpush