<?php

namespace App\Http\Controllers;

use App\Models\mutasi;
use App\Models\ViewStok;
use App\Models\BahanBaku;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Transaksi;
use App\Models\SumberDana;
use App\Exports\StokExport;
use App\Models\VSaldoAkhir;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Charts\SaldoKasChart;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    /**
     * Laporan Saldo Kas per Sumber Dana (Dompet)
     * Data sumber: view_saldo_dana
     */
    public function laporanSaldoKas(Request $request, SaldoKasChart $chart)
    {
        $title = 'Laporan Saldo Kas';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Laporan Saldo Kas', 'url' => null],
        ];

        // Ambil data saldo per dompet dari view_saldo_dana
        $saldoPerDompet = DB::table('view_saldo_dana')
            ->select('id_sumber_dana', 'nama', 'saldo_awal', 'total_pemasukan', 'total_pengeluaran', 'saldo_current')
            ->orderBy('nama')
            ->get();

        // Ringkasan total
        $totalSaldoAwal = $saldoPerDompet->sum('saldo_awal');
        $totalPemasukan = $saldoPerDompet->sum('total_pemasukan');
        $totalPengeluaran = $saldoPerDompet->sum('total_pengeluaran');
        $totalSaldoAkhir = $saldoPerDompet->sum('saldo_current');

        return view('laporan.saldo-kas',['chart' => $chart->build()], compact(
            'title',
            'breadcrumbs',
            'saldoPerDompet',
            'totalSaldoAwal',
            'totalPemasukan',
            'totalPengeluaran',
            'totalSaldoAkhir',
            // 'chart'
        ));
    }

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

    public function exportPdf()
    {
        $title = 'Laporan Stok Bahan Baku';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Bahan Baku', 'url' => route('bahan-baku.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $laporan_stok = ViewStok::all();
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

    public function laporanKartuStok(Request $request)
    {
        $title = 'Laporan Kartu Stok Bahan Baku';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Laporan Kartu Stok', 'url' => null],
        ];

        // 1. Ambil semua bahan baku untuk pilihan filter (dropdown)
        $bahan_baku_list = BahanBaku::orderBy('nama', 'asc')->get();

        // 2. Siapkan variabel untuk menampung hasil
        $riwayat_mutasi = collect(); // Gunakan collect() agar selalu jadi collection
        $selected_item = null;
        $selected_id = $request->input('id_bahan_baku');

        // 3. Jika ada bahan baku yang dipilih, jalankan query
        if ($selected_id) {
            // Ambil data bahan baku yang dipilih (untuk info stok awal)
            $selected_item = BahanBaku::find($selected_id);

            // --- PERBAIKAN DIMULAI DI SINI ---

            // Ambil riwayat mutasi TANPA diurutkan oleh database
            $riwayat_mutasi_unsorted = Mutasi::where('id_bahan_baku', $selected_id)
                ->with('mutasiable') // Eager load tetap sangat penting
                ->get();

            // Urutkan data di sisi aplikasi (PHP) menggunakan Collection sort
            // Kita mengasumsikan kedua tabel (pembelian & penjualan)
            // memiliki kolom 'tanggal' sebagai tanggal transaksi.
            $riwayat_mutasi = $riwayat_mutasi_unsorted->sortBy(function ($mutasi) {

                // Cek untuk menghindari error jika relasi 'mutasiable' rusak/null
                if ($mutasi->mutasiable) {
                    // 'tanggal' adalah kolom tanggal transaksi di tabel pembelian/penjualan
                    // Ganti 'tanggal' jika nama kolomnya beda (misal: 'tgl_transaksi')
                    return $mutasi->mutasiable->tanggal;
                }

                // Jika relasi tidak ada, taruh di akhir
                return now()->addYears(10);
            });

            // --- PERBAIKAN SELESAI ---
        }

        // 4. Kirim data ke view
        return view('laporan.kartu-stok', compact(
            'title',
            'breadcrumbs',
            'bahan_baku_list',  // Data untuk dropdown filter
            'riwayat_mutasi',   // Hasil laporan (sudah terurut)
            'selected_item',    // Info bahan baku yang dipilih
            'selected_id'       // Untuk set 'selected' di dropdown
        ));
    }

    public function laporanRekapTransaksi(Request $request)
    {
        $title = 'Laporan Rekap Transaksi';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Laporan Rekap Transaksi', 'url' => null],
        ];

        // 1. Ambil filter tanggal dari request
        $tanggal_awal = $request->input('tanggal_awal', now()->startOfMonth()->toDateString());
        $tanggal_akhir = $request->input('tanggal_akhir', now()->endOfMonth()->toDateString());

        // 2. Rekap Pembelian per Supplier
        $rekap_pembelian = Pembelian::with('supplier')
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->selectRaw('id_supplier, COUNT(id) as jumlah_transaksi, SUM(total) as total_pembelian')
            ->groupBy('id_supplier')
            ->orderByDesc('total_pembelian')
            ->get()
            ->map(function ($item) {
                return [
                    'nama_supplier' => $item->supplier->nama ?? '-',
                    'jumlah_transaksi' => $item->jumlah_transaksi,
                    'total_pembelian' => $item->total_pembelian,
                ];
            });

        // dd($rekap_pembelian);

        // 3. Rekap Penjualan per Outlet
        $rekap_penjualan = Penjualan::with('cabang')
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->selectRaw('id_cabang, COUNT(id) as jumlah_transaksi, SUM(total) as total_penjualan')
            ->groupBy('id_cabang')
            ->orderByDesc('total_penjualan')
            ->get()
            ->map(function ($item) {
                return [
                    'nama_outlet' => $item->cabang->nama ?? '-',
                    'jumlah_transaksi' => $item->jumlah_transaksi,
                    'total_penjualan' => $item->total_penjualan,
                ];
            });

        // 4. Kirim ke view
        return view('laporan.rekap-transaksi', compact(
            'title',
            'breadcrumbs',
            'rekap_pembelian',
            'rekap_penjualan',
            'tanggal_awal',
            'tanggal_akhir'
        ));
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
        $sumber_dana = $request->input('sumber_dana', 'all');
        $tipe_transaksi = $request->input('tipe_transaksi', 'all');
        $search = $request->input('search', '');

        // 2. Query Builder untuk Transaksi
        $query = Transaksi::with('sumberDana')
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->where('status', 1); // hanya transaksi aktif

        // Apply filters
        if ($sumber_dana !== 'all') {
            $query->where('id_sumber_dana', $sumber_dana);
        }

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
            ->when($sumber_dana !== 'all', function($q) use ($sumber_dana) {
                return $q->where('id_sumber_dana', $sumber_dana);
            })
            ->sum('jumlah');

        $totalKredit = Transaksi::where('tipe', 'kredit')
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->where('status', 1)
            ->when($sumber_dana !== 'all', function($q) use ($sumber_dana) {
                return $q->where('id_sumber_dana', $sumber_dana);
            })
            ->sum('jumlah');

        $saldoAkhir = $totalDebit - $totalKredit;
        $jumlahTransaksi = $transaksi->total();

        // 5. Get Sumber Dana untuk Filter Dropdown
        $sumberDanaList = SumberDana::select('id', 'nama')->get();

        // 6. Running Balance Calculation untuk setiap transaksi
        foreach ($transaksi as $index => $trx) {
            if ($index == 0) {
                // Hitung saldo awal dari transaksi sebelum periode
                $saldoSebelumnya = Transaksi::where('tanggal', '<', $tanggal_awal)
                    ->where('status', 1)
                    ->when($sumber_dana !== 'all', function($q) use ($sumber_dana) {
                        return $q->where('id_sumber_dana', $sumber_dana);
                    })
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
            'sumber_dana',
            'tipe_transaksi',
            'search',
            'totalDebit',
            'totalKredit',
            'saldoAkhir',
            'jumlahTransaksi',
            'sumberDanaList'
        ));
    }
}
