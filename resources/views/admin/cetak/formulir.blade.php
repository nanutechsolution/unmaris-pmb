<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Formulir PMB - {{ config('app.name') }}</title>
    <style>
        /* SETTING KERTAS 1 HALAMAN PAS */
        @page {
            margin: 10mm 15mm;
            /* Margin dikecilkan agar area cetak lebih luas */
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9.5pt;
            /* Ukuran font dioptimalkan */
            color: #1f2937;
            /* Abu-abu sangat gelap (Modern) */
            line-height: 1.3;
        }

        .page-break {
            page-break-after: always;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* KOP SURAT MINIMALIS */
        .kop-surat {
            border-bottom: 2px solid #1f2937;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .kop-logo {
            width: 70px;
            text-align: left;
        }

        .kop-logo img {
            width: 65px;
        }

        .kop-text {
            text-align: left;
            padding-left: 10px;
        }

        .kop-text h1 {
            font-size: 14pt;
            margin: 0;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .kop-text p {
            margin: 2px 0;
            font-size: 8.5pt;
            color: #4b5563;
        }

        /* JUDUL FORMULIR */
        .judul-area {
            text-align: center;
            margin-bottom: 15px;
        }

        .judul-area h2 {
            font-size: 12pt;
            margin: 0 0 3px 0;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .no-pendaftaran {
            display: inline-block;
            background-color: #f3f4f6;
            padding: 4px 15px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12pt;
            font-weight: bold;
            border: 1px solid #d1d5db;
            letter-spacing: 2px;
        }

        /* SECTION TITLE */
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
            background-color: #f3f4f6;
            padding: 5px 8px;
            margin-top: 10px;
            margin-bottom: 8px;
            border-left: 3px solid #2563eb;
            /* Aksen biru kecil (akan dicetak abu-abu jika B&W) */
        }

        /* TABEL ISIAN DATA */
        .form-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .col-label {
            width: 160px;
            color: #4b5563;
        }

        .col-titik {
            width: 10px;
            text-align: center;
        }

        .col-value {
            font-weight: bold;
            text-transform: uppercase;
        }

        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #6b7280;
            text-align: center;
            line-height: 12px;
            font-size: 14px;
            margin-right: 4px;
            font-weight: bold;
            font-family: 'DejaVu Sans', sans-serif;
            /* Wajib pakai ini agar ✓ terbaca DOMPDF */
        }

        /* DATA ORANG TUA (SIDE BY SIDE) */
        .ortu-table td {
            padding: 2px 0;
        }

        .ortu-label {
            width: 90px;
            color: #4b5563;
            font-size: 8.5pt;
        }

        /* AREA TANDA TANGAN & FOTO */
        .ttd-area {
            margin-top: 15px;
        }

        .ttd-area td {
            text-align: center;
            vertical-align: bottom;
        }

        .box-foto {
            width: 2.5cm;
            /* Diperkecil sedikit agar presisi 1 halaman */
            height: 3.5cm;
            border: 1px solid #9ca3af;
            background-color: #f9fafb;
            margin: 0 auto;
            position: relative;
        }

        .box-foto img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(100%);
        }

        /* POTONGAN PROSPEK MINIMALIS */
        .potongan-area {
            margin-top: 15px;
            border-top: 1px dashed #9ca3af;
            padding-top: 10px;
        }

        .label-gunting {
            font-size: 8pt;
            font-style: italic;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .box-prospek {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            border-radius: 4px;
        }

        .prospek-table td {
            padding: 2px 0;
            font-size: 8.5pt;
        }
    </style>
</head>

<body>

    @foreach($pendaftars as $index => $p)
    <div class="{{ !$loop->last ? 'page-break' : '' }}">

        <!-- KOP SURAT -->
        <table class="kop-surat">
            <tr>
                <td class="kop-logo">
                    @php
                    $logoPath = public_path('images/logo.png');
                    $logoData = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
                    @endphp
                    @if($logoData)
                    <img src="{{ $logoData }}" alt="Logo">
                    @endif
                </td>
                <td class="kop-text">
                    <h1>{{ config('app.name', 'UNIVERSITAS STELLA MARIS SUMBA') }}</h1>
                    <p style="font-weight: bold; color: #1f2937;">SK.MENDIKBUDRISTEK NO.985/E/O/2023</p>
                    <p>Jln. Karya Kasih No. 5 Tambolaka, Desa Payola Umbu, Kec. Loura, Kab. Sumba Barat Daya, NTT</p>
                    <p>Telp/Fax: (0387) 24016 | Web: www.unmaris.ac.id | Sistem PMB: pmb.unmaris.ac.id</p>
                </td>
            </tr>
        </table>

        <!-- JUDUL & NO PENDAFTARAN -->
        <div class="judul-area">
            <h2>Formulir Pendaftaran Mahasiswa Baru</h2>
            <div style="font-size: 9.5pt; margin-bottom: 8px;">Tahun Akademik {{ $p->gelombang->tahun_akademik ?? date('Y').'/'.(date('Y')+1) }}</div>
            <div class="no-pendaftaran">NO: {{ str_pad($p->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <!-- PILIHAN KELAS & PRODI -->
        <div class="section-title">A. PILIHAN PROGRAM STUDI & KELAS</div>
        <table class="form-table">
            <tr>
                <td class="col-label" style="width: 140px;">Pilihan Kelas</td>
                <td class="col-titik">:</td>
                <td class="col-value">
                    <span class="checkbox">{{ strtolower($p->jalur_pendaftaran) == 'reguler' ? '✓' : '' }}</span> Reguler
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{{ strtolower($p->jalur_pendaftaran) == 'karyawan' ? '✓' : '' }}</span> Non Reguler/Ext
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{{ strtolower($p->jalur_pendaftaran) == 'pindahan' ? '✓' : '' }}</span> Transfer
                </td>
            </tr>
            <tr>
                <td class="col-label" style="width: 140px;">Program Studi Pilihan 1</td>
                <td class="col-titik">:</td>
                <td class="col-value">{{ $p->pilihan_prodi_1 }}</td>
            </tr>
            <tr>
                <td class="col-label" style="width: 140px;">Program Studi Pilihan 2</td>
                <td class="col-titik">:</td>
                <td class="col-value">{{ $p->pilihan_prodi_2 ?? '-' }}</td>
            </tr>
        </table>

        <!-- DATA CALON MAHASISWA -->
        <div class="section-title">B. DATA CALON MAHASISWA</div>
        <table class="form-table">
            <tr>
                <td class="col-label">Nama Lengkap</td>
                <td class="col-titik">:</td>
                <td class="col-value">{{ $p->user->name }}</td>
            </tr>
            <tr>
                <td class="col-label">NIK</td>
                <td class="col-titik">:</td>
                <td class="col-value" style="letter-spacing: 1px;">{{ $p->nik }}</td>
            </tr>
            <tr>
                <td class="col-label">Tempat, Tanggal Lahir</td>
                <td class="col-titik">:</td>
                <td class="col-value">{{ $p->tempat_lahir }}, {{ $p->tgl_lahir instanceof \DateTime ? $p->tgl_lahir->format('d/m/Y') : date('d/m/Y', strtotime($p->tgl_lahir)) }}</td>
            </tr>
            <tr>
                <td class="col-label">Jenis Kelamin</td>
                <td class="col-titik">:</td>
                <td class="col-value">
                    <span class="checkbox">{!! (strtoupper($p->jenis_kelamin) == 'L' || strtoupper($p->jenis_kelamin) == 'LAKI-LAKI') ? '&#10003;' : '' !!}</span> Laki-laki
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! (strtoupper($p->jenis_kelamin) == 'P' || strtoupper($p->jenis_kelamin) == 'PEREMPUAN') ? '&#10003;' : '' !!}</span> Perempuan
                </td>
            </tr>
            <tr>
                <td class="col-label">Alamat Lengkap</td>
                <td class="col-titik">:</td>
                <td class="col-value">{{ $p->alamat }}</td>
            </tr>
            <tr>
                <td class="col-label">Nomor HP / Email</td>
                <td class="col-titik">:</td>
                <td class="col-value">{{ $p->nomor_hp }} / <span style="text-transform: none; font-weight: normal;">{{ strtolower($p->user->email) }}</span></td>
            </tr>
            <tr>
                <td class="col-label">Asal Sekolah</td>
                <td class="col-titik">:</td>
                <td class="col-value">{{ $p->asal_sekolah }}</td>
            </tr>
            <tr>
                <td class="col-label">NISN</td>
                <td class="col-titik">:</td>
                <td class="col-value">{{ $p->nisn ?? '-' }}</td>
            </tr>
        </table>

        <!-- DATA ORANG TUA (DIBUAT SEJAJAR KIRI-KANAN UNTUK HEMAT TEMPAT) -->
        <div class="section-title">C. DATA ORANG TUA</div>
        <table width="100%">
            <tr>
                <!-- KIRI: DATA AYAH -->
                <td width="48%" valign="top">
                    <div style="font-weight: bold; margin-bottom: 5px; font-size: 8.5pt; color: #4b5563;">DATA AYAH:</div>
                    <table class="ortu-table">
                        <tr>
                            <td class="ortu-label">Nama</td>
                            <td width="10">:</td>
                            <td class="col-value">{{ $p->nama_ayah }}</td>
                        </tr>
                        <tr>
                            <td class="ortu-label">NIK</td>
                            <td>:</td>
                            <td class="col-value">{{ $p->nik_ayah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="ortu-label">Pendidikan</td>
                            <td>:</td>
                            <td class="col-value">{{ $p->pendidikan_ayah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="ortu-label">Pekerjaan</td>
                            <td>:</td>
                            <td class="col-value">{{ $p->pekerjaan_ayah ?? '-' }}</td>
                        </tr>
                    </table>
                </td>

                <td width="4%"></td> <!-- Spacer -->

                <!-- KANAN: DATA IBU -->
                <td width="48%" valign="top">
                    <div style="font-weight: bold; margin-bottom: 5px; font-size: 8.5pt; color: #4b5563;">DATA IBU:</div>
                    <table class="ortu-table">
                        <tr>
                            <td class="ortu-label">Nama</td>
                            <td width="10">:</td>
                            <td class="col-value">{{ $p->nama_ibu }}</td>
                        </tr>
                        <tr>
                            <td class="ortu-label">NIK</td>
                            <td>:</td>
                            <td class="col-value">{{ $p->nik_ibu ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="ortu-label">Pendidikan</td>
                            <td>:</td>
                            <td class="col-value">{{ $p->pendidikan_ibu ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="ortu-label">Pekerjaan</td>
                            <td>:</td>
                            <td class="col-value">{{ $p->pekerjaan_ibu ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- LAMPIRAN -->
        <div class="section-title">D. LAMPIRAN PENDAFTARAN</div>
        <table width="100%" style="font-size: 8.5pt;">
            <tr>
                <td width="50%">
                    <span class="checkbox">{!! !empty($p->ijazah_path) ? '&#10003;' : '' !!}</span> 1. Fotocopy Ijazah/SKL<br>
                    <span class="checkbox">{!! !empty($p->kk_path) ? '&#10003;' : '' !!}</span> 2. Kartu Keluarga (KK)<br>
                    <span class="checkbox">{!! !empty($p->ktp_path) ? '&#10003;' : '' !!}</span> 3. Fotocopy KTP
                </td>
                <td width="50%">
                    <span class="checkbox">{!! !empty($p->akta_path) ? '&#10003;' : '' !!}</span> 4. Akta Kelahiran<br>
                    <span class="checkbox">{!! !empty($p->foto_path) ? '&#10003;' : '' !!}</span> 5. Pas Foto 4x6 (Berwarna)
                </td>
            </tr>
        </table>

        <!-- AREA TANDA TANGAN & FOTO -->
        <div style="text-align: right; margin-top: 15px; font-size: 9pt;">
            Tambolaka, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
        <table class="ttd-area" width="100%">
            <tr>
                <td width="35%" style="padding-bottom: 0;">
                    <p style="margin-bottom: 50px; font-size: 9pt; color: #4b5563;">Panitia Pendaftaran</p>
                    <div style="border-bottom: 1px solid #1f2937; width: 80%; margin: 0 auto;"></div>
                    <p style="font-size: 8pt; color: #6b7280; margin-top: 3px;">Soleman Renda Bili, S.Sos., M.A.P</p>
                </td>

                <td width="30%" style="vertical-align: middle;">
                    <div class="box-foto">
                        @if($p->foto_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($p->foto_path))
                        @php
                        $fotoData = base64_encode(\Illuminate\Support\Facades\Storage::disk('public')->get($p->foto_path));
                        $mime = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($p->foto_path);
                        @endphp
                        <img src="data:{{ $mime }};base64,{{ $fotoData }}">
                        @else
                        <span style="display: block; margin-top: 35%; font-size: 8pt; color: #9ca3af;">Foto<br>3x4 / 4x6</span>
                        @endif
                    </div>
                </td>

                <td width="35%" style="padding-bottom: 0;">
                    <p style="margin-bottom: 50px; font-size: 9pt; color: #4b5563;">Calon Mahasiswa</p>
                    <div style="font-weight: bold; text-decoration: underline; text-transform: uppercase;">
                        {{ $p->user->name }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- POTONGAN PROSPEK (DIBIKIN COMPACT DI BAWAH) -->
        <div class="potongan-area">
            <div class="label-gunting">✂️ Gunting disini (Data Referral/Prospek)</div>
            <div class="box-prospek">
                <table width="100%">
                    <tr>
                        <td width="50%" valign="top">
                            <table class="prospek-table" width="100%">
                                <tr>
                                    <td width="100" style="color:#4b5563;">Nama Prospek</td>
                                    <td width="5">:</td>
                                    <td class="col-value">{{ $p->nama_referensi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#4b5563;">No. HP Prospek</td>
                                    <td>:</td>
                                    <td class="col-value">{{ $p->nomor_hp_referensi ?? '-' }}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="50%" valign="top">
                            <table class="prospek-table" width="100%">
                                <tr>
                                    <td width="120" style="color:#4b5563;">Maba yang diajak</td>
                                    <td width="5">:</td>
                                    <td class="col-value">{{ $p->user->name }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#4b5563;">No. HP Maba</td>
                                    <td>:</td>
                                    <td class="col-value">{{ $p->nomor_hp }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
    @endforeach

</body>

</html>