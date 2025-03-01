<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CabangController extends Controller
{
    public function index(Request $request) {


        $search = $request->input('search');
        $perPage = $request->input('per_page');

        // $cabang = Cabang::when($search, function ($query, $search) {
        //     return $query->where(function ($query) use ($search) {
        //         $query->where('nama', 'LIKE', "%{$search}%")
        //               ->orWhere('alamat', 'LIKE', "%{$search}%");
        //     });
        // })->paginate($perPage);

        $cabang = Cabang::all();

        // dd($cabang);{}

        $title = 'Tabel Cabang';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel Cabang', 'url' => null],
        ];

        return view('cabang.index', compact('cabang', 'search', 'breadcrumbs', 'title', 'perPage'));
    }


    public function store(Request $request) {
        // dd($request);
        $request->validate([
            'nama' => 'required',
            'telepon' => 'required',
            'alamat' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $imageFile = $request->file('foto');

        if ($imageFile) {
            $imageName = time().'.'.$imageFile->extension();
            $path = $imageFile->storeAs('public/images/cabang', $imageName);
        } else {
            $imageName = 'default.jpg';
        }

        Cabang::create([
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'foto' => $imageName
        ]);
        notify()->success('Data berhasil di input');
        return redirect()->back();
    }

    public function edit($id) {
        $title = 'Form update karyawan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel', 'url' => route('cabang.index')],
            ['label' => 'Form update karyawan', 'url' => null],
        ];

        $cabang = Cabang::where('id', $id)->first();

        return view('cabang.update', compact('cabang', 'breadcrumbs', 'title'));
    }

    public function update(Request $request, $id) {

        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $cabang = Cabang::findOrFail($id);


        if ($request->hasFile('foto')) {
        
            if ($cabang->foto) {
                Storage::delete('public/images/cabang' . $cabang->foto);
            }

            // Simpan gambar baru
            $imageFile = $request->file('foto');
            $imageName = time().'.'.$imageFile->extension();
            $imageFile->storeAs('public/images/cabang', $imageName);
        } else {
            // Jika tidak ada gambar baru, gunakan gambar lama
            $imageName = $cabang->foto;
        }

        // Update data di database
        $cabang->update([
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'foto' => $imageName
        ]);

        notify()->success('Data berhasil di update');
        return redirect()->route('cabang.index');
    }

    public function destroy($id)
{
    $cabang = Cabang::findOrFail($id);

    if ($cabang->foto) {
        $filePath = storage_path('app/public/images/' . $cabang->foto);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $cabang->delete();

    notify()->success('Cabang berhasil dihapus!');
    return redirect()->back();
}

}
