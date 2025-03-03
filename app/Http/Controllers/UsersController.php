<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index(Request $request) {

        $title = 'Tabel Karyawan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel Karyawan', 'url' => null],
        ];

        // $search = $request->input('search');
        // $perPage = $request->input('per_page', 10);

        // $users = User::with('karyawan')->when($search, function ($query, $search) {
        //     return $query->where(function ($query) use ($search) {
        //         $query->where('name', 'LIKE', "%{$search}%")
        //               ->orWhere('email', 'LIKE', "%{$search}%");
        //     });
        // })->paginate($perPage);

        $users = User::with(['cabang', 'roles'])->get();
        // $users = Karyawan::with(['user', 'cabang', 'role'])->get();

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

        $cabang = Cabang::pluck('nama', 'id');
        $roles = Role::pluck('name', 'id');

        // dd($cabang);
        return view('users.create', compact('cabang', 'breadcrumbs', 'title', 'roles'));
    }

    public function show($id)
    {

        $title = 'Detail Karyawan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel', 'url' => route('users.index')],
            ['label' => 'Detail Karyawan', 'url' => null],
        ];

        $user = User::findOrFail($id);

        return view('users.show', compact('user', 'breadcrumbs', 'title'));
    }


    public function edit($id) {

        $title = 'Form Karyawan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel', 'url' => route('users.index')],
            ['label' => 'Form Karyawan', 'url' => null],
        ];

        $user = User::findOrFail($id);
        // DD($users);
        $cabang = Cabang::pluck('nama', 'id');

        $roles = Role::pluck('name', 'id');

        return view('users.edit', compact('title', 'breadcrumbs', 'user', 'cabang', 'roles'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name'      => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'telepon'   => 'required|string|max:15',
            'id_roles'  => 'required|exists:roles,id',
            'id_cabang' => 'required|exists:cabang,id',
            'alamat'    => 'nullable|string',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Update data user
        $user->update([
            'name'      => $request->name,
            'tgl_lahir' => $request->tgl_lahir,
            'telepon'   => $request->telepon,
            // 'id_roles'  => $request->id_roles,
            'id_cabang' => $request->id_cabang,
            'alamat'    => $request->alamat,
            'updated_at' => now(),
        ]);

        // Update role
        $role = Role::findById($request->id_roles, 'web');
        $user->syncRoles([$role->name]); // Gunakan syncRoles untuk menyinkronkan role

        // Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && file_exists(public_path('uploads/users/' . $user->foto))) {
                unlink(public_path('uploads/users/' . $user->foto));
            }

            // Upload foto baru
            $foto = $request->file('foto');
            $namaFoto = time() . '.' . $foto->extension();
            $foto->move(public_path('uploads/users'), $namaFoto);

            // Update foto pada user
            $user->foto = $namaFoto;
            $user->save();
        }

        notify()->success('Data User berhasil diperbarui!');

        return redirect()->route('users.index');
    }


}
