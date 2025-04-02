<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index() {
        $title = 'Cabang';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Cabang', 'url' => route('cabang.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];
        return view('discount.index', compact('title', 'breadcrumbs'));
    }
}
