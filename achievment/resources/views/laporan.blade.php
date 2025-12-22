<x-layout title="Laporan">
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

    <style>
        #myTable {
            width: 100%;
            table-layout: fixed;
        }

        .badge-primary {
            background-color: #0056b3 !important;
            color: #ffffff !important;
        }

        .badge-success {
            background-color: #28a745 !important;
            color: #ffffff !important;
        }

        .badge-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        .badge-danger {
            background-color: #dc3545 !important;
            color: #ffffff !important;
        }

        .point-positive {
            color: #28a745;
            font-weight: bold;
        }

        .point-negative {
            color: #dc3545;
            font-weight: bold;
        }

        /* ===== STAT CARD ===== */
        .stats-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .stat-card {
            flex: 1;
            max-width: 320px;
            border-radius: 12px;
            padding: 24px 0 16px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            text-align: center;
            font-weight: 600;
        }

        .stat-number {
            font-size: 2.2rem;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .stat-label {
            font-size: 1.05rem;
            color: #555;
        }

        .total-prestasi {
            background: #e6f7ec;
            color: #218838;
        }

        .total-pelanggaran {
            background: #fff7e6;
            color: #ff9800;
        }

        /* ===== PRINT ===== */
        @media print {
            body * {
                visibility: hidden;
            }

            .content-section, .content-section * {
                visibility: visible;
            }

            .content-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100vw;
                background: #fff !important;
                box-shadow: none !important;
            }

            .stats-grid,
            .table-container {
                page-break-inside: avoid;
            }

            nav, header, footer, .no-print {
                display: none !important;
            }
        }

        /* ===== MOBILE ===== */
        @media (max-width: 768px) {
            .stats-grid {
                flex-direction: column;
                gap: 12px;
            }

            .stat-card {
                max-width: 100%;
                padding: 16px 0 10px 0;
            }

            .table-container {
                overflow-x: auto;
            }

            .table {
                font-size: 13px;
                min-width: 650px;
            }
        }
    </style>

    <section id="laporan" class="content-section">

        <button class="no-print"
                onclick="printLaporanPDF()"
                style="margin-bottom: 18px; padding: 8px 20px; background: #1565c0; color: #fff; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; float: right;">
            🖨️ Print
        </button>

        <h1 class="page-title">Laporan</h1>

        <!-- ===== STAT ===== -->
        <div class="stats-grid">
            <div class="stat-card total-prestasi">
                <div class="stat-number">{{ $jmlPrestasi }}</div>
                <div class="stat-label">Total Prestasi</div>
            </div>

            <div class="stat-card total-pelanggaran">
                <div class="stat-number">{{ $jmlMasalah }}</div>
                <div class="stat-label">Total Pelanggaran</div>
            </div>
        </div>

        <!-- ===== TABLE ===== -->
        <div class="table-container" style="padding: 0 10px 20px 10px;">
            <div class="no-print" style="margin-bottom: 12px;">
                <label for="filter-kelas" style="font-weight: 500; margin-right: 8px;">Pilih Kelas:</label>
                <select id="filter-kelas"
                        style="padding: 4px 12px; border-radius: 5px; border: 1px solid #bbb;">
                    <option value="" {{ empty($kelasFilter) ? 'selected' : '' }}>Semua</option>
                    <option value="X" {{ $kelasFilter == 'X' ? 'selected' : '' }}>X</option>
                    <option value="XI" {{ $kelasFilter == 'XI' ? 'selected' : '' }}>XI</option>
                    <option value="XII" {{ $kelasFilter == 'XII' ? 'selected' : '' }}>XII</option>
                </select>
            </div>

            <h3 style="padding: 16px; margin: 0; background: #f8f9fa; border-bottom: 1px solid #e0e0e0;">
                Ranking Siswa
            </h3>

            <div style="overflow-x: auto;">
                <table class="table" id="myTable">
                    <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Total Poin</th>
                        <th>Prestasi</th>
                        <th>Pelanggaran</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($dataSiswa as $rank => $data)
                        @php $rank += 1; @endphp
                        <tr>
                            <td>
                                @if ($rank == 1) 🥇
                                @elseif ($rank == 2) 🥈
                                @elseif ($rank == 3) 🥉
                                @endif
                                {{ $rank }}
                            </td>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->kelas }}</td>
                            <td>
                                <span class="{{ $data->total_poin >= 0 ? 'point-positive' : 'point-negative' }}">
                                    {{ $data->total_poin >= 0 ? '+' : '' }}{{ $data->total_poin }}
                                </span>
                            </td>
                            <td>{{ $data->total_prestasi }}</td>
                            <td>{{ $data->total_pelanggaran }}</td>
                            <td>
                                @php
                                    $poin = $data->total_poin;
                                    if ($poin >= 80) {
                                        $status = ['Teladan', 'success'];
                                    } elseif ($poin >= 50) {
                                        $status = ['Baik', 'primary'];
                                    } elseif ($poin >= 20) {
                                        $status = ['Cukup', 'warning'];
                                    } else {
                                        $status = ['Perhatian', 'danger'];
                                    }
                                @endphp
                                <span class="badge badge-{{ $status[1] }}">
                                    {{ $status[0] }}
                                </span>
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
        function printLaporanPDF() {
            let url = '/laporan_pdf';
            const kelas = document.getElementById('filter-kelas').value;
            if (kelas) url += '?kelas=' + encodeURIComponent(kelas);
            const win = window.open(url, '_blank');
            if (win) win.onload = () => win.print();
        }

        new DataTable('#myTable', {
            ordering: false
        });

        document.getElementById('filter-kelas').addEventListener('change', function () {
            const url = new URL(window.location.href);
            if (this.value) {
                url.searchParams.set('kelas', this.value);
            } else {
                url.searchParams.delete('kelas');
            }
            window.location.href = url.toString();
        });
    </script>
</x-layout>
