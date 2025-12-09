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

        $user = auth()->user();
        $status = $request->get('status', 'pending');

        // Filter berdasarkan role user
        if ($user->hasRole('gudang')) {
            // Admin gudang hanya melihat pesanan pending
            $allowedStatuses = ['pending'];
        } elseif ($user->hasRole('keuangan')) {
            // Admin keuangan melihat pesanan yang sudah disetujui gudang atau pending (untuk backward compatibility)
            $allowedStatuses = ['approved_by_gudang', 'pending'];
            // Default status untuk admin keuangan adalah approved_by_gudang
            if (!$request->has('status')) {
                $status = 'approved_by_gudang';
            }
        } elseif ($user->hasRole('owner')) {
            // Owner melihat semua status
            $allowedStatuses = ['pending', 'approved_by_gudang', 'approved', 'rejected', 'rejected_by_gudang', 'completed'];
        } else {
            // Default - tidak ada akses
            $allowedStatuses = [];
        }

        // Jika status yang diminta tidak diizinkan untuk role ini, redirect ke status pertama yang diizinkan
        if (!in_array($status, $allowedStatuses)) {
            $status = $allowedStatuses[0] ?? 'pending';
        }

        $orders = Penjualan::with('outlet')
            ->whereIn('status', $allowedStatuses)
            ->when($status !== 'all', function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pesanan.index', compact('orders', 'status', 'title', 'breadcrumbs', 'allowedStatuses'));
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

            // Cek role dan status yang diizinkan
            if ($user->hasRole('gudang')) {
                // Admin gudang hanya bisa approve pesanan pending (verifikasi stok)
                if ($order->status !== 'pending') {
                    notify()->error('Admin gudang hanya dapat memproses pesanan dengan status pending.');
                    return redirect()->back();
                }

                // Admin gudang hanya verifikasi stok, tidak handle pembayaran
                $order->status = 'approved_by_gudang';
                $order->save();

                // Mark mutasi as approved by gudang (status = 1)
                $order->mutasi()->update(['status' => 1]);

                DB::commit();
                notify()->success('Pesanan berhasil disetujui oleh admin gudang. Menunggu approval admin keuangan.');
                return redirect()->route('admin.pesanan.index', ['status' => 'pending']);
            } elseif ($user->hasRole('keuangan') || $user->hasRole('owner')) {
                // Validasi input untuk admin keuangan
                $request->validate([
                    'payment_type' => 'required|in:tunai,kasbon',
                    'paid_amount' => 'nullable|numeric|min:0',
                    'id_sumber_dana' => 'nullable|exists:sumber_dana,id',
                    'jatuh_tempo' => 'nullable|date'
                ]);

                // Admin keuangan/owner bisa approve final
                if ($order->status !== 'approved_by_gudang' && $order->status !== 'pending') {
                    notify()->error('Hanya pesanan yang sudah disetujui gudang atau pending dapat diproses.');
                    return redirect()->back();
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
                        'nobukti' => $order->nobukti,
                        'id_sumber_dana' => $request->id_sumber_dana ?? null,
                        'tanggal' => now(),
                        'tipe' => 'debit',
                        'jumlah' => $paid > 0 ? $paid : $order->total,
                        'deskripsi' => 'Pembayaran tunai untuk pesanan ' . $order->nobukti,
                        'status' => 1,
                    ]);

                    // if fully paid -> completed
                    if ($paid >= $order->total || $paid == 0) {
                        $order->status = 'completed';
                        $order->save();
                    }
                    DB::commit();
                    notify()->success('Pesanan berhasil diproses dengan pembayaran tunai.');
                    return redirect()->route('admin.pesanan.index');
                } else {
                    // kasbon or partial -> create piutang
                    $piutang = Piutang::create([
                        'nobukti' => $order->nobukti,
                        'jumlah_piutang' => $order->total,
                        'sisa_piutang' => $order->total - $paid,
                        'jatuh_tempo' => $request->jatuh_tempo ?? now()->addDays(3),
                        'status' => 'belum_lunas'
                    ]);

                    if ($paid > 0) {
                        PiutangPembayaran::create([
                            'nobukti' => $piutang->nobukti,
                            'id_piutang' => $piutang->id,
                            'id_sumber_dana' => $request->id_sumber_dana ?? null,
                            'tanggal' => now(),
                            'jumlah' => $paid,
                            'keterangan' => 'Pembayaran kasbon untuk pesanan ' . $order->nobukti
                        ]);

                        // Juga catat di table transaksi untuk konsistensi
                        Transaksi::create([
                            'nobukti' => $piutang->nobukti,
                            'id_sumber_dana' => $request->id_sumber_dana ?? null,
                            'tanggal' => now(),
                            'tipe' => 'debit',
                            'jumlah' => $paid,
                            'deskripsi' => 'Pembayaran kasbon untuk pesanan ' . $order->nobukti,
                            'status' => 1,
                        ]);
                    }

                    DB::commit();
                    notify()->success('Pesanan berhasil diproses dengan pembayaran kasbon.');
                    return redirect()->route('admin.pesanan.index');
                }
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

    /**
     * Reject order
     */
    public function reject(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $order = Penjualan::with('mutasi')->findOrFail($id);
            $user = auth()->user();

            // Cek role dan status yang diizinkan
            if ($user->hasRole('gudang')) {
                // Admin gudang hanya bisa reject pesanan pending
                if ($order->status !== 'pending') {
                    notify()->error('Admin gudang hanya dapat menolak pesanan dengan status pending.');
                    return redirect()->back();
                }
                $order->status = 'rejected_by_gudang';
            } elseif ($user->hasRole('keuangan') || $user->hasRole('owner')) {
                // Admin keuangan/owner bisa reject pesanan approved_by_gudang atau pending
                if ($order->status !== 'approved_by_gudang' && $order->status !== 'pending') {
                    notify()->error('Hanya pesanan yang sudah disetujui gudang atau pending dapat ditolak.');
                    return redirect()->back();
                }
                $order->status = 'rejected';
            } else {
                notify()->error('Anda tidak memiliki izin untuk menolak pesanan.');
                return redirect()->back();
            }

            $order->save();

            // mark mutasi as rejected (status = 2)
            $order->mutasi()->update(['status' => 2]);

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
