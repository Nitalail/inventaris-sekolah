<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $judul;
    protected $reportType;

    public function __construct($data, $judul, $reportType)
    {
        $this->data = $data;
        $this->judul = $judul;
        $this->reportType = $reportType;
    }

    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return substr($this->judul, 0, 31);
    }

    public function headings(): array
    {
        switch ($this->reportType) {
            case 'inventory':
                return [
                    'No', 'Kode Barang', 'Kode Item', 'Nama Barang', 'Kategori', 'Ruangan',
                    'Kondisi', 'Tahun Perolehan', 'Sumber Dana'
                ];
            case 'transaction':
                return [
                    'No', 'Kode Barang', 'Nama Barang', 'Peminjam', 'Jumlah',
                    'Tanggal Pinjam', 'Tanggal Kembali', 'Status', 'Keperluan', 'Kode Item'
                ];
            case 'room':
                return [
                    'No', 'Kode Ruangan', 'Nama Ruangan', 'Total Item', 'Item Baik',
                    'Item Rusak Ringan', 'Item Rusak Berat', 'Jenis Barang', 'Status', 'Kategori'
                ];
            default:
                return ['No', 'Data'];
        }
    }

    public function map($item): array
    {
        static $number = 1;
        
        switch ($this->reportType) {
            case 'inventory':
                return [
                    $number++,
                    $item->kode_barang,
                    $item->kode,
                    $item->nama_barang,
                    $item->barang->kategori->nama ?? '-',
                    $item->barang->ruangan->nama_ruangan ?? '-',
                    $this->formatKondisi($item->kondisi),
                    $item->tahun_perolehan,
                    $item->sumber_dana ?? '-'
                ];
            case 'transaction':
                return [
                    $number++,
                    $item->kode_barang,
                    $item->nama_barang,
                    $item->peminjam,
                    $item->jumlah,
                    $item->tanggal_pinjam,
                    $item->tanggal_kembali,
                    $this->formatStatus($item->status),
                    $item->keperluan ?? '-',
                    $item->sub_barang_codes ?? '-'
                ];
            case 'room':
                return [
                    $number++,
                    $item->kode_ruangan,
                    $item->nama_ruangan,
                    $item->total_sub_barang,
                    $item->barang_baik,
                    $item->barang_rusak_ringan,
                    $item->barang_rusak_berat,
                    $item->total_barang_types . ' jenis',
                    $this->formatStatusRuangan($item->status),
                    $item->kategori_list
                ];
            default:
                return [$number++, json_encode($item)];
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style header
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '4472C4']
                ]
            ],
            // Auto width untuk semua kolom
            'A:Z' => [
                'alignment' => [
                    'horizontal' => 'left',
                    'vertical' => 'center'
                ]
            ]
        ];
    }

    protected function formatKondisi($kondisi)
    {
        $status = [
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat'
        ];
        
        return $status[$kondisi] ?? $kondisi;
    }

    protected function formatStatus($status)
    {
        $statusMap = [
            'pending' => 'Pending',
            'dipinjam' => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            'terlambat' => 'Terlambat',
            'rusak' => 'Rusak'
        ];
        
        return $statusMap[$status] ?? ucfirst($status);
    }

    protected function formatStatusRuangan($status)
    {
        $statusMap = [
            'aktif' => 'Aktif',
            'perbaikan' => 'Perbaikan',
            'tidak_aktif' => 'Tidak Aktif'
        ];
        
        return $statusMap[$status] ?? ucfirst($status);
    }
}