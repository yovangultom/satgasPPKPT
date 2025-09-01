<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Rekomendasi - {{ $suratRekomendasi->id }}</title>
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
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .judul-surat {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 5px;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
        }

        .content-table td {
            vertical-align: top;
            padding: 1px 0;
        }

        .content-table .label {
            width: 120px;
        }

        .content-table .separator {
            width: 10px;
        }

        .paragraph {
            text-align: justify;
        }

        .signature-section {
            margin-top: 5px;
            width: 40%;
            float: right;
            text-align: left;
        }

        .signature-placeholder {
            height: 80px;
        }

        .signature-wrapper {
            width: 100%;
            page-break-inside: avoid;
            margin-top: 5px;
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

        .tembusan-section {
            margin-top: 10px;
            clear: both;
        }

        .tembusan-section ol {
            padding-left: 20px;
            margin: 0;
        }

        .pihak-divider {
            border-bottom: 1px dashed #ccc;
            margin: 10px 0;
        }

        .page-break {
            page-break-before: always;
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
            <div style="font-weight: bold">RAHASIA</div>
            <div class="judul-surat" style="">SURAT REKOMENDASI PENANGANAN KASUS KEKERASAN
            </div>
            <div>NOMOR: {{ $suratRekomendasi->nomor_surat ?? '...../...../.....' }}</div>
        </div>
        <p>
            Yth.<br>
            Rektor Institut Teknologi Sumatera<br>
            di Tempat,
        </p>

        @if ($lhp->status_terbukti === 'terbukti')
            <p class="paragraph">
                Dengan hormat,<br>
                Berdasarkan hasil pemeriksaan terhadap Berita Acara Pemeriksaan (BAP), barang bukti, serta keterangan
                yang diberikan oleh pelaku, saksi, dan korban kepada Divisi Penanganan Satuan Tugas Pencegahan dan
                Penanganan Kekerasan di Perguruan Tinggi (Satgas PPKPT Itera), pelaku dengan identitas sebagai
                berikut:
            </p>
            @foreach ($suratRekomendasi->pihak_direkomendasi_data as $pihak)
                <table class="content-table" style="margin-left: 20px;">
                    <tr>
                        <td class="label">Nama</td>
                        <td class="separator">:</td>
                        <td>{{ $pihak['nama'] ?? '-' }}</td>
                    </tr>
                    @if (($pihak['status'] ?? '') === 'Mahasiswa')
                        <tr>
                            <td class="label">NIM</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['nim'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Semester</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['semester'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Program Studi</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['prodi'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Fakultas</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['fakultas'] ?? '-' }}</td>
                        </tr>
                    @endif
                    @if (in_array($pihak['status'] ?? '', ['Dosen', 'Tendik']))
                        <tr>
                            <td class="label">NIP/NRK</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['nip_nrk'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Fakultas/Unit</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['fakultas_unit'] ?? '-' }}</td>
                        </tr>
                    @endif
                    @if (in_array($pihak['status'] ?? '', ['Warga Kampus', 'Masyarakat Umum']))
                        <tr>
                            <td class="label">NIK</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['nik'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Instansi</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['instansi'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Keterangan</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['keterangan'] ?? '-' }}</td>
                        </tr>
                    @endif
                </table>
                @if (!$loop->last)
                    <div class="pihak-divider"></div>
                @endif
            @endforeach
            <p class="paragraph">
                <b>terbukti</b> melakukan kekerasan terhadap korban yang beridentitas sebagai berikut:
            </p>
            @foreach ($suratRekomendasi->pihak_pelapor_data as $pihak)
                <table class="content-table" style="margin-left: 20px;">
                    <tr>
                        <td class="label">Nama</td>
                        <td class="separator">:</td>
                        <td>{{ $pihak['nama'] ?? '-' }}</td>
                    </tr>
                    @if (($pihak['status'] ?? '') === 'Mahasiswa')
                        <tr>
                            <td class="label">NIM</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['nim'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Semester</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['semester'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Program Studi</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['prodi'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Fakultas</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['fakultas'] ?? '-' }}</td>
                        </tr>
                    @endif
                    @if (in_array($pihak['status'] ?? '', ['Dosen', 'Tendik']))
                        <tr>
                            <td class="label">NIP/NRK</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['nip_nrk'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Fakultas/Unit</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['fakultas_unit'] ?? '-' }}</td>
                        </tr>
                    @endif
                    @if (in_array($pihak['status'] ?? '', ['Warga Kampus', 'Masyarakat Umum']))
                        <tr>
                            <td class="label">NIK</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['nik'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Instansi</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['instansi'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Keterangan</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['keterangan'] ?? '-' }}</td>
                        </tr>
                    @endif
                </table>
                @if (!$loop->last)
                    <div class="pihak-divider"></div>
                @endif
            @endforeach

            @php
                $sanksi_keterangan = $suratRekomendasi->sanksis->pluck('keterangan')->map(function ($item) {
                    return trim(strtolower($item), '.');
                });

                $sanksi_count = $sanksi_keterangan->count();
                $formatted_sanksi = '';

                if ($sanksi_count === 1) {
                    $formatted_sanksi = $sanksi_keterangan->first();
                } elseif ($sanksi_count > 1) {
                    $last_sanksi = $sanksi_keterangan->pop();
                    $formatted_sanksi = $sanksi_keterangan->implode(', ') . ' dan ' . $last_sanksi;
                }
            @endphp

            {{-- <p class="paragraph" style="margin-left: 0px; margin-top: 5px;">
                Sesuai dengan Permendikbudristek Nomor 55 Tahun 2024
                @foreach ($lhp->pasalPelanggarans as $pasal)
                    Pasal {{ $pasal->pasal }} Ayat {{ str_replace(['(', ')'], '', $pasal->ayat) }} Butir
                    ({{ $pasal->butir }})
                    @if (!$loop->last)
                        @if ($loop->remaining == 1)
                        , dan @else, jo.
                        @endif
                    @endif
                @endforeach, maka pelaku dijatuhi sanksi administratif
                {{ strtolower($suratRekomendasi->rekomendasi_data['jenis_sanksi'] ?? '') }} bagi
                {{ strtolower($suratRekomendasi->rekomendasi_data['status_pelaku_manual'] ?? '') }}, yaitu berupa:
                {{ $formatted_sanksi }}.
            </p> --}}

            <p class="paragraph" style="margin-left: 0px; margin-top: 5px;">
                Sesuai dengan Permendikbudristek Nomor 55 Tahun 2024
                {{-- PERBAIKAN: Menggunakan $lhp->pasalPelanggarans yang sudah disiapkan di controller --}}
                @foreach ($lhp->pasalPelanggarans as $pasal)
                    Pasal {{ $pasal->pasal }} Ayat {{ str_replace(['(', ')'], '', $pasal->ayat) }} Butir
                    ({{ $pasal->butir }})
                    @if (!$loop->last)
                        ,
                    @endif
                @endforeach, maka pelaku dijatuhi sanksi administratif
                {{ strtolower($suratRekomendasi->rekomendasi_data['jenis_sanksi'] ?? '') }} bagi
                {{ strtolower($suratRekomendasi->rekomendasi_data['status_pelaku_manual'] ?? '') }}, yaitu berupa:
                {{ $formatted_sanksi }}.
            </p>
        @else
            <p class="paragraph">
                Dengan hormat,<br>
                Berdasarkan hasil pemeriksaan terhadap Berita Acara Pemeriksaan (BAP), barang bukti, serta keterangan
                yang diberikan oleh pelaku, saksi, dan korban kepada Divisi Penanganan Satuan Tugas Pencegahan dan
                Penanganan Kekerasan di Perguruan Tinggi (Satgas PPKPT Itera), terlapor dengan identitas sebagai
                berikut:
            </p>

            @foreach ($suratRekomendasi->pihak_direkomendasi_data as $pihak)
                <table class="content-table" style="margin-left: 20px;">
                    <tr>
                        <td class="label">Nama</td>
                        <td class="separator">:</td>
                        <td>{{ $pihak['nama'] ?? '-' }}</td>
                    </tr>
                    @if (($pihak['status'] ?? '') === 'Mahasiswa')
                        <tr>
                            <td class="label">NIM</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['nim'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Semester</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['semester'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Program Studi</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['prodi'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Fakultas</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['fakultas'] ?? '-' }}</td>
                        </tr>
                    @endif
                    @if (in_array($pihak['status'] ?? '', ['Dosen', 'Tendik']))
                        <tr>
                            <td class="label">NIP/NRK</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['nip_nrk'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Fakultas/Unit</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['fakultas_unit'] ?? '-' }}</td>
                        </tr>
                    @endif
                    @if (in_array($pihak['status'] ?? '', ['Warga Kampus', 'Masyarakat Umum']))
                        <tr>
                            <td class="label">NIK</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['nik'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Instansi</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['instansi'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Keterangan</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['keterangan'] ?? '-' }}</td>
                        </tr>
                    @endif
                </table>
                @if (!$loop->last)
                    <div class="pihak-divider"></div>
                @endif
            @endforeach

            <p class="paragraph">
                <b>tidak terbukti</b> melakukan kekerasan terhadap pelapor yang beridentitas sebagai berikut:
            </p>

            @foreach ($suratRekomendasi->pihak_pelapor_data as $pihak)
                <table class="content-table" style="margin-left: 20px;">
                    <tr>
                        <td class="label">Nama</td>
                        <td class="separator">:</td>
                        <td>{{ $pihak['nama'] ?? '-' }}</td>
                    </tr>
                    @if (($pihak['status'] ?? '') === 'Mahasiswa')
                        <tr>
                            <td class="label">NIM</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['nim'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Semester</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['semester'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Program Studi</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['prodi'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Fakultas</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['fakultas'] ?? '-' }}</td>
                        </tr>
                    @endif
                    @if (in_array($pihak['status'] ?? '', ['Dosen', 'Tendik']))
                        <tr>
                            <td class="label">NIP/NRK</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['nip_nrk'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Fakultas/Unit</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['fakultas_unit'] ?? '-' }}</td>
                        </tr>
                    @endif
                    @if (in_array($pihak['status'] ?? '', ['Warga Kampus', 'Masyarakat Umum']))
                        <tr>
                            <td class="label">NIK</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['nik'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Instansi</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['instansi'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Keterangan</td>
                            <td class="separator">:</td>
                            <td>{{ $pihak['keterangan'] ?? '-' }}</td>
                        </tr>
                    @endif
                </table>
                @if (!$loop->last)
                    <div class="pihak-divider"></div>
                @endif
            @endforeach

            <p class="paragraph">
                Sehubungan dengan hal tersebut, kami mohon agar dapat dilakukan pemulihan nama baik terhadap yang
                bersangkutan sebagaimana mestinya.
            </p>
        @endif
        <p class="paragraph">
            Demikian surat ini kami sampaikan untuk dapat digunakan sepenuhnya oleh Rektor Institut Teknologi Sumatera.
        </p>

        <div class="signature-wrapper">
            <div class="signature-right signature-block">

                <p>Lampung Selatan,
                    {{ \Carbon\Carbon::parse($suratRekomendasi->created_at)->translatedFormat('j F Y') }}<br>
                    Hormat kami,<br>
                </p>
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
                        style="height: 60px; margin: 5px auto;">
                @else
                    <div style="height: 70px;"></div>
                @endif

                <p>
                    <b><u>{{ $ketuaSatgas->nama ?? 'Nama Ketua Belum Diatur' }}</u></b><br>
                    NIP. {{ $ketuaSatgas->nip ?? '.....................' }}
                </p>
            </div>


            @if ($suratRekomendasi->tembusan)
                <div class="tembusan-section">
                    <b>Tembusan:</b>
                    <ol>
                        @foreach (explode("\n", $suratRekomendasi->tembusan) as $item)
                            <li>{!! nl2br(e(trim($item))) !!}</li>
                        @endforeach
                    </ol>
                </div>
            @endif

            @if ($lhp->status_terbukti === 'terbukti')
                <div class="page-break"></div>
                <h3 style="text-align: left;">Lampiran 1</h3>
                @foreach ($lhp->pasalPelanggarans as $pasal)
                    <p style="margin-bottom: 0;">
                        <strong>Permendikbudristek Nomor 55 Tahun 2024 Pasal {{ $pasal->pasal }} Ayat
                            {{ str_replace(['(', ')'], '', $pasal->ayat) }} Butir ({{ $pasal->butir }})</strong>
                    </p>
                    <p style="margin-top: 0; padding-left: 0px;">{{ $pasal->butir }}.
                        <i>{{ $pasal->keterangan }};</i>
                    </p>
                @endforeach
                <br>
                @php
                    $groupedSanksis = $suratRekomendasi->sanksis->groupBy(['pasal', 'ayat']);
                @endphp

                @foreach ($groupedSanksis as $pasalGroup)
                    @foreach ($pasalGroup as $ayatGroup)
                        @php
                            $firstSanksi = $ayatGroup->first();
                            $butirs = $ayatGroup
                                ->pluck('butir')
                                ->map(function ($butir) {
                                    return "($butir)";
                                })
                                ->implode(' dan ');
                        @endphp
                        <p style="margin-bottom: 0;">
                            <strong>Permendikbudristek Nomor 55 Tahun 2024 Pasal {{ $firstSanksi->pasal }} Ayat
                                {{ str_replace(['(', ')'], '', $firstSanksi->ayat) }} Butir
                                {{ $butirs }}</strong>
                        </p>
                        <div style="padding-left: 0px;">
                            <p style="margin-top: 0; margin-bottom: 0;"> <i>
                                    ({{ str_replace(['(', ')'], '', $firstSanksi->ayat) }})
                                    Sanksi administratif tingkat {{ strtolower($firstSanksi->jenis_sanksi) }} bagi
                                    {{ strtolower($suratRekomendasi->rekomendasi_data['status_pelaku_manual'] ?? '') }}
                                    Pelaku
                                    Kekerasan berupa:
                                </i>
                            </p>
                            <ol type="a" style="margin-top: 0; padding-left: 16px;">
                                @foreach ($ayatGroup as $sanksi)
                                    <i>
                                        <li>{{ $sanksi->keterangan }};</li>
                                    </i>
                                @endforeach
                            </ol>
                        </div>
                    @endforeach
                @endforeach
            @endif
        </div>
</body>

</html>
