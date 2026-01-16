<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerSettingsController extends Controller
{
    /**
     * Display customer settings page
     */
    public function index()
    {
        $user = Auth::user();
        return view('customer.settings', compact('user'));
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Update notification preferences
     */
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'order_updates' => 'boolean',
            'newsletter' => 'boolean',
        ]);

        $user = Auth::user();
        
        // Store preferences in user settings (you might need to add these columns or use a separate settings table)
        $user->update([
            'email_notifications' => $request->boolean('email_notifications'),
            'order_updates' => $request->boolean('order_updates'),
            'newsletter' => $request->boolean('newsletter'),
        ]);

        return back()->with('success', 'Preferensi berhasil disimpan!');
    }
}
