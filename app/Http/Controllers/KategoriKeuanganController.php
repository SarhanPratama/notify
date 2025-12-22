<?php

namespace App\Http\Controllers;

use App\Models\KategoriKeuangan;
use Illuminate\Http\Request;

class KategoriKeuanganController extends Controller
{
    public function index()
    {
        $title = 'Kategori Keuangan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Kategori Keuangan', 'url' => route('kategori-keuangan.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $kategori = KategoriKeuangan::orderBy('created_at', 'desc')->get();

        return view('kategori-keuangan.index', compact('title', 'breadcrumbs', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_keuangan,nama',
            'jenis' => 'required|in:pemasukan,pengeluaran,lainnya',
        ]);

        try {
            KategoriKeuangan::create([
                'nama' => $request->nama,
                'jenis' => $request->jenis,
            ]);

            notify()->success('Kategori Keuangan berhasil ditambahkan');
        } catch (\Exception $e) {
            notify()->error('Terjadi kesalahan saat menyimpan data.');
        }

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_keuangan,nama,' . $id,
            'jenis' => 'required|in:pemasukan,pengeluaran,lainnya',
        ]);

        try {
            $kategori = KategoriKeuangan::findOrFail($id);
            $kategori->update([
                'nama' => $request->nama,
                'jenis' => $request->jenis,
            ]);

            notify()->success('Kategori Keuangan berhasil diperbarui');
        } catch (\Exception $e) {
            notify()->error('Terjadi kesalahan saat memperbarui data.');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $kategori = KategoriKeuangan::findOrFail($id);
            $kategori->delete();

            notify()->success('Kategori Keuangan berhasil dihapus');
        } catch (\Exception $e) {
            notify()->error('Terjadi kesalahan saat menghapus data.');
        }

        return redirect()->back();
    }
}
