<?php

namespace App\Http\Controllers;

use App\Models\bahanBaku;
use App\Models\Pembelian;
use App\Exports\StokExport;
use App\Models\VSaldoAkhir;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{

    public function laporanStok()
    {
        $title = 'Laporan Stok Bahan Baku';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            // ['label' => 'Bahan Baku', 'url' => route('bahan-baku.index')],
            ['label' => 'Stok Bahan Baku', 'url' => null],
        ];
        $laporan_stok = VSaldoAkhir::all();

        return view('laporan.stok', compact('title', 'breadcrumbs', 'laporan_stok'));
    }

    public function exportPdf()
    {
        $title = 'Laporan Stok Bahan Baku';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Bahan Baku', 'url' => route('bahan-baku.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $laporan_stok = VSaldoAkhir::all();
        // dd($laporan_stok);
        $pdf = Pdf::loadView('laporan.stok-pdf', compact('title', 'breadcrumbs', 'laporan_stok'))->setPaper('A4', 'landscape');
        return $pdf->stream('laporan-stok.pdf');
    }

    // public function cetakPDF()
    // {
    //     $laporan = DB::table('mutasi')
    //         ->join('pembelian', 'mutasi.nobukti', '=', 'pembelian.nobukti')
    //         ->join('supplier', 'pembelian.id_supplier', '=', 'supplier.id')
    //         ->join('bahan_baku', 'mutasi.id_bahan_baku', '=', 'bahan_baku.id')
    //         ->join('satuan', 'bahan_baku.id_satuan', '=', 'satuan.id')
    //         ->where('mutasi.jenis_transaksi', 'M')
    //         ->where('mutasi.status', '1')
    //         ->select(
    //             'mutasi.created_at as tanggal',
    //             'mutasi.nobukti',
    //             'supplier.nama as supplier',
    //             'bahan_baku.nama as nama_barang',
    //             'mutasi.quantity',
    //             'satuan.nama as satuan',
    //             'mutasi.harga',
    //             'mutasi.sub_total'
    //         )
    //         ->get();

    //     $pdf = Pdf::loadView('laporan.pembelian-pdf', compact('laporan'))->setPaper('a4', 'landscape');
    //     return $pdf->download('laporan-pembelian.pdf');
    // }

    public function exportExcel()
    {
        return Excel::download(new StokExport, 'Laporan-Stok.xlsx');
    }
}
