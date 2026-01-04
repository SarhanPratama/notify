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

    public function laporanStok(Request $request)
    {
        // $tes = request()->routeIs('laporan-stok');
        // dd($tes);
        $title = 'Laporan Stok Bahan Baku';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            // ['label' => 'Bahan Baku', 'url' => route('bahan-baku.index')],
            ['label' => 'Stok Bahan Baku', 'url' => null],
        ];

        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $laporan_stok = DB::table('bahan_baku')
            ->leftJoin('mutasi', function($join) use ($bulan, $tahun) {
                $join->on('bahan_baku.id', '=', 'mutasi.id_bahan_baku')
                     ->whereYear('mutasi.created_at', $tahun)
                     ->whereMonth('mutasi.created_at', $bulan);
            })
            ->leftJoin('satuan', 'bahan_baku.id_satuan', '=', 'satuan.id')
            ->select(
                'bahan_baku.id as id_bahan_baku',
                'bahan_baku.nama',
                'bahan_baku.stok_awal',
                DB::raw('COALESCE(SUM(CASE WHEN mutasi.jenis_transaksi = "M" THEN mutasi.quantity ELSE 0 END), 0) as total_masuk'),
                DB::raw('COALESCE(SUM(CASE WHEN mutasi.jenis_transaksi = "K" THEN mutasi.quantity ELSE 0 END), 0) as total_keluar'),
                DB::raw('bahan_baku.stok_awal + COALESCE(SUM(CASE WHEN mutasi.jenis_transaksi = "M" THEN mutasi.quantity ELSE 0 END), 0) - COALESCE(SUM(CASE WHEN mutasi.jenis_transaksi = "K" THEN mutasi.quantity ELSE 0 END), 0) as stok_akhir'),
                'satuan.nama as nama_satuan'
            )
            ->groupBy('bahan_baku.id', 'bahan_baku.nama', 'bahan_baku.stok_awal', 'satuan.nama')
            ->get();

        return view('laporan.stok', compact('title', 'breadcrumbs', 'laporan_stok', 'bulan', 'tahun'));
    }
    public function exportExcel(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        return Excel::download(new StokExport($bulan, $tahun), 'Laporan-Stok-' . $bulan . '-' . $tahun . '.xlsx');
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
