<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() {
        $title = 'Tabel supplier';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel supplier', 'url' => null],
        ];

        return view('supplier.index', compact('title', 'breadcrumbs'));
    }

    public function store() {
        
    }
}
