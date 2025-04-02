<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CabangController extends Controller
{
    public function index(Request $request)
    {


        // $search = $request->input('search');
        // $perPage = $request->input('per_page');

        // $cabang = Cabang::when($search, function ($query, $search) {
        //     return $query->where(function ($query) use ($search) {
        //         $query->where('nama', 'LIKE', "%{$search}%")
        //               ->orWhere('alamat', 'LIKE', "%{$search}%");
        //     });
        // })->paginate($perPage);

        $cabang = Cabang::all();

        // dd($cabang);{}

        $title = 'Cabang';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Cabang', 'url' => route('cabang.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        return view('cabang.index', compact('cabang', 'breadcrumbs', 'title'));
    }


    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'nama' => 'required',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required',
            'lokasi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('uploads/cabang', $fotoName, 'public');
        }

        Cabang::create([
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'lokasi' => $request->lokasi,
            'foto' => $fotoPath
        ]);
        notify()->success('Data berhasil di input');
        return redirect()->back();
    }

    public function edit($id)
    {
        $title = 'Form Update Cabang';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel', 'url' => route('cabang.index')],
            ['label' => 'Form Update Cabang', 'url' => null],
        ];

        $cabang = Cabang::where('id', $id)->first();

        return view('cabang.update', compact('cabang', 'breadcrumbs', 'title'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'lokasi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $cabang = Cabang::findOrFail($id);


        $fotoPath = $cabang->foto;
        if ($request->hasFile('foto')) {
            if ($cabang->foto && file_exists(storage_path('app/public/' . $cabang->foto))) {
                unlink(storage_path('app/public/' . $cabang->foto));
            }

            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('uploads/cabang', $fotoName, 'public');
        }

        $cabang->update([
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'lokasi' => $request->lokasi,
            'foto' => $fotoPath
        ]);

        notify()->success('Data berhasil di update');
        return redirect()->route('cabang.index');
    }

    public function destroy($id)
    {
        try {
            // Cari data cabang yang akan dihapus
            $cabang = Cabang::findOrFail($id);

            // Periksa apakah cabang memiliki foto dan hapus filenya
            if ($cabang->foto) {
                $filePath = storage_path('app/public/' . $cabang->foto);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Hapus cabang
            $cabang->delete();

            notify()->success('Cabang berhasil dihapus!');
            return redirect()->route('cabang.index');

        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
                notify()->error('Cabang tidak bisa dihapus karena masih ada data terkait.');
                return redirect()->route('cabang.index');
            }
            notify()->error('Terjadi kesalahan saat menghapus cabang.');
            return redirect()->route('cabang.index');
        }
    }
}
