<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('images/Logo PPKPT 2025 Square Black - CROP.png') }}" type="image/png">
    <title>Laporan Pengaduan - {{ $pengaduan->nomor_pengaduan }}</title>
    <style>
        body {
            font-family: 'Times New Roman', sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        .content {
            margin-left: 30px;
            margin-right: 30px;
        }

        .container {
            width: 100%;
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

        .section {
            margin-bottom: 20px;
            border: none;
        }

        .section h2 {
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        table.detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table td {
            padding-top: 4px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 30%;
        }

        ul {
            padding-left: 20px;
            margin: 0;
        }

        .signature-block {
            page-break-inside: avoid;
            padding-top: 40px;
        }
    </style>
</head>

<body>
    @php
        $pelaporAdalahKorban = false;
        if ($pengaduan->pelapors->isNotEmpty()) {
            $peranUtama =
                $pengaduan->pelapors->first()->pivot->peran_dalam_pengaduan ?? $pengaduan->pelapors->first()->peran;
            if ($peranUtama === 'Korban') {
                $pelaporAdalahKorban = true;
            }
        }
    @endphp
    <div class="content">
        <div class="container">
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

            <div style="text-align: center; margin-bottom: 10px; margin-top: 10px;">
                <h3 style="text-decoration: underline; margin-bottom: 5px;">LAPORAN PENGADUAN</h3>
            </div>

            <div class="section">

                <table class="detail-table">
                    <tr>
                        <td class="label">Nomor Pengaduan</td>
                        <td>: {{ $pengaduan->nomor_pengaduan }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Pelaporan</td>
                        <td>: {{ $pengaduan->tanggal_pelaporan->translatedFormat('j F Y') }}</td>
                    </tr>
                </table>
            </div>

            @if ($pengaduan->pelapors->isNotEmpty())
                <div class="section">
                    @foreach ($pengaduan->pelapors as $pelapor)
                        @if ($loop->count > 1)
                            <h3
                                style="margin-top: {{ $loop->first ? '0' : '15px' }}; border-bottom: 1px solid #eee; padding-bottom: 5px;">
                                Pelapor ke-{{ $loop->iteration }}
                            </h3>
                        @endif
                        <table class="detail-table" style="{{ $loop->count > 1 ? 'margin-top: 5px;' : '' }}">
                            <tr>
                                <td class="label">Nama Pelapor</td>
                                <td>: {{ $pelapor->nama }}</td>
                            </tr>
                            <tr>
                                <td class="label">Peran</td>
                                <td>: {{ $pelapor->pivot->peran_dalam_pengaduan ?? $pelapor->peran }}</td>
                            </tr>
                            <tr>
                                <td class="label">Nomor Telepon Pelapor</td>
                                <td>: {{ $pelapor->nomor_telepon }}</td>
                            </tr>
                            <tr>
                                <td class="label">Jenis Kelamin Pelapor</td>
                                <td>: {{ $pelapor->jenis_kelamin }}</td>
                            </tr>
                            <tr>
                                <td class="label">Domisili Pelapor</td>
                                <td>: {{ $pelapor->domisili }}</td>
                            </tr>
                            <tr>
                                <td class="label">Memiliki Disabilitas</td>
                                <td>: {{ $pelapor->memiliki_disabilitas ? 'Ya' : 'Tidak' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Status Pelapor</td>
                                <td>: {{ $pelapor->status }}</td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif

            @if ($pengaduan->korbans->isNotEmpty() && !$pelaporAdalahKorban)
                <div class="section">

                    @foreach ($pengaduan->korbans as $korban)
                        <table class="detail-table" style="{{ $loop->count > 1 ? 'margin-top: 5px;' : '' }}">
                            <tr>
                                <td class="label">Nama Korban {{ $loop->iteration }}</td>
                                <td>: {{ $korban->nama }}</td>
                            </tr>
                            <tr>
                                <td class="label">Nomor Telepon Korban</td>
                                <td>: {{ $korban->nomor_telepon }}</td>
                            </tr>
                            <tr>
                                <td class="label">Jenis Kelamin Korban</td>
                                <td>: {{ $korban->jenis_kelamin }}</td>
                            </tr>
                            <tr>
                                <td class="label">Domisili Korban</td>
                                <td>: {{ $korban->domisili }}</td>
                            </tr>
                            <tr>
                                <td class="label">Disabilitas Korban</td>
                                <td>: {{ $korban->memiliki_disabilitas ? 'Ya' : 'Tidak' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Status Korban</td>
                                <td>: {{ $korban->status }}</td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if ($pengaduan->terlapors->isNotEmpty())
                <div class="section">
                    @foreach ($pengaduan->terlapors as $terlapor)
                        <table class="detail-table" style="{{ $loop->count > 1 ? 'margin-top: 5px;' : '' }}">
                            <tr>
                                <td class="label">Nama Terlapor {{ $loop->iteration }}</td>
                                <td>: {{ $terlapor->nama }}</td>
                            </tr>
                            <tr>
                                <td class="label">Nomor Telepon Terlapor</td>
                                <td>: {{ $terlapor->nomor_telepon }}</td>
                            </tr>
                            <tr>
                                <td class="label">Jenis Kelamin Terlapor</td>
                                <td>: {{ $terlapor->jenis_kelamin }}</td>
                            </tr>
                            <tr>
                                <td class="label">Domisili Terlapor</td>
                                <td>: {{ $terlapor->domisili }}</td>
                            </tr>
                            <tr>
                                <td class="label">Disabilitas Terlapor</td>
                                <td>: {{ $terlapor->memiliki_disabilitas ? 'Ya' : 'Tidak' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Status Terlapor</td>
                                <td>: {{ $terlapor->status }}</td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            <div class="section">

                <table class="detail-table">
                    <tr>
                        <td class="label">Jenis Kejadian</td>
                        <td>: {{ $pengaduan->jenis_kejadian }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Kejadian</td>
                        <td>: {{ $pengaduan->tanggal_kejadian->translatedFormat('j F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Lokasi Kejadian</td>
                        <td>: {{ $pengaduan->lokasi_kejadian }}</td>
                    </tr>
                    <tr>
                        <td class="label">Deskripsi</td>
                        <td style="text-align: justify;">: {{ $pengaduan->deskripsi_pengaduan }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">

                <table class="detail-table">
                    <tr>
                        <td class="label">Alasan Pengaduan</td>
                        <td>:
                            <ul>
                                @foreach ($pengaduan->alasan_pengaduan as $alasan)
                                    <li>{{ $alasan }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Kebutuhan Korban</td>
                        <td>:
                            <ul>
                                @foreach ($pengaduan->identifikasi_kebutuhan_korban as $kebutuhan)
                                    <li>{{ $kebutuhan }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="signature-block">
                <p>Bandar Lampung, {{ $pengaduan->tanggal_pelaporan->translatedFormat('j F Y') }}</p>
                @if ($pengaduan->tanda_tangan_pelapor_image_url)
                    <img src="{{ public_path(str_replace(url('/'), '', $pengaduan->tanda_tangan_pelapor_image_url)) }}"
                        alt="Tanda Tangan" style="height: 60px; margin: 5px auto; display:block;">
                @else
                    <div style="height: 80px;">
                        <p>(Tidak ada tanda tangan)</p>
                    </div>
                @endif
                @if ($pengaduan->pelapors->isNotEmpty())
                    <p style="">{{ $pengaduan->pelapors->first()->nama }}</p>
                @else
                    <p>(Data Pelapor tidak tersedia)</p>
                @endif
            </div>
        </div>
    </div>

</body>

</html>
