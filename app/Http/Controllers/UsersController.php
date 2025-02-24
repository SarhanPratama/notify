<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index() {

       $users = User::with('Karyawan')->paginate(12);

        $title = 'Tabel Karyawan';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel Karyawan', 'url' => null],
        ];

        return view('users.index', compact('breadcrumbs', 'title', 'users'));
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

    public function show($id)
    {
        $user = User::with('karyawan', 'karyawan.cabang', 'roles')->findOrFail($id);

        return view('users.show', compact('user'));
    }


    public function edit($id) {

        $title = 'Form Karyawan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel', 'url' => route('users.index')],
            ['label' => 'Form Karyawan', 'url' => null],
        ];


        $user = User::with('Karyawan')->findOrFail($id);
        // DD($users);
        $cabang = Cabang::all();

        $roles = Role::all();

        return view('users.edit', compact('title', 'breadcrumbs', 'user', 'cabang', 'roles'));
    }
    public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'name'      => 'required|string|max:255',
        'usia'      => 'required|integer|min:18',
        'telepon'   => 'required|string|max:15',
        'id_roles'  => 'required|exists:roles,id',
        'id_cabang' => 'required|exists:cabang,id',
        'alamat'    => 'nullable|string',
        'foto'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Ambil data user dan relasi karyawan
    $user = User::findOrFail($id);
    $karyawan = $user->karyawan; // Pastikan relasi karyawan ada

    // Update data user
    $user->update([
        'name' => $request->name,
    ]);

    $role = Role::findById($request->id_roles, 'web');
    $user->syncRoles([$role->name]);
    // Cek jika ada unggahan foto baru
    if ($request->hasFile('foto')) {
        // Hapus foto lama jika ada
        if ($karyawan->foto && file_exists(public_path('images/karyawan/' . $karyawan->foto))) {
            unlink(public_path('images/karyawan/' . $karyawan->foto));
        }

        // Simpan foto baru
        $foto = $request->file('foto');
        $namaFoto = time() . '.' . $foto->extension();
        $foto->move(public_path('uploads/karyawan'), $namaFoto);

        // Simpan nama file di database
        $karyawan->foto = $namaFoto;
    }

    // Update data karyawan
    $karyawan->update([
        'usia'      => $request->usia,
        'telepon'   => $request->telepon,
        'id_roles'  => $request->id_roles,
        'id_cabang' => $request->id_cabang,
        'alamat'    => $request->alamat,
    ]);

    notify()->success('Data Karyawan berhasil diperbarui!');

    return redirect()->route('users.index');
}

}
