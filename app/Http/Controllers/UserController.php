<?php

namespace App\Http\Controllers;

use App\Models\DataSiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /* =========================
     *  LIST USER
     * ========================= */
    public function index()
    {
        $users = User::all();
        return view('users.user', compact('users'));
    }

    /* =========================
     *  FORM EDIT
     * ========================= */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /* =========================
     *  STORE (TAMBAH USER)
     * ========================= */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'role'     => 'required|in:admin,guru,kepsek,guru_bk,siswa',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // CREATE USER
        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email, // 🔑 WAJIB
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        // JIKA ROLE SISWA → AUTO CREATE DATA SISWA
        if ($user->role === 'siswa') {
            DataSiswa::create([
                'nama'    => $user->name,
                'nis'     => '000000000000000000', // dummy 18 digit
                'kelas'   => 'X',
                'status'  => 1,
                'user_id' => $user->id,
            ]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    /* =========================
     *  UPDATE USER
     * ========================= */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'role'     => 'required|in:admin,guru,kepsek,guru_bk,siswa',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name     = $request->name;
        $user->username = $request->username;
        $user->email    = $request->email;
        $user->role     = $request->role;

        // update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui');
    }

    /* =========================
     *  DELETE USER
     * ========================= */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // hapus data siswa jika user adalah siswa
        if ($user->role === 'siswa') {
            DataSiswa::where('user_id', $user->id)->delete();
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Data pengguna berhasil dihapus');
    }
}
