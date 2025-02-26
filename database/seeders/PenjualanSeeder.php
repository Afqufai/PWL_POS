<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['penjualan_id' => 1, 'user_id' => 1, 'pembeli' => 'Jono Kaswara', 'penjualan_kode' => 'PJ001', 'penjualan_tanggal' => '2024-09-12'],
            ['penjualan_id' => 2, 'user_id' => 2, 'pembeli' => 'Rian Hinata', 'penjualan_kode' => 'PJ002', 'penjualan_tanggal' => '2024-09-16'],
            ['penjualan_id' => 3, 'user_id' => 3, 'pembeli' => 'Ayaka Yukina', 'penjualan_kode' => 'PJ003', 'penjualan_tanggal' => '2024-09-15'],
            ['penjualan_id' => 4, 'user_id' => 3, 'pembeli' => 'Nur Panjaitan', 'penjualan_kode' => 'PJ004', 'penjualan_tanggal' => '2024-09-14'],
            ['penjualan_id' => 5, 'user_id' => 3, 'pembeli' => 'Raihan Fadilah', 'penjualan_kode' => 'PJ005', 'penjualan_tanggal' => '2024-09-13'],
            ['penjualan_id' => 6, 'user_id' => 3, 'pembeli' => 'Rahmawati Putri', 'penjualan_kode' => 'PJ006', 'penjualan_tanggal' => '2024-09-17'],
            ['penjualan_id' => 7, 'user_id' => 3, 'pembeli' => 'Rahmad Fadilah', 'penjualan_kode' => 'PJ007', 'penjualan_tanggal' => '2024-09-18'],
            ['penjualan_id' => 8, 'user_id' => 3, 'pembeli' => 'James Mann', 'penjualan_kode' => 'PJ008', 'penjualan_tanggal' => '2024-09-19'],
            ['penjualan_id' => 9, 'user_id' => 3, 'pembeli' => 'Anon Scott', 'penjualan_kode' => 'PJ009', 'penjualan_tanggal' => '2024-09-20'],
            ['penjualan_id' => 10, 'user_id' => 3, 'pembeli' => 'Tom Yard', 'penjualan_kode' => 'PJ010', 'penjualan_tanggal' => '2024-09-21'],
        ];
        DB::table('t_penjualan')->insert($data);
    }
}
