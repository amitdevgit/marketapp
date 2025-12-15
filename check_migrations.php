<?php

// Migration Check Script for MarketApp
// This script checks if all required migrations exist and are properly configured

echo "🔍 Checking MarketApp Migration Files...\n\n";

$migrationDir = __DIR__ . '/database/migrations';
$requiredMigrations = [
    'create_users_table.php',
    'create_cache_table.php', 
    'create_jobs_table.php',
    'create_merchants_table.php',
    'create_customers_table.php',
    'create_products_table.php',
    'create_bills_table.php', // This contains merchant_bills, merchant_bill_items, customer_bills, customer_bill_items
    'create_customer_payments_table.php',
    'create_bill_edit_logs_table.php',
];

$existingMigrations = [];
if (is_dir($migrationDir)) {
    $files = scandir($migrationDir);
    foreach ($files as $file) {
        if (strpos($file, '.php') !== false) {
            $existingMigrations[] = $file;
        }
    }
}

echo "📋 Required Migrations:\n";
foreach ($requiredMigrations as $migration) {
    $found = false;
    foreach ($existingMigrations as $existing) {
        if (strpos($existing, $migration) !== false) {
            echo "✅ {$migration}\n";
            $found = true;
            break;
        }
    }
    if (!$found) {
        echo "❌ {$migration} - MISSING\n";
    }
}

echo "\n📋 All Existing Migrations:\n";
foreach ($existingMigrations as $migration) {
    echo "📄 {$migration}\n";
}

echo "\n🔧 Migration Issues Found:\n";

// Check for problematic migrations
$problematicMigrations = [
    'update_merchants_table_remove_location_fields.php',
    'update_customers_table_simplify_fields.php'
];

foreach ($problematicMigrations as $migration) {
    foreach ($existingMigrations as $existing) {
        if (strpos($existing, $migration) !== false) {
            echo "⚠️  {$migration} - This migration is commented out and may cause issues\n";
            break;
        }
    }
}

echo "\n✅ Migration Check Complete!\n";
echo "\n💡 Recommendations:\n";
echo "1. Run 'php artisan migrate:fresh --seed' to reset database\n";
echo "2. Check if all controllers exist and are properly imported\n";
echo "3. Verify that all models have correct fillable fields\n";
echo "4. Ensure all views exist in resources/views directory\n";




