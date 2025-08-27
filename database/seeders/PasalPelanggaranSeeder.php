<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PasalPelanggaran;

class PasalPelanggaranSeeder extends Seeder
{
    public function run(): void
    {
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan fisik', 'pasal' => '9', 'ayat' => '(2)', 'butir' => 'a', 'keterangan' => 'tawuran']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan fisik', 'pasal' => '9', 'ayat' => '(2)', 'butir' => 'b', 'keterangan' => 'penganiayaan']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan fisik', 'pasal' => '9', 'ayat' => '(2)', 'butir' => 'c', 'keterangan' => 'perkelahian']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan fisik', 'pasal' => '9', 'ayat' => '(2)', 'butir' => 'd', 'keterangan' => 'eksploitasi ekonomi melalui kerja paksa untuk memberikan keuntungan ekonomi bagi Pelaku']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan fisik', 'pasal' => '9', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'pembunuhan']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan fisik', 'pasal' => '9', 'ayat' => '(2)', 'butir' => 'f', 'keterangan' => 'perbuatan lain yang dinyatakan sebagai Kekerasan fisik sesuai dengan ketentuan peraturan perundang-undangan']);

        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan psikis', 'pasal' => '10', 'ayat' => '(2)', 'butir' => 'a', 'keterangan' => 'pengucilan']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan psikis', 'pasal' => '10', 'ayat' => '(2)', 'butir' => 'b', 'keterangan' => 'penolakan']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan psikis', 'pasal' => '10', 'ayat' => '(2)', 'butir' => 'c', 'keterangan' => 'pengabaian']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan psikis', 'pasal' => '10', 'ayat' => '(2)', 'butir' => 'd', 'keterangan' => 'penghinaan']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan psikis', 'pasal' => '10', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'penyebaran rumor']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan psikis', 'pasal' => '10', 'ayat' => '(2)', 'butir' => 'f', 'keterangan' => 'panggilan yang mengejek']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan psikis', 'pasal' => '10', 'ayat' => '(2)', 'butir' => 'g', 'keterangan' => 'intimidasi']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan psikis', 'pasal' => '10', 'ayat' => '(2)', 'butir' => 'h', 'keterangan' => 'teror']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan psikis', 'pasal' => '10', 'ayat' => '(2)', 'butir' => 'i', 'keterangan' => 'perbuatan mempermalukan di depan umum']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan psikis', 'pasal' => '10', 'ayat' => '(2)', 'butir' => 'j', 'keterangan' => 'pemerasan']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan psikis', 'pasal' => '10', 'ayat' => '(2)', 'butir' => 'k', 'keterangan' => 'perbuatan lain yang dinyatakan sebagai Kekerasan psikis sesuai dengan ketentuan peraturan perundang-undangan']);

        PasalPelanggaran::create(['jenis_kekerasan' => 'Perundungan', 'pasal' => '11', 'ayat' => '(-)', 'butir' => '-', 'keterangan' => 'Kekerasan fisik sebagaimana dimaksud dalam Pasal 9 ayat (2) huruf b Penganiayaan yang dilakukan secara berulang dan adanya ketimpangan relasi kuasa']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Perundungan', 'pasal' => '11', 'ayat' => '(-)', 'butir' => '-', 'keterangan' => 'Kekerasan psikis sebagaimana dimaksud dalam Pasal 10 ayat (2) yang dilakukan secara berulang dan adanya ketimpangan relasi kuasa']);

        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'a', 'keterangan' => 'penyampaian ujaran yang mendiskriminasi atau melecehkan tampilan fisik, kondisi tubuh, dan/atau identitas gender Korban']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'b', 'keterangan' => 'perbuatan memperlihatkan alat kelamin dengan sengaja tanpa persetujuan Korban']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'c', 'keterangan' => 'penyampaian ucapan yang memuat rayuan, lelucon, dan/atau siulan yang bernuansa seksual']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'd', 'keterangan' => 'perbuatan menatap Korban dengan nuansa seksual dan/atau membuat Korban merasa tidak nyaman']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'pengiriman pesan, lelucon, gambar, foto, audio, dan/atau video bernuansa seksual kepada Korban meskipun sudah dilarang Korban']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'f', 'keterangan' => 'perbuatan mengambil, merekam, dan/atau mengedarkan foto dan/atau rekaman audio dan/atau visual Korban yang bernuansa seksual tanpa persetujuan Korban']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'g', 'keterangan' => 'perbuatan mengunggah foto tubuh dan/atau informasi pribadi Korban yang bernuansa seksual tanpa persetujuan Korban']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'h', 'keterangan' => 'penyebaran informasi terkait tubuh dan/atau informasi pribadi Korban yang bernuansa seksual tanpa persetujuan Korban']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'i', 'keterangan' => 'perbuatan mengintip atau dengan sengaja melihat Korban yang sedang melakukan kegiatan secara pribadi dan/atau pada ruang yang bersifat pribadi']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'j', 'keterangan' => 'perbuatan membujuk, menjanjikan, atau menawarkan sesuatu kepada Korban untuk melakukan transaksi atau kegiatan seksual yang tidak disetujui Korban']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'k', 'keterangan' => 'pemberian hukuman atau sanksi yang bernuansa seksual']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'l', 'keterangan' => 'perbuatan menyentuh, mengusap, meraba, memegang, memeluk, mencium, dan/atau menggosokkan bagian tubuhnya pada tubuh Korban tanpa persetujuan Korban']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'm', 'keterangan' => 'perbuatan membuka pakaian Korban tanpa persetujuan Korban']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'n', 'keterangan' => 'pemaksaan terhadap Korban untuk melakukan transaksi atau kegiatan seksual']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'o', 'keterangan' => 'praktik budaya komunitas Warga Kampus yang bernuansa Kekerasan seksual']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'p', 'keterangan' => 'percobaan perkosaan walaupun penetrasi tidak terjadi']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'q', 'keterangan' => 'perkosaan termasuk penetrasi dengan benda atau bagian tubuh selain alat kelamin']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'r', 'keterangan' => 'pemaksaan atau perbuatan memperdayai Korban untuk melakukan aborsi']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 's', 'keterangan' => 'pemaksaan atau perbuatan memperdayai Korban untuk hamil']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 't', 'keterangan' => 'pemaksaan sterilisasi']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'u', 'keterangan' => 'penyiksaan seksual']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'v', 'keterangan' => 'eksploitasi seksual']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'w', 'keterangan' => 'perbudakan seksual']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'x', 'keterangan' => 'tindak pidana perdagangan orang yang ditujukan untuk eksploitasi seksual']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'y', 'keterangan' => 'pembiaran terjadinya kekerasan seksual dengan sengaja']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Kekerasan seksual', 'pasal' => '12', 'ayat' => '(2)', 'butir' => 'z', 'keterangan' => 'perbuatan lain yang dinyatakan sebagai kekerasan seksual sesuai dengan ketentuan peraturan perundang-undangan']);

        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'a', 'keterangan' => 'larangan untuk menggunakan pakaian yang sesuai dengan keyakinan dan/atau kepercayaan agama']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'a', 'keterangan' => 'larangan untuk mengikuti mata kuliah agama/kepercayaan yang diajar oleh dosen sesuai dengan agama/kepercayaan mahasiswa yang diakui oleh pemerintah']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'a', 'keterangan' => 'larangan untuk mengamalkan ajaran agama/kepercayaan yang sesuai keyakinan agama/kepercayaan yang dianut']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'b', 'keterangan' => 'pemaksaan untuk menggunakan pakaian yang tidak sesuai dengan keyakinan dan/atau kepercayaan agama']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'b', 'keterangan' => 'pemaksaan untuk mengikuti mata kuliah agama/kepercayaan yang diajar oleh dosen yang tidak sesuai dengan agama/kepercayaan mahasiswa yang diakui oleh pemerintah']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'b', 'keterangan' => 'pemaksaan untuk mengamalkan ajaran agama atau kepercayaan yang tidak sesuai keyakinan agama/kepercayaan yang dianut']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'c', 'keterangan' => 'memberikan perlakuan khusus kepada calon pemimpin/pengurus organisasi berdasarkan latar belakang identitas tertentu di Perguruan Tinggi']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'd', 'keterangan' => 'larangan untuk mengikuti atau tidak mengikuti perayaan hari besar keagamaan yang dilaksanakan di Perguruan Tinggi yang berbeda dengan agama/kepercayaan sesuai yang diyakininya']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'd', 'keterangan' => 'larangan untuk memberikan donasi/bantuan dengan alasan latar belakang suku/etnis, agama, kepercayaan, ras, warna kulit, usia, status sosial ekonomi, kebangsaan, afiliasi, ideologi, jenis kelamin, dan/atau kemampuan intelektual, mental, sensorik, serta fisik']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau tidak memberikan hak atau kebutuhan mahasiswa untuk mengikuti proses penerimaan mahasiswa']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau tidak memberikan hak atau kebutuhan mahasiswa untuk menggunakan sarana dan prasarana belajar dan/atau akomodasi yang layak']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau tidak memberikan hak atau kebutuhan mahasiswa untuk menerima bantuan pendidikan atau beasiswa yang menjadi hak mahasiswa']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau tidak memberikan hak atau kebutuhan mahasiswa untuk memiliki kesempatan dalam mengikuti kompetisi']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau tidak memberikan hak atau kebutuhan mahasiswa untuk memiliki kesempatan untuk mengikuti pelatihan atau melanjutkan pendidikan pada jenjang berikutnya']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau tidak memberikan hak atau kebutuhan mahasiswa untuk memperoleh hasil penilaian pembelajaran']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau tidak memberikan hak atau kebutuhan mahasiswa untuk lulus mata kuliah']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau tidak memberikan hak atau kebutuhan mahasiswa untuk lulus dari Perguruan Tinggi']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau tidak memberikan hak atau kebutuhan mahasiswa untuk mengikuti bimbingan dan konsultasi']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau tidak memberikan hak atau kebutuhan mahasiswa untuk memperoleh dokumen pendidikan yang menjadi hak mahasiswa']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau tidak memberikan hak atau kebutuhan mahasiswa untuk memperoleh bentuk pelayanan pendidikan lainnya yang menjadi hak mahasiswa']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau tidak memberikan hak atau kebutuhan mahasiswa untuk menunjukkan/menampilkan ekspresi terhadap seni dan budaya yang diminati']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'e', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau tidak memberikan hak atau kebutuhan mahasiswa untuk mengembangkan bakat dan minat mahasiswa sesuai dengan sumber daya atau kemampuan yang dimiliki oleh Itera']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'f', 'keterangan' => 'perbuatan mengurangi, menghalangi, atau membedakan hak dan/atau kewajiban dosen atau tenaga kependidikan sesuai dengan ketentuan peraturan perundang-undangan']);
        PasalPelanggaran::create(['jenis_kekerasan' => 'Diskriminasi dan intoleransi', 'pasal' => '13', 'ayat' => '(2)', 'butir' => 'g', 'keterangan' => 'perbuatan diskriminasi dan intoleransi lain sesuai dengan ketentuan peraturan perundang-undangan']);

        PasalPelanggaran::create(['jenis_kekerasan' => 'Kebijakan yang mengandung kekerasan', 'pasal' => '14', 'ayat' => '(1)', 'butir' => '-', 'keterangan' => 'kebijakan yang berpotensi atau menimbulkan terjadinya Kekerasan']);
    }
}
