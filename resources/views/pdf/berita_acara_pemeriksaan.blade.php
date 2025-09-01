<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('images/Logo PPKPT 2025 Square Black - CROP.png') }}" type="image/png">
    <title>Berita Acara Pemeriksaan - {{ $bap->pihak_diperiksa_nama }}</title>
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
            <div class="judul-surat">BERITA ACARA PEMERIKSAAN</div>
        </div>

        <p class="paragraf-pembuka">
            {{ ucfirst(strtolower($bap->pihak_diperiksa_peran)) }} yang bertandatangan dibawah ini menerangkan bahwa
            pada hari
            {{ $bap->tanggal_pemeriksaan->translatedFormat('l') }}, tanggal
            {{ $bap->tanggal_pemeriksaan->translatedFormat('j F Y') }},
            pukul {{ \Carbon\Carbon::parse($bap->waktu_pemeriksaan)->format('H:i') }} telah datang kepada Satgas PPKPT
            ITERA untuk melakukan pemeriksaan di ruang {{ $bap->tempat_pemeriksaan }} gedung ITERA, yang mengaku
            bernama,
        </p>

        <table class="detail-table">
            <tr>
                <td class="label">Nama</td>
                <td>: {{ $bap->pihak_diperiksa_nama }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td>: {{ $bap->pihak_diperiksa_tempat_lahir }},
                    {{ \Carbon\Carbon::parse($bap->pihak_diperiksa_tanggal_lahir)->translatedFormat('j F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Agama</td>
                <td>: {{ $bap->pihak_diperiksa_agama }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td>: {{ $bap->pihak_diperiksa_jenis_kelamin }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td>: {{ $bap->pihak_diperiksa_alamat }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kejadian</td>
                <td>:
                    {{ is_array($bap->jenis_kejadian_awal) ? implode(', ', $bap->jenis_kejadian_awal) : $bap->jenis_kejadian_awal }}
                </td>
            </tr>
            <tr>
                <td class="label">Uraian Singkat Kejadian</td>
                <td style="text-align: justify;">: {{ $bap->uraian_singkat_kejadian }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Kejadian</td>
                <td>: {{ \Carbon\Carbon::parse($bap->tanggal_kejadian)->translatedFormat('j F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Tempat Kejadian</td>
                <td>: {{ $bap->tempat_kejadian }}</td>
            </tr>
            <tr>
                <td class="label">Saksi Pendamping</td>
                <td>: {{ $bap->saksi_pendamping }}</td>
            </tr>
            <tr>
                <td class="label">Anggota Satgas PPKPT</td>
                <td>:</td>
            </tr>
        </table>

        <div style="margin-top: 5px; page-break-inside: avoid;">
            <table style="width: 100%; border-collapse: collapse; text-align: center;">
                @if (is_array($bap->anggota_satgas_ids))
                    @foreach (collect($bap->anggota_satgas_ids)->chunk(2) as $row)
                        <tr>
                            @foreach ($row as $petugas)
                                <td style="width: 50%; padding-bottom: 10px; vertical-align: top;">
                                    @if (!empty($petugas['tanda_tangan']) && file_exists(public_path('storage/' . $petugas['tanda_tangan'])))
                                        <img src="{{ public_path('storage/' . $petugas['tanda_tangan']) }}"
                                            style="max-height: 50px; display: block; margin: 0 auto 5px auto;">
                                    @else
                                        <div style="height: 50px; margin-bottom: 5px;"></div>
                                    @endif
                                    <p style="margin: 0; ">
                                        {{ $petugas['name'] ?? 'N/A' }}</p>
                                </td>
                            @endforeach
                            @if (count($row) < 2)
                                <td style="width: 50%;"></td>
                            @endif
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
        <p class="paragraf-penutup">
            Berita acara ini diperlukan untuk verifikasi dan investigasi. Demikian berita acara ini agar dapat digunakan
            sepenuhnya.
        </p>
    </div>
    <div class="signature-wrapper">
        <div class="signature-right signature-block">
            <p>Lampung Selatan, {{ $bap->created_at->translatedFormat('j F Y') }}</p>
            <p>KETUA SATGAS PPKPT ITERA</p>
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
        <div class="signature-left signature-block">
            <br>
            <p>Tanda Tangan Terperiksa</p>
            @if (!empty($bap->tanda_tangan_terperiksa) && file_exists(public_path('storage/' . $bap->tanda_tangan_terperiksa)))
                <img src="{{ public_path('storage/' . $bap->tanda_tangan_terperiksa) }}"
                    style="height: 50px; margin: 10px auto;">
            @else
                <div style="height: 60px;"></div>
            @endif
            <p style="">{{ $bap->pihak_diperiksa_nama }}</p>
        </div>
    </div>
    </div>
</body>

</html>
