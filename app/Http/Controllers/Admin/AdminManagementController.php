<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $admins = User::with('village')
            ->whereIn('role', ['admin', 'superadmin'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $villages = Village::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.admins.create', compact('villages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed',
            'role' => ['required', Rule::in(['admin', 'superadmin'])],
            'village_id' => 'nullable|exists:villages,id',
            'phone' => 'nullable|string|max:20',
        ]);

        // Jika role admin, wajib ada village_id
        if ($validated['role'] === 'admin' && empty($validated['village_id'])) {
            return back()->withErrors(['village_id' => 'Admin Desa harus dipilih desanya.'])->withInput();
        }

        // Jika role superadmin, village_id harus null
        if ($validated['role'] === 'superadmin') {
            $validated['village_id'] = null;
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin berhasil ditambahkan!');
    }

    public function edit(User $admin)
    {
        // Tidak bisa edit superadmin lain
        if ($admin->isSuperAdmin() && $admin->id !== auth()->id()) {
            abort(403, 'Cannot edit other SuperAdmin.');
        }

        $villages = Village::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.admins.edit', compact('admin', 'villages'));
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
            'password' => 'nullable|string|min:3|confirmed',
            'role' => ['required', Rule::in(['admin', 'superadmin'])],
            'village_id' => 'nullable|exists:villages,id',
            'phone' => 'nullable|string|max:20',
        ]);

        // Jika role admin, wajib ada village_id
        if ($validated['role'] === 'admin' && empty($validated['village_id'])) {
            return back()->withErrors(['village_id' => 'Admin Desa harus dipilih desanya.'])->withInput();
        }

        // Jika role superadmin, village_id harus null
        if ($validated['role'] === 'superadmin') {
            $validated['village_id'] = null;
        }

        // Update password hanya jika diisi
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

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
