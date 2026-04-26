<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Penjualan;
use App\Models\Piutang;
use App\Models\PiutangPembayaran;
use App\Models\KategoriKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemasukanController extends Controller
{
    public function index()
    {
        $title = 'Pemasukan Kas';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pemasukan', 'url' => route('pemasukan.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $pemasukan = Transaksi::with('kategoriKeuangan', 'transaksiable')
        ->whereHas('kategoriKeuangan', function ($q) {
                $q->where('jenis', 'pemasukan');
            })
        ->orderBy('tanggal', 'desc')
            ->get();

        $penjualanPending = Penjualan::with(['outlet', 'mutasi.bahanBaku.satuan'])
            ->where('status_gudang', 'approved')
            ->where('status_keuangan', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($penjualanPending);

        $categories = KategoriKeuangan::whereIn('jenis', ['pemasukan'])
            ->where('nama', '!=', 'Penjualan BB')
            ->get();

        return view('pemasukan.index', compact('title', 'breadcrumbs', 'pemasukan', 'penjualanPending', 'categories'));
    }

    public function approvePenjualan($id)
    {
        DB::beginTransaction();

        try {
            $penjualan = Penjualan::findOrFail($id);

            // Update status penjualan
            $penjualan->update([
                'status_keuangan' => 'approved',
            ]);

            Piutang::create([
                'nobukti' => $penjualan->nobukti,
                'jumlah_piutang' => $penjualan->total,
                'sisa_piutang' => $penjualan->total,
                'jatuh_tempo' => now()->addDays(7),
            ]);

            DB::commit();
            notify()->success('Penjualan berhasil disetujui dan masuk ke piutang.');
            return redirect()->route('pemasukan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menyetujui penjualan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function rejectPenjualan($id)
    {
        DB::beginTransaction();
        try {
            $penjualan = Penjualan::findOrFail($id);

            $penjualan->update([
                'status_keuangan' => 'rejected',
            ]);

            DB::commit();
            notify()->success('Penjualan berhasil ditolak.');
            return redirect()->route('pemasukan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menolak penjualan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'deskripsi' => 'required|string|max:255',
            'id_kategori_keuangan' => 'nullable|exists:kategori_keuangan,id',
            'posisi_kas' => 'required|in:Tunai,Bank BSI',
        ]);

        DB::beginTransaction();

        try {
            $nobukti = 'IN-' . date('Ymd-His');

            Transaksi::create([
                'nobukti' => $nobukti,
                'tanggal' => $request->tanggal,
                'jumlah' => $request->jumlah,
                'tipe' => 'kredit',
                'posisi_kas' => $request->posisi_kas,
                'transaksiable_id' => null,
                'transaksiable_type' => null,
                'deskripsi' => strip_tags($request->deskripsi),
                'id_kategori_keuangan' => $request->id_kategori_keuangan,
                'status' => 1,
            ]);

            DB::commit();
            notify()->success('Pemasukan berhasil ditambahkan.');
            return redirect()->route('pemasukan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menyimpan pemasukan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'deskripsi' => 'required|string|max:255',
            'id_kategori_keuangan' => 'nullable|exists:kategori_keuangan,id',
            'posisi_kas' => 'required|in:Tunai,Bank BSI',
        ]);

        DB::beginTransaction();

        try {
            $transaksi = Transaksi::findOrFail($id);

            $transaksi->update([
                'tanggal' => $request->tanggal,
                'jumlah' => $request->jumlah,
                'deskripsi' => $request->deskripsi,
                'id_kategori_keuangan' => $request->id_kategori_keuangan,
                'posisi_kas' => $request->posisi_kas,
            ]);

            DB::commit();
            notify()->success('Pemasukan berhasil diperbarui.');
            return redirect()->route('pemasukan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal memperbarui pemasukan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);

            // Jika transaksi berasal dari pembayaran piutang (PiutangPembayaran), rollback pembayaran
            if ($transaksi->transaksiable_type === 'App\Models\PiutangPembayaran') {
                // Update sisa_piutang di piutang
                $piutangPembayaran = $transaksi->transaksiable;
                if ($piutangPembayaran) {
                    $piutang = Piutang::where('nobukti', $piutangPembayaran->nobukti)->first();
                    if ($piutang) {
                        $piutang->update(['sisa_piutang' => $piutang->sisa_piutang + $transaksi->jumlah]);
                    }
                }
                // Hapus pembayaran
                $piutangPembayaran->delete();
            }

            $transaksi->delete();

            DB::commit();
            notify()->success('Pemasukan berhasil dihapus.');
            return redirect()->route('pemasukan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menghapus pemasukan: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
