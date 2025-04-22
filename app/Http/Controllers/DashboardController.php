<?php

namespace App\Http\Controllers;

use App\Models\bahanBaku;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $title = 'Dashboard';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Dashboard', 'url' => null],
        ];

        $bahanBakuLimit = bahanBaku::with(['satuan'])
        ->whereColumn('stok_akhir', '<=', 'stok_minimum')
        ->get();
        return view('dashboard.index', compact('title', 'breadcrumbs', 'bahanBakuLimit'));
    }
}
