# MarketApp - Complete Setup Guide for Another PC

## 🚀 Quick Setup Instructions

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Node.js & NPM
- Git

### 1. Clone the Repository
```bash
git clone <repository-url>
cd marketapp
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration
Update `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketapp
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Database Setup
```bash
# Run migrations
php artisan migrate:fresh --seed

# Clear and cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Start the Application
```bash
# Start Laravel server
php artisan serve

# In another terminal, compile assets
npm run dev
```

### 7. Access the Application
- **Web Application**: http://localhost:8000
- **Login Credentials**: 
  - Email: `test@example.com`
  - Password: `password`

## 📋 Features Included

### ✅ Core Functionality
- **User Authentication**: Login/Register with Laravel Breeze
- **Merchant Management**: CRUD operations for merchants
- **Customer Management**: CRUD operations for customers
- **Product Management**: CRUD operations for products
- **Merchant Billing**: Create and manage merchant bills
- **Customer Billing**: Auto-generate customer bills from merchant bills
- **Payment Tracking**: Record and track customer payments
- **Bill Editing**: Edit merchant bills with automatic customer bill updates
- **PDF Generation**: Generate PDF invoices for customer bills
- **Data Export**: Export data to Excel/PDF using DataTables

### ✅ Advanced Features
- **Automatic Customer Bill Updates**: When merchant bills are edited, customer bills are automatically updated
- **Balance Tracking**: Real-time customer balance calculation
- **Bill Edit Logging**: Complete audit trail of all bill modifications
- **Professional UI**: Clean, responsive design with Tailwind CSS
- **Data Validation**: Comprehensive form validation
- **Error Handling**: Proper error handling and user feedback

## 🗄️ Database Schema

### Core Tables
- `users` - User authentication
- `merchants` - Merchant information
- `customers` - Customer information with balance tracking
- `products` - Product catalog
- `merchant_bills` - Merchant billing records
- `merchant_bill_items` - Individual items in merchant bills
- `customer_bills` - Customer billing records
- `customer_bill_items` - Individual items in customer bills
- `customer_payments` - Payment tracking
- `bill_edit_logs` - Audit trail for bill modifications

## 🔧 Troubleshooting

### Common Issues

1. **Controller Not Found Error**
   - Run `composer dump-autoload`
   - Clear route cache: `php artisan route:clear`

2. **Database Connection Issues**
   - Check `.env` database credentials
   - Ensure MySQL service is running
   - Run `php artisan migrate:fresh --seed`

3. **Permission Issues**
   - Set proper permissions on `storage` and `bootstrap/cache` directories
   - Run `chmod -R 775 storage bootstrap/cache`

4. **Asset Compilation Issues**
   - Run `npm install` to install dependencies
   - Run `npm run dev` to compile assets

### Migration Issues
If you encounter migration issues:
```bash
# Reset database completely
php artisan migrate:fresh --seed

# Or if you need to rollback specific migrations
php artisan migrate:rollback
```

## 📱 Mobile App Integration

The project also includes a React Native mobile app in the `marketmobileapp` directory:

### Mobile App Setup
```bash
cd ../marketmobileapp/frontend/MarketMobileApp
npm install
npx expo start
```

### Backend API
The Laravel backend serves as the API for the mobile app:
- API routes are in `routes/api.php`
- API controllers are in `app/Http/Controllers/Api/`
- Authentication uses Laravel Sanctum

## 🎯 Testing the Application

### Test Scenarios
1. **User Registration/Login**
2. **Create Merchants, Customers, Products**
3. **Create Merchant Bills**
4. **Generate Customer Bills**
5. **Record Customer Payments**
6. **Edit Merchant Bills** (should auto-update customer bills)
7. **Generate PDF Invoices**
8. **Export Data**

### Test Data
The application comes with seeded test data:
- Default user: `test@example.com` / `password`
- Sample merchants, customers, and products

## 📞 Support

If you encounter any issues:
1. Check the Laravel logs in `storage/logs/laravel.log`
2. Verify all dependencies are installed
3. Ensure database is properly configured
4. Check file permissions

## 🔄 Updates

To update the application:
```bash
git pull origin main
composer install
php artisan migrate
npm install
npm run build
```

---

**Last Updated**: October 24, 2025
**Version**: 1.0.0
**Laravel Version**: 12.7.1




