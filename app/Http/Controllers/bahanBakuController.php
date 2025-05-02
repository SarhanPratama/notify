<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\Kategori;
use App\Models\bahanBaku;
use Illuminate\Http\Request;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;

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
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_satuan' => 'required',
            'id_kategori' => 'required'
        ]);

        try {
            if ($request->hasFile('foto')) {
                $filename = time() . '_' . $request->file('foto')->getClientOriginalName();
                $fotoPath = $request->file('foto')->storeAs('uploads/bahanbaku', $filename, 'public');
            }

            bahanBaku::create([
                'nama' => $request->nama,
                'stok_awal' => $request->stok_awal,
                // 'stok_akhir' => $request->stok_akhir,
                'stok_minimum' => $request->stok_minimum,
                'harga' => $request->harga,
                'foto' => $fotoPath,
                'id_satuan' => $request->id_satuan,
                'id_kategori' => $request->id_kategori,
            ]);

            notify()->success('Berhasil menambah data');
            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Gagal menambah data' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'stok_awal' => 'required',
            // 'stok_akhir' => 'required',
            'stok_minimum' => 'required',
            'harga' => 'required',
            'id_satuan' => 'required',
            'id_kategori' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $bahanBaku = bahanBaku::findOrFail($id);

            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($bahanBaku->foto && Storage::disk('public')->exists($bahanBaku->foto)) {
                    Storage::disk('public')->delete($bahanBaku->foto);
                }

                $filename = time() . '_' . $request->file('foto')->getClientOriginalName();
                $fotoPath = $request->file('foto')->storeAs('uploads/bahanbaku', $filename, 'public');
            } else {
                $fotoPath = $bahanBaku->foto;
            }

            $bahanBaku->update([
                'nama' => $request->nama,
                'stok_awal' => $request->stok_awal,
                // 'stok_akhir' => $request->stok_akhir,
                'stok_minimum' => $request->stok_minimum,
                'harga' => $request->harga,
                'foto' => $fotoPath,
                'id_satuan' => $request->id_satuan,
                'id_kategori' => $request->id_kategori,
            ]);

            notify()->success('Berhasil memperbarui data');
            return redirect()->back();

        } catch (\Exception $e) {
            notify()->error('Gagal memperbarui data: ' . $e->getMessage());
            return redirect()->back();
        }
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
