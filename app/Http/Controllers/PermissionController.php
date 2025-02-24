<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request) {

        $title = 'Tabel Permission';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel Permission', 'url' => null],
        ];
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $permission = Permission::when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            });
        })->paginate($perPage);


        return view('permission.index', compact('title', 'breadcrumbs', 'permission', 'perPage'));
    }

    public function store(Request $request) {

        $request->validate([
            'name' => 'required',
        ]);

        Permission::create($request->all());

        notify()->success('Data berhasil ditambah');
        return redirect()->back();
    }

    public function update(Request $request, $id) {

        $request->validate([
            'name' => 'required',
        ]);

        $permission = Permission::findOrFail($id);

        $permission->update($request->all());

        notify()->success('Data berhasil diupdate');
        return redirect()->back();
    }

    public function destroy($id) {
        // dd($id);
        $permission = Permission::findOrFail($id);

        $permission->delete();

        notify()->success('Data berhasil dihapus');
        return redirect()->back();
    }
 }
