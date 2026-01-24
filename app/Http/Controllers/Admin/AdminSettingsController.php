<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    /**
     * Display settings page
     */
    /**
     * Display settings page
     */
    public function index()
    {
        // Fetch all settings and map to key-value array
        $settings = StoreSetting::all()->pluck('value', 'key');
        
        return view('admin.settings', compact('settings'));
    }

    /**
     * Update store settings
     */
    public function update(Request $request)
    {
        // Validate inputs
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'store_address' => 'required|string',
            'store_email' => 'required|email',
            'store_phone' => 'required|string',
            'store_currency' => 'required|string',
            'store_timezone' => 'required|string',
            'operating_hours' => 'nullable|array', // Expecting array for JSON storage
        ]);

        try {
            foreach ($validated as $key => $value) {
                // Handle array values (like operating hours)
                if (is_array($value)) {
                    $value = json_encode($value);
                    $type = 'json';
                } else {
                    $type = 'text';
                }

                StoreSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'type' => $type
                    ]
                );
            }

            return redirect()->route('admin.settings')->with('success', 'Changes saved successfully');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to save settings: ' . $e->getMessage()]);
        }
    }
}
