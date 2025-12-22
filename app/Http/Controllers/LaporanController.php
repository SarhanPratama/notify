<?php

namespace App\Http\Controllers;

use App\Models\mutasi;
use App\Models\ViewStok;
use App\Models\BahanBaku;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Transaksi;
use App\Models\KategoriKeuangan;
use App\Exports\StokExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\BukuBesarExport;
use App\Exports\KartuStokExport;
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
        $laporan_stok = ViewStok::all();

        return view('laporan.stok', compact('title', 'breadcrumbs', 'laporan_stok'));
    }
    public function exportExcel()
    {
        return Excel::download(new StokExport, 'Laporan-Stok.xlsx');
    }

    public function laporanKartuStok(Request $request)
    {
        $title = 'Laporan Kartu Stok Bahan Baku';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Laporan Kartu Stok', 'url' => null],
        ];

        $bahan_baku_list = BahanBaku::orderBy('nama')->get();
        $riwayat_mutasi = collect();
        $selected_item = null;

        if ($request->filled('id_bahan_baku')) {
            $selected_item = BahanBaku::findOrFail($request->id_bahan_baku);

            $riwayat_mutasi = Mutasi::where('id_bahan_baku', $request->id_bahan_baku)
                ->orderBy('created_at')
                ->get();
        }

        return view('laporan.kartu-stok', compact(
            'title',
            'breadcrumbs',
            'bahan_baku_list',
            'riwayat_mutasi',
            'selected_item'
        ));
    }
    public function exportKartuStok(Request $request)
    {
        $id_bahan_baku = $request->input('id_bahan_baku');
        $bahanBaku = BahanBaku::findOrFail($id_bahan_baku);

        return Excel::download(new KartuStokExport($id_bahan_baku), 'Kartu-Stok-' . $bahanBaku->nama . '.xlsx');
    }


    public function laporanRekapTransaksi(Request $request)
    {
        $title = 'Laporan Rekap Transaksi';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Laporan Rekap Transaksi', 'url' => null],
        ];

        $tanggal_awal = $request->input('tanggal_awal', now()->startOfMonth()->toDateString());
        $tanggal_akhir = $request->input('tanggal_akhir', now()->endOfMonth()->toDateString());
        $kategori_filter = $request->input('kategori', 'all');

        $query = Transaksi::with('kategoriKeuangan')
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->where('status', 1);

        if ($kategori_filter !== 'all') {
            $query->where('id_kategori_keuangan', $kategori_filter);
        }

        $transaksi = $query->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $categories = KategoriKeuangan::all();

        $totalPemasukan = $transaksi->filter(function ($t) {
            return $t->kategoriKeuangan && $t->kategoriKeuangan->jenis === 'pemasukan';
        })->sum('jumlah');

        $totalPengeluaran = $transaksi->filter(function ($t) {
            return $t->kategoriKeuangan && $t->kategoriKeuangan->jenis === 'pengeluaran';
        })->sum('jumlah');

        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        return view('laporan.rekap-transaksi', compact(
            'title',
            'breadcrumbs',
            'transaksi',
            'tanggal_awal',
            'tanggal_akhir',
            'kategori_filter',
            'categories',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir'
        ));
    }
    public function exportRekapTransaksi(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal', now()->startOfMonth()->toDateString());
        $tanggal_akhir = $request->input('tanggal_akhir', now()->endOfMonth()->toDateString());
        $kategori = $request->input('kategori', 'all');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\RekapTransaksiExport($tanggal_awal, $tanggal_akhir, $kategori),
            'Rekap-Transaksi-' . $tanggal_awal . '-to-' . $tanggal_akhir . '.xlsx'
        );
    }
}
