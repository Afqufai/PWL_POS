<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\StokModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadCrumb = (object) [
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];
        $page = (object) [
            'title' => 'Daftar Penjualan yang ada'
        ];
        $activeMenu = 'penjualan';
        $user = UserModel::all();
        return view('penjualan.index', ['breadcrumb' => $breadCrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $penjualan = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with('user')
            ->with('detail.barang');

        if ($request->user_id) {
            $penjualan->where('user_id', $request->user_id);
        }

        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('user_nama', function ($penjualan) {
                return $penjualan->user->nama ?? '-';
            })
            ->addColumn('total_pembelian', function($p) {
                return number_format($p->detail->sum(fn($d)=>$d->harga),0,',','.');
            })              
            ->addColumn('aksi', function ($penjualan) {
                $btn = "<button onclick=\"modalAction('" . url("/penjualan/{$penjualan->penjualan_id}/") . "')\" class=\"btn btn-info btn-sm\">Detail</button> ";
                //$btn .= "<button onclick=\"modalAction('" . url("/penjualan/{$penjualan->penjualan_id}/edit") . "')\" class=\"btn btn-warning btn-sm\">Edit</button> ";
                $btn .= "<button onclick=\"modalAction('" . url("/penjualan/{$penjualan->penjualan_id}/delete") . "')\" class=\"btn btn-danger btn-sm\">Hapus</button> ";
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show(string $id)
    {
        $penjualan = PenjualanModel::with('user')->find($id);
        $detail = DetailPenjualanModel::with('barang')
            ->where('penjualan_id', $id)
            ->select('detail_id', 'penjualan_id', 'barang_id', 'harga', 'jumlah')
            ->get();

        $page = (object) [
            'title' => 'Detail Penjualan'
        ];
        return view('penjualan.show', ['page' => $page, 'penjualan' => $penjualan, 'detail' => $detail]);
    }

    public function create()
    {
        $barang = StokModel::with('barang')
            ->select('stok_id', 'barang_id', 'stok_jumlah')
            ->get();

        $detail = DetailPenjualanModel::with('barang')
            ->select('detail_id', 'penjualan_id', 'barang_id', 'harga', 'jumlah')
            ->get();

        return view('penjualan.create_ajax', [
            'barang' => $barang,
            'detail' => $detail,
        ]);
    }

    public function store(Request $request)
    {
        // tambahkan tanggal & user_id
        $request->merge([
            'penjualan_tanggal' => now()->format('Y-m-d H:i:s'),
            'user_id' => auth()->user()->user_id,
        ]);

        if (!$request->ajax() && !$request->wantsJson()) {
            return redirect('/');
        }

        // validasi header + detail
        $rules = [
            'user_id' => 'required|integer|exists:m_user,user_id',
            'pembeli' => 'required|string|max:300',
            'penjualan_kode' => 'required|string|max:300',
            'penjualan_tanggal' => 'required|date',

            // karena form pakai name="barang_id[]"/array
            'barang_id.*' => 'required|integer|exists:m_barang,barang_id',
            'barang_jumlah.*' => 'required|integer|min:1',
            'barang_harga.*' => 'required|numeric|min:0',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        DB::beginTransaction();
        try {
            $penjualan = PenjualanModel::create([
                'user_id' => $request->user_id,
                'pembeli' => $request->pembeli,
                'penjualan_kode' => $request->penjualan_kode,
                'penjualan_tanggal' => $request->penjualan_tanggal,
            ]);

            foreach ($request->barang_id as $i => $barangId) {
                $jumlah = $request->barang_jumlah[$i];
                $total  = $request->barang_harga[$i];
                $stok = StokModel::where('barang_id', $barangId)
                ->lockForUpdate()
                ->first();

                if (!$stok || $stok->stok_jumlah < $jumlah) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => false,
                        'message' => "Stok untuk barang ID {$barangId} tidak mencukupi (tersisa: {$stok->stok_jumlah}).",
                        'msgField'=> ["barang_jumlah.{$i}" => ["Maksimal {$stok->stok_jumlah}"]]
                    ]);
                }

                DetailPenjualanModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $barangId,
                    'harga' => $total,
                    'jumlah' => $jumlah,
                ]);
                $stok->decrement('stok_jumlah', $jumlah);
            }

            // commit transaksi
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan!'
            ]);

        } catch (\Throwable $e) { //Ambil Error
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.'
            ], 500);
        }
    }


    public function edit(string $id)
    {
        $penjualan = PenjualanModel::with(['detail.barang'])->findOrFail($id);
        $barang = StokModel::with('barang')
            ->where('stok_jumlah', '>', 0)
            ->get();
        return view('penjualan.edit_ajax', ['penjualan' => $penjualan, 'barang' => $barang]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if (!($request->ajax() || $request->wantsJson())) {
            return redirect('/penjualan');
        }

        // Merge tanggal & user_id jika perlu
        $request->merge([
            'penjualan_tanggal' => now()->format('Y-m-d H:i:s'),
            'user_id' => auth()->user()->user_id,
        ]);

        // Aturan validasi, termasuk array detail
        $rules = [
            'pembeli' => 'required|string|max:300',
            'penjualan_kode' => 'required|string|max:300',
            'detail_id.*' => 'nullable|integer|exists:t_penjualan_detail,detail_id',
            'barang_id.*' => 'required|integer|exists:m_barang,barang_id',
            'barang_jumlah.*' => 'required|integer|min:1',
            'barang_harga.*' => 'required|numeric|min:0',
        ];
        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $v->errors(),
            ]);
        }

        DB::beginTransaction();
        try {
            $penjualan = PenjualanModel::findOrFail($id);
            $penjualan->update([
                'pembeli' => $request->pembeli,
                'penjualan_kode' => $request->penjualan_kode,
                'penjualan_tanggal' => $request->penjualan_tanggal,
            ]);

            $existing = $penjualan->detail->keyBy('detail_id');

            $incomingDetailIds = $request->input('detail_id', []);
            foreach ($existing as $detId => $det) {
                if (!in_array($detId, $incomingDetailIds)) {
                    StokModel::where('barang_id', $det->barang_id)
                        ->increment('stok_jumlah', $det->jumlah);
                    $det->delete();
                }
            }

            foreach ($request->barang_id as $i => $barangId) {
                $jumlahBaru = $request->barang_jumlah[$i];
                $hargaBaru = $request->barang_harga[$i];
                $detailId = $request->detail_id[$i] ?? null;

                if ($detailId) {
                    $det = DetailPenjualanModel::findOrFail($detailId);
                    $delta = $jumlahBaru - $det->jumlah;

                    StokModel::where('barang_id', $barangId)
                        ->decrement('stok_jumlah', $delta);

                    $det->update([
                        'jumlah' => $jumlahBaru,
                        'harga' => $hargaBaru,
                    ]);
                } else {
                    DetailPenjualanModel::create([
                        'penjualan_id' => $penjualan->penjualan_id,
                        'barang_id' => $barangId,
                        'jumlah' => $jumlahBaru,
                        'harga' => $hargaBaru,
                    ]);
                    StokModel::where('barang_id', $barangId)
                        ->decrement('stok_jumlah', $jumlahBaru);
                }
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil diperbarui!'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data.'
            ], 500);
        }
    }

    public function confirm_ajax(string $id)
    {
        $penjualan = PenjualanModel::find($id);
        return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if (!$request->ajax()) {
            return;
        }

        $penjualan = PenjualanModel::with('detail')->find($id);
        if (!$penjualan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan!'
            ]);
        }

        DB::transaction(function () use ($penjualan) {
            // Hapus semua detail
            $penjualan->detail()->delete();

            // Hapus header penjualan
            $penjualan->delete();
        });

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    /* Fitur import disabled, dengan asumsi pelanggan selesai belanja dikasir.

        public function import()
        {
            return view('penjualan.import');
        }

        public function import_ajax(Request $request)
        {
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    // validasi file harus xls atau xlsx, max 1MB
                    'file_penjualan' => ['required', 'mimes:xlsx', 'max:1024']
                ];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi Gagal',
                        'msgField' => $validator->errors()
                    ]);
                }
                $file = $request->file('file_penjualan'); // ambil file dari request
                $reader = IOFactory::createReader('Xlsx'); // load reader file excel
                $reader->setReadDataOnly(true); // hanya membaca data
                $spreadsheet = $reader->load($file->getRealPath()); // load file excel
                $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
                $data = $sheet->toArray(null, false, true, true); // ambil data excel
                $insert = [];

                if (count($data) > 1) { // jika data lebih dari 1 baris
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                            $rawTanggal = $sheet->getCell('D' . $baris)->getValue(); // ini bisa angka serial
                            $excelDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rawTanggal);

                            $insert[] = [
                                'user_id' => $value['A'],
                                'pembeli' => $value['B'],
                                'penjualan_kode' => $value['C'],
                                'penjualan_tanggal' => $excelDate->format('Y-m-d'),
                                'created_at' => now(),
                            ];
                        }
                    }
                    if (count($insert) > 0) {
                        // insert data ke database, jika data sudah ada, maka diabaikan
                        PenjualanModel::insertOrIgnore($insert);
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada data yang diimport'
                    ]);
                }
            }
            return redirect('/');
        }*/

    public function export_excel()
    {
        // ambil semua penjualan beserta detail dan barang
        $penjualan = PenjualanModel::with(['user', 'detail.barang'])
            ->orderBy('penjualan_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 1) Buat header kolom
        $headers = [
            'No',
            'ID Penjualan',
            'Kasir',
            'Pembeli',
            'Kode',
            'Tanggal',
            'Nama Barang',
            'Qty',
            'Harga Satuan',
            'Subtotal'
        ];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        // 2) Isi data
        $row = 2;
        $no = 1;
        foreach ($penjualan as $p) {
            foreach ($p->detail as $d) {
                $sheet->setCellValue("A{$row}", $no++);
                $sheet->setCellValue("B{$row}", $p->penjualan_id);
                $sheet->setCellValue("C{$row}", $p->user->nama ?? '-');
                $sheet->setCellValue("D{$row}", $p->pembeli);
                $sheet->setCellValue("E{$row}", $p->penjualan_kode);
                $sheet->setCellValue("F{$row}", date('d-m-Y H:i:s', strtotime($p->penjualan_tanggal)));
                $sheet->setCellValue("G{$row}", $d->barang->barang_nama);
                $sheet->setCellValue("H{$row}", $d->jumlah);
                $sheet->setCellValue("I{$row}", $d->harga);
                $sheet->setCellValue("J{$row}", $d->jumlah * $d->harga);
                // center alignment contoh
                $sheet->getStyle("A{$row}:J{$row}")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $row++;
            }
        }

        // 3) Auto‐size kolom A–J
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 4) Kirim header & file
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Penjualan_Detail_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }


    public function export_pdf()
    {
        $penjualan = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with(['user', 'detail']) // <-- ini
            ->orderBy('penjualan_id')
            ->get();

        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data_Penjualan_' . date('Y-m-d H:i:s') . '.pdf');
    }
}
