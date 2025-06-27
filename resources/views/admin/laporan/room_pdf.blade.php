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
        <p><strong>Total Ruangan:</strong> {{ count($data) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="12%">Kode Ruangan</th>
                <th width="20%">Nama Ruangan</th>
                <th width="8%" class="text-center">Kapasitas</th>
                <th width="8%" class="text-center">Jumlah Barang</th>
                <th width="10%" class="text-center">% Kapasitas</th>
                <th width="8%" class="text-center">Barang Baik</th>
                <th width="8%" class="text-center">Barang Rusak</th>
                <th width="8%" class="text-center">Barang Hilang</th>
                <th width="13%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $ruangan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $ruangan->kode_ruangan }}</td>
                <td>{{ $ruangan->nama_ruangan }}</td>
                <td>{{ $ruangan->kapasitas }}</td>
                <td>{{ $ruangan->barangs->count() }}</td> <!-- Gunakan barangs -->
                <td>
                    @if($ruangan->kapasitas > 0)
                        {{ round(($ruangan->barangs->count() / $ruangan->kapasitas) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </td>
                <td>{{ $ruangan->barangs->where('kondisi', 'baik')->count() }}</td>
                <td>{{ $ruangan->barangs->where('kondisi', 'rusak')->count() }}</td>
                <td>{{ $ruangan->barangs->where('kondisi', 'hilang')->count() }}</td>
                <td>{{ $ruangan->status }}</td>
            </tr>
            @endforeach
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