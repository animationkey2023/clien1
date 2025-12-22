<x-layout title="Penilaian">

<link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

<section class="content-section" style="max-width:900px;margin:auto">

    <h2 style="margin-bottom:20px">Penilaian Perilaku Siswa</h2>

    @php
        $role = auth()->user()->role;
    @endphp

    {{-- ================= FORM INPUT ================= --}}
    @if(in_array($role, ['admin','kepsek','guru','guru_bk']))
    <form action="{{ route('penilaian.store') }}" method="POST"
          style="background:#f9fafb;padding:25px;border-radius:12px;margin-bottom:35px">
        @csrf

        <div style="display:flex;flex-direction:column;gap:16px">

            <div>
                <label class="form-label">Siswa</label>
                <select name="siswa_id" class="form-select" required>
                    <option value="">Pilih Siswa</option>
                    @foreach ($dataSiswa as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Jenis</label>
                <select name="jenis" class="form-select" id="jenis-penilaian" required>
                    <option value="">Pilih Jenis</option>
                    <option value="prestasi">Prestasi</option>
                    <option value="pelanggaran">Pelanggaran</option>
                </select>
            </div>

            <div>
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select" id="kategori-penilaian" required>
                    <option value="">Pilih Kategori</option>
                </select>
            </div>

            <div>
                <label class="form-label">Poin</label>
                <input type="number" name="poin" class="form-input" required>
            </div>

            <div>
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-input" id="tanggal-penilaian" required>
            </div>

            <div>
                <label class="form-label">Keterangan</label>
                <input type="text" name="keterangan" class="form-input">
            </div>

            <div class="form-action">
                <button type="submit" class="btn btn-success">
                    💾 Simpan Penilaian
                </button>
            </div>

        </div>
    </form>
    @endif

    {{-- ================= RIWAYAT ================= --}}
    <h3 style="margin-bottom:15px">Riwayat Penilaian</h3>

    <div style="overflow-x:auto">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Siswa</th>
                    <th>Jenis</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Poin</th>
                    <th>Aksi</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($penilaianSiswa as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->tanggal }}</td>
                    <td>{{ $p->siswa->nama }}</td>
                    <td>
                        <span class="badge {{ $p->jenis == 'prestasi' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($p->jenis) }}
                        </span>
                    </td>
                    <td>{{ ucfirst($p->kategori) }}</td>
                    <td>{{ $p->keterangan }}</td>
                    <td class="{{ $p->jenis == 'prestasi' ? 'point-positive' : 'point-negative' }}">
                        {{ $p->jenis == 'prestasi' ? '+' : '-' }}{{ $p->poin }}
                    </td>
                     {{-- AKSI --}}
    <td>
        <a href="{{ route('penilaian.edit', $p->id) }}"
           class="btn btn-sm btn-success">
           Edit
        </a>

        <form action="{{ route('penilaian.delete', $p->id) }}"
              method="POST"
              style="display:inline-block;"
              onsubmit="return confirm('Yakin hapus data ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger">
                Hapus
            </button>
        </form>
    </td>
</tr>
@endforeach
</tbody>
        </table>
    </div>

</section>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="//cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

<script>
new DataTable('#myTable', {
    ordering: false,
    pageLength: 10
});

// limit tanggal hari ini
const tgl = document.getElementById('tanggal-penilaian');
if (tgl) {
    tgl.max = new Date().toISOString().split('T')[0];
}

// kategori dinamis
const jenis = document.getElementById('jenis-penilaian');
const kategori = document.getElementById('kategori-penilaian');

if (jenis && kategori) {
    jenis.addEventListener('change', function () {
        kategori.innerHTML = '<option value="">Pilih Kategori</option>';

        if (this.value === 'prestasi') {
            ['akademik','nonakademik'].forEach(v =>
                kategori.innerHTML += `<option value="${v}">${v}</option>`
            );
        }

        if (this.value === 'pelanggaran') {
            ['ringan','sedang','berat'].forEach(v =>
                kategori.innerHTML += `<option value="${v}">${v}</option>`
            );
        }
    });
}
</script>

</x-layout>
