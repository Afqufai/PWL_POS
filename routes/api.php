<?php

use App\Http\Controllers\Api\LevelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', \App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/register-again', \App\Http\Controllers\Api\RegisterController::class)->name('register-again');
Route::post('/login', \App\Http\Controllers\Api\LoginController::class)->name('login');
Route::post('/logout', \App\Http\Controllers\Api\LogoutController::class)->name('logout');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//ROUTE LEVEL
Route::get('levels', [LevelController::class, 'index']);
Route::post('levels', [LevelController::class, 'store']);
Route::get('levels/{level}', [LevelController::class, 'show']);
Route::put('levels/{level}', [LevelController::class, 'update']);
Route::delete('levels/{level}', [LevelController::class, 'destroy']);

//ROUTE USER
Route::get('users', [\App\Http\Controllers\Api\UserController::class, 'index']);
Route::post('users', [\App\Http\Controllers\Api\UserController::class, 'store']);
Route::get('users/{user}', [\App\Http\Controllers\Api\UserController::class, 'show']);
Route::put('users/{user}', [\App\Http\Controllers\Api\UserController::class, 'update']);
Route::delete('users/{user}', [\App\Http\Controllers\Api\UserController::class, 'destroy']);

//ROUTE BARANG
Route::get('stuff', [\App\Http\Controllers\Api\BarangController::class, 'index']);
Route::post('stuff', [\App\Http\Controllers\Api\BarangController::class, 'store']);
Route::get('stuff/{barang}', [\App\Http\Controllers\Api\BarangController::class, 'show']);
Route::put('stuff/{barang}', [\App\Http\Controllers\Api\BarangController::class, 'update']);
Route::delete('stuff/{barang}', [\App\Http\Controllers\Api\BarangController::class, 'destroy']);

//ROUTE KATEGORI
Route::get('categories', [\App\Http\Controllers\Api\KategoriController::class, 'index']);
Route::post('categories', [\App\Http\Controllers\Api\KategoriController::class, 'store']);
Route::get('categories/{kategori}', [\App\Http\Controllers\Api\KategoriController::class, 'show']);
Route::put('categories/{kategori}', [\App\Http\Controllers\Api\KategoriController::class, 'update']);
Route::delete('categories/{kategori}', [\App\Http\Controllers\Api\KategoriController::class, 'destroy']);