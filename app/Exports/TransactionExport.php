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

class TransactionExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithMapping
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

    public function map($transaksi): array
    {
        return [
            '', // No akan diisi otomatis
            $transaksi->kode_transaksi ?? '-',
            $transaksi->nama_barang ?? 'Barang Dihapus',
            $transaksi->peminjam ?? 'Pengguna Dihapus',
            $transaksi->jumlah ?? 1,
            $this->formatDate($transaksi->tanggal_pinjam),
            $transaksi->tanggal_kembali ? $this->formatDate($transaksi->tanggal_kembali) : 'Belum Kembali',
            $this->translateStatus($transaksi->status),
            $transaksi->keperluan ?? '-',
            $transaksi->catatan ?? '-'
        ];
    }

    public function headings(): array
    {
        return [
            ['LAPORAN TRANSAKSI PEMINJAMAN'],
            ['Periode: ' . ($this->dateFrom ?? 'Semua Data') . ' - ' . ($this->dateTo ?? date('d/m/Y'))],
            [], // Baris kosong
            [
                'No',
                'Kode Transaksi',
                'Nama Barang',
                'Peminjam',
                'Jumlah',
                'Tanggal Pinjam',
                'Tanggal Kembali',
                'Status',
                'Keperluan',
                'Catatan'
            ]
        ];
    }

    public function title(): string
    {
        return 'Transaksi';
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
            // Format tanggal
            'F:G' => [
                'numberFormat' => ['formatCode' => 'dd/mm/yyyy']
            ],
            // Auto numbering
            'A5:A'.$totalRows => [
                'alignment' => ['horizontal' => 'center']
            ],
            // Warna status
            'H5:H'.$totalRows => [
                'font' => ['color' => ['rgb' => $this->getStatusColor($this->data->first()->status ?? '')]]
            ],
            // Border untuk seluruh data
            'A4:J'.$totalRows => [
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
            'B' => 18,  // Kode Transaksi
            'C' => 25,  // Nama Barang
            'D' => 20,  // Peminjam
            'E' => 10,  // Jumlah
            'F' => 15,  // Tanggal Pinjam
            'G' => 15,  // Tanggal Kembali
            'H' => 12,  // Status
            'I' => 30,  // Keperluan
            'J' => 30   // Catatan
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
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'dipinjam' => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            'terlambat' => 'Terlambat',
            'borrowed' => 'Dipinjam',
            'returned' => 'Dikembalikan',
            'overdue' => 'Terlambat'
        ];

        return $statusMap[strtolower($status)] ?? ucfirst($status);
    }

    private function getStatusColor($status)
    {
        $colorMap = [
            'pending' => 'f39c12', // Orange
            'approved' => '3498db', // Blue
            'dipinjam' => '3498db', // Blue
            'dikembalikan' => '2ecc71', // Green
            'terlambat' => 'e74c3c', // Red
            'rejected' => 'e74c3c' // Red
        ];

        return $colorMap[strtolower($status)] ?? '000000'; // Default black
    }
}