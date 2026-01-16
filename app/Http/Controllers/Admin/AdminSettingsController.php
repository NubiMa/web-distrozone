<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    /**
     * Get all settings
     */
    public function index()
    {
        $settings = StoreSetting::all()->groupBy('type');

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Get specific setting by key
     */
    public function show($key)
    {
        $setting = StoreSetting::where('key', $key)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $setting,
        ]);
    }

    /**
     * Update setting
     */
    public function update(Request $request, $key)
    {
        $validated = $request->validate([
            'value' => 'required',
            'description' => 'nullable|string',
        ]);

        $setting = StoreSetting::where('key', $key)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found',
            ], 404);
        }

        $setting->update([
            'value' => $validated['value'],
            'description' => $validated['description'] ?? $setting->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully',
            'data' => $setting,
        ]);
    }

    /**
     * Update operational hours (batch update)
     */
    public function updateOperationalHours(Request $request)
    {
        $validated = $request->validate([
            'offline_open_time' => 'nullable|string',
            'offline_close_time' => 'nullable|string',
            'offline_closed_day' => 'nullable|string',
            'online_open_time' => 'nullable|string',
            'online_close_time' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                StoreSetting::set($key, $value);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Operational hours updated successfully',
        ]);
    }

    /**
     * Get current operational status
     */
    public function operationalStatus()
    {
        $status = [
            'online_store_open' => StoreSetting::isOnlineStoreOpen(),
            'offline_store_open' => StoreSetting::isOfflineStoreOpen(),
            'settings' => [
                'online_open_time' => StoreSetting::get('online_open_time'),
                'online_close_time' => StoreSetting::get('online_close_time'),
                'offline_open_time' => StoreSetting::get('offline_open_time'),
                'offline_close_time' => StoreSetting::get('offline_close_time'),
                'offline_closed_day' => StoreSetting::get('offline_closed_day'),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $status,
        ]);
    }
}
