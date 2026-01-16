<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserFilterExport implements FromQuery, WithHeadings, WithMapping
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->nomor_hp,
            $user->pendaftar ? 'Sudah Isi' : 'Belum Isi',
            $user->created_at->format('d-m-Y H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Email',
            'Nomor HP (WA)',
            'Status Pendaftaran',
            'Tanggal Daftar Akun',
        ];
    }
}