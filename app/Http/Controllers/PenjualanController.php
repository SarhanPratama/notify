<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cabang;
use App\Models\mutasi;
use App\Models\BahanBaku;
use App\Models\Penjualan;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PembelianService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PenjualanRequest;
use App\Services\PenjualanService;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Penjualan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian', 'url' => route('penjualan.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        $penjualan = penjualan::with('cabang', 'mutasi.bahanBaku.satuan');

        // Tambahkan filter hanya jika kedua tanggal ada
        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            $penjualan = $penjualan->whereBetween('tanggal', [$tanggalMulai, $tanggalSampai]);
        }

        $penjualan = $penjualan->latest()->get();

        return view('penjualan.index', compact('title', 'breadcrumbs', 'penjualan'));
    }

    public function create()
    {
        $title = 'Penjualan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Penjualan', 'url' => route('penjualan.index')],
            ['label' => 'Form Tambah', 'url' => null],
        ];
        $cabang = Cabang::pluck('nama', 'id');
        $produk = BahanBaku::with('satuan')->get();
        $sumberDana = SumberDana::pluck('nama', 'id');

        return view('penjualan.create', compact('title', 'breadcrumbs', 'cabang', 'produk', 'sumberDana'));
    }

    public function store(PenjualanRequest $request, PenjualanService $penjualanService)
    {
        // dd($request);
        try {
            $validated = $request->validated();
            $penjualanService->tambah($validated);
        } catch (\Exception $e) {
            notify()->error('Gagal menyimpan data penjualan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
        notify()->success('Data penjualan berhasil disimpan');
        return redirect()->route('penjualan.index');
    }

    public function show($nobukti, PenjualanService $penjualanService)
    {

        $title = 'Detail Penjualan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Penjualan', 'url' => route('penjualan.index')],
            ['label' => 'Detail', 'url' => null],
        ];

        $detailPenjualan = $penjualanService->getPenjualanDetails($nobukti);
        // dd($detailPenjualan);
        return view('penjualan.show', compact('title', 'breadcrumbs', 'detailPenjualan'));
    }

    public function edit($nobukti)
    {
        $title = 'Penjualan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Penjualan', 'url' => route('penjualan.index')],
            ['label' => 'Form Edit', 'url' => null],
        ];

        $penjualan = Penjualan::with(['mutasi.bahanBaku.satuan', 'cabang', 'transaksi'])
            ->where('nobukti', $nobukti)
            ->firstOrFail();
        // dd($penjualan);
        $sumberDana = SumberDana::pluck('nama', 'id');
        $cabang = Cabang::pluck('nama', 'id');
        $produk = BahanBaku::with('satuan')->get();

        return view('penjualan.edit', compact('title', 'breadcrumbs', 'penjualan', 'cabang', 'produk', 'sumberDana'));
    }

    public function update(PenjualanRequest $request, $nobukti, PenjualanService $penjualanService)
    {
        // dd($nobukti);
        try {
            $validated = $request->validated();
            $penjualanService->updatePenjualan($nobukti, $validated);
            notify()->success('Pembelian berhasil diperbarui');
            return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
            notify()->error('Gagal memperbarui penjualan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    // public function update(PenjualanRequest $request, $nobukti, PenjualanRequest $penjualanService)
    // {

    //     try {
    //         // Ambil data pembelian berdasarkan nobukti
    //         $pembelian = Pembelian::where('nobukti', $nobukti)->firstOrFail();

    //         // Hitung total baru
    //         $totalBaru = 0;
    //         foreach ($request->bahanBaku as $index => $idBahanBaku) {
    //             $totalBaru += $request->quantity[$index] * $request->harga[$index];
    //         }

    //         $dataPembelianBaru = [
    //             'tanggal' => $request->tanggal,
    //             'id_supplier' => $request->id_supplier,
    //             'total' => $totalBaru,
    //             'catatan' => $request->catatan,
    //         ];

    //         $dataPembelianLama = [
    //             'tanggal' => $pembelian->tanggal,
    //             'id_supplier' => $pembelian->id_supplier,
    //             'total' => $pembelian->total,
    //             'catatan' => $pembelian->catatan,
    //         ];

    //         // Ambil mutasi lama dan susun datanya untuk perbandingan
    //         $mutasiLama = $pembelian->mutasi()
    //             ->orderBy('id_bahan_baku')
    //             ->get()
    //             ->map(function ($item) {
    //                 return [
    //                     'id_bahan_baku' => $item->id_bahan_baku,
    //                     'quantity' => $item->quantity,
    //                     'harga' => $item->harga
    //                 ];
    //             })->values()->toArray();

    //         // Susun input produk dari request
    //         $produkBaru = collect($request->bahanBaku)->map(function ($id, $index) use ($request) {
    //             return [
    //                 'id_bahan_baku' => $id,
    //                 'quantity' => $request->quantity[$index],
    //                 'harga' => $request->harga[$index]
    //             ];
    //         })->sortBy('id_bahan_baku')->values()->toArray();

    //         // Cek apakah tidak ada data yang berubah
    //         if ($dataPembelianBaru == $dataPembelianLama && $mutasiLama == $produkBaru) {
    //             DB::rollBack();
    //             notify()->info('Tidak ada data yang diupdate');
    //             return redirect()->back();
    //         }

    //         // Lanjut update pembelian
    //         $pembelian->update($dataPembelianBaru);

    //         // Ambil mutasi lama untuk update
    //         $mutasiLama = $pembelian->mutasi()->get()->keyBy('id_bahan_baku');

    //         foreach ($produkBaru as $item) {
    //             $idBahanBaku = $item['id_bahan_baku'];
    //             $quantityBaru = $item['quantity'];
    //             $hargaBaru = $item['harga'];
    //             $subTotal = $quantityBaru * $hargaBaru;

    //             if ($mutasiLama->has($idBahanBaku)) {
    //                 $mutasi = $mutasiLama[$idBahanBaku];

    //                 // Update mutasi
    //                 $mutasi->update([
    //                     'quantity' => $quantityBaru,
    //                     'harga' => $hargaBaru,
    //                     'sub_total' => $subTotal,
    //                     'updated_at' => now(),
    //                 ]);
    //             } else {
    //                 // Mutasi belum ada, buat baru
    //                 Mutasi::create([
    //                     'mutasiable_id' => $pembelian->id,  // Menyimpan ID pembelian
    //                     'mutasiable_type' => Pembelian::class,  // Menyimpan tipe model pembelian
    //                     'id_bahan_baku' => $idBahanBaku,
    //                     'quantity' => $quantityBaru,
    //                     'harga' => $hargaBaru,
    //                     'sub_total' => $subTotal,
    //                     'jenis_transaksi' => 'M',  // Menggunakan transaksi mutasi 'M' untuk pembelian
    //                     'status' => 1,
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ]);
    //             }
    //         }

    //         // Hapus transaksi lama jika ada
    //         $transaksiLama = $pembelian->transaksi()->first();
    //         if ($transaksiLama) {
    //             // Kembalikan saldo lama
    //             $transaksiLama->sumberDana->increment('saldo_current', $transaksiLama->jumlah);

    //             // Update data transaksi
    //             $transaksiLama->update([
    //                 'id_sumber_dana' => $request->id_sumber_dana,
    //                 'tanggal' => $request->tanggal,
    //                 'tipe' => 'credit',
    //                 'jumlah' => $totalBaru,
    //                 'deskripsi' => 'Update pembelian bahan baku #' . $pembelian->nobukti,
    //             ]);
    //         } else {
    //             // Buat transaksi baru jika sebelumnya tidak ada
    //             $pembelian->transaksi()->create([
    //                 'id_sumber_dana' => $request->id_sumber_dana,
    //                 'tanggal' => $request->tanggal,
    //                 'tipe' => 'credit',
    //                 'jumlah' => $totalBaru,
    //                 'deskripsi' => 'Update pembelian bahan baku #' . $pembelian->nobukti,
    //             ]);
    //         }

    //         // Kurangi saldo dari sumber dana baru
    //         SumberDana::find($request->id_sumber_dana)->decrement('saldo_current', $totalBaru);

    //         DB::commit();
    //         notify()->success('Data Pembelian berhasil diperbarui');
    //         return redirect()->route('pembelian.index');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         notify()->error('Gagal memperbarui data Pembelian: ' . $e->getMessage());
    //         return redirect()->back()->withInput();
    //     }
    // }


    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $penjualan = Penjualan::findOrFail($id);

            // Ambil semua mutasi berdasarkan nomor bukti
            $mutasiList = Mutasi::where('nobukti', $penjualan->nobukti)->get();

            foreach ($mutasiList as $mutasi) {
                // Jika status 1, rollback stok bahan baku
                if ($mutasi->status == 1) {
                    $bahanBaku = BahanBaku::find($mutasi->id_bahan_baku);
                    if ($bahanBaku) {
                        $bahanBaku->stok_akhir -= $mutasi->quantity;
                        $bahanBaku->save();
                    }
                }

                // Hapus mutasi
                $mutasi->delete();
            }

            // Hapus penjualan
            $penjualan->delete();

            DB::commit();

            notify()->success('Data penjualan berhasil dihapus.');
            return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menghapus penjualan: ' . $e->getMessage());
            return redirect()->back();
        }
    }



    // public function showStruk($id)
    // {
    //     $penjualan = Penjualan::with('cabang', 'mutasi.bahanBaku.satuan')->findOrFail($id);
    //     return view('penjualan.struk', compact('penjualan'));
    // }

    public function laporanPenjualan(Request $request)
    {
        $title = 'Laporan Penjualan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')], // Pastikan route 'admin.dashboard' ada
            // ['label' => 'Laporan Penjualan', 'url' => route('laporan-penjualan')], // Sesuaikan route jika berbeda
            ['label' => 'Laporan Penjualan', 'url' => null], // Bisa ditambahkan jika ingin lebih spesifik
        ];

        // Mengambil input tanggal dari request
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        // Inisialisasi koleksi laporan penjualan sebagai koleksi kosong
        $laporan_penjualan = collect();
        // Flag untuk menentukan apakah tabel akan ditampilkan atau tidak
        $showTable = false;

        // Hanya query jika kedua tanggal telah diinput
        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            // Format tanggalSampai untuk memastikan mencakup keseluruhan hari tersebut
            $tanggalSampaiFormatted = Carbon::parse($tanggalSampai)->endOfDay()->toDateTimeString();

            // Query untuk mengambil data mutasi (penjualan)
            $laporan_penjualan = Mutasi::with(['bahanBaku.satuan', 'penjualan.cabang'])
                ->where('jenis_transaksi', 'K') // 'K' untuk Keluar (Penjualan)
                ->where('status', 1) // Asumsi status 1 adalah transaksi yang valid/selesai
                ->whereBetween('created_at', [$tanggalMulai, $tanggalSampaiFormatted])
                ->latest() // Mengurutkan berdasarkan data terbaru
                ->get();

            $showTable = true; // Set flag menjadi true karena data telah diambil (atau percobaan pengambilan data dilakukan)
        }

        // Mengembalikan view dengan data yang diperlukan
        return view('penjualan.laporan-penjualan', compact(
            'title',
            'breadcrumbs',
            'laporan_penjualan',
            'showTable',        // Kirim flag ini ke view
            'tanggalMulai',     // Kirim kembali untuk mengisi ulang form
            'tanggalSampai'     // Kirim kembali untuk mengisi ulang form
        ));
    }

    public function exportPDF(Request $request)
    {
        $title = 'Laporan Penjualan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Penjualan', 'url' => route('penjualan.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        $laporan_penjualan = Mutasi::with(['bahanBaku.satuan', 'penjualan.cabang'])
            ->where('jenis_transaksi', 'K')
            ->where('status', 1);

        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            $laporan_penjualan->whereBetween('created_at', [$tanggalMulai, $tanggalSampai]);
            $periode = 'Periode: ' .
                \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d M Y') .
                ' - ' .
                \Carbon\Carbon::parse($tanggalSampai)->translatedFormat('d M Y');
        } else {
            $periode = 'Semua Periode';
        }

        $laporan_penjualan = $laporan_penjualan->latest()->get();
        // dd($laporan_penjualan);
        $pdf = Pdf::loadView('penjualan.penjualan-pdf', [
            'laporan' => $laporan_penjualan,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'periode' => $periode,

        ])->setPaper('A4', 'landscape');


        return $pdf->stream('Laporan_Penjualan.pdf');
    }

    public function formPenjualan()
    {

        // Get all available bahan baku with stock > 0
        $bahanBaku = bahanBaku::with('satuan', 'kategori')
            ->leftJoin('vsaldoakhir2', 'bahan_baku.id', '=', 'vsaldoakhir2.id')
            ->where('vsaldoakhir2.saldoakhir', '>', 0)
            ->get();

        return view('penjualan.form-penjualan', [
            'title' => 'Checkout Bahan Baku',
            'bahanBaku' => $bahanBaku
        ]);
    }
}
