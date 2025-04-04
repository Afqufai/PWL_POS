@extends('layouts.template')
@section('content')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
                <button onclick="modalAction('{{ url('/barang/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah
                    Ajax</button>
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
                    $("#modal-tambah").html("");
                    $.get(url, function (response) {
                        $("#modal-tambah").html(response);
                        $("#modal-tambah").modal("show");
                    });
                }
                $('#modal-tambah').on('hidden.bs.modal', function () {
                    $("#modal-tambah .modal-content").html("");
                });

                var dataBarang;
                $(document).ready(function () {
                    dataBarang = $('#table_barang').DataTable({
                        serverSide: true,
                        ajax: {
                            "url": "{{ url('barang/list') }}",
                            "dataType": "json",
                            "type": "POST",
                            "data": function (d) {
                                d.kategori_id = $('#kategori_id').val();
                                d._token = "{{ csrf_token() }}";
                            }
                        },
                        columns: [{
                            data: "DT_RowIndex",
                            className: "text-center",
                            orderable: false,
                            searchable: false
                        }, {
                            data: "kategori.kategori_nama",
                            className: "",
                            orderable: true,
                            searchable: true
                        }, {
                            data: "barang_kode",
                            className: "",
                            orderable: true,
                            searchable: true
                        }, {
                            data: "barang_nama",
                            className: "",
                            orderable: true,
                            searchable: true
                        }, {
                            data: "harga_beli",
                            className: "",
                            orderable: true,
                            searchable: true
                        }, {
                            data: "harga_jual",
                            className: "",
                            orderable: true,
                            searchable: true
                        }, {
                            data: "aksi",
                            className: "",
                            orderable: false,
                            searchable: false
                        }]
                    });
                    $('#kategori_id').on('change', function () {
                        dataBarang.ajax.reload();
                    })
                });
            </script>
        @endpush