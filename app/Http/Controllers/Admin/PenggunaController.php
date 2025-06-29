<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->orderBy('created_at', 'desc');

        // Filter berdasarkan nama
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->paginate(10);

        // Add computed attributes for display
        $users->getCollection()->transform(function ($user) {
            $user->role_label = $user->roles->first() ? ucfirst($user->roles->first()->name) : 'Tidak ada role';
            $user->role_badge_class = $this->getRoleBadgeClass($user->roles->first() ? $user->roles->first()->name : '');
            return $user;
        });

        $roles = Role::all();

        return view('admin.pengguna', compact('users', 'roles'));
    }

    private function getRoleBadgeClass($role)
    {
        switch ($role) {
            case 'admin':
                return 'badge-primary';
            case 'pengguna':
                return 'badge-success';
            default:
                return 'badge-secondary';
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,pengguna',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Pastikan role exists, jika tidak buat
            $role = Role::firstOrCreate(['name' => $request->role]);
            $user->assignRole($role);

            DB::commit();

            return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Store Error: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Find user by ID
            $user = User::findOrFail($id);

            \Log::info('Update Request for User ID: ' . $id);
            \Log::info('Request Data:', $request->all());

            // Validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'role' => 'required|in:admin,pengguna',
            ];

            // Add password validation only if password is provided
            if ($request->filled('password')) {
                $rules['password'] = 'min:8|confirmed';
            }

            $validated = $request->validate($rules);

            DB::beginTransaction();

            // Update user data
            $user->name = $validated['name'];
            $user->email = $validated['email'];

            // Update password only if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            // Update role
            $role = Role::firstOrCreate(['name' => $validated['role']]);
            $user->syncRoles([$role]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil diupdate',
                    'user' => $user->fresh()->load('roles'),
                ]);
            }

            return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil diupdate.');
        } catch (ValidationException $e) {
            DB::rollBack();
            \Log::error('Validation Error: ', $e->errors());

            if ($request->expectsJson()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $e->errors(),
                    ],
                    422,
                );
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            if ($request->expectsJson()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Gagal update: ' . $e->getMessage(),
                    ],
                    500,
                );
            }

            return redirect()
                ->back()
                ->with('error', 'Gagal update: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Delete Error: ' . $e->getMessage());

            return redirect()
                ->route('admin.pengguna.index')
                ->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
