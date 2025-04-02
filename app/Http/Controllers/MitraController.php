<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MitraController extends Controller
{
    public function index() {
        return view('auth.absensi');
    }

    public function create() {
        $title = 'Formulir ';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Produk', 'url' => route('produk.index')],
            ['label' => 'Form Tambah', 'url' => null],
        ];

        return view('mitra.create');
    }
}
