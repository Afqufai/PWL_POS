@empty($penjualan)
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
                <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
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
                @empty($penjualan)
                    <div class="alert alert-danger alert-dismissible">
                        <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                        Data yang Anda cari tidak ditemukan.
                    </div>
                @else
                    <table class="table table-bordered table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>No ID</th>
                                <th>User</th>
                                <th>Pembeli</th>
                                <th>Kode Penjualan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $penjualan->penjualan_id }}</td>
                                <td>{{ $penjualan->user->nama }}</td>
                                <td>{{ $penjualan->pembeli }}</td>
                                <td>{{ $penjualan->penjualan_kode }}</td>
                                <td>{{ date('d-m-Y', strtotime($penjualan->created_at)) }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endempty

                <!-- DETAIL PENJUALAN -->
                @if ($penjualan->detail->isNotEmpty())
                    @php
                        $totalHarga = 0;
                    @endphp
                    <table class="table table-bordered table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penjualan->detail as $item)
                            @php
                                $totalHarga += $item->harga;
                            @endphp
                                <tr>
                                    <td>{{ $item->barang->barang_nama }}</td>
                                    <td>{{ number_format($item->barang->harga_jual, 0, ',', '.') }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ number_format($item->harga, 0, ',', '.') }}</td>                                  
                                </tr>
                            @endforeach
                            <tfoot>
                                <tr>
                                    <th colspan="3" style="text-align:right;">Total Harga:</th>
                                    <th>{{ number_format($totalHarga, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </tbody>
                    </table>
                @else
                <div class="alert alert-secondary alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Tidak ada detail penjualan...
                </div>
                @endif
                <button class="btn btn-primary" data-dismiss="modal" aria-label="Close" aria-hidden="true">
                    Kembali
                </button>
            </div>
        </div>
    </div>
@endempty