<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('images/Logo PPKPT 2025 Square Black - CROP.png') }}" type="image/png">
    <title>Borang Kesimpulan - {{ $pengaduan->nomor_pengaduan }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            margin: 0;
            line-height: 1.5;
        }

        .container {
            width: 100%;
            padding: 0 1cm;
        }

        #kop-surat {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        #kop-surat td {
            vertical-align: middle;
        }

        #logo {
            width: 100px;
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
            font-size: 10px;
        }

        .title-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .title-section h3 {
            text-decoration: underline;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }

        .title-section span {
            font-size: 12px;
        }

        .section {
            margin-top: 20px;
        }

        .section-title {
            margin-bottom: 10px;
            font-size: 13px;
            font-weight: bold;
            background-color: #f2f2f2;
            padding: 5px;
            text-align: left;
        }

        table.detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table td {
            padding: 4px 0px;
            vertical-align: top;
        }

        .detail-table .label {
            width: 40%;
        }

        .detail-table .separator {
            width: 5%;
            text-align: center;
        }

        .detail-table .content {
            width: 55%;
            text-align: justify;
        }

        ol {
            padding-left: 20px;
            margin: 0;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            width: 40%;
            margin-left: 60%;
        }

        .footer p {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    @php
        $kesimpulan = $pengaduan->data_kesimpulan ?? [];
    @endphp
    <div class="container">
        <table id="kop-surat">
            <tr>
                <td>
                    <img id="logo" src="{{ public_path('images/Logo PPKPT 2025 Square Black.png') }}" alt="Logo">
                </td>
                <td class="kop-teks">
                    <p>KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI<br>INSTITUT TEKNOLOGI SUMATERA</p>
                    <h1>SATUAN TUGAS PENCEGAHAN DAN PENANGANAN KEKERASAN DI PERGURUAN TINGGI (PPKPT)</h1>
                    <p>Jalan Terusan Ryacudu Way Hui, Kecamatan Jati Agung, Lampung Selatan 35365</p>
                    <p>Email: satgas.ppks@itera.ac.id, Website: https://satgasppks.itera.ac.id/</p>
                </td>
            </tr>
        </table>

        <div style="text-align: center; margin-bottom: 20px; margin-top: 20px;">
            <h3 style="text-decoration: underline; margin: 0;">BORANG KESIMPULAN</h3>
            <span>Nomor: {{ $kesimpulan['nomor_pengaduan'] ?? $pengaduan->nomor_pengaduan }}</span>
        </div>

        @if (!empty($kesimpulan))


            @if (($kesimpulan['status_kasus_terlapor'] ?? null) === 'terbukti')
                <div class="section">
                    <div class="section-title">A. HASIL PEMERIKSAAN: TERBUKTI</div>
                    <table class="detail-table">
                        <tr>
                            <td class="label">Status Kasus Akhir</td>
                            <td class="separator">:</td>
                            <td class="content"><strong>Terbukti melakukan kekerasan</strong></td>
                        </tr>
                        <tr>
                            <td class="label">Dugaan Kekerasan</td>
                            <td class="separator">:</td>
                            <td class="content">{{ $dataTerbukti['jenis_kejadian'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Bentuk Pendampingan/Perlindungan</td>
                            <td class="separator">:</td>
                            <td class="content">{{ $kesimpulan['bentuk_pendampingan'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="vertical-align: top;">Rekomendasi Pemulihan Korban</td>
                            <td class="separator" style="vertical-align: top;">:</td>
                            <td class="content">
                                @if (!empty($kesimpulan['rekomendasi_pemulihan']))
                                    <ol>
                                        @foreach ($kesimpulan['rekomendasi_pemulihan'] as $item)
                                            <li>{{ $item['Rekomendasi Pemulihan Korban'] ?? '-' }}</li>
                                        @endforeach
                                    </ol>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label" style="vertical-align: top;">Rekomendasi Sanksi Kepada Pelaku</td>
                            <td class="separator" style="vertical-align: top;">:</td>
                            <td class="content">
                                @if (!empty($kesimpulan['sanksi_pelaku']))
                                    <ol>
                                        @foreach ($kesimpulan['sanksi_pelaku'] as $item)
                                            <li>{{ $item['Sanksi Kepada Pelaku'] ?? '-' }}</li>
                                        @endforeach
                                    </ol>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label" style="vertical-align: top;">Tindakan Pencegahan Keberulangan</td>
                            <td class="separator" style="vertical-align: top;">:</td>
                            <td class="content">
                                @if (!empty($kesimpulan['tindakan_pencegahan']))
                                    <ol>
                                        @foreach ($kesimpulan['tindakan_pencegahan'] as $item)
                                            <li>{{ $item['Tindakan Pencegahan Keberulangan'] ?? '-' }}</li>
                                        @endforeach
                                    </ol>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Status Akhir Kasus</td>
                            <td class="separator">:</td>
                            <td class="content" style="text-transform: capitalize;">
                                {{ str_replace('_', ' ', $kesimpulan['status_kasus'] ?? '-') }}
                            </td>
                        </tr>
                    </table>
                </div>
            @elseif (($kesimpulan['status_kasus_terlapor'] ?? null) === 'tidak_terbukti')
                @php
                    $dataTidakTerbukti = $kesimpulan['tidak_terbukti'] ?? [];
                    $terlapor = $pengaduan->terlapors->find($dataTidakTerbukti['terlapor_id'] ?? null);
                @endphp
                <div class="section">
                    <div class="section-title">A. HASIL PEMERIKSAAN: TIDAK TERBUKTI</div>
                    <table class="detail-table">
                        <tr>
                            <td class="label">Status Kasus Akhir</td>
                            <td class="separator">:</td>
                            <td class="content"><strong>Tidak terbukti melakukan kekerasan</strong></td>
                        </tr>
                        <tr>
                            <td class="label">Nama Terlapor</td>
                            <td class="separator">:</td>
                            <td class="content">{{ $terlapor->nama ?? 'Data tidak ditemukan' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Dugaan Kekerasan (Laporan Awal)</td>
                            <td class="separator">:</td>
                            <td class="content">{{ $dataTidakTerbukti['jenis_kejadian'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Ringkasan Hasil Pemeriksaan</td>
                            <td class="separator">:</td>
                            <td class="content">{{ $dataTidakTerbukti['ringkasan_pemeriksaan'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="vertical-align: top;">Rekomendasi Pemulihan Nama Baik Terlapor
                            </td>
                            <td class="separator" style="vertical-align: top;">:</td>
                            <td class="content">
                                @if (!empty($kesimpulan['rekomendasi_pemulihan']))
                                    <ol>
                                        @foreach ($kesimpulan['rekomendasi_pemulihan'] as $item)
                                            <li>{{ $item['Rekomendasi Bentuk Pemulihan Nama Baik Terlapor'] ?? '-' }}
                                            </li>
                                        @endforeach
                                    </ol>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Status Akhir Kasus</td>
                            <td class="separator">:</td>
                            <td class="content" style="text-transform: capitalize;">
                                {{ str_replace('_', ' ', $kesimpulan['status_kasus_akhir'] ?? '-') }}
                            </td>
                        </tr>
                    </table>
                </div>
            @endif
        @else
            <p style="text-align: center; margin-top: 50px;">Data kesimpulan belum diisi.</p>
        @endif

        <div class="footer">
            <p>Lampung Selatan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Ketua Satgas PPKPT ITERA</p>

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
                    style="height: 60px; margin: 5px 0;">
            @else
                <div style="height: 70px;"></div>
            @endif

            <p><strong><u>{{ $ketuaSatgas->nama ?? 'Nama Ketua Belum Diatur' }}</u></strong></p>
            <p>NIP: {{ $ketuaSatgas->nip ?? '-' }}</p>
        </div>
    </div>
</body>

</html>
