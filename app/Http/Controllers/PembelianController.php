<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function index() {
        return view('pembelian.index');
    }

    public function store() {
        return redirect()->back();
    }
}
