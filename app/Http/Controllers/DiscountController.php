<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index() {
        $title = 'Discounts';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Discounts', 'url' => route('discount.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];
        return view('discount.index', compact('title', 'breadcrumbs'));
    }
}
