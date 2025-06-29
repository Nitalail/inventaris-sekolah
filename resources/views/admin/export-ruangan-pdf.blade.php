<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Ruangan - Inventaris Barang Sekolah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-aktif {
            color: #10b981;
            font-weight: bold;
        }
        .status-perbaikan {
            color: #f59e0b;
            font-weight: bold;
        }
        .status-tidak_aktif {
            color: #ef4444;
            font-weight: bold;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #6366f1;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA RUANGAN</h1>
        <p>Inventaris Barang Sekolah</p>
        <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 150px;"><strong>Total Ruangan:</strong></td>
            <td>{{ $rooms->count() }} ruangan</td>
        </tr>
        <tr>
            <td><strong>Total Item:</strong></td>
            <td>{{ $rooms->sum('total_sub_barang') }} item</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Kode</th>
                <th style="width: 30%;">Nama Ruangan</th>
                <th style="width: 15%;">Jumlah Item</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 20%;">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rooms as $room)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $room->kode_ruangan }}</td>
                <td>{{ $room->nama_ruangan }}</td>
                <td>{{ $room->total_sub_barang ?? 0 }} item</td>
                <td>
                    @if ($room->status === 'aktif')
                        <span class="status-aktif">Aktif</span>
                    @elseif ($room->status === 'perbaikan')
                        <span class="status-perbaikan">Perbaikan</span>
                    @else
                        <span class="status-tidak_aktif">Tidak Aktif</span>
                    @endif
                </td>
                <td>{{ $room->deskripsi ?: '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #666;">Tidak ada data ruangan yang ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3 style="margin: 0 0 10px 0;">Ringkasan Status Ruangan</h3>
        <table style="width: 100%;">
            <tr>
                <td>Ruangan Aktif:</td>
                <td><strong>{{ $rooms->where('status', 'aktif')->count() }} ruangan</strong></td>
            </tr>
            <tr>
                <td>Ruangan dalam Perbaikan:</td>
                <td><strong>{{ $rooms->where('status', 'perbaikan')->count() }} ruangan</strong></td>
            </tr>
            <tr>
                <td>Ruangan Tidak Aktif:</td>
                <td><strong>{{ $rooms->where('status', 'tidak_aktif')->count() }} ruangan</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem Inventaris Barang Sekolah</p>
    </div>
</body>
</html> 