<?php

namespace App\Http\Controllers;

use App\Models\DataSiswa;
use App\Models\PenilaianSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $allowedRoles = ['admin','guru_bk','guru','kepsek'];

        if (!in_array($user->role, $allowedRoles)) {
            return redirect('/non_admin');
        }

        $dataSiswa = DataSiswa::orderBy('nama')->get();
        $penilaianSiswa = PenilaianSiswa::orderByDesc('tanggal')->get();

        return view('penilaian', compact('dataSiswa', 'penilaianSiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:data_siswa,id',
            'jenis' => 'required|in:prestasi,pelanggaran',
            'kategori' => 'required',
            'keterangan' => 'required|max:255',
            'poin' => 'required|integer|min:1|max:100',
            'tanggal' => 'required|date',
        ]);

        $poin = $request->poin;

        // otomatis minus jika pelanggaran
        if ($request->jenis === 'pelanggaran') {
            $poin = -abs($poin);
        }

        PenilaianSiswa::create([
            'siswa_id' => $request->siswa_id,
            'jenis' => $request->jenis,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'poin' => $poin,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan');
    }

    // ===============================
    // ✏️ FORM EDIT
    // ===============================
    public function edit($id)
    {
        $penilaian = PenilaianSiswa::findOrFail($id);
        $dataSiswa = DataSiswa::orderBy('nama')->get();

        return view('penilaian_edit', compact('penilaian', 'dataSiswa'));
    }

    // ===============================
    // 🔄 UPDATE DATA
    // ===============================
    public function update(Request $request, $id)
    {
        $request->validate([
            'siswa_id' => 'required|exists:data_siswa,id',
            'jenis' => 'required|in:prestasi,pelanggaran',
            'kategori' => 'required',
            'keterangan' => 'required|max:255',
            'poin' => 'required|integer|min:1|max:100',
            'tanggal' => 'required|date',
        ]);

        $penilaian = PenilaianSiswa::findOrFail($id);

        $poin = $request->poin;

        // tetap konsisten: pelanggaran = minus
        if ($request->jenis === 'pelanggaran') {
            $poin = -abs($poin);
        }

        $penilaian->update([
            'siswa_id' => $request->siswa_id,
            'jenis' => $request->jenis,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'poin' => $poin,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('penilaian_siswa')
            ->with('success', 'Penilaian berhasil diperbarui');
    }

    // ===============================
    // 🗑️ HAPUS
    // ===============================
    public function destroy($id)
    {
        PenilaianSiswa::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus');
    }
}
