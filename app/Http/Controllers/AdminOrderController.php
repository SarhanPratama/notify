<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\mutasi;
use App\Models\Piutang;
use App\Models\PiutangPembayaran;
use App\Models\Transaksi;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * List orders (admin) - show pending by default
     */
    public function index(Request $request)
    {
        $title = 'Pesanan Outlet';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pesanan Outlet', 'url' => route('admin.pesanan.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];
        $status = $request->get('status', 'pending');

        $orders = Penjualan::with('cabang')
            ->when($status, function ($q) use ($status) {
                if ($status === 'all') return $q;
                return $q->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.orders.index', compact('orders', 'status', 'title', 'breadcrumbs'));
    }

    /**
     * Show single order for review
     */
    public function show($id)
    {
                $title = 'Detail Pesanan Outlet';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pesanan Outlet', 'url' => route('admin.pesanan.index')],
            ['label' => 'Detail', 'url' => null],
        ];
        $order = Penjualan::with('mutasi.bahanBaku.satuan', 'cabang')->findOrFail($id);
        $sumberDana = SumberDana::all();

        return view('admin.orders.show', compact('order', 'sumberDana', 'title', 'breadcrumbs'));
    }

    /**
     * Approve order and optionally record payment / piutang
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'payment_type' => 'required|in:tunai,kasbon,partial',
            'paid_amount' => 'nullable|numeric|min:0',
            'id_sumber_dana' => 'nullable|exists:sumber_dana,id',
            'jatuh_tempo' => 'nullable|date'
        ]);

        DB::beginTransaction();
        try {
            $order = Penjualan::with('mutasi')->lockForUpdate()->findOrFail($id);

            if ($order->status !== 'pending') {
                return redirect()->back()->with('error', 'Hanya pesanan dengan status pending dapat diproses.');
            }

            $order->status = 'approved';
            $order->metode_pembayaran = $request->payment_type;
            $order->save();

            // Mark mutasi as approved (status = 1)
            $order->mutasi()->update(['status' => 1]);

            $paid = (float) ($request->paid_amount ?? 0);

            if ($request->payment_type === 'tunai') {
                // record transaksi
                $order->transaksi()->create([
                    'id_sumber_dana' => $request->id_sumber_dana ?? null,
                    'tanggal' => now(),
                    'tipe' => 'in',
                    'jumlah' => $paid > 0 ? $paid : $order->total,
                    'deskripsi' => 'Pembayaran tunai untuk pesanan ' . $order->nobukti,
                    'status' => 1,
                ]);

                // if fully paid -> completed
                if ($paid >= $order->total || $paid == 0) {
                    $order->status = 'completed';
                    $order->save();
                }
            } else {
                // kasbon or partial -> create piutang
                $piutang = Piutang::create([
                    'nobukti' => $order->nobukti,
                    'jumlah_piutang' => $order->total,
                    'jatuh_tempo' => $request->jatuh_tempo ?? now()->addDays(14),
                    'status' => 'belum_lunas'
                ]);

                if ($paid > 0) {
                    PiutangPembayaran::create([
                        'id_piutang' => $piutang->id,
                        'id_sumber_dana' => $request->id_sumber_dana ?? null,
                        'tanggal' => now(),
                        'jumlah' => $paid,
                        'keterangan' => 'Pembayaran sebagian untuk ' . $order->nobukti
                    ]);

                    $totalPaid = $piutang->pembayaran()->sum('jumlah');
                    if ($totalPaid >= $piutang->jumlah_piutang) {
                        $piutang->status = 'lunas';
                        $piutang->save();
                        $order->status = 'completed';
                        $order->save();
                    }
                }
            }

            DB::commit();

            return redirect()->route('penjualan.index')->with('success', 'Pesanan berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject order
     */
    public function reject(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $order = Penjualan::with('mutasi')->findOrFail($id);
            if ($order->status !== 'pending') {
                return redirect()->back()->with('error', 'Hanya pesanan dengan status pending dapat ditolak.');
            }

            $order->status = 'rejected';
            $order->save();

            // mark mutasi as rejected (status = 2)
            $order->mutasi()->update(['status' => 2]);

            DB::commit();

            return redirect()->route('penjualan.index')->with('success', 'Pesanan berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
