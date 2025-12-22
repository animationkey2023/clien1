<nav class="sidebar">

    {{-- LOGO & USER --}}
    <div class="logo" style="
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:10px;
    margin-bottom:30px;
">
    <img src="{{ asset('img/LOGO-QUANTUM.png') }}" width="70" alt="Logo">

    <div style="text-align:center;color:white;line-height:1.3">
        <strong style="font-size:15px;">
            {{ auth()->user()->name }}
        </strong><br>
        <span style="font-size:12px;opacity:.85">
            {{ strtoupper(str_replace('_',' ',auth()->user()->role)) }}
        </span>
    </div>
</div>


    @php $role = auth()->user()->role; @endphp

    {{-- MENU --}}
    <ul class="nav-menu">

        {{-- HOME --}}
        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span>
                <span>Home</span>
            </a>
        </li>

        {{-- DATA SISWA --}}
        @if($role === 'admin')
        <li class="nav-item">
            <a href="{{ route('data_siswa') }}"
               class="nav-link {{ request()->is('data_siswa') ? 'active' : '' }}">
                <span class="nav-icon">👥</span>
                <span>Data Siswa</span>
            </a>
        </li>
        @endif

        {{-- PENILAIAN --}}
        @if(in_array($role,['admin','kepsek','guru','guru_bk']))
        <li class="nav-item">
            <a href="{{ route('penilaian_siswa') }}"
               class="nav-link {{ request()->is('penilaian_siswa') ? 'active' : '' }}">
                <span class="nav-icon">⭐</span>
                <span>Penilaian</span>
            </a>
        </li>
        @endif

        {{-- LAPORAN --}}
        <li class="nav-item">
            <a href="{{ route('laporan_siswa') }}"
               class="nav-link {{ request()->is('laporan_siswa') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                <span>Laporan</span>
            </a>
        </li>

        {{-- PENGGUNA --}}
        @if($role === 'admin')
        <li class="nav-item">
            <a href="{{ route('users.index') }}"
               class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <span class="nav-icon">👤</span>
                <span>Pengguna</span>
            </a>
        </li>
        @endif

    </ul>

    {{-- LOGOUT --}}
    <div class="logout-section">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <span class="nav-icon">🚪</span> Logout
            </button>
        </form>
    </div>

</nav>
