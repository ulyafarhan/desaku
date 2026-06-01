<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Tidak Mampu</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16pt;
        }
        .header p {
            margin: 2px 0;
            font-size: 10pt;
        }
        .title {
            text-align: center;
            margin: 30px 0;
            text-decoration: underline;
            font-weight: bold;
            font-size: 14pt;
        }
        .nomor {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            text-align: justify;
            margin: 20px 0;
        }
        .biodata {
            margin: 20px 0 20px 40px;
        }
        .biodata table {
            width: 100%;
        }
        .biodata td {
            padding: 3px 0;
        }
        .biodata td:first-child {
            width: 200px;
        }
        .biodata td:nth-child(2) {
            width: 20px;
        }
        .signature {
            margin-top: 50px;
            float: right;
            text-align: center;
            width: 250px;
        }
        .qr-code {
            position: absolute;
            bottom: 40px;
            left: 40px;
        }
        .qr-code img {
            width: 100px;
            height: 100px;
        }
        .footer {
            position: absolute;
            bottom: 20px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>PEMERINTAH KABUPATEN PIDIE JAYA</h2>
        <h2>KECAMATAN BANDAR BARU</h2>
        <h2>GAMPONG UDEUNG</h2>
        <p>Alamat: Jl. Gampong Udeung, Kec. Bandar Baru, Kab. Pidie Jaya, Aceh 24186</p>
        <p>Email: gampong@udeung.desa.id | Telp: 0853-xxxx-xxxx</p>
    </div>

    <div class="title">
        SURAT KETERANGAN TIDAK MAMPU
    </div>

    <div class="nomor">
        Nomor: {{ $nomor_surat }}
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini Keuchik Gampong Udeung, Kecamatan Bandar Baru, Kabupaten Pidie Jaya, Provinsi Aceh, dengan ini menerangkan bahwa:</p>
    </div>

    <div class="biodata">
        <table>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><strong>{{ $pemohon->nama_lengkap }}</strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $pemohon->nik }}</td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $pemohon->tempat_lahir }}, {{ $pemohon->tanggal_lahir->format('d-m-Y') }}</td>
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
            <tr>
                <td>Jumlah Tanggungan</td>
                <td>:</td>
                <td>{{ $data_isian['jumlah_tanggungan'] }} orang</td>
            </tr>
            <tr>
                <td>Penghasilan Per Bulan</td>
                <td>:</td>
                <td>Rp {{ number_format($data_isian['penghasilan_perbulan'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="content">
        <p>Adalah benar warga Gampong Udeung yang tergolong <strong>TIDAK MAMPU</strong> secara ekonomi.</p>
        
        <p>Surat keterangan ini dibuat untuk keperluan <strong>{{ $data_isian['keperluan'] }}</strong>.</p>
        
        <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature">
        <p>Gampong Udeung, {{ $tanggal_surat }}</p>
        <p><strong>Keuchik Gampong Udeung</strong></p>
        <br><br><br>
        <p><strong><u>Nama Keuchik</u></strong></p>
    </div>

    <div class="qr-code">
        <img src="{{ $qr_code_path }}" alt="QR Code">
        <p style="font-size: 8pt; margin: 5px 0;">Scan untuk verifikasi</p>
    </div>

    <div class="footer">
        Dokumen ini ditandatangani secara elektronik dan sah menurut Undang-Undang ITE
    </div>
</body>
</html>
