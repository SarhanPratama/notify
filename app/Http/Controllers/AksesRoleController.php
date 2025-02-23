<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AksesRoleController extends Controller
{
    public function index() {
        $role = Role::with('permissions')->get();
        $permissions = Permission::all();

        // dd($permissions);
        $title = 'Tabel Akses Role';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel Akses Role', 'url' => null],
        ];

        return view('akses-role.index', compact('role', 'permissions', 'title', 'breadcrumbs'));
    }

    // public function edit($id) {

    //     $role = Role::findOrFail($id);


    //     $permissions = Permission::orderBy('name', 'ASC');
    //     // dd($permissions);
    //     return view('akses-role.create', compact('permissions', 'role'));
    // }

    public function update(Request $request, Role $role) {

        $request->validate([
            'permissions' => 'nullable|array',
        ]);

        $role->syncPermissions($request->permissions);

        return redirect()->route('akses-role.index');
    }
}
