@extends('layouts.template')
@section('content')

<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>0</h3>
                <p>Total Barang</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>0</h3>
                <p>Supplier Terdaftar</p>
            </div>
            <div class="icon">
                <i class="fas fa-truck"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>0</h3>
                <p>User Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>0</h3>
                <p>Transaksi Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Halo, Apakabar!</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        Selamat datang, ini adalah halaman utama aplikasi ini.
    </div>
</div>

@endsection