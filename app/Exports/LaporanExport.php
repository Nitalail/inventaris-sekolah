<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected $data;
    protected $judul;

    public function __construct($data, $judul)
    {
        $this->data = $data;
        $this->judul = $judul;
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
        if (str_contains($this->judul, 'Inventaris Barang')) {
            return [
                'No', 'Kode Barang', 'Nama Barang', 'Kategori', 'Ruangan',
                'Jumlah', 'Kondisi', 'Tahun Perolehan', 'Sumber Dana'
            ];
        }
        // Tambahkan heading untuk jenis laporan lainnya
        return ['No', 'Data'];
    }

    public function map($item): array
    {
        static $number = 1;
        
        if (str_contains($this->judul, 'Inventaris Barang')) {
            return [
                $number++,
                $item->kode,
                $item->nama,
                $item->kategori->nama ?? '-',
                $item->ruangan->nama_ruangan ?? '-',
                $item->jumlah,
                $this->formatKondisi($item->kondisi),
                $item->tahun_perolehan,
                $item->sumber_dana ?? '-'
            ];
        }
        // Tambahkan mapping untuk jenis laporan lainnya
        return [$number++, json_encode($item)];
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
}