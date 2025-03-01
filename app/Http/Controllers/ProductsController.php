<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Merek;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index() {

        $title = 'Tabel Produk';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel Produk', 'url' => null],
        ];

        return view('produk.index', compact('title', 'breadcrumbs'));
    }

    public function create() {

        $kategori = Kategori::pluck('nama', 'id');
        $merek = Merek::pluck('nama', 'id');

        $title = 'Form Produk';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel', 'url' => route('produk.index')],
            ['label' => 'Form Produk', 'url' => null],
        ];

        return view('produk.create', compact('title', 'breadcrumbs', 'kategori', 'merek'));
    }
}
