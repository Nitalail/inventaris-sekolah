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
        @if($filters['room'])
            <p>Ruangan: {{ $filters['room'] }}</p>
        @endif
        @if($filters['status'])
            <p>Status: {{ ucfirst($filters['status']) }}</p>
        @endif
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 120px;"><strong>Total Ruangan:</strong></td>
            <td>{{ $data->count() }} ruangan</td>
            <td style="width: 120px;"><strong>Total Item:</strong></td>
            <td>{{ $data->sum('total_sub_barang') }} item</td>
        </tr>
        <tr>
            <td><strong>Ruangan Aktif:</strong></td>
            <td>{{ $data->where('status', 'aktif')->count() }} ruangan</td>
            <td><strong>Total Jenis Barang:</strong></td>
            <td>{{ $data->sum('total_barang_types') }} jenis</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">Kode Ruangan</th>
                <th style="width: 20%;">Nama Ruangan</th>
                <th style="width: 10%;">Total Item</th>
                <th style="width: 8%;">Item Baik</th>
                <th style="width: 8%;">Rusak Ringan</th>
                <th style="width: 8%;">Rusak Berat</th>
                <th style="width: 10%;">Jenis Barang</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 9%;">Kategori</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $room)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $room->kode_ruangan }}</td>
                    <td>{{ $room->nama_ruangan }}</td>
                    <td class="text-center">{{ $room->total_sub_barang }}</td>
                    <td class="text-center">{{ $room->barang_baik }}</td>
                    <td class="text-center">{{ $room->barang_rusak_ringan }}</td>
                    <td class="text-center">{{ $room->barang_rusak_berat }}</td>
                    <td class="text-center" title="{{ $room->jenis_barang_list }}">{{ $room->total_barang_types }} jenis</td>
                    <td class="text-center">
                        @if($room->status == 'aktif')
                            <span class="status-aktif">Aktif</span>
                        @elseif($room->status == 'perbaikan')
                            <span class="status-perbaikan">Perbaikan</span>
                        @else
                            <span class="status-tidak_aktif">Tidak Aktif</span>
                        @endif
                    </td>
                    <td style="font-size: 8px;">{{ $room->kategori_list }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data ruangan yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 10px; font-size: 8px; color: #666; font-style: italic;">
        *Jenis Barang = Jumlah tipe/kategori barang yang berbeda di ruangan
    </div>

    <div class="summary">
        <h3 style="margin: 0 0 10px 0; font-size: 12px;">Ringkasan Inventaris Per Ruangan</h3>
        <table style="width: 100%; font-size: 10px;">
            <tr>
                <td style="width: 25%;">Total Ruangan:</td>
                <td style="width: 25%;"><strong>{{ $data->count() }} ruangan</strong></td>
                <td style="width: 25%;">Total Item:</td>
                <td style="width: 25%;"><strong>{{ $data->sum('total_sub_barang') }} item</strong></td>
            </tr>
            <tr>
                <td>Ruangan Aktif:</td>
                <td><strong>{{ $data->where('status', 'aktif')->count() }} ruangan</strong></td>
                <td>Ruangan Perbaikan:</td>
                <td><strong>{{ $data->where('status', 'perbaikan')->count() }} ruangan</strong></td>
            </tr>
            <tr>
                <td>Ruangan Tidak Aktif:</td>
                <td><strong>{{ $data->where('status', 'tidak_aktif')->count() }} ruangan</strong></td>
                <td>Rata-rata Item per Ruangan:</td>
                <td><strong>{{ $data->count() > 0 ? round($data->sum('total_sub_barang') / $data->count(), 1) : 0 }} item</strong></td>
            </tr>
            <tr>
                <td>Total Item Kondisi Baik:</td>
                <td><strong>{{ $data->sum('barang_baik') }} item</strong></td>
                <td>Total Item Rusak:</td>
                <td><strong>{{ $data->sum('barang_rusak_ringan') + $data->sum('barang_rusak_berat') }} item</strong></td>
            </tr>
        </table>
    </div>

    <!-- Detail per ruangan jika ada filter ruangan spesifik -->
    @if($filters['room'] && $data->count() == 1)
        @php $room = $data->first(); @endphp
        <div style="margin-top: 20px; page-break-inside: avoid;">
            <h3 style="margin: 0 0 10px 0; font-size: 12px;">Detail Barang di {{ $room->nama_ruangan }}</h3>
            @if($room->barangs->count() > 0)
                <table class="data-table" style="font-size: 8px;">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">Kode Barang</th>
                            <th style="width: 25%;">Nama Barang</th>
                            <th style="width: 15%;">Kategori</th>
                            <th style="width: 10%;">Total Item</th>
                            <th style="width: 8%;">Baik</th>
                            <th style="width: 8%;">Rusak Ringan</th>
                            <th style="width: 8%;">Rusak Berat</th>
                            <th style="width: 6%;">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($room->barangs as $barang)
                            @php
                                $baik = $barang->subBarang->where('kondisi', 'baik')->count();
                                $rusakRingan = $barang->subBarang->where('kondisi', 'rusak_ringan')->count();
                                $rusakBerat = $barang->subBarang->where('kondisi', 'rusak_berat')->count();
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $barang->kode }}</td>
                                <td>{{ $barang->nama }}</td>
                                <td>{{ $barang->kategori->nama ?? '-' }}</td>
                                <td class="text-center">{{ $barang->subBarang->count() }}</td>
                                <td class="text-center">{{ $baik }}</td>
                                <td class="text-center">{{ $rusakRingan }}</td>
                                <td class="text-center">{{ $rusakBerat }}</td>
                                <td class="text-center">{{ $barang->satuan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align: center; color: #666; font-style: italic;">Belum ada barang di ruangan ini.</p>
            @endif
        </div>
    @endif

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem Inventaris Barang Sekolah</p>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>
</body>
</html>