<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PasalSanksi;

class PasalSanksiSeeder extends Seeder
{
    public function run(): void
    {
        // Sanksi untuk Dosen ASN
        PasalSanksi::create(['jenis_sanksi' => 'Ringan', 'pelaku' => 'Dosen ASN', 'pasal' => '68', 'ayat' => '(1)', 'butir' => '-', 'keterangan' => 'Sesuai dengan ketentuan peraturan perundang-undangan']);
        PasalSanksi::create(['jenis_sanksi' => 'Ringan', 'pelaku' => 'Tenaga Kependidikan ASN', 'pasal' => '68', 'ayat' => '(1)', 'butir' => '-', 'keterangan' => 'Sesuai dengan ketentuan peraturan perundang-undangan']);
        PasalSanksi::create(['jenis_sanksi' => 'Ringan', 'pelaku' => 'Dosen Non-ASN', 'pasal' => '68', 'ayat' => '(3)', 'butir' => 'a', 'keterangan' => 'Teguran Tertulis']);
        PasalSanksi::create(['jenis_sanksi' => 'Ringan', 'pelaku' => 'Dosen Non-ASN', 'pasal' => '68', 'ayat' => '(3)', 'butir' => 'b', 'keterangan' => 'Pernyataan permohonan maaf secara tertulis dari Pelaku kepada Korban']);
        PasalSanksi::create(['jenis_sanksi' => 'Ringan', 'pelaku' => 'Tenaga Kependidikan Non-ASN', 'pasal' => '68', 'ayat' => '(3)', 'butir' => 'a', 'keterangan' => 'Teguran Tertulis']);
        PasalSanksi::create(['jenis_sanksi' => 'Ringan', 'pelaku' => 'Tenaga Kependidikan Non-ASN', 'pasal' => '68', 'ayat' => '(3)', 'butir' => 'b', 'keterangan' => 'Pernyataan permohonan maaf secara tertulis dari Pelaku kepada Korban']);

        PasalSanksi::create(['jenis_sanksi' => 'Sedang', 'pelaku' => 'Dosen ASN', 'pasal' => '68', 'ayat' => '(1)', 'butir' => '-', 'keterangan' => 'Sesuai dengan ketentuan peraturan perundang-undangan']);
        PasalSanksi::create(['jenis_sanksi' => 'Sedang', 'pelaku' => 'Tenaga Kependidikan ASN', 'pasal' => '68', 'ayat' => '(1)', 'butir' => '-', 'keterangan' => 'Sesuai dengan ketentuan peraturan perundang-undangan']);
        PasalSanksi::create(['jenis_sanksi' => 'Sedang', 'pelaku' => 'Dosen Non-ASN', 'pasal' => '68', 'ayat' => '(4)', 'butir' => '-', 'keterangan' => 'Penurunan jenjang jabatan akademik dosen atau penurunan jenjang jabatan fungsional tenaga kependidikan selama 12 (dua belas) bulan']);
        PasalSanksi::create(['jenis_sanksi' => 'Sedang', 'pelaku' => 'Tenaga Kependidikan Non-ASN', 'pasal' => '68', 'ayat' => '(4)', 'butir' => '-', 'keterangan' => 'Penurunan jenjang jabatan akademik dosen atau penurunan jenjang jabatan fungsional tenaga kependidikan selama 12 (dua belas) bulan']);

        PasalSanksi::create(['jenis_sanksi' => 'Berat', 'pelaku' => 'Dosen ASN', 'pasal' => '68', 'ayat' => '(1)', 'butir' => '-', 'keterangan' => 'Sesuai dengan ketentuan peraturan perundang-undangan']);
        PasalSanksi::create(['jenis_sanksi' => 'Berat', 'pelaku' => 'Tenaga Kependidikan ASN', 'pasal' => '68', 'ayat' => '(1)', 'butir' => '-', 'keterangan' => 'Sesuai dengan ketentuan peraturan perundang-undangan']);
        PasalSanksi::create(['jenis_sanksi' => 'Berat', 'pelaku' => 'Dosen Non-ASN', 'pasal' => '68', 'ayat' => '(5)', 'butir' => '-', 'keterangan' => 'Pemberhentian tetap sebagai dosen']);
        PasalSanksi::create(['jenis_sanksi' => 'Berat', 'pelaku' => 'Tenaga Kependidikan Non-ASN', 'pasal' => '68', 'ayat' => '(5)', 'butir' => '-', 'keterangan' => 'Pemberhentian tetap sebagai tenaga kependidikan']);

        PasalSanksi::create(['jenis_sanksi' => 'Ringan', 'pelaku' => 'Mahasiswa', 'pasal' => '69', 'ayat' => '(2)', 'butir' => 'a', 'keterangan' => 'Teguran tertulis']);
        PasalSanksi::create(['jenis_sanksi' => 'Ringan', 'pelaku' => 'Mahasiswa', 'pasal' => '69', 'ayat' => '(2)', 'butir' => 'b', 'keterangan' => 'Pernyataan permohonan maaf secara tertulis dari Pelaku kepada Korban']);

        PasalSanksi::create(['jenis_sanksi' => 'Sedang', 'pelaku' => 'Mahasiswa', 'pasal' => '69', 'ayat' => '(3)', 'butir' => 'a', 'keterangan' => 'Penundaan mengikuti perkuliahan']);
        PasalSanksi::create(['jenis_sanksi' => 'Sedang', 'pelaku' => 'Mahasiswa', 'pasal' => '69', 'ayat' => '(3)', 'butir' => 'b', 'keterangan' => 'Pencabutan beasiswa']);
        PasalSanksi::create(['jenis_sanksi' => 'Sedang', 'pelaku' => 'Mahasiswa', 'pasal' => '69', 'ayat' => '(3)', 'butir' => 'c', 'keterangan' => 'Pengurangan hak lain sesuai dengan ketentuan peraturan perundang-undangan']);

        PasalSanksi::create(['jenis_sanksi' => 'Berat', 'pelaku' => 'Mahasiswa', 'pasal' => '69', 'ayat' => '(4)', 'butir' => '-', 'keterangan' => 'Pemberhentian tetap sebagai mahasiswa']);

        PasalSanksi::create(['jenis_sanksi' => 'Ringan', 'pelaku' => 'Warga kampus', 'pasal' => '70', 'ayat' => '(2)', 'butir' => 'a', 'keterangan' => 'Teguran tertulis']);
        PasalSanksi::create(['jenis_sanksi' => 'Ringan', 'pelaku' => 'Warga kampus', 'pasal' => '70', 'ayat' => '(2)', 'butir' => 'b', 'keterangan' => 'Pernyataan permohonan maaf secara tertulis dari Pelaku kepada Korban']);

        PasalSanksi::create(['jenis_sanksi' => 'Sedang', 'pelaku' => 'Warga kampus', 'pasal' => '70', 'ayat' => '(3)', 'butir' => '-', 'keterangan' => 'Penghentian sementara kerja sama dengan Perguruan Tinggi']);

        PasalSanksi::create(['jenis_sanksi' => 'Berat', 'pelaku' => 'Warga kampus', 'pasal' => '70', 'ayat' => '(4)', 'butir' => '-', 'keterangan' => 'Pemutusan kerja sama dengan Perguruan Tinggi']);

        PasalSanksi::create(['jenis_sanksi' => 'Ringan', 'pelaku' => 'Pemimpin Perguruan Tinggi ASN', 'pasal' => '71', 'ayat' => '(1)', 'butir' => '-', 'keterangan' => 'Sesuai dengan ketentuan peraturan perundang-undangan']);
        PasalSanksi::create(['jenis_sanksi' => 'Ringan', 'pelaku' => 'Pemimpin Perguruan Tinggi Non-ASN', 'pasal' => '71', 'ayat' => '(2)', 'butir' => 'a', 'keterangan' => 'Teguran tertulis']);
        PasalSanksi::create(['jenis_sanksi' => 'Ringan', 'pelaku' => 'Pemimpin Perguruan Tinggi Non-ASN', 'pasal' => '71', 'ayat' => '(2)', 'butir' => 'b', 'keterangan' => 'Pernyataan permohonan maaf secara tertulis dari Pelaku kepada Korban']);
        PasalSanksi::create(['jenis_sanksi' => 'Sedang', 'pelaku' => 'Pemimpin Perguruan Tinggi ASN', 'pasal' => '71', 'ayat' => '(1)', 'butir' => '-', 'keterangan' => 'Sesuai dengan ketentuan peraturan perundang-undangan']);
        PasalSanksi::create(['jenis_sanksi' => 'Sedang', 'pelaku' => 'Pemimpin Perguruan Tinggi Non-ASN', 'pasal' => '71', 'ayat' => '(3)', 'butir' => '-', 'keterangan' => 'Penurunan jenjang jabatan akademik selama 12 (dua belas) bulan']);
        PasalSanksi::create(['jenis_sanksi' => 'Berat', 'pelaku' => 'Pemimpin Perguruan Tinggi ASN', 'pasal' => '71', 'ayat' => '(1)', 'butir' => '-', 'keterangan' => 'Sesuai dengan ketentuan peraturan perundang-undangan']);
        PasalSanksi::create(['jenis_sanksi' => 'Berat', 'pelaku' => 'Pemimpin Perguruan Tinggi Non-ASN', 'pasal' => '71', 'ayat' => '(4)', 'butir' => '-', 'keterangan' => 'Pemberhentian tetap sebagai Pemimpin Perguruan Tinggi']);
    }
}
