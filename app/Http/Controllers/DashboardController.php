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
    public function index(Request $request)
    {
        $title = 'Dashboard';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Dashboard', 'url' => null],
        ];

        // 1. Optimasi query kas dengan menggabungkan perhitungan bulan ini dan bulan lalu
        $now = Carbon::now();
        $currentMonth = $now->month;
        $lastMonth = $now->copy()->subMonth()->month;

        $kasQuery = cash_flow::where('sumber_dana', 'kas seroo')
            ->selectRaw('
                   SUM(CASE WHEN jenis_transaksi = "debit" THEN nominal ELSE -nominal END) as total,
                   SUM(CASE WHEN MONTH(tanggal) = ? AND YEAR(tanggal) = ? AND jenis_transaksi = "debit" THEN nominal ELSE 0 END) as current_month,
                   SUM(CASE WHEN MONTH(tanggal) = ? AND YEAR(tanggal) = ? AND jenis_transaksi = "debit" THEN nominal ELSE 0 END) as last_month
               ', [
                $currentMonth,
                $now->year,  // untuk current_month
                $lastMonth,
                $now->copy()->subMonth()->year // untuk last_month
            ])
            ->first();

        $totalKasSeroo = $kasQuery->total ?? 0;
        $currentMonthKasSeroo = $kasQuery->current_month ?? 0;
        $lastMonthKasSeroo = $kasQuery->last_month ?? 0;

        $persentaseKas = $lastMonthKasSeroo != 0
            ? round(($currentMonthKasSeroo - $lastMonthKasSeroo) / $lastMonthKasSeroo * 100, 2)
            : 0;

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
            'persentaseKas',
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
