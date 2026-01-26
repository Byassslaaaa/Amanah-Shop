<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registrasi berhasil!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function profile()
    {
        return view('auth.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo && \Storage::disk('public')->exists($user->profile_photo)) {
                \Storage::disk('public')->delete($user->profile_photo);
            }
            
            // Store new profile photo
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $updateData['profile_photo'] = $path;
        }

        $user->update($updateData);

        return redirect()->route('profile')->with('success', 'Profile berhasil diperbarui!');
    }
}
