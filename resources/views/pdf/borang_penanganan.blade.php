<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Borang Penanganan Pengaduan - {{ $pengaduan->nomor_pengaduan }}</title>
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

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section h2 {
            margin: 0 0 10px 0;
            font-size: 12px;
            font-weight: bold;
            background-color: #e8e8e8;
            padding: 5px;
            border-left: 3px solid #000;
        }

        table.detail-table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
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

        .long-text-label {}

        .long-text-content {
            text-align: justify;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        ul,
        ol {
            padding-left: 20px;
            margin: 0;
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

        table.pihak-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        table.pihak-table th,
        table.pihak-table td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
            font-size: 11px;
        }

        table.pihak-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        h4.pihak-subheading {
            margin-top: 10px;
            margin-bottom: 5px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    @php
        $peranUtamaPelapor = ucfirst($pengaduan->pelapors->first()?->pivot->peran_dalam_pengaduan ?? 'N/A');
    @endphp

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
            <div class="judul-surat">BORANG PENANGANAN LAPORAN</div>
            <div>Nomor: {{ $pengaduan->nomor_pengaduan }}</div>
        </div>
        <div class="section">
            <h2>A. INFORMASI LAPORAN AWAL</h2>
            <table class="detail-table">
                <tr>
                    <td class="label">Nomor Pengaduan</td>
                    <td>: {{ $pengaduan->nomor_pengaduan }}</td>
                </tr>
                <tr>
                    <td class="label">Jenis Kekerasan</td>
                    <td>: {{ $pengaduan->jenis_kejadian }}</td>
                </tr>
            </table>
            <p class="long-text-label">Kronologi Awal:</p>
            <p class="long-text-content">{!! nl2br(e($pengaduan->deskripsi_pengaduan)) !!}</p>
            <table class="detail=table">
                <tr>
                    <td class="label">Alasan Pengaduan</td>
                    <td>
                        @if (is_array($pengaduan->alasan_pengaduan) && !empty($pengaduan->alasan_pengaduan))
                            <ol style="margin: 0; padding-left: 18px;">
                                @foreach ($pengaduan->alasan_pengaduan as $alasan)
                                    <li>{{ $alasan }}</li>
                                @endforeach
                            </ol>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Kebutuhan Mendesak bagi Korban</td>
                    <td>
                        @if (is_array($pengaduan->identifikasi_kebutuhan_korban) && !empty($pengaduan->identifikasi_kebutuhan_korban))
                            <ol style="margin: 0; padding-left: 18px;">
                                @foreach ($pengaduan->identifikasi_kebutuhan_korban as $kebutuhan)
                                    <li>{{ $kebutuhan }}</li>
                                @endforeach
                            </ol>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="section">
            <h2>B. IDENTITAS PIHAK TERLIBAT</h2>

            @foreach ($pengaduan->pelapors as $pelapor)
                <h4 class="pihak-subheading">Data Pelapor {{ $loop->count > 1 ? $loop->iteration : '' }}</h4>
                <table class="detail-table">
                    <tr>
                        <td class="label">Nama</td>
                        <td>: {{ $pelapor->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status</td>
                        <td>: {{ $pelapor->status ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jenis Kelamin</td>
                        <td>: {{ $pelapor->jenis_kelamin ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Domisili</td>
                        <td>: {{ $pelapor->domisili ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Memiliki Disabilitas</td>
                        <td>: {{ $pelapor->memiliki_disabilitas ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                </table>
            @endforeach

            @if ($pengaduan->korbans->isNotEmpty())
                @foreach ($pengaduan->korbans as $korban)
                    <h4 class="pihak-subheading">Data Korban {{ $loop->count > 1 ? $loop->iteration : '' }}</h4>
                    <table class="detail-table">
                        <tr>
                            <td class="label">Nama</td>
                            <td>: {{ $korban->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Status</td>
                            <td>: {{ $korban->status ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Jenis Kelamin</td>
                            <td>: {{ $korban->jenis_kelamin ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Domisili</td>
                            <td>: {{ $korban->domisili ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Memiliki Disabilitas</td>
                            <td>: {{ $korban->memiliki_disabilitas ? 'Ya' : 'Tidak' }}</td>
                        </tr>
                    </table>
                @endforeach
            @endif

            @if ($pengaduan->terlapors->isNotEmpty())
                @foreach ($pengaduan->terlapors as $terlapor)
                    <h4 class="pihak-subheading">Data Terlapor {{ $loop->count > 1 ? $loop->iteration : '' }}</h4>
                    <table class="detail-table">
                        <tr>
                            <td class="label">Nama</td>
                            <td>: {{ $terlapor->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Status</td>
                            <td>: {{ $terlapor->status ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Jenis Kelamin</td>
                            <td>: {{ $terlapor->jenis_kelamin ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Domisili</td>
                            <td>: {{ $terlapor->domisili ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Memiliki Disabilitas</td>
                            <td>: {{ $terlapor->memiliki_disabilitas ? 'Ya' : 'Tidak' }}</td>
                        </tr>
                    </table>
                @endforeach
            @endif
        </div>

        <div class="section">
            <h2>C. TINDAK LANJUT PENANGANAN</h2>
            <table class="detail-table">
                <tr>
                    <td class="label">Analisis Kronologi Peristiwa</td>
                    <td style="text-align: justify;">: {!! nl2br(e($borangPenanganan->deskripsi_pengaduan ?? '-')) !!}</td>
                </tr>
                <tr>
                    <td class="label">Pihak yang Telah Dihubungi</td>
                    <td>:
                        @if (!empty($borangPenanganan->pihak_yang_dihubungi) && is_array($borangPenanganan->pihak_yang_dihubungi))
                            <ol>
                                @foreach ($borangPenanganan->pihak_yang_dihubungi as $pihak)
                                    <li>{{ $pihak['nama'] ?? '-' }}</li>
                                @endforeach
                            </ol>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Kemungkinan Kerja Sama</td>
                    <td>: {{ $borangPenanganan->kerja_sama ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="signature-wrapper">
            <div class="signature-right">
                <p style="margin: 0;">Bandar Lampung,
                    {{ $borangPenanganan->created_at->translatedFormat('j F Y') }}</p>
                <p style="margin: 0;">Petugas Satgas PPKPT ITERA,</p>

                @if ($borangPenanganan->user && $borangPenanganan->user->tanda_tangan)
                    <img src="{{ public_path('storage/' . $borangPenanganan->user->tanda_tangan) }}"
                        style="height: 80px; margin-top: 5px; margin-bottom: 5px;">
                @else
                    <br><br><br><br>
                @endif

                <p style="font-weight: bold; text-decoration: underline; margin: 0;">
                    {{ $borangPenanganan->user->name ?? 'N/A' }}
                </p>
                <p style="margin: 0;">NIP: {{ $borangPenanganan->user->nip ?? 'N/A' }}</p>
            </div>
        </div>

    </div>
</body>

</html>
