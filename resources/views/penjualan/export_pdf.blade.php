<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 4px 3px;
        }

        th {
            text-align: left;
        }

        .d-block {
            display: block;
        }

        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .p-1 {
            padding: 5px 1px 5px 1px;
        }

        .font-10 {
            font-size: 10pt;
        }

        .font-11 {
            font-size: 11pt;
        }

        .font-12 {
            font-size: 12pt;
        }

        .font-13 {
            font-size: 13pt;
        }

        .border-bottom-header {
            border-bottom: 1px solid;
        }

        .border-all,
        .border-all th,
        .border-all td {
            border: 1px solid;
        }
    </style>
</head>

<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center"><img src="{{ asset('polinema-bw.png') }}"></td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN
                    PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI
                    MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang
                    65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-
                    105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>
    <h3 class="text-center">LAPORAN DATA PENJUALAN</h4>
        <table class="border-all">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th class="text-center" width="25%">User</th>
                    <th class="text-center" width="25%">Pembeli</th>
                    <th class="text-center" width="25%">Kode Penjualan</th>
                    <th class="text-center" width="25%">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan as $b)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $b->user->nama }}</td>
                        <td class="text-center">{{ $b->pembeli }}</td>
                        <td class="text-center">{{ $b->penjualan_kode }}</td>
                        <td class="text-center">{{ date('d-m-Y', strtotime($b->penjualan_tanggal)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br><br><hr>
        <h3 class="text-center">LAPORAN DETAIL PENJUALAN</h3>
        <table class="border-all">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th class="text-center" width="20%">Kode Penjualan</th>
                    <th class="text-center" width="20%">Nama Barang</th>
                    <th class="text-center" width="10%">Qty</th>
                    <th class="text-center" width="20%">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($penjualan as $b)
                    @foreach ($b->detail as $d)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td class="text-center">{{ $b->penjualan_kode }}</td>
                            <td>{{ $d->barang->barang_nama }}</td>
                            <td class="text-center">{{ $d->jumlah }}</td>
                            <td class="text-right">Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Total Seluruh Penjualan:</strong></td>
                    <td class="text-right">
                        <strong>
                            Rp {{ number_format($penjualan->sum(fn($p) => $p->detail->sum(fn($d) => $d->harga)), 0, ',','.') }}
                        </strong>
                    </td>
                </tr>
            </tfoot>
        </table>

</body>

</html>