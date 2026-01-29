<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StoreSetting;

class FixOnlineHours extends Command
{
    protected $signature = 'settings:fix-online-hours';
    protected $description = 'Fix online_hours boolean values';

    public function handle()
    {
        // Get current data
        $setting = StoreSetting::where('key', 'online_hours')->first();
        
        if (!$setting) {
            $this->error('online_hours not found!');
            return 1;
        }
        
        $hours = json_decode($setting->value, true);
        
        // Fix the closed field to be proper boolean
        foreach ($hours as $day => &$schedule) {
            $schedule['closed'] = isset($schedule['closed']) && $schedule['closed'] ? true : false;
        }
        unset($schedule);
        
        // Update with proper booleans
        $setting->value = json_encode($hours);
        $setting->save();
        
        $this->info('online_hours fixed successfully!');
        $this->info('Updated data: ' . json_encode($hours, JSON_PRETTY_PRINT));
        
        return 0;
    }
}
