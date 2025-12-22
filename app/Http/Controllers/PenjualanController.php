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
use App\Models\Outlet;
use App\Services\PenjualanService;

class PenjualanController extends Controller
{
    public function index()
    {
        $title = 'Penjualan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian', 'url' => route('penjualan.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $penjualan = Penjualan::with('outlet', 'piutang')->whereIn('status_gudang', ['approved', 'rejected'])->latest()->get();

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
        $cabang = Outlet::pluck('nama', 'id');
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

        $penjualan = Penjualan::with(['mutasi.bahanBaku.satuan', 'outlet', 'transaksi.sumberDana', 'piutang'])
            ->where('nobukti', $nobukti)
            ->firstOrFail();

        // Session key unik per nobukti
        $sessionKey = 'cart_penjualan_' . $nobukti;

        // Clear cart jika bukan dari operasi cart (fresh page load/reload)
        $previousUrl = url()->previous();
        $isFromCartOperation = str_contains($previousUrl, $nobukti . '/cart') || str_contains($previousUrl, 'penjualan/' . $nobukti . '/edit');
        if (!$isFromCartOperation) {
            session()->forget($sessionKey);
        }

        // Jika session belum ada, inisialisasi dengan data dari database
        if (!session()->has($sessionKey)) {
            $cartItems = [];
            foreach ($penjualan->mutasi as $mutasi) {
                $cartItems[] = [
                    'id_bahan_baku' => $mutasi->id_bahan_baku,
                    'nama_bahan_baku' => $mutasi->bahanBaku->nama,
                    'satuan' => $mutasi->bahanBaku->satuan->nama ?? '',
                    'quantity' => $mutasi->quantity,
                    'harga' => $mutasi->harga,
                    'sub_total' => $mutasi->sub_total,
                ];
            }
            session([$sessionKey => $cartItems]);
        }

        $cartItems = session($sessionKey, []);

        $sumberDana = SumberDana::pluck('nama', 'id');
        $outlet = Outlet::pluck('nama', 'id');
        $produk = BahanBaku::with('satuan')->get();

        return view('penjualan.edit', compact('title', 'breadcrumbs', 'penjualan', 'outlet', 'produk', 'sumberDana', 'cartItems'));
    }

    // Tambah item ke cart session
    public function addToCart(Request $request, $nobukti)
    {
        $request->validate([
            'id_bahan_baku' => 'required|exists:bahan_baku,id',
            'quantity' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ]);

        $sessionKey = 'cart_penjualan_' . $nobukti;
        $cartItems = session($sessionKey, []);

        $bahanBaku = BahanBaku::with('satuan')->find($request->id_bahan_baku);

        $cartItems[] = [
            'id_bahan_baku' => $request->id_bahan_baku,
            'nama_bahan_baku' => $bahanBaku->nama,
            'satuan' => $bahanBaku->satuan->nama ?? '',
            'quantity' => $request->quantity,
            'harga' => $request->harga,
            'sub_total' => $request->quantity * $request->harga,
        ];

        session([$sessionKey => $cartItems]);

        notify()->success('Item berhasil ditambahkan ke keranjang');
        return redirect()->route('penjualan.edit', $nobukti);
    }

    // Hapus item dari cart session
    public function removeFromCart($nobukti, $index)
    {
        $sessionKey = 'cart_penjualan_' . $nobukti;
        $cartItems = session($sessionKey, []);

        if (isset($cartItems[$index])) {
            unset($cartItems[$index]);
            $cartItems = array_values($cartItems);
            session([$sessionKey => $cartItems]);
            notify()->success('Item berhasil dihapus dari keranjang');
        }

        return redirect()->route('penjualan.edit', $nobukti);
    }

    // Clear cart session
    public function clearCart($nobukti)
    {
        $sessionKey = 'cart_penjualan_' . $nobukti;
        session()->forget($sessionKey);
        notify()->success('Keranjang berhasil dikosongkan');
        return redirect()->route('penjualan.edit', $nobukti);
    }

    public function update(Request $request, $nobukti, PenjualanService $penjualanService)
    {
        $sessionKey = 'cart_penjualan_' . $nobukti;
        $cartItems = session($sessionKey, []);

        if (empty($cartItems)) {
            notify()->error('Keranjang masih kosong, tambahkan item terlebih dahulu');
            return redirect()->back();
        }

        $request->validate([
            'tanggal' => 'required|date',
            'id_outlet' => 'required|exists:outlet,id',
            'id_sumber_dana' => 'required|exists:sumber_dana,id',
            'status_pembayaran' => 'required|in:lunas,kasbon',
            'catatan' => 'nullable|string',
        ]);

        try {
            // Konversi cartItems ke format yang diharapkan service
            $validated = [
                'tanggal' => $request->tanggal,
                'id_cabang' => $request->id_outlet,
                'id_sumber_dana' => $request->id_sumber_dana,
                'status_pembayaran' => $request->status_pembayaran,
                'catatan' => $request->catatan,
                'bahanBaku' => collect($cartItems)->pluck('id_bahan_baku')->toArray(),
                'quantity' => collect($cartItems)->pluck('quantity')->toArray(),
                'harga' => collect($cartItems)->pluck('harga')->toArray(),
            ];

            $penjualanService->updatePenjualan($nobukti, $validated);

            // Clear session setelah berhasil
            session()->forget($sessionKey);

            notify()->success('Penjualan berhasil diperbarui');
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
    //                     'jenis_transaksi' => 'M'  // Menggunakan transaksi mutasi 'M' untuk pembelian
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
            $penjualan = Penjualan::with(['mutasi', 'transaksi', 'piutang'])->findOrFail($id);

            // Soft delete mutasi (view stok akan otomatis menyesuaikan)
            foreach ($penjualan->mutasi as $mutasi) {
                if ($mutasi->status == 1) {
                    $mutasi->delete();
                }
            }

            // Soft delete transaksi jika ada (view saldo akan otomatis menyesuaikan)
            $transaksi = $penjualan->transaksi->first();
            if ($transaksi) {
                $transaksi->status = 0;
                $transaksi->save();
                $transaksi->delete();
            }

            // Soft delete piutang jika ada
            $piutang = $penjualan->piutang;
            if ($piutang) {
                $piutang->delete();
            }

            // Soft delete penjualan (tanpa mengubah status karena enum tidak menerima 0)
            $penjualan->delete();

            DB::commit();

            notify()->success('Data penjualan berhasil dihapus sementara.');
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

    public function restore($nobukti)
    {
        DB::beginTransaction();

        try {
            $penjualan = Penjualan::withTrashed()
                ->with([
                    'mutasi' => fn($q) => $q->withTrashed(),
                    'transaksi' => fn($q) => $q->withTrashed(),
                    'piutang' => fn($q) => $q->withTrashed()
                ])
                ->where('nobukti', $nobukti)
                ->firstOrFail();

            // Set status kembali ke approved (karena enum tidak menerima 1)
            $penjualan->status = 'approved';
            $penjualan->restore();

            // Restore mutasi (view stok akan otomatis menyesuaikan)
            foreach ($penjualan->mutasi as $mutasi) {
                $mutasi->restore();
            }

            // Restore transaksi jika ada (view saldo akan otomatis menyesuaikan)
            $transaksi = $penjualan->transaksi->first();
            if ($transaksi) {
                $transaksi->status = 1;
                $transaksi->restore();
            }

            // Restore piutang jika ada
            $piutang = $penjualan->piutang;
            if ($piutang) {
                $piutang->restore();
            }

            DB::commit();
            notify()->success('Data penjualan berhasil dipulihkan.');
            return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal memulihkan penjualan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function forceDelete($id)
    {
        DB::beginTransaction();

        try {
            $penjualan = Penjualan::withTrashed()
                ->with([
                    'mutasi' => fn($q) => $q->withTrashed(),
                    'transaksi' => fn($q) => $q->withTrashed(),
                    'piutang' => fn($q) => $q->withTrashed()
                ])
                ->findOrFail($id);

            // Force delete semua mutasi
            foreach ($penjualan->mutasi as $mutasi) {
                $mutasi->forceDelete();
            }

            // Force delete transaksi jika ada
            $transaksi = $penjualan->transaksi->first();
            if ($transaksi) {
                $transaksi->forceDelete();
            }

            // Force delete piutang jika ada
            $piutang = $penjualan->piutang;
            if ($piutang) {
                $piutang->forceDelete();
            }

            // Force delete penjualan
            $penjualan->forceDelete();

            DB::commit();

            notify()->success('Data penjualan berhasil dihapus permanen.');
            return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menghapus permanen: ' . $e->getMessage());
            return redirect()->back();
        }
    }

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
            $laporan_penjualan = Mutasi::with(['bahanBaku.satuan', 'penjualan.outlet'])
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
            ->leftJoin('view_stok', 'bahan_baku.id', '=', 'view_stok.id_bahan_baku')
            ->where('view_stok.stok_akhir', '>', 0)
            ->get();

        return view('penjualan.form-penjualan', [
            'title' => 'Checkout Bahan Baku',
            'bahanBaku' => $bahanBaku
        ]);
    }
}
