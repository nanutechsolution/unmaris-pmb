<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Kelulusan - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }
        
        /* Layout Utama A4 dengan Margin */
        .container {
            padding: 0 40px;
        }

        /* KOP SURAT */
        .kop-surat {
            width: 100%;
            border-bottom: 3px double #000; /* Garis ganda tebal */
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .kop-surat td {
            vertical-align: middle;
        }
        .logo-cell {
            width: 15%;
            text-align: center;
        }
        .logo-img {
            width: 90px;
            height: auto;
        }
        .text-cell {
            width: 85%;
            text-align: center;
            padding-left: 10px;
        }
        .yayasan {
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .universitas {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 5px 0;
        }
        .alamat {
            font-size: 10pt;
            font-style: italic;
        }

        /* ISI SURAT */
        .judul-surat {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .nomor-surat {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 30px;
        }

        .isi-paragraf {
            text-align: justify;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        /* TABEL DATA SISWA */
        .data-table {
            width: 100%;
            margin-left: 20px;
            margin-bottom: 20px;
        }
        .data-table td {
            vertical-align: top;
            padding: 3px 0;
        }
        .label { width: 160px; }
        .sep { width: 20px; text-align: center; }
        .val { font-weight: bold; }

        /* KOTAK KEPUTUSAN */
        .keputusan-box {
            border: 2px solid #000;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 16pt;
            margin: 20px auto;
            width: 80%;
            background-color: #f9f9f9; /* Abu sangat muda */
        }

        /* FOOTER & TTD */
        .footer-table {
            width: 100%;
            margin-top: 40px;
        }
        .qr-section {
            width: 30%;
            text-align: center;
            vertical-align: bottom;
            font-size: 9pt;
            color: #555;
        }
        .ttd-section {
            width: 40%;
            text-align: center;
            vertical-align: top;
            margin-left: auto; /* Dorong ke kanan */
        }
        .nama-pejabat {
            margin-top: 70px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- KOP SURAT PROFESIONAL -->
        <table class="kop-surat">
            <tr>
                <!-- LOGO (Gunakan public_path agar terbaca oleh DOMPDF) -->
                <td class="logo-cell">
                    <img src="{{ public_path('images/logo.png') }}" class="logo-img" alt="Logo">
                </td>
                <td class="text-cell">
                    <div class="universitas">UNIVERSITAS STELLA MARIS SUMBA</div>
                    <div class="alamat">
                        Jl. Karya Kasih No. 5, Tambolaka – Kab. Sumba Barat Daya, Nusa Tenggara Timur<br>
                        Telp: (0387) 2524016 | Email: pmb@unmarissumba.ac.id | Website: www.unmarissumba.ac.id
                    </div>
                </td>
            </tr>
        </table>

        <!-- JUDUL -->
        <div class="judul-surat">SURAT KETERANGAN LULUS SELEKSI</div>
        <div class="nomor-surat">Nomor: {{ $no_surat }}</div>

        <!-- ISI -->
        <div class="isi-paragraf">
            Panitia Penerimaan Mahasiswa Baru Universitas Stella Maris Sumba (UNMARIS) Tahun Akademik {{ date('Y') }}/{{ date('Y')+1 }}, berdasarkan hasil seleksi administrasi, ujian akademik, dan wawancara, dengan ini menerangkan bahwa:
        </div>

        <table class="data-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="sep">:</td>
                <td class="val">{{ strtoupper($user->name) }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Pendaftaran</td>
                <td class="sep">:</td>
                <td class="val">REG-{{ str_pad($pendaftar->id, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td class="sep">:</td>
                <td class="val">{{ $pendaftar->nisn ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Asal Sekolah</td>
                <td class="sep">:</td>
                <td class="val">{{ $pendaftar->asal_sekolah }}</td>
            </tr>
        </table>

        <div class="isi-paragraf">
            Dinyatakan <strong>LULUS SELEKSI</strong> dan diterima sebagai Mahasiswa Baru pada:
        </div>

        <div class="keputusan-box">
            PROGRAM STUDI {{ strtoupper($pendaftar->prodi_diterima) }}
            <br><span style="font-size: 11pt; font-weight: normal;">Jalur Masuk: {{ ucfirst($pendaftar->jalur_pendaftaran) }}</span>
        </div>

        <div class="isi-paragraf">
            Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya. Calon mahasiswa diwajibkan melakukan daftar ulang sesuai dengan jadwal yang telah ditentukan.
        </div>

        <!-- FOOTER TTD -->
        <table class="footer-table">
            <tr>
                <td class="qr-section">
                    <!-- QR Code -->
                    <img src="data:image/svg+xml;base64,{{ $qrcode }}" width="80">
                    <br>
                    <i>Scan untuk validasi dokumen</i>
                </td>
                <td width="30%"></td> <!-- Spacer -->
                <td class="ttd-section">
                    Ditetapkan di: Tambolaka<br>
                    Pada Tanggal: {{ date('d F Y') }}
                    <br><br>
                    Ketua Panitia PMB,
                    <br><br><br><br>
                    <div class="nama-pejabat">Soleman Renda Bili, S.Sos, M.AP</div>
                    <div>NIDN. 3137770671130343</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>