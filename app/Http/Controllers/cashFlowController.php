<?php

namespace App\Http\Controllers;

use App\Models\cash_flow;
use Illuminate\Http\Request;

class cashFlowController extends Controller
{
    public function index() {
        $title = 'Arus Kas';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Arus Kas', 'url' => route('kas.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $kas = cash_flow::get();

        return view('kas.index', compact('title', 'breadcrumbs', 'kas'));
    }

    public function create() {
        $title = 'Arus Kas';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Arus Kas', 'url' => route('kas.index')],
            ['label' => 'Form Tambah', 'url' => null],
        ];
        return view('kas.create', compact('title', 'breadcrumbs'));
    }

    public function store(Request $request) {
        // dd($request);
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'jenis_transaksi' => 'required|in:debit,kredit',
            'sumber_dana' => 'required|in:kas seroo,kas rekening,dana peminjaman,piutang',
            'keperluan' => 'nullable|string|max:255',
        ]);

        $data = [
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'keperluan' => $request->keperluan,
            'nominal' => $request->nominal,
            'sumber_dana' => $request->sumber_dana,
            'debit' => $request->jenis_transaksi === 'debit' ? $request->nominal : 0,
            'kredit' => $request->jenis_transaksi === 'kredit' ? $request->nominal : 0,
        ];

        cash_flow::create($data);

        return redirect()->route('kas.index');
    }
}
