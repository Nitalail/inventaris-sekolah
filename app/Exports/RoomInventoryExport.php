<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class RoomInventoryExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithMapping
{
    protected $data;
    protected $title;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($data, $title, $dateFrom = null, $dateTo = null)
    {
        $this->data = $data;
        $this->title = $title;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($room): array
    {
        return [
            '', // No akan diisi otomatis
            $room->kode_ruangan ?? '-',
            $room->nama_ruangan ?? '-',
            $room->kapasitas ?? 0,
            $room->total_barang_aktual ?? 0,
            $room->persentase_kapasitas . '%',
            $room->barang_baik ?? 0,
            $room->barang_rusak ?? 0,
            $room->barang_hilang ?? 0,
            $this->translateStatus($room->status),
            $room->kategori_list ? str_replace(',', ', ', $room->kategori_list) : '-',
            $room->deskripsi ?? '-'
        ];
    }

    public function headings(): array
    {
        return [
            ['LAPORAN INVENTARIS RUANGAN'],
            ['Periode: ' . ($this->dateFrom ? $this->formatDate($this->dateFrom) : 'Semua Data') . ' - ' . ($this->dateTo ? $this->formatDate($this->dateTo) : date('d/m/Y'))],
            [], // Baris kosong
            [
                'No',
                'Kode Ruangan',
                'Nama Ruangan',
                'Kapasitas',
                'Jumlah Barang',
                '% Kapasitas',
                'Barang Baik',
                'Barang Rusak',
                'Barang Hilang',
                'Status',
                'Kategori Barang',
                'Deskripsi'
            ]
        ];
    }

    public function title(): string
    {
        return 'Inventaris Ruangan';
    }

    public function styles(Worksheet $sheet)
    {
        // Hitung total baris data + header
        $totalRows = count($this->data) + 4;

        return [
            // Style untuk judul laporan
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center']
            ],
            // Style untuk periode
            2 => [
                'font' => ['italic' => true],
                'alignment' => ['horizontal' => 'center']
            ],
            // Style untuk header kolom
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '3498db']]
            ],
            // Auto numbering
            'A5:A'.$totalRows => [
                'alignment' => ['horizontal' => 'center']
            ],
            // Center alignment untuk kolom angka
            'D5:I'.$totalRows => [
                'alignment' => ['horizontal' => 'center']
            ],
            // Warna status
            'J5:J'.$totalRows => [
                'font' => ['color' => ['rgb' => $this->getStatusColor($this->data->first()->status ?? '')]]
            ],
            // Border untuk seluruh data
            'A4:L'.$totalRows => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 15,  // Kode Ruangan
            'C' => 25,  // Nama Ruangan
            'D' => 12,  // Kapasitas
            'E' => 15,  // Jumlah Barang
            'F' => 12,  // % Kapasitas
            'G' => 12,  // Barang Baik
            'H' => 12,  // Barang Rusak
            'I' => 12,  // Barang Hilang
            'J' => 15,  // Status
            'K' => 30,  // Kategori Barang
            'L' => 35   // Deskripsi
        ];
    }

    private function formatDate($date)
    {
        if (!$date) return '-';
        
        try {
            return Carbon::parse($date)->format('d/m/Y');
        } catch (\Exception $e) {
            return $date;
        }
    }

    private function translateStatus($status)
    {
        $statusMap = [
            'aktif' => 'Aktif',
            'perbaikan' => 'Perbaikan',
            'tidak_aktif' => 'Tidak Aktif',
            'maintenance' => 'Maintenance',
            'reserved' => 'Reserved'
        ];

        return $statusMap[strtolower($status)] ?? ucfirst($status);
    }

    private function getStatusColor($status)
    {
        $colorMap = [
            'aktif' => '2ecc71', // Green
            'perbaikan' => 'f39c12', // Orange
            'tidak_aktif' => 'e74c3c', // Red
            'maintenance' => 'f39c12', // Orange
            'reserved' => '3498db' // Blue
        ];

        return $colorMap[strtolower($status)] ?? '000000'; // Default black
    }
}