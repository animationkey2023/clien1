<x-layout title="Edit Pengguna">
    <section id="data-siswa" class="content-section">
        <h1 class="page-title">Edit Pengguna</h1>

        <div class="form-grid">
            <div>
                <h3>Edit Data Pengguna</h3>
                <form id="form-siswa" action="{{ route('users.update',$user->id) }}" method="POST"
                      style="margin-top: 15px">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-input" name="name"
                               value="{{ $user->name }}">
                        @error('name')
                        <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Pengguna</label>
                        <input type="text" class="form-input" name="username"
                               value="{{ $user->username }}">
                        @error('username')
                        <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-input" name="email"
                               value="{{ $user->email }}">
                        @error('email')
                        <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
    <label class="form-label">Password Baru <small class="text-muted">(opsional)</small></label>
    <input type="password"
           name="password"
           class="form-input"
           placeholder="Kosongkan jika tidak ingin mengubah">

</div>

<div class="form-group" style="margin-top: 12px;">
    <label class="form-label">Konfirmasi Password Baru</label>
    <input type="password"
           name="password_confirmation"
           class="form-input"
           placeholder="Ulangi password baru">
</div>


                    <div class="form-group">
                        <label class="form-label">Role</label>

                        <select class="form-select" name="role">
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="guru_bk" {{ $user->role == 'guru_bk' ? 'selected' : '' }}>Guru BK</option>
                            <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="kepsek" {{ $user->role == 'kepsek'?  'selected' : '' }}>Kepala Sekolah</option>
                            <option value="siswa" {{ $user->role == 'siswa' ?'selected' : '' }}>Siswa</option>
                        </select>
                    </div>

                    <div>
                        <a onclick="kembali()" class="btn">Kembali</a>
                        <button type="submit" class="btn btn-success">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        function kembali() {
            window.location.href = '/users';
        }
    </script>
</x-layout>
