<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Peserta Ujian - {{ $user->name }}</title>
    <style>
        /* Mengatur Margin Halaman agar muat 1 lembar */
        @page { margin: 1cm; }
        
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11pt; 
            line-height: 1.2;
            color: #000;
        }

        /* Kop Surat yang lebih Compact */
        .kop-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .logo-cell {
            width: 12%;
            text-align: left;
            vertical-align: middle;
        }
        .logo-img {
            width: 75px;
            height: auto;
        }
        .text-cell {
            width: 88%;
            text-align: center;
            vertical-align: middle;
        }
        .yayasan { font-size: 11pt; font-weight: bold; letter-spacing: 1px; margin: 0; }
        .universitas { font-size: 16pt; font-weight: bold; text-transform: uppercase; margin: 2px 0; }
        .alamat { font-size: 9pt; font-style: italic; margin: 0; }

        /* Judul */
        .doc-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        .doc-subtitle {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Layout Konten Utama */
        .main-content {
            width: 100%;
            position: relative;
        }

        /* Area Foto (Pojok Kanan) */
        .photo-area {
            position: absolute;
            top: 0;
            right: 0;
            width: 3cm;
            height: 4cm;
            border: 1px solid #000;
            padding: 2px;
            background: #fff;
        }
        .photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 9pt;
            font-weight: bold;
            background: #f0f0f0;
            padding-top: 1.5cm;
        }

        /* Tabel Data (Agar tidak nabrak foto, dikasih margin kanan) */
        .data-wrapper {
            margin-right: 3.2cm;
        }
        .table-data { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 10px; 
        }
        .table-data td { 
            padding: 2px 0; 
            vertical-align: top; 
        }
        .label { width: 140px; font-weight: bold; }
        .sep { width: 15px; text-align: center; }
        .val-big { font-size: 13pt; font-weight: bold; letter-spacing: 1px; }
        .val { font-weight: bold; }

        /* Section Header */
        .section-header {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            margin-top: 10px;
            margin-bottom: 5px;
            font-size: 11pt;
            width: 100%;
        }

        /* Tabel Jadwal */
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
        }
        .schedule-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        /* Footer TTD */
        .footer-table {
            width: 100%;
            margin-top: 20px;
        }
        .ttd-box {
            text-align: center;
            width: 250px;
            float: right;
        }
        .nama-pejabat {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }

        /* Kotak Catatan */
        .notes-box {
            margin-top: 15px;
            border: 1px solid #000;
            padding: 8px;
            font-size: 9pt;
            clear: both;
            background-color: #f9f9f9;
        }
        .notes-box h4 { margin: 0 0 3px 0; font-size: 10pt; text-decoration: underline; }
        .notes-box ul { margin: 0; padding-left: 20px; }
        .notes-box li { margin-bottom: 1px; }
    </style>
</head>
<body>

    <!-- KOP SURAT (Compact Table) -->
    <table class="kop-table">
        <tr>
            <td class="logo-cell">
                <!-- Pastikan path logo benar -->
                <img src="{{ public_path('images/logo.png') }}" class="logo-img" alt="Logo">
            </td>
            <td class="text-cell">
                <div class="universitas">UNIVERSITAS STELLA MARIS SUMBA</div>
                <div class="alamat">
                    Jl. Karya Kasih No. 5, Tambolaka – Kab. Sumba Barat Daya, NTT<br>
                    Telp: (0387) 2524016 | Email: pmb@unmarissumba.ac.id | Website: www.unmarissumba.ac.id
                </div>
            </td>
        </tr>
    </table>

    <!-- JUDUL -->
    <div class="doc-title">KARTU PESERTA UJIAN</div>
    <div class="doc-subtitle">PENERIMAAN MAHASISWA BARU T.A. {{ date('Y') }}/{{ date('Y')+1 }}</div>

    <div class="main-content">
        <!-- FOTO (POSISI ABSOLUTE KANAN) -->
        <div class="photo-area">
            @if($pendaftar->foto_path)
                <img src="{{ public_path('storage/' . $pendaftar->foto_path) }}" class="photo-img">
            @else
                <div class="photo-placeholder">
                    FOTO<br>3x4
                </div>
            @endif
        </div>

        <!-- BIODATA (Disebelah kiri foto) -->
        <div class="data-wrapper">
            <table class="table-data">
                <tr>
                    <td class="label">NOMOR PESERTA</td>
                    <td class="sep">:</td>
                    <td class="val-big">{{ $no_peserta }}</td>
                </tr>
                <tr>
                    <td class="label">NAMA LENGKAP</td>
                    <td class="sep">:</td>
                    <td class="val">{{ strtoupper($user->name) }}</td>
                </tr>
                <tr>
                    <td class="label">NISN</td>
                    <td class="sep">:</td>
                    <td>{{ $pendaftar->nisn ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">ASAL SEKOLAH</td>
                    <td class="sep">:</td>
                    <td>{{ $pendaftar->asal_sekolah }}</td>
                </tr>
            </table>

            <div class="section-header">PILIHAN PROGRAM STUDI</div>
            <table class="table-data">
                <tr>
                    <td class="label">PILIHAN 1</td>
                    <td class="sep">:</td>
                    <td class="val">{{ strtoupper($pendaftar->pilihan_prodi_1) }}</td>
                </tr>
                <tr>
                    <td class="label">PILIHAN 2</td>
                    <td class="sep">:</td>
                    <td>{{ $pendaftar->pilihan_prodi_2 ? strtoupper($pendaftar->pilihan_prodi_2) : '-' }}</td>
                </tr>
            </table>

            <div class="section-header">JADWAL UJIAN & SELEKSI</div>
            <table class="schedule-table">
                <tr>
                    <td class="label">UJIAN TULIS</td>
                    <td class="sep">:</td>
                    <td>
                        @if($pendaftar->jadwal_ujian)
                            <strong>{{ $pendaftar->jadwal_ujian->format('l, d F Y') }}</strong><br>
                            Pukul: {{ $pendaftar->jadwal_ujian->format('H:i') }} WITA<br>
                            Lokasi: {{ $pendaftar->lokasi_ujian }}
                        @else
                            <i>Menunggu Jadwal</i>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">WAWANCARA</td>
                    <td class="sep">:</td>
                    <td>
                        @if($pendaftar->jadwal_wawancara)
                            <strong>{{ $pendaftar->jadwal_wawancara->format('l, d F Y') }}</strong><br>
                            Pukul: {{ $pendaftar->jadwal_wawancara->format('H:i') }} WITA<br>
                            Penguji: {{ $pendaftar->pewawancara ?? 'Panitia' }}
                        @else
                            <i>Menunggu Jadwal</i>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- FOOTER TTD -->
    <table class="footer-table">
        <tr>
            <!-- Space Kiri Kosong -->
            <td></td> 
            <!-- Area TTD Kanan -->
            <td style="width: 260px;">
                <div class="ttd-box">
                    <p style="margin: 0;">Tambolaka, {{ date('d F Y') }}</p>
                    <p style="margin: 5px 0;">Ketua Panitia PMB,</p>
                    
                    <!-- QR Code / Space TTD -->
                    @if(isset($qrcode))
                        <div style="margin: 10px auto;">
                            <img src="data:image/svg+xml;base64,{{ $qrcode }}" width="70">
                        </div>
                    @else
                        <br><br><br>
                    @endif

                    <div class="nama-pejabat">Soleman Renda Bili, S.Sos, M.AP</div>
                    <div>NIDN. 3137770671130343</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- CATATAN PENTING (Footer) -->
    <div class="notes-box">
        <h4>TATA TERTIB PESERTA:</h4>
        <ul>
            <li>Kartu ini <strong>WAJIB</strong> dibawa saat mengikuti ujian tulis dan wawancara.</li>
            <li>Hadir di lokasi ujian <strong>30 menit</strong> sebelum jadwal dimulai.</li>
            <li>Berpakaian rapi (Kemeja Putih, Bawahan Hitam/Gelap, dan Bersepatu).</li>
            <li>Membawa alat tulis sendiri (Pensil 2B, Penghapus, Ballpoint Hitam).</li>
            <li>Dilarang melakukan kecurangan dalam bentuk apapun.</li>
        </ul>
    </div>

</body>
</html>