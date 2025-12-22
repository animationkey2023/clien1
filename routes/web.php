<?php

use App\Http\Controllers\{
    DashboardController,
    DataSiswaController,
    LaporanSiswaController,
    LoginController,
    PenilaianSiswaController,
    RegisterController,
    UserController
};
use Illuminate\Support\Facades\Route;
use App\Exports\TemplateSiswaExport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| TEMPLATE SISWA (SATU SAJA)
|--------------------------------------------------------------------------
*/
Route::get('/template-siswa', function () {
    return Excel::download(
        new TemplateSiswaExport,
        'template_data_siswa.xlsx'
    );
})->name('template.siswa');

/*
|--------------------------------------------------------------------------
| GUEST (BELUM LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/', [DashboardController::class, 'landingPage']);

    // LOGIN
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.process');

    // REGISTER
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});


/*
|--------------------------------------------------------------------------
| AUTH (SUDAH LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::get('/data_siswa', [DataSiswaController::class, 'index'])->name('data_siswa');
        Route::post('/siswa_store', [DataSiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa_edit/{id}', [DataSiswaController::class, 'edit'])->name('siswa.edit');
        Route::post('/siswa_update/{id}', [DataSiswaController::class, 'update'])->name('siswa.update');
        Route::get('/siswa_delete/{id}', [DataSiswaController::class, 'destroy'])->name('siswa.delete');

        Route::resource('users', UserController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | PENILAIAN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,guru,guru_bk,kepsek')->group(function () {

    // tampil penilaian
    Route::get('/penilaian_siswa', [PenilaianSiswaController::class, 'index'])
        ->name('penilaian_siswa');

    // simpan penilaian
    Route::post('/penilaian_store', [PenilaianSiswaController::class, 'store'])
        ->name('penilaian.store');

    // form edit
    Route::get('/penilaian_edit/{id}', [PenilaianSiswaController::class, 'edit'])
        ->name('penilaian.edit');

    // update
    Route::put('/penilaian_update/{id}', [PenilaianSiswaController::class, 'update'])
        ->name('penilaian.update');

    // hapus
    Route::delete('/penilaian_delete/{id}', [PenilaianSiswaController::class, 'destroy'])
        ->name('penilaian.delete');
});

    /*
    |--------------------------------------------------------------------------
    | Data Siswa
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

    // tampil penilaian
    Route::get('/data_siswa', [DataSiswaController::class, 'index'])
        ->name('data_siswa');

    // simpan penilaian
    Route::post('/data_store', [DataSiswaController::class, 'store'])
        ->name('data.store');

    // form edit
    Route::get('/data_edit/{id}', [DataSiswaController::class, 'edit'])
        ->name('data.edit');

    // update
    Route::put('/data_update/{id}', [DataSiswaController::class, 'update'])
        ->name('data.update');

    // hapus
    Route::delete('/data_delete/{id}', [DataSiswaController::class, 'destroy'])
        ->name('data.delete');
});


    /*
    |--------------------------------------------------------------------------
    | LAPORAN
    |--------------------------------------------------------------------------
    */
    Route::get('/laporan_siswa', [LaporanSiswaController::class, 'index'])->name('laporan_siswa');
    Route::get('/laporan_pdf', [LaporanSiswaController::class, 'pdf'])->name('laporan_pdf');
    Route::get('/laporan_pdf_download', [LaporanSiswaController::class, 'pdfDownload'])
        ->name('laporan_pdf_download');
});

/*
|--------------------------------------------------------------------------
| IMPORT SISWA
|--------------------------------------------------------------------------
*/
Route::post('/data-siswa/import', [DataSiswaController::class, 'import'])
    ->middleware('auth')
    ->name('data-siswa.import');
