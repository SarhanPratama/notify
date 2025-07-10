<?php

namespace App\Http\Controllers;

use App\Models\cash_flow;
use App\Models\Transaksi;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cashFlowController extends Controller
{
    public function index()
    {
        $title = 'Arus Kas';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Arus Kas', 'url' => route('transaksi.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $kas = Transaksi::with('SumberDana')->get();


        return view('kas.index', compact('title', 'breadcrumbs', 'kas'));
    }

    public function create()
    {
        $title = 'Arus Kas';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Arus Kas', 'url' => route('transaksi.index')],
            ['label' => 'Form Tambah', 'url' => null],
        ];

        $sumberDana = SumberDana::pluck('nama', 'id');
        return view('kas.create', compact('title', 'breadcrumbs', 'sumberDana'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'jenis_transaksi' => 'required|in:debit,credit', // debit = uang masuk, credit = uang keluar
            'id_sumber_dana' => 'required|exists:sumber_dana,id',
            'deskripsi' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Simpan transaksi manual
            $transaksi = Transaksi::create([
                'id_sumber_dana' => $request->id_sumber_dana,
                'tanggal' => $request->tanggal,
                'tipe' => $request->tipe,
                'jumlah' => $request->jumlah,
                'deskripsi' => $request->deskripsi,
                // Kosongkan referenceable karena ini transaksi manual
                'referenceable_type' => null,
                'referenceable_id' => null,
            ]);

            // Update saldo sumber dana
            $sumberDana = SumberDana::findOrFail($request->id_sumber_dana);
            if ($request->tipe === 'debit') {
                $sumberDana->increment('saldo_current', $request->jumlah);
            } else {
                $sumberDana->decrement('saldo_current', $request->jumlah);
            }

            DB::commit();
            notify()->success('Transaksi berhasil ditambahkan.');
            return redirect()->route('kas.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menyimpan transaksi: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
