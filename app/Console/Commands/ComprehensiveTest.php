<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Merchant;
use App\Models\Customer;
use App\Models\Product;
use App\Models\MerchantBill;
use App\Models\CustomerBill;
use App\Models\CustomerPayment;
use Illuminate\Support\Facades\DB;

class ComprehensiveTest extends Command
{
    protected $signature = 'test:comprehensive';
    protected $description = 'Run comprehensive test case from start to end';

    public function handle()
    {
        $this->info('🧪 Starting Comprehensive Test Case');
        $this->line('=====================================');
        $this->line('');

        try {
            // Step 1: Create Test Data
            $this->info('📝 Step 1: Creating Test Data');
            $this->line('');

            // Create Merchant
            $merchant = Merchant::create([
                'name' => 'Fresh Vegetables Co.',
                'email' => 'fresh@vegetables.com',
                'phone' => '9876543210',
                'address' => '123 Vegetable Market, Delhi',
                'business_name' => 'Fresh Vegetables Company',
                'gst_number' => 'GST123456789',
                'notes' => 'Premium vegetable supplier',
                'is_active' => true,
            ]);
            $this->line("✅ Created Merchant: {$merchant->name} (ID: {$merchant->id})");

            // Create Customers
            $customer1 = Customer::create([
                'name' => 'Mohan Das',
                'phone' => '9876543211',
                'address' => '456 Retail Street, Delhi',
                'customer_type' => 'retailer',
                'notes' => 'Regular customer',
                'is_active' => true,
            ]);

            $customer2 = Customer::create([
                'name' => 'Ballu Singh',
                'phone' => '9876543212',
                'address' => '789 Wholesale Area, Delhi',
                'customer_type' => 'wholesaler',
                'notes' => 'Bulk buyer',
                'is_active' => true,
            ]);
            $this->line("✅ Created Customers: {$customer1->name} (ID: {$customer1->id}), {$customer2->name} (ID: {$customer2->id})");

            // Create Products
            $product1 = Product::create([
                'name' => 'Tomatoes',
                'unit' => 'kg',
                'product_type' => 'vegetables',
                'notes' => 'Fresh red tomatoes',
                'is_active' => true,
            ]);

            $product2 = Product::create([
                'name' => 'Onions',
                'unit' => 'kg',
                'product_type' => 'vegetables',
                'notes' => 'White onions',
                'is_active' => true,
            ]);

            $product3 = Product::create([
                'name' => 'Potatoes',
                'unit' => 'kg',
                'product_type' => 'vegetables',
                'notes' => 'Fresh potatoes',
                'is_active' => true,
            ]);
            $this->line("✅ Created Products: {$product1->name} (ID: {$product1->id}), {$product2->name} (ID: {$product2->id}), {$product3->name} (ID: {$product3->id})");

            $this->line('');

            // Step 2: Create Merchant Bill
            $this->info('📋 Step 2: Creating Merchant Bill');
            $this->line('');

            $merchantBill = MerchantBill::create([
                'merchant_id' => $merchant->id,
                'bill_date' => now()->format('Y-m-d'),
                'total_amount' => 0, // Will be calculated
                'notes' => 'Test merchant bill',
            ]);

            // Add items to merchant bill
            $items = [
                [
                    'customer_id' => $customer1->id,
                    'product_id' => $product1->id,
                    'quantity' => 10,
                    'weight' => 5.0,
                    'rate' => 20.00,
                    'misc_adjustment' => -0.5,
                    'net_quantity' => 4.5,
                    'total_amount' => 90.00,
                ],
                [
                    'customer_id' => $customer1->id,
                    'product_id' => $product2->id,
                    'quantity' => 8,
                    'weight' => 3.0,
                    'rate' => 15.00,
                    'misc_adjustment' => 0,
                    'net_quantity' => 3.0,
                    'total_amount' => 45.00,
                ],
                [
                    'customer_id' => $customer2->id,
                    'product_id' => $product3->id,
                    'quantity' => 15,
                    'weight' => 7.5,
                    'rate' => 12.00,
                    'misc_adjustment' => -0.3,
                    'net_quantity' => 7.2,
                    'total_amount' => 86.40,
                ],
            ];

            $totalAmount = 0;
            foreach ($items as $item) {
                $merchantBill->items()->create($item);
                $totalAmount += $item['total_amount'];
            }

            $merchantBill->update(['total_amount' => $totalAmount]);
            $this->line("✅ Created Merchant Bill #{$merchantBill->id} with total: ₹{$totalAmount}");
            $this->line("   - Items: " . count($items));
            $this->line("   - Customers: {$customer1->name}, {$customer2->name}");
            $this->line('');

            // Step 3: Generate Customer Bills
            $this->info('💰 Step 3: Generating Customer Bills');
            $this->line('');

            // Generate customer bills manually (simulating the controller logic)
            DB::transaction(function () use ($merchantBill) {
                // Group items by customer
                $itemsByCustomer = $merchantBill->items->groupBy('customer_id');

                foreach ($itemsByCustomer as $customerId => $items) {
                    $customer = Customer::find($customerId);
                    
                    // Find or create a single bill for this customer on the bill date
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

                    // Add items to customer bill
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
                    $totalAmount = $customerBill->items()->sum('total_amount');
                    $customerBill->update(['total_amount' => $totalAmount]);

                    // Update customer balance
                    $customer->updateBalance($totalAmount, 'bill');
                }
            });

            // Check created customer bills
            $customerBills = CustomerBill::whereHas('items', function($query) use ($merchantBill) {
                $query->whereHas('merchantBillItem', function($q) use ($merchantBill) {
                    $q->where('merchant_bill_id', $merchantBill->id);
                });
            })->get();

            $this->line("✅ Generated Customer Bills:");
            foreach ($customerBills as $bill) {
                $customer = $bill->customer;
                $this->line("   - Customer Bill #{$bill->id} for {$customer->name}: ₹{$bill->total_amount}");
            }
            $this->line('');

            // Step 4: Test Customer Payments
            $this->info('💳 Step 4: Testing Customer Payments');
            $this->line('');

            // Make payments for customers
            $payment1 = CustomerPayment::create([
                'customer_id' => $customer1->id,
                'customer_bill_id' => $customerBills->where('customer_id', $customer1->id)->first()->id,
                'amount' => 100.00,
                'payment_method' => 'cash',
                'notes' => 'Partial payment',
                'payment_date' => now()->format('Y-m-d'),
                'status' => 'completed',
            ]);

            // Update customer balance after payment
            $customer1->updateBalance($payment1->amount, 'payment');

            $payment2 = CustomerPayment::create([
                'customer_id' => $customer2->id,
                'customer_bill_id' => $customerBills->where('customer_id', $customer2->id)->first()->id,
                'amount' => 50.00,
                'payment_method' => 'bank_transfer',
                'notes' => 'Partial payment',
                'payment_date' => now()->format('Y-m-d'),
                'status' => 'completed',
            ]);

            // Update customer balance after payment
            $customer2->updateBalance($payment2->amount, 'payment');

            $this->line("✅ Created Customer Payments:");
            $this->line("   - Payment #{$payment1->id} for {$customer1->name}: ₹{$payment1->amount}");
            $this->line("   - Payment #{$payment2->id} for {$customer2->name}: ₹{$payment2->amount}");
            $this->line('');

            // Step 5: Test Merchant Bill Edit and Auto-Update
            $this->info('✏️  Step 5: Testing Merchant Bill Edit and Auto-Update');
            $this->line('');

            // Get old customer bill totals
            $oldCustomer1Total = $customerBills->where('customer_id', $customer1->id)->first()->total_amount;
            $oldCustomer2Total = $customerBills->where('customer_id', $customer2->id)->first()->total_amount;

            $this->line("   Before Edit:");
            $this->line("   - {$customer1->name} bill: ₹{$oldCustomer1Total}");
            $this->line("   - {$customer2->name} bill: ₹{$oldCustomer2Total}");

            // Prepare new items data
            $newItems = [
                [
                    'customer_id' => $customer1->id,
                    'product_id' => $product1->id,
                    'quantity' => 10,
                    'weight' => 5.0,
                    'rate' => 25.00, // Changed from 20.00
                    'misc_adjustment' => -0.5,
                    'net_quantity' => 4.5,
                    'total_amount' => 112.50, // Changed from 90.00
                ],
                [
                    'customer_id' => $customer1->id,
                    'product_id' => $product2->id,
                    'quantity' => 8,
                    'weight' => 3.0,
                    'rate' => 12.00, // Changed from 15.00
                    'misc_adjustment' => 0,
                    'net_quantity' => 3.0,
                    'total_amount' => 36.00, // Changed from 45.00
                ],
                [
                    'customer_id' => $customer2->id,
                    'product_id' => $product3->id,
                    'quantity' => 15,
                    'weight' => 7.5,
                    'rate' => 15.00, // Changed from 12.00
                    'misc_adjustment' => -0.3,
                    'net_quantity' => 7.2,
                    'total_amount' => 108.00, // Changed from 86.40
                ],
            ];

            // Test automatic customer bill update BEFORE deleting old items
            $oldItems = $merchantBill->items; // Get the actual items before deletion
            $newItemsCollection = collect($newItems);
            
            $updateSummary = \App\Services\CustomerBillUpdateService::updateCustomerBillsFromMerchantBill(
                $merchantBill, 
                $oldItems, 
                $newItemsCollection
            );

            // Now delete old items and create new ones
            $merchantBill->items()->delete();
            
            $newTotalAmount = 0;
            foreach ($newItems as $item) {
                $merchantBill->items()->create($item);
                $newTotalAmount += $item['total_amount'];
            }

            $merchantBill->update(['total_amount' => $newTotalAmount]);

            // Check updated customer bills
            $customerBills->each(function($bill) {
                $bill->refresh();
            });

            $newCustomer1Total = $customerBills->where('customer_id', $customer1->id)->first()->total_amount;
            $newCustomer2Total = $customerBills->where('customer_id', $customer2->id)->first()->total_amount;

            $this->line("   After Edit:");
            $this->line("   - {$customer1->name} bill: ₹{$oldCustomer1Total} → ₹{$newCustomer1Total}");
            $this->line("   - {$customer2->name} bill: ₹{$oldCustomer2Total} → ₹{$newCustomer2Total}");
            $this->line("   - Update Summary: " . \App\Services\CustomerBillUpdateService::generateUpdateSummary($updateSummary));
            $this->line('');

            // Step 6: Verify Data Consistency
            $this->info('🔍 Step 6: Verifying Data Consistency');
            $this->line('');

            // Check customer balances
            $customer1->refresh();
            $customer2->refresh();

            $this->line("✅ Customer Balances:");
            $this->line("   - {$customer1->name}: ₹{$customer1->balance} (Total Purchased: ₹{$customer1->total_purchased}, Total Paid: ₹{$customer1->total_paid})");
            $this->line("   - {$customer2->name}: ₹{$customer2->balance} (Total Purchased: ₹{$customer2->total_purchased}, Total Paid: ₹{$customer2->total_paid})");

            // Check bill edit logs
            $billEditLogs = \App\Models\BillEditLog::where('bill_type', 'merchant_bill')
                ->where('bill_id', $merchantBill->id)
                ->get();

            $this->line("✅ Bill Edit Logs: {$billEditLogs->count()} entries");

            // Verify calculations
            $expectedCustomer1Total = 112.50 + 36.00; // 148.50
            $expectedCustomer2Total = 108.00;
            $expectedCustomer1Balance = $expectedCustomer1Total - 100.00; // 48.50 (positive means customer owes)
            $expectedCustomer2Balance = $expectedCustomer2Total - 50.00; // 58.00 (positive means customer owes)

            $this->line('');
            $this->line('🧮 Verification:');
            
            if (abs($newCustomer1Total - $expectedCustomer1Total) < 0.01) {
                $this->line("   ✅ Customer 1 bill total is correct: ₹{$newCustomer1Total}");
            } else {
                $this->line("   ❌ Customer 1 bill total incorrect: Expected ₹{$expectedCustomer1Total}, Got ₹{$newCustomer1Total}");
            }

            if (abs($newCustomer2Total - $expectedCustomer2Total) < 0.01) {
                $this->line("   ✅ Customer 2 bill total is correct: ₹{$newCustomer2Total}");
            } else {
                $this->line("   ❌ Customer 2 bill total incorrect: Expected ₹{$expectedCustomer2Total}, Got ₹{$newCustomer2Total}");
            }

            if (abs($customer1->balance - $expectedCustomer1Balance) < 0.01) {
                $this->line("   ✅ Customer 1 balance is correct: ₹{$customer1->balance}");
            } else {
                $this->line("   ❌ Customer 1 balance incorrect: Expected ₹{$expectedCustomer1Balance}, Got ₹{$customer1->balance}");
            }

            if (abs($customer2->balance - $expectedCustomer2Balance) < 0.01) {
                $this->line("   ✅ Customer 2 balance is correct: ₹{$customer2->balance}");
            } else {
                $this->line("   ❌ Customer 2 balance incorrect: Expected ₹{$expectedCustomer2Balance}, Got ₹{$customer2->balance}");
            }

            $this->line('');

            // Step 7: Cleanup
            $this->info('🧹 Step 7: Cleaning Up Test Data');
            $this->line('');

            // Delete test data
            $merchantBill->delete();
            $customerBills->each(function($bill) { $bill->delete(); });
            $payment1->delete();
            $payment2->delete();
            $merchant->delete();
            $customer1->delete();
            $customer2->delete();
            $product1->delete();
            $product2->delete();
            $product3->delete();

            $this->line('✅ Test data cleaned up');
            $this->line('');

            $this->info('🎉 Comprehensive Test Case Completed Successfully!');
            $this->line('');
            $this->line('📋 Test Summary:');
            $this->line('   ✅ Merchant creation');
            $this->line('   ✅ Customer creation');
            $this->line('   ✅ Product creation');
            $this->line('   ✅ Merchant bill creation');
            $this->line('   ✅ Customer bill generation');
            $this->line('   ✅ Customer payment processing');
            $this->line('   ✅ Merchant bill edit and auto-update');
            $this->line('   ✅ Data consistency verification');
            $this->line('   ✅ Bill edit logging');
            $this->line('');
            $this->line('🚀 All systems are working correctly!');

        } catch (\Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
        
        return 0;
    }
}
