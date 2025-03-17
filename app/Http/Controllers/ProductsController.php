<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Merek;
use App\Models\Kategori;
use App\Models\products;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index() {

        $title = 'Produk';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Produk', 'url' => route('produk.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $produk = products::with(['merek', 'kategori'])->get();

        return view('produk.index', compact('title', 'breadcrumbs', 'produk'));
    }

    public function create() {

        $title = 'Produk';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Produk', 'url' => route('produk.index')],
            ['label' => 'Form Tambah', 'url' => null],
        ];

        $kode = 'P-' . str_pad(products::count() + 1, 4, '0', STR_PAD_LEFT);

        $kategori = Kategori::pluck('nama', 'id');
        $merek = Merek::pluck('nama', 'id');


        return view('produk.create', compact('title', 'breadcrumbs', 'kategori', 'merek', 'kode'));
    }

    public function store(Request $request)
{
    $request->validate([
        'kode' => 'required|string|max:50|unique:products,kode',
        'nama' => 'required|string|max:100',
        'stok' => 'required|integer|min:0',
        'harga_modal' => 'required|numeric|min:0',
        'harga_jual' => 'required|numeric|min:0',
        // 'status' => 'nullable|in:aktif,nonaktif',
        'id_kategori' => 'required|exists:kategori,id',
        'id_merek' => 'required|exists:merek,id',
        'deskripsi' => 'nullable|string',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $fotoPath = null;
    if ($request->hasFile('foto')) {
        $foto = $request->file('foto');
        $fotoName = time() . '_' . $foto->getClientOriginalName();
        $fotoPath = $foto->storeAs('uploads/produk', $fotoName, 'public');
    }
    products::create($request->all());
    notify()->success('Produk berhasil Ditambahkan');
    return redirect()->route('produk.index');
    }

    public function show($id) {

        $title = 'Detail Produk';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel', 'url' => route('produk.index')],
            ['label' => 'Detail Produk', 'url' => null],
        ];
        $produk = products::findOrFail($id);

        return view('produk.show', compact('produk', 'title', 'breadcrumbs'));
    }

    public function edit($id) {
        $title = 'Produk';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel', 'url' => route('produk.index')],
            ['label' => 'Form Edit', 'url' => null],
        ];
        // $produk = products::where('id', $id)->with('kategori', 'merek')->first();
        // $produk = products::findOrFail($id);
        $produk = products::where('id', $id)->first();

        // dd($produk);
        $kategori = Kategori::pluck('nama', 'id');
        $merek = Merek::pluck('nama', 'id');
        return view('produk.edit', compact('title', 'breadcrumbs', 'produk', 'kategori', 'merek'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:products,kode,' . $id,
            'nama' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'harga_modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'status' => 'nullable|in:aktif,nonaktif',
            'id_kategori' => 'required|exists:kategori,id',
            'id_merek' => 'required|exists:merek,id',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $produk = products::findOrFail($id);

        $fotoPath = $produk->foto;
        if ($request->hasFile('foto')) {
            if ($produk->foto && file_exists(storage_path('app/public/' . $produk->foto))) {
                unlink(storage_path('app/public/' . $produk->foto));
            }

            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('uploads/produk', $fotoName, 'public');
        }

        $produk->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'stok' => $request->stok,
            'harga_modal' => $request->harga_modal,
            'harga_jual' => $request->harga_jual,
            'status' => $request->status,
            'id_kategori' => $request->id_kategori,
            'id_merek' => $request->id_merek,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
        ]);

        notify()->success('Produk berhasil diupdate');
        return redirect()->route('produk.index');
    }


    public function destroy($id)
    {
        $produk = products::findOrFail($id);

        if ($produk->foto && file_exists(storage_path('app/public/' . $produk->foto))) {
            unlink(storage_path('app/public/' . $produk->foto));
        }

        $produk->delete();

        notify()->success('Produk berhasil dihapus');
        return redirect()->route('produk.index');
    }

    public function merekStore(Request $request) {
        $request->validate([
            'nama' => 'required'
        ]);

        try {
            $merek = Merek::create($request->all());

            notify()->success('Data "' . $merek->nama . '" berhasil ditambahkan.');
        }
        catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                notify()->error('Data dengan nama "' . $request->nama . '" sudah ada.');
            } else {
                notify()->error('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
            }
        }
        return redirect()->back();
    }

    public function kategoriStore(Request $request) {
        $request->validate([
            'nama' => 'required'
        ]);

        try {
            $kategori = Kategori::create($request->all());

            notify()->success('Data "' . $kategori->nama . '" berhasil ditambahkan.');
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                notify()->error('Data dengan nama "' . $request->nama . '" sudah ada.');
            } else {

                notify()->error('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
            }
        }

        return redirect()->back();
    }

}
