<?php

use App\Http\Controllers\BarangTransaksiController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin/login');
});

Route::get('/admin/reports/surat-keluar/{id}', [ReportController::class, 'printSuratKeluar'])->name('print.surat-keluar');
Route::get('/admin/reports/surat-serah-terima/{id}', [ReportController::class, 'printSuratSerahTerima'])->name('print.surat-serah-terima');
