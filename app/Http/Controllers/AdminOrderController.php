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

        $orders = Penjualan::with('outlet')
            ->where('status_gudang', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pesanan.index', compact('orders', 'title', 'breadcrumbs'));
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
        $order = Penjualan::with('mutasi.bahanBaku.satuan', 'outlet')->findOrFail($id);
        $sumberDana = SumberDana::all();

        return view('pesanan.show', compact('order', 'sumberDana', 'title', 'breadcrumbs'));
    }

    /**
     * Approve order and optionally record payment / piutang
     */
    public function approve(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $order = Penjualan::with('mutasi')->lockForUpdate()->findOrFail($id);
            $user = auth()->user();

            if ($user->hasRole('gudang')) {
                if ($order->status_gudang !== 'pending') {
                    notify()->error('Admin gudang hanya dapat memproses pesanan dengan status_gudang pending.');
                    return redirect()->back();
                }
                $order->status_gudang = 'approved';
                $order->save();
                $order->mutasi()->update(['status' => 1]);
                DB::commit();
                notify()->success('Pesanan berhasil disetujui oleh admin gudang. Menunggu proses keuangan.');
                return redirect()->back();
            } else {
                notify()->error('Anda tidak memiliki izin untuk menyetujui pesanan.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function reject(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $order = Penjualan::with('mutasi')->findOrFail($id);
            $user = auth()->user();

            if ($user->hasRole('gudang')) {
                if ($order->status_gudang !== 'pending') {
                    notify()->error('Admin gudang hanya dapat menolak pesanan dengan status_gudang pending.');
                    return redirect()->back();
                }
                $order->status_gudang = 'rejected';
            } else {
                notify()->error('Anda tidak memiliki izin untuk menolak pesanan.');
                return redirect()->back();
            }

            $order->save();
            $order->mutasi()->update(['status' => 0]);
            DB::commit();
            $message = $user->hasRole('gudang') ?
                'Pesanan berhasil ditolak oleh admin gudang.' :
                'Pesanan berhasil ditolak oleh admin keuangan.';
            return redirect()->route('admin.pesanan.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
