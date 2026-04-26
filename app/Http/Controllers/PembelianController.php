<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\mutasi;
use App\Models\Supplier;
use App\Models\BahanBaku;
use App\Models\Pembelian;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePembelianRequest;

class PembelianController extends Controller
{

    public static function generateNoBukti()
    {
        $prefix = 'PB-';
        $date = date('Ymd');

        $lastTransaction = Pembelian::where('nobukti', 'like', $prefix . $date . '%')
                              ->orderBy('nobukti', 'desc')
                              ->first();

        if ($lastTransaction) {
            $lastNumber = intval(substr($lastTransaction->nobukti, -3));
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        return $prefix . $date . '-' . $nextNumber;
    }

    public function index(Request $request)
    {
        $title = 'Pembelian Stok';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian Stok', 'url' => route('pembelian.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        $pembelian = Pembelian::with('supplier', 'mutasi.bahanBaku.satuan', 'user');

        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            $pembelian = $pembelian->whereBetween('created_at', [$tanggalMulai, $tanggalSampai]);
        }

        $pembelian = $pembelian->latest()->get();

        // dd($pembelian);

        return view('pembelian.index', compact('title', 'breadcrumbs', 'pembelian'));
    }

    public function create()
    {
        $title = 'Tambah Pembelian Stok';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian Stok', 'url' => route('pembelian.index')],
            ['label' => 'Form Tambah', 'url' => null],
        ];
        $suppliers = Supplier::pluck('nama', 'id');
        $bahanBaku = BahanBaku::with('satuan')->get();

        // Clear cart jika bukan dari operasi cart (fresh page load/reload)
        $previousUrl = url()->previous();
        $isFromCartOperation = str_contains($previousUrl, 'pembelian/cart') || str_contains($previousUrl, 'pembelian/create');
        if (!$isFromCartOperation) {
            session()->forget('cart_pembelian_create');
        }

        // Ambil cart dari session
        $cartItems = session('cart_pembelian_create', []);

        return view('pembelian.create', compact('title', 'breadcrumbs', 'suppliers', 'bahanBaku', 'cartItems'));
    }

    // Tambah item ke cart create
    public function addToCartCreate(Request $request)
    {
        $request->validate([
            'id_bahan_baku' => 'required|exists:bahan_baku,id',
            'quantity' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ]);

        $sessionKey = 'cart_pembelian_create';
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
        return redirect()->route('pembelian.create');
    }

    // Hapus item dari cart create
    public function removeFromCartCreate($index)
    {
        $sessionKey = 'cart_pembelian_create';
        $cartItems = session($sessionKey, []);

        if (isset($cartItems[$index])) {
            unset($cartItems[$index]);
            $cartItems = array_values($cartItems);
            session([$sessionKey => $cartItems]);
            notify()->success('Item berhasil dihapus dari keranjang');
        }

        return redirect()->route('pembelian.create');
    }

    // Clear cart create
    public function clearCartCreate()
    {
        session()->forget('cart_pembelian_create');
        notify()->success('Keranjang berhasil dikosongkan');
        return redirect()->route('pembelian.create');
    }

    public function store(Request $request)
    {
        $sessionKey = 'cart_pembelian_create';
        $cartItems = session($sessionKey, []);

        // Validasi hanya supplier dan catatan karena item dari session
        $request->validate([
            'id_supplier' => 'nullable|exists:supplier,id',
            'catatan'     => 'nullable|string',
        ]);

        if (empty($cartItems)) {
            notify()->error('Keranjang masih kosong, tambahkan item terlebih dahulu');
            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            $nobukti = $this->generateNoBukti();

            // Hitung total dari session cart
            $total = collect($cartItems)->sum('sub_total');

            $pembelian = Pembelian::create([
                'nobukti'     => $nobukti,
                'total'       => $total,
                'tanggal'     => now(),
                'status'      => 'pending',
                'catatan'     => $request->catatan,
                'id_supplier' => $request->id_supplier,
            ]);

            foreach ($cartItems as $item) {
                $pembelian->mutasi()->create([
                    'id_bahan_baku'   => $item['id_bahan_baku'],
                    'quantity'        => $item['quantity'],
                    'harga'           => $item['harga'],
                    'sub_total'       => $item['sub_total'],
                    'jenis_transaksi' => 'M',
                    'nobukti'         => $nobukti,
                ]);
            }

            DB::commit();

            // Clear session setelah berhasil
            session()->forget($sessionKey);

            notify()->success('Pembelian berhasil disimpan');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menyimpan pembelian: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function show($nobukti)
    {

        $title = 'Detail Pembelian Stok';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian Stok', 'url' => route('pembelian.index')],
            ['label' => 'Detail', 'url' => null],
        ];

        $detailPembelian = Pembelian::with(['mutasi.bahanBaku.satuan', 'supplier', 'Transaksi'])
            ->where('nobukti', $nobukti)
            ->firstOrFail();

        // dd($detailPembelian);
        return view('pembelian.show', compact('title', 'breadcrumbs', 'detailPembelian'));
    }

    public function edit(Request $request, $nobukti)
    {
        $title = 'Edit Pembelian Stok';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian Stok', 'url' => route('pembelian.index')],
            ['label' => 'Form Edit', 'url' => null],
        ];

        $detailPembelian = Pembelian::with(['mutasi.bahanBaku.satuan', 'supplier', 'Transaksi'])
            ->where('nobukti', $nobukti)
            ->firstOrFail();

        // Session key unik per nobukti
        $sessionKey = 'cart_pembelian_' . $nobukti;

        // Clear cart jika bukan dari operasi cart (fresh page load/reload)
        $previousUrl = url()->previous();
        $isFromCartOperation = str_contains($previousUrl, $nobukti . '/cart') || str_contains($previousUrl, 'pembelian/' . $nobukti . '/edit');
        if (!$isFromCartOperation) {
            session()->forget($sessionKey);
        }

        // Jika session belum ada, inisialisasi dengan data dari database
        if (!session()->has($sessionKey)) {
            $cartItems = [];
            foreach ($detailPembelian->mutasi as $mutasi) {
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

        $suppliers = Supplier::pluck('nama', 'id');
        $produk = BahanBaku::with('satuan')->get();

        return view('pembelian.edit', compact('title', 'breadcrumbs', 'detailPembelian', 'suppliers', 'produk', 'cartItems'));
    }

    // Tambah item ke cart session
    public function addToCart(Request $request, $nobukti)
    {
        $request->validate([
            'id_bahan_baku' => 'required|exists:bahan_baku,id',
            'quantity' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ]);

        $sessionKey = 'cart_pembelian_' . $nobukti;
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
        return redirect()->route('pembelian.edit', $nobukti);
    }

    // Hapus item dari cart session
    public function removeFromCart($nobukti, $index)
    {
        $sessionKey = 'cart_pembelian_' . $nobukti;
        $cartItems = session($sessionKey, []);

        if (isset($cartItems[$index])) {
            unset($cartItems[$index]);
            $cartItems = array_values($cartItems); // Re-index array
            session([$sessionKey => $cartItems]);
            notify()->success('Item berhasil dihapus dari keranjang');
        }

        return redirect()->route('pembelian.edit', $nobukti);
    }

    // Clear cart session
    public function clearCart($nobukti)
    {
        $sessionKey = 'cart_pembelian_' . $nobukti;
        session()->forget($sessionKey);
        notify()->success('Keranjang berhasil dikosongkan');
        return redirect()->route('pembelian.edit', $nobukti);
    }

    public function update(StorePembelianRequest $request, $nobukti)
    {
        $sessionKey = 'cart_pembelian_' . $nobukti;
        $cartItems = session($sessionKey, []);

        if (empty($cartItems)) {
            notify()->error('Keranjang masih kosong, tambahkan item terlebih dahulu');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $validated['cartItems'] = $cartItems;

            $pembelian = Pembelian::with(['mutasi.bahanBaku.satuan', 'transaksi'])->where('nobukti', $nobukti)->firstOrFail();

            if (isset($validated['cartItems']) && !empty($validated['cartItems'])) {
                $cartItems = $validated['cartItems'];

                $total = collect($cartItems)->sum('sub_total');

                // Sync mutasi: reuse unchanged, update changed, delete removed, create new
                $existing = $pembelian->mutasi->keyBy('id_bahan_baku');

                $incomingByBahan = collect($cartItems)->keyBy('id_bahan_baku');

                // Update existing and delete removed
                foreach ($existing as $idBahan => $mutasi) {
                    if ($incomingByBahan->has($idBahan)) {
                        $incoming = $incomingByBahan->get($idBahan);
                        $incomingQty = (int) $incoming['quantity'];
                        $incomingHarga = (float) $incoming['harga'];

                        if ((int) $mutasi->quantity === $incomingQty && (float) $mutasi->harga === $incomingHarga) {
                            // unchanged, keep existing
                            // ensure sub_total is correct
                            $mutasi->sub_total = $mutasi->quantity * $mutasi->harga;
                            $mutasi->save();
                        } else {
                            $mutasi->update([
                                'quantity' => $incomingQty,
                                'harga' => $incomingHarga,
                                'sub_total' => $incomingQty * $incomingHarga,
                                'jenis_transaksi' => 'M',
                                'nobukti' => $nobukti,
                            ]);
                        }

                        // remove from incoming map so remaining are new items
                        $incomingByBahan->forget($idBahan);
                    } else {
                        // removed in incoming -> delete
                        $mutasi->delete();
                    }
                }

                // Create remaining new incoming items
                foreach ($incomingByBahan->values() as $item) {
                    $pembelian->mutasi()->create([
                        'id_bahan_baku' => $item['id_bahan_baku'],
                        'quantity' => $item['quantity'],
                        'harga' => $item['harga'],
                        'sub_total' => $item['sub_total'],
                        'jenis_transaksi' => 'M',
                        'nobukti' => $nobukti,
                    ]);
                }

                // Finally update pembelian totals and metadata
                $pembelian->update([
                    'total' => $total,
                    'catatan' => $validated['catatan'] ?? null,
                    'id_supplier' => $validated['id_supplier'] ?? null,
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            // Clear session setelah berhasil update
            session()->forget($sessionKey);

            notify()->success('Pembelian berhasil diperbarui');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal memperbarui pembelian: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $pembelian = Pembelian::with('mutasi.bahanBaku.satuan')->findOrFail($id);
            // $transaksi = $pembelian->transaksi->first();

            foreach ($pembelian->mutasi as $mutasi) {

                    $mutasi->delete();

            }

            // if ($pembelian->transaksi->isNotEmpty()) {
            //     if (is_null($transaksi->deleted_at)) {

            //         $transaksi = $pembelian->transaksi->first();
            //         $transaksi->status = 0;
            //         $transaksi->delete();
            //     }
            // }
            // $pembelian->save();
            $pembelian->delete();

            DB::commit();

            notify()->success('Transaksi pembelian berhasil dibatalkan.');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menghapus pembelian: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function cancel($nobukti)
    {
        DB::beginTransaction();

        try {
            $pembelian = Pembelian::where('nobukti', $nobukti)->firstOrFail();
            $pembelian->status = 'cancelled';
            $pembelian->save();
            DB::commit();
            notify()->success('Transaksi pembelian berhasil dibatalkan.');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal membatalkan pembelian: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $pembelian = Pembelian::withTrashed()
                ->with([
                    'mutasi' => fn($q) => $q->withTrashed(),
                    'transaksi' => fn($q) => $q->withTrashed()
                ])
                ->where('nobukti', $id)
                ->firstOrFail();

            $pembelian->status = 'pending';
            $pembelian->restore();

            // Restore mutasi
            foreach ($pembelian->mutasi as $mutasi) {
                $mutasi->restore();
            }

            $transaksi = $pembelian->transaksi->first();
            if ($transaksi) {
                $transaksi->status = 0;
                $transaksi->restore();
            }

            DB::commit();
            notify()->success('Transaksi pembelian berhasil dipulihkan');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal memulihkan pembelian: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    // public function forceDelete($id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $pembelian = Pembelian::withTrashed()
    //             ->with([
    //                 'mutasi' => fn($q) => $q->withTrashed(),
    //                 'transaksi' => fn($q) => $q->withTrashed()->with('sumberDana')
    //             ])
    //             ->findOrFail($id);

    //         $transaksi = $pembelian->transaksi->first();

    //         if ($transaksi && $transaksi->sumberDana) {
    //             $sumberDana = $transaksi->sumberDana;

    //             if (is_null($transaksi->deleted_at)) {
    //                 $sumberDana->saldo_current += $transaksi->jumlah;
    //                 $sumberDana->save();
    //             }
    //         }

    //         foreach ($pembelian->mutasi as $mutasi) {
    //             $mutasi->forceDelete();
    //         }

    //         if ($transaksi) {
    //             $transaksi->forceDelete();
    //         }

    //         $pembelian->forceDelete();

    //         DB::commit();

    //         notify()->success('Data pembelian berhasil dihapus permanen.');
    //         return redirect()->route('pembelian.index');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         notify()->error('Gagal menghapus permanen: ' . $e->getMessage());
    //         return redirect()->back();
    //     }
    // }



    public function laporanPembelian(Request $request)
    {
        $title = 'Laporan Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            // ['label' => 'Laporan Pembelian', 'url' => route('laporan-pembelian')],
            ['label' => 'Laporan Pembelian', 'url' => null],
        ];

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        $laporan_pembelian = collect();
        $showTable = false;

        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            $tanggalSampaiFormatted = Carbon::parse($tanggalSampai)->endOfDay()->toDateTimeString();

            $laporan_pembelian = Mutasi::with(['bahanBaku.satuan', 'pembelian.supplier'])
                ->where('jenis_transaksi', 'M')
                ->where('status', 1)
                ->whereBetween('created_at', [$tanggalMulai, $tanggalSampaiFormatted])
                ->latest()
                ->get();
            $showTable = true;
        }

        return view('pembelian.laporan-pembelian', compact(
            'title',
            'breadcrumbs',
            'laporan_pembelian',
            'showTable',
            'tanggalMulai',
            'tanggalSampai'
        ));
    }

    public function exportPDF(Request $request)
    {
        $title = 'Laporan Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Bahan Baku', 'url' => route('bahan-baku.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        $laporan_pembelian = Mutasi::with(['bahanBaku', 'pembelian.supplier'])
            ->where('jenis_transaksi', 'M')
            ->where('status', 1);

        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            $laporan_pembelian->whereBetween('created_at', [$tanggalMulai, $tanggalSampai]);
            $periode = 'Periode: ' .
                $tanggalMulai->translatedFormat('d M Y') .
                ' - ' .
                $tanggalSampai->translatedFormat('d M Y');
        } else {
            $periode = 'Semua Periode';
        }

        $laporan_pembelian = $laporan_pembelian->latest()->get();
        // dd($laporan_pembelian);
        $pdf = Pdf::loadView('pembelian.pembelian-pdf', [
            'laporan' => $laporan_pembelian,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'periode' => $periode,

        ])->setPaper('A4', 'landscape');


        return $pdf->stream('Laporan_Pembelian.pdf');
    }
}
