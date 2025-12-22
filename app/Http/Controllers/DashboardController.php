<?php

namespace App\Http\Controllers;

use App\Models\DataSiswa;
use App\Models\PenilaianSiswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display dashboard.
     */
    public function index()
{
    $user = Auth::user();

    // ===============================
    // AUTO LINK SISWA KE DATA_SISWA
    // ===============================
    if ($user->role === 'siswa' && !$user->siswa) {
        DataSiswa::create([
            'user_id' => $user->id,
            'nama'    => $user->name ?? 'Siswa',
            'nis'     => 'TEMP-' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
            'kelas'   => 'BELUM DITENTUKAN',
        ]);

        $user->load('siswa');
    }

    $jmlSiswa = DataSiswa::count();

    $penilaianSiswa = collect();
    $jmlPrestasi = 0;
    $jmlMasalah  = 0;
    $rataPoin    = 0;

    // =========================
    // ROLE SISWA
    // =========================
    if ($user->role === 'siswa') {

    if (!$user->siswa) {
        abort(403, 'Data siswa belum terhubung');
    }

    $siswaId = $user->siswa->id;

    // =========================
    // RIWAYAT: SEMUA MILIK SISWA
    // =========================
    $penilaianSiswa = PenilaianSiswa::where('siswa_id', $siswaId)
        ->orderByDesc('tanggal')
        ->get();

    // =========================
    // STATISTIK: AKUMULATIF
    // =========================
    $jmlPrestasi = PenilaianSiswa::where('siswa_id', $siswaId)
        ->where('jenis', 'prestasi')
        ->count();

    $jmlMasalah = PenilaianSiswa::where('siswa_id', $siswaId)
        ->where('jenis', 'pelanggaran')
        ->count();

    return view('dashboard', compact(
        'jmlPrestasi',
        'jmlMasalah',
        'penilaianSiswa'
    ));
}


    // =========================
    // ROLE ADMIN / GURU / BK / KEPSEK
    // =========================
    $penilaianSiswa = PenilaianSiswa::with('siswa')
    ->orderByDesc('tanggal')
    ->get();

$jmlPrestasi = PenilaianSiswa::where('jenis', 'prestasi')->count();

$jmlMasalah = PenilaianSiswa::where('jenis', 'pelanggaran')->count();


    return view('dashboard', compact(
        'jmlSiswa',
        'jmlPrestasi',
        'jmlMasalah',
        'penilaianSiswa'
    ));
}


    /**
     * Landing Page
     */
    public function landingPage()
    {
        return view('landingpage');
    }

    // ================= UNUSED =================
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
