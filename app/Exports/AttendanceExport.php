<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromQuery, WithHeadings, WithMapping
{
    protected $id_pkl_places;

    public function __construct($id_pkl_places)
    {
        $this->id_pkl_places = $id_pkl_places;
    }

    public function query()
    {
        return Attendance::with(['user', 'pklPlace'])
            ->where('pkl_places', $this->id_pkl_places)
            ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Nama Siswa', 'Tanggal', 'Masuk', 'Pulang', 'Keterangan', 'Tempat PKL'
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->user->nama ?? '-', // Use '-' if user name is null
            $attendance->date ?? '-', // Use '-' if date is null
            $attendance->check_in ?? '-', // Use '-' if check-in time is null
            $attendance->check_out ?? '-', // Use '-' if check-out time is null
            $attendance->status === 0 ? 'masuk' : ($attendance->status === 1 ? 'izin' : '-'), // Conditional status text
            $attendance->pklPlace->nama_perusahaan ?? '-' // Use '-' if pklPlace name is null
        ];
    }
}
