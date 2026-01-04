<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\mutasi;
use App\Models\Piutang;
use App\Models\ViewStok;
use App\Models\BahanBaku;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Transaksi;
use App\Models\SumberDana;
use App\Charts\ArusKasChart;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
        public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('gudang')) {
            return redirect()->route('dashboard.admin-gudang');
        } elseif ($user->hasRole('keuangan')) {
            return redirect()->route('dashboard.manajer-keuangan');
        } elseif ($user->hasRole('kasir')) {
            return redirect()->route('dashboard.kasir-outlet');
        } elseif ($user->hasRole('owner')) {
            return redirect()->route('dashboard.owner');
        }

        abort(403, 'Role tidak dikenali');
    }

    public function adminGudang()
    {
        $title = 'Dashboard';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('dashboard.admin-gudang')],
            ['label' => 'Dashboard', 'url' => null],
        ];

        // Data Ringkasan Stok
        $totalBahanBaku = BahanBaku::count();
        $bahanBakuMinimumCount = ViewStok::where('stok_akhir', '<=', DB::raw('bahan_baku.stok_minimum'))
            ->join('bahan_baku', 'view_stok.id_bahan_baku', '=', 'bahan_baku.id')
            ->count();

        // Grafik Tren Stok 30 Hari Terakhir
        $stokTrend = Mutasi::selectRaw('DATE(created_at) as date, SUM(CASE WHEN jenis_transaksi = "M" THEN quantity ELSE -quantity END) as perubahan')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('status', 1)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Pembelian Terakhir
        $pembelianTerakhir = Pembelian::with('supplier')
            ->where('status', '=', 'approved')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        $totalPengeluaranBulanIni = Pembelian::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->where('status', '=', 'completed')
            ->sum('total');

        // Opsi 3: Total penjualan - piutang beredar (menggunakan sisa_piutang accessor)
        $totalPenjualanBulanIni = Penjualan::whereMonth('tanggal', Carbon::now()->month)
        ->where('status_gudang', '=', 'completed')
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('total');

        $totalPiutangBeredarBulanIni = Piutang::whereHas('penjualan', function ($query) {
            $query->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year);
        })->get()->sum('sisa_piutang');

        $totalPendapatanBulanIni = $totalPenjualanBulanIni - $totalPiutangBeredarBulanIni;

        // Distribusi ke Outlet Hari Ini
        $distribusiHariIni = Mutasi::with(['bahanBaku', 'penjualan.outlet'])
            ->where('jenis_transaksi', 'K')
            ->where('status', 1)
            ->whereHas('penjualan', function ($query) {
                $query->whereDate('tanggal', Carbon::today());
            })
            ->get();
        // dd($distribusiHariIni);
        // Outlet dengan Permintaan Terbanyak (3 bulan terakhir jika bulan ini kurang data)
        $outletPermintaan = DB::table('mutasi')
            ->join('penjualan', 'mutasi.nobukti', '=', 'penjualan.nobukti')
            ->join('outlet', 'penjualan.id_outlet', '=', 'outlet.id')
            ->select('penjualan.id_outlet', 'outlet.nama as outlet_nama', DB::raw('SUM(mutasi.quantity) as total_permintaan'))
            ->where('mutasi.jenis_transaksi', 'K')
            ->where('mutasi.status', 1)
            ->where('penjualan.tanggal', '>=', Carbon::now()->subMonths(2)->startOfMonth()) // 3 bulan terakhir
            ->groupBy('penjualan.id_outlet', 'outlet.nama')
            ->orderByDesc('total_permintaan')
            ->take(5)
            ->get();

        // Bahan Baku Perlu Restock
        $bahanPerluRestock = DB::table('view_stok')
            ->join('bahan_baku', 'view_stok.id_bahan_baku', '=', 'bahan_baku.id')
            ->select('bahan_baku.nama', 'view_stok.stok_akhir', 'bahan_baku.stok_minimum', 'view_stok.nama_satuan')
            ->whereColumn('view_stok.stok_akhir', '<=', 'bahan_baku.stok_minimum')
            ->orderBy('view_stok.stok_akhir', 'asc')
            ->get();

        return view('dashboard.gudang', compact(
            'totalBahanBaku',
            'title',
            'breadcrumbs',
            'bahanBakuMinimumCount',
            'stokTrend',
            'pembelianTerakhir',
            'totalPengeluaranBulanIni',
            'totalPendapatanBulanIni',
            'distribusiHariIni',
            'outletPermintaan',
            'bahanPerluRestock'
        ));
    }

    public function stafKeuangan(ArusKasChart $ArusKasChart)
    {
        $title = 'Dashboard Manajer Keuangan';


        // Total Saldo Kas Saat Ini (All Time)
        $totalSaldoSaatIni = Transaksi::join('kategori_keuangan', 'transaksi.id_kategori_keuangan', '=', 'kategori_keuangan.id')
            ->where('transaksi.status', 1)
            ->sum(DB::raw("CASE WHEN kategori_keuangan.jenis = 'pemasukan' THEN transaksi.jumlah ELSE -transaksi.jumlah END"));

        // Total Pendapatan & Pengeluaran Bulan Ini
        $totalPendapatanBulanIni = Transaksi::whereHas('kategoriKeuangan', function($q) {
                $q->where('jenis', 'pemasukan');
            })
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->where('status', 1)
            ->sum('jumlah');

        $totalPengeluaranBulanIni = Transaksi::whereHas('kategoriKeuangan', function($q) {
                $q->where('jenis', 'pengeluaran');
            })
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->where('status', 1)
            ->sum('jumlah');

        // Top Pengeluaran
        $topPengeluaran = Transaksi::with('kategoriKeuangan')
            ->whereHas('kategoriKeuangan', function($q) {
                $q->where('jenis', 'pengeluaran');
            })
            ->orderBy('jumlah', 'desc')
            ->take(5)
            ->get();

        // Piutang & Hutang
        $totalPiutangBeredar = Piutang::where('status', '!=', 'lunas')
            ->get()
            ->sum('sisa_piutang');

        // Total Hutang Jatuh Tempo (sudah melewati tanggal jatuh tempo)
        $totalHutang = Piutang::where('status', '!=', 'lunas')
            ->where('jatuh_tempo', '<=', Carbon::now())
            ->get()
            ->sum('sisa_piutang');

        // Piutang Jatuh Tempo
        $piutangJatuhTempo = Piutang::with(['penjualan.outlet'])
            ->where('status', '!=', 'lunas')
            ->where('jatuh_tempo', '<=', Carbon::now())
            ->orderBy('jatuh_tempo', 'ASC')
            ->take(5)
            ->get();

        // Transaksi Terbaru
        $transaksiTerbaru = Transaksi::orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $ArusKasChart = $ArusKasChart->build();

        return view('dashboard.keuangan', compact(
            'title',
            'totalSaldoSaatIni',
            'totalPendapatanBulanIni',
            'totalPengeluaranBulanIni',
            'topPengeluaran',
            'totalPiutangBeredar',
            'totalHutang',
            'ArusKasChart',
            // 'laporanBulanan',
            'piutangJatuhTempo',
            'transaksiTerbaru'
        ));
    }

    public function owner(ArusKasChart $ArusKasChart)
    {
        $title = 'Dashboard';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Dashboard', 'url' => null],
        ];

        // Total Saldo Kas Saat Ini (All Time)
        $totalSaldoSaatIni = Transaksi::join('kategori_keuangan', 'transaksi.id_kategori_keuangan', '=', 'kategori_keuangan.id')
            ->where('transaksi.status', 1)
            ->sum(DB::raw("CASE WHEN kategori_keuangan.jenis = 'pemasukan' THEN transaksi.jumlah ELSE -transaksi.jumlah END"));

        // Pemasukan Bulan Ini: SUM(jumlah) dari transaksi tipe='pemasukan' bulan berjalan
        $pemasukanBulanIni = Transaksi::whereHas('kategoriKeuangan', function($q) {
                $q->where('jenis', 'pemasukan');
            })
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');

        // Pengeluaran Bulan Ini: SUM(jumlah) dari transaksi tipe='pengeluaran' bulan berjalan
        $pengeluaranBulanIni = Transaksi::whereHas('kategoriKeuangan', function($q) {
                $q->where('jenis', 'pengeluaran');
            })
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');

        // Total Piutang Beredar: SUM(sisa_piutang) dari piutang status != 'lunas'
        $totalPiutangBeredar = Piutang::where('status', '!=', 'lunas')
            ->get()
            ->sum('sisa_piutang');

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
            ->select(
                'outlet.nama as nama_mitra',
                'piutang.jumlah_piutang',
                'piutang.jatuh_tempo',
                DB::raw('DATEDIFF(NOW(), piutang.jatuh_tempo) as hari_telat')
            )
            ->where('piutang.status', '!=', 'lunas')
            ->where('piutang.jatuh_tempo', '<=', Carbon::now())
            ->orderBy('piutang.jatuh_tempo', 'ASC')
            ->limit(5)
            ->get();

        // Query: SUM(jumlah) dari transaksi WHERE tipe = 'debit' AND tanggal = HARI_INI
        $pemasukanHariIni = Transaksi::whereHas('kategoriKeuangan', function($q) {
                $q->where('jenis', 'pemasukan');
            })
            ->whereDate('tanggal', Carbon::today())
            ->sum('jumlah');

        // Query: SUM(jumlah) dari transaksi WHERE tipe = 'kredit' AND tanggal = HARI_INI
        $pengeluaranHariIni = Transaksi::whereHas('kategoriKeuangan', function($q) {
                $q->where('jenis', 'pengeluaran');
            })
            ->whereDate('tanggal', Carbon::today())
            ->sum('jumlah');

        // Query: COUNT(id) dari penjualan + pembelian WHERE tanggal = HARI_INI
        // $penjualanHariIni = Penjualan::whereDate('tanggal', Carbon::today())
        //     ->count();

        $transaksiBaruHariIni = Transaksi::whereDate('tanggal', Carbon::today())
            ->count();

        // $transaksiBaruHariIni = $penjualanHariIni + $pembelianHariIni;

        $ArusKasChart = $ArusKasChart->build();

        return view('dashboard.owner', compact(
            'title',
            'breadcrumbs',
            'totalSaldoSaatIni',
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'totalPiutangBeredar',
            'itemStokKritis',
            'barangSegeraHabis',
            'piutangJatuhTempo',
            'pemasukanHariIni',
            'pengeluaranHariIni',
            'transaksiBaruHariIni',
            'ArusKasChart'
        ));
    }
}
