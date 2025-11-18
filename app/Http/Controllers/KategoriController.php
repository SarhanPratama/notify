<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();

        $title = 'Kategori';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Kategori', 'url' => route('kategori.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        return view('kategori.index', compact('kategori', 'title', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama',
        ]);

        try {
            Kategori::create([
                'nama' => $request->nama,
            ]);

            notify()->success('Kategori berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                notify()->error('Kategori dengan nama tersebut sudah ada.');
            } else {
                notify()->error('Terjadi kesalahan saat menyimpan data.');
            }
        }

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama,' . $id,
        ]);

        $kategori = Kategori::findOrFail($id);

        try {
            $kategori->update(['nama' => $request->nama]);
            notify()->success('Kategori berhasil diupdate');
        } catch (\Exception $e) {
            notify()->error('Terjadi kesalahan saat mengupdate data.');
        }

        return redirect()->route('kategori.index');
    }

    public function destroy($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();
            notify()->success('Kategori berhasil dihapus');
        } catch (\Exception $e) {
            if (stripos($e->getMessage(), 'foreign key') !== false) {
                notify()->error('Kategori tidak bisa dihapus karena terkait dengan data lain.');
            } else {
                notify()->error('Terjadi kesalahan saat menghapus data.');
            }
        }

        return redirect()->route('kategori.index');
    }
}
