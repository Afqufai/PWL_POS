@extends('layouts.template')
@section('content')

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <!-- LOG PENJUALAN TIDAK BISA DI-IMPORT SELAIN DATA DUMMY.
                <button onclick="modalAction('{{ url('/penjualan/import') }}')" class="btn btn-sm btn-info">Import
                    Penjualan</button>  --->
            <a href="{{ url('/penjualan/export_excel') }}" class="btn btn-sm btn-primary"><i
                    class="fa fa-fileexcel"></i> Export Penjualan</a>
            <a href="{{ url('/penjualan/export_pdf') }}" class="btn btn-sm btn-warning"><i class="fa fa-filepdf"></i>
                Export Penjualan (PDF)</a>
            <button onclick="modalAction('{{ url('/penjualan/create') }}')"
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
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="">- Semua -</option>
                            @foreach($user as $item)
                            <option value="{{ $item->user_id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">User</small>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>User Kasir</th>
                    <th>Pembeli</th>
                    <th>Kode Penjualan</th>
                    <th>Tanggal</th>
                    <th>Total Pembelian</th>
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

            var tablePenjualan;
            $(document).ready(function () {
                tablePenjualan = $('#table_penjualan').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        "url": "{{ url('penjualan/list') }}",
                        "dataType": "json",
                        "type": "POST",
                        "data": function (d) {
                            if ($('#user_id').length) {
                                d.user_id = $('#user_id').val();
                            } else {
                                console.error("Elemen dengan ID 'user_id' tidak ditemukan.");
                                d.user_id = null;
                            }
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '5%', orderable: false, searchable: false },
                        { data: 'user_nama', name: 'user_nama', width: '20%' },
                        { data: 'pembeli', name: 'pembeli', width: '20%' },
                        { data: 'penjualan_kode', name: 'penjualan_kode', width: '12%' },
                        { data: 'penjualan_tanggal', name: 'penjualan_tanggal' },
                        { data: 'total_pembelian', name: 'total_pembelian', width: '15%' },
                        { data: 'aksi', name: 'aksi', orderable: false, searchable: false, width: '15%' },
                    ]
                });

                $('#user_id').change(function () {
                    tablePenjualan.ajax.reload();
                });
            });
        </script>
        @endpush