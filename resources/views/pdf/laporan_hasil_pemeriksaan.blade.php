<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Pemeriksaan - {{ $lhp->beritaAcaraPemeriksaan->pihak_diperiksa_nama }}</title>
    <style>
        body {
            font-family: 'Times New Roman', sans-serif;
            font-size: 12px;
            margin: 0;
            line-height: 1.5;
        }

        .content {
            margin-left: 30px;
            margin-right: 30px;
            word-wrap: break-word;
        }

        #kop-surat {
            width: 100%;
            padding-bottom: 20px;
            border-bottom: 1px solid #000;
        }

        #kop-surat td {
            vertical-align: middle;
        }

        #logo {
            width: 70px;
            height: auto;
        }

        .kop-teks {
            text-align: center;
        }

        .kop-teks h1,
        .kop-teks h2,
        .kop-teks p {
            margin: 2px;
            padding: 0;
        }

        .kop-teks h1 {
            font-size: 14px;
            font-weight: bold;
        }

        .kop-teks p {
            font-size: 11px;
        }

        .judul-surat-wrapper {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .judul-surat {
            font-weight: bold;
            text-decoration: underline;
            font-size: 12pt;
            margin-bottom: 5px;
        }

        .paragraf-pembuka {
            text-align: justify;
            margin-bottom: 10px;
        }

        table.detail-table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
            margin-bottom: 15px;
        }

        table.detail-table td {
            padding-top: 2px;
            padding-bottom: 2px;
            vertical-align: top;
            word-wrap: break-word;
        }

        table.detail-table td.label {
            width: 30%;
        }

        .narasi-pemeriksaan {
            text-align: justify;
            margin-top: 10px;
        }

        .signature-wrapper {
            width: 100%;
            page-break-inside: avoid;
            margin-top: 50px;
        }

        .signature-right {
            float: right;
            width: 45%;
            text-align: center;
        }

        .signature-left {
            float: left;
            width: 45%;
            text-align: center;
        }

        .signature-block p {
            margin: 0;
        }

        .paragraf-penutup {
            margin-top: 10px;
            text-align: justify;
        }
    </style>
</head>

<body>
    <div class="content">
        <table id="kop-surat">
            <tr>
                <td><img id="logo" src="{{ public_path('images/Logo-ITERA.png') }}" alt="Logo"></td>
                <td class="kop-teks">
                    <p>Kementrian Pendidikan Tinggi, Sains, dan Teknologi Institut Teknologi Sumatera</p>
                    <h1>Satuan Tugas Pencegahan dan Penanganan Kekerasan di Perguruan Tinggi (PPKPT)</h1>
                    <p>Jalan Terusan Ryacudu Way Hui, Kecamatan Jati Agung, Lampung Selatan 35365</p>
                    <p>Email: satgasppks@itera.ac.id, Website: https://satgasppkpt.itera.ac.id/</p>
                </td>
            </tr>
        </table>

        <div class="judul-surat-wrapper">
            <div class="judul-surat">LAPORAN HASIL PEMERIKSAAN</div>
        </div>
        <p class="paragraf-pembuka">
            Laporan hasil pemeriksaan ini disusun berdasarkan hasil pemeriksaan yang dilakukan oleh Satgas PPKPT Itera.
        </p>

        <table class="detail-table">
            <tr>
                <td class="label">Nama</td>
                <td>: {{ $lhp->beritaAcaraPemeriksaan->pihak_diperiksa_nama }}</td>
            </tr>
            <tr>
                <td class="label">
                    Tempat, Tanggal Lahir
                </td>
                <td>: {{ $lhp->beritaAcaraPemeriksaan->pihak_diperiksa_tempat_lahir }},
                    {{ \Carbon\Carbon::parse($lhp->beritaAcaraPemeriksaan->pihak_diperiksa_tanggal_lahir)->translatedFormat('j F Y') }}
                </td>
            </tr>
            <tr>
                <td class="label">Agama</td>
                <td>: {{ $lhp->beritaAcaraPemeriksaan->pihak_diperiksa_agama }}</td>
            </tr>
            <tr>
                <td class="label">
                    Jenis Kelamin
                </td>
                <td>: {{ $lhp->beritaAcaraPemeriksaan->pihak_diperiksa_jenis_kelamin }}</td>
            </tr>
            <tr>
                <td class="label">
                    Alamat</td>
                <td>: {{ $lhp->beritaAcaraPemeriksaan->pihak_diperiksa_alamat }}</td>
            </tr>
            <tr>
                <td class="label">Dugaan Bentuk Kekerasan</td>
                <td>: {{ $lhp->beritaAcaraPemeriksaan->jenis_kejadian_awal }}</td>
            </tr>
            <tr>
                <td class="label">Ketentuan yang dilanggar</td>
                <td>:
                    @if ($pasalPelanggarans->isNotEmpty())
                        &bull; {{ $pasalPelanggarans->first()->keterangan }}

                        @if ($pasalPelanggarans->count() > 1)
                            <ul style="list-style-type: none; margin: 0; padding-left: 7px;">
                                @foreach ($pasalPelanggarans->slice(1) as $pasal)
                                    <li>&bull; {{ $pasal->keterangan }}</li>
                                @endforeach
                            </ul>
                        @endif
                    @else
                        Tidak ada ketentuan spesifik yang tercatat.
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Pembuktian dan Analisis Bukti</td>
                <td>: {{ $lhp->pembuktian_dan_analisis }}</td>
            </tr>
            <tr>
                <td class="label">Ringkasan Pemeriksaan</td>
                <td>: {{ $lhp->ringkasan_pemeriksaan }}</td>
            </tr>
            <tr>
                <td class="label">Pendampingan, pelindungan, dan/ atau pemulihan yang telah diberikan kepada
                    Korban atau Saksi
                </td>
                <td>: {{ $lhp->pendampingan_diberikan }}</td>
            </tr>
        </table>
        <p class="paragraf-penutup">
            Berdasarkan pemeriksaan, {{ strtolower($lhp->beritaAcaraPemeriksaan->pihak_diperiksa_peran) }}
            <strong>{{ $lhp->status_terbukti == 'terbukti' ? 'TERBUKTI' : 'TIDAK TERBUKTI' }}</strong> melakukan
            kekerasan berdasarkan
            Peraturan Menteri Pendidikan, Kebudayaan, Riset, dan Teknologi Nomor 55 Tahun 2024 tentang Pencegahan dan
            Penanganan Kekerasan di Lingkungan Perguruan Tinggi.
        </p>
        <p class="paragraf-penutup">Laporan ini dibuat sebagai dasar penyusunan rekomendasi. Demikian laporan ini agar
            dapat digunakan sepenuhnya.
        </p>


        <div class="signature-wrapper">
            <div class="signature-right signature-block">
                <p>Lampung Selatan, {{ $lhp->created_at->translatedFormat('j F Y') }}</p>
                <p>Ketua Satgas PPKPT Itera,</p>
                @if (
                    $ketuaSatgas &&
                        $ketuaSatgas->tanda_tangan &&
                        \Illuminate\Support\Facades\Storage::disk('public')->exists($ketuaSatgas->tanda_tangan))
                    @php
                        $imagePath = storage_path('app/public/' . $ketuaSatgas->tanda_tangan);
                        $imageData = base64_encode(file_get_contents($imagePath));
                        $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
                    @endphp
                    <img src="data:image/{{ $imageType }};base64,{{ $imageData }}" alt="Tanda Tangan"
                        style="height: 60px; margin: 5px auto;">
                @else
                    <div style="height: 70px;"></div>
                @endif
                <p style="font-weight: bold; text-decoration: underline;">
                    {{ $ketuaSatgas->nama ?? 'Nama Ketua Belum Diatur' }}</p>
                <p>NIP: {{ $ketuaSatgas->nip ?? '-' }}</p>
            </div>
        </div>
    </div>
</body>

</html>
