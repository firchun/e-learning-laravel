<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DosenMatkulController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\ProfileController;
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
Route::post('/dosen-matkul/store', [DosenMatkulController::class, 'store']);
Route::get('/dosen-matkul-datatable/{id}', [DosenMatkulController::class, 'getDosenMatkulDataTable']);
Route::delete('/dosen-matkul/{id}/delete', [DosenMatkulController::class, 'destroy']);
Route::get('/matkul', [MatkulController::class, 'index'])->name('matkul');
Route::get('/matkul/materi/{kode_matkul}', [MatkulController::class, 'materi'])->name('matkul.materi');
Route::get('/matkul-datatable', [MatkulController::class, 'getMatkulDataTable']);

Auth::routes(['reset' => false]);
Route::middleware(['auth:web'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //akun managemen
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //customers managemen
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::post('/customers/store',  [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/edit/{id}',  [CustomerController::class, 'edit'])->name('customers.edit');
    Route::delete('/customers/delete/{id}',  [CustomerController::class, 'destroy'])->name('customers.delete');
    Route::get('/customers-datatable', [CustomerController::class, 'getCustomersDataTable']);
});
Route::middleware(['auth:web', 'role:Admin'])->group(function () {
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