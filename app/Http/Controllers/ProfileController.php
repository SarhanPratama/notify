<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index() {
        $title = 'Profile';
    $breadcrumbs = [
        ['label' => 'Home', 'url' => route('admin.dashboard')],
        ['label' => 'Profile', 'url' => null],
    ];

    $id = Auth::user()->id;

    $user = User::select('id', 'name', 'email', 'updated_at')
                ->with(['Karyawan:usia,telepon,alamat,tgl_lahir,foto,id_users,id_roles,id_cabang'], ['role:id,name'], ['cabang:id,nama'])
                ->where('id', $id)->first();
    // dd($user);
        return view('profile.index', compact('title', 'breadcrumbs', 'user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'usia' => 'nullable|integer|min:18|max:100',
            'phone' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user data
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if ($user->Karyawan) {
            $user->Karyawan->usia = $validatedData['usia'] ?? $user->Karyawan->usia;
            $user->Karyawan->telepon = $validatedData['phone'] ?? $user->Karyawan->telepon;
            $user->Karyawan->alamat = $validatedData['alamat'] ?? $user->Karyawan->alamat;
        }

        // Handle file upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/karyawan/'), $filename);

            // Delete old photo if exists
            if ($user->Karyawan && $user->Karyawan->foto && file_exists(public_path('uploads/karyawan/' . $user->Karyawan->foto))) {
                unlink(public_path('uploads/karyawan/' . $user->Karyawan->foto));
            }

            $user->Karyawan->foto = $filename;
        }

        // Save changes
        $user->save();
        if ($user->Karyawan) {
            $user->Karyawan->save();
        }

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }

}
