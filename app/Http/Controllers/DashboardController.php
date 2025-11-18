<?php

namespace App\Http\Controllers;

use App\Charts\ArusKasChart;
use App\Charts\SaldoKasChart;
use Carbon\Carbon;
use App\Models\Cabang;
use App\Models\mutasi;
use App\Models\products;
use App\Models\Supplier;
use App\Models\bahanBaku;
use App\Models\cash_flow;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Piutang;
use App\Models\ViewStok;
use App\Models\Transaksi;
use App\Models\SumberDana;
use App\Models\VSaldoAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:gudang')->only('adminGudang');
        $this->middleware('role:keuangan')->only('manajerKeuangan');
        $this->middleware('role:kasir')->only('kasirOutlet');
        $this->middleware('role:owner')->only('owner');
    }

    public function adminGudang()
    {
        $title = 'Dashboard';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Dashboard', 'url' => null],
        ];
        // Data Ringkasan Stok
        $totalBahanBaku = BahanBaku::count();
        $bahanBakuMinimumCount = ViewStok::whereHas('bahanBaku', function ($query) {
            $query->whereColumn('vsaldoakhir2.saldoakhir', '<=', 'bahan_baku.stok_minimum');
        })->count();
        // dd($bahanBakuMinimum);
        // $bahanBakuMinimum = BahanBaku::whereColumn('stok_akhir', '<=', 'stok_minimum')->get();
        // $bahanBakuMinimumCount = $bahanBakuMinimum->count();

        // Grafik Tren Stok 30 Hari Terakhir
        $stokTrend = Mutasi::selectRaw('DATE(created_at) as date, SUM(CASE WHEN jenis_transaksi = "masuk" THEN quantity ELSE -quantity END) as perubahan')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Pembelian Terakhir
        $pembelianTerakhir = Pembelian::with('supplier')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        $totalPengeluaranBulanIni = Pembelian::whereMonth('tanggal', Carbon::now()->month)
            ->sum('total');

        $totalPendapatanBulanIni = Penjualan::whereMonth('tanggal', Carbon::now()->month)
            ->sum('total');
        // dd($totalPengeluaranBulanIni);
        // $totalPengeluaranBulanLalu = Pembelian::whereMonth('tanggal', Carbon::now()->subMonth()->month)
        //     ->sum('total');
        // $persentasePembelian = $totalPengeluaranBulanLalu > 0 ?
        //     (($totalPengeluaranBulanIni - $totalPengeluaranBulanLalu) / $totalPengeluaranBulanLalu) * 100 : 0;
        // dd($persentasePembelian);


        // Distribusi ke Outlet
        $distribusiHariIni = Mutasi::with(['bahanBaku', 'penjualan.cabang'])
            ->where('jenis_transaksi', 'K')
            ->whereHas('penjualan', function ($query) {
                $query->whereDate('tanggal', Carbon::today());
            })
            ->get();
        // dd($distribusiHariIni);

        $outletPermintaan = DB::table('mutasi')
            ->join('penjualan', 'mutasi.nobukti', '=', 'penjualan.nobukti')
            ->join('cabang', 'cabang.id', '=', 'penjualan.id_cabang')
            ->select('penjualan.id_cabang', 'cabang.nama as cabang', DB::raw('SUM(mutasi.quantity) as total_permintaan'))
            ->whereMonth('mutasi.created_at', Carbon::now()->month)
            ->groupBy('penjualan.id_cabang')
            ->orderByDesc('total_permintaan')
            ->get();

        // dd($outletPermintaan);
        // Alert Restock
        $bahanPerluRestock = DB::table('bahan_baku')
            ->leftJoin('vsaldoakhir2', 'vsaldoakhir2.id', '=', 'bahan_baku.id')
            ->select('bahan_baku.*', 'vsaldoakhir2.saldoakhir as stok_akhir')
            ->whereColumn('vsaldoakhir2.saldoakhir', '<=', 'bahan_baku.stok_minimum')
            ->get();

        // $bahanPerluRestock = BahanBaku::join('vsaldoakhir2', 'vsaldoakhir2.id', '=', 'bahan_baku.id')
        //     ->whereColumn('vsaldoakhir2.saldoakhir', '<', 'bahan_baku.stok_minimum')
        //     // ->with(['supplier' => function ($q) {
        //     //     $q->orderBy('harga', 'asc');
        //     // }])
        //     ->get();
        // dd($bahanPerluRestock);
        return view('dashboard.gudang', compact(
            'totalBahanBaku',
            'title',
            'breadcrumbs',
            // 'bahanBakuMinimum',
            'bahanBakuMinimumCount',
            'stokTrend',
            'pembelianTerakhir',
            'totalPengeluaranBulanIni',
            'totalPendapatanBulanIni',
            // 'persentasePembelian',
            'distribusiHariIni',
            'outletPermintaan',
            'bahanPerluRestock'
        ));
    }

    // public function manajerKeuangan()
    // {
    //     // Ringkasan Keuangan
    //     $saldoSumberDana = SumberDana::withSum(['cashFlows as saldo' => function($query) {
    //         $query->selectRaw('SUM(CASE WHEN jenis_transaksi = "debit" THEN nominal ELSE -nominal END)');
    //     }], 'nominal')->get();

    //     $totalPendapatanBulanIni = Penjualan::whereMonth('tanggal', Carbon::now()->month)
    //                                        ->sum('total');
    //     $totalPengeluaranBulanIni = Pembelian::whereMonth('tanggal', Carbon::now()->month)
    //                                         ->sum('total');

    //     // Cash Flow
    //     $cashFlow30Hari = CashFlow::selectRaw('DATE(tanggal) as date,
    //                                         SUM(CASE WHEN jenis_transaksi = "debit" THEN nominal ELSE 0 END) as debit,
    //                                         SUM(CASE WHEN jenis_transaksi = "kredit" THEN nominal ELSE 0 END) as kredit')
    //                              ->where('tanggal', '>=', Carbon::now()->subDays(30))
    //                              ->groupBy('date')
    //                              ->orderBy('date')
    //                              ->get();

    //     $topPengeluaran = CashFlow::where('jenis_transaksi', 'kredit')
    //                              ->orderBy('nominal', 'desc')
    //                              ->take(5)
    //                              ->get();

    //     // Piutang & Hutang
    //     $piutang = Penjualan::where('status_pembayaran', '!=', 'lunas')
    //                         ->sum('total');

    //     $hutang = Pembelian::where('status_pembayaran', '!=', 'lunas')
    //                       ->sum('total');

    //     // Laporan Laba/Rugi
    //     $laporanBulanan = Penjualan::selectRaw('MONTH(tanggal) as bulan,
    //                                   YEAR(tanggal) as tahun,
    //                                   SUM(total) as pendapatan')
    //                               ->groupBy('bulan', 'tahun')
    //                               ->orderBy('tahun')
    //                               ->orderBy('bulan')
    //                               ->take(12)
    //                               ->get();

    //     $biayaBulanan = Pembelian::selectRaw('MONTH(tanggal) as bulan,
    //                                 YEAR(tanggal) as tahun,
    //                                 SUM(total) as pengeluaran')
    //                             ->groupBy('bulan', 'tahun')
    //                             ->orderBy('tahun')
    //                             ->orderBy('bulan')
    //                             ->take(12)
    //                             ->get();

    //     return view('dashboard.manajer-keuangan', compact(
    //         'saldoSumberDana',
    //         'totalPendapatanBulanIni',
    //         'totalPengeluaranBulanIni',
    //         'cashFlow30Hari',
    //         'topPengeluaran',
    //         'piutang',
    //         'hutang',
    //         'laporanBulanan',
    //         'biayaBulanan'
    //     ));
    // }

    public function kasirOutlet(Request $request)
    {
        $outletId = $request->user()->cabang_id;

        // Penjualan Hari Ini
        $penjualanHariIni = Penjualan::where('id_cabang', $outletId)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        $totalPenjualanHariIni = $penjualanHariIni ? $penjualanHariIni->total : 0;
        $jumlahProdukTerjual = $penjualanHariIni ? $penjualanHariIni->detailPenjualan->sum('quantity') : 0;

        // Produk Terlaris
        $produkTerlaris = products::withCount(['detailPenjualan as total_terjual' => function ($query) use ($outletId) {
            $query->whereHas('penjualan', function ($q) use ($outletId) {
                $q->where('id_cabang', $outletId);
            });
        }])
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->get();

        // Stok Bahan Baku Outlet
        $stokOutlet = Mutasi::where('id_cabang', $outletId)
            ->where('jenis_transaksi', 'keluar')
            ->with('bahanBaku')
            ->get()
            ->groupBy('id_bahan_baku')
            ->map(function ($items) {
                return $items->sum('quantity');
            });

        // Riwayat Transaksi
        $riwayatTransaksi = Penjualan::where('id_cabang', $outletId)
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.kasir-outlet', compact(
            'totalPenjualanHariIni',
            'jumlahProdukTerjual',
            'produkTerlaris',
            'stokOutlet',
            'riwayatTransaksi'
        ));
    }

    public function owner(SaldoKasChart $SaldoKasChart, ArusKasChart $ArusKasChart)
    {
        $title = 'Dashboard';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Dashboard', 'url' => null],
        ];

        // Pemasukan Bulan Ini: SUM(jumlah) dari transaksi tipe='pemasukan' bulan berjalan
        $pemasukanBulanIni = Transaksi::where('tipe', 'debit')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');

        // Pengeluaran Bulan Ini: SUM(jumlah) dari transaksi tipe='pengeluaran' bulan berjalan
        $pengeluaranBulanIni = Transaksi::where('tipe', 'kredit')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');

        // Total Piutang Beredar: SUM(jumlah_piutang) dari piutang status != 'lunas'
        $totalPiutangBeredar = Piutang::where('status', '!=', 'lunas')
            ->sum('jumlah_piutang');

        // Item Stok Kritis: COUNT(*) dari view_stok stok_akhir <= stok_minimum
        $itemStokKritis = ViewStok::join('bahan_baku', 'bahan_baku.id', '=', 'view_stok.id_bahan_baku')
            ->whereColumn('view_stok.stok_akhir', '<=', 'bahan_baku.stok_minimum')
            ->count();

        // Query: SELECT nama, stok_akhir, nama_satuan FROM view_stok WHERE stok_akhir <= stok_minimum ORDER BY stok_akhir ASC LIMIT 5
        $barangSegeraHabis = DB::table('view_stok')
            ->join('bahan_baku', 'bahan_baku.nama', '=', 'view_stok.nama')
            ->select('view_stok.nama', 'view_stok.stok_akhir', 'view_stok.nama_satuan', 'bahan_baku.stok_minimum')
            ->whereColumn('view_stok.stok_akhir', '<=', 'bahan_baku.stok_minimum')
            ->orderBy('view_stok.stok_akhir', 'ASC')
            ->limit(5)
            ->get();

        // Query: SELECT dari piutang JOIN penjualan JOIN cabang WHERE status != 'lunas' AND jatuh_tempo <= NOW() ORDER BY jatuh_tempo ASC LIMIT 5
        $piutangJatuhTempo = DB::table('piutang')
            ->join('penjualan', 'piutang.nobukti', '=', 'penjualan.nobukti')
            ->join('outlet', 'penjualan.id_outlet', '=', 'outlet.id')
            ->select('outlet.nama as nama_mitra', 'piutang.jumlah_piutang', 'piutang.jatuh_tempo',
                    DB::raw('DATEDIFF(NOW(), piutang.jatuh_tempo) as hari_telat'))
            ->where('piutang.status', '!=', 'lunas')
            ->where('piutang.jatuh_tempo', '<=', Carbon::now())
            ->orderBy('piutang.jatuh_tempo', 'ASC')
            ->limit(5)
            ->get();

        // Query: SUM(jumlah) dari transaksi WHERE tipe = 'debit' AND tanggal = HARI_INI
        $pemasukanHariIni = Transaksi::where('tipe', 'debit')
            ->whereDate('tanggal', Carbon::today())
            ->sum('jumlah');

        // Query: SUM(jumlah) dari transaksi WHERE tipe = 'kredit' AND tanggal = HARI_INI
        $pengeluaranHariIni = Transaksi::where('tipe', 'kredit')
            ->whereDate('tanggal', Carbon::today())
            ->sum('jumlah');

        // Query: COUNT(id) dari penjualan + pembelian WHERE tanggal = HARI_INI
        $penjualanHariIni = Penjualan::whereDate('tanggal', Carbon::today())
            ->count();

        $pembelianHariIni = Pembelian::whereDate('tanggal', Carbon::today())
            ->count();

        $transaksiBaruHariIni = $penjualanHariIni + $pembelianHariIni;

        $SaldoKasChart = $SaldoKasChart->build();
        $ArusKasChart = $ArusKasChart->build();

        return view('dashboard.owner', compact(
            'title',
            'breadcrumbs',
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'totalPiutangBeredar',
            'itemStokKritis',
            'barangSegeraHabis',
            'piutangJatuhTempo',
            'pemasukanHariIni',
            'pengeluaranHariIni',
            'transaksiBaruHariIni',
            'SaldoKasChart',
            'ArusKasChart'
        ));
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('gudang')) {
            return redirect()->route('dashboard.admin-gudang');
        } elseif ($user->hasRole('manajer-keuangan')) {
            return redirect()->route('dashboard.manajer-keuangan');
        } elseif ($user->hasRole('kasir')) {
            return redirect()->route('dashboard.kasir-outlet');
        } elseif ($user->hasRole('owner')) {
            return redirect()->route('dashboard.owner');
        }

        abort(403, 'Role tidak dikenali');
    }

    // private function prepareStockChartData()
    // {
    //     // Gunakan query yang lebih efisien
    //     $stockHistory = VSaldoAkhir::join('bahan_baku', 'vsaldoakhir2.id', '=', 'bahan_baku.id')
    //     ->selectRaw('DATE(bahan_baku.updated_at) as date, SUM(vsaldoakhir2.saldoakhir) as total')
    //     ->where('bahan_baku.updated_at', '>=', now()->subDays(30))
    //     ->groupBy(DB::raw('DATE(bahan_baku.updated_at)'))
    //     ->orderBy('date')
    //     ->get();
    //     // dd($stockHistory);

    //     $dates = collect();
    //     $stokData = collect();

    //     // Isi data untuk 30 hari terakhir
    //     for ($i = 29; $i >= 0; $i--) {
    //         $currentDate = now()->subDays($i)->format('Y-m-d');
    //         $dates->push(now()->subDays($i)->format('d M'));

    //         // Cari data untuk tanggal ini
    //         $record = $stockHistory->firstWhere('date', $currentDate);
    //         $stokData->push($record ? $record->total : 0);
    //     }

    //     return [
    //         'labels' => $dates,
    //         'datasets' => [
    //             [
    //                 'label' => 'Stok Gudang',
    //                 'data' => $stokData,
    //                 'backgroundColor' => 'rgba(78, 115, 223, 0.05)',
    //                 'borderColor' => '#4e73df',
    //                 'pointBackgroundColor' => '#4e73df',
    //                 'pointBorderColor' => '#fff',
    //                 'pointHoverRadius' => 5,
    //                 'pointHoverBackgroundColor' => '#4e73df',
    //                 'pointHoverBorderColor' => '#fff',
    //                 'pointHitRadius' => 10,
    //                 'pointBorderWidth' => 2,
    //                 'borderWidth' => 2,
    //                 'fill' => true
    //             ]
    //         ]
    //     ];
    // }

    // public function index()
    // {
    //     $title = 'Dashboard';
    //     $breadcrumbs = [
    //         ['label' => 'Home', 'url' => route('admin.dashboard')],
    //         ['label' => 'Dashboard', 'url' => null],
    //     ];
    //     $user = auth()->user();
    //     $stats = [];
    //     $charts = [];

    //     // COMMON STATS
    //     $stats['current_date'] = now()->isoFormat('dddd, D MMMM Y');

    //     // ADMIN GUDANG STATS
    //     if ($user->hasAnyRole(['gudang', 'owner'])) {
    //         $stats = [
    //             'total_bahan_baku' => BahanBaku::count(),
    //             'bahan_kritis' => VSaldoAkhir::whereHas('bahanBaku', function($query) {
    //                 $query->whereColumn('vsaldoakhir2.saldoakhir', '<=', 'bahan_baku.stok_minimum');
    //             })->count(),
    //             'pembelian_bulan_ini' => Pembelian::whereMonth('tanggal', now()->month)->sum('total'),
    //             'penjualan_bulan_ini' => Penjualan::whereMonth('tanggal', now()->month)->sum('total'),
    //             'distribusi_hari_ini' => Penjualan::whereDate('tanggal', today())->count(),
    //             // 'supplier_aktif' => Supplier::has('pembelians', '>', 0)->count(),
    //         ];

    //         // Data Bahan Baku Kritis
    //         $bahanBakuKritis = bahanBaku::leftJoin('vsaldoakhir2', 'vsaldoakhir2.id', '=', 'bahan_baku.id')
    //         ->leftJoin('satuan', 'bahan_baku.id_satuan', '=', 'satuan.id')
    //         ->whereColumn('vsaldoakhir2.saldoakhir', '<=', 'bahan_baku.stok_minimum')
    //         ->orderBy('vsaldoakhir2.saldoakhir')
    //         ->limit(5)
    //         ->get([
    //             'bahan_baku.*',
    //             'vsaldoakhir2.saldoakhir',
    //             'satuan.nama as satuan_nama',
    //         ]);

    //         // Riwayat Pembelian Terakhir
    //         $riwayatPembelian = Pembelian::with(['supplier', 'mutasi.bahanBaku'])
    //             ->latest()
    //             ->limit(5)
    //             ->get();

    //         $chartData = $this->prepareStockChartData();

    //         return view('dashboard.index', compact('title', 'breadcrumbs','stats', 'bahanBakuKritis', 'riwayatPembelian', 'chartData'));
    //     }


    //     return view('dashboard.index', compact('title', 'breadcrumbs', 'stats', 'charts'));
    // }
    // public function getPengeluaran()
    // {
    //     $now = Carbon::now();

    //     $pengeluaranPembelian = Pembelian::whereMonth('tanggal', $now->month)
    //         ->whereYear('tanggal', $now->year)
    //         ->sum('total');
    //     // dd($pengeluaranPembelian);
    //     $pengeluaranCashFlow = cash_flow::where('jenis_transaksi', 'kredit')
    //         ->whereMonth('tanggal', $now->month)
    //         ->whereYear('tanggal', $now->year)
    //         ->sum('nominal');

    //     $totalPengeluaran = $pengeluaranCashFlow + $pengeluaranPembelian;
    //     return [
    //         'pengeluaranCashFlow' => $pengeluaranCashFlow,
    //         'pengeluaranPembelian' => $pengeluaranPembelian,
    //         'totalPengeluaran' => $totalPengeluaran
    //     ];
    // }

    // public function getPemasukkan()
    // {
    //     $now = Carbon::now();

    //     $pemasukanPenjualan = Penjualan::whereMonth('tanggal', $now->month)
    //         ->whereYear('tanggal', $now->year)
    //         ->sum('total');

    //     $pemasukanCashFlow = cash_flow::where('jenis_transaksi', 'debit')
    //         ->whereMonth('tanggal', $now->month)
    //         ->whereYear('tanggal', $now->year)
    //         ->sum('nominal');

    //     $totalPemasukkan = $pemasukanPenjualan + $pemasukanCashFlow;

    //     return [
    //         'pemasukanCashFlow' => $pemasukanCashFlow,
    //         'pemasukanPenjualan' => $pemasukanPenjualan,
    //         'totalPemasukkan' => $totalPemasukkan
    //     ];
    // }

    // function getKasSeroo()
    // {
    //     $now = Carbon::now();
    //     $lastMonth = $now->copy()->subMonth();

    //     // ----- Saldo Kas Seroo Bulan Ini -----
    //     $totalPenjualanBulanIni = $this->getPemasukkan();
    //     $totalPembelianBulanIni = $this->getPengeluaran();
    //     // $totalPenjualanBulanIni = mutasi::where('jenis_transaksi', 'K')
    //     //     ->whereMonth('created_at', $now->month)
    //     //     ->whereYear('created_at', $now->year)
    //     //     ->get()
    //     //     ->sum(function ($item) {
    //     //         return $item->harga * $item->quantity;
    //     //     });

    //     // $totalPembelianBulanIni = mutasi::where('jenis_transaksi', 'M')
    //     //     ->whereMonth('created_at', $now->month)
    //     //     ->whereYear('created_at', $now->year)
    //     //     ->get()
    //     //     ->sum(function ($item) {
    //     //         return $item->harga * $item->quantity;
    //     //     });

    //     // $kasSerooDebitBulanIni = cash_flow::where('sumber_dana', 'kas seroo')
    //     //     ->where('jenis_transaksi', 'debit')
    //     //     ->whereMonth('created_at', $now->month)
    //     //     ->whereYear('created_at', $now->year)
    //     //     ->sum('nominal');

    //     // $kasSerooKreditBulanIni = cash_flow::where('sumber_dana', 'kas seroo')
    //     //     ->where('jenis_transaksi', 'kredit')
    //     //     ->whereMonth('created_at', $now->month)
    //     //     ->whereYear('created_at', $now->year)
    //     //     ->sum('nominal');

    //     $kasSerooBulanIni = ($totalPenjualanBulanIni['pemasukanPenjualan'] - $totalPembelianBulanIni['pengeluaranPembelian']) + ($totalPenjualanBulanIni['pemasukanCashFlow'] - $totalPembelianBulanIni['pengeluaranCashFlow']);

    //     // $totalPenjualanBulanLalu = mutasi::where('jenis_transaksi', 'K')
    //     //     ->whereMonth('created_at', $lastMonth->month)
    //     //     ->whereYear('created_at', $lastMonth->year)
    //     //     ->get()
    //     //     ->sum(function ($item) {
    //     //         return $item->harga * $item->quantity;
    //     //     });

    //     // $totalPembelianBulanLalu = mutasi::where('jenis_transaksi', 'M')
    //     //     ->whereMonth('created_at', $lastMonth->month)
    //     //     ->whereYear('created_at', $lastMonth->year)
    //     //     ->get()
    //     //     ->sum(function ($item) {
    //     //         return $item->harga * $item->quantity;
    //     //     });

    //     // $kasSerooDebitBulanLalu = cash_flow::where('sumber_dana', 'kas seroo')
    //     //     ->where('jenis_transaksi', 'debit')
    //     //     ->whereMonth('created_at', $lastMonth->month)
    //     //     ->whereYear('created_at', $lastMonth->year)
    //     //     ->sum('nominal');

    //     // $kasSerooKreditBulanLalu = cash_flow::where('sumber_dana', 'kas seroo')
    //     //     ->where('jenis_transaksi', 'kredit')
    //     //     ->whereMonth('created_at', $lastMonth->month)
    //     //     ->whereYear('created_at', $lastMonth->year)
    //     //     ->sum('nominal');

    //     // $kasSerooBulanLalu = ($totalPenjualanBulanLalu - $totalPembelianBulanLalu) + ($kasSerooDebitBulanLalu - $kasSerooKreditBulanLalu);

    //     // // ----- Persentase Perubahan -----
    //     // if ($kasSerooBulanLalu != 0) {
    //     //     $persentaseKasSeroo = (($kasSerooBulanIni - $kasSerooBulanLalu) / abs($kasSerooBulanLalu)) * 100;
    //     // } else {
    //     //     $persentaseKasSeroo = $kasSerooBulanIni > 0 ? 100 : 0;
    //     // }

    //     return [
    //         'kas_seroo_bulan_ini' => $kasSerooBulanIni,
    //         // 'kas_seroo_bulan_lalu' => $kasSerooBulanLalu,
    //         // 'persentase_perubahan' => $persentaseKasSeroo
    //     ];
    // }


    // public function index(Request $request)
    // {
    //     $title = 'Dashboard';
    //     $breadcrumbs = [
    //         ['label' => 'Home', 'url' => route('admin.dashboard')],
    //         ['label' => 'Dashboard', 'url' => null],
    //     ];

    //     // 1. Optimasi query kas dengan menggabungkan perhitungan bulan ini dan bulan lalu
    //     $totalKasSeroo = $this->getKasSeroo();
    //     $totalPengeluaran = $this->getPengeluaran();
    //     $totalPemasukkan = $this->getPemasukkan();
    //     // 2. Optimasi query penjualan dan pembelian
    //     $salesPurchases = DB::table('penjualan')
    //         ->selectRaw('
    //     SUM(CASE WHEN MONTH(created_at) = ? THEN total ELSE 0 END) as current_month_sales,
    //     SUM(CASE WHEN MONTH(created_at) = ? THEN total ELSE 0 END) as last_month_sales,
    //     (SELECT SUM(total) FROM pembelian WHERE MONTH(created_at) = ?) as current_month_purchase,
    //     (SELECT SUM(total) FROM pembelian WHERE MONTH(created_at) = ?) as last_month_purchase
    //     ', [
    //             Carbon::now()->month,
    //             Carbon::now()->subMonth()->month,
    //             Carbon::now()->month,
    //             Carbon::now()->subMonth()->month
    //         ])
    //         ->first();

    //     $totalPenjualan = $salesPurchases->current_month_sales ?? 0;
    //     $lastMonthPenjualan = $salesPurchases->last_month_sales ?? 0;
    //     $persentasePenjualan = $lastMonthPenjualan != 0 ?
    //         round(($totalPenjualan - $lastMonthPenjualan) / $lastMonthPenjualan * 100, 2) : 0;

    //     $totalPembelian = $salesPurchases->current_month_purchase ?? 0;
    //     $lastMonthPembelian = $salesPurchases->last_month_purchase ?? 0;
    //     $persentasePembelian = $lastMonthPembelian != 0 ?
    //         round(($totalPembelian - $lastMonthPembelian) / $lastMonthPembelian * 100, 2) : 0;

    //     // 3. Bahan Baku Minimum (sudah optimal dengan eager loading)
    //     $bahanBakuMinimum = BahanBaku::whereColumn('stok_akhir', '<=', 'stok_minimum')
    //         ->with('satuan')
    //         ->orderBy('stok_akhir', 'asc')
    //         ->limit(5)
    //         ->get();

    //     $bahanBakuMinimumCount = BahanBaku::whereColumn('stok_akhir', '<=', 'stok_minimum')
    //         ->count();

    //     // 4. Bahan Baku Terlaris (optimasi dengan join)
    //     $topBahanBaku = Mutasi::select([
    //         'bahan_baku.id',
    //         'bahan_baku.nama',
    //         DB::raw('sum(mutasi.quantity) as total_terjual'),
    //         's.nama as satuan_nama' // Ambil langsung nama satuan
    //     ])
    //         ->join('bahan_baku', 'mutasi.id_bahan_baku', '=', 'bahan_baku.id')
    //         ->join('satuan as s', 'bahan_baku.id_satuan', '=', 's.id')
    //         ->where('mutasi.jenis_transaksi', 'K')
    //         ->whereMonth('mutasi.created_at', Carbon::now()->month)
    //         ->groupBy('bahan_baku.id', 'bahan_baku.nama', 's.nama')
    //         ->orderBy('total_terjual', 'desc')
    //         ->limit(5)
    //         ->get();

    //     $maxTerjual = $topBahanBaku->max('total_terjual') ?? 1;

    //     // 5. Grafik Bulanan (versi fixed untuk sql_mode=only_full_group_by)
    //     // $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
    //     // $monthlyPenjualan = [1000000, 1500000, 1200000, 1800000, 2000000, 2200000];
    //     // $monthlyPembelian = [800000, 900000, 1100000, 950000, 1200000, 1500000];
    //     $monthlyData = DB::table('penjualan')
    //         ->selectRaw('
    //     DATE_FORMAT(created_at, "%b %Y") as month_year,
    //     SUM(total) as total_penjualan,
    //     (SELECT SUM(total) FROM pembelian WHERE DATE_FORMAT(created_at, "%b %Y") = month_year) as total_pembelian
    //     ')
    //         ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
    //         ->groupBy('month_year')
    //         ->orderBy('month_year')
    //         ->get();

    //     $monthlyLabels = $monthlyData->pluck('month_year')->toArray();
    //     $monthlyPenjualan = $monthlyData->pluck('total_penjualan')->toArray();
    //     $monthlyPembelian = $monthlyData->pluck('total_pembelian')->toArray();

    //     // 6. Aktivitas Terakhir dengan eager loading
    //     $recentActivities = cash_flow::
    //         // with('user')->
    //         orderBy('created_at', 'desc')
    //         ->limit(5)
    //         ->get()
    //         ->map(function ($item) {
    //             return (object)[
    //                 'description' => $item->keterangan,
    //                 'user' => $item->user,
    //                 'created_at' => $item->created_at,
    //                 'icon' => $item->jenis_transaksi == 'debit' ? 'arrow-up' : 'arrow-down',
    //                 'is_important' => abs($item->nominal) > 1000000
    //             ];
    //         });
    //     return view('dashboard.index', compact(
    //         'title',
    //         'breadcrumbs',
    //         'totalKasSeroo',
    //         'totalPengeluaran',
    //         'totalPemasukkan',
    //         // 'persentaseKas',
    //         'totalPenjualan',
    //         'persentasePenjualan',
    //         'totalPembelian',
    //         'persentasePembelian',
    //         'bahanBakuMinimum',
    //         'bahanBakuMinimumCount',
    //         'topBahanBaku',
    //         'maxTerjual',
    //         'monthlyLabels',
    //         'monthlyPenjualan',
    //         'monthlyPembelian',
    //         'recentActivities'
    //     ));
    // }
}
