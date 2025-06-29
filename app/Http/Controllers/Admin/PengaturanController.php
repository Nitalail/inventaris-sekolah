<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('admin.pengaturan');
    }

    public function update(Request $request)
    {
        // Implementasi update pengaturan jika diperlukan
        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
} 