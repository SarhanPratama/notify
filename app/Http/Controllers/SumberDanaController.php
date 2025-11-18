<?php

namespace App\Http\Controllers;

use App\Models\SumberDana;
use Illuminate\Http\Request;

class SumberDanaController extends Controller
{
    public function index()
    {
        $sumberDana = SumberDana::all();

        $title = 'Sumber Dana';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Sumber Dana', 'url' => route('sumber-dana.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        return view('sumber-dana.index', compact('sumberDana', 'title', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:sumber_dana,nama',
            'saldo_awal' => 'required|numeric|min:0',
        ]);

        try {
            SumberDana::create($request->only(['nama', 'saldo_awal']));
            notify()->success('Sumber Dana berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                notify()->error('Sumber Dana dengan nama tersebut sudah ada.');
            } else {
                notify()->error('Terjadi kesalahan saat menyimpan data.');
            }
        }

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:sumber_dana,nama,' . $id,
            'saldo_awal' => 'required|numeric|min:0',
        ]);

        $sumber = SumberDana::findOrFail($id);

        try {
            $sumber->update($request->only(['nama', 'saldo_awal']));
            notify()->success('Sumber Dana berhasil diupdate');
        } catch (\Exception $e) {
            notify()->error('Terjadi kesalahan saat mengupdate data.');
        }

        return redirect()->route('sumber-dana.index');
    }

    public function destroy($id)
    {
        try {
            $sumber = SumberDana::findOrFail($id);
            $sumber->delete();
            notify()->success('Sumber Dana berhasil dihapus');
        } catch (\Exception $e) {
            if (stripos($e->getMessage(), 'foreign key') !== false) {
                notify()->error('Sumber Dana tidak bisa dihapus karena terkait dengan data lain.');
            } else {
                notify()->error('Terjadi kesalahan saat menghapus data.');
            }
        }

        return redirect()->route('sumber-dana.index');
    }
}
