<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/LOGO-QUANTUM.png') }}">
    <title>{{ $title ?? '' }} | Penilaian Siswa</title>

    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg,#667eea,#764ba2);
            min-height: 100vh;
            overflow: auto !important; /* 🔑 FIX CLICK */
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 250px;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(10px);
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar a {
            color: white !important;
            text-decoration: none !important;
        }

        /* MENU */
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item { margin: 6px 0; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            font-size: 14px;
            font-weight: 500;
            color: white;
            border-left: 4px solid transparent;
            transition: all .25s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255,255,255,0.22);
            border-left-color: #00e5ff;
            transform: translateX(6px);
        }

        .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 18px;
        }

        /* LOGOUT */
        .logout-section {
            margin-top: auto;
            padding: 20px;
        }

        .logout-btn {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            border: none;
            background: rgba(220,53,69,.9);
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: rgba(220,53,69,1);
        }

        /* ================= MAIN ================= */
        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            position: relative;
            z-index: 1; /* 🔑 FIX */
        }

        .content-section {
            background: #fff;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,.15);
        }

/* ================= BADGE ROLE FIX ================= */
.badge {
    display: inline-block;
    min-width: 70px;
    padding: 6px 12px;
    border-radius: 999px; /* pill */
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    white-space: nowrap;
}

/* WARNA ROLE */
.badge-danger {
    background: #ef4444;
    color: #fff;
}

.badge-primary {
    background: #3b82f6;
    color: #fff;
}

.badge-success {
    background: #22c55e;
    color: #fff;
}

.badge-warning {
    background: #facc15;
    color: #1f2937;
}

.badge-info {
    background: #6366f1;
    color: #fff;
}

/* TABEL USERS */
.table td,
.table th {
    vertical-align: middle;
}

/* KOLOM AKSI */
.table td:last-child {
    white-space: nowrap;
}

/* BUTTON AKSI */
.table td .btn {
    padding: 6px 14px;
    font-size: 13px;
    border-radius: 999px;
}


        /* ================= FORM GLOBAL ================= */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 10px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 600;
    margin-bottom: 6px;
    color: #374151;
}

/* INPUT & SELECT */
.form-input,
.form-select {
    width: 100% !important;
    min-height: 44px;
    padding: 10px 14px;
    font-size: 15px;
    border-radius: 10px;
    border: 1.6px solid #d1d5db;
    background: #fff;
    transition: all .2s ease;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102,126,234,.18);
}

/* FIELD FULL WIDTH */
.form-group.full {
    grid-column: span 2;
}

/* BUTTON */
.btn {
    padding: 12px 22px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    border: none;
}

.btn-success {
    background: linear-gradient(135deg,#22c55e,#16a34a);
    color: #fff;
}

.btn-danger {
    background: linear-gradient(135deg,#ef4444,#dc2626);
    color: #fff;
}

/* ================= FORM ACTION ================= */
.form-action {
    display: flex;
    justify-content: flex-start; /* tombol ke kanan */
    margin-top: 20px;
    margin-bottom: 10px;
}

.form-action .btn {
    min-width: 180px;
    padding: 12px 24px;
    font-size: 15px;
    border-radius: 999px; /* pill */
}


/* ================= RESPONSIVE FORM ================= */
@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full {
        grid-column: span 1;
    }
}


        /* ================= TABLE & DATATABLE ================= */
        table.dataTable { width:100%!important; }
        .dataTables_wrapper { width:100%; }

        .table {
            width:100%;
            border-collapse:collapse;
            border-radius:12px;
            overflow:hidden;
        }

        .table thead th {
            background: linear-gradient(135deg, #667eea, #5a67d8);
            color: white;
            font-weight: 600;
            text-align: center;
        }

        .table tbody td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
        }

/* ================= IMPORT & TEMPLATE ================= */
.import-wrapper {
    margin-top: 12px;
}

.import-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
}

.btn {
    padding: 10px 16px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Download template */
.btn-outline {
    border: 1.5px dashed #7b88ff;
    color: #5b6cff;
    background: #f5f7ff;
    justify-content: flex-end;
}

.btn-outline:hover {
    background: #e9ecff;
}

/* Upload file */
.btn-upload {
    background: #eef2ff;
    color: #4f46e5;
}

.btn-upload:hover {
    background: #e0e7ff;
}

/* Import */
.btn-import {
    background: #22c55e;
    color: white;
}

.btn-import:hover {
    background: #16a34a;
}

.file-name {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    color: #6b7280;
}


        /* ================= MOBILE OVERLAY ================= */
        .desktop-only-overlay {
            display: none;
        }

        @media(max-width:768px){
            .desktop-only-overlay{
                display:flex;
                position:fixed;
                inset:0;
                background:rgba(0,0,0,.9);
                z-index:9999;
                color:#fff;
                justify-content:center;
                align-items:center;
                text-align:center;
                padding:30px;
                font-size:18px;
            }
        }

        .footer {
    margin-top: 40px;
    padding: 15px 10px;
    text-align: center;
    font-size: 14px;
    color: #777;
    border-top: 1px solid #eaeaea;
    background: #f9f9f9;
}

body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.footer {
    margin-top: auto;
}


    </style>
</head>

<body>

@php
    $role = auth()->user()->role ?? null;
@endphp

{{-- OVERLAY MOBILE (NON SISWA) --}}
@if($role !== 'siswa')
<div class="desktop-only-overlay">
    Aplikasi ini hanya dapat digunakan pada layar desktop / tablet
</div>
@endif

<div class="container">
    <x-sidebar/>
    <main class="main-content">
        {{ $slot }}
    </main>
</div>

{{-- 🔑 FORCE FIX INTERACTION --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.body.style.pointerEvents = 'auto';
    document.body.style.overflow = 'auto';
});
</script>




</body>

<footer class="footer">
    Copyright © 2025 – <strong>Quantum IDEA</strong> All Rights Reserved
</footer>

</html>
