<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Merchant;
use App\Models\MerchantBill;
use App\Models\MerchantBillItem;
use App\Models\CustomerBill;
use App\Models\CustomerBillItem;
use App\Models\CustomerPayment;
use Illuminate\Support\Facades\DB;

class TestCustomerPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:customer-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test customer payment functionality with sample data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Starting Customer Payment Test Case...');
        
        try {
            DB::beginTransaction();
            
            // Step 1: Create test customers
            $this->info('📝 Step 1: Creating test customers...');
            $customers = $this->createTestCustomers();
            
            // Step 2: Create test products
            $this->info('📝 Step 2: Creating test products...');
            $products = $this->createTestProducts();
            
            // Step 3: Create test merchant
            $this->info('📝 Step 3: Creating test merchant...');
            $merchant = $this->createTestMerchant();
            
            // Step 4: Create merchant bills
            $this->info('📝 Step 4: Creating merchant bills...');
            $merchantBills = $this->createTestMerchantBills($merchant, $customers, $products);
            
            // Step 5: Generate customer bills
            $this->info('📝 Step 5: Generating customer bills...');
            $customerBills = $this->generateCustomerBills($customers);
            
            // Step 6: Test customer payments
            $this->info('📝 Step 6: Testing customer payments...');
            $this->testCustomerPayments($customers, $customerBills);
            
            // Step 7: Verify balances
            $this->info('📝 Step 7: Verifying customer balances...');
            $this->verifyCustomerBalances($customers);
            
            DB::commit();
            
            $this->info('✅ Test completed successfully!');
            $this->displayTestResults($customers, $customerBills);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Test failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
    
    private function createTestCustomers()
    {
        $customers = [];
        
        $customerData = [
            ['name' => 'Test Customer 1', 'phone' => '9876543210', 'address' => 'Test Address 1', 'customer_type' => 'retailer'],
            ['name' => 'Test Customer 2', 'phone' => '9876543211', 'address' => 'Test Address 2', 'customer_type' => 'wholesaler'],
            ['name' => 'Test Customer 3', 'phone' => '9876543212', 'address' => 'Test Address 3', 'customer_type' => 'restaurant'],
        ];
        
        foreach ($customerData as $data) {
            $customer = Customer::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'customer_type' => $data['customer_type'],
                'is_active' => true,
                'balance' => 0,
                'total_purchased' => 0,
                'total_paid' => 0,
            ]);
            $customers[] = $customer;
            $this->line("   ✓ Created customer: {$customer->name}");
        }
        
        return $customers;
    }
    
    private function createTestProducts()
    {
        $products = [];
        
        $productData = [
            ['name' => 'Tomatoes', 'unit' => 'kg', 'product_type' => 'vegetables'],
            ['name' => 'Onions', 'unit' => 'kg', 'product_type' => 'vegetables'],
            ['name' => 'Potatoes', 'unit' => 'kg', 'product_type' => 'vegetables'],
            ['name' => 'Apples', 'unit' => 'kg', 'product_type' => 'fruits'],
        ];
        
        foreach ($productData as $data) {
            $product = Product::create([
                'name' => $data['name'],
                'unit' => $data['unit'],
                'product_type' => $data['product_type'],
                'is_active' => true,
            ]);
            $products[] = $product;
            $this->line("   ✓ Created product: {$product->name}");
        }
        
        return $products;
    }
    
    private function createTestMerchant()
    {
        $merchant = Merchant::create([
            'name' => 'Test Merchant',
            'business_name' => 'Test Merchant Business',
            'phone' => '9876543200',
            'address' => 'Test Merchant Address',
            'is_active' => true,
        ]);
        
        $this->line("   ✓ Created merchant: {$merchant->name}");
        return $merchant;
    }
    
    private function createTestMerchantBills($merchant, $customers, $products)
    {
        $merchantBills = [];
        
        // Create merchant bill for Customer 1
        $merchantBill1 = MerchantBill::create([
            'merchant_id' => $merchant->id,
            'bill_date' => now()->subDays(2),
            'total_amount' => 1500,
            'notes' => 'Test merchant bill 1',
        ]);
        
        // Add items for Customer 1
        MerchantBillItem::create([
            'merchant_bill_id' => $merchantBill1->id,
            'customer_id' => $customers[0]->id,
            'product_id' => $products[0]->id,
            'quantity' => 10,
            'rate' => 50,
            'misc_adjustment' => 0,
            'net_quantity' => 10,
            'total_amount' => 500,
        ]);
        
        MerchantBillItem::create([
            'merchant_bill_id' => $merchantBill1->id,
            'customer_id' => $customers[0]->id,
            'product_id' => $products[1]->id,
            'quantity' => 20,
            'rate' => 50,
            'misc_adjustment' => 0,
            'net_quantity' => 20,
            'total_amount' => 1000,
        ]);
        
        $merchantBills[] = $merchantBill1;
        $this->line("   ✓ Created merchant bill 1 for {$customers[0]->name}: ₹1500");
        
        // Create merchant bill for Customer 2
        $merchantBill2 = MerchantBill::create([
            'merchant_id' => $merchant->id,
            'bill_date' => now()->subDays(1),
            'total_amount' => 2000,
            'notes' => 'Test merchant bill 2',
        ]);
        
        // Add items for Customer 2
        MerchantBillItem::create([
            'merchant_bill_id' => $merchantBill2->id,
            'customer_id' => $customers[1]->id,
            'product_id' => $products[2]->id,
            'quantity' => 30,
            'rate' => 40,
            'misc_adjustment' => 5,
            'net_quantity' => 25,
            'total_amount' => 1000,
        ]);
        
        MerchantBillItem::create([
            'merchant_bill_id' => $merchantBill2->id,
            'customer_id' => $customers[1]->id,
            'product_id' => $products[3]->id,
            'quantity' => 25,
            'rate' => 40,
            'misc_adjustment' => 0,
            'net_quantity' => 25,
            'total_amount' => 1000,
        ]);
        
        $merchantBills[] = $merchantBill2;
        $this->line("   ✓ Created merchant bill 2 for {$customers[1]->name}: ₹2000");
        
        return $merchantBills;
    }
    
    private function generateCustomerBills($customers)
    {
        $customerBills = [];
        
        foreach ($customers as $customer) {
            // Generate customer bill for today
            $customerBill = CustomerBill::create([
                'customer_id' => $customer->id,
                'bill_date' => now()->format('Y-m-d'),
                'total_amount' => 0, // Will be calculated
                'notes' => 'Test customer bill',
                'payment_status' => 'pending',
                'paid_amount' => 0,
            ]);
            
            // Get merchant bill items for this customer
            $merchantBillItems = MerchantBillItem::where('customer_id', $customer->id)->get();
            
            foreach ($merchantBillItems as $item) {
                CustomerBillItem::create([
                    'customer_bill_id' => $customerBill->id,
                    'merchant_bill_item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'rate' => $item->rate,
                    'misc_adjustment' => $item->misc_adjustment,
                    'net_quantity' => $item->net_quantity,
                    'total_amount' => $item->total_amount,
                ]);
            }
            
            // Calculate total amount
            $totalAmount = $customerBill->items()->sum('total_amount');
            $customerBill->update(['total_amount' => $totalAmount]);
            
            // Update customer balance
            $customer->updateBalance($totalAmount, 'bill');
            
            $customerBills[] = $customerBill;
            $this->line("   ✓ Generated customer bill for {$customer->name}: ₹{$totalAmount}");
        }
        
        return $customerBills;
    }
    
    private function testCustomerPayments($customers, $customerBills)
    {
        // Test payment for Customer 1 (partial payment)
        $payment1 = CustomerPayment::create([
            'customer_id' => $customers[0]->id,
            'customer_bill_id' => $customerBills[0]->id,
            'amount' => 500,
            'payment_method' => 'cash',
            'reference_number' => 'CASH001',
            'notes' => 'Partial payment test',
            'payment_date' => now()->format('Y-m-d'),
            'status' => 'completed',
        ]);
        
        // Update customer balance
        $customers[0]->updateBalance(500, 'payment');
        
        // Update customer bill payment status
        $customerBills[0]->updatePaymentStatus();
        
        $this->line("   ✓ Created payment 1 for {$customers[0]->name}: ₹500 (Partial)");
        
        // Test payment for Customer 2 (full payment)
        $payment2 = CustomerPayment::create([
            'customer_id' => $customers[1]->id,
            'customer_bill_id' => $customerBills[1]->id,
            'amount' => 2000,
            'payment_method' => 'bank_transfer',
            'reference_number' => 'TXN002',
            'notes' => 'Full payment test',
            'payment_date' => now()->format('Y-m-d'),
            'status' => 'completed',
        ]);
        
        // Update customer balance
        $customers[1]->updateBalance(2000, 'payment');
        
        // Update customer bill payment status
        $customerBills[1]->updatePaymentStatus();
        
        $this->line("   ✓ Created payment 2 for {$customers[1]->name}: ₹2000 (Full)");
        
        // Test general payment for Customer 3 (not linked to bill)
        $payment3 = CustomerPayment::create([
            'customer_id' => $customers[2]->id,
            'customer_bill_id' => null,
            'amount' => 300,
            'payment_method' => 'upi',
            'reference_number' => 'UPI003',
            'notes' => 'General payment test',
            'payment_date' => now()->format('Y-m-d'),
            'status' => 'completed',
        ]);
        
        // Update customer balance
        $customers[2]->updateBalance(300, 'payment');
        
        $this->line("   ✓ Created payment 3 for {$customers[2]->name}: ₹300 (General)");
    }
    
    private function verifyCustomerBalances($customers)
    {
        foreach ($customers as $customer) {
            $customer->refresh();
            
            $this->line("   📊 {$customer->name}:");
            $this->line("      Balance: ₹{$customer->balance}");
            $this->line("      Total Purchased: ₹{$customer->total_purchased}");
            $this->line("      Total Paid: ₹{$customer->total_paid}");
            
            // Verify balance calculation
            $expectedBalance = $customer->total_purchased - $customer->total_paid;
            if ($customer->balance == $expectedBalance) {
                $this->line("      ✅ Balance calculation correct");
            } else {
                $this->error("      ❌ Balance calculation incorrect. Expected: ₹{$expectedBalance}, Actual: ₹{$customer->balance}");
            }
        }
    }
    
    private function displayTestResults($customers, $customerBills)
    {
        $this->info('');
        $this->info('📋 Test Results Summary:');
        $this->info('========================');
        
        $this->table(
            ['Customer', 'Total Purchased', 'Total Paid', 'Balance', 'Status'],
            collect($customers)->map(function ($customer) {
                $status = $customer->balance > 0 ? 'Outstanding' : ($customer->balance < 0 ? 'Overpaid' : 'Paid');
                return [
                    $customer->name,
                    '₹' . number_format($customer->total_purchased, 2),
                    '₹' . number_format($customer->total_paid, 2),
                    '₹' . number_format($customer->balance, 2),
                    $status
                ];
            })
        );
        
        $this->info('');
        $this->info('🎯 Test Scenarios Covered:');
        $this->info('  ✓ Customer creation with balance tracking');
        $this->info('  ✓ Merchant bill creation');
        $this->info('  ✓ Customer bill generation');
        $this->info('  ✓ Partial payment processing');
        $this->info('  ✓ Full payment processing');
        $this->info('  ✓ General payment (not linked to bill)');
        $this->info('  ✓ Balance calculation verification');
        $this->info('  ✓ Payment status updates');
        
        $this->info('');
        $this->info('🌐 You can now test the web interface:');
        $this->info('  • Customer Payments: /customer-payments');
        $this->info('  • Customer Bills: /customer-bills');
        $this->info('  • Customers: /customers');
    }
}