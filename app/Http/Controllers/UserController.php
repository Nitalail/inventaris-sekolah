<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = User::with('roles');
        
    //     // Filter berdasarkan nama
    //     if ($request->filled('name')) {
    //         $query->where('name', 'like', '%' . $request->name . '%');
    //     }
        
    //     // Filter berdasarkan role
    //     if ($request->filled('role')) {
    //         $query->where('role', $request->role);
    //     }
        
    //     // Filter berdasarkan status
    //     if ($request->filled('status')) {
    //         if ($request->status === 'aktif') {
    //             $query->whereNotNull('email_verified_at');
    //         } elseif ($request->status === 'nonaktif') {
    //             $query->whereNull('email_verified_at');
    //         }
    //     }
        
    //     $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
    //     return view('nama_view_anda', compact('users'));
    // }
    
    // public function destroy(User $user)
    // {
    //     $user->delete();
    //     return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    // }
}
