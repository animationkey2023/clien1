<x-layout title="Data Siswa">

<link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

<section class="content-section">

    <h1 class="page-title">Data Siswa</h1>

    {{-- ================= FORM TAMBAH SISWA ================= --}}
    <div class="content-section" style="margin-bottom:32px">

        <h3 style="margin-bottom:20px">Tambah Siswa</h3>

        <form action="{{ route('siswa.store') }}" method="POST">
            @csrf

            <div class="form-grid">

                {{-- NAMA --}}
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text"
                           name="nama"
                           class="form-input"
                           placeholder="Nama siswa"
                           value="{{ old('nama') }}"
                           required>
                    @error('nama')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
                </div>

                {{-- NIS --}}
                <div class="form-group">
                    <label class="form-label">NIS</label>

                    <div style="display:flex;gap:10px;align-items:center">
                        {{-- PREFIX --}}
                        <input type="text"
                               class="form-input"
                               value="131232750027"
                               readonly
                               style="width:120px">

                        {{-- 6 DIGIT TERAKHIR --}}
                        <input type="text"
                               id="nis_suffix"
                               class="form-input"
                               maxlength="6"
                               inputmode="numeric"
                               placeholder="6 digit terakhir"
                               required>
                    </div>

                    <small id="nisInfo" style="color:#666">
                        Masukkan 6 digit terakhir
                    </small>

                    {{-- NIS FINAL --}}
                    <input type="hidden" name="nis" id="nis">

                    @error('nis')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
                </div>

                {{-- EMAIL --}}
<!--<div class="form-group">
    <label class="form-label">Email Akun Siswa</label>
    <input type="email"
           name="email"
           class="form-input"
           placeholder="email@siswa.sch.id"
           value="{{ old('email') }}"
           required>
    @error('email')
        <small style="color:red">{{ $message }}</small>
    @enderror
</div>

{{-- PASSWORD --}}
<div class="form-group">
    <label class="form-label">Password Akun Siswa</label>
    <input type="password"
           name="password"
           class="form-input"
           placeholder="Password login siswa"
           required>
    @error('password')
        <small style="color:red">{{ $message }}</small>
    @enderror
</div>-->


                {{-- KELAS --}}
                <div class="form-group">
                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-select" required>
                        <option value="">Pilih Kelas</option>
                        <option value="X" {{ old('kelas')=='X'?'selected':'' }}>X</option>
                        <option value="XI" {{ old('kelas')=='XI'?'selected':'' }}>XI</option>
                        <option value="XII" {{ old('kelas')=='XII'?'selected':'' }}>XII</option>
                    </select>
                    @error('kelas')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <div style="margin-top:20px">
                <button type="submit" class="btn btn-success">
                    ➕ Tambah Siswa
                </button>
            </div>
        </form>

        {{-- ================= IMPORT & TEMPLATE ================= --}}
        <form action="{{ route('data-siswa.import') }}"
              method="POST"
              enctype="multipart/form-data"
              class="import-wrapper"
              style="margin-top:20px">
            @csrf

            <div class="import-actions">



                <label class="btn btn-upload">
                    📂 Pilih File Excel
                    <input type="file" name="file" accept=".xlsx,.xls" hidden required
                           onchange="document.getElementById('file-name').innerText = this.files[0].name">
                </label>

                <button type="submit" class="btn btn-import">
                    ⬆ Import Excel
                </button>

                <!-- <a href="{{ route('template.siswa') }}" class="btn btn-outline">
                    📄 Download Template
                </a> -->

            </div>

            <small id="file-name" class="file-name">Belum ada file dipilih</small>
        </form>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

    </div>

    {{-- ================= TABLE SISWA ================= --}}
    <div class="content-section">

        <h3 style="margin-bottom:20px">Daftar Siswa</h3>

        <div style="overflow-x:auto">
            <table class="table" id="myTable">
                <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($dataSiswa as $i => $data)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $data->nis }}</td>
                        <td>{{ $data->nama }}</td>
                        <td>{{ $data->kelas }}</td>
                    {{-- AKSI --}}
    <td>
        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'guru_bk,guru,kepsek')
    {{-- tombol edit & hapus --}}
@endif

        <a href="{{ route('data.edit', $data->id) }}"
           class="btn btn-sm btn-success">
            Edit
        </a>

        <form action="{{ route('data.delete', $data->id) }}"
              method="POST"
              style="display:inline-block"
              onsubmit="return confirm('Yakin hapus siswa ini?')">
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

    </div>

</section>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="//cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

<script>
    new DataTable('#myTable', {
        ordering: false,
        pageLength: 10
    });

    const prefix = '131232750027';
    const suffixInput = document.getElementById('nis_suffix');
    const nisInput = document.getElementById('nis');
    const nisInfo = document.getElementById('nisInfo');

    suffixInput.addEventListener('input', function () {
        if (this.value.length === 6 && /^\d+$/.test(this.value)) {
            nisInput.value = prefix + this.value;
            nisInfo.innerText = 'NIS: ' + nisInput.value;
            nisInfo.style.color = 'green';
        } else {
            nisInput.value = '';
            nisInfo.innerText = 'Masukkan 6 digit terakhir';
            nisInfo.style.color = '#666';
        }
    });
</script>

</x-layout>
