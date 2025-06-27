<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $judul }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .keterangan {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .keterangan h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .keterangan ul {
            margin: 0;
            padding-left: 20px;
        }
        .keterangan li {
            margin-bottom: 5px;
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
    </div>

    <div class="info">
        <p><strong>Total Transaksi:</strong> {{ count($data) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="10%">Kode Barang</th>
                <th width="20%">Nama Barang</th>
                <th width="15%">Peminjam</th>
                <th width="10%" class="text-center">Jumlah</th>
                <th width="12%">Tgl Pinjam</th>
                <th width="12%">Tgl Kembali</th>
                <th width="16%" class="text-center">Status</th>
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
                    <td>{{ $item->tanggal_pinjam }}</td>
                    <td>{{ $item->tanggal_kembali }}</td>
                    <td class="text-center">{{ ucfirst($item->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <div style="float: right; text-align: center;">
            <p>Mengetahui,</p>
            <br><br><br>
            <p>_____________________</p>
            <p>Admin</p>
        </div>
    </div>
</body>
</html>