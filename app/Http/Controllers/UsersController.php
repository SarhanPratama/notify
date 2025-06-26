<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Merek;
use App\Models\Cabang;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function index(Request $request)
    {

        $title = 'Karyawan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Karyawan', 'url' => route('users.index')],
            ['label' => 'Tabel Data', 'url' => null],
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

    public function create()
    {
        // User::with('Cabang')->get();
        $title = 'Karyawan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Karyawan', 'url' => route('users.index')],
            ['label' => 'Form Tambah', 'url' => null],
        ];
        // $user = User::all();
        // dd($user);
        $cabang = Cabang::pluck('nama', 'id');
        $roles = Role::pluck('name', 'id');

        // dd($cabang);
        return view('users.create', compact('cabang', 'breadcrumbs', 'title', 'roles'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'tgl_lahir'         => 'required|date',
            'telepon'           => 'required|string|max:15',
            'id_roles'          => 'required|exists:roles,id',
            'id_cabang'         => 'required|exists:cabang,id',
            'alamat'            => 'nullable|string',
            'foto'              => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'password'          => 'required|string|min:8|confirmed',
        //     'password_confirmation' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'tgl_lahir'         => $request->tgl_lahir,
            'telepon'           => $request->telepon,
            // 'id_cabang'         => $request->id_cabang,
            'alamat'            => $request->alamat,
            // 'password'          => Hash::make($request->password),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Assign the role to the user
        $role = Role::findById($request->id_roles, 'web');
        $user->syncRoles([$role->name]);

        // Handle the photo upload if it exists
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::exists('public/' . $user->foto)) {
                Storage::delete('public/' . $user->foto);
            }

            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('uploads/users', $fotoName, 'public');

            $user->foto = $fotoPath;
            $user->save();
        }

        notify()->success('Data user "' . $user->name . '" berhasil ditambahkan.');
        return redirect()->route('users.index');
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


    public function edit($id)
    {

        $title = 'Karyawan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Karyawan', 'url' => route('users.index')],
            ['label' => 'Form Edit', 'url' => null],
        ];

        $user = User::findOrFail($id);
        // DD($users);
        $cabang = Cabang::pluck('nama', 'id');

        $roles = Role::pluck('name', 'id');

        $userRole = $user->getRoleNames();

        return view('users.edit', compact('title', 'breadcrumbs', 'user', 'cabang', 'roles', 'userRole'));
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
            // 'password'          => 'required|string|min:8|confirmed',
            // 'password_confirmation' => 'required|string|min:8',
        ]);

        // dd($request->all());
        $user = User::findOrFail($id);

        $user->update([
            'name'      => $request->name,
            'tgl_lahir' => $request->tgl_lahir,
            'telepon'   => $request->telepon,
            // 'id_cabang' => $request->id_cabang,
            'alamat'    => $request->alamat,
            // 'password'  => Hash::make($request->password),
            'updated_at' => now(),
        ]);

        $role = Role::findById($request->id_roles, 'web');
        $user->syncRoles([$role->name]);

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::exists('public/' . $user->foto)) {
                Storage::delete('public/' . $user->foto);
            }

            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('uploads/users', $fotoName, 'public');

            $user->foto = $fotoPath;
            $user->save();
        }

        notify()->success('Data User berhasil diperbarui!');
        return redirect()->route('users.index');
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string',
        ]);
        $user = User::findOrFail($id);

        try {
            if (!Hash::check($request->password, $user->password)) {
                notify()->error('Gagal delete akun, password salah');
                return redirect()->back();
            }

            if ($user->foto && Storage::exists('public/' . $user->foto)) {
                Storage::delete('public/' . $user->foto);
            }

            $user->roles()->detach();
            $user->permissions()->detach();

            $user->delete();

            notify()->success('User berhasil dihapus!');
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            notify()->error('Terjadi kesalahan saat menghapus user!');
            return redirect()->route('users.index');
        }
    }
}
