<?php

use App\Http\Controllers\Admin\DivisiController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\RekamKehadiranController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;



Route::controller(AuthenticationController::class)->group(function(){
    Route::middleware(['guest'])->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/sign_in', 'userSignIn')->name('userSignIn');
        Route::post('/sign_in', 'authenticate')->name('authenticate');
    });
    Route::post('/sign_out', 'logout')->name('logout')->middleware('auth');
});


Route::group(['middleware' => ['auth']], function(){
    Route::get('/beranda', [HomeController::class, 'index'])->name('home');

    Route::controller(JabatanController::class)->group(function(){
        Route::get('/jabatan', 'index')->name('jabatan.index');
        Route::get('/jabatan/get_data', 'getData')->name('jabatan.getData');
        Route::get('/jabatan/tambah', 'create')->name('jabatan.create');
        Route::post('/jabatan/tambah', 'store')->name('jabatan.store');
        Route::get('/jabatan/edit/{id}', 'edit')->name('jabatan.edit');
        Route::patch('/jabatan/edit/{id}', 'update')->name('jabatan.update');
        Route::delete('/jabatan/hapus/{id}', 'destroy')->name('jabatan.destroy');
    });

    Route::controller(DivisiController::class)->group(function(){
        Route::get('/divisi', 'index')->name('divisi.index');
        Route::get('/divisi/get_data', 'getData')->name('divisi.getData');
        Route::get('/divisi/tambah', 'create')->name('divisi.create');
        Route::post('/divisi/tambah', 'store')->name('divisi.store');
        Route::get('/divisi/edit/{id}', 'edit')->name('divisi.edit');
        Route::patch('/divisi/edit/{id}', 'update')->name('divisi.update');
        Route::delete('/divisi/hapus/{id}', 'destroy')->name('divisi.destroy');
    });

    Route::controller(ShiftController::class)->group(function(){
        Route::get('/shifts', 'index')->name('shifts.index');
        Route::get('/shifts/get_data', 'getData')->name('shifts.getData');
        Route::get('/shifts/tambah', 'create')->name('shifts.create');
        Route::post('/shifts/tambah', 'store')->name('shifts.store');
        Route::get('/shifts/edit/{id}', 'edit')->name('shifts.edit');
        Route::patch('/shifts/edit/{id}', 'update')->name('shifts.update');
        Route::delete('/shifts/hapus/{id}', 'destroy')->name('shifts.destroy');
    });

    Route::controller(KaryawanController::class)->group(function(){
        Route::get('/karyawan', 'index')->name('karyawan.index');
        Route::get('/karyawan/get_data', 'getData')->name('karyawan.getData');
        Route::get('/karyawan/tambah', 'create')->name('karyawan.create');
        Route::post('/karyawan/cek-nik', 'checkNik')->name('karyawan.checkNik');
        Route::post('/karyawan/cek-telepon', 'checkPhoneNumber')->name('karyawan.checkPhoneNumber');
        Route::post('/karyawan/cek-email', 'checkEmail')->name('karyawan.checkEmail');
        Route::post('/karyawan/tambah', 'store')->name('karyawan.store');
        Route::get('/karyawan/detail/{id}', 'show')->name('karyawan.show');
        Route::patch('/karyawan/upload-foto/{id}', 'changeProfileImage')->name('karyawan.changeProfileImage');
        Route::get('/karyawan/edit/{id}', 'edit')->name('karyawan.edit');
        Route::patch('/karyawan/edit/{id}', 'update')->name('karyawan.update');
        Route::patch('/karyawan/ubah-password/{id}', 'changePassword')->name('karyawan.changePassword');
        Route::patch('/karyawan/ubah-status/{id}', 'changeStatus')->name('karyawan.changeStatus');
        Route::delete('/karyawan/hapus/{id}', 'destroy')->name('karyawan.destroy');
    });

    Route::controller(RekamKehadiranController::class)->group(function(){
        Route::get('/presensi/rekam', 'index')->name('presensi.index');
        Route::get('/presensi/get_data', 'getData')->name('presensi.getData');
        Route::get('/presensi/tambah', 'create')->name('presensi.create');
        Route::post('/presensi/tambah', 'store')->name('presensi.store');
        Route::get('/presensi/edit/{id}', 'edit')->name('presensi.edit');
        Route::patch('/presensi/edit/{id}', 'update')->name('presensi.update');
        Route::delete('/presensi/hapus/{id}', 'destroy')->name('presensi.destroy');
    });
});
