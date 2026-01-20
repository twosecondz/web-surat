<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Tugas - {{ $surat->nomor_surat }}</title>
    <style>
        @page {
            margin: 1.5cm 2cm 1.5cm 2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            margin-top: 0;
        }
        .logo {
            width: 70px;
            height: auto;
        }
        .header-title {
            font-size: 13pt;
            font-weight: bold;
            margin: 2px 0;
            line-height: 1.3;
        }
        .header-subtitle {
            font-size: 10pt;
            margin: 1px 0;
            line-height: 1.3;
        }
        .divider {
            border-top: 3px solid #000;
            margin: 5px 0 2px 0;
        }
        .divider-thin {
            border-top: 1px solid #000;
            margin: 0 0 10px 0;
        }
        .document-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0 5px 0;
        }
        .nomor {
            text-align: center;
            font-size: 12pt;
            margin-bottom: 15px;
        }
        .content {
            margin: 10px 0;
        }
        .section {
            margin-bottom: 10px;
        }
        .section-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        table.participants {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table.participants td {
            padding: 2px 5px;
            vertical-align: top;
        }
        .participant-number {
            width: 30px;
        }
        .participant-label {
            width: 120px;
        }
        .participant-value {
            width: auto;
        }
        .signature-section {
            margin-top: 30px;
        }
        .signature-table {
            width: 100%;
        }
        .signature-table td {
            vertical-align: top;
        }
        .signature-box {
            text-align: center;
            padding: 10px;
        }
        .signature-title {
            margin-bottom: 80px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-nip {
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <!-- Header with Logo and Title -->
    <div class="header">
        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
            <tr>
                <td style="width: 90px; vertical-align: top; text-align: center; padding: 0;">
                    {{-- Logo Placeholder - You can add logo image here --}}
                    <div style="width: 70px; height: 70px; border: 1px solid #ccc; display: inline-block; margin: 0;">
                        <img src="{{ public_path('images/logo-pancacita.png') }}" style="width: 70px; height: auto; display: block;" onerror="this.style.display='none'">
                    </div>
                </td>
                <td style="vertical-align: top; text-align: center; padding-left: 10px;">
                    <div class="header-title" style="margin-top: 0;">PEMERINTAH ACEH</div>
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

    <!-- Document Title -->
    <div class="document-title">SURAT TUGAS</div>
    <div class="nomor">NOMOR : {{ $surat->nomor_surat }}</div>

    <!-- Content -->
    <div class="content">
        <!-- Dasar Section -->
        <div class="section">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 80px; vertical-align: top;">Dasar</td>
                    <td style="width: 20px; vertical-align: top;">:</td>
                    <td style="vertical-align: top;">{{ $surat->dasar_hukum }}</td>
                </tr>
            </table>
        </div>

        <!-- Kepada Section -->
        <div class="section">
            <div class="section-label">Kepada :</div>
            <table class="participants">
                @foreach($surat->peserta as $index => $peserta)
                <tr>
                    <td class="participant-number">{{ $index + 1 }}.</td>
                    <td class="participant-label">Nama</td>
                    <td class="participant-value">: {{ $peserta->pivot->nama }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="participant-label">Pangkat/Gol</td>
                    <td class="participant-value">: {{ $peserta->pivot->pangkat }} {{ $peserta->pivot->golongan ? '/ ' . $peserta->pivot->golongan : '' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="participant-label">NIP.</td>
                    <td class="participant-value">: {{ $peserta->pivot->nip }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="participant-label">Jabatan</td>
                    <td class="participant-value">: {{ $peserta->pivot->jabatan }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="participant-label">SKPA</td>
                    <td class="participant-value">: {{ $peserta->pivot->skpa }}</td>
                </tr>
                @if(!$loop->last)
                <tr><td colspan="3" style="height: 5px;"></td></tr>
                @endif
                @endforeach
            </table>
        </div>

        <!-- Untuk Section -->
        <div class="section">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 80px; vertical-align: top;">Untuk</td>
                    <td style="width: 20px; vertical-align: top;">:</td>
                    <td style="vertical-align: top;">{{ $surat->maksud }}</td>
                </tr>
            </table>
        </div>

        <!-- Di Section -->
        <div class="section">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 80px; vertical-align: top;">Di</td>
                    <td style="width: 20px; vertical-align: top;">:</td>
                    <td style="vertical-align: top;">
                        {{ $surat->tempat_tujuan }}<br>
                        Pada tanggal {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->translatedFormat('d F Y') }} 
                        s.d {{ \Carbon\Carbon::parse($surat->tanggal_selesai)->translatedFormat('d F Y') }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Kode Rekening -->
        <div class="section">
            Kode Rekening : {{ $surat->kode_rekening }}{{ $surat->sub_kegiatan ? '/Sub Kegiatan ' . $surat->sub_kegiatan : '' }}
        </div>

        <!-- Closing Statement -->
        <div class="section">
            Demikian untuk dapat dilaksanakan sebagaimana mestinya.
        </div>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%;">
                    <div class="signature-box">
                        <div>{{ $surat->tempat_surat }}, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</div>
                        <div class="signature-title">Kepala Badan Pengelolaan Keuangan Aceh</div>
                        <div class="signature-name">{{ $surat->penandatangan_nama ?? ($surat->penandatangan->name ?? '') }}</div>
                        <div>{{ $surat->penandatangan_jabatan ?? ($surat->penandatangan->jabatan ?? '') }}</div>
                        <div class="signature-nip">NIP. {{ $surat->penandatangan_nip ?? ($surat->penandatangan->nip ?? '') }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
