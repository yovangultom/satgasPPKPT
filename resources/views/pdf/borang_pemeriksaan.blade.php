<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('images/Logo PPKPT 2025 Square Black - CROP.png') }}" type="image/png">
    <title>Borang Pemeriksaan - {{ $pengaduan->nomor_pengaduan }}</title>
    <style>
        body {
            font-family: 'Times New Roman', sans-serif;
            font-size: 12px;
            margin: 0;
            line-height: 1.5;
        }

        .content {
            margin: 0 30px;
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

        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        table.detail-table {
            border-collapse: collapse;
            width: 100%;
        }

        table.detail-table td {
            padding: 1px 0;
            vertical-align: top;
            word-wrap: break-word;
        }

        table.detail-table td.label {
            width: 200px;
        }

        .signature-wrapper {
            width: 100%;
            page-break-inside: avoid;
            margin-top: 10px;
            clear: both;
        }

        .signature-title {
            text-align: center;
            margin-bottom: 10px;
        }

        table.signature-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        table.signature-table td {
            width: 50%;
            padding-bottom: 10px;
            vertical-align: top;
        }

        .signature-image {
            max-height: 50px;
            display: block;
            margin: 0 auto 5px auto;
        }

        .signature-placeholder {
            height: 50px;
            margin-bottom: 5px;
        }

        .signature-name {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="content">
        {{-- KOP SURAT --}}
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
            <div class="judul-surat">BORANG PEMERIKSAAN</div>
        </div>

        <div class="section">
            <table class="detail-table">
                <tr>
                    <td class="label">Nomor Pengaduan</td>
                    <td>: {{ $pengaduan->nomor_pengaduan }}</td>
                </tr>
                @foreach ($pengaduan->terlapors as $terlapor)
                    <tr>
                        <td class="label">
                            Nama Terlapor
                            @if ($pengaduan->terlapors->count() > 1)
                                {{ $loop->iteration }}
                            @endif
                        </td>
                        <td>: {{ $terlapor->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">
                            Disabilitas Terlapor
                        </td>
                        <td>: {{ $terlapor->memiliki_disabilitas ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                @endforeach

                @foreach ($pengaduan->pelapors as $pelapor)
                    @if ($pelapor->pivot->peran_dalam_pengaduan == 'Saksi')
                        <tr>
                            <td class="label">Nama Saksi</td>
                            <td>: {{ $pelapor->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Disabilitas Saksi</td>
                            <td>: {{ $pelapor->memiliki_disabilitas ? 'Ya' : 'Tidak' }}</td>
                        </tr>
                    @endif
                @endforeach

                @foreach ($pengaduan->korbans as $korban)
                    <tr>
                        <td class="label">
                            Nama Korban
                            @if ($pengaduan->korbans->count() > 1)
                                {{ $loop->iteration }}
                            @endif
                        </td>
                        <td>: {{ $korban->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Disabilitas Korban</td>
                        <td>: {{ $korban->memiliki_disabilitas ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                @endforeach

                @if (is_array($borangPemeriksaan->pemeriksa_info) && count($borangPemeriksaan->pemeriksa_info) > 0)
                    <tr>
                        <td class="label" style="vertical-align: top;">Petugas Pemeriksa</td>
                        <td style="vertical-align: top;">
                            <table style="border: none; border-collapse: collapse;">
                                <tbody>
                                    @foreach ($borangPemeriksaan->pemeriksa_info as $petugas)
                                        <tr>
                                            @if ($loop->first)
                                                <td style="border: none; padding: 0; vertical-align: top; width: 5px;">:
                                                </td>
                                            @else
                                                <td style="border: none; padding: 0;"></td>
                                            @endif
                                            <td
                                                style="border: none; padding: 0; vertical-align: top; width: 20px; padding-left: 5px;">
                                                {{ $loop->iteration }}.</td>
                                            <td style="border: none; padding: 0; vertical-align: top;">
                                                {{ $petugas['name'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endif

            </table>
            <table class="detail-table" style="margin-top: 10px;">
                <tr>
                    <td class="label">Tanggal Pemeriksaan</td>
                    <td>:
                        {{ \Carbon\Carbon::parse($borangPemeriksaan->tanggal_pemeriksaan)->translatedFormat('j F Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Tempat Pemeriksaan</td>
                    <td>
                        : {{ $borangPemeriksaan->tempat_pemeriksaan ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Relasi Akademik/Profesional Terlapor & Korban</td>
                    <td>: {{ $borangPemeriksaan->relasi_terlapor_korban ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Kronologi Kejadian</td>
                    <td>: {!! nl2br(e($borangPemeriksaan->kronologi_pemeriksaan ?? '-')) !!}</td>
                </tr>
                <tr>
                    <td class="label">Verifikasi Kebutuhan Mendesak Korban</td>
                    <td>: {!! nl2br(e($borangPemeriksaan->kebutuhan_mendesak_verifikasi ?? '-')) !!}</td>
                </tr>
                <tr>
                    <td class="label">Pemeriksaan Bukti</td>
                    <td>: {!! nl2br(e($borangPemeriksaan->pemeriksaan_bukti ?? '-')) !!}</td>
                </tr>
            </table>
        </div>

        <div class="signature-wrapper">
            <div class="signature-title">
                Bandar Lampung, {{ $borangPemeriksaan->created_at->translatedFormat('j F Y') }} <br>
                Tim Pemeriksa Satgas PPKPT ITERA
            </div>
            <table class="signature-table">
                @if (is_array($borangPemeriksaan->pemeriksa_info))
                    @foreach (collect($borangPemeriksaan->pemeriksa_info)->chunk(2) as $row)
                        <tr>
                            @foreach ($row as $petugas)
                                <td>
                                    @if (!empty($petugas['tanda_tangan']) && file_exists(public_path('storage/' . $petugas['tanda_tangan'])))
                                        <img src="{{ public_path('storage/' . $petugas['tanda_tangan']) }}"
                                            alt="Tanda Tangan" class="signature-image">
                                    @else
                                        <div class="signature-placeholder"></div>
                                    @endif
                                    <p class="signature-name">
                                        {{ $petugas['name'] ?? 'N/A' }}
                                    </p>
                                </td>
                            @endforeach
                            @if (count($row) < 2)
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>
</body>

</html>
