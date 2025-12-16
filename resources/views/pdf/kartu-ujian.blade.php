<!DOCTYPE html>
<html>
<head>
    <title>Kartu Peserta Ujian - {{ $user->name }}</title>
    <style>
        /* Reset & Base Fonts */
        @page { margin: 2cm; }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11pt; 
            line-height: 1.3;
            color: #000;
        }

        /* Kop Surat Resmi */
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .header h3 { 
            font-size: 14pt; 
            margin: 0; 
            font-weight: normal; 
            text-transform: uppercase; 
        }
        .header h1 { 
            font-size: 16pt; 
            margin: 5px 0; 
            font-weight: bold; 
            text-transform: uppercase; 
        }
        .header p { 
            font-size: 10pt; 
            margin: 0; 
            font-style: italic; 
        }
        .line-thick { border-bottom: 2px solid #000; margin-top: 10px; }
        .line-thin { border-bottom: 1px solid #000; margin-top: 2px; margin-bottom: 25px; }

        /* Judul Dokumen */
        .doc-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .doc-subtitle {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 30px;
        }

        /* Container Utama untuk Layout Foto di Kanan */
        .content-container {
            position: relative; /* Agar foto bisa absolute relative ke sini */
            width: 100%;
        }

        /* Tabel Data */
        .table-data { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px; 
        }
        .table-data td { 
            padding: 4px 0; 
            vertical-align: top; 
        }
        .label { 
            width: 160px; 
            font-weight: bold; 
        }
        .separator { 
            width: 15px; 
            text-align: center; 
        }

        /* Area Foto (Pojok Kanan Atas Data) */
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
        }

        /* Section Header Kecil */
        .section-header {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            margin-top: 15px;
            margin-bottom: 10px;
            padding-bottom: 2px;
            width: 100%;
        }

        /* Footer & TTD */
        .footer {
            margin-top: 40px;
            width: 100%;
        }
        .ttd-box {
            float: right;
            width: 250px;
            text-align: center;
        }
        .ttd-space {
            height: 70px;
        }

        /* Kotak Peraturan */
        .notes-box {
            margin-top: 30px;
            border: 1px solid #000;
            padding: 10px;
            font-size: 9pt;
            clear: both;
        }
        .notes-box h4 { margin: 0 0 5px 0; font-size: 10pt; }
        .notes-box ul { margin: 0; padding-left: 20px; }
        .notes-box li { margin-bottom: 2px; }
    </style>
</head>
<body>

    <!-- KOP SURAT RESMI -->
    <div class="header">
        <h3>YAYASAN PENDIDIKAN STELLA MARIS</h3>
        <h1>UNIVERSITAS STELLA MARIS SUMBA</h1>
        <p>Jl. Soekarno Hatta No.05, Tambolaka, Sumba Barat Daya, Nusa Tenggara Timur</p>
        <p>Telp: (0387) 24xxx | Email: pmb@unmaris.ac.id | Website: www.unmaris.ac.id</p>
        <div class="line-thick"></div>
        <div class="line-thin"></div>
    </div>

    <!-- JUDUL -->
    <div class="doc-title">KARTU PESERTA SELEKSI MASUK</div>
    <div class="doc-subtitle">TAHUN AKADEMIK {{ date('Y') }}/{{ date('Y')+1 }}</div>

    <div class="content-container">
        
        <!-- FOTO PESERTA (Posisi Absolute Kanan) -->
        <div class="photo-area">
            @if($pendaftar->foto_path)
                <img src="{{ public_path('storage/' . $pendaftar->foto_path) }}" class="photo-img">
            @else
                <div class="photo-placeholder">
                    TEMPEL<br>FOTO<br>3x4
                </div>
            @endif
        </div>

        <!-- BIODATA (Margin Right agar tidak menabrak foto) -->
        <div style="margin-right: 3.5cm;">
            <table class="table-data">
                <tr>
                    <td class="label">NOMOR PESERTA</td>
                    <td class="separator">:</td>
                    <td style="font-size: 14pt; font-weight: bold; letter-spacing: 2px;">
                        {{ $no_peserta }}
                    </td>
                </tr>
                <tr>
                    <td class="label">NAMA LENGKAP</td>
                    <td class="separator">:</td>
                    <td>{{ strtoupper($user->name) }}</td>
                </tr>
                <tr>
                    <td class="label">NISN</td>
                    <td class="separator">:</td>
                    <td>{{ $pendaftar->nisn ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">ASAL SEKOLAH</td>
                    <td class="separator">:</td>
                    <td>{{ $pendaftar->asal_sekolah }}</td>
                </tr>
            </table>

            <div class="section-header">PILIHAN PROGRAM STUDI</div>
            <table class="table-data">
                <tr>
                    <td class="label">PILIHAN 1</td>
                    <td class="separator">:</td>
                    <td><strong>{{ strtoupper($pendaftar->pilihan_prodi_1) }}</strong></td>
                </tr>
                <tr>
                    <td class="label">PILIHAN 2</td>
                    <td class="separator">:</td>
                    <td>{{ $pendaftar->pilihan_prodi_2 ? strtoupper($pendaftar->pilihan_prodi_2) : '-' }}</td>
                </tr>
            </table>

            <div class="section-header">JADWAL SELEKSI</div>
            <table class="table-data">
                <tr>
                    <td class="label">UJIAN TULIS</td>
                    <td class="separator">:</td>
                    <td>
                        @if($pendaftar->jadwal_ujian)
                            {{ $pendaftar->jadwal_ujian->format('l, d F Y') }}<br>
                            Pukul: {{ $pendaftar->jadwal_ujian->format('H:i') }} WITA<br>
                            Lokasi: {{ $pendaftar->lokasi_ujian }}
                        @else
                            <i>Menunggu Jadwal</i>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">WAWANCARA</td>
                    <td class="separator">:</td>
                    <td>
                        @if($pendaftar->jadwal_wawancara)
                            {{ $pendaftar->jadwal_wawancara->format('l, d F Y') }}<br>
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

    <!-- TANDA TANGAN -->
    <div class="footer">
        <div class="ttd-box">
            <p>Tambolaka, {{ date('d F Y') }}</p>
            <p>Ketua Panitia PMB,</p>
            
            <!-- Jika ingin pakai QR Code Tanda Tangan -->
            @if(isset($qrcode))
                <div style="margin: 10px auto;">
                    <img src="data:image/svg+xml;base64,{{ $qrcode }}" width="80">
                </div>
            @else
                <div class="ttd-space"></div>
            @endif

            <p style="font-weight: bold; text-decoration: underline;">( NAMA PEJABAT )</p>
            <p>NIDN. 123456789</p>
        </div>
    </div>

    <!-- CATATAN PENTING -->
    <div class="notes-box">
        <h4>CATATAN UNTUK PESERTA:</h4>
        <ul>
            <li>Kartu ini wajib dibawa saat mengikuti Ujian Tulis dan Wawancara.</li>
            <li>Peserta wajib hadir 30 menit sebelum ujian dimulai.</li>
            <li>Wajib mengenakan pakaian rapi (Kemeja Putih & Celana/Rok Hitam) dan bersepatu.</li>
            <li>Membawa alat tulis (Pensil 2B, Penghapus, Bolpoin Hitam).</li>
            <li>Segala bentuk kecurangan akan mengakibatkan diskualifikasi.</li>
        </ul>
    </div>

</body>
</html>