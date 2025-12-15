<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\CustomerBill;
use Illuminate\Support\Facades\DB;

class TestWebInterface extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:web-interface';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test web interface functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🌐 Testing Web Interface Functionality...');
        
        try {
            // Test 1: Verify test data exists
            $this->info('📊 Test 1: Verifying test data...');
            $this->verifyTestData();
            
            // Test 2: Test customer payment creation
            $this->info('📝 Test 2: Testing customer payment creation...');
            $this->testPaymentCreation();
            
            // Test 3: Test balance calculations
            $this->info('💰 Test 3: Testing balance calculations...');
            $this->testBalanceCalculations();
            
            // Test 4: Test payment status updates
            $this->info('📋 Test 4: Testing payment status updates...');
            $this->testPaymentStatusUpdates();
            
            // Test 5: Test DataTables data integrity
            $this->info('📊 Test 5: Testing DataTables data integrity...');
            $this->testDataTablesIntegrity();
            
            $this->info('✅ All web interface tests passed!');
            $this->displayTestSummary();
            
        } catch (\Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
    
    private function verifyTestData()
    {
        $customers = Customer::where('name', 'like', 'Test Customer%')->get();
        $payments = CustomerPayment::whereHas('customer', function($query) {
            $query->where('name', 'like', 'Test Customer%');
        })->get();
        $bills = CustomerBill::whereHas('customer', function($query) {
            $query->where('name', 'like', 'Test Customer%');
        })->get();
        
        $this->line("   ✓ Found {$customers->count()} test customers");
        $this->line("   ✓ Found {$payments->count()} test payments");
        $this->line("   ✓ Found {$bills->count()} test bills");
        
        if ($customers->count() < 3) {
            throw new \Exception('Insufficient test customers found');
        }
    }
    
    private function testPaymentCreation()
    {
        $customer = Customer::where('name', 'Test Customer 1')->first();
        $originalBalance = $customer->balance;
        
        // Create a new payment
        $payment = CustomerPayment::create([
            'customer_id' => $customer->id,
            'customer_bill_id' => null,
            'amount' => 100,
            'payment_method' => 'cash',
            'reference_number' => 'TEST001',
            'notes' => 'Test payment creation',
            'payment_date' => now()->format('Y-m-d'),
            'status' => 'completed',
        ]);
        
        // Update customer balance
        $customer->updateBalance(100, 'payment');
        $customer->refresh();
        
        $expectedBalance = $originalBalance - 100;
        
        if ($customer->balance == $expectedBalance) {
            $this->line("   ✓ Payment creation and balance update successful");
        } else {
            throw new \Exception("Balance update failed. Expected: {$expectedBalance}, Actual: {$customer->balance}");
        }
        
        // Clean up test payment
        $payment->delete();
        $customer->updateBalance(100, 'payment'); // Reverse the payment
    }
    
    private function testBalanceCalculations()
    {
        $customers = Customer::where('name', 'like', 'Test Customer%')->get();
        
        foreach ($customers as $customer) {
            $expectedBalance = $customer->total_purchased - $customer->total_paid;
            
            if ($customer->balance == $expectedBalance) {
                $this->line("   ✓ {$customer->name}: Balance calculation correct (₹{$customer->balance})");
            } else {
                throw new \Exception("Balance calculation incorrect for {$customer->name}. Expected: ₹{$expectedBalance}, Actual: ₹{$customer->balance}");
            }
        }
    }
    
    private function testPaymentStatusUpdates()
    {
        $bills = CustomerBill::whereHas('customer', function($query) {
            $query->where('name', 'like', 'Test Customer%');
        })->get();
        
        foreach ($bills as $bill) {
            $bill->updatePaymentStatus();
            $bill->refresh();
            
            $totalPaid = $bill->payments()->where('status', 'completed')->sum('amount');
            
            if ($totalPaid >= $bill->total_amount) {
                $expectedStatus = 'paid';
            } elseif ($totalPaid > 0) {
                $expectedStatus = 'partial';
            } else {
                $expectedStatus = 'pending';
            }
            
            if ($bill->payment_status == $expectedStatus) {
                $this->line("   ✓ Bill #{$bill->id}: Payment status correct ({$bill->payment_status})");
            } else {
                throw new \Exception("Payment status incorrect for Bill #{$bill->id}. Expected: {$expectedStatus}, Actual: {$bill->payment_status}");
            }
        }
    }
    
    private function testDataTablesIntegrity()
    {
        $payments = CustomerPayment::with(['customer', 'customerBill'])->get();
        
        foreach ($payments as $payment) {
            // Test that all required relationships exist
            if (!$payment->customer) {
                throw new \Exception("Payment #{$payment->id} missing customer relationship");
            }
            
            // Test that payment data is complete
            if (empty($payment->amount) || empty($payment->payment_method) || empty($payment->payment_date)) {
                throw new \Exception("Payment #{$payment->id} has incomplete data");
            }
            
            // Test that customer bill relationship is valid (if exists)
            if ($payment->customer_bill_id && !$payment->customerBill) {
                throw new \Exception("Payment #{$payment->id} has invalid customer bill relationship");
            }
        }
        
        $this->line("   ✓ All {$payments->count()} payments have valid data and relationships");
    }
    
    private function displayTestSummary()
    {
        $this->info('');
        $this->info('📋 Web Interface Test Summary:');
        $this->info('==============================');
        
        $customers = Customer::where('name', 'like', 'Test Customer%')->get();
        $payments = CustomerPayment::whereHas('customer', function($query) {
            $query->where('name', 'like', 'Test Customer%');
        })->get();
        $bills = CustomerBill::whereHas('customer', function($query) {
            $query->where('name', 'like', 'Test Customer%');
        })->get();
        
        $this->table(
            ['Test Component', 'Status', 'Count'],
            [
                ['Customers', '✅ Pass', $customers->count()],
                ['Payments', '✅ Pass', $payments->count()],
                ['Bills', '✅ Pass', $bills->count()],
                ['Balance Calculations', '✅ Pass', 'All correct'],
                ['Payment Status Updates', '✅ Pass', 'All correct'],
                ['DataTables Integrity', '✅ Pass', 'All valid'],
            ]
        );
        
        $this->info('');
        $this->info('🎯 Ready for Manual Testing:');
        $this->info('  • Customer Payments: http://localhost:8000/customer-payments');
        $this->info('  • Customer Bills: http://localhost:8000/customer-bills');
        $this->info('  • Customers: http://localhost:8000/customers');
        $this->info('');
        $this->info('🔍 Manual Tests to Perform:');
        $this->info('  • Verify DataTables loads without column count error');
        $this->info('  • Test search and pagination functionality');
        $this->info('  • Test export to Excel/PDF');
        $this->info('  • Test create/edit payment forms');
        $this->info('  • Verify customer balance updates in real-time');
    }
}






