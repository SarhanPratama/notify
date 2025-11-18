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
        $piutang = Piutang::with('penjualan', 'pembayaran')->get();
        // dd($piutang);
        $sumberDana = SumberDana::pluck('nama', 'id');

        return view('piutang.index', compact('title', 'breadcrumbs', 'piutang', 'sumberDana'));
    }

    public function bayar(Request $request, $nobukti)
    {
        // dd($request->all());

        $request->validate([
            'id_sumber_dana' => 'required|exists:sumber_dana,id',
            'jumlah' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $piutang = Piutang::with('penjualan.transaksi','pembayaran')->where('nobukti', $nobukti)->firstOrFail();
            // dd($piutang);
            $totalSudahDibayar = $piutang->pembayaran->sum('jumlah');
            // dd($totalSudahDibayar);
            $sisaPiutang = $piutang->jumlah_piutang - $totalSudahDibayar;
            // dd($sisaPiutang);
            if ($request->jumlah > $sisaPiutang) {
                notify()->success('Pembayaran melebihi sisa piutang.');
                return redirect()->back();
            }

            // Simpan histori cicilan
            $pembayaran = $piutang->pembayaran()->create([
                'id_piutang' => $piutang->id,
                'id_sumber_dana' => $request->id_sumber_dana,
                'tanggal' => now(),
                'jumlah' => $request->jumlah,
                'keterangan' => 'Pembayaran sebagian untuk piutang #' . $piutang->nobukti,
            ]);

            // Catat arus kas masuk
            $piutang->penjualan->transaksi()->create([
                'id_sumber_dana' => $request->id_sumber_dana,
                'tanggal' => now(),
                'tipe' => 'debit',
                'jumlah' => $request->jumlah,
                'deskripsi' => 'Pembayaran piutang #' . $piutang->nobukti,
            ]);

            // Cek pelunasan
            if ($request->jumlah + $totalSudahDibayar >= $piutang->jumlah_piutang) {
                $piutang->update(['status' => 'lunas']);
            }

            DB::commit();
            notify()->success('Pembayaran berhasil dicatat.');
            return redirect()->route('piutang.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menyimpan pembayaran: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
