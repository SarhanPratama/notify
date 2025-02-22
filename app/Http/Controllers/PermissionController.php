<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request) {

        $title = 'Tabel Role';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel Permission', 'url' => null],
        ];

        $search = $request->input('search');

        $permission = Permission::when($search, function($query, $search) {
            $query->where('name', 'LIKE', "%{$search}");
        })->paginate(10);


        return view('permission.index', compact('title', 'breadcrumbs', 'permission'));
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
