<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() {
        $title = 'Supplier';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Supplier', 'url' => route('supplier.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $supplier = Supplier::all();

        return view('supplier.index', compact('title', 'breadcrumbs', 'supplier'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama' => 'required',
            'telepon' => 'required',
            'alamat' => 'required'
        ]);

        try {
            // Simpan data supplier
            Supplier::create([
                'nama' => $request->nama,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat
            ]);

            notify()->success('Data supplier berhasil disimpan!');
            return redirect()->back();
        } catch (\Throwable $th) {
            notify()->error('Terjadi kesalahan saat menyimpan data!');
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function update(Request $request, $id) {
        $supplier = Supplier::findOrFail($id);
        $request->validate([
            'nama' => 'required',
            'telepon' => 'required',
            'alamat' => 'required'
        ]);

        try {
            // Simpan data supplier
            $supplier->update([
                'nama' => $request->nama,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat
            ]);

            notify()->success('Data supplier berhasil diupdate!');
            return redirect()->back();
        } catch (\Throwable $th) {
            notify()->error('Terjadi kesalahan saat menyimpan data!');
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function destroy($id) {
        $supplier = Supplier::findOrFail($id);

        $supplier->delete();

        notify()->success('Data Supplier berhasil dihapus');
        return redirect()->back();
    }
}
