<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rekapitulasi PMB</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 6px; text-align: left; }
        .table th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">REKAPITULASI PENERIMAAN MAHASISWA BARU</h2>
        <h3 style="margin:5px 0;">UNIVERSITAS STELLA MARIS SUMBA</h3>
        <p>Tahun Akademik {{ date('Y') }}/{{ date('Y')+1 }}</p>
    </div>

    <div style="margin-bottom: 15px;">
        <strong>Filter Data:</strong><br>
        Prodi: {{ $filter['prodi'] ?: 'Semua' }} | 
        Status: {{ $filter['status'] ? strtoupper($filter['status']) : 'Semua' }} | 
        Total Data: {{ count($data) }} Mahasiswa
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">No. Daftar</th>
                <th width="20%">Nama Lengkap</th>
                <th width="10%">NISN</th>
                <th width="15%">Asal Sekolah</th>
                <th width="15%">Prodi Pilihan</th>
                <th width="10%">Nilai Ujian</th>
                <th width="15%">Status Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $p)
            <tr>
                <td style="text-align:center;">{{ $key + 1 }}</td>
                <td>REG-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ strtoupper($p->user->name) }}</td>
                <td>{{ $p->nisn ?? '-' }}</td>
                <td>{{ $p->asal_sekolah }}</td>
                <td>{{ $p->pilihan_prodi_1 }}</td>
                <td style="text-align:center;">{{ $p->nilai_ujian > 0 ? $p->nilai_ujian : '-' }}</td>
                <td style="text-align:center; font-weight:bold;">{{ strtoupper($p->status_pendaftaran) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i') }}</p>
    </div>
</body>
</html>