# MarketApp - Fresh Installation Guide

## 🚀 Quick Setup Instructions

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Node.js & NPM (for frontend assets)

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/amitdevgit/marketapp.git
   cd marketapp
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   ```
   
   Update the `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=marketapp
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```
   
   This will create all necessary tables with the correct structure:
   - `users` - User authentication
   - `merchants` - Merchant information
   - `customers` - Customer information (with balance tracking)
   - `products` - Product catalog
   - `merchant_bills` - Merchant billing records
   - `merchant_bill_items` - Individual items in merchant bills (with weight field)
   - `customer_bills` - Customer billing records (with payment status)
   - `customer_bill_items` - Individual items in customer bills (with weight field)
   - `customer_payments` - Payment tracking
   - `bill_edit_logs` - Activity logging

6. **Compile Frontend Assets**
   ```bash
   npm run build
   ```

7. **Start the Development Server**
   ```bash
   php artisan serve
   ```

8. **Access the Application**
   - Open your browser and go to `http://localhost:8000`
   - Register a new account or login

## 📊 Database Structure

### Key Features Included:
- **Complete Billing System**: Merchant bills → Customer bills → Payments
- **Weight Field**: Proper weight tracking for accurate calculations
- **Balance Management**: Customer balance tracking with credit/debit support
- **Payment Tracking**: Complete payment history and status management
- **Activity Logging**: All bill modifications are logged
- **DataTables Integration**: Search, sort, and export functionality

### Migration Files (Clean Setup):
- All fields are included in the initial table creation
- No redundant migrations or field additions
- Proper foreign key relationships
- Optimized for fresh installations

## 🔧 System Features

### Merchant Management
- Add/edit/delete merchants
- Track merchant information and status

### Customer Management
- Add/edit/delete customers
- Balance tracking (total purchased, total paid, current balance)
- Customer type classification

### Product Management
- Add/edit/delete products
- Product type categorization (vegetables, fruits, other)
- Unit tracking (kg, pieces, trays, etc.)

### Billing System
- **Merchant Bills**: Record products supplied by merchants
- **Customer Bills**: Auto-generate customer bills from merchant bills
- **Weight Field**: Accurate weight-based calculations
- **Misc Adjustment**: Handle weight adjustments
- **Net Quantity**: Weight - Misc Adjustment
- **Total Calculation**: Net Quantity × Rate

### Payment System
- Record customer payments
- Track payment methods (cash, bank transfer, cheque, UPI, card, other)
- Real-time balance updates
- Payment history and status tracking

### Reporting & Export
- DataTables integration for all lists
- Excel and PDF export functionality
- Search and filter capabilities
- Activity logs for audit trails

## 🎯 Ready-to-Use Features

After installation, the system includes:
- ✅ Complete authentication system
- ✅ Professional UI with sidebar navigation
- ✅ Responsive design for all devices
- ✅ DataTables with search, sort, and export
- ✅ PDF invoice generation
- ✅ Real-time balance calculations
- ✅ Comprehensive error handling
- ✅ Activity logging system

## 🔍 Testing

The system has been thoroughly tested with:
- Multiple merchant bills for the same customer
- Complex payment scenarios
- Balance calculation accuracy
- DataTables functionality
- PDF generation
- Error handling

## 📝 Notes

- All migrations are optimized for fresh installations
- No manual database modifications required
- All features are production-ready
- Comprehensive error handling included
- Professional UI/UX design

## 🆘 Support

If you encounter any issues during installation:
1. Check PHP version compatibility
2. Verify database connection settings
3. Ensure all dependencies are installed
4. Check Laravel logs in `storage/logs/`

The system is designed to work out-of-the-box with minimal configuration required.






