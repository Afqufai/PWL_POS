<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailPenjualanModel;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function show(DetailPenjualanModel $penjualan)
    {
        $penjualan = DetailPenjualanModel::with('barang')->find($penjualan);
        return response()->json($penjualan, 200);
    }
}
