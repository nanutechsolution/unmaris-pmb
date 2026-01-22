<?php

namespace App\Exports;

use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithDrawings; // Tambahan untuk Logo
use Maatwebsite\Excel\Concerns\WithCustomStartCell; // Tambahan untuk geser tabel ke bawah
use Maatwebsite\Excel\Concerns\WithEvents; // Tambahan untuk Event (Header Custom)
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReferralExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting, WithDrawings, WithCustomStartCell, WithEvents
{
    protected $search;
    protected $filterSumber;
    protected $rewardPerSiswa;

    public function __construct($search, $filterSumber, $rewardPerSiswa)
    {
        $this->search = $search;
        $this->filterSumber = $filterSumber;
        $this->rewardPerSiswa = $rewardPerSiswa;
    }

    // Mulai tabel dari baris ke-7 (Kasih ruang buat Kop Surat)
    public function startCell(): string
    {
        return 'A7';
    }

    public function query()
    {
        return Pendaftar::select(
                'nama_referensi', 
                'nomor_hp_referensi', 
                'sumber_informasi', 
                DB::raw('count(*) as total_rekrut')
            )
            ->whereNotNull('nama_referensi')
            ->where('nama_referensi', '!=', '')
            ->where(function($q) {
                $q->where('nama_referensi', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_hp_referensi', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterSumber, function($q) {
                $q->where('sumber_informasi', $this->filterSumber);
            })
            ->groupBy('nama_referensi', 'nomor_hp_referensi', 'sumber_informasi')
            ->orderByDesc('total_rekrut');
    }

    public function map($row): array
    {
        return [
            strtoupper($row->nama_referensi),
            $row->nomor_hp_referensi ? "'".$row->nomor_hp_referensi : '-',
            strtoupper($row->sumber_informasi),
            $row->total_rekrut,
            $row->total_rekrut * $this->rewardPerSiswa,
        ];
    }

    public function headings(): array
    {
        return [
            'NAMA PEMBERI REFERENSI',
            'NOMOR HP / WA',
            'STATUS / SUMBER',
            'TOTAL REKRUT (ORG)',
            'ESTIMASI REWARD (Rp)',
        ];
    }

    // --- TAMBAHAN: LOGO ---
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo Kampus');
        $drawing->setPath(public_path('images/logo.png')); // Pastikan file ada di public/images/logo.png
        $drawing->setHeight(60);
        $drawing->setCoordinates('B2'); // Posisi Logo
        $drawing->setOffsetX(10);
        
        return $drawing;
    }

    // --- TAMBAHAN: CUSTOM HEADER MANUAL ---
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                // Judul Besar (KOP SURAT)
                $sheet->mergeCells('C2:E2');
                $sheet->setCellValue('C2', 'UNIVERSITAS STELLA MARIS SUMBA');
                $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FF1E3A8A'); // Biru

                // Alamat / Sub-judul
                $sheet->mergeCells('C3:E3');
                $sheet->setCellValue('C3', 'Laporan Data Referral & Prospek PMB');
                $sheet->getStyle('C3')->getFont()->setSize(10)->setItalic(true);

                // Tanggal Cetak
                $sheet->mergeCells('C4:E4');
                $sheet->setCellValue('C4', 'Tanggal Cetak: ' . date('d F Y'));
                $sheet->getStyle('C4')->getFont()->setSize(9);
            },
        ];
    }

    // --- STYLING TABEL KEREN ---
    public function styles(Worksheet $sheet)
    {
        // Header Tabel (Baris 7) -> Bold, Putih, Background Biru
        $sheet->getStyle('A7:E7')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']], // Unmaris Blue
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]],
        ]);
        
        $sheet->getRowDimension(7)->setRowHeight(30);

        // Border untuk seluruh data (Mulai dari A7 sampai akhir)
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A7:E'.$lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCCCCCC']]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);
        
        // Center Kolom C & D
        $sheet->getStyle('C8:D'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Bold Kolom Nama (A)
        $sheet->getStyle('A8:A'.$lastRow)->getFont()->setBold(true);
    }

    public function columnFormats(): array
    {
        return [
            'E' => '#,##0', // Format Rupiah
        ];
    }
}