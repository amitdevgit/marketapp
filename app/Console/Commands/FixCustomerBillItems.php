<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CustomerBillItem;

class FixCustomerBillItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:customer-bill-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix customer bill items with missing misc_adjustment and net_quantity data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing customer bill items...');
        
        $customerBillItems = CustomerBillItem::whereHas('merchantBillItem')->get();
        
        $fixedCount = 0;
        
        foreach ($customerBillItems as $item) {
            if ($item->merchantBillItem) {
                $item->update([
                    'quantity' => $item->merchantBillItem->quantity,
                    'misc_adjustment' => $item->merchantBillItem->misc_adjustment,
                    'net_quantity' => $item->merchantBillItem->net_quantity
                ]);
                $fixedCount++;
                $this->line("Fixed item ID: {$item->id}");
            }
        }
        
        $this->info("Fixed {$fixedCount} customer bill items.");
        
        return Command::SUCCESS;
    }
}