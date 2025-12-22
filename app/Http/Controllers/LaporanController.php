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

    // public function exportPdf()
    // {
    //     $title = 'Laporan Stok Bahan Baku';
    //     $breadcrumbs = [
    //         ['label' => 'Home', 'url' => route('admin.dashboard')],
    //         ['label' => 'Bahan Baku', 'url' => route('bahan-baku.index')],
    //         ['label' => 'Tabel Data', 'url' => null],
    //     ];

    //     $laporan_stok = ViewStok::all();
    //     // dd($laporan_stok);
    //     $pdf = Pdf::loadView('laporan.stok-pdf', compact('title', 'breadcrumbs', 'laporan_stok'))->setPaper('A4', 'landscape');
    //     return $pdf->stream('laporan-stok.pdf');
    // }

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

    public function exportKartuStok(Request $request)
    {
        $id_bahan_baku = $request->input('id_bahan_baku');
        $bahanBaku = BahanBaku::findOrFail($id_bahan_baku);

        return Excel::download(new KartuStokExport($id_bahan_baku), 'Kartu-Stok-' . $bahanBaku->nama . '.xlsx');
    }

    public function exportBukuBesar(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal', now()->startOfMonth()->toDateString());
        $tanggal_akhir = $request->input('tanggal_akhir', now()->endOfMonth()->toDateString());
        $tipe_transaksi = $request->input('tipe_transaksi', 'all');
        $search = $request->input('search', '');

        return Excel::download(new BukuBesarExport($tanggal_awal, $tanggal_akhir, $tipe_transaksi, $search), 'Buku-Besar.xlsx');
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

    // public function laporanRekapTransaksi(Request $request)
    // {
    //     $title = 'Laporan Rekap Transaksi';
    //     $breadcrumbs = [
    //         ['label' => 'Home', 'url' => route('admin.dashboard')],
    //         ['label' => 'Laporan Rekap Transaksi', 'url' => null],
    //     ];

    //     // 1. Ambil filter tanggal dari request
    //     $tanggal_awal = $request->input('tanggal_awal', now()->startOfMonth()->toDateString());
    //     $tanggal_akhir = $request->input('tanggal_akhir', now()->endOfMonth()->toDateString());

    //     // 2. Rekap Pembelian per Supplier
    //     $rekap_pembelian = Pembelian::with('supplier')
    //         ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
    //         ->selectRaw('id_supplier, COUNT(id) as jumlah_transaksi, SUM(total) as total_pembelian')
    //         ->groupBy('id_supplier')
    //         ->orderByDesc('total_pembelian')
    //         ->get()
    //         ->map(function ($item) {
    //             return [
    //                 'nama_supplier' => $item->supplier->nama ?? '-',
    //                 'jumlah_transaksi' => $item->jumlah_transaksi,
    //                 'total_pembelian' => $item->total_pembelian,
    //             ];
    //         });

    //     // dd($rekap_pembelian);

    //     // 3. Rekap Penjualan per Outlet
    //     $rekap_penjualan = Penjualan::with('outlet')
    //         ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
    //         ->selectRaw('id_outlet, COUNT(id) as jumlah_transaksi, SUM(total) as total_penjualan')
    //         ->groupBy('id_outlet')
    //         ->orderByDesc('total_penjualan')
    //         ->get()
    //         ->map(function ($item) {
    //             return [
    //                 'nama_outlet' => $item->outlet->nama ?? '-',
    //                 'jumlah_transaksi' => $item->jumlah_transaksi,
    //                 'total_penjualan' => $item->total_penjualan,
    //             ];
    //         });

    //     // 4. Kirim ke view
    //     return view('laporan.rekap-transaksi', compact(
    //         'title',
    //         'breadcrumbs',
    //         'rekap_pembelian',
    //         'rekap_penjualan',
    //         'tanggal_awal',
    //         'tanggal_akhir'
    //     ));
    // }

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

    public function laporanBukuBesar(Request $request)
    {
        $title = 'Laporan Buku Besar';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Laporan Buku Besar', 'url' => null],
        ];

        // 1. Filter Parameters
        $tanggal_awal = $request->input('tanggal_awal', now()->startOfMonth()->toDateString());
        $tanggal_akhir = $request->input('tanggal_akhir', now()->endOfMonth()->toDateString());
        $tipe_transaksi = $request->input('tipe_transaksi', 'all');
        $search = $request->input('search', '');

        // 2. Query Builder untuk Transaksi
        $query = Transaksi::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->where('status', 1); // hanya transaksi aktif

        // Apply filters
        if ($tipe_transaksi !== 'all') {
            $query->where('tipe', $tipe_transaksi);
        }

        if (!empty($search)) {
            $query->where('deskripsi', 'like', '%' . $search . '%');
        }

        // 3. Get Transaksi dengan Pagination dan Ordering
        $transaksi = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // 4. Summary Calculations
        $totalDebit = Transaksi::where('tipe', 'debit')
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->where('status', 1)
            ->sum('jumlah');

        $totalKredit = Transaksi::where('tipe', 'kredit')
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->where('status', 1)
            ->sum('jumlah');

        $saldoAkhir = $totalDebit - $totalKredit;
        $jumlahTransaksi = $transaksi->total();

        // 6. Running Balance Calculation untuk setiap transaksi
        foreach ($transaksi as $index => $trx) {
            if ($index == 0) {
                // Hitung saldo awal dari transaksi sebelum periode
                $saldoSebelumnya = Transaksi::where('tanggal', '<', $tanggal_awal)
                    ->where('status', 1)
                    ->sum(DB::raw('CASE WHEN tipe = "debit" THEN jumlah ELSE -jumlah END'));
            } else {
                $saldoSebelumnya = $transaksi[$index - 1]->running_balance ?? 0;
            }

            $trx->running_balance = $saldoSebelumnya + ($trx->tipe === 'debit' ? $trx->jumlah : -$trx->jumlah);
        }

        return view('laporan.buku-besar', compact(
            'title',
            'breadcrumbs',
            'transaksi',
            'tanggal_awal',
            'tanggal_akhir',
            'tipe_transaksi',
            'search',
            'totalDebit',
            'totalKredit',
            'saldoAkhir',
            'jumlahTransaksi'
        ));
    }
}
