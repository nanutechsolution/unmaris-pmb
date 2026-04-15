<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Formulir PMB - {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" />
    <!-- Menggunakan Tailwind untuk layouting cepat -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* PENGATURAN KERTAS A4 UNTUK PRINTER */
        @page {
            size: A4 portrait;
            margin: 10mm 15mm;
            /* Margin standar dokumen resmi */
        }

        body {
            background-color: #e5e7eb;
            /* Abu-abu di layar komputer */
            font-family: 'Times New Roman', Times, serif;
            /* Font resmi dokumen */
            font-size: 11pt;
            color: #000;
        }

        /* Kertas simulasi di layar */
        .kertas-a4 {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 10mm 15mm;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* PERILAKU SAAT TOMBOL PRINT DITEKAN (CTRL+P) */
        @media print {
            body {
                background-color: #fff;
                margin: 0;
                padding: 0;
            }

            .kertas-a4 {
                box-shadow: none;
                margin: 0;
                padding: 0;
                width: 100%;
                min-height: auto;
            }

            .no-print {
                display: none !important;
            }

            /* Sembunyikan tombol saat dicetak */
            .page-break {
                page-break-after: always;
            }

            /* Pisah halaman jika cetak massal */

            /* Paksa printer mencetak background dan logo */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        /* UTILITY UNTUK DESAIN FORMULIR FISIK */
        .titik-titik {
            border-bottom: 1.5px dotted #000;
            flex-grow: 1;
            margin-left: 8px;
            display: flex;
            align-items: flex-end;
            padding-bottom: 2px;
        }

        .isian-data {
            font-family: 'Courier New', Courier, monospace;
            /* Font mesin tik untuk isian */
            font-weight: bold;
            font-size: 12pt;
            text-transform: uppercase;
            padding-left: 5px;
        }

        .kotak-ceklis {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            border: 1.5px solid #000;
            margin-right: 6px;
            font-weight: bold;
            font-size: 14px;
            line-height: 1;
        }

        .grid-form {
            display: grid;
            grid-template-columns: 230px 1fr;
            row-gap: 10px;
            align-items: end;
        }
    </style>
</head>

<body>

    <!-- TOMBOL KENDALI (Hanya tampil di layar komputer) -->
    <div class="w-[210mm] mx-auto mb-4 mt-6 flex justify-between items-center no-print">
        <a href="javascript:history.back()" class="px-5 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 font-bold shadow-md">
            ⬅ Kembali
        </a>
        <button onclick="window.print()" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-black text-lg hover:bg-blue-700 shadow-md flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Formulir ({{ count($pendaftars) }} Lembar)
        </button>
    </div>

    <!-- LOOPING UNTUK CETAK MASSAL -->
    @foreach($pendaftars as $index => $p)
    <div class="kertas-a4 {{ !$loop->last ? 'page-break' : '' }}">

        <!-- ================= KOP SURAT ================= -->
        <div class="flex items-center border-b-[3px] border-black pb-4 mb-5">
            <!-- LOGO KAMPUS -->
            <div class="w-24 shrink-0 flex justify-center">
                <!-- PASTIKAN FILE logo.png ADA DI FOLDER public/images/ -->
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 object-contain " onerror="this.style.display='none'">
            </div>

            <div class="flex-1 text-center px-2">
                <h1 class="text-xl font-black uppercase tracking-wider m-0">{{ config('app.name', 'UNIVERSITAS STELLA MARIS SUMBA') }}</h1>
                <p class="text-[10pt] font-bold m-0 uppercase">SK.MENDIKBUDRISTEK NO.985/E/O/2023</p>
                <p class="text-[9pt] m-0 leading-tight mt-1">Alamat: Jln. Karya Kasih No. 5 Tambolaka, Desa Payola Umbu, Kecamatan Loura,</p>
                <p class="text-[9pt] m-0 leading-tight">Kabupaten Sumba Barat Daya, Provinsi Nusa Tenggara Timur</p>
                <p class="text-[9pt] m-0 italic">Telp. (0387) 24016 - Fax: (0387) 24016, Website: www.unmaris.ac.id</p>
            </div>
        </div>

        <!-- ================= JUDUL ================= -->
        <div class="text-center mb-6">
            <h2 class="text-[13pt] font-black uppercase underline decoration-2 underline-offset-4">Formulir Pendaftaran Mahasiswa Baru</h2>
            <h3 class="text-[11pt] font-bold uppercase mt-1">Tahun Akademik {{ $p->gelombang->tahun_akademik ?? date('Y').'/'.(date('Y')+1) }}</h3>
        </div>

        <!-- ================= BAGIAN A: REGISTRASI ================= -->
        <div class="grid-form mb-6 text-[11pt]">
            <div>1. Form Pendaftaran Online</div>
            <div class="flex items-end">: <span class="font-bold ml-2">https://pmb.unmaris.ac.id</span></div>

            <div>2. Form Pendaftaran Offline</div>
            <div class="flex items-end">: <div class="titik-titik"></div>
            </div>

            <div class="font-black uppercase mt-2">A. NO. PENDAFTARAN</div>
            <div class="flex items-center mt-2">
                : <div class="border-[1.5px] border-black px-4 py-1 ml-2 font-mono font-bold text-lg tracking-widest">{{ str_pad($p->id, 6, '0', STR_PAD_LEFT) }}</div>
                <span class="text-[9pt] italic ml-3 text-gray-600">(Diisi Panitia)</span>
            </div>

            <div class="mt-3 ml-4 uppercase">PILIHAN KELAS</div>
            <div class="flex items-center mt-3">
                : <div class="ml-2 flex items-center font-bold text-[10pt]">
                    <span class="kotak-ceklis">{{ strtolower($p->jalur_pendaftaran) == 'reguler' ? '✓' : '' }}</span> 1) Reguler
                    <span class="kotak-ceklis ml-5">{{ strtolower($p->jalur_pendaftaran) == 'karyawan' ? '✓' : '' }}</span> 2) Non Reguler/Ext
                    <span class="kotak-ceklis ml-5">{{ strtolower($p->jalur_pendaftaran) == 'pindahan' ? '✓' : '' }}</span> 3) Transfer
                </div>
            </div>
        </div>

        <!-- ================= BAGIAN B: CALON MAHASISWA ================= -->
        <div class="font-black uppercase mb-3">B. DATA CALON MAHASISWA</div>
        <div class="grid-form pl-4 mb-6 text-[11pt]">
            <div>1. Nama Lengkap</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">{{ $p->user->name }}</span></div>
            </div>

            <div>2. NIK</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data tracking-wider">{{ $p->nik }}</span></div>
            </div>

            <div>3. Tempat / Tgl. Lahir</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">{{ $p->tempat_lahir }}, {{ $p->tgl_lahir instanceof \DateTime ? $p->tgl_lahir->format('d/m/Y') : date('d/m/Y', strtotime($p->tgl_lahir)) }}</span></div>
            </div>

            <div>4. Jenis Kelamin</div>
            <div class="flex items-center">
                : <div class="ml-2 flex items-center font-bold text-[10pt]">
                    <span class="kotak-ceklis">{{ $p->jenis_kelamin == 'L' ? '✓' : '' }}</span> Laki-laki
                    <span class="kotak-ceklis ml-6">{{ $p->jenis_kelamin == 'P' ? '✓' : '' }}</span> Perempuan
                </div>
            </div>

            <div>5. Alamat</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data text-[10pt]">{{ $p->alamat }}</span></div>
            </div>

            <div>6. RT/RW & Kode Pos</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">...... / ......</span></div>
            </div>

            <div class="ml-4">Kota/Kabupaten</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data"></span></div>
            </div>

            <div class="ml-4">No. HP / Email</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">{{ $p->nomor_hp }} / {{ strtolower($p->user->email) }}</span></div>
            </div>

            <div>7. Asal Sekolah/NPSN</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">{{ $p->asal_sekolah }}</span></div>
            </div>

            <div>8. NISN</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">{{ $p->nisn ?? '-' }}</span></div>
            </div>

            <div>9. Alamat Sekolah</div>
            <div class="flex items-end">: <div class="titik-titik"></div>
            </div>

            <div>10. Program Studi Pilihan 1</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">{{ $p->pilihan_prodi_1 }}</span></div>
            </div>

            <div><span class="opacity-0">10.</span> Program Studi Pilihan 2</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">{{ $p->pilihan_prodi_2 ?? '-' }}</span></div>
            </div>
        </div>

        <!-- ================= BAGIAN 11: DATA ORANG TUA ================= -->
        <div class="grid-form pl-4 text-[11pt]">
            <div class="font-black uppercase mt-1 uppercase col-span-2 -ml-4">11. DATA ORANG TUA</div>

            <div>1. Nama Ayah</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">{{ $p->nama_ayah }}</span></div>
            </div>

            <div>2. NIK Ayah</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data tracking-wider">{{ $p->nik_ayah ?? '-' }}</span></div>
            </div>

            <div>3. Pekerjaan Ayah</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">{{ $p->pekerjaan_ayah ?? '-' }}</span></div>
            </div>

            <div>4. Pendidikan Ayah</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">{{ $p->pendidikan_ayah ?? '-' }}</span></div>
            </div>

            <div>5. Nama Ibu</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">{{ $p->nama_ibu }}</span></div>
            </div>

            <div>6. NIK Ibu</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data tracking-wider">{{ $p->nik_ibu ?? '-' }}</span></div>
            </div>

            <div>7. Pekerjaan Ibu</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">{{ $p->pekerjaan_ibu ?? '-' }}</span></div>
            </div>

            <div>8. Pendidikan Ibu</div>
            <div class="flex items-end">: <div class="titik-titik"><span class="isian-data">{{ $p->pendidikan_ibu ?? '-' }}</span></div>
            </div>
        </div>

        <!-- ================= TTD & FOTO ================= -->
        <div class="text-right mt-8 mb-4 font-bold">
            Tambolaka, ......................................... 20....
        </div>

        <div class="flex justify-between items-end mb-8 px-4">
            <!-- TTD PETUGAS -->
            <div class="text-center w-1/3">
                <p class="mb-16">Petugas</p>
                <div class="border-b-[1.5px] border-black w-3/4 mx-auto mb-1"></div>
                <p class="text-[9pt] italic">(Nama Terang & Tanda Tangan)</p>
            </div>

            <!-- PAS FOTO -->
            <div class="w-1/3 flex justify-center">
                <div class="border-[1.5px] border-black w-[3cm] h-[4cm] flex items-center justify-center relative bg-gray-50/50">
                    <span class="text-gray-400 font-black absolute z-0 text-xs text-center leading-tight">Pas Foto<br>4 x 6</span>
                    <!-- Jika ada foto, jadikan hitam putih (Grayscale) agar hemat tinta dan rapi -->
                    @if($p->foto_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($p->foto_path))
                    <img src="{{ asset('storage/'.$p->foto_path) }}" class="w-full h-full object-cover z-10 grayscale" style="filter: grayscale(100%);">
                    @endif
                </div>
            </div>

            <!-- TTD MABA -->
            <div class="text-center w-1/3">
                <p class="mb-16">Calon Mahasiswa</p>
                <div class="w-4/5 mx-auto text-center font-black underline decoration-2 uppercase font-mono text-[12pt] leading-none">
                    {{ $p->user->name }}
                </div>
            </div>
        </div>

        <!-- ================= LAMPIRAN ================= -->
        <div class="text-[9pt] mb-10 pl-4">
            <p class="font-bold mb-1">Lampiran Syarat Pendaftaran:</p>
            <ol class="list-decimal pl-5 m-0 space-y-0.5">
                <li>Fotocopy Ijazah terakhir/ Surat Tanda Lulus</li>
                <li>Fotocopy Kartu Keluarga</li>
                <li>Akta Kelahiran</li>
                <li>KTP</li>
                <li>Pas Foto 4x6 Berwarna 4 Lembar</li>
            </ol>
        </div>

        <!-- ================= POTONGAN PROSPEK ================= -->
        <div class="border-t-[2px] border-dashed border-black pt-4 relative mt-auto">
            <span class="absolute -top-3 right-8 bg-white px-2 italic text-[9pt] font-bold">✂️ Gunting Disini</span>

            <div class="border-[1.5px] border-black p-4">
                <div class="grid-form mb-0" style="grid-template-columns: 180px 1fr; row-gap: 8px;">
                    <div>Nama Prospek (Referral)</div>
                    <div class="flex items-end">: <div class="titik-titik"><span class="isian-data text-[10pt]">{{ $p->nama_referensi ?? '-' }}</span></div>
                    </div>

                    <div>No. HP Prospek</div>
                    <div class="flex items-end">: <div class="titik-titik"><span class="isian-data text-[10pt]">{{ $p->nomor_hp_referensi ?? '-' }}</span></div>
                    </div>

                    <div>Nama Yang Diprospek</div>
                    <div class="flex items-end">: <div class="titik-titik"><span class="isian-data text-[10pt]">{{ $p->user->name }}</span></div>
                    </div>

                    <div>No. HP Diprospek</div>
                    <div class="flex items-end">: <div class="titik-titik"><span class="isian-data text-[10pt]">{{ $p->nomor_hp }}</span></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @endforeach

</body>

</html>