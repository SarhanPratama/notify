<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request) {

        $title = 'Role';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Role', 'url' => route('role.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        // $perPage = $request->input('per_page');

        // $search = $request->input('search');



        // $role = Role::when($search, function ($query, $search) {
        //     return $query->where('name', 'LIKE', "%{$search}");
        // })->paginate($perPage);

        $role = Role::pluck('name', 'id');

        return view('role.index', compact('breadcrumbs', 'title', 'role'));
    }

    public function store(Request $request) {

        $request->validate([
            'name' => 'required'
        ]);

        Role::create($request->all());

        return redirect()->back();
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required',
        ]);

        $role = Role::findOrFail($id);

        $role->update($request->all());

        notify()->success('Data Berhasil Diupdate');
        return redirect()->back();
    }

    public function destroy($id)
{
    try {
        $role = Role::findOrFail($id);
        $role->delete();
        notify()->success('Berhasil menghapus data');
        return redirect()->back();
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menghapus role');
    }
}
}
