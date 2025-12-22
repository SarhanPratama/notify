<?php

namespace App\Http\Controllers;

use App\Models\cash_flow;
use App\Models\Transaksi;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $title = 'Arus Kas';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Arus Kas', 'url' => route('transaksi.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $kas = Transaksi::orderBy('tanggal', 'desc')->get();


        return view('transaksi.index', compact('title', 'breadcrumbs', 'kas'));
    }
}
