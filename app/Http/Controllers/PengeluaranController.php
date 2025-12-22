<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Transaksi;
use App\Models\SumberDana;
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

        $pengeluaran = Pengeluaran::with('kategoriKeuangan')
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
            $pembelian = Pembelian::findOrFail($id);

            // Update status pembelian
            $pembelian->update([
                'status' => 'approved',
            ]);

            // Update status mutasi
            $pembelian->mutasi()->update([
                'status' => 1,
            ]);

            $kategoriPembelian = KategoriKeuangan::firstOrCreate(
                ['nama' => 'Pembelian BB'],
                ['jenis' => 'pengeluaran']
            );

            foreach ($pembelian->mutasi as $mutasi) {
                $namaBahan[] = $mutasi->bahanBaku->nama;
            }

            $deskripsi = implode(', ', $namaBahan);

            // Catat sebagai Pengeluaran
            Pengeluaran::create([
                'nobukti' => $pembelian->nobukti,
                'tanggal' => now(),
                'jumlah' => $pembelian->total,
                'status' => 1,
                'deskripsi' => $deskripsi,
                'posisi_kas' => $request->posisi_kas,
                'id_kategori_keuangan' => $kategoriPembelian->id,
            ]);

            // Catat di Transaksi
            Transaksi::create([
                'nobukti' => $pembelian->nobukti,
                'tanggal' => now(),
                'jumlah' => $pembelian->total,
                'deskripsi' => $deskripsi,
                'posisi_kas' => $request->posisi_kas,
                'id_kategori_keuangan' => $kategoriPembelian->id,
                'status' => 1,
            ]);

            DB::commit();
            notify()->success('Pembelian berhasil disetujui dan dicatat sebagai pengeluaran.');
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

            $pengeluaran = Pengeluaran::create([
                'nobukti' => $nobukti,
                'tanggal' => $request->tanggal,
                'jumlah' => $request->jumlah,
                'deskripsi' => strip_tags($request->deskripsi),
                'id_kategori_keuangan' => $request->id_kategori_keuangan,
                'posisi_kas' => $request->posisi_kas,
            ]);

            Transaksi::create([
                'nobukti' => $pengeluaran->nobukti,
                'tanggal' => $request->tanggal,
                'jumlah' => $request->jumlah,
                'deskripsi' => strip_tags($request->deskripsi),
                'id_kategori_keuangan' => $request->id_kategori_keuangan,
                'posisi_kas' => $request->posisi_kas,
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
            'id_kategori_keuangan' => 'nullable|exists:kategori_keuangans,id',
            'posisi_kas' => 'required|in:Tunai,Bank BSI',
        ]);

        DB::beginTransaction();

        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $nobukti = $pengeluaran->nobukti;

            $pengeluaran->update([
                'tanggal' => $request->tanggal,
                'jumlah' => $request->jumlah,
                'deskripsi' => strip_tags($request->deskripsi),
                'id_kategori_keuangan' => $request->id_kategori_keuangan,
                'posisi_kas' => $request->posisi_kas,
            ]);

            $transaksi = Transaksi::where('nobukti', $nobukti)->first();
            if ($transaksi) {
                $transaksi->update([
                    'tanggal' => $request->tanggal,
                    'jumlah' => $request->jumlah,
                    'deskripsi' => strip_tags($request->deskripsi),
                    'id_kategori_keuangan' => $request->id_kategori_keuangan,
                    'posisi_kas' => $request->posisi_kas,
                ]);
            }

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
            $pengeluaran = Pengeluaran::findOrFail($id);
            $nobukti = $pengeluaran->nobukti;

            $pembelian = Pembelian::where('nobukti', $nobukti)->first();
            if ($pembelian) {
                $pembelian->update(['status' => 'cancelled']);
                $pembelian->mutasi()->update(['status' => 0]);
            }

            $pengeluaran->delete();
            Transaksi::where('nobukti', $nobukti)->delete();

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
