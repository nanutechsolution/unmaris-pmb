<!DOCTYPE html>
<html>
<head>
    <title>Kartu Tanda Peserta Ujian</title>
    <style>
        body { font-family: sans-serif; font-size: 11pt; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 12px; }
        .box-nomor { border: 2px dashed #000; padding: 10px; text-align: center; font-size: 20px; font-weight: bold; margin: 20px 0; background-color: #f0f0f0; }
        .foto-placeholder { width: 113px; height: 151px; border: 1px solid #000; float: right; margin-top: -180px; object-fit: cover;}
        .table-data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-data td { padding: 5px; vertical-align: top; }
        .label { width: 160px; font-weight: bold; }
        .section-title { font-weight: bold; text-decoration: underline; margin-top: 15px; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div style="font-weight:bold; font-size: 24px;">UNMARIS</div> 
        <div class="title" style="margin-top: 5px;">UNIVERSITAS STELLA MARIS SUMBA</div>
        <div class="subtitle">Jl. Soekarno Hatta No.05, Tambolaka, Sumba Barat Daya, NTT</div>
        <div class="subtitle">Website: www.unmaris.ac.id | Email: pmb@unmaris.ac.id</div>
    </div>

    <center><h3>KARTU TANDA PESERTA UJIAN MASUK</h3></center>

    <div class="box-nomor">
        NO. PESERTA: {{ $no_peserta }}
    </div>

    <div class="content">
        <!-- DATA DIRI -->
        <table class="table-data">
            <tr><td class="label">Nama Peserta</td><td>: {{ strtoupper($user->name) }}</td></tr>
            <tr><td class="label">NISN</td><td>: {{ $pendaftar->nisn ?? '-' }}</td></tr>
            <tr><td class="label">Asal Sekolah</td><td>: {{ $pendaftar->asal_sekolah }}</td></tr>
            <tr><td class="label">Pilihan Prodi 1</td><td>: {{ $pendaftar->pilihan_prodi_1 }}</td></tr>
            <tr><td class="label">Pilihan Prodi 2</td><td>: {{ $pendaftar->pilihan_prodi_2 ?? '-' }}</td></tr>
        </table>

        <!-- JADWAL UJIAN -->
        <div class="section-title">JADWAL SELEKSI</div>
        <table class="table-data">
            <tr>
                <td class="label">1. Ujian Tulis</td>
                <td>: 
                    @if($pendaftar->jadwal_ujian)
                        <strong>{{ $pendaftar->jadwal_ujian->format('d F Y, H:i') }} WITA</strong><br>
                        Lokasi: {{ $pendaftar->lokasi_ujian }}
                    @else
                        <i>Menunggu Jadwal</i>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">2. Wawancara</td>
                <td>: 
                    @if($pendaftar->jadwal_wawancara)
                        <strong>{{ $pendaftar->jadwal_wawancara->format('d F Y, H:i') }} WITA</strong><br>
                        Pewawancara: {{ $pendaftar->pewawancara ?? 'Dosen Penguji' }}
                    @else
                        <i>Menunggu Jadwal</i>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Foto -->
        @if($pendaftar->foto_path)
            <img src="{{ public_path('storage/' . $pendaftar->foto_path) }}" class="foto-placeholder">
        @else
            <div class="foto-placeholder" style="display:flex; align-items:center; justify-content:center; text-align:center; font-size:10px;">FOTO<br>KOSONG</div>
        @endif
    </div>

    <div style="margin-top: 40px; text-align: right;">
        <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>
        <br><br><br>
        <p>( {{ strtoupper($user->name) }} )</p>
    </div>
</body>
</html>