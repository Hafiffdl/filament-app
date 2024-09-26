<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ReportController extends Controller
{
    public function printSuratKeluar($id)
{
    $suratKeluar = SuratKeluar::with('barangTransaksis.barangMaster')->findOrFail($id);

        $pdf = PDF::loadView('reports.surat-keluar', compact('suratKeluar'));

        return $pdf->stream('surat-keluar' . $suratKeluar->nomor . '.pdf');
}
}


// $suratKeluar = SuratKeluar::with(['faskes', 'barangTransaksis.barangMaster'])->findOrFail($id);
//     $pdf = PDF::loadView('reports.surat-keluar', compact('suratKeluar'));
//     return $pdf->download('surat_keluar.pdf');
