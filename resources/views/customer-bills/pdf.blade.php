<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Bill #{{ $customerBill->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }
        
        .header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #059669;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #059669;
            text-align: center;
            margin-bottom: 0;
        }
        
        .bill-details {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            gap: 20px;
            width: 100%;
        }
        
        .bill-from {
            flex: 1;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-right: 10px;
            order: 1;
        }
        
        .bill-to {
            flex: 1;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-left: 10px;
            order: 2;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .bill-details {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
            width: 100%;
            gap: 20px;
            clear: both;
            overflow: hidden;
        }
        
        /* Fallback for older browsers/PDF engines */
        .bill-details:after {
            content: "";
            display: table;
            clear: both;
        }
        
        .bill-from {
            flex: 0 0 49%;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #059669;
            float: left;
            width: 49%;
            box-sizing: border-box;
        }
        
        .bill-to {
            flex: 0 0 49%;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #059669;
            float: right;
            width: 49%;
            box-sizing: border-box;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #059669;
            padding-bottom: 5px;
        }
        
        .bill-info {
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
        }
        
        .items-table th {
            background: #059669;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e9ecef;
            font-size: 11px;
        }
        
        .items-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        
        .total-table {
            width: 300px;
            border-collapse: collapse;
        }
        
        .total-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e9ecef;
            font-size: 12px;
        }
        
        .total-table .total-label {
            font-weight: bold;
            background: #f8f9fa;
        }
        
        .total-table .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #059669;
            background: #e8f5e8;
        }
        
        .notes-section {
            margin-top: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .notes-title {
            font-size: 12px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 8px;
        }
        
        .notes-content {
            font-size: 11px;
            color: #555;
            line-height: 1.4;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .invoice-container {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="invoice-title">INVOICE</div>
        </div>

        <!-- Bill Details -->
        <div class="bill-details">
            <div class="bill-from">
                <div class="section-title">From</div>
                <div class="bill-info">
                    <strong>{{ $user->name ?? 'Business Owner' }}</strong><br>
                    {{ $user->email ?? 'business@veggiemarket.com' }}<br>
                    Phone: {{ $user->phone ?? '+91 9876543210' }}<br>
                    Address: {{ $user->address ?? '123 Business Street, City' }}<br>
                    <strong>Bill #{{ $customerBill->id }}</strong> | Date: {{ $customerBill->bill_date->format('M d, Y') }}
                </div>
            </div>
            <div class="bill-to">
                <div class="section-title">To</div>
                <div class="bill-info">
                    <strong>{{ $customerBill->customer->name }}</strong><br>
                    Phone: {{ $customerBill->customer->phone }}<br>
                    Address: {{ $customerBill->customer->address }}<br>
                    Type: {{ ucfirst($customerBill->customer->customer_type) }}
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 25%;">Product</th>
                    <th style="width: 10%;">Unit</th>
                    <th style="width: 10%;" class="text-right">Quantity</th>
                    <th style="width: 10%;" class="text-right">Badi</th>
                    <th style="width: 10%;" class="text-right">Net Qty</th>
                    <th style="width: 10%;" class="text-right">Rate (Rs)</th>
                    <th style="width: 15%;" class="text-right">Total (Rs)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customerBill->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->product->name }}</strong>
                    </td>
                    <td>{{ $item->product->unit }}</td>
                    <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                    <td class="text-right">{{ number_format($item->misc_adjustment, 2) }}</td>
                    <td class="text-right">{{ number_format($item->net_quantity, 2) }}</td>
                    <td class="text-right">Rs {{ number_format($item->rate, 2) }}</td>
                    <td class="text-right">Rs {{ number_format($item->total_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            <table class="total-table">
                <tr>
                    <td class="total-label">Subtotal:</td>
                    <td class="text-right">Rs {{ number_format($customerBill->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="total-label">Tax (0%):</td>
                    <td class="text-right">Rs 0.00</td>
                </tr>
                <tr>
                    <td class="total-label">Discount:</td>
                    <td class="text-right">Rs 0.00</td>
                </tr>
                <tr class="grand-total">
                    <td>Total Amount:</td>
                    <td class="text-right">Rs {{ number_format($customerBill->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Notes Section -->
        @if($customerBill->notes)
        <div class="notes-section">
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $customerBill->notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div>Thank you for your business!</div>
            <div>Generated on {{ now()->format('M d, Y \a\t h:i A') }}</div>
        </div>
    </div>
</body>
</html>
