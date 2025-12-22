<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataSiswaRequest;
use App\Models\DataSiswa;
use Illuminate\Http\Request;
use App\Imports\DataSiswaImport;
use Maatwebsite\Excel\Facades\Excel;

class DataSiswaController extends Controller
{
    /* =========================
     *  INDEX
     * ========================= */
    public function index()
    {
        $dataSiswa = DataSiswa::all();
        return view('data_siswa', compact('dataSiswa'));
    }

    /* =========================
     *  STORE (TAMBAH MANUAL)
     * ========================= */
    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'nis'   => 'required|digits:18|unique:data_siswa,nis',
            'kelas' => 'required'
        ], [
            'nis.required' => 'NIS tidak boleh kosong',
            'nis.digits'   => 'NIS harus 18 digit',
            'nis.unique'   => 'NIS sudah terdaftar',
        ]);

        DataSiswa::create([
            'nama'  => $request->nama,
            'nis'   => $request->nis,
            'kelas' => $request->kelas,
        ]);

        return back()->with('success', 'Siswa berhasil ditambahkan');
    }

    /* =========================
     *  IMPORT EXCEL
     * ========================= */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        try {
            $import = new DataSiswaImport();
            Excel::import($import, $request->file('file'));

            return back()->with(
                'success',
                "Import selesai. Berhasil: {$import->berhasil}, Duplikat: {$import->duplikat}"
            );

        } catch (\Throwable $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    /* =========================
     *  EDIT
     * ========================= */
    public function edit($id)
    {
        $dataSiswa = DataSiswa::findOrFail($id);
        return view('edit_siswa', compact('dataSiswa'));
    }

    /* =========================
     *  UPDATE
     * ========================= */
    public function update(DataSiswaRequest $request, $id)
    {
        $validated = $request->validated();

        $siswa = DataSiswa::findOrFail($id);

        $siswa->update([
            'nama'  => $validated['nama'],
            'nis'   => $validated['nis'],
            'kelas' => $validated['kelas'],
        ]);

        return redirect()
            ->route('data_siswa')
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    /* =========================
     *  DELETE
     * ========================= */
    public function destroy($id)
    {
        $data = DataSiswa::findOrFail($id);
        $data->delete();

        return back()->with('success', 'Data siswa berhasil dihapus');
    }
}
