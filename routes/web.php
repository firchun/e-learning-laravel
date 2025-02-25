<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DosenMatkulController;
use App\Http\Controllers\MateriMatkulController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatBelajarController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\PageController::class, 'index']);
Route::get('/api/matkul/getall', [MatkulController::class, 'getall']);
Route::get('/matkul-datatable', [MatkulController::class, 'getMatkulDataTable']);

Auth::routes(['reset' => false]);
Route::middleware(['auth:web'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/matkul', [MatkulController::class, 'index'])->name('matkul');
    Route::get('/matkul/materi/{kode_matkul}', [MateriMatkulController::class, 'materi'])->name('matkul.materi');
    Route::get('/matkul/daftar-isi/{kode_matkul}', [MateriMatkulController::class, 'daftar_isi'])->name('matkul.daftar-isi');
    Route::get('/matkul/open-materi/{kode_matkul}', [MateriMatkulController::class, 'open_materi'])->name('matkul.open-materi');
    Route::post('/dosen-matkul/store', [DosenMatkulController::class, 'store']);
    Route::get('/dosen-matkul-datatable/{id}', [DosenMatkulController::class, 'getDosenMatkulDataTable']);
    Route::delete('/dosen-matkul/{id}/delete', [DosenMatkulController::class, 'destroy']);
    Route::get('/get-mahasiswa/{matkul_id}', [MatkulController::class, 'getMahasiswa']);
    //materi
    Route::get('/materi', [MateriMatkulController::class, 'index'])->name('materi.index');
    Route::post('/materi', [MateriMatkulController::class, 'store'])->name('materi.store');
    Route::post('/materi/store-riwayat', [MateriMatkulController::class, 'storeRiwayat'])->name('materi.store-riwayat');
    Route::delete('/materi/{id}', [MateriMatkulController::class, 'destroy'])->name('materi.destroy');
    //point
    Route::get('/point', [PointController::class, 'index'])->name('point');
    Route::post('/point', [PointController::class, 'store'])->name('point.store');
    Route::delete('/point/{id}', [PointController::class, 'destroy'])->name('point.destroy');
    //riwayat belajar
    Route::get('/riwayat-belajar', [RiwayatBelajarController::class, 'index'])->name('riwayat-belajar');
    Route::get('/riwayat-datatable', [RiwayatBelajarController::class, 'getRiwayatDataTable']);
    //akun managemen
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //customers managemen
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::post('/customers/store',  [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/edit/{id}',  [CustomerController::class, 'edit'])->name('customers.edit');
    Route::delete('/customers/delete/{id}',  [CustomerController::class, 'destroy'])->name('customers.delete');
    Route::get('/customers-datatable', [CustomerController::class, 'getCustomersDataTable']);
    //ujian
    Route::get('ujian/create/{id}', [UjianController::class, 'create'])->name('ujian.create');
    Route::post('ujian/store', [UjianController::class, 'store'])->name('ujian.store');
    Route::post('ujian/store-pertanyaan', [UjianController::class, 'store_pertanyaan'])->name('ujian.store-pertanyaan');
    Route::get('/cek-ujian/{matkulId}/{jenis}', [UjianController::class, 'cekKetersediaan'])->name('cek-ujian');
    Route::get('/ujian/{id}/pertanyaan', [UjianController::class, 'getPertanyaan'])->name('ujian.get-pertanyaan');
    Route::delete('/ujian/pertanyaan/{id}', [UjianController::class, 'deletePertanyaan'])->name('ujian.delete-pertanyaan');
    Route::get('/ujian', [UjianController::class, 'index'])->name('ujian');
    Route::get('/ujian/{id}', [UjianController::class, 'show'])->name('ujian.show');
    Route::post('/ujian/{id}/submit', [UjianController::class, 'submit'])->name('ujian.submit');
    Route::get('/ujian/{id}/hasil', [UjianController::class, 'hasilUjian'])->name('ujian.result');
    //tambah matakuliah mahasiswa
    Route::post('/api/matkul/store-matkul-mahasiswa', [MatkulController::class, 'storeMahasiswa']);
    Route::delete('/api/matkul/{id}/delete-mahasiswa', [MatkulController::class, 'destroyMahasiswa']);
});
Route::middleware(['auth:web', 'role:Admin,Dosen'])->group(function () {
    //matkul managemen

    //api 
    Route::post('/api/matkul/create', [MatkulController::class, 'store']);
    Route::put('/api/matkul/{id}/update', [MatkulController::class, 'update']);
    Route::delete('/api/matkul/{id}/delete', [MatkulController::class, 'destroy']);
    Route::get('/api/matkul/{id}/edit', [MatkulController::class, 'edit']);
    //user managemen
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/dosen', [UserController::class, 'dosen'])->name('dosen');
    Route::get('/mahasiswa', [UserController::class, 'mahasiswa'])->name('mahasiswa');
    Route::post('/users/store',  [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{id}',  [UserController::class, 'edit'])->name('users.edit');
    Route::delete('/users/delete/{id}',  [UserController::class, 'destroy'])->name('users.delete');
    Route::get('/users-datatable/{oler}', [UserController::class, 'getUsersDataTable']);
});
