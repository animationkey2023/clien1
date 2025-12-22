<x-layout title="Dashboard">
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

    <style>
        #myTable {
            width: 100%;
            table-layout: fixed;
        }

        .stats-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            flex: 1;
            border-radius: 12px;
            padding: 24px 0 16px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            text-align: center;
            font-weight: 600;
        }

        .stat-card .stat-number {
            font-size: 2.2rem;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .stat-card .stat-label {
            font-size: 1.1rem;
            color: #555;
        }

        .stat-card.total-siswa {
            background: #e3f0ff;
            color: #1565c0;
        }

        .stat-card.prestasi {
            background: #e6f7ec;
            color: #218838;
        }

        .stat-card.pelanggaran {
            background: #ffeaea;
            color: #c62828;
        }

        .point-positive {
            color: #28a745;
            font-weight: bold;
        }

        .point-negative {
            color: #dc3545;
            font-weight: bold;
        }
    </style>

    <section id="home" class="content-section active">
        <h1 class="page-title">Dashboard Utama</h1>

        @php $role = auth()->user()->role; @endphp

        <!-- ===== STAT CARD ===== -->
        <div class="stats-grid">

    {{-- TOTAL SISWA (NON SISWA) --}}
    @if($role !== 'siswa')
        <div class="stat-card total-siswa">
            <div class="stat-number">{{ $jmlSiswa }}</div>
            <div class="stat-label">Total Siswa</div>
        </div>
    @endif

    {{-- TOTAL PRESTASI --}}
    <div class="stat-card prestasi">
        <div class="stat-number">{{ $jmlPrestasi }}</div>
        <div class="stat-label">Total Prestasi</div>
    </div>

    {{-- TOTAL PELANGGARAN --}}
    <div class="stat-card pelanggaran">
        <div class="stat-number">{{ $jmlMasalah }}</div>
        <div class="stat-label">Total Pelanggaran</div>
    </div>

</div>


        <!-- ===== RIWAYAT PENILAIAN ===== -->
        <div class="table-container" style="padding-left: 10px; padding-right: 10px;">
            <h3 style="padding: 20px; margin: 0; background: #f8f9fa; border-bottom: 1px solid #e0e0e0;">
                Riwayat Penilaian
            </h3>

            <div style="overflow-x: auto;">
                <table class="table" id="myTable">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th style="white-space: nowrap;">Tanggal</th>
                        <th>Siswa</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Poin</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($penilaianSiswa as $penilaian)
                        <tr>
                            {{-- KOLOM NO DIKOSONGKAN (DIISI DATATABLES) --}}
                            <td></td>

                            <td style="white-space: nowrap;">
                                {{ $penilaian->tanggal }}
                            </td>

                            <td>{{ $penilaian->siswa->nama }}</td>

                            <td>
                                <span class="badge {{ $penilaian->jenis == 'prestasi' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucfirst($penilaian->jenis) }}
                                </span>
                            </td>

                            <td>{{ ucfirst($penilaian->kategori) }}</td>

                            <td>{{ $penilaian->keterangan }}</td>

                            <td>
                                <span class="{{ $penilaian->poin >= 0 ? 'point-positive' : 'point-negative' }}">
                                    {{ $penilaian->poin >= 0 ? '+' : '' }}{{ $penilaian->poin }}
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
        let table = new DataTable('#myTable', {
            ordering: true,
            order: [[1, 'desc']], // urut berdasarkan tanggal
            columnDefs: [
                { orderable: false, targets: [0, 3, 4, 5, 6] }
            ],
            drawCallback: function () {
                let api = this.api();
                api.column(0, { search: 'applied', order: 'applied' })
                    .nodes()
                    .each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }
        });
    </script>
</x-layout>
