<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\BahanBaku;
use App\Models\Penjualan;
use App\Models\mutasi;
use App\Models\Outlet;
use App\Models\Piutang;
use App\Models\PiutangPembayaran;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class OutletOrderController extends Controller
{
    /**
     * Show order form for outlet via barcode scan
     */
    public function belanja(Request $request, $token)
    {
        try {
            // Validasi token dan ambil data outlet
            $outlet = Outlet::where('barcode_token', $token)
                ->where('barcode_active', true)
                ->firstOrFail();

            // Ambil parameter filter
            $search = $request->get('search', '');
            $categoryId = $request->get('category', '');

            // Query bahan baku dengan filter
            $bahanBakuQuery = BahanBaku::with(['satuan', 'kategori', 'viewStok' => function($query) {
                $query->select('id_bahan_baku', 'stok_akhir');
            }])
                ->whereHas('viewStok', function($query) {
                    $query->where('stok_akhir', '>', 0);
                });

            // Filter berdasarkan search
            if (!empty($search)) {
                $bahanBakuQuery->where('nama', 'like', '%' . $search . '%');
            }

            // Filter berdasarkan kategori
            if (!empty($categoryId)) {
                $bahanBakuQuery->where('id_kategori', $categoryId);
            }

            $bahanBaku = $bahanBakuQuery->get();

            // dd($bahanBaku);

            // Ambil data kategori untuk filter (selalu tampilkan semua kategori)
            $kategoris = Kategori::orderBy('nama')->get();

            // Session key untuk cart
            $sessionKey = 'outlet_cart_' . $token;

            // Clear cart jika bukan dari operasi cart (fresh page load/reload)
            $previousUrl = url()->previous();
            $isFromCartOperation = str_contains($previousUrl, $token . '/cart') || str_contains($previousUrl, $token . '/belanja');
            if (!$isFromCartOperation) {
                session()->forget($sessionKey);
            }

            // Ambil cart dari session
            $cartItems = session($sessionKey, []);

            $title = 'Pesanan Bahan Baku - ' . $outlet->nama;

            return view('outlet.belanja', compact('outlet', 'bahanBaku', 'kategoris', 'token', 'title', 'search', 'categoryId', 'cartItems'));
        } catch (\Exception $e) {
            return view('outlet.error', [
                'type' => 'invalid_token',
                'message' => 'Token tidak valid atau outlet tidak aktif',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Add item to cart session
     */
    public function addToCart(Request $request, $token)
    {
        try {
            // Validasi token
            $outlet = Outlet::where('barcode_token', $token)
                ->where('barcode_active', true)
                ->firstOrFail();

            $request->validate([
                'id_bahan_baku' => 'required|exists:bahan_baku,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $sessionKey = 'outlet_cart_' . $token;
            $cartItems = session($sessionKey, []);

            $bahanBaku = BahanBaku::with(['satuan', 'viewStok'])->find($request->id_bahan_baku);
            $stokTersedia = $bahanBaku->viewStok->stok_akhir ?? 0;

            // Cek stok
            if ($request->quantity > $stokTersedia) {
                return redirect()->route('outlet.belanja', $token)
                    ->with('error', "Stok {$bahanBaku->nama} tidak mencukupi. Tersedia: {$stokTersedia}");
            }

            // Cek apakah item sudah ada di cart
            $existingIndex = null;
            foreach ($cartItems as $index => $item) {
                if ($item['id_bahan_baku'] == $request->id_bahan_baku) {
                    $existingIndex = $index;
                    break;
                }
            }

            if ($existingIndex !== null) {
                // Update quantity jika sudah ada
                $newQty = $cartItems[$existingIndex]['quantity'] + $request->quantity;
                if ($newQty > $stokTersedia) {
                    return redirect()->route('outlet.belanja', $token)
                        ->with('error', "Total quantity melebihi stok. Tersedia: {$stokTersedia}");
                }
                $cartItems[$existingIndex]['quantity'] = $newQty;
                $cartItems[$existingIndex]['sub_total'] = $newQty * $cartItems[$existingIndex]['harga'];
            } else {
                // Tambah item baru
                $cartItems[] = [
                    'id_bahan_baku' => $request->id_bahan_baku,
                    'nama_bahan_baku' => $bahanBaku->nama,
                    'satuan' => $bahanBaku->satuan->nama ?? '',
                    'quantity' => $request->quantity,
                    'harga' => $bahanBaku->harga,
                    'sub_total' => $request->quantity * $bahanBaku->harga,
                    'stok_tersedia' => $stokTersedia,
                ];
            }

            session([$sessionKey => $cartItems]);

            return redirect()->route('outlet.belanja', $token)
                ->with('success', "{$bahanBaku->nama} berhasil ditambahkan ke keranjang");

        } catch (\Exception $e) {
            return redirect()->route('outlet.belanja', $token)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateCartItem(Request $request, $token, $index)
    {
        try {
            $outlet = Outlet::where('barcode_token', $token)
                ->where('barcode_active', true)
                ->firstOrFail();

            $request->validate([
                'quantity' => 'required|integer|min:0',
            ]);

            $sessionKey = 'outlet_cart_' . $token;
            $cartItems = session($sessionKey, []);

            if (isset($cartItems[$index])) {
                // Jika quantity 0, hapus item dari cart
                if ($request->quantity <= 0) {
                    unset($cartItems[$index]);
                    $cartItems = array_values($cartItems);
                    session([$sessionKey => $cartItems]);
                    return redirect()->route('outlet.belanja', $token)
                        ->with('success', 'Item berhasil dihapus dari keranjang');
                }

                $bahanBaku = BahanBaku::with('viewStok')->find($cartItems[$index]['id_bahan_baku']);
                $stokTersedia = $bahanBaku->viewStok->stok_akhir ?? 0;

                if ($request->quantity > $stokTersedia) {
                    return redirect()->route('outlet.belanja', $token)
                        ->with('error', "Quantity melebihi stok. Tersedia: {$stokTersedia}");
                }

                $cartItems[$index]['quantity'] = $request->quantity;
                $cartItems[$index]['sub_total'] = $request->quantity * $cartItems[$index]['harga'];
                session([$sessionKey => $cartItems]);

                return redirect()->route('outlet.belanja', $token)
                    ->with('success', 'Keranjang berhasil diupdate');
            }

            return redirect()->route('outlet.belanja', $token)
                ->with('error', 'Item tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('outlet.belanja', $token)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove item from cart session
     */
    public function removeFromCart($token, $index)
    {
        try {
            $outlet = Outlet::where('barcode_token', $token)
                ->where('barcode_active', true)
                ->firstOrFail();

            $sessionKey = 'outlet_cart_' . $token;
            $cartItems = session($sessionKey, []);

            if (isset($cartItems[$index])) {
                $nama = $cartItems[$index]['nama_bahan_baku'];
                unset($cartItems[$index]);
                $cartItems = array_values($cartItems);
                session([$sessionKey => $cartItems]);

                return redirect()->route('outlet.belanja', $token)
                    ->with('success', "{$nama} berhasil dihapus dari keranjang");
            }

            return redirect()->route('outlet.belanja', $token)
                ->with('error', 'Item tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('outlet.belanja', $token)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Clear cart session
     */
    public function clearCart($token)
    {
        try {
            $outlet = Outlet::where('barcode_token', $token)
                ->where('barcode_active', true)
                ->firstOrFail();

            $sessionKey = 'outlet_cart_' . $token;
            session()->forget($sessionKey);

            return redirect()->route('outlet.belanja', $token)
                ->with('success', 'Keranjang berhasil dikosongkan');

        } catch (\Exception $e) {
            return redirect()->route('outlet.belanja', $token)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function pesanan($token)
    {
        try {
            $outlet = Outlet::where('barcode_token', $token)
                ->where('barcode_active', true)
                ->firstOrFail();

            // Ambil riwayat pesanan outlet ini
            $orders = Penjualan::where('id_outlet', $outlet->id)
                ->with('piutang')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $title = 'Riwayat Pesanan - ' . $outlet->nama;

            return view('outlet.pesanan', compact('outlet', 'orders', 'token', 'title'));
        } catch (\Exception $e) {
            return view('outlet.error', [
                'type' => 'invalid_token',
                'message' => 'Token tidak valid atau outlet tidak aktif',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store outlet order (using session cart)
     */
    public function storeOrder(Request $request, $token)
    {
        try {
            // Validasi token
            $outlet = Outlet::where('barcode_token', $token)
                ->where('barcode_active', true)
                ->firstOrFail();



            // Ambil cart dari session
            $sessionKey = 'outlet_cart_' . $token;
            $cartItems = session($sessionKey, []);

            if (empty($cartItems)) {
                return redirect()->route('outlet.belanja', $token)
                    ->with('error', 'Keranjang masih kosong! Tambahkan item terlebih dahulu.');
            }

            // Server-side stock validation
            $itemIds = collect($cartItems)->pluck('id_bahan_baku')->unique()->values()->all();
            $bahanBakuStok = BahanBaku::with(['ViewStok' => function($query) {
                $query->select('id_bahan_baku', 'stok_akhir');
            }])
                ->whereIn('id', $itemIds)
                ->get()
                ->keyBy('id');

            $stockProblems = [];
            foreach ($cartItems as $item) {
                $bahanBaku = $bahanBakuStok->get($item['id_bahan_baku']);
                $available = 0;
                if ($bahanBaku) {
                    if (!empty($bahanBaku->ViewStok)) {
                        $available = $bahanBaku->ViewStok->stok_akhir ?? $bahanBaku->ViewStok->saldo ?? 0;
                    } else {
                        $available = $bahanBaku->stok_awal ?? 0;
                    }

                    if ($item['quantity'] > $available) {
                        $stockProblems[] = "{$item['nama_bahan_baku']}: diminta {$item['quantity']}, tersedia {$available}";
                    }
                }
            }

            if (!empty($stockProblems)) {
                return redirect()->route('outlet.belanja', $token)
                    ->with('error', 'Stok tidak mencukupi: ' . implode(', ', $stockProblems));
            }

            DB::beginTransaction();

            // Hitung total
            $total = collect($cartItems)->sum('sub_total');

            // Buat pesanan dengan status pending
            $pesanan = Penjualan::create([
                'nobukti' => $this->generateNoBukti(),
                'id_outlet' => $outlet->id,
                'tanggal' => now(),
                'total' => $total,
                // 'status' => 'pending',
            ]);

            // Simpan detail items via mutasi
            foreach ($cartItems as $item) {
                mutasi::create([
                    'nobukti' => $pesanan->nobukti,
                    'id_bahan_baku' => $item['id_bahan_baku'],
                    'quantity' => $item['quantity'],
                    'harga' => $item['harga'],
                    'sub_total' => $item['sub_total'],
                    'jenis_transaksi' => 'K',
                    // 'status' => 0
                ]);
            }

            DB::commit();

            // Clear session cart setelah berhasil
            session()->forget($sessionKey);

            return redirect()->route('outlet.pesanan', $token)
                ->with('success', 'Pesanan berhasil dibuat! Menunggu approval dari gudang. No. Bukti: ' . $pesanan->nobukti);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $errors = collect($e->errors())->flatten()->implode(', ');
            return redirect()->route('outlet.belanja', $token)
                ->with('error', 'Validasi gagal: ' . $errors);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('outlet.belanja', $token)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show order detail
     */
    public function detailPesanan($token, $orderId)
    {
        try {
            $outlet = Outlet::where('barcode_token', $token)
                ->where('barcode_active', true)
                ->firstOrFail();

            $order = Penjualan::where('id', $orderId)
                ->where('id_outlet', $outlet->id)
                ->with(['mutasi.bahanBaku.satuan', 'piutang'])
                ->firstOrFail();

            $title = 'Detail Pesanan - ' . $order->nobukti;

            return view('outlet.pesanan-detail', compact('outlet', 'order', 'token', 'title'));
        } catch (\Exception $e) {
            return view('outlet.error', [
                'type' => 'system_error',
                'message' => 'Pesanan tidak ditemukan',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate nomor bukti unik
     */
    private function generateNoBukti()
    {
        $date = date('Ymd');
        $lastOrder = Penjualan::whereDate('created_at', today())
            ->where('nobukti', 'like', 'OUT-' . $date . '%')
            ->count();

        return 'OUT-' . $date . '-' . str_pad($lastOrder + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Show tagihan/piutang page for outlet
     */
    public function kasbon($token)
    {
        try {
            // Validasi token outlet
            $outlet = Outlet::where('barcode_token', $token)
                ->where('barcode_active', true)
                ->firstOrFail();

            // Ambil semua piutang untuk outlet ini (tanpa filter/search), urutkan by jatuh tempo
            $piutangs = Piutang::with([
                'penjualan.mutasi',
                'pembayaran'
            ])->whereHas('penjualan', function ($q) use ($outlet) {
                $q->where('id_outlet', $outlet->id);
            })
                ->orderBy('jatuh_tempo', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

            // Hitung total piutang berdasarkan status untuk summary
            $totalPiutang = Piutang::whereHas('penjualan', function ($query) use ($outlet) {
                $query->where('id_outlet', $outlet->id);
            })->sum('jumlah_piutang');

            $belumDibayar = Piutang::whereHas('penjualan', function ($query) use ($outlet) {
                $query->where('id_outlet', $outlet->id);
            })->where('status', 'belum_lunas')->sum('sisa_piutang');

            $sudahDibayar = Piutang::whereHas('penjualan', function ($query) use ($outlet) {
                $query->where('id_outlet', $outlet->id);
            })->where('status', 'lunas')->sum('jumlah_piutang');

            // Hitung jatuh tempo (piutang yang sudah lewat tanggal jatuh tempo)
            $jatuhTempo = Piutang::whereHas('penjualan', function ($query) use ($outlet) {
                $query->where('id_outlet', $outlet->id);
            })
                ->where('status', 'belum_lunas')
                ->where('jatuh_tempo', '<', now())
                ->sum('sisa_piutang');

            // Hitung total yang sudah dibayar dari semua pembayaran
            $totalDibayar = PiutangPembayaran::whereHas('piutang.penjualan', function ($query) use ($outlet) {
                $query->where('id_outlet', $outlet->id);
            })->sum('jumlah');

            $title = 'Tagihan & Piutang - ' . $outlet->nama;

            return view('outlet.kasbon', compact(
                'outlet',
                'piutangs',
                'totalPiutang',
                'belumDibayar',
                'sudahDibayar',
                'jatuhTempo',
                'totalDibayar',
                'token',
                'title'
            ));
        } catch (\Exception $e) {
            return view('outlet.error', [
                'type' => 'system_error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show single piutang (kasbon) detail page (separate view, no modal)
     */
    public function kasbonDetail($token, $piutangId)
    {
        try {
            // Validasi token outlet
            $outlet = Outlet::where('barcode_token', $token)
                ->where('barcode_active', true)
                ->firstOrFail();

            // Ambil piutang dengan relasi
            $piutang = Piutang::with([
                'penjualan.mutasi.bahanBaku.satuan',
                'pembayaran'
            ])->whereHas('penjualan', function ($q) use ($outlet) {
                $q->where('id_outlet', $outlet->id);
            })->findOrFail($piutangId);

            $totalBayar = $piutang->pembayaran->sum('jumlah');
            $sisaPiutang = $piutang->sisa_piutang;
            $persenBayar = $piutang->jumlah_piutang > 0 ? ($totalBayar / $piutang->jumlah_piutang) * 100 : 0;
            $isJatuhTempo = $piutang->jatuh_tempo < now() && $piutang->status != 'lunas';

            $title = 'Detail Tagihan - ' . $piutang->nobukti;

            return view('outlet.kasbon-detail', compact(
                'outlet',
                'piutang',
                'totalBayar',
                'sisaPiutang',
                'persenBayar',
                'isJatuhTempo',
                'token',
                'title'
            ));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return view('outlet.error', [
                'type' => 'system_error',
                'message' => 'Tagihan tidak ditemukan',
                'error' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            return view('outlet.error', [
                'type' => 'system_error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ]);
        }
    }
    public function downloadInvoice($token, $id)
    {
        try {
            // Validasi token outlet
            $outlet = Outlet::where('barcode_token', $token)
                ->where('barcode_active', true)
                ->firstOrFail();

            // Ambil piutang dengan relasi
            $piutang = Piutang::with([
                'penjualan.outlet',
                'penjualan.mutasi.bahanBaku.satuan',
                'pembayaran'
            ])->whereHas('penjualan', function ($q) use ($outlet) {
                $q->where('id_outlet', $outlet->id);
            })->findOrFail($id);

            $totalBayar = $piutang->pembayaran->sum('jumlah');
            $sisaPiutang = $piutang->sisa_piutang;

            $pdf = Pdf::loadView('outlet.invoice-pdf', compact(
                'piutang',
                'outlet',
                'totalBayar',
                'sisaPiutang'
            ))->setPaper('a4', 'portrait');

            return $pdf->download('Invoice-' . $piutang->nobukti . '.pdf');
        } catch (\Exception $e) {
            notify()->error('Gagal mengunduh invoice: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function cancelPesanan($token, $orderId)
    {
        try {
            $outlet = Outlet::where('barcode_token', $token)
                ->where('barcode_active', true)
                ->firstOrFail();

            $order = Penjualan::where('id', $orderId)
                ->where('id_outlet', $outlet->id)
                ->firstOrFail();

            // Check if order can be cancelled
            if ($order->status_gudang !== 'pending' && $order->status_keuangan !== 'pending') {
                return redirect()->route('outlet.pesanan', $token)->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
            }

            // Update status to cancelled or delete
            $order->update([
                'status_gudang' => 'cancelled',
                'status_keuangan' => 'cancelled'
            ]);

            return redirect()->route('outlet.pesanan', $token)->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            return redirect()->route('outlet.pesanan', $token)->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }
}
