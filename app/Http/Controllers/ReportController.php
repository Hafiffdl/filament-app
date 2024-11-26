<?php
namespace App\Http\Controllers;

use App\Models\SuratBast;
use App\Models\SuratKeluar;
use App\Models\SuratRekon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ReportController extends Controller
{
    public function printSuratKeluar($id)
    {
        $suratKeluar = SuratKeluar::with(['faskes', 'barangTransaksis.items.barangMaster'])->findOrFail($id);
        $filename = 'surat-keluar-' . str_replace('/', '-', $suratKeluar->nomor) . '.pdf';
        $pdf = PDF::loadView('reports.surat-keluar', compact('suratKeluar'));
        return $pdf->stream($filename);
    }

    public function printSuratSerahTerima($id)
    {
        $suratSerahTerima = SuratBast::with(['faskes', 'barangTransaksis.items.barangMaster'])->findOrFail($id);
        $filename = 'surat-serah-terima-' . str_replace('/', '-', $suratSerahTerima->nomor) . '.pdf';
        $pdf = PDF::loadView('reports.surat-serah-terima', compact('suratSerahTerima'));
        return $pdf->stream($filename);
    }

    public function printSuratRekon($id)
    {
        $suratRekon = SuratRekon::with(['faskes', 'barangTransaksis.items.barangMaster'])->findOrFail($id);
        // Mengganti karakter '/' menjadi '-' atau karakter aman lainnya
        $nomor = str_replace(['/', '\\'], '-', $suratRekon->nomor); 
        $filename = 'surat-rekon-' . $nomor . '.pdf';
        $pdf = PDF::loadView('reports.surat-rekon', compact('suratRekon'));
        return $pdf->stream($filename);
    }
}
