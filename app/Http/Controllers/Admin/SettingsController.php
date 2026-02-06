<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\System\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'admin_email' => 'required|email',
            'admin_whatsapp' => 'required|string',
            'shipping_origin_latitude' => 'required|numeric|between:-90,90',
            'shipping_origin_longitude' => 'required|numeric|between:-180,180',
            'shipping_origin_address' => 'required|string|max:500',
            // SMTP settings (optional)
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|integer',
            'smtp_username' => 'nullable|string',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|in:tls,ssl',
            'mail_from_name' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            if ($value !== null) { // Only update if value is not null
                Setting::set($key, $value);
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
