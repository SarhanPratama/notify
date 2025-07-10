<?php

namespace App\Http\Controllers;

use App\Models\Piutang;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PiutangController extends Controller
{
    public function index()
    {
        $title = 'Kasbon';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'kasbon', 'url' => route('piutang.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];
        $piutang = Piutang::with('penjualan')->get();
        $sumberDana = SumberDana::pluck('nama', 'id');

        return view('piutang.index', compact('title', 'breadcrumbs', 'piutang', 'sumberDana'));
    }

    public function bayar(Request $request, $nobukti)
    {
        // dd($nobukti);
        $request->validate([
            'id_sumber_dana' => 'required|exists:sumber_dana,id',
        ]);

        DB::beginTransaction();

        try {
            $piutang = Piutang::with('penjualan')->where('nobukti', $nobukti)->firstOrFail();
            // dd($piutang);
            // Pastikan belum lunas
            if ($piutang->status === 'lunas') {
                notify()->warning('Piutang sudah lunas');
                return redirect()->back()->with('warning', 'Piutang sudah dilunasi.');
            }

            $sumberDana = SumberDana::findOrFail($request->id_sumber_dana);
            // dd($sumberDana);

            // Catat transaksi pembayaran piutang (uang masuk)
            $piutang->penjualan->transaksi()->create([
                'id_sumber_dana' => $sumberDana->id,
                'tanggal' => now(),
                'tipe' => 'debit',
                'jumlah' => $piutang->jumlah_piutang,
                'deskripsi' => 'Pelunasan piutang #' . $piutang->nobukti,
            ]);

            // Tambah saldo
            $sumberDana->increment('saldo_current', $piutang->jumlah_piutang);

            // Ubah status piutang
            $piutang->update([
                'status' => 'lunas',
            ]);

            DB::commit();
            notify()->success('Piutang berhasil dibayar.');
            return redirect()->route('piutang.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal memperbarui penjualan: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
