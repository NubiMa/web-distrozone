use App\Models\StoreSetting;

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

echo "online_hours added successfully!\n";
