<?php

namespace App\Http\Controllers;

use App\Models\bahanBaku;
use App\Models\Kategori;
use App\Models\Satuan;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;

class bahanBakuController extends Controller
{
    public function index() {
        $title = 'Bahan Baku';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Bahan Baku', 'url' => route('bahan-baku.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $bahan_baku = bahanBaku::with('satuan', 'kategori')->leftJoin('vsaldoakhir', 'bahan_baku.id', '=', 'vsaldoakhir.id')->get();

        $satuan = Satuan::pluck('nama', 'id');
        $kategori = Kategori::pluck('nama', 'id');

        return view('bahan-baku.index', compact('title', 'breadcrumbs', 'bahan_baku', 'satuan', 'kategori'));
    }

    public function store(Request $request) {
        // dd($request);
        $request->validate([
            'nama' => 'required',
            'stok_awal' => 'required',
            // 'stok_akhir' => 'required',
            'stok_minimum' => 'required',
            'harga' => 'required',
            'id_satuan' => 'required',
            'id_kategori' => 'required'
        ]);

        try {
            bahanBaku::create([
                'nama' => $request->nama,
                'stok_awal' => $request->stok_awal,
                // 'stok_akhir' => $request->stok_akhir,
                'stok_minimum' => $request->stok_minimum,
                'harga' => $request->harga,
                'id_satuan' => $request->id_satuan,
                'id_kategori' => $request->id_kategori,
            ]);

            notify()->success('Berhasil menambah data');
            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Gagal menambah data' . $e->getMessage());
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function update(Request $request, $id) {
        // dd($request);
        $request->validate([
            'nama' => 'required',
            'stok_awal' => 'required',
            // 'stok_akhir' => 'required',
            'stok_minimum' => 'required',
            'harga' => 'required',
            'id_satuan' => 'required',
            'id_kategori' => 'required'
        ]);
        $bahanBaku = bahanBaku::findOrFail($id);
        try {
            $bahanBaku->update([
                'nama' => $request->nama,
                'stok_awal' => $request->stok_awal,
                // 'stok_akhir' => $request->stok_akhir,
                'stok_minimum' => $request->stok_minimum,
                'harga' => $request->harga,
                'id_satuan' => $request->id_satuan,
                'id_kategori' => $request->id_kategori,
            ]);

            notify()->success('Berhasil update data');
            return redirect()->back();
        } catch (\Exception $e) {
            notify()->success('Gagal update data');
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
