<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index() {
        $title = 'Profile';
    $breadcrumbs = [
        ['label' => 'Home', 'url' => route('admin.dashboard')],
        ['label' => 'Profile', 'url' => null],
    ];

    $id = Auth::user()->id;

    $user = User::where('id', $id)->first();

    // $user = User::select('id', 'name', 'email', 'updated_at')
    //                 ->with('karyawan', )
    //             ->with(['Karyawan:telepon,alamat,tgl_lahir,foto,id_users,id_roles,id_cabang'], ['role:id,name'], ['cabang:id,nama'])
    //             ->where('id', $id)->first();
    // dd($user);
        return view('profile.index', compact('title', 'breadcrumbs', 'user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'tgl_lahir' => 'required|date',
            'telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->tgl_lahir = $request->tgl_lahir;
        $user->telepon = $request->telepon;
        $user->alamat = $request->alamat;

        $user->save();

        notify()->success('Profile berhasil diupdate');
        return redirect()->route('profile.index');
    }


    public function updateFoto(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'foto.required' => 'Silakan pilih gambar untuk diunggah.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'foto.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        if (!$user->Karyawan) {
            notify()->error('Karyawan tidak ditemukan!');
            return redirect()->back();
        }

        if ($user->Karyawan->foto && file_exists(public_path('uploads/karyawan/' . $user->Karyawan->foto))) {
            unlink(public_path('uploads/karyawan/' . $user->Karyawan->foto));
        }

        $foto = $request->file('foto');
        $namaFoto = time() . '.' . $foto->extension();
        $foto->move(public_path('uploads/karyawan'), $namaFoto);

        $user->Karyawan->update(['foto' => $namaFoto]);

        notify()->success('Foto berhasil diupdate!');
        return redirect()->back();
    }

    public function destroy(Request $request, $id) {

        $request->validate([
            'password' => 'required|string',
        ]);

        $user = User::findOrFail($id);

        if (!Hash::check($request->password, $user->password)) {
            notify()->error('Gagal delete akun, password salah');
            return redirect()->back();
        }

        $user->roles()->detach();
        $user->permissions()->detach();

        if ($user->foto && file_exists(public_path('uploads/karyawan/' . $user->foto))) {
            unlink(public_path('uploads/karyawan/' . $user->foto));
        }

        $user->delete();

        Auth::logout();

        return redirect()->route('login')->with('success', 'Akun Anda berhasil dihapus.');
    }


}
