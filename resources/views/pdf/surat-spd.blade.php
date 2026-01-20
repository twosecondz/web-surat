<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Perjalanan Dinas - {{ $spd->nomor_spd }}</title>
    <style>
        @page {
            margin: 1.5cm 2cm 1.5cm 2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
        }
        .page-break {
            page-break-after: always;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .logo {
            width: 70px;
            height: auto;
        }
        .header-title {
            font-size: 13pt;
            font-weight: bold;
            margin: 3px 0;
        }
        .header-subtitle {
            font-size: 10pt;
            margin: 2px 0;
        }
        .divider {
            border-top: 3px solid #000;
            margin: 8px 0 3px 0;
        }
        .divider-thin {
            border-top: 1px solid #000;
            margin: 0 0 15px 0;
        }
        .document-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            margin: 15px 0 10px 0;
        }
        .meta-info {
            font-size: 10pt;
            margin-bottom: 15px;
        }
        table.spd-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table.spd-table td {
            border: 1px solid #000;
            padding: 5px 8px;
            vertical-align: top;
            font-size: 10pt;
        }
        table.spd-table .label-cell {
            width: 30%;
            font-weight: normal;
        }
        table.spd-table .value-cell {
            width: 70%;
        }
        table.spd-table .section-header {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .signature-section {
            margin-top: 20px;
        }
        .signature-row {
            display: table;
            width: 100%;
        }
        .signature-cell {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 10px;
        }
        .signature-title {
            margin-bottom: 70px;
            font-size: 10pt;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-details {
            margin-top: 2px;
            font-size: 10pt;
        }
        .note {
            font-size: 9pt;
            font-style: italic;
            margin-top: 15px;
        }
        /* Page 2 Styles */
        table.journey-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table.journey-table th,
        table.journey-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: middle;
            font-size: 9pt;
        }
        table.journey-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        table.journey-table .center {
            text-align: center;
        }
        table.journey-table .small {
            font-size: 8pt;
        }
    </style>
</head>
<body>
    <!-- PAGE 1: Main SPD -->
    <!-- Header -->
    <div class="header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 80px; vertical-align: middle; text-align: center;">
                    <div style="width: 70px; height: 70px; border: 1px solid #ccc; display: inline-block;">
                        <img src="{{ public_path('images/logo-pancacita.png') }}" style="width: 70px; height: auto;" onerror="this.style.display='none'">
                    </div>
                </td>
                <td style="vertical-align: middle; text-align: center;">
                    <div class="header-title">PEMERINTAH ACEH</div>
                    <div class="header-title">BADAN PENGELOLAAN KEUANGAN ACEH</div>
                    <div class="header-subtitle">Jln. T. Nyak Arief No. 219 Syiah Kuala, Kota Banda Aceh, 23114</div>
                    <div class="header-subtitle">(Komplek Kantor Gubernur Aceh, Gedung D)</div>
                    <div class="header-subtitle">Telp. 0651-7551045 Fax. 0651-7551046</div>
                    <div class="header-subtitle">website: bpka.acehprov.go.id | email:sandi_bpka@acehprov.go.id</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>
    <div class="divider-thin"></div>

    <!-- Meta Information -->
    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td style="width: 70%;">
                <div class="document-title">SURAT PERJALANAN DINAS (SPD)</div>
            </td>
            <td style="width: 30%; text-align: left; vertical-align: top; font-size: 9pt;">
                Lembar ke :<br>
                Kode No. :<br>
                Nomor : {{ $spd->nomor_spd }}
            </td>
        </tr>
    </table>

    <!-- SPD Details Table -->
    <table class="spd-table">
        <!-- Row 1: PPK -->
        <tr>
            <td class="label-cell">1. Pejabat Pembuat Komitmen</td>
            <td class="value-cell">{{ $spd->ppk_nama ?? ($spd->ppk->name ?? '-') }}</td>
        </tr>

        <!-- Row 2: Employee Details -->
        <tr>
            <td class="label-cell">2. Nama/ NIP Pegawai yang melaksanakan perjalanan dinas</td>
            <td class="value-cell">
                {{ $spd->pegawai_nama ?? $spd->pegawai->name }}<br>
                NREG. {{ $spd->pegawai_nip ?? $spd->pegawai->nip }}
            </td>
        </tr>

        <!-- Row 3: Pangkat, Golongan, Jabatan -->
        <tr>
            <td class="label-cell">
                3. a. Pangkat dan Golongan<br>
                &nbsp;&nbsp;&nbsp;b. Jabatan / Instansi<br>
                &nbsp;&nbsp;&nbsp;c. Tingkat Biaya Perjalanan Dinas
            </td>
            <td class="value-cell">
                a. {{ $spd->pegawai_pangkat ?? $spd->pegawai->pangkat ?? '-' }} {{ $spd->pegawai_golongan ? '/ ' . $spd->pegawai_golongan : '' }}<br>
                b. {{ $spd->pegawai_jabatan ?? $spd->pegawai->jabatan ?? '-' }} / {{ $spd->pegawai_instansi ?? $spd->pegawai->skpa ?? '-' }}<br>
                c. {{ $spd->tingkat_biaya ?? '-' }}
            </td>
        </tr>

        <!-- Row 4: Maksud Perjalanan -->
        <tr>
            <td class="label-cell">4. Maksud Perjalanan Dinas</td>
            <td class="value-cell">{{ $spd->maksud_perjalanan }}</td>
        </tr>

        <!-- Row 5: Alat Transportasi -->
        <tr>
            <td class="label-cell">5. Alat angkutan yang dipergunakan</td>
            <td class="value-cell">{{ $spd->alat_transportasi }}</td>
        </tr>

        <!-- Row 6: Tempat -->
        <tr>
            <td class="label-cell">
                6. a. Tempat berangkat<br>
                &nbsp;&nbsp;&nbsp;b. Tempat tujuan
            </td>
            <td class="value-cell">
                a. {{ $spd->tempat_berangkat }}<br>
                b. {{ $spd->tempat_tujuan }}
            </td>
        </tr>

        <!-- Row 7: Waktu Perjalanan -->
        <tr>
            <td class="label-cell">
                7. a. Lamanya Perjalanan Dinas<br>
                &nbsp;&nbsp;&nbsp;b. Tanggal berangkat<br>
                &nbsp;&nbsp;&nbsp;c. Tanggal harus Kembali/tiba (di tempat baru*)
            </td>
            <td class="value-cell">
                a. {{ $spd->lama_perjalanan_hari }} ({{ $spd->lama_perjalanan_hari > 1 ? 'Empat' : 'Satu' }} Hari)<br>
                b. {{ \Carbon\Carbon::parse($spd->tanggal_berangkat)->translatedFormat('d F Y') }}<br>
                c. {{ \Carbon\Carbon::parse($spd->tanggal_kembali)->translatedFormat('d F Y') }}
            </td>
        </tr>

        <!-- Row 8: Pengikut -->
        <tr>
            <td class="label-cell">8. Pengikut : Nama</td>
            <td class="value-cell">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr style="border: none;">
                        <td style="border: none; width: 10%; text-align: center; font-weight: bold;">Tanggal Lahir</td>
                        <td style="border: none; width: 90%; font-weight: bold;">Keterangan</td>
                    </tr>
                    @forelse($spd->pengikut as $pengikut)
                    <tr style="border: none;">
                        <td style="border: none; padding: 2px 5px;">{{ $loop->iteration }}.</td>
                        <td style="border: none; padding: 2px;">{{ $pengikut->tanggal_lahir ? \Carbon\Carbon::parse($pengikut->tanggal_lahir)->format('d/m/Y') : '-' }}</td>
                        <td style="border: none; padding: 2px;">{{ $pengikut->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr style="border: none;">
                        <td colspan="3" style="border: none; padding: 2px; text-align: center;">-</td>
                    </tr>
                    @endforelse
                </table>
            </td>
        </tr>

        <!-- Row 9: Pembebanan Anggaran -->
        <tr>
            <td class="label-cell">
                9. Pembebanan Anggaran<br>
                &nbsp;&nbsp;&nbsp;a. Kegiatan/Instansi<br>
                &nbsp;&nbsp;&nbsp;b. Akun/Kode Rekening
            </td>
            <td class="value-cell">
                a. {{ $spd->kegiatan_instansi ?? '-' }}<br>
                b. {{ $spd->akun_kode_rekening ?? '-' }}
            </td>
        </tr>

        <!-- Row 10: Keterangan -->
        <tr>
            <td class="label-cell">10. Keterangan lain-lain</td>
            <td class="value-cell">{{ $spd->keterangan ?? '-' }}</td>
        </tr>
    </table>

    <!-- Signature Section for Page 1 -->
    <div class="signature-section">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <!-- Empty left side -->
                </td>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <div>Dikeluarkan di {{ $spd->tempat_spd }}</div>
                    <div>Tanggal {{ \Carbon\Carbon::parse($spd->tanggal_spd)->translatedFormat('d F Y') }}</div>
                    <div class="signature-title">Pejabat Pelaksana Teknis Kegiatan<br>Badan Pengelolaan Keuangan Aceh</div>
                    <div class="signature-name">{{ $spd->pptk_nama ?? ($spd->pptk->name ?? '') }}</div>
                    <div class="signature-details">{{ $spd->pptk_jabatan ?? ($spd->pptk->jabatan ?? '') }}</div>
                    <div class="signature-details">NIP. {{ $spd->pptk_nip ?? ($spd->pptk->nip ?? '') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- PAGE BREAK -->
    <div class="page-break"></div>

    <!-- PAGE 2: Journey Logs -->
    <!-- Header (repeated) -->
    <div class="header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 80px; vertical-align: middle; text-align: center;">
                    <div style="width: 70px; height: 70px; border: 1px solid #ccc; display: inline-block;">
                        <img src="{{ public_path('images/logo-pancacita.png') }}" style="width: 70px; height: auto;" onerror="this.style.display='none'">
                    </div>
                </td>
                <td style="vertical-align: middle; text-align: center;">
                    <div class="header-title">BADAN PENGELOLAAN KEUANGAN ACEH</div>
                    <div class="header-subtitle">Jln. T. Nyak Arief No. 219 Syiah Kuala, Kota Banda Aceh, 23114</div>
                    <div class="header-subtitle">(Komplek Kantor Gubernur Aceh, Gedung D)</div>
                    <div class="header-subtitle">Telp. 0651-7551045 Fax. 0651-7551046</div>
                    <div class="header-subtitle">website: bpka.acehprov.go.id | email:sandi_bpka@acehprov.go.id</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>
    <div class="divider-thin"></div>

    <!-- Journey Log Table -->
    <table class="journey-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%;">No</th>
                <th colspan="4" style="width: 47.5%;">Tiba di</th>
                <th colspan="4" style="width: 47.5%;">Berangkat dari</th>
            </tr>
            <tr>
                <th style="width: 15%;">Pada Tanggal</th>
                <th style="width: 12%;">Kepala</th>
                <th style="width: 10%;">Pada Tanggal</th>
                <th style="width: 10.5%;">Ke</th>
                <th style="width: 10%;">(Tempat Kedudukan)</th>
                <th style="width: 12%;">Kepala</th>
                <th style="width: 15%;">Pada Tanggal</th>
                <th style="width: 10.5%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @if($spd->perjalanan && count($spd->perjalanan) > 0)
                @foreach($spd->perjalanan as $journey)
                <tr>
                    <td class="center">{{ $journey->urutan }}.</td>
                    <!-- Tiba di -->
                    <td class="center">{{ $journey->pada_tanggal ? \Carbon\Carbon::parse($journey->pada_tanggal)->format('d/m/Y') : '' }}</td>
                    <td>{{ $journey->kepala_nama ?? '' }}</td>
                    <td class="center">{{ $journey->tiba_tanggal ? \Carbon\Carbon::parse($journey->tiba_tanggal)->format('d/m/Y') : '' }}</td>
                    <td>{{ $journey->tiba_di ?? '' }}</td>
                    <!-- Berangkat dari -->
                    <td>{{ $journey->berangkat_dari ?? '' }}</td>
                    <td>{{ $journey->tiba_kepala_nama ?? '' }}</td>
                    <td class="center">{{ $journey->pada_tanggal ? \Carbon\Carbon::parse($journey->pada_tanggal)->format('d/m/Y') : '' }}</td>
                    <td>{{ $journey->keterangan ?? '' }}</td>
                </tr>
                @endforeach
            @else
            <!-- Default First Journey Entry -->
            <tr>
                <td class="center">I.</td>
                <!-- Tiba di -->
                <td class="center"></td>
                <td></td>
                <td class="center"></td>
                <td>{{ $spd->tempat_tujuan }}</td>
                <!-- Berangkat dari -->
                <td>{{ $spd->tempat_berangkat }}</td>
                <td></td>
                <td class="center">{{ \Carbon\Carbon::parse($spd->tanggal_berangkat)->format('d/m/Y') }}</td>
                <td class="small">Telah diperiksa dengan keterangan bahwa perjalanan tersebut atas perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat</td>
            </tr>
            @endif

            <!-- Empty rows for additional entries -->
            @for($i = count($spd->perjalanan ?? []); $i < 6; $i++)
            <tr>
                <td class="center">{{ ['II', 'III', 'IV', 'V', 'VI'][$i] ?? '' }}.</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endfor

            <!-- Last Row with Notes -->
            <tr>
                <td class="center">VI.</td>
                <td colspan="8" class="small">
                    Telah diperiksa dengan keterangan bahwa perjalanan tersebut atas perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Notes -->
    <div class="note">
        VII. CATATAN LAIN-LAIN<br>
        VIII. PERHATIAN :<br>
        PA/KPA/PPK/PPTK yang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan tanggal berangkat/tiba, 
        serta bendahara pengeluaran bertanggung jawab berdasarkan peraturan-peraturan Keuangan Negara apabila Negara menderita rugi akibat 
        kesalahan, kelalaian dan kealpaanya.
    </div>

    <!-- Signature for PPTK at bottom of page 2 -->
    <div class="signature-section" style="margin-top: 30px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%; text-align: center;">
                    <div>Pejabat Pelaksana Teknis Kegiatan</div>
                    <div>Badan Pengelolaan Keuangan Aceh</div>
                    <div style="margin-top: 70px;"></div>
                    <div class="signature-name">{{ $spd->pptk_nama ?? ($spd->pptk->name ?? '') }}</div>
                    <div class="signature-details">{{ $spd->pptk_jabatan ?? ($spd->pptk->jabatan ?? '') }}</div>
                    <div class="signature-details">NIP. {{ $spd->pptk_nip ?? ($spd->pptk->nip ?? '') }}</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
