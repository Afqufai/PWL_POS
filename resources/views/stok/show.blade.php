@empty($stok)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" arialabel="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/stok') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ $page->title }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @empty($stok)
                    <div class="alert alert-danger alert-dismissible">
                        <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                        Data yang Anda cari tidak ditemukan.
                    </div>
                @else
                    <table class="table table-bordered table-striped table-hover table-sm">
                        <tr>
                            <th>ID</th>
                            <td>{{ $stok->stok_id }}</td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>{{ $stok->supplier->supplier_nama }}</td>
                        </tr>
                        <tr>
                            <th>Barang</th>
                            <td>{{ $stok->barang->barang_nama }}</td>
                        </tr>
                        <tr>
                            <th>User</th>
                            <td>{{ $stok->user->nama }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ date('d-m-Y', strtotime($stok->stok_tanggal)) }}</td>
                        </tr>
                        <tr>
                            <th>Stok</th>
                            <td>{{ $stok->stok_jumlah }}</td>
                        </tr>
                    </table>
                @endempty
                <button class="btn btn-primary" data-dismiss="modal" aria-label="Close" aria-hidden="true">
                    Kembali
                </button>
            </div>
        </div>
    </div>
@endempty