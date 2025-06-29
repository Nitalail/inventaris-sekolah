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
        .status-pending {
            color: #f59e0b;
            font-weight: bold;
        }
        .status-dipinjam {
            color: #3b82f6;
            font-weight: bold;
        }
        .status-dikembalikan {
            color: #10b981;
            font-weight: bold;
        }
        .status-terlambat {
            color: #ef4444;
            font-weight: bold;
        }
        .status-rusak {
            color: #dc2626;
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
        .sub-barang-codes {
            font-size: 8px;
            color: #666;
            font-style: italic;
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
        @if($filters['room'])
            <p>Ruangan: {{ $filters['room'] }}</p>
        @endif
        @if($filters['status'])
            <p>Status: {{ ucfirst($filters['status']) }}</p>
        @endif
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 120px;"><strong>Total Transaksi:</strong></td>
            <td>{{ $data->count() }} transaksi</td>
            <td style="width: 120px;"><strong>Total Item Dipinjam:</strong></td>
            <td>{{ $data->sum('jumlah') }} item</td>
        </tr>
        <tr>
            <td><strong>Status Pending:</strong></td>
            <td>{{ $data->where('status', 'pending')->count() }} transaksi</td>
            <td><strong>Status Dipinjam:</strong></td>
            <td>{{ $data->where('status', 'dipinjam')->count() }} transaksi</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">Kode Barang</th>
                <th style="width: 18%;">Nama Barang</th>
                <th style="width: 15%;">Peminjam</th>
                <th style="width: 6%;">Jumlah</th>
                <th style="width: 9%;">Tgl Pinjam</th>
                <th style="width: 9%;">Tgl Kembali</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 19%;">Kode Item</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->kode_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->peminjam }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td class="text-center">{{ $item->tanggal_pinjam }}</td>
                    <td class="text-center">{{ $item->tanggal_kembali }}</td>
                    <td class="text-center">
                        @if($item->status == 'pending')
                            <span class="status-pending">Pending</span>
                        @elseif($item->status == 'dipinjam')
                            <span class="status-dipinjam">Dipinjam</span>
                        @elseif($item->status == 'dikembalikan')
                            <span class="status-dikembalikan">Dikembalikan</span>
                        @elseif($item->status == 'terlambat')
                            <span class="status-terlambat">Terlambat</span>
                        @else
                            <span class="status-rusak">Rusak</span>
                        @endif
                    </td>
                    <td class="sub-barang-codes">{{ $item->sub_barang_codes ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data transaksi yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3 style="margin: 0 0 10px 0; font-size: 12px;">Ringkasan Transaksi Peminjaman</h3>
        <table style="width: 100%; font-size: 10px;">
            <tr>
                <td style="width: 25%;">Total Transaksi:</td>
                <td style="width: 25%;"><strong>{{ $data->count() }} transaksi</strong></td>
                <td style="width: 25%;">Total Item Dipinjam:</td>
                <td style="width: 25%;"><strong>{{ $data->sum('jumlah') }} item</strong></td>
            </tr>
            <tr>
                <td>Status Pending:</td>
                <td><strong>{{ $data->where('status', 'pending')->count() }} transaksi</strong></td>
                <td>Status Dipinjam:</td>
                <td><strong>{{ $data->where('status', 'dipinjam')->count() }} transaksi</strong></td>
            </tr>
            <tr>
                <td>Status Dikembalikan:</td>
                <td><strong>{{ $data->where('status', 'dikembalikan')->count() }} transaksi</strong></td>
                <td>Status Terlambat:</td>
                <td><strong>{{ $data->where('status', 'terlambat')->count() }} transaksi</strong></td>
            </tr>
            <tr>
                <td>Rata-rata Item per Transaksi:</td>
                <td><strong>{{ $data->count() > 0 ? round($data->sum('jumlah') / $data->count(), 1) : 0 }} item</strong></td>
                <td>Peminjam Unik:</td>
                <td><strong>{{ $data->pluck('peminjam')->unique()->count() }} orang</strong></td>
            </tr>
        </table>
    </div>

    <!-- Daftar item yang sedang dipinjam -->
    @if($data->whereIn('status', ['pending', 'dipinjam'])->count() > 0)
        <div style="margin-top: 20px; page-break-inside: avoid;">
            <h3 style="margin: 0 0 10px 0; font-size: 12px;">Item yang Sedang Dipinjam</h3>
            <table class="data-table" style="font-size: 8px;">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 20%;">Nama Barang</th>
                        <th style="width: 20%;">Peminjam</th>
                        <th style="width: 10%;">Jumlah</th>
                        <th style="width: 12%;">Tgl Pinjam</th>
                        <th style="width: 12%;">Tgl Kembali</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 11%;">Hari Tersisa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data->whereIn('status', ['pending', 'dipinjam']) as $item)
                        @php
                            $tanggalKembali = \Carbon\Carbon::createFromFormat('d/m/Y', $item->tanggal_kembali);
                            $hariTersisa = now()->diffInDays($tanggalKembali, false);
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->peminjam }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-center">{{ $item->tanggal_pinjam }}</td>
                            <td class="text-center">{{ $item->tanggal_kembali }}</td>
                            <td class="text-center">{{ ucfirst($item->status) }}</td>
                            <td class="text-center" style="color: {{ $hariTersisa < 0 ? '#ef4444' : ($hariTersisa <= 3 ? '#f59e0b' : '#10b981') }};">
                                {{ $hariTersisa >= 0 ? $hariTersisa . ' hari' : abs($hariTersisa) . ' hari terlambat' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem Inventaris Barang Sekolah</p>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>
</body>
</html>