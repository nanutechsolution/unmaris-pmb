<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Formulir PMB - {{ config('app.name') }}</title>
    <style>
        @page {
            margin: 10mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.2;
        }
        .page-break {
            page-break-after: always;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        /* Kop Surat */
        .kop-surat {
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .kop-surat td {
            vertical-align: middle;
        }
        .kop-logo {
            width: 90px;
            text-align: center;
        }
        .kop-logo img {
            width: 80px;
        }
        .kop-text {
            text-align: center;
        }
        .kop-text h1 {
            font-size: 16pt;
            margin: 0;
            font-weight: bold;
        }
        .kop-text p {
            margin: 2px 0;
            font-size: 9pt;
        }

        /* Judul */
        .judul {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul h2 {
            font-size: 13pt;
            text-decoration: underline;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .judul h3 {
            font-size: 11pt;
            margin: 3px 0 0 0;
        }

        /* Form Layout */
        .form-table td {
            padding: 4px 0;
            vertical-align: bottom;
        }
        .col-label {
            width: 190px;
        }
        .col-titik {
            width: 15px;
            text-align: center;
        }
        .col-isian {
            border-bottom: 1.5px dotted #000;
        }
        
        .isian-data {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
        }
        
        .sub-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        /* Checkbox */
        .kotak-ceklis {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            text-align: center;
            line-height: 12px;
            font-size: 10px;
            margin-right: 5px;
            font-weight: bold;
        }

        /* Tanda Tangan & Foto */
        .tgl-ttd {
            text-align: right;
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .ttd-area td {
            text-align: center;
            vertical-align: bottom;
            width: 33.33%;
        }
        .box-foto {
            width: 3cm;
            height: 4cm;
            border: 1px solid #000;
            margin: 0 auto;
            text-align: center;
            position: relative;
        }
        .box-foto img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(100%);
        }

        /* Potongan Prospek */
        .potongan-area {
            margin-top: 30px;
            border-top: 2px dashed #000;
            padding-top: 20px;
            position: relative;
        }
        .label-gunting {
            position: absolute;
            top: -10px;
            right: 20px;
            background: #fff;
            padding: 0 10px;
            font-size: 9pt;
            font-style: italic;
            font-weight: bold;
        }
        .box-prospek {
            border: 1.5px solid #000;
            padding: 10px;
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
                    {{-- Gunakan public_path agar DOMPDF bisa membaca gambar lokal --}}
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
                    <p style="font-weight: bold;">SK.MENDIKBUDRISTEK NO.985/E/O/2023</p>
                    <p>Alamat: Jln. Karya Kasih No. 5 Tambolaka, Desa Payola Umbu, Kecamatan Loura,</p>
                    <p>Kabupaten Sumba Barat Daya, Provinsi Nusa Tenggara Timur</p>
                    <p><i>Telp. (0387) 24016 - Fax: (0387) 24016, Website: www.unmaris.ac.id</i></p>
                </td>
            </tr>
        </table>

        <!-- JUDUL -->
        <div class="judul">
            <h2>Formulir Pendaftaran Mahasiswa Baru</h2>
            <h3>Tahun Akademik {{ $p->gelombang->tahun_akademik ?? date('Y').'/'.(date('Y')+1) }}</h3>
        </div>

        <!-- BAGIAN A -->
        <table class="form-table">
            <tr>
                <td class="col-label">1. Form Pendaftaran Online</td>
                <td class="col-titik">:</td>
                <td><b>https://pmb.unmaris.ac.id</b></td>
            </tr>
            <tr>
                <td class="col-label">2. Form Pendaftaran Offline</td>
                <td class="col-titik">:</td>
                <td class="col-isian"></td>
            </tr>
        </table>

        <div class="sub-title">A. NO. PENDAFTARAN</div>
        <table class="form-table">
            <tr>
                <td class="col-label"></td>
                <td class="col-titik">:</td>
                <td>
                    <span style="border: 1.5px solid #000; padding: 3px 15px; font-family: monospace; font-size: 14pt; font-weight: bold; letter-spacing: 2px;">
                        {{ str_pad($p->id, 6, '0', STR_PAD_LEFT) }}
                    </span>
                    <i style="font-size: 9pt; color: #666; margin-left: 10px;">(Diisi Panitia)</i>
                </td>
            </tr>
            <tr>
                <td class="col-label" style="padding-top: 15px;">PILIHAN KELAS</td>
                <td class="col-titik" style="padding-top: 15px;">:</td>
                <td style="padding-top: 15px;">
                    <span class="kotak-ceklis">{{ strtolower($p->jalur_pendaftaran) == 'reguler' ? '✓' : '' }}</span> 1) Reguler 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="kotak-ceklis">{{ strtolower($p->jalur_pendaftaran) == 'karyawan' ? '✓' : '' }}</span> 2) Non Reguler/Ext 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="kotak-ceklis">{{ strtolower($p->jalur_pendaftaran) == 'pindahan' ? '✓' : '' }}</span> 3) Transfer
                </td>
            </tr>
        </table>

        <!-- BAGIAN B -->
        <div class="sub-title" style="margin-top: 20px;">B. DATA CALON MAHASISWA</div>
        <table class="form-table" style="margin-left: 15px; width: calc(100% - 15px);">
            <tr>
                <td class="col-label">1. Nama Lengkap</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->user->name }}</span></td>
            </tr>
            <tr>
                <td class="col-label">2. NIK</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data" style="letter-spacing: 2px;">{{ $p->nik }}</span></td>
            </tr>
            <tr>
                <td class="col-label">3. Tempat / Tgl. Lahir</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->tempat_lahir }}, {{ $p->tgl_lahir instanceof \DateTime ? $p->tgl_lahir->format('d/m/Y') : date('d/m/Y', strtotime($p->tgl_lahir)) }}</span></td>
            </tr>
            <tr>
                <td class="col-label">4. Jenis Kelamin</td>
                <td class="col-titik">:</td>
                <td>
                    <span class="kotak-ceklis">{{ $p->jenis_kelamin == 'L' ? '✓' : '' }}</span> Laki-laki 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="kotak-ceklis">{{ $p->jenis_kelamin == 'P' ? '✓' : '' }}</span> Perempuan
                </td>
            </tr>
            <tr>
                <td class="col-label">5. Alamat</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->alamat }}</span></td>
            </tr>
            <tr>
                <td class="col-label">6. RT/RW & Kode Pos</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">...... / ......</span></td>
            </tr>
            <tr>
                <td class="col-label" style="padding-left: 15px;">Kota/Kabupaten</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data"></span></td>
            </tr>
            <tr>
                <td class="col-label" style="padding-left: 15px;">No. HP / Email</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->nomor_hp }} / {{ strtolower($p->user->email) }}</span></td>
            </tr>
            <tr>
                <td class="col-label">7. Asal Sekolah/NPSN</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->asal_sekolah }}</span></td>
            </tr>
            <tr>
                <td class="col-label">8. NISN</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->nisn ?? '-' }}</span></td>
            </tr>
            <tr>
                <td class="col-label">9. Alamat Sekolah</td>
                <td class="col-titik">:</td>
                <td class="col-isian"></td>
            </tr>
            <tr>
                <td class="col-label">10. Program Studi Pilihan 1</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->pilihan_prodi_1 }}</span></td>
            </tr>
            <tr>
                <td class="col-label" style="color: transparent;">10. <span style="color: black;">Program Studi Pilihan 2</span></td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->pilihan_prodi_2 ?? '-' }}</span></td>
            </tr>
        </table>

        <!-- BAGIAN 11 -->
        <div class="sub-title" style="margin-top: 15px;">11. DATA ORANG TUA</div>
        <table class="form-table" style="margin-left: 15px; width: calc(100% - 15px);">
            <tr>
                <td class="col-label">1. Nama Ayah</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->nama_ayah }}</span></td>
            </tr>
            <tr>
                <td class="col-label">2. NIK Ayah</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->nik_ayah ?? '-' }}</span></td>
            </tr>
            <tr>
                <td class="col-label">3. Pekerjaan Ayah</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->pekerjaan_ayah ?? '-' }}</span></td>
            </tr>
            <tr>
                <td class="col-label">4. Pendidikan Ayah</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->pendidikan_ayah ?? '-' }}</span></td>
            </tr>
            <tr>
                <td class="col-label">5. Nama Ibu</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->nama_ibu }}</span></td>
            </tr>
            <tr>
                <td class="col-label">6. NIK Ibu</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->nik_ibu ?? '-' }}</span></td>
            </tr>
            <tr>
                <td class="col-label">7. Pekerjaan Ibu</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->pekerjaan_ibu ?? '-' }}</span></td>
            </tr>
            <tr>
                <td class="col-label">8. Pendidikan Ibu</td>
                <td class="col-titik">:</td>
                <td class="col-isian"><span class="isian-data">{{ $p->pendidikan_ibu ?? '-' }}</span></td>
            </tr>
        </table>

        <!-- TTD & FOTO -->
        <div class="tgl-ttd">
            Tambolaka, ......................................... 20....
        </div>

        <table class="ttd-area">
            <tr>
                <!-- TTD PETUGAS -->
                <td style="padding-bottom: 0;">
                    <p style="margin-bottom: 60px;">Petugas</p>
                    <div style="border-bottom: 1.5px solid #000; width: 80%; margin: 0 auto;"></div>
                    <p style="font-size: 9pt; font-style: italic; margin-top: 5px;">(Nama Terang & Tanda Tangan)</p>
                </td>
                
                <!-- FOTO -->
                <td style="vertical-align: middle;">
                    <div class="box-foto">
                        @if($p->foto_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($p->foto_path))
                            @php
                                $fotoData = base64_encode(\Illuminate\Support\Facades\Storage::disk('public')->get($p->foto_path));
                                $mime = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($p->foto_path);
                            @endphp
                            <img src="data:{{ $mime }};base64,{{ $fotoData }}">
                        @else
                            <span style="display: block; margin-top: 40%; line-height: 1.2;">Pas Foto<br>4 x 6</span>
                        @endif
                    </div>
                </td>

                <!-- TTD MABA -->
                <td style="padding-bottom: 0;">
                    <p style="margin-bottom: 60px;">Calon Mahasiswa</p>
                    <div style="font-family: monospace; font-weight: bold; font-size: 12pt; text-decoration: underline; text-transform: uppercase;">
                        {{ $p->user->name }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- LAMPIRAN -->
        <div style="font-size: 9pt; margin-top: 20px; padding-left: 15px;">
            <p style="font-weight: bold; margin-bottom: 5px;">Lampiran Syarat Pendaftaran:</p>
            <ol style="margin-top: 0; padding-left: 20px;">
                <li>Fotocopy Ijazah terakhir/ Surat Tanda Lulus</li>
                <li>Fotocopy Kartu Keluarga</li>
                <li>Akta Kelahiran</li>
                <li>KTP</li>
                <li>Pas Foto 4x6 Berwarna 4 Lembar</li>
            </ol>
        </div>

        <!-- POTONGAN PROSPEK -->
        <div class="potongan-area">
            <span class="label-gunting">✂️ Gunting Disini</span>
            <div class="box-prospek">
                <table class="form-table" style="width: 100%;">
                    <tr>
                        <td style="width: 170px;">Nama Prospek (Referral)</td>
                        <td class="col-titik">:</td>
                        <td class="col-isian"><span class="isian-data">{{ $p->nama_referensi ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td>No. HP Prospek</td>
                        <td class="col-titik">:</td>
                        <td class="col-isian"><span class="isian-data">{{ $p->nomor_hp_referensi ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td>Nama Yang Diprospek</td>
                        <td class="col-titik">:</td>
                        <td class="col-isian"><span class="isian-data">{{ $p->user->name }}</span></td>
                    </tr>
                    <tr>
                        <td>No. HP Diprospek</td>
                        <td class="col-titik">:</td>
                        <td class="col-isian"><span class="isian-data">{{ $p->nomor_hp }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
        
    </div>
    @endforeach

</body>
</html>