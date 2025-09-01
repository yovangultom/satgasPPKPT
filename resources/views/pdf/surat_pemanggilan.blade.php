<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/Logo PPKPT 2025 Square Black - CROP.png') }}" type="image/png">
    <title>Surat Panggilan Pemeriksaan</title>
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

        .kop-teks h2 {
            font-size: 12px;
        }

        .kop-teks p {
            font-size: 11px;
        }

        .judul-surat-wrapper {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .rahasia {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .judul-surat {
            font-weight: bold;
            text-decoration: underline;
            font-size: 12pt;
            margin-bottom: 5px;
        }

        .paragraf {
            text-align: justify;
            text-indent: 50px;
            margin-bottom: 15px;
        }

        .data-pihak,
        .data-pemanggilan {
            margin-left: 50px;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        table.data-table {
            border-collapse: collapse;
            width: 100%;
        }

        table.data-table td {
            padding-top: 2px;
            padding-bottom: 2px;
            vertical-align: top;
        }

        table.data-table td.label {
            width: 150px;
        }

        .signature-wrapper {
            margin-top: 50px;
            text-align: right;
        }

        .signature-block {
            width: 45%;
            text-align: left;
            display: inline-block;
        }

        .signature-block .nama-ketua {
            font-weight: bold;
            text-decoration: underline;
        }

        .tembusan {
            margin-top: 50px;
            font-size: 10px;
        }

        .tembusan ol {
            padding-left: 20px;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="content">

        <table id="kop-surat">
            <tr>
                <td>
                    <img id="logo" src="{{ public_path('images/Logo-ITERA.png') }}" alt="Logo">
                </td>
                <td class="kop-teks">
                    <p>Kementrian Pendidikan Tinggi, Sains, dan Teknologi Institut Teknologi Sumatera</p>
                    <h1>Satuan Tugas Pencegahan dan Penanganan Kekerasan di Perguruan Tinggi (PPKPT)</h1>
                    <p>Jalan Terusan Ryacudu Way Hui, Kecamatan Jati Agung, Lampung Selatan 35365</p>
                    <p>Email: satgasppks@itera.ac.id, Website: https://satgasppkpt.itera.ac.id/</p>
                </td>
            </tr>
        </table>
        <div class="judul-surat-wrapper">
            <div class="rahasia">RAHASIA</div>
            <div class="judul-surat">SURAT PEMANGGILAN PEMERIKSAAN</div>
            <div>NOMOR:{{ $nomor_pelaporan }}</div>
        </div>

        <p>Bersama ini diminta dengan hormat kehadiran saudara:</p>

        <div class="data-pihak">
            <table class="data-table">
                <tr>
                    <td class="label">Nama</td>
                    <td>: {{ $nama }}</td>
                </tr>
                @if ($status == 'mahasiswa')
                    <tr>
                        <td class="label">NIM</td>
                        <td>: {{ $nim ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Semester</td>
                        <td>: {{ $semester ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Program Studi</td>
                        <td>: {{ $program_studi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Fakultas</td>
                        <td>: {{ $fakultas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Asal Instansi</td>
                        <td>: {{ $asal_instansi ?? '-' }}</td>
                    </tr>
                @elseif($status == 'dosen' || $status == 'tendik')
                    <tr>
                        <td class="label">NIP/NIDN</td>
                        <td>: {{ $nip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Fakultas/Unit</td>
                        <td>: {{ $fakultas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Asal Instansi</td>
                        <td>: {{ $asal_instansi ?? '-' }}</td>
                    </tr>
                @elseif($status == 'warga kampus' || $status == 'masyarakat umum')
                    @if (isset($info_tambahan) && is_array($info_tambahan) && count($info_tambahan) > 0)
                        @foreach ($info_tambahan as $key => $value)
                            <tr>
                                <td class="label">{{ $key }}</td>
                                <td>: {{ $value }}</td>
                            </tr>
                        @endforeach
                    @endif

                @endif
            </table>
        </div>

        <p class="paragraf">
            Untuk Menghadap kepada Satuan Tugas Pencegahan dan Penanganan Kekerasan di Perguruan Tinggi (Satgas PPKPT)
            Institut Teknologi Sumatera dalam rangka diperiksa dan dimintai keterangannya sebagai {{ $peran }}
            terkait kasus {{ $jenis_kejadian }} dengan nomor pelaporan <strong>{{ $nomor_pelaporan }}</strong> sesuai
            dengan Peraturan Menteri Pendidikan,
            Kebudayaan, Riset, dan Teknologi (Permendikbudristek) Nomor 55 Tahun 2024 tentang Pencegahan dan Penanganan
            Kekerasan di Perguruan Tinggi.
        </p>
        <p>Dimohon kehadiran saudara pada:</p>
        <div class="data-pemanggilan">
            <table class="data-table">
                <tr>
                    <td class="label">Hari/Tanggal</td>
                    <td>: {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM YYYY') }}</td>
                </tr>
                <tr>
                    <td class="label">Waktu</td>
                    <td>: {{ \Carbon\Carbon::parse($waktu)->format('H:i') }} WIB</td>
                </tr>
                <tr>
                    <td class="label">Tempat</td>
                    <td>: {{ $tempat }}</td>
                </tr>
            </table>
        </div>
        <p class="">Demikian untuk dilaksanakan sebagaimana mestinya.</p>
        <div style="margin-top: 20px; width: 40%; float: right; text-align: center; page-break-inside: avoid;">
            <p style="margin-bottom: 0px; margin-top: 0px;">Bandar Lampung, {{ now()->translatedFormat('j F Y') }}</p>
            <p style="margin-bottom: 0px; margin-top: 0px;">Ketua Satgas PPKPT ITERA,</p>
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
                    style="display: block; margin-left: auto; margin-right: auto; width: auto; max-height: 70px; margin-top:5px; margin-bottom:5px;">
            @else
                <br><br><br><br>
            @endif

            <p style="font-weight: bold; text-decoration: underline; margin-top: 0; margin-bottom: 0;">
                {{ $ketuaSatgas->nama ?? 'Nama Ketua Belum Diatur' }}
            </p>
            <p style="margin-top: 0;">
                NIP: {{ $ketuaSatgas->nip ?? '-' }}
            </p>
        </div>

        <div style="clear: both;"></div>

        <div class="tembusan">
            <strong>Tembusan:</strong>
            <ol>
                <li>Wakil Rektor Bidang Akademik dan Kemahasiswaan</li>
                <li>Kepala Biro Akademik, Perencanaan, dan Umum</li>
            </ol>
        </div>
    </div>

</body>

</html>
