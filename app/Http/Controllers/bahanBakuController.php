<?php

namespace App\Http\Controllers;

use App\Models\bahanBaku;
use App\Models\Satuan;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;

class bahanBakuController extends Controller
{
    public function index() {
        $title = 'Tabel Bahan Baku';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel Bahan Baku', 'url' => null],
        ];

        $bahan_baku = bahanBaku::with('satuan')->get();

        $satuan = Satuan::pluck('nama', 'id');

        return view('bahan-baku.index', compact('title', 'breadcrumbs', 'bahan_baku', 'satuan'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama' => 'required',
            'stok' => 'required',
            'stok_minimum' => 'required',
            'id_satuan' => 'required'
        ]);

        try {
            bahanBaku::create([
                'nama' => $request->nama,
                'stok' => $request->stok,
                'stok_minimum' => $request->stok_minimum,
                'id_satuan' => $request->id_satuan,
            ]);

            notify()->success('Berhasil menambah data');
            return redirect()->back();
        } catch (\Exception $e) {
            notify()->success('Gagal menambah data');
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function destroy($id) {
        try {
            $bahan_baku = bahanBaku::findOrFail($id);
            $bahan_baku->delete();
            notify()->success('Berhasil menghapus data');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus role');
        }
        return redirect()->back();
    }
}
