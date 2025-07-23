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
            'tipe' => 'required|in:debit,kredit', // debit = uang masuk, credit = uang keluar
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
                'deskripsi' => strip_tags($request->deskripsi, '<b><i>'),
                'status' => 1,
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
            return redirect()->route('transaksi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menyimpan transaksi: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $title = 'Arus Kas';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Arus Kas', 'url' => route('transaksi.index')],
            ['label' => 'Detail', 'url' => null],
        ];
        $transaksi = Transaksi::with(['sumberDana', 'referenceable'])->findOrFail($id);

        return view('kas.show', compact('title', 'breadcrumbs', 'transaksi'));
    }
}
