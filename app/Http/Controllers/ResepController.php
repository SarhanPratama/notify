<?php

namespace App\Http\Controllers;

use App\Models\resep;
use App\Models\products;
use App\Models\bahanBaku;
use App\Models\DetailResep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResepController extends Controller
{
    public function index() {

        $title = 'Resep';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Resep', 'url' => route('resep.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $resep = resep::with('bahanBaku', 'produk', 'detailResep')->get();
        $produk = products::pluck('nama', 'id');
        $bahanBaku = bahanBaku::all();

        return view('resep.index', compact('title', 'breadcrumbs', 'resep', 'produk', 'bahanBaku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_products' => 'required|exists:products,id',
            'id_bahan_baku' => 'required|array',
            'id_bahan_baku.*' => 'exists:bahan_baku,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
            'instruksi' => 'nullable|string',
        ]);

        try {
            $resep = resep::create([
                'id_products' => $request->id_products,
                'instruksi' => $request->instruksi,
            ]);

            foreach ($request->id_bahan_baku as $index => $id_bahan_baku) {
                DetailResep::create([
                    'id_resep' => $resep->id,
                    'id_bahan_baku' => $id_bahan_baku,
                    'jumlah' => $request->jumlah[$index],
                ]);
            }
            notify()->success('Data resep "' . $resep->produk->nama . '" berhasil ditambahkan.');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_products' => 'required|exists:products,id',
            'id_bahan_baku' => 'required|array',
            'id_bahan_baku.*' => 'exists:bahan_baku,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
            'instruksi' => 'nullable|string',
        ]);

        try {
            $resep = Resep::findOrFail($id);
            $resep->update([
                'id_products' => $request->id_products,
                'instruksi' => $request->instruksi,
            ]);

            $resep->detailResep()->delete();

            foreach ($request->id_bahan_baku as $index => $id_bahan_baku) {
                DetailResep::create([
                    'id_resep' => $resep->id,
                    'id_bahan_baku' => $id_bahan_baku,
                    'jumlah' => $request->jumlah[$index],
                ]);
            }
            notify()->success('Data Berhasil Diupdate');
            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Data gagal Diupdate');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $resep = Resep::findOrFail($id);

            $resep->detailResep()->delete();

            $resep->delete();
            notify()->success('Data resep berhasil dihapus');
            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Data resep gagal dihapus');
            return redirect()->back();
        }
    }
}
