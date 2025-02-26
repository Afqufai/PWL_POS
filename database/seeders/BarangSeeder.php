<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            //Data PT 1
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'ATK001', 'barang_nama' => 'Pensil', 'harga_beli' => 4000, 'harga_jual' => 5000],
            ['barang_id' => 2, 'kategori_id' => 2, 'barang_kode' => 'ELK001', 'barang_nama' => 'Televisi', 'harga_beli' => 2000000, 'harga_jual' => 2500000],
            ['barang_id' => 3, 'kategori_id' => 3, 'barang_kode' => 'MKN001', 'barang_nama' => 'Mie Instan', 'harga_beli' => 2000, 'harga_jual' => 2500],
            ['barang_id' => 4, 'kategori_id' => 4, 'barang_kode' => 'MNM001', 'barang_nama' => 'Aqua', 'harga_beli' => 5000, 'harga_jual' => 6000],
            ['barang_id' => 5, 'kategori_id' => 5, 'barang_kode' => 'PRT001', 'barang_nama' => 'Panci', 'harga_beli' => 50000, 'harga_jual' => 60000],
            //Data PT 2
            ['barang_id' => 6, 'kategori_id' => 1, 'barang_kode' => 'ATK002', 'barang_nama' => 'Buku Tulis', 'harga_beli' => 5000, 'harga_jual' => 6000],
            ['barang_id' => 7, 'kategori_id' => 2, 'barang_kode' => 'ELK002', 'barang_nama' => 'Kulkas', 'harga_beli' => 3000000, 'harga_jual' => 3500000],
            ['barang_id' => 8, 'kategori_id' => 3, 'barang_kode' => 'MKN002', 'barang_nama' => 'Susu', 'harga_beli' => 10000, 'harga_jual' => 12000],
            ['barang_id' => 9, 'kategori_id' => 4, 'barang_kode' => 'MNM002', 'barang_nama' => 'Teh Botol', 'harga_beli' => 6000, 'harga_jual' => 7000],
            ['barang_id' => 10, 'kategori_id' => 5, 'barang_kode' => 'PRT002', 'barang_nama' => 'Pisau', 'harga_beli' => 20000, 'harga_jual' => 25000],
            //Data PT 3
            ['barang_id' => 11, 'kategori_id' => 1, 'barang_kode' => 'ATK003', 'barang_nama' => 'Penghapus', 'harga_beli' => 3000, 'harga_jual' => 4000],
            ['barang_id' => 12, 'kategori_id' => 2, 'barang_kode' => 'ELK003', 'barang_nama' => 'Kipas Angin', 'harga_beli' => 500000, 'harga_jual' => 600000],
            ['barang_id' => 13, 'kategori_id' => 3, 'barang_kode' => 'MKN003', 'barang_nama' => 'Kecap', 'harga_beli' => 5000, 'harga_jual' => 6000],
            ['barang_id' => 14, 'kategori_id' => 4, 'barang_kode' => 'MNM003', 'barang_nama' => 'Kopi', 'harga_beli' => 8000, 'harga_jual' => 9000],
            ['barang_id' => 15, 'kategori_id' => 5, 'barang_kode' => 'PRT003', 'barang_nama' => 'Garpu', 'harga_beli' => 10000, 'harga_jual' => 12000],
        ];
        DB::table('m_barang')->insert($data);
    }
}
