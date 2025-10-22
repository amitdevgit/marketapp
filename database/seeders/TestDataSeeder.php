<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Merchant;
use App\Models\Customer;
use App\Models\Product;
use App\Models\MerchantBill;
use App\Models\MerchantBillItem;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test merchants
        $merchant1 = Merchant::create([
            'name' => 'Fresh Vegetables Co.',
            'email' => 'fresh@example.com',
            'phone' => '9876543210',
            'address' => '123 Market Street, City',
            'business_name' => 'Fresh Vegetables Company',
            'gst_number' => 'GST123456789',
            'notes' => 'Premium vegetable supplier',
            'is_active' => true,
        ]);

        $merchant2 = Merchant::create([
            'name' => 'Green Farm Produce',
            'email' => 'green@example.com',
            'phone' => '9876543211',
            'address' => '456 Farm Road, Village',
            'business_name' => 'Green Farm Produce Ltd',
            'gst_number' => 'GST987654321',
            'notes' => 'Organic vegetables',
            'is_active' => true,
        ]);

        // Create test customers
        $customer1 = Customer::create([
            'name' => 'ABC Restaurant',
            'phone' => '9876543220',
            'address' => '789 Restaurant Street, City',
            'customer_type' => 'restaurant',
            'notes' => 'Regular customer',
            'is_active' => true,
        ]);

        $customer2 = Customer::create([
            'name' => 'XYZ Grocery Store',
            'phone' => '9876543221',
            'address' => '321 Store Avenue, City',
            'customer_type' => 'retailer',
            'notes' => 'Wholesale customer',
            'is_active' => true,
        ]);

        // Create test products
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

        $product3 = Product::create([
            'name' => 'Potatoes',
            'unit' => 'kg',
            'product_type' => 'vegetables',
            'is_active' => true,
        ]);

        // Create test merchant bill
        $merchantBill = MerchantBill::create([
            'merchant_id' => $merchant1->id,
            'bill_date' => now()->subDays(2),
            'total_amount' => 0, // Will be calculated
            'notes' => 'Test merchant bill',
        ]);

        // Create merchant bill items
        MerchantBillItem::create([
            'merchant_bill_id' => $merchantBill->id,
            'customer_id' => $customer1->id,
            'product_id' => $product1->id,
            'quantity' => 50.00,
            'rate' => 25.00,
            'misc_adjustment' => 2.00,
            'net_quantity' => 48.00,
            'total_amount' => 1200.00,
        ]);

        MerchantBillItem::create([
            'merchant_bill_id' => $merchantBill->id,
            'customer_id' => $customer1->id,
            'product_id' => $product2->id,
            'quantity' => 30.00,
            'rate' => 20.00,
            'misc_adjustment' => 1.00,
            'net_quantity' => 29.00,
            'total_amount' => 580.00,
        ]);

        MerchantBillItem::create([
            'merchant_bill_id' => $merchantBill->id,
            'customer_id' => $customer2->id,
            'product_id' => $product3->id,
            'quantity' => 40.00,
            'rate' => 15.00,
            'misc_adjustment' => 0.00,
            'net_quantity' => 40.00,
            'total_amount' => 600.00,
        ]);

        // Update merchant bill total
        $merchantBill->update([
            'total_amount' => 2380.00
        ]);

        $this->command->info('Test data created successfully!');
        $this->command->info('Merchants: 2');
        $this->command->info('Customers: 2');
        $this->command->info('Products: 3');
        $this->command->info('Merchant Bills: 1');
        $this->command->info('Merchant Bill Items: 3');
    }
}