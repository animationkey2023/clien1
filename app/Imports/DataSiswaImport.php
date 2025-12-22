<?php

namespace App\Imports;

use App\Models\DataSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataSiswaImport implements ToCollection, WithHeadingRow
{
    public int $berhasil = 0;
    public int $duplikat = 0;

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            foreach ($rows as $row) {

                // Skip baris kosong
                if (
                    empty($row['nama']) ||
                    empty($row['nis']) ||
                    empty($row['kelas'])
                ) {
                    continue;
                }

                $nis = trim((string) $row['nis']);

                // WAJIB 18 digit
                if (!preg_match('/^\d{18}$/', $nis)) {
                    continue;
                }

                // Skip duplikat
                if (DataSiswa::where('nis', $nis)->exists()) {
                    $this->duplikat++;
                    continue;
                }

                // Simpan
                DataSiswa::create([
                    'nama'  => trim($row['nama']),
                    'nis'   => $nis,
                    'kelas' => trim($row['kelas']),
                ]);

                $this->berhasil++;
            }

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
