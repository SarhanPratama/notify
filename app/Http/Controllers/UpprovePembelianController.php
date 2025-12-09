<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class UpprovePembelianController extends Controller
{
    public function index()
    {

        $title = 'Upproval Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Upproval Pembelian', 'url' => route('approval-pembelian.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];


        $pembelian = Pembelian::whereIn('status', ['pending', 'approved', 'rejected'])->orderBy('created_at', 'desc')->get();
        // dd($pembelian);
        return view('approval-pembelian.index', compact('title', 'breadcrumbs', 'pembelian'));
    }

    public function show($id)
    {
        $title = 'Detail Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel', 'url' => route('pembelian.index')],
            ['label' => 'Detail Pembelian', 'url' => null],
        ];

        $detailPembelian = Pembelian::with(['mutasi.bahanBaku.satuan', 'supplier', 'Transaksi'])->findOrFail($id);

        return view('approval-pembelian.show', compact('title', 'breadcrumbs', 'detailPembelian'));
    }

    public function update($id)
    {
        $pembelian = Pembelian::findOrFail($id);

        // Update status
        $pembelian->update([
            'status' => 'approved',

        ]);

        $pembelian->mutasi()->update([
            'status' => 1,
        ]);

        // Buat transaksi terkait
        $pembelian->transaksi()->create([
            'nobukti' => $pembelian->nobukti,
            'tanggal' => now(),
            'tipe' => 'kredit',
            'jumlah' => $pembelian->total,
            'deskripsi' => 'Pembelian bahan baku dengan kode ' . $pembelian->nobukti,
            'status' => 1,
        ]);

        notify()->success('Pembelian berhasil disetujui');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::findOrFail($id);

        // Update status
        $pembelian->update([
            'status' => 'rejected',
        ]);

        $pembelian->mutasi()->update([
            'status' => 0,
        ]);

        notify()->success('Pembelian berhasil ditolak');
        return redirect()->back();
    }
}
