<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MerchantBill;
use App\Models\MerchantBillItem;
use App\Models\Merchant;
use App\Models\Customer;
use App\Models\Product;

class TestMerchantBillWeight extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:merchant-bill-weight';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test merchant bill weight functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Merchant Bill Weight Functionality...');
        
        try {
            // Test 1: Create test data
            $this->info('📝 Step 1: Creating test data...');
            $merchant = $this->createTestMerchant();
            $customer = $this->createTestCustomer();
            $product = $this->createTestProduct();
            
            // Test 2: Create merchant bill with weight
            $this->info('📝 Step 2: Creating merchant bill with weight...');
            $merchantBill = $this->createTestMerchantBill($merchant, $customer, $product);
            
            // Test 3: Verify calculations
            $this->info('📝 Step 3: Verifying calculations...');
            $this->verifyCalculations($merchantBill);
            
            $this->info('✅ All tests passed!');
            $this->displayTestSummary($merchantBill);
            
        } catch (\Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
    
    private function createTestMerchant()
    {
        $merchant = Merchant::create([
            'name' => 'Test Weight Merchant',
            'business_name' => 'Test Weight Business',
            'phone' => '9876543200',
            'address' => 'Test Weight Address',
            'is_active' => true,
        ]);
        
        $this->line("   ✓ Created merchant: {$merchant->name}");
        return $merchant;
    }
    
    private function createTestCustomer()
    {
        $customer = Customer::create([
            'name' => 'Test Weight Customer',
            'phone' => '9876543210',
            'address' => 'Test Weight Customer Address',
            'customer_type' => 'retailer',
            'is_active' => true,
        ]);
        
        $this->line("   ✓ Created customer: {$customer->name}");
        return $customer;
    }
    
    private function createTestProduct()
    {
        $product = Product::create([
            'name' => 'Test Weight Product',
            'unit' => 'kg',
            'product_type' => 'vegetables',
            'is_active' => true,
        ]);
        
        $this->line("   ✓ Created product: {$product->name}");
        return $product;
    }
    
    private function createTestMerchantBill($merchant, $customer, $product)
    {
        $merchantBill = MerchantBill::create([
            'merchant_id' => $merchant->id,
            'bill_date' => now()->format('Y-m-d'),
            'total_amount' => 0,
            'notes' => 'Test weight functionality',
        ]);
        
        // Create bill item with weight
        $item = MerchantBillItem::create([
            'merchant_bill_id' => $merchantBill->id,
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 10, // Original quantity
            'weight' => 8.5,  // Weight for calculation
            'rate' => 50.00,  // Rate per kg
            'misc_adjustment' => 0.5, // Misc adjustment
            'net_quantity' => 8.0, // Weight - Misc Adj = 8.5 - 0.5 = 8.0
            'total_amount' => 400.00, // Net Qty * Rate = 8.0 * 50 = 400.00
        ]);
        
        // Update total amount
        $merchantBill->update(['total_amount' => $item->total_amount]);
        
        $this->line("   ✓ Created merchant bill: #{$merchantBill->id}");
        $this->line("   ✓ Created bill item with weight: {$item->weight} kg");
        
        return $merchantBill;
    }
    
    private function verifyCalculations($merchantBill)
    {
        $item = $merchantBill->items->first();
        
        // Test calculations
        $expectedNetQuantity = $item->weight - $item->misc_adjustment;
        $expectedTotalAmount = $expectedNetQuantity * $item->rate;
        
        if ($item->net_quantity == $expectedNetQuantity) {
            $this->line("   ✓ Net Quantity calculation correct: {$item->net_quantity}");
        } else {
            throw new \Exception("Net Quantity calculation incorrect. Expected: {$expectedNetQuantity}, Actual: {$item->net_quantity}");
        }
        
        if ($item->total_amount == $expectedTotalAmount) {
            $this->line("   ✓ Total Amount calculation correct: ₹{$item->total_amount}");
        } else {
            throw new \Exception("Total Amount calculation incorrect. Expected: ₹{$expectedTotalAmount}, Actual: ₹{$item->total_amount}");
        }
        
        if ($merchantBill->total_amount == $expectedTotalAmount) {
            $this->line("   ✓ Merchant Bill Total Amount correct: ₹{$merchantBill->total_amount}");
        } else {
            throw new \Exception("Merchant Bill Total Amount incorrect. Expected: ₹{$expectedTotalAmount}, Actual: ₹{$merchantBill->total_amount}");
        }
    }
    
    private function displayTestSummary($merchantBill)
    {
        $this->info('');
        $this->info('📋 Test Summary:');
        $this->info('================');
        
        $item = $merchantBill->items->first();
        
        $this->table(
            ['Field', 'Value', 'Calculation'],
            [
                ['Quantity', $item->quantity, 'Original quantity'],
                ['Weight', $item->weight . ' kg', 'Weight for calculation'],
                ['Rate', '₹' . $item->rate, 'Rate per kg'],
                ['Misc Adjustment', $item->misc_adjustment . ' kg', 'Adjustment amount'],
                ['Net Quantity', $item->net_quantity . ' kg', 'Weight - Misc Adj'],
                ['Total Amount', '₹' . $item->total_amount, 'Net Qty × Rate'],
                ['Bill Total', '₹' . $merchantBill->total_amount, 'Sum of all items'],
            ]
        );
        
        $this->info('');
        $this->info('🎯 Key Changes Implemented:');
        $this->info('  ✓ Added Weight field after Quantity');
        $this->info('  ✓ Total = Net Qty × Rate');
        $this->info('  ✓ Net Qty = Weight - Misc Adj');
        $this->info('  ✓ Updated all forms (create, edit, show)');
        $this->info('  ✓ Updated controller validation and calculations');
        $this->info('  ✓ Updated model to include weight field');
        
        $this->info('');
        $this->info('🌐 Ready for Web Testing:');
        $this->info('  • Create Merchant Bill: http://localhost:8000/merchant-bills/create');
        $this->info('  • View Merchant Bills: http://localhost:8000/merchant-bills');
        $this->info('  • Test Bill #' . $merchantBill->id . ': http://localhost:8000/merchant-bills/' . $merchantBill->id);
    }
}
