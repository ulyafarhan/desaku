<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Domisili</title>
    <style>
        @page {
            margin: 3.2cm 3cm 3cm 3cm;
        }
        header {
            position: fixed;
            top: -2.4cm;
            left: 0px;
            right: 0px;
            height: 2.2cm;
            border-bottom: 3px double #000;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.35;
            margin: 0;
        }
        .title {
            text-align: center;
            margin: 12px 0;
            text-decoration: underline;
            font-weight: bold;
            font-size: 14pt;
        }
        .nomor {
            text-align: center;
            margin-bottom: 15px;
            font-size: 12pt;
        }
        .content {
            text-align: justify;
            margin: 8px 0;
        }
        .content p {
            margin: 6px 0;
        }
        .biodata {
            margin: 8px 0 8px 30px;
        }
        .biodata table {
            width: 100%;
            border-collapse: collapse;
        }
        .biodata td {
            padding: 2px 0;
            vertical-align: top;
        }
        .biodata td:first-child {
            width: 180px;
        }
        .biodata td:nth-child(2) {
            width: 15px;
        }
        .signature {
            margin-top: 15px;
            float: right;
            text-align: center;
            width: 230px;
            position: relative;
        }
        .signature p {
            margin: 2px 0;
        }
        .qr-code {
            position: absolute;
            bottom: 0px;
            left: 0px;
        }
        .qr-code img {
            width: 75px;
            height: 75px;
        }
        .footer {
            position: absolute;
            bottom: -30px;
            left: 0px;
            right: 0px;
            text-align: center;
            font-size: 7.5pt;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 0;">
            <tr>
                <td style="width: 80px; text-align: left; vertical-align: middle; padding-bottom: 5px;">
                    @php
                        $logoPath = public_path('images/logo-pidiejaya.png');
                    @endphp
                    @if(file_exists($logoPath))
                        <img src="{{ $logoPath }}" style="width: 70px; height: auto;" alt="Logo">
                    @endif
                </td>
                <td style="text-align: center; vertical-align: middle; padding-bottom: 5px;">
                    <h4 style="margin: 0; font-size: 11pt; font-weight: bold; text-transform: uppercase; line-height: 1.25;">
                        PEMERINTAH KABUPATEN {{ strtoupper(\App\Models\PengaturanGampong::get('kabupaten', 'PIDIE JAYA')) }}
                    </h4>
                    <h4 style="margin: 2px 0 0 0; font-size: 11pt; font-weight: bold; text-transform: uppercase; line-height: 1.25;">
                        KECAMATAN {{ strtoupper(\App\Models\PengaturanGampong::get('kecamatan', 'BANDAR BARU')) }}
                    </h4>
                    <h3 style="margin: 2px 0 0 0; font-size: 13pt; font-weight: bold; text-transform: uppercase; line-height: 1.25;">
                        KANTOR KEUCHIK GAMPONG {{ strtoupper(\App\Models\PengaturanGampong::get('nama_gampong', 'UDEUNG')) }}
                    </h3>
                    <p style="margin: 4px 0 0 0; font-size: 7.5pt; font-style: italic; line-height: 1.2;">
                        Alamat: Jalan Gampong {{ \App\Models\PengaturanGampong::get('nama_gampong', 'Udeung') }}, Kec. {{ \App\Models\PengaturanGampong::get('kecamatan', 'Bandar Baru') }}, Kab. {{ \App\Models\PengaturanGampong::get('kabupaten', 'Pidie Jaya') }}, Kode Pos {{ \App\Models\PengaturanGampong::get('kode_pos', '24186') }}
                    </p>
                </td>
                <td style="width: 80px;"></td>
            </tr>
        </table>
    </header>

    <div class="title">
        SURAT KETERANGAN DOMISILI
    </div>

    <div class="nomor">
        Nomor: {{ $nomor_surat }}
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini,</p>
        <p style="text-indent: 30px; margin-top: 0;">Keuchik Gampong {{ \App\Models\PengaturanGampong::get('nama_gampong', 'Udeung') }} Kecamatan {{ \App\Models\PengaturanGampong::get('kecamatan', 'Bandar Baru') }} Kabupaten {{ \App\Models\PengaturanGampong::get('kabupaten', 'Pidie Jaya') }}, menerangkan bahwa:</p>
    </div>

    <div class="biodata">
        <table>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><strong>{{ $pemohon->nama_lengkap }}</strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $pemohon->nik }}</td>
            </tr>
            <tr>
                <td>Tempat/Tgl. Lahir</td>
                <td>:</td>
                <td>{{ $pemohon->tempat_lahir }}, {{ $pemohon->tanggal_lahir->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $pemohon->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>:</td>
                <td>{{ $pemohon->agama }}</td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td>{{ $pemohon->pekerjaan }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $pemohon->keluarga->alamat }}, Dusun {{ $pemohon->keluarga->dusun }}, RT/RW {{ $pemohon->keluarga->rt_rw }}</td>
            </tr>
        </table>
    </div>

    <div class="content">
        <p>Bahwa yang bersangkutan merupakan benar warga yang berdomisili di Gampong {{ \App\Models\PengaturanGampong::get('nama_gampong', 'Udeung') }}, Kecamatan {{ \App\Models\PengaturanGampong::get('kecamatan', 'Bandar Baru') }}, Kabupaten {{ \App\Models\PengaturanGampong::get('kabupaten', 'Pidie Jaya') }} yang telah tinggal selama <strong>{{ $data_isian['lama_tinggal'] ?? ($data_isian['Lama Tinggal'] ?? '-') }} tahun</strong>.</p>
        
        <p>Surat keterangan ini dibuat untuk keperluan <strong>{{ $data_isian['keperluan'] ?? ($data_isian['Keperluan'] ?? '-') }}</strong>.</p>
        
        <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature">
        <p>Gampong {{ \App\Models\PengaturanGampong::get('nama_gampong', 'Udeung') }}, {{ $tanggal_surat }}</p>
        <p><strong>Keuchik Gampong {{ \App\Models\PengaturanGampong::get('nama_gampong', 'Udeung') }}</strong></p>
        
        @php
            $ttdPath = public_path('images/signature.png');
            $stempelPath = public_path('images/stempel.png');
        @endphp
        @if(file_exists($ttdPath))
            <img src="{{ $ttdPath }}" style="position: absolute; left: -60px; top: 10px; width: 500px; height: auto; transform: rotate(-5deg);" alt="Tanda Tangan">
        @endif
        @if(file_exists($stempelPath))
            <img src="{{ $stempelPath }}" style="position: absolute; left: -65px; top: -15px; width: 190px; height: auto; opacity: 0.85;" alt="Stempel">
        @endif

        <br><br><br>
        <p><strong><u>{{ \App\Models\PengaturanGampong::get('nama_keuchik', 'Nama Keuchik') }}</u></strong></p>
        @if(\App\Models\PengaturanGampong::get('nip_keuchik'))
            <p style="font-size: 10pt; margin-top: 2px;">NIP. {{ \App\Models\PengaturanGampong::get('nip_keuchik') }}</p>
        @endif
    </div>

    <div class="qr-code">
        <img src="{{ $qr_code_path }}" alt="QR Code">
        <p style="font-size: 7.5pt; margin: 4px 0 0 0; text-align: center; color: #666;">Scan Verifikasi</p>
    </div>

    <div class="footer">
        Dokumen ini ditandatangani secara elektronik dan sah menurut Undang-Undang ITE
    </div>
</body>
</html>
