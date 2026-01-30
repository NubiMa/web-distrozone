<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AutoCompleteShippedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically mark shipped orders as completed after 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffTime = Carbon::now()->subDay();

        $orders = Transaction::where('order_status', 'shipped')
            ->whereNotNull('shipped_at')
            ->where('shipped_at', '<=', $cutoffTime)
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            $order->order_status = 'completed';
            $order->save();
            $count++;
        }

        $this->info("Auto-completed {$count} shipped order(s).");
        
        return 0;
    }
}
