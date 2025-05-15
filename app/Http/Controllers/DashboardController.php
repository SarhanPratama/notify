<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\mutasi;
use App\Models\bahanBaku;
use App\Models\cash_flow;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getPengeluaran()
    {
        $now = Carbon::now();

        $pengeluaranPembelian = Pembelian::whereMonth('tanggal', $now->month)
        ->whereYear('tanggal', $now->year)
        ->sum('total');
        // dd($pengeluaranPembelian);
        $pengeluaranCashFlow = cash_flow::where('jenis_transaksi', 'kredit')
        ->whereMonth('tanggal', $now->month)
        ->whereYear('tanggal', $now->year)
        ->sum('nominal');

        $totalPengeluaran = $pengeluaranCashFlow + $pengeluaranPembelian;
        return [
            'pengeluaranCashFlow' => $pengeluaranCashFlow,
            'pengeluaranPembelian' => $pengeluaranPembelian,
            'totalPengeluaran' => $totalPengeluaran
        ];
    }

    public function getPemasukkan() {
        $now = Carbon::now();

        $pemasukanPenjualan = Penjualan::whereMonth('tanggal', $now->month)
        ->whereYear('tanggal', $now->year)
        ->sum('total');

        $pemasukanCashFlow = cash_flow::where('jenis_transaksi', 'debit')
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->sum('nominal');

            $totalPemasukkan = $pemasukanPenjualan + $pemasukanCashFlow;

            return [
                'pemasukanCashFlow' => $pemasukanCashFlow,
                'pemasukanPenjualan' => $pemasukanPenjualan,
                'totalPemasukkan' => $totalPemasukkan
            ];
    }

    function getKasSeroo()
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        // ----- Saldo Kas Seroo Bulan Ini -----
        $totalPenjualanBulanIni = $this->getPemasukkan();
        $totalPembelianBulanIni = $this->getPengeluaran();
        // $totalPenjualanBulanIni = mutasi::where('jenis_transaksi', 'K')
        //     ->whereMonth('created_at', $now->month)
        //     ->whereYear('created_at', $now->year)
        //     ->get()
        //     ->sum(function ($item) {
        //         return $item->harga * $item->quantity;
        //     });

        // $totalPembelianBulanIni = mutasi::where('jenis_transaksi', 'M')
        //     ->whereMonth('created_at', $now->month)
        //     ->whereYear('created_at', $now->year)
        //     ->get()
        //     ->sum(function ($item) {
        //         return $item->harga * $item->quantity;
        //     });

        // $kasSerooDebitBulanIni = cash_flow::where('sumber_dana', 'kas seroo')
        //     ->where('jenis_transaksi', 'debit')
        //     ->whereMonth('created_at', $now->month)
        //     ->whereYear('created_at', $now->year)
        //     ->sum('nominal');

        // $kasSerooKreditBulanIni = cash_flow::where('sumber_dana', 'kas seroo')
        //     ->where('jenis_transaksi', 'kredit')
        //     ->whereMonth('created_at', $now->month)
        //     ->whereYear('created_at', $now->year)
        //     ->sum('nominal');

        $kasSerooBulanIni = ($totalPenjualanBulanIni['pemasukanPenjualan'] - $totalPembelianBulanIni['pengeluaranPembelian']) + ($totalPenjualanBulanIni['pemasukanCashFlow'] - $totalPembelianBulanIni['pengeluaranCashFlow']);

        // $totalPenjualanBulanLalu = mutasi::where('jenis_transaksi', 'K')
        //     ->whereMonth('created_at', $lastMonth->month)
        //     ->whereYear('created_at', $lastMonth->year)
        //     ->get()
        //     ->sum(function ($item) {
        //         return $item->harga * $item->quantity;
        //     });

        // $totalPembelianBulanLalu = mutasi::where('jenis_transaksi', 'M')
        //     ->whereMonth('created_at', $lastMonth->month)
        //     ->whereYear('created_at', $lastMonth->year)
        //     ->get()
        //     ->sum(function ($item) {
        //         return $item->harga * $item->quantity;
        //     });

        // $kasSerooDebitBulanLalu = cash_flow::where('sumber_dana', 'kas seroo')
        //     ->where('jenis_transaksi', 'debit')
        //     ->whereMonth('created_at', $lastMonth->month)
        //     ->whereYear('created_at', $lastMonth->year)
        //     ->sum('nominal');

        // $kasSerooKreditBulanLalu = cash_flow::where('sumber_dana', 'kas seroo')
        //     ->where('jenis_transaksi', 'kredit')
        //     ->whereMonth('created_at', $lastMonth->month)
        //     ->whereYear('created_at', $lastMonth->year)
        //     ->sum('nominal');

        // $kasSerooBulanLalu = ($totalPenjualanBulanLalu - $totalPembelianBulanLalu) + ($kasSerooDebitBulanLalu - $kasSerooKreditBulanLalu);

        // // ----- Persentase Perubahan -----
        // if ($kasSerooBulanLalu != 0) {
        //     $persentaseKasSeroo = (($kasSerooBulanIni - $kasSerooBulanLalu) / abs($kasSerooBulanLalu)) * 100;
        // } else {
        //     $persentaseKasSeroo = $kasSerooBulanIni > 0 ? 100 : 0;
        // }

        return [
            'kas_seroo_bulan_ini' => $kasSerooBulanIni,
            // 'kas_seroo_bulan_lalu' => $kasSerooBulanLalu,
            // 'persentase_perubahan' => $persentaseKasSeroo
        ];
    }


    public function index(Request $request)
    {
        $title = 'Dashboard';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Dashboard', 'url' => null],
        ];

        // 1. Optimasi query kas dengan menggabungkan perhitungan bulan ini dan bulan lalu
        $totalKasSeroo = $this->getKasSeroo();
        $totalPengeluaran = $this->getPengeluaran();
        $totalPemasukkan = $this->getPemasukkan();
        // 2. Optimasi query penjualan dan pembelian
        $salesPurchases = DB::table('penjualan')
            ->selectRaw('
        SUM(CASE WHEN MONTH(created_at) = ? THEN total ELSE 0 END) as current_month_sales,
        SUM(CASE WHEN MONTH(created_at) = ? THEN total ELSE 0 END) as last_month_sales,
        (SELECT SUM(total) FROM pembelian WHERE MONTH(created_at) = ?) as current_month_purchase,
        (SELECT SUM(total) FROM pembelian WHERE MONTH(created_at) = ?) as last_month_purchase
    ', [
                Carbon::now()->month,
                Carbon::now()->subMonth()->month,
                Carbon::now()->month,
                Carbon::now()->subMonth()->month
            ])
            ->first();

        $totalPenjualan = $salesPurchases->current_month_sales ?? 0;
        $lastMonthPenjualan = $salesPurchases->last_month_sales ?? 0;
        $persentasePenjualan = $lastMonthPenjualan != 0 ?
            round(($totalPenjualan - $lastMonthPenjualan) / $lastMonthPenjualan * 100, 2) : 0;

        $totalPembelian = $salesPurchases->current_month_purchase ?? 0;
        $lastMonthPembelian = $salesPurchases->last_month_purchase ?? 0;
        $persentasePembelian = $lastMonthPembelian != 0 ?
            round(($totalPembelian - $lastMonthPembelian) / $lastMonthPembelian * 100, 2) : 0;

        // 3. Bahan Baku Minimum (sudah optimal dengan eager loading)
        $bahanBakuMinimum = BahanBaku::whereColumn('stok_akhir', '<=', 'stok_minimum')
            ->with('satuan')
            ->orderBy('stok_akhir', 'asc')
            ->limit(5)
            ->get();

        $bahanBakuMinimumCount = BahanBaku::whereColumn('stok_akhir', '<=', 'stok_minimum')
            ->count();

        // 4. Bahan Baku Terlaris (optimasi dengan join)
        $topBahanBaku = Mutasi::select([
            'bahan_baku.id',
            'bahan_baku.nama',
            DB::raw('sum(mutasi.quantity) as total_terjual'),
            's.nama as satuan_nama' // Ambil langsung nama satuan
        ])
            ->join('bahan_baku', 'mutasi.id_bahan_baku', '=', 'bahan_baku.id')
            ->join('satuan as s', 'bahan_baku.id_satuan', '=', 's.id')
            ->where('mutasi.jenis_transaksi', 'K')
            ->whereMonth('mutasi.created_at', Carbon::now()->month)
            ->groupBy('bahan_baku.id', 'bahan_baku.nama', 's.nama')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        $maxTerjual = $topBahanBaku->max('total_terjual') ?? 1;

        // 5. Grafik Bulanan (versi fixed untuk sql_mode=only_full_group_by)
        // $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
        // $monthlyPenjualan = [1000000, 1500000, 1200000, 1800000, 2000000, 2200000];
        // $monthlyPembelian = [800000, 900000, 1100000, 950000, 1200000, 1500000];
        $monthlyData = DB::table('penjualan')
            ->selectRaw('
        DATE_FORMAT(created_at, "%b %Y") as month_year,
        SUM(total) as total_penjualan,
        (SELECT SUM(total) FROM pembelian WHERE DATE_FORMAT(created_at, "%b %Y") = month_year) as total_pembelian
    ')
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->get();

        $monthlyLabels = $monthlyData->pluck('month_year')->toArray();
        $monthlyPenjualan = $monthlyData->pluck('total_penjualan')->toArray();
        $monthlyPembelian = $monthlyData->pluck('total_pembelian')->toArray();

        // 6. Aktivitas Terakhir dengan eager loading
        $recentActivities = cash_flow::
            // with('user')->
            orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return (object)[
                    'description' => $item->keterangan,
                    'user' => $item->user,
                    'created_at' => $item->created_at,
                    'icon' => $item->jenis_transaksi == 'debit' ? 'arrow-up' : 'arrow-down',
                    'is_important' => abs($item->nominal) > 1000000
                ];
            });
        return view('dashboard.index', compact(
            'title',
            'breadcrumbs',
            'totalKasSeroo',
            'totalPengeluaran',
            'totalPemasukkan',
            // 'persentaseKas',
            'totalPenjualan',
            'persentasePenjualan',
            'totalPembelian',
            'persentasePembelian',
            'bahanBakuMinimum',
            'bahanBakuMinimumCount',
            'topBahanBaku',
            'maxTerjual',
            'monthlyLabels',
            'monthlyPenjualan',
            'monthlyPembelian',
            'recentActivities'
        ));
    }
}
