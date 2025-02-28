<?php

use App\Http\Controllers\Admin\DivisiController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\JenisCutiController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\NormaCutiController;
use App\Http\Controllers\Admin\PengajuanCutiController;
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
    Route::get('/beranda/getCount', [HomeController::class, 'countData'])->name('home.getCount');

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
        Route::get('/presensi/ringkasan', 'myAttendances')->name('presensi.myAttendances');
        Route::get('/presensi/get_data', 'getData')->name('presensi.getData');
        Route::get('/presensi/ringkasan/detail/{id}', 'show')->name('presensi.show');
    });

    Route::prefix('cuti')->group(function(){
        Route::controller(JenisCutiController::class)->group(function(){
            Route::get('/jenis_cuti', 'index')->name('jenis_cuti.index');
            Route::get('/jenis_cuti/get_data', 'getData')->name('jenis_cuti.getData');
            Route::get('/jenis_cuti/tambah', 'create')->name('jenis_cuti.create');
            Route::post('/jenis_cuti/tambah', 'store')->name('jenis_cuti.store');
            Route::get('/jenis_cuti/edit/{id}', 'edit')->name('jenis_cuti.edit');
            Route::patch('/jenis_cuti/edit/{id}', 'update')->name('jenis_cuti.update');
            Route::delete('/jenis_cuti/{id}', 'destroy')->name('jenis_cuti.destroy');
        });

        Route::controller(NormaCutiController::class)->group(function(){
            Route::get('/norma_cuti', 'index')->name('norma_cuti.index');
            Route::get('/norma_cuti/get_data', 'getData')->name('norma_cuti.getData');
            Route::get('/norma_cuti/tambah', 'create')->name('norma_cuti.create');
            Route::post('/norma_cuti/tambah', 'store')->name('norma_cuti.store');
        });

        Route::controller(PengajuanCutiController::class)->group(function(){
            Route::get('/pengajuan_cuti', 'index')->name('pengajuan_cuti.index');
            Route::get('/pengajuan_cuti/get_data', 'getData')->name('pengajuan_cuti.getData');
            Route::get('/pengajuan_cuti/tambah', 'create')->name('pengajuan_cuti.create');
            Route::post('/pengajuan_cuti/tambah', 'store')->name('pengajuan_cuti.store');
            Route::get('/pengajuan_cuti/detail/{id}', 'show')->name('pengajuan_cuti.show');
        });

    });

});
