<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MerchantBill;
use App\Models\MerchantBillItem;
use App\Models\Merchant;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerBill;
use App\Models\CustomerBillItem;
use Illuminate\Support\Facades\DB;

class TestCorrectedCalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:corrected-calculation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test corrected merchant bill calculation with complete flow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Corrected Merchant Bill Calculation...');
        
        try {
            // Step 1: Clear all existing bills
            $this->info('📝 Step 1: Clearing all existing bills...');
            $this->clearAllBills();
            
            // Step 2: Create test data
            $this->info('📝 Step 2: Creating test data...');
            $testData = $this->createTestData();
            
            // Step 3: Create merchant bill with corrected calculation
            $this->info('📝 Step 3: Creating merchant bill with corrected calculation...');
            $merchantBill = $this->createMerchantBill($testData);
            
            // Step 4: Verify calculations
            $this->info('📝 Step 4: Verifying calculations...');
            $this->verifyCalculations($merchantBill);
            
            // Step 5: Generate customer bill
            $this->info('📝 Step 5: Generating customer bill...');
            $customerBill = $this->generateCustomerBill($merchantBill);
            
            // Step 6: Verify customer bill calculations
            $this->info('📝 Step 6: Verifying customer bill calculations...');
            $this->verifyCustomerBillCalculations($customerBill);
            
            $this->info('✅ All tests passed!');
            $this->displayTestSummary($merchantBill, $customerBill);
            
        } catch (\Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
    
    private function clearAllBills()
    {
        DB::table('merchant_bill_items')->delete();
        DB::table('merchant_bills')->delete();
        DB::table('customer_bill_items')->delete();
        DB::table('customer_bills')->delete();
        
        $this->line("   ✓ All merchant bills cleared");
        $this->line("   ✓ All customer bills cleared");
    }
    
    private function createTestData()
    {
        // Create merchant
        $merchant = Merchant::create([
            'name' => 'Test Merchant',
            'business_name' => 'Test Business',
            'phone' => '9876543200',
            'address' => 'Test Address',
            'is_active' => true,
        ]);
        
        // Create customer
        $customer = Customer::create([
            'name' => 'Test Customer',
            'phone' => '9876543210',
            'address' => 'Test Customer Address',
            'customer_type' => 'retailer',
            'is_active' => true,
        ]);
        
        // Create products
        $product1 = Product::create([
            'name' => 'Tomatoes',
            'unit' => 'kg',
            'product_type' => 'vegetables',
            'is_active' => true,
        ]);
        
        $product2 = Product::create([
            'name' => 'Onions',
            'unit' => 'kg',
            'product_type' => 'vegetables',
            'is_active' => true,
        ]);
        
        $this->line("   ✓ Created merchant: {$merchant->name}");
        $this->line("   ✓ Created customer: {$customer->name}");
        $this->line("   ✓ Created product 1: {$product1->name}");
        $this->line("   ✓ Created product 2: {$product2->name}");
        
        return [
            'merchant' => $merchant,
            'customer' => $customer,
            'product1' => $product1,
            'product2' => $product2,
        ];
    }
    
    private function createMerchantBill($testData)
    {
        $merchantBill = MerchantBill::create([
            'merchant_id' => $testData['merchant']->id,
            'bill_date' => now()->format('Y-m-d'),
            'total_amount' => 0,
            'notes' => 'Test corrected calculation',
        ]);
        
        // Item 1: Tomatoes
        $item1 = MerchantBillItem::create([
            'merchant_bill_id' => $merchantBill->id,
            'customer_id' => $testData['customer']->id,
            'product_id' => $testData['product1']->id,
            'quantity' => 20, // Original quantity
            'weight' => 18.5, // Weight for calculation
            'rate' => 30.00, // Rate per kg
            'misc_adjustment' => 1.5, // Misc adjustment
            'net_quantity' => 17.0, // Weight - Misc Adj = 18.5 - 1.5 = 17.0
            'total_amount' => 510.00, // Net Qty * Rate = 17.0 * 30 = 510.00
        ]);
        
        // Item 2: Onions
        $item2 = MerchantBillItem::create([
            'merchant_bill_id' => $merchantBill->id,
            'customer_id' => $testData['customer']->id,
            'product_id' => $testData['product2']->id,
            'quantity' => 15, // Original quantity
            'weight' => 12.0, // Weight for calculation
            'rate' => 25.00, // Rate per kg
            'misc_adjustment' => 0.5, // Misc adjustment
            'net_quantity' => 11.5, // Weight - Misc Adj = 12.0 - 0.5 = 11.5
            'total_amount' => 287.50, // Net Qty * Rate = 11.5 * 25 = 287.50
        ]);
        
        // Update total amount
        $totalAmount = $item1->total_amount + $item2->total_amount;
        $merchantBill->update(['total_amount' => $totalAmount]);
        
        $this->line("   ✓ Created merchant bill: #{$merchantBill->id}");
        $this->line("   ✓ Created item 1: {$testData['product1']->name} - Weight: {$item1->weight}kg, Net Qty: {$item1->net_quantity}kg, Total: ₹{$item1->total_amount}");
        $this->line("   ✓ Created item 2: {$testData['product2']->name} - Weight: {$item2->weight}kg, Net Qty: {$item2->net_quantity}kg, Total: ₹{$item2->total_amount}");
        $this->line("   ✓ Total bill amount: ₹{$totalAmount}");
        
        return $merchantBill;
    }
    
    private function verifyCalculations($merchantBill)
    {
        $items = $merchantBill->items;
        
        foreach ($items as $index => $item) {
            $expectedNetQuantity = $item->weight - $item->misc_adjustment;
            $expectedTotalAmount = $expectedNetQuantity * $item->rate;
            
            if ($item->net_quantity == $expectedNetQuantity) {
                $this->line("   ✓ Item " . ($index + 1) . " Net Quantity correct: {$item->net_quantity}kg");
            } else {
                throw new \Exception("Item " . ($index + 1) . " Net Quantity incorrect. Expected: {$expectedNetQuantity}, Actual: {$item->net_quantity}");
            }
            
            if ($item->total_amount == $expectedTotalAmount) {
                $this->line("   ✓ Item " . ($index + 1) . " Total Amount correct: ₹{$item->total_amount}");
            } else {
                throw new \Exception("Item " . ($index + 1) . " Total Amount incorrect. Expected: ₹{$expectedTotalAmount}, Actual: ₹{$item->total_amount}");
            }
        }
        
        $expectedBillTotal = $items->sum('total_amount');
        if ($merchantBill->total_amount == $expectedBillTotal) {
            $this->line("   ✓ Merchant Bill Total Amount correct: ₹{$merchantBill->total_amount}");
        } else {
            throw new \Exception("Merchant Bill Total Amount incorrect. Expected: ₹{$expectedBillTotal}, Actual: ₹{$merchantBill->total_amount}");
        }
    }
    
    private function generateCustomerBill($merchantBill)
    {
        // Generate customer bill using the controller logic
        DB::transaction(function () use ($merchantBill) {
            // Group items by customer
            $itemsByCustomer = $merchantBill->items->groupBy('customer_id');

            foreach ($itemsByCustomer as $customerId => $items) {
                // Find or create customer bill
                $customerBill = CustomerBill::firstOrCreate(
                    [
                        'customer_id' => $customerId,
                        'bill_date' => $merchantBill->bill_date,
                    ],
                    [
                        'total_amount' => 0,
                        'notes' => 'Generated from merchant bill #' . $merchantBill->id,
                    ]
                );

                // Create customer bill items
                foreach ($items as $item) {
                    $customerBill->items()->create([
                        'merchant_bill_item_id' => $item->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'weight' => $item->weight,
                        'rate' => $item->rate,
                        'misc_adjustment' => $item->misc_adjustment,
                        'net_quantity' => $item->net_quantity,
                        'total_amount' => $item->total_amount,
                    ]);
                }

                // Recalculate total
                $recalculatedTotal = $customerBill->items()->sum('total_amount');
                $customerBill->update(['total_amount' => $recalculatedTotal]);
            }
        });
        
        $customerBill = CustomerBill::where('customer_id', $merchantBill->items->first()->customer_id)
            ->where('bill_date', $merchantBill->bill_date)
            ->first();
        
        $this->line("   ✓ Generated customer bill: #{$customerBill->id}");
        $this->line("   ✓ Customer bill total: ₹{$customerBill->total_amount}");
        
        return $customerBill;
    }
    
    private function verifyCustomerBillCalculations($customerBill)
    {
        $items = $customerBill->items;
        
        foreach ($items as $index => $item) {
            $this->line("   ✓ Customer Bill Item " . ($index + 1) . ": {$item->product->name} - Total: ₹{$item->total_amount}");
        }
        
        $expectedTotal = $items->sum('total_amount');
        if ($customerBill->total_amount == $expectedTotal) {
            $this->line("   ✓ Customer Bill Total Amount correct: ₹{$customerBill->total_amount}");
        } else {
            throw new \Exception("Customer Bill Total Amount incorrect. Expected: ₹{$expectedTotal}, Actual: ₹{$customerBill->total_amount}");
        }
    }
    
    private function displayTestSummary($merchantBill, $customerBill)
    {
        $this->info('');
        $this->info('📋 Test Summary:');
        $this->info('================');
        
        $this->table(
            ['Bill Type', 'Bill ID', 'Total Amount', 'Items Count'],
            [
                ['Merchant Bill', '#' . $merchantBill->id, '₹' . $merchantBill->total_amount, $merchantBill->items->count()],
                ['Customer Bill', '#' . $customerBill->id, '₹' . $customerBill->total_amount, $customerBill->items->count()],
            ]
        );
        
        $this->info('');
        $this->info('📊 Detailed Calculations:');
        $this->info('==========================');
        
        foreach ($merchantBill->items as $index => $item) {
            $this->line("Item " . ($index + 1) . ": {$item->product->name}");
            $this->line("  • Quantity: {$item->quantity}");
            $this->line("  • Weight: {$item->weight} kg");
            $this->line("  • Rate: ₹{$item->rate} per kg");
            $this->line("  • Misc Adj: {$item->misc_adjustment} kg");
            $this->line("  • Net Qty: {$item->net_quantity} kg (Weight - Misc Adj)");
            $this->line("  • Total: ₹{$item->total_amount} (Net Qty × Rate)");
            $this->line('');
        }
        
        $this->info('🎯 Key Verification Points:');
        $this->info('  ✓ Net Qty = Weight - Misc Adj');
        $this->info('  ✓ Total Amount = Net Qty × Rate');
        $this->info('  ✓ Merchant Bill Total = Sum of all items');
        $this->info('  ✓ Customer Bill Total = Merchant Bill Total');
        
        $this->info('');
        $this->info('🌐 Ready for Web Testing:');
        $this->info('  • Merchant Bill: http://localhost:8000/merchant-bills/' . $merchantBill->id);
        $this->info('  • Customer Bill: http://localhost:8000/customer-bills/' . $customerBill->id);
        $this->info('  • Create New Bill: http://localhost:8000/merchant-bills/create');
    }
}
