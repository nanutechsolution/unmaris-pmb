<?php

namespace App\Exports;

use App\Models\Pendaftar;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReferralDetailExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithCustomStartCell, WithEvents
{
    protected $nama_referensi;
    protected $nomor_hp_referensi;

    public function __construct($nama_referensi, $nomor_hp_referensi = null)
    {
        $this->nama_referensi = $nama_referensi;
        $this->nomor_hp_referensi = $nomor_hp_referensi;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function query()
    {
        return Pendaftar::with('user')
            ->where('nama_referensi', $this->nama_referensi)
            // UPDATED: Logika Pencocokan HP Lebih Ketat
            ->where(function($q) {
                if ($this->nomor_hp_referensi) {
                    // Jika ada HP, cari yang persis sama
                    $q->where('nomor_hp_referensi', $this->nomor_hp_referensi);
                } else {
                    // Jika HP kosong, HANYA cari yang kosong juga (null atau string kosong)
                    $q->whereNull('nomor_hp_referensi')
                      ->orWhere('nomor_hp_referensi', '');
                }
            })
            ->orderBy('created_at', 'desc');
    }

    public function map($pendaftar): array
    {
        return [
            strtoupper($pendaftar->user->name),
            $pendaftar->user->nomor_hp ? "'".$pendaftar->user->nomor_hp : '-',
            $pendaftar->pilihan_prodi_1,
            strtoupper($pendaftar->status_pendaftaran),
            $pendaftar->created_at->format('d/m/Y H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'NAMA CAMABA',
            'NO HP CAMABA',
            'PRODI PILIHAN',
            'STATUS SAAT INI',
            'TANGGAL DAFTAR',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', 'LAPORAN DETAIL REKRUTAN');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FF1E3A8A');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A2:E2');
                $sheet->setCellValue('A2', 'Perekomendasi: ' . strtoupper($this->nama_referensi) . ($this->nomor_hp_referensi ? " ({$this->nomor_hp_referensi})" : ''));
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $sheet->mergeCells('A3:E3');
                $sheet->setCellValue('A3', 'Dicetak pada: ' . date('d F Y, H:i'));
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A5:E5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A5:E'.$lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCCCCCC']]],
        ]);

        return [];
    }
}