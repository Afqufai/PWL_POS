<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
    public function index()
    {        
        $breadcrumb = (object)[
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];
        $page = (object)[
            'title' => 'Daftar Supplier Barang',
        ];
        $activeMenu = 'supplier';
        return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $supplier = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat')->get();
        return DataTables::of($supplier)
            ->addIndexColumn()
            ->addColumn('aksi', function($data){
                $btn = '<a href="' . url('/supplier/' . $data->supplier_id) . '" class="btn btn-info btn-sm">Detail</a>';
                $btn .= '<a href="' . url('/supplier/' . $data->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
                $btn .= '<form class="d-inline-block" method="POST" action="' .
                    url('/supplier/' . $data->supplier_id) . '">' .
                    csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Supplier',
            'list' => ['Home', 'Supplier', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Tambah Supplier Baru',
        ];
        $activeMenu = 'supplier';
        return view('supplier.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_kode' => 'required|string|min:5|max:5',
            'supplier_nama' => 'required|string|min:5',
            'supplier_alamat' => 'required'
        ]);

        SupplierModel::create([
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
            'supplier_alamat' => $request->supplier_alamat
        ]);

        return redirect('/supplier')->with('success', 'Data supplier berhasil ditambahkan');
    }

    public function show(string $id){
        $supplier = SupplierModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => ['Home', 'Supplier', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail Supplier',
        ];
        $activeMenu = 'supplier';
        return view('supplier.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id){
        $supplier = SupplierModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list' => ['Home', 'Supplier', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit Supplier',
        ];
        $activeMenu = 'supplier';
        return view ('supplier.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id){
        $request->validate([
            'supplier_kode' => 'required|string|min:5|max:5',
            'supplier_nama' => 'required|string|min:5',
            'supplier_alamat' => 'required'
        ]);

        SupplierModel::where('supplier_id', $id)->update([
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
            'supplier_alamat' => $request->supplier_alamat
        ]);

        return redirect('/supplier')->with('success', 'Data supplier berhasil diubah');
    }

    public function destroy(string $id){
        $check = SupplierModel::find($id);
        if(!$check){
            return redirect('/supplier')->with('error', 'Data level tidak ditemukan');
        }
        try {
            SupplierModel::destroy($id);
            return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/supplier')->with('error', 'Data supplier gagal dihapus');
        }
    }
}
