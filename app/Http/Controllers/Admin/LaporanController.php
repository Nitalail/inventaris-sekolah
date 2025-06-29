<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\Barang;
use App\Models\SubBarang;
use App\Models\Peminjaman;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
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
            'report_type' => 'required|in:inventory,transaction,room',
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
        }

        $tanggal = now()->format('d F Y');
        $fileName = 'reports/' . Str::slug($judul) . '_' . now()->timestamp . '.' . $request->format;

        // Ensure reports directory exists
        Storage::makeDirectory('public/reports');

        if ($request->format == 'pdf') {
            try {
                $pdf = Pdf::loadView($viewTemplate, [
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

                // Save to storage with error handling
                $stored = Storage::put('public/' . $fileName, $pdf->output());
                
                if ($stored) {
                    $this->saveReportRecord($request, $judul, $fileName);
                } else {
                    throw new \Exception('Failed to save PDF to storage');
                }

                return $pdf->download("{$judul} - {$tanggal}.pdf")
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . "{$judul} - {$tanggal}.pdf" . '"');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
            }
        } else {
            try {
                $export = new \App\Exports\LaporanExport($data, $judul, $request->report_type);
                
                // Save to storage with error handling
                $stored = Excel::store($export, 'public/' . $fileName);
                
                if ($stored) {
                    $this->saveReportRecord($request, $judul, $fileName);
                } else {
                    throw new \Exception('Failed to save Excel to storage');
                }

                return Excel::download($export, "{$judul} - {$tanggal}.xlsx")
                    ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                    ->header('Content-Disposition', 'attachment; filename="' . "{$judul} - {$tanggal}.xlsx" . '"');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal generate Excel: ' . $e->getMessage());
            }
        }
    }

    protected function getReportStatistics()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        return [
            'total_reports' => Report::count(),
            'monthly_reports' => Report::whereMonth('report_date', $currentMonth)->whereYear('report_date', $currentYear)->count(),
            'available_formats' => 2, // PDF, Excel
            'pdf_count' => Report::where('file_format', 'pdf')->count(),
            'excel_count' => Report::where('file_format', 'excel')->count(),
            'most_active_user' => DB::table('reports')->select('users.name', DB::raw('COUNT(reports.id) as count'))->join('users', 'reports.user_id', '=', 'users.id')->groupBy('users.name')->orderBy('count', 'desc')->first(),
        ];
    }

    protected function saveReportRecord($request, $judul, $filePath)
    {
        try {
            Report::create([
                'report_name' => $judul . ' - ' . now()->format('d M Y'),
                'report_type' => $request->report_type,
                'report_date' => now(),
                'file_format' => $request->format,
                'file_path' => $filePath,
                'user_id' => auth()->id(),
            ]);
            
            // Clean up old reports (keep only last 50 reports)
            $this->cleanupOldReports();
            
        } catch (\Exception $e) {
            \Log::error('Failed to save report record: ' . $e->getMessage());
        }
    }

    protected function cleanupOldReports()
    {
        try {
            // Get reports older than 50 most recent
            $oldReports = Report::orderBy('created_at', 'desc')
                ->skip(50)
                ->take(100)
                ->get();

            foreach ($oldReports as $report) {
                // Delete physical file
                if (Storage::exists('public/' . $report->file_path)) {
                    Storage::delete('public/' . $report->file_path);
                }
                
                // Delete database record
                $report->delete();
            }
        } catch (\Exception $e) {
            \Log::error('Failed to cleanup old reports: ' . $e->getMessage());
        }
    }

    protected function getInventoryData($request)
    {
        // Menggunakan SubBarang sebagai data inventaris utama
        $query = SubBarang::with(['barang.kategori', 'barang.ruangan'])
            ->join('barang', 'sub_barang.barang_id', '=', 'barang.id')
            ->select('sub_barang.*', 'barang.nama as nama_barang', 'barang.kode as kode_barang', 'barang.satuan', 'barang.sumber_dana', 'barang.deskripsi as barang_deskripsi');

        // Filter berdasarkan kategori
        if ($request->category) {
            $query->where('barang.kategori_id', $request->category);
        }

        // Filter berdasarkan ruangan
        if ($request->room) {
            $query->where('barang.ruangan_id', $request->room);
        }

        // Filter berdasarkan tanggal
        if ($request->date_from && $request->date_to) {
            $query->whereBetween('sub_barang.created_at', [$request->date_from, $request->date_to]);
        }

        return $query->orderBy('barang.kode')
                    ->orderBy('sub_barang.kode')
                    ->get();
    }

    protected function getTransactionData($request)
    {
        $query = DB::table('peminjaman')
            ->join('barang', 'peminjaman.barang_id', '=', 'barang.id')
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->select(
                'peminjaman.id',
                'barang.kode as kode_barang',
                'barang.nama as nama_barang',
                'users.name as peminjam',
                'peminjaman.tanggal_pinjam',
                'peminjaman.tanggal_kembali',
                'peminjaman.status',
                'peminjaman.jumlah',
                'peminjaman.keperluan',
                'peminjaman.catatan',
                'peminjaman.sub_barang_ids'
            );

        // Filter berdasarkan tanggal
        if ($request->date_from && $request->date_to) {
            $query->whereBetween('peminjaman.tanggal_pinjam', [$request->date_from, $request->date_to]);
        }

        // Filter berdasarkan status
        if ($request->status) {
            $query->where('peminjaman.status', $request->status);
        }

        // Filter berdasarkan ruangan
        if ($request->room) {
            $query->where('barang.ruangan_id', $request->room);
        }

        return $query->get()->map(function ($item) {
            $item->tanggal_pinjam = date('d/m/Y', strtotime($item->tanggal_pinjam));
            $item->tanggal_kembali = $item->tanggal_kembali ? date('d/m/Y', strtotime($item->tanggal_kembali)) : '-';
            
            // Decode sub barang IDs dan ambil kode
            if ($item->sub_barang_ids) {
                $subBarangIds = json_decode($item->sub_barang_ids, true);
                if ($subBarangIds) {
                    $subBarangCodes = DB::table('sub_barang')
                        ->whereIn('id', $subBarangIds)
                        ->pluck('kode')
                        ->toArray();
                    $item->sub_barang_codes = implode(', ', $subBarangCodes);
                } else {
                    $item->sub_barang_codes = '-';
                }
            } else {
                $item->sub_barang_codes = '-';
            }
            
            return $item;
        });
    }

    protected function getRoomData($request)
    {
        $query = Ruangan::with(['barangs.subBarang', 'barangs.kategori']);

        // Filter berdasarkan ruangan spesifik
        if ($request->room) {
            $query->where('id', $request->room);
        }

        // Filter berdasarkan status ruangan
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        return $query->get()->map(function ($room) {
            // Hitung total sub barang per ruangan
            $totalSubBarang = $room->barangs->sum(function ($barang) {
                return $barang->subBarang->count();
            });

            // Hitung kondisi sub barang
            $kondisiCounts = [
                'baik' => 0,
                'rusak_ringan' => 0,
                'rusak_berat' => 0
            ];

            foreach ($room->barangs as $barang) {
                foreach ($barang->subBarang as $subBarang) {
                    if (isset($kondisiCounts[$subBarang->kondisi])) {
                        $kondisiCounts[$subBarang->kondisi]++;
                    }
                }
            }

            // Ambil kategori yang ada di ruangan dengan cara yang lebih explisit
            $kategoris = [];
            $jenisBarang = [];
            foreach ($room->barangs as $barang) {
                if ($barang->kategori && $barang->kategori->nama) {
                    $kategoris[] = $barang->kategori->nama;
                }
                // Ambil nama barang untuk kolom jenis barang
                if ($barang->nama) {
                    $jenisBarang[] = $barang->nama;
                }
            }
            $kategoris = array_unique($kategoris);
            $jenisBarang = array_unique($jenisBarang);

            $room->total_sub_barang = $totalSubBarang;
            $room->barang_baik = $kondisiCounts['baik'];
            $room->barang_rusak_ringan = $kondisiCounts['rusak_ringan'];
            $room->barang_rusak_berat = $kondisiCounts['rusak_berat'];
            $room->kategori_list = !empty($kategoris) ? implode(', ', $kategoris) : 'Belum ada kategori';
            $room->total_barang_types = $room->barangs->count();
            $room->jenis_barang_list = !empty($jenisBarang) ? implode(', ', $jenisBarang) : 'Belum ada barang';

            return $room;
        });
    }
}
