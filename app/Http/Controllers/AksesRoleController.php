<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Laravel\Prompts\search;

class AksesRoleController extends Controller
{
    public function index(Request $request) {

        $title = 'Akses Role';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akses Role', 'url' => route('akses-role.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $search = $request->input('search');

        $perPage = $request->input('per_page', 10);

        $role = Role::with('permissions')->when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                ->orWhereHas('permissions', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                });
        })->paginate($perPage);


        // $role = Role::with('permissions')->get();

        $permissions = Permission::pluck('name', 'id');

        // dd($permissions);

        return view('akses-role.index', compact('role', 'permissions', 'title', 'breadcrumbs'));
    }

    // public function edit($id) {

    //     $role = Role::findOrFail($id);


    //     $permissions = Permission::orderBy('name', 'ASC');
    //     // dd($permissions);
    //     return view('akses-role.create', compact('permissions', 'role'));
    // }

    public function update(Request $request, Role $akses_role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
        ]);

        $akses_role->syncPermissions($request->permissions);

        return redirect()->route('akses-role.index');
    }
}
