@extends('layouts.template')
@section('content')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-sm btn-info">Import
                    Barang</button>
                <a href="{{ url('/stok/export_excel') }}" class="btn btn-primary"><i class="fa fa-fileexcel"></i> Export
                    Barang</a>
                <a href="{{ url('/stok/export_pdf') }}" class="btn btn-warning"><i class="fa fa-filepdf"></i> Export Barang
                    (PDF)</a>
                <button onclick="modalAction('{{ url('/stok/create_ajax') }}')"
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
                            <select class="form-control" id="supplier_id" name="supplier_id" required>
                                <option value="">- Semua -</option>
                                @foreach($supplier as $item)
                                    <option value="{{ $item->supplier_id }}">{{ $item->supplier_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Supplier Barang</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Supplier</th>
                        <th>Barang</th>
                        <th>User</th>
                        <th>Tanggal</th>
                        <th>Stok</th>
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
                var tableStok;
                $(document).ready(function () {
                    tableStok = $('#table_stok').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            "url": "{{ url('stok/list') }}",
                            "dataType": "json",
                            "type": "POST",
                            "data": function (d) {
                                d.supplier_id = $('#supplier_id').val();
                            }
                        },
                        columns: [{
                            data: "DT_RowIndex",
                            className: "text-center",
                            width: "5%",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: "supplier_nama",
                            className: "text-center",
                            width: "20%",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: "barang_nama",
                            className: "text-center",
                            width: "20%",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: "user_nama",
                            className: "text-center",
                            width: "15%",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: "stok_tanggal",
                            className: "text-center",
                            width: "15%",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: "stok_jumlah",
                            className: "text-center",
                            width: "10%",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: "aksi",
                            className: "text-center",
                            width: "14%",
                            orderable: false,
                            searchable: false
                        }
                        ]
                    });
                    $('#table-stok_filter input').unbind().bind().on('keyup', function (e) {
                        if (e.keyCode == 13) { // enter key
                            tableStok.search(this.value).draw();
                        }
                    });

                    $('#supplier_id').on('change', function () {
                        tableStok.ajax.reload();
                    });
                });
            </script>
        @endpush