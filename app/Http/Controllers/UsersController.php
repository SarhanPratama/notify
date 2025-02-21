<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index() {

        $title = 'Tabel Karyawan';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel Karyawan', 'url' => null],
        ];

        return view('users.index', compact('breadcrumbs', 'title'));
    }

    public function create() {
        // User::with('Cabang')->get();
        $title = 'Form Karyawan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel', 'url' => route('users.index')],
            ['label' => 'Form Karyawan', 'url' => null],
        ];

        $cabang = Cabang::all();


        // dd($cabang);
        return view('users.create', compact('cabang', 'breadcrumbs', 'title'));
    }
}
