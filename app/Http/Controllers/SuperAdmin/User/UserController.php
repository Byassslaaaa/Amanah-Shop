<?php

namespace App\Http\Controllers\SuperAdmin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Only superadmin can access this
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        // HANYA tampilkan user biasa (role='user'), bukan admin/superadmin
        $query = User::where('role', 'user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $users = $query->latest()->paginate(10);

        // Get statistics - HANYA user biasa
        $totalUsers = User::where('role', 'user')->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers'
        ));
    }
    
    public function create()
    {
        // Only superadmin can access this
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }
        
        return view('admin.users.create');
    }
    
    public function store(Request $request)
    {
        // Only superadmin can access this
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        // Selalu buat sebagai user biasa (role='user')
        // Untuk buat admin, gunakan menu "Kelola Admin"
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        $user->role = 'user';
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil ditambahkan.');
    }
    
    public function show(User $user)
    {
        // Only superadmin can access this
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }
        
        return view('admin.users.show', compact('user'));
    }
    
    public function edit(User $user)
    {
        // Only superadmin can access this
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        // Prevent editing admin/superadmin (harus di menu "Kelola Admin")
        if (in_array($user->role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Admin tidak dapat diedit di sini. Gunakan menu "Kelola Admin".');
        }

        // Prevent editing own account through this interface
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.show', $user)
                            ->with('error', 'Gunakan halaman profile untuk mengedit akun Anda sendiri.');
        }

        return view('admin.users.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        // Only superadmin can access this
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        // Prevent editing admin/superadmin (harus di menu "Kelola Admin")
        if (in_array($user->role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Admin tidak dapat diedit di sini. Gunakan menu "Kelola Admin".');
        }

        // Prevent editing own account through this interface
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.show', $user)
                            ->with('error', 'Gunakan halaman profile untuk mengedit akun Anda sendiri.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);
        $user->role = 'user'; // Paksa tetap 'user'
        $user->save();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil diperbarui.');
    }
    
    public function destroy(User $user)
    {
        // Only superadmin can access this
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        // Prevent deleting admin/superadmin (harus di menu "Kelola Admin")
        if (in_array($user->role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Admin tidak dapat dihapus di sini. Gunakan menu "Kelola Admin".');
        }

        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil dihapus.');
    }
}
