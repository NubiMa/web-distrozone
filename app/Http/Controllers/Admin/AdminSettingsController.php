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
        // Fetch all settings and decode JSON values properly
        $settings = StoreSetting::all()->mapWithKeys(function ($setting) {
            $value = $setting->value;
            
            // Decode JSON values
            if ($setting->type === 'json' && is_string($value)) {
                $value = json_decode($value, true);
            }
            
            return [$setting->key => $value];
        });
        
        // Migrate old payment format to new if payment_methods doesn't exist
        if (empty($settings['payment_methods'])) {
            $paymentMethods = [];
            
            // Check for old bank transfer data
            if (!empty($settings['bank_name']) || !empty($settings['bank_account_number'])) {
                $paymentMethods[] = [
                    'type' => 'bank_transfer',
                    'name' => ($settings['bank_name'] ?? 'Bank') . ' Transfer',
                    'enabled' => true,
                    'details' => [
                        'bank_name' => $settings['bank_name'] ?? '',
                        'account_number' => $settings['bank_account_number'] ?? '',
                        'account_holder' => $settings['bank_account_holder'] ?? ''
                    ]
                ];
            }
            
            // Check for old QRIS data
            if (!empty($settings['qris_image'])) {
                $paymentMethods[] = [
                    'type' => 'qris',
                    'name' => 'QRIS Payment',
                    'enabled' => true,
                    'details' => [
                        'image_path' => $settings['qris_image']
                    ]
                ];
            }
            
            // Save migrated data
            if (!empty($paymentMethods)) {
                StoreSetting::updateOrCreate(
                    ['key' => 'payment_methods'],
                    [
                        'value' => json_encode($paymentMethods),
                        'type' => 'json'
                    ]
                );
                
                $settings['payment_methods'] = $paymentMethods;
            }
        }
        
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
            // 'store_currency' => 'required|string', // Disabled in view
            // 'store_timezone' => 'required|string', // Disabled in view
            
            // Payment Methods (JSON array)
            'payment_methods' => 'nullable|json',
            'qris_image' => 'nullable|image|max:2048', // For QRIS method

            // Online store hours (per-day schedule)
            'online_hours' => 'required|array',
        ]);

        try {
            // Handle QRIS Image Upload
            $qrisImagePath = null;
            if ($request->hasFile('qris_image')) {
                $file = $request->file('qris_image');
                $filename = 'qris_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Ensure directory exists
                $directory = public_path('images/payment');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Move file
                $file->move($directory, $filename);
                $qrisImagePath = 'images/payment/' . $filename; // Relative public path
                
                // Delete old image if exists? (Optional cleanup logic could go here)
                
                $validated['qris_image'] = $qrisImagePath;
            } else {
                // If no new file uploaded, do not update the key (keep existing)
                unset($validated['qris_image']);
            }

            // Sync QRIS image path to payment_methods JSON
            // Determine the path to use: new upload or existing passed in JSON (if handled by frontend) 
            // OR we just inject the new upload path if available.
            if ($qrisImagePath || isset($validated['payment_methods'])) {
                $methods = json_decode($validated['payment_methods'] ?? '[]', true);
                
                // If we uploaded a new image, update the QRIS method in the array
                if ($qrisImagePath) {
                    foreach ($methods as &$method) {
                        if (isset($method['type']) && $method['type'] === 'qris') {
                            $method['details']['image_path'] = $qrisImagePath;
                        }
                    }
                }
                
                $validated['payment_methods'] = json_encode($methods);
            }

            foreach ($validated as $key => $value) {
                // Handle online_hours and payment_methods arrays
                if (in_array($key, ['online_hours', 'payment_methods']) && (is_array($value) || is_string($value))) {
                    if ($key === 'online_hours') {
                        // Convert closed flags from "1"/"0" strings to proper booleans
                        foreach ($value as $day => &$hours) {
                            if (isset($hours['closed'])) {
                                $hours['closed'] = (bool) $hours['closed'];
                            }
                        }
                        unset($hours); // Break reference
                    }
                    
                    // payment_methods is already JSON string from Alpine.js (or updated above), or array from online_hours
                    $value = is_string($value) ? $value : json_encode($value);
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
