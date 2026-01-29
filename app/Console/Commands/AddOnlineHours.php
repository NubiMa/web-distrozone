<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StoreSetting;

class AddOnlineHours extends Command
{
    protected $signature = 'settings:add-online-hours';
    protected $description = 'Add online_hours setting to database';

    public function handle()
    {
        $hours = [
            'Monday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
            'Tuesday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
            'Wednesday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
            'Thursday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
            'Friday' => ['start' => '09:00', 'end' => '22:00', 'closed' => false],
            'Saturday' => ['start' => '10:00', 'end' => '22:00', 'closed' => false],
            'Sunday' => ['start' => '10:00', 'end' => '21:00', 'closed' => false],
        ];

        StoreSetting::updateOrCreate(
            ['key' => 'online_hours'],
            [
                'value' => json_encode($hours),
                'type' => 'json',
                'description' => 'Online store hours for each day'
            ]
        );

        $this->info('online_hours added successfully!');
        return 0;
    }
}
