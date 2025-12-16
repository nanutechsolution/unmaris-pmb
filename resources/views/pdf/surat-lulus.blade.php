<!DOCTYPE html>
<html>
<head>
    <title>Surat Keterangan Lulus - {{ $user->name }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; color: #000; }
        .header { border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 30px; text-align: center; }
        .header h1 { font-size: 18pt; margin: 0; font-weight: bold; text-transform: uppercase; }
        .header h2 { font-size: 14pt; margin: 5px 0; font-weight: bold; }
        .header p { margin: 0; font-size: 10pt; font-style: italic; }
        
        .content { margin: 0 40px; }
        .title { text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 20px; font-size: 14pt; }
        .nomor { text-align: center; margin-top: -20px; margin-bottom: 30px; }
        
        .table-data { width: 100%; margin-bottom: 20px; }
        .table-data td { padding: 5px; vertical-align: top; }
        .label { width: 150px; }
        
        .decision-box { border: 2px solid #000; padding: 15px; text-align: center; margin: 30px 0; font-weight: bold; font-size: 14pt; }
        
        /* Footer Layout dengan Flexbox tidak support di DOMPDF lama, pakai Table */
        .footer-table { width: 100%; margin-top: 50px; }
        .footer-left { width: 40%; vertical-align: bottom; }
        .footer-right { width: 40%; text-align: right; vertical-align: top; }
        
        .ttd-area { margin-top: 80px; font-weight: bold; text-decoration: underline; }
        .qr-area { border: 1px solid #ccc; padding: 5px; display: inline-block; }
        .validasi-text { font-size: 8pt; color: #555; margin-top: 5px; }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="header">
        <h2>YAYASAN PENDIDIKAN STELLA MARIS</h2>
        <h1>UNIVERSITAS STELLA MARIS SUMBA (UNMARIS)</h1>
        <p>Jl. Soekarno Hatta No.05, Tambolaka, Sumba Barat Daya, Nusa Tenggara Timur</p>
        <p>Telp: (0387) 24xxx | Email: pmb@unmaris.ac.id | Website: www.unmaris.ac.id</p>
    </div>

    <div class="content">
        <div class="title">SURAT KETERANGAN LULUS SELEKSI</div>
        <div class="nomor">Nomor: {{ $no_surat }}</div>

        <p>Panitia Penerimaan Mahasiswa Baru Universitas Stella Maris Sumba (UNMARIS) Tahun Akademik {{ date('Y') }}/{{ date('Y')+1 }}, dengan ini menerangkan bahwa:</p>

        <table class="table-data">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td>: <strong>{{ strtoupper($user->name) }}</strong></td>
            </tr>
            <tr>
                <td class="label">Nomor Pendaftaran</td>
                <td>: REG-{{ str_pad($pendaftar->id, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td>: {{ $pendaftar->nisn ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Asal Sekolah</td>
                <td>: {{ $pendaftar->asal_sekolah }}</td>
            </tr>
        </table>

        <p>Berdasarkan hasil seleksi administrasi dan akademik, peserta tersebut dinyatakan:</p>

        <div class="decision-box">
            LULUS / DITERIMA
        </div>

        <p>Sebagai Mahasiswa Baru Universitas Stella Maris Sumba pada:</p>
        <table class="table-data">
            <tr>
                <td class="label">Program Studi</td>
                <td>: <strong>{{ strtoupper($pendaftar->pilihan_prodi_1) }}</strong></td>
            </tr>
            <tr>
                <td class="label">Jalur Masuk</td>
                <td>: {{ ucfirst($pendaftar->jalur_pendaftaran) }}</td>
            </tr>
        </table>

        <p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya. Calon mahasiswa diwajibkan melakukan daftar ulang sesuai jadwal yang ditentukan.</p>

        <!-- FOOTER DENGAN QR CODE -->
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    <div class="qr-area">
                        <!-- Embed QR Code Base64 -->
                        <img src="data:image/svg+xml;base64,{{ $qrcode }}" width="90">
                    </div>
                    <div class="validasi-text">
                        Scan untuk verifikasi<br>keaslian dokumen.
                    </div>
                </td>
                <td class="footer-right">
                    <p>Ditetapkan di: Tambolaka</p>
                    <p>Pada Tanggal: {{ date('d F Y') }}</p>
                    <br>
                    <p>Rektor / Ketua Panitia PMB,</p>
                    <div class="ttd-area">
                        ( NAMA REKTOR / KETUA )
                    </div>
                    <p>NIDN. 123456789</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>