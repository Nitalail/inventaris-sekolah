<!-- resources/views/admin/laporan/template_pdf.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>{{ $judul }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin-bottom: 5px; }
        .header p { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #333; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 20px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $judul }}</h2>
        <p>Tanggal: {{ $tanggal }}</p>
    </div>

    @if($judul == 'Laporan Inventaris Barang')
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Ruangan</th>
                <th>Jumlah</th>
                <th>Kondisi</th>
                <th>Tahun</th>
                <th>Sumber Dana</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->kategori->nama }}</td>
                <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>
                    @if($item->kondisi == 'baik')
                        Baik
                    @elseif($item->kondisi == 'rusak_ringan')
                        Rusak Ringan
                    @else
                        Rusak Berat
                    @endif
                </td>
                <td>{{ $item->tahun_perolehan }}</td>
                <td>{{ $item->sumber_dana }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name }}</p>
    </div>
</body>
</html>