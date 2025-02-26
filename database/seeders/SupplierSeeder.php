<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['supplier_id' => 1, 'supplier_kode' => 'SP001', 'supplier_nama' => 'PT. Limbo Putra', 'supplier_alamat' => 'Jl. Raya No. 25'],
            ['supplier_id' => 2, 'supplier_kode' => 'SP002', 'supplier_nama' => 'PT. Bintang Jaya', 'supplier_alamat' => 'Jl. Merdeka No. 10'],
            ['supplier_id' => 3, 'supplier_kode' => 'SP003', 'supplier_nama' => 'PT. Cahaya Abadi', 'supplier_alamat' => 'Jl. Pahlawan No. 5'],
        ];
        DB::table('m_supplier')->insert($data);
    }
}
