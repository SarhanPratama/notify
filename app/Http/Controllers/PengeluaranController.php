<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pembelian;
use App\Models\KategoriKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{
    public function index()
    {
        $title = 'Pengeluaran Kas';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pengeluaran', 'url' => route('pengeluaran.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $pengeluaran = Transaksi::with('kategoriKeuangan')
            ->whereHas('kategoriKeuangan', function ($q) {
                $q->where('jenis', 'pengeluaran');
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        $pembelianPending = Pembelian::with(['supplier', 'mutasi.bahanBaku.satuan'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = KategoriKeuangan::whereIn('jenis', ['pengeluaran'])
            ->where('nama', '!=', 'Pembelian BB')
            ->get();

        return view('pengeluaran.index', compact('title', 'breadcrumbs', 'pengeluaran', 'pembelianPending', 'categories'));
    }

    public function approvePembelian(Request $request, $id)
    {
        // dd($request);
        $request->validate([
            'posisi_kas' => 'required|in:Tunai,Bank BSI',
        ]);

        DB::beginTransaction();

        try {
            $pembelian = Pembelian::with('mutasi.bahanBaku')->findOrFail($id);

            // Update status pembelian
            $pembelian->update([
                'status' => 'approved',
            ]);

            // Update status mutasi (aktifkan sehingga ikut hitung stok)
            $pembelian->mutasi()->update([
                'status' => 1,
            ]);

            // Prepare deskripsi dari nama bahan
            $namaBahan = [];
            foreach ($pembelian->mutasi as $mutasi) {
                $namaBahan[] = $mutasi->bahanBaku->nama ?? null;
            }
            $deskripsi = trim(implode(', ', array_filter($namaBahan)));

            // Pastikan kategori keuangan 'Pembelian BB' ada
            $kategoriPembelian = KategoriKeuangan::firstOrCreate(
                ['nama' => 'Pembelian BB'],
                ['jenis' => 'pengeluaran']
            );

            // Catat transaksi polymorphic pada pembelian (pengeluaran)
            $pembelian->transaksi()->create([
                'nobukti' => $pembelian->nobukti,
                'tanggal' => now(),
                'jumlah' => $pembelian->total,
                'tipe' => 'debit',
                'id_kategori_keuangan' => $kategoriPembelian->id,
                'posisi_kas' => $request->posisi_kas,
                'deskripsi' => 'Pembelian bahan baku ' . $deskripsi,
                'status' => 1,
            ]);

            DB::commit();
            notify()->success('Pembelian berhasil disetujui dan dicatat ke transaksi.');
            return redirect()->route('pengeluaran.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menyetujui pembelian: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function rejectPembelian($id)
    {
        DB::beginTransaction();
        try {
            $pembelian = Pembelian::findOrFail($id);

            $pembelian->update([
                'status' => 'rejected',
            ]);

            $pembelian->mutasi()->update([
                'status' => 0,
            ]);

            DB::commit();
            notify()->success('Pembelian berhasil ditolak.');
            return redirect()->route('pengeluaran.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menolak pembelian: ' . $e->getMessage());
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
            $nobukti = 'OUT-' . date('Ymd-His');

            Transaksi::create([
                'nobukti' => $nobukti,
                'tanggal' => $request->tanggal,
                'jumlah' => $request->jumlah,
                'tipe' => 'debit',
                'posisi_kas' => $request->posisi_kas,
                'transaksiable_id' => null,
                'transaksiable_type' => null,
                'deskripsi' => strip_tags($request->deskripsi),
                'id_kategori_keuangan' => $request->id_kategori_keuangan,
                'status' => 1,
            ]);

            DB::commit();
            notify()->success('Pengeluaran berhasil ditambahkan.');
            return redirect()->route('pengeluaran.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menyimpan pengeluaran: ' . $e->getMessage());
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
            notify()->success('Pengeluaran berhasil diperbarui.');
            return redirect()->route('pengeluaran.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal memperbarui pengeluaran: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);

            // Jika transaksi terkait dengan pembelian, cancel pembelian
            if ($transaksi->transaksiable_type === Pembelian::class) {
                $pembelian = $transaksi->transaksiable;
                if ($pembelian) {
                    $pembelian->update(['status' => 'cancelled']);
                    $pembelian->mutasi()->update(['status' => 0]);
                }
            }

            $transaksi->update(['status' => 0]);

            DB::commit();
            notify()->success('Pengeluaran berhasil dihapus.');
            return redirect()->route('pengeluaran.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menghapus pengeluaran: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
