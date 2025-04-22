<?php

namespace App\Http\Controllers;

use App\Exports\StokExport;
use App\Models\bahanBaku;
use App\Models\VSaldoAkhir;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{


public function laporanStok()
{
    $title = 'Laporan Stok Bahan Baku';
    $breadcrumbs = [
        ['label' => 'Home', 'url' => route('admin.dashboard')],
        ['label' => 'Bahan Baku', 'url' => route('bahan-baku.index')],
        ['label' => 'Tabel Data', 'url' => null],
    ];
    $laporan_stok = VSaldoAkhir::all();

    return view('laporan.stok', compact('title', 'breadcrumbs','laporan_stok'));
}


public function exportPdf()
{
    $title = 'Laporan Stok Bahan Baku';
    $breadcrumbs = [
        ['label' => 'Home', 'url' => route('admin.dashboard')],
        ['label' => 'Bahan Baku', 'url' => route('bahan-baku.index')],
        ['label' => 'Tabel Data', 'url' => null],
    ];

    $laporan_stok = VSaldoAkhir::all(); // atau query sesuai kebutuhan
    // dd($laporan_stok);
    $pdf = Pdf::loadView('laporan.stok-pdf', compact('title', 'breadcrumbs','laporan_stok'));
    return $pdf->download('laporan-stok.pdf');
}

public function exportExcel()
{
    return Excel::download(new StokExport, 'Laporan-Stok.xlsx');
}

}
