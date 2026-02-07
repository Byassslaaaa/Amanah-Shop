<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    public function __construct()
    {
        // Hanya SuperAdmin yang bisa akses
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isSuperAdmin()) {
                abort(403, 'Unauthorized. Only SuperAdmin can manage admins.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $admins = User::whereIn('role', ['admin', 'superadmin'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'superadmin'])],
            'phone' => 'nullable|string|max:20',
        ]);

        $role = $validated['role'];
        unset($validated['role']);
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        $user->role = $role;
        $user->save();

        Log::info('Admin created', [
            'created_by' => auth()->id(),
            'new_admin_id' => $user->id,
            'role' => $role,
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin berhasil ditambahkan!');
    }

    public function edit(User $admin)
    {
        // Tidak bisa edit superadmin lain
        if ($admin->isSuperAdmin() && $admin->id !== auth()->id()) {
            abort(403, 'Cannot edit other SuperAdmin.');
        }

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        // Tidak bisa update superadmin lain
        if ($admin->isSuperAdmin() && $admin->id !== auth()->id()) {
            abort(403, 'Cannot edit other SuperAdmin.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'superadmin'])],
            'phone' => 'nullable|string|max:20',
        ]);

        // Extract role before mass assignment (role is guarded)
        $newRole = $validated['role'];
        unset($validated['role']);

        // Update password hanya jika diisi
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Log role change
        if ($admin->role !== $newRole) {
            Log::alert('Admin role changed', [
                'changed_by' => auth()->id(),
                'admin_id' => $admin->id,
                'old_role' => $admin->role,
                'new_role' => $newRole,
            ]);
        }

        $admin->update($validated);
        $admin->role = $newRole;
        $admin->save();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin berhasil diupdate!');
    }

    public function destroy(User $admin)
    {
        // Tidak bisa hapus diri sendiri
        if ($admin->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        // Tidak bisa hapus superadmin lain
        if ($admin->isSuperAdmin()) {
            return back()->with('error', 'Tidak bisa menghapus SuperAdmin!');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin berhasil dihapus!');
    }
}
