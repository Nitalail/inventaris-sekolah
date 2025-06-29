<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\Report;
use PDF;
use Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaporanController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        $ruangans = Ruangan::all();
        // Ubah dari get() menjadi paginate()
        $reports = Report::with('user')->latest()->paginate(10);
        $stats = $this->getReportStatistics();

        return view('admin.laporan', compact('kategoris', 'ruangans', 'reports', 'stats'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:inventory,transaction,maintenance,procurement,room,room_inventory',
            'format' => 'required|in:pdf,excel',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'category' => 'nullable|exists:kategoris,id',
            'room' => 'nullable|exists:ruangan,id',
            'status' => 'nullable|in:pending,dipinjam,dikembalikan,terlambat,rusak,aktif,perbaikan,tidak_aktif',
        ]);

        $data = [];
        $judul = '';
        $viewTemplate = '';

        switch ($request->report_type) {
            case 'inventory':
                $data = $this->getInventoryData($request);
                $judul = 'Laporan Inventaris Barang';
                $viewTemplate = 'admin.laporan.inventory_pdf';
                break;

            case 'transaction':
                $data = $this->getTransactionData($request);
                $judul = 'Laporan Transaksi Peminjaman';
                $viewTemplate = 'admin.laporan.transaction_pdf';
                break;

            case 'room':
                $data = $this->getRoomData($request);
                $judul = 'Laporan Inventaris Per Ruangan';
                $viewTemplate = 'admin.laporan.room_pdf';
                break;

            case 'room_inventory':
                $data = $this->getRoomInventoryData($request);
                $judul = 'Laporan Inventaris Ruangan';
                $viewTemplate = 'admin.laporan.room_inventory_pdf';
                break;
        }

        $tanggal = now()->format('d F Y');
        $fileName = 'reports/' . Str::slug($judul) . '_' . now()->timestamp . '.' . $request->format;

        if ($request->format == 'pdf') {
            $pdf = PDF::loadView($viewTemplate, [
                'data' => $data,
                'judul' => $judul,
                'tanggal' => $tanggal,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'report_type' => $request->report_type,
                'filters' => [
                    'category' => $request->category ? Kategori::find($request->category)->nama : null,
                    'room' => $request->room ? Ruangan::find($request->room)->nama_ruangan : null,
                    'status' => $request->status,
                ],
            ]);

            Storage::put('public/' . $fileName, $pdf->output());
            $this->saveReportRecord($request, $judul, $fileName);

            return $pdf->download("{$judul} - {$tanggal}.pdf");
        } else {
            $export = new \App\Exports\LaporanExport($data, $judul, $request->report_type);
            Excel::store($export, 'public/' . $fileName);
            $this->saveReportRecord($request, $judul, $fileName);

            return Excel::download($export, "{$judul} - {$tanggal}.xlsx");
        }
    }

    protected function getReportStatistics()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        return [
            'total_reports' => Report::count(),
            'monthly_reports' => Report::whereMonth('report_date', $currentMonth)->whereYear('report_date', $currentYear)->count(),
            'available_formats' => 3, // PDF, Excel, CSV
            'pdf_count' => Report::where('file_format', 'pdf')->count(),
            'excel_count' => Report::where('file_format', 'excel')->count(),
            'most_active_user' => DB::table('reports')->select('users.name', DB::raw('COUNT(reports.id) as count'))->join('users', 'reports.user_id', '=', 'users.id')->groupBy('users.name')->orderBy('count', 'desc')->first(),
        ];
    }

    protected function saveReportRecord($request, $judul, $filePath)
    {
        Report::create([
            'report_name' => $judul . ' - ' . now()->format('d M Y'),
            'report_type' => $request->report_type,
            'report_date' => now(),
            'file_format' => $request->format,
            'file_path' => $filePath,
            'user_id' => auth()->id(),
        ]);
    }

    protected function getInventoryData($request)
    {
        return Barang::with(['kategori', 'ruangan'])
            ->when($request->category, fn($q) => $q->where('kategori_id', $request->category))
            ->when($request->room, fn($q) => $q->where('ruangan_id', $request->room))
            ->when($request->date_from && $request->date_to, function ($q) use ($request) {
                $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })
            ->get();
    }

    protected function getTransactionData($request)
    {
        return DB::table('peminjaman')
            ->join('barang', 'peminjaman.barang_id', '=', 'barang.id')
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->select('peminjaman.id', 'barang.kode as kode_barang', 'barang.nama as nama_barang', 'users.name as peminjam', 'peminjaman.tanggal_pinjam', 'peminjaman.tanggal_kembali', 'peminjaman.status', 'peminjaman.jumlah')
            ->when($request->date_from && $request->date_to, function ($query) use ($request) {
                return $query->whereBetween('peminjaman.tanggal_pinjam', [$request->date_from, $request->date_to]);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('peminjaman.status', $status);
            })
            ->when($request->room, function ($query, $room) {
                return $query->where('barang.ruangan_id', $room);
            })
            ->get()
            ->map(function ($item) {
                $item->tanggal_pinjam = date('d/m/Y', strtotime($item->tanggal_pinjam));
                $item->tanggal_kembali = $item->tanggal_kembali ? date('d/m/Y', strtotime($item->tanggal_kembali)) : '-';
                return $item;
            });
    }

    protected function getMaintenanceData($request)
    {
        return Pemeliharaan::with(['barang'])
            ->when($request->date_from && $request->date_to, function ($q) use ($request) {
                $q->whereBetween('maintenance_date', [$request->date_from, $request->date_to]);
            })
            ->get();
    }

    protected function getProcurementData($request)
    {
        return Pengadaan::with(['barang'])
            ->when($request->date_from && $request->date_to, function ($q) use ($request) {
                $q->whereBetween('procurement_date', [$request->date_from, $request->date_to]);
            })
            ->get();
    }

    protected function getRoomData($request)
    {
        $query = Ruangan::with([
            'barangs' => function ($q) use ($request) {
                if ($request->category) {
                    $q->where('kategori_id', $request->category);
                }

                if ($request->date_from && $request->date_to) {
                    $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
                }

                $q->with('kategori');
            },
        ]);

        if ($request->room) {
            $query->where('id', $request->room);
        }

        return $query->get();
    }

    protected function getRoomInventoryData($request)
    {
        $query = Ruangan::select('ruangan.*', DB::raw('COUNT(barang.id) as total_barang_aktual'), DB::raw('SUM(CASE WHEN barang.kondisi = "baik" THEN 1 ELSE 0 END) as barang_baik'), DB::raw('SUM(CASE WHEN barang.kondisi = "rusak" THEN 1 ELSE 0 END) as barang_rusak'), DB::raw('SUM(CASE WHEN barang.kondisi = "hilang" THEN 1 ELSE 0 END) as barang_hilang'), DB::raw('GROUP_CONCAT(DISTINCT kategori.nama) as kategori_list'))->leftJoin('barang', 'ruangan.id', '=', 'barang.ruangan_id')->leftJoin('kategori', 'barang.kategori_id', '=', 'kategori.id')->groupBy('ruangan.id', 'ruangan.kode_ruangan', 'ruangan.nama_ruangan', 'ruangan.kapasitas', 'ruangan.jumlah_barang', 'ruangan.status', 'ruangan.deskripsi', 'ruangan.created_at', 'ruangan.updated_at');

        if ($request->status) {
            $query->where('ruangan.status', $request->status);
        }

        if ($request->room) {
            $query->where('ruangan.id', $request->room);
        }

        if ($request->category) {
            $query->where('barang.kategori_id', $request->category);
        }

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('ruangan.created_at', [$request->date_from, $request->date_to]);
        }

        $rooms = $query->get()->map(function ($room) {
            $room->persentase_kapasitas = $room->kapasitas > 0 ? round(($room->total_barang_aktual / $room->kapasitas) * 100, 2) : 0;

            if ($room->persentase_kapasitas > 100) {
                $room->status_kapasitas = 'Overload';
            } elseif ($room->persentase_kapasitas > 80) {
                $room->status_kapasitas = 'Hampir Penuh';
            } elseif ($room->persentase_kapasitas > 50) {
                $room->status_kapasitas = 'Normal';
            } else {
                $room->status_kapasitas = 'Kosong/Sedikit';
            }

            $room->tanggal_dibuat = date('d/m/Y', strtotime($room->created_at));

            return $room;
        });

        return $rooms;
    }

    public function getRoomInventoryStats($request = null)
    {
        $query = Ruangan::select(DB::raw('COUNT(*) as total_ruangan'), DB::raw('SUM(CASE WHEN status = "aktif" THEN 1 ELSE 0 END) as ruangan_aktif'), DB::raw('SUM(CASE WHEN status = "perbaikan" THEN 1 ELSE 0 END) as ruangan_perbaikan'), DB::raw('SUM(CASE WHEN status = "tidak_aktif" THEN 1 ELSE 0 END) as ruangan_tidak_aktif'), DB::raw('SUM(kapasitas) as total_kapasitas'), DB::raw('SUM(jumlah_barang) as total_barang_terdaftar'));

        if ($request && $request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $stats = $query->first();

        $totalBarangAktual = DB::table('barang')
            ->when($request && $request->date_from && $request->date_to, function ($q) use ($request) {
                $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })
            ->count();

        $stats->total_barang_aktual = $totalBarangAktual;
        $stats->persentase_penggunaan = $stats->total_kapasitas > 0 ? round(($totalBarangAktual / $stats->total_kapasitas) * 100, 2) : 0;

        return $stats;
    }
}
