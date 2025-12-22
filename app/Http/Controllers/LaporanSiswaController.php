<?php

namespace App\Http\Controllers;

use App\Models\PenilaianSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanSiswaController extends Controller
{
    /* =========================
     *  INDEX LAPORAN
     * ========================= */
    public function index(Request $request)
    {
        $user = Auth::user();
        $allowedRoles = ['admin', 'guru_bk', 'guru', 'siswa', 'kepsek'];

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman laporan.');
        }

        $kelasFilter = $request->query('kelas');

        /* =========================
         *  QUERY DASAR RANKING
         * ========================= */
        $query = DB::table('penilaian_siswa')
            ->join('data_siswa', 'penilaian_siswa.siswa_id', '=', 'data_siswa.id')
            ->select(
                'data_siswa.id',
                'data_siswa.nama',
                'data_siswa.kelas',
                DB::raw('COALESCE(SUM(penilaian_siswa.poin),0) AS total_poin'),
                DB::raw('SUM(CASE WHEN penilaian_siswa.jenis = "prestasi" THEN 1 ELSE 0 END) AS total_prestasi'),
                DB::raw('SUM(CASE WHEN penilaian_siswa.jenis = "pelanggaran" THEN 1 ELSE 0 END) AS total_pelanggaran')
            )
            ->groupBy('data_siswa.id', 'data_siswa.nama', 'data_siswa.kelas');

        /* =========================
         *  FILTER ROLE SISWA
         * ========================= */
        if ($user->role === 'siswa') {
            if (!$user->siswa) {
                abort(403, 'Data siswa belum terhubung.');
            }

            $siswaId = $user->siswa->id;

            // ranking hanya siswa login
            $query->where('data_siswa.id', $siswaId);

            // 🔢 total prestasi siswa ini
            $jmlPrestasi = PenilaianSiswa::where('siswa_id', $siswaId)
                ->where('jenis', 'prestasi')
                ->count();

            // 🔢 total pelanggaran siswa ini
            $jmlMasalah = PenilaianSiswa::where('siswa_id', $siswaId)
                ->where('jenis', 'pelanggaran')
                ->count();

            // tidak dipakai untuk siswa
            $presentasePrestasi = 0;
            $presentaseMasalah  = 0;

        } else {

            /* =========================
             *  FILTER KELAS (NON SISWA)
             * ========================= */
            if ($kelasFilter) {
                $query->where('data_siswa.kelas', $kelasFilter);
            }

            // statistik global
            $jmlPrestasi = PenilaianSiswa::where('jenis', 'prestasi')->count();
            $jmlMasalah  = PenilaianSiswa::where('jenis', 'pelanggaran')->count();

            $totalSiswa = DB::table('data_siswa')->count();

            $presentasePrestasi = $totalSiswa > 0
                ? ($jmlPrestasi / $totalSiswa) * 100
                : 0;

            $presentaseMasalah = $totalSiswa > 0
                ? ($jmlMasalah / $totalSiswa) * 100
                : 0;
        }

        $dataSiswa = $query->orderByDesc('total_poin')->get();

        return view('laporan', compact(
            'presentasePrestasi',
            'presentaseMasalah',
            'jmlPrestasi',
            'jmlMasalah',
            'dataSiswa',
            'kelasFilter'
        ));
    }

    /* =========================
     *  PDF VIEW
     * ========================= */
    public function pdf(Request $request)
    {
        $user = Auth::user();
        $allowedRoles = ['admin', 'guru_bk', 'guru', 'siswa', 'kepsek'];

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman laporan.');
        }

        $kelasFilter = $request->query('kelas');

        $query = DB::table('penilaian_siswa')
            ->join('data_siswa', 'penilaian_siswa.siswa_id', '=', 'data_siswa.id')
            ->select(
                'data_siswa.id',
                'data_siswa.nama',
                'data_siswa.kelas',
                DB::raw('COALESCE(SUM(penilaian_siswa.poin),0) AS total_poin'),
                DB::raw('SUM(CASE WHEN penilaian_siswa.jenis = "prestasi" THEN 1 ELSE 0 END) AS total_prestasi'),
                DB::raw('SUM(CASE WHEN penilaian_siswa.jenis = "pelanggaran" THEN 1 ELSE 0 END) AS total_pelanggaran')
            )
            ->groupBy('data_siswa.id', 'data_siswa.nama', 'data_siswa.kelas');

        if ($user->role === 'siswa') {

            if (!$user->siswa) {
                abort(403, 'Data siswa belum terhubung.');
            }

            $siswaId = $user->siswa->id;
            $query->where('data_siswa.id', $siswaId);

            $jmlPrestasi = PenilaianSiswa::where('siswa_id', $siswaId)
                ->where('jenis', 'prestasi')
                ->count();

            $jmlMasalah = PenilaianSiswa::where('siswa_id', $siswaId)
                ->where('jenis', 'pelanggaran')
                ->count();

            $presentasePrestasi = 0;
            $presentaseMasalah  = 0;

        } else {

            if ($kelasFilter) {
                $query->where('data_siswa.kelas', $kelasFilter);
            }

            $jmlPrestasi = PenilaianSiswa::where('jenis', 'prestasi')->count();
            $jmlMasalah  = PenilaianSiswa::where('jenis', 'pelanggaran')->count();

            $totalSiswa = DB::table('data_siswa')->count();

            $presentasePrestasi = $totalSiswa > 0
                ? ($jmlPrestasi / $totalSiswa) * 100
                : 0;

            $presentaseMasalah = $totalSiswa > 0
                ? ($jmlMasalah / $totalSiswa) * 100
                : 0;
        }

        $dataSiswa = $query->orderByDesc('total_poin')->get();

        return view('laporan_pdf', compact(
            'presentasePrestasi',
            'presentaseMasalah',
            'jmlPrestasi',
            'jmlMasalah',
            'dataSiswa',
            'kelasFilter'
        ));
    }

    /* =========================
     *  PDF DOWNLOAD
     * ========================= */
    public function pdfDownload(Request $request)
    {
        return $this->pdf($request);
    }
}
