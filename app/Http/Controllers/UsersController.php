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

        $title = 'Users';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Users', 'url' => route('users.index')],
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

        $users = User::with(['roles'])->get();
        // $users = Karyawan::with(['user', 'cabang', 'role'])->get();

        return view('users.index', compact('breadcrumbs', 'title', 'users'));
    }

    public function create()
    {
        $title = 'Karyawan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Karyawan', 'url' => route('users.index')],
            ['label' => 'Form Tambah', 'url' => null],
        ];
        // $user = User::all();
        // dd($user);
        $roles = Role::pluck('name', 'id');

        // dd($cabang);
        return view('users.create', compact('breadcrumbs', 'title', 'roles'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'id_roles'          => 'required|exists:roles,id',
            'password'          => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'id_role'              => $request->id_roles,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $role = Role::findById($request->id_roles, 'web');
        $user->syncRoles([$role->name]);


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

        $roles = Role::pluck('name', 'id');

        $userRole = $user->getRoleNames();

        return view('users.edit', compact('title', 'breadcrumbs', 'user', 'roles', 'userRole'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'id_roles'  => 'required|exists:roles,id',
            'password'          => 'nullable|string|min:8|confirmed',
        ]);

        // dd($request->all());
        $user = User::findOrFail($id);

        $data = [
            'name' => $request->name,
            'id_role' => $request->id_roles,
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $role = Role::findById($request->id_roles, 'web');
        $user->syncRoles([$role->name]);

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
