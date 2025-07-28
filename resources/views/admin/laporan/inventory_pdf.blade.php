<!-- resources/views/admin/laporan/template_pdf.blade.php -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #333;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 10px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px 5px;
            font-size: 10px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 6px 4px;
            text-align: left;
            font-size: 9px;
        }
        .data-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
            text-align: center;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .kondisi-baik {
            color: #10b981;
            font-weight: bold;
        }
        .kondisi-rusak-ringan {
            color: #f59e0b;
            font-weight: bold;
        }
        .kondisi-rusak-berat {
            color: #ef4444;
            font-weight: bold;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $judul }}</h1>
        <p>Tanggal Cetak: {{ $tanggal }}</p>
        @if($date_from && $date_to)
            <p>Periode: {{ date('d/m/Y', strtotime($date_from)) }} - {{ date('d/m/Y', strtotime($date_to)) }}</p>
        @endif
        @if($filters['category'] || $filters['room'])
            <p>
                @if($filters['category'])
                    Kategori: {{ $filters['category'] }}
                @endif
                @if($filters['category'] && $filters['room']) | @endif
                @if($filters['room'])
                    Ruangan: {{ $filters['room'] }}
                @endif
            </p>
        @endif
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 120px;"><strong>Total Item:</strong></td>
            <td>{{ $data->count() }} item</td>
            <td style="width: 120px;"><strong>Item Baik:</strong></td>
            <td>{{ $data->where('kondisi', 'baik')->count() }} item</td>
        </tr>
        <tr>
            <td><strong>Item Rusak Ringan:</strong></td>
            <td>{{ $data->where('kondisi', 'rusak_ringan')->count() }} item</td>
            <td><strong>Item Rusak Berat:</strong></td>
            <td>{{ $data->where('kondisi', 'rusak_berat')->count() }} item</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">Kode Barang</th>
                <th style="width: 12%;">Kode Sub Barang</th>
                <th style="width: 22%;">Nama Barang</th>
                <th style="width: 12%;">Kategori</th>
                <th style="width: 12%;">Ruangan</th>
                <th style="width: 8%;">Kondisi</th>
                <th style="width: 8%;">Tahun</th>
                <th style="width: 10%;">Sumber Dana</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->kode_barang }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->barang->kategori->nama ?? '-' }}</td>
                    <td>{{ $item->barang->ruangan->nama_ruangan ?? '-' }}</td>
                    <td class="text-center">
                        @if($item->kondisi == 'baik')
                            <span class="kondisi-baik">Baik</span>
                        @elseif($item->kondisi == 'rusak_ringan')
                            <span class="kondisi-rusak-ringan">Rusak Ringan</span>
                        @else
                            <span class="kondisi-rusak-berat">Rusak Berat</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->tahun_perolehan }}</td>
                    <td>{{ $item->sumber_dana ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data inventaris yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3 style="margin: 0 0 10px 0; font-size: 12px;">Ringkasan Inventaris</h3>
        <table style="width: 100%; font-size: 10px;">
            <tr>
                <td style="width: 25%;">Total Item:</td>
                <td style="width: 25%;"><strong>{{ $data->count() }} item</strong></td>
                <td style="width: 25%;">Total Barang Types:</td>
                <td style="width: 25%;"><strong>{{ $data->groupBy('barang_id')->count() }} jenis</strong></td>
            </tr>
            <tr>
                <td>Kondisi Baik:</td>
                <td><strong>{{ $data->where('kondisi', 'baik')->count() }} item</strong></td>
                <td>Kondisi Rusak Ringan:</td>
                <td><strong>{{ $data->where('kondisi', 'rusak_ringan')->count() }} item</strong></td>
            </tr>
            <tr>
                <td>Kondisi Rusak Berat:</td>
                <td><strong>{{ $data->where('kondisi', 'rusak_berat')->count() }} item</strong></td>
                <td>Persentase Kondisi Baik:</td>
                <td><strong>{{ $data->count() > 0 ? round(($data->where('kondisi', 'baik')->count() / $data->count()) * 100, 1) : 0 }}%</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem Inventaris Barang Sekolah</p>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>
</body>
</html>