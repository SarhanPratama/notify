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
            $bahanBakuQuery = BahanBaku::with(['satuan', 'kategori', 'ViewStok' => function($query) {
                $query->select('id_bahan_baku', 'stok_akhir');
            }])
                ->whereHas('ViewStok', function($query) {
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

            // Ambil data kategori untuk filter (selalu tampilkan semua kategori)
            $kategoris = Kategori::orderBy('nama')->get();

            $title = 'Pesanan Bahan Baku - ' . $outlet->nama;

            return view('outlet.belanja', compact('outlet', 'bahanBaku', 'kategoris', 'token', 'title', 'search', 'categoryId'));
        } catch (\Exception $e) {
            return view('outlet.error', [
                'type' => 'invalid_token',
                'message' => 'Token tidak valid atau outlet tidak aktif',
                'error' => $e->getMessage()
            ]);
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
     * Store outlet order
     */
    public function storeOrder(Request $request, $token)
    {
        try {
            // Validasi token
            $outlet = Outlet::where('barcode_token', $token)
                ->where('barcode_active', true)
                ->firstOrFail();

            $request->validate([
                'cart' => 'required|array',
                'cart.*.id' => 'required|exists:bahan_baku,id',
                'cart.*.quantity' => 'required|integer|min:1',
                'cart.*.harga' => 'required|numeric|min:0',
                'delivery_date' => 'required|date|after:today',
                'notes' => 'nullable|string|max:500'
            ]);

            $items = $request->cart;

            if (empty($items)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada item yang dipilih!'
                ], 400);
            }

            // Server-side stock validation - batch query untuk menghindari N+1
            $itemIds = collect($items)->pluck('id')->unique()->values()->all();
            $bahanBakuStok = BahanBaku::with(['ViewStok' => function($query) {
                $query->select('id_bahan_baku', 'stok_akhir');
            }])
                ->whereIn('id', $itemIds)
                ->get()
                ->keyBy('id');

            $stockProblems = [];
            foreach ($items as $item) {
                $bahanBaku = $bahanBakuStok->get($item['id']);
                $available = 0;
                if ($bahanBaku) {
                    // Prefer viewStok->stok_akhir, fallback to viewStok->saldo or stok_awal
                    if (!empty($bahanBaku->ViewStok)) {
                        $available = $bahanBaku->ViewStok->stok_akhir ?? $bahanBaku->ViewStok->saldo ?? 0;
                    } else {
                        $available = $bahanBaku->stok_awal ?? 0;
                    }

                    if ($item['quantity'] > $available) {
                        $stockProblems[] = [
                            'id' => $item['id'],
                            'nama' => $bahanBaku->nama,
                            'requested' => $item['quantity'],
                            'available' => $available
                        ];
                    }
                }
            }

            if (!empty($stockProblems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi untuk beberapa item.',
                    'errors' => $stockProblems
                ], 422);
            }

            DB::beginTransaction();

            // Hitung total
            $total = collect($items)->sum(function ($item) {
                return $item['harga'] * $item['quantity'];
            });

            // Buat pesanan dengan status pending
            $pesanan = Penjualan::create([
                'nobukti' => $this->generateNoBukti(),
                'id_outlet' => $outlet->id,
                'tanggal' => now(),
                'total' => $total,
                'catatan' => $request->notes,
                'status' => 'pending', // Status pending untuk approval
                'order_via' => 'barcode_scan',
                'tanggal_pengiriman' => $request->delivery_date
            ]);

            // Simpan detail items via mutasi (polymorphic: mutasiable) - gunakan data yang sudah ada
            foreach ($items as $item) {
                $bahanBaku = $bahanBakuStok->get($item['id']);
                if ($bahanBaku) {
                    mutasi::create([
                        'nobukti' => $pesanan->nobukti,
                        'id_bahan_baku' => $item['id'],
                        'quantity' => $item['quantity'],
                        'harga' => $item['harga'],
                        'sub_total' => $item['harga'] * $item['quantity'],
                        'jenis_transaksi' => 'K', // Keluar
                        'status' => 0 // Pending approval (boolean)
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat! Menunggu approval dari gudang.',
                'pesanan_id' => $pesanan->id,
                'nobukti' => $pesanan->nobukti
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
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

            return view('outlet.detail-pesanan', compact('outlet', 'order', 'token', 'title'));
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
                'penjualan.mutasi'
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
                'pembayaran.sumberDana'
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
}
