<?php

namespace App\Exports;

use App\Models\Pendaftar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PendaftarExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Pendaftar::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'No. Pendaftaran',
            'Nama Lengkap',
            'Email',
            'Nomor HP',
            'Jalur',
            'NISN',
            'Asal Sekolah',
            'Pilihan Prodi 1',
            'Pilihan Prodi 2',
            'Status',
            'Tanggal Daftar',
        ];
    }

    public function map($pendaftar): array
    {
        return [
            'REG-' . str_pad($pendaftar->id, 5, '0', STR_PAD_LEFT),
            $pendaftar->user->name,
            $pendaftar->user->email,
            $pendaftar->user->nomor_hp,
            ucfirst($pendaftar->jalur_pendaftaran),
            $pendaftar->nisn ?? '-',
            $pendaftar->asal_sekolah,
            $pendaftar->pilihan_prodi_1,
            $pendaftar->pilihan_prodi_2 ?? '-',
            strtoupper($pendaftar->status_pendaftaran),
            $pendaftar->created_at->format('d-m-Y H:i'),
        ];
    }
}