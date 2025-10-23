<?php

namespace App\Http\Controllers;

use App\Models\CustomerPayment;
use App\Models\Customer;
use App\Models\CustomerBill;
use App\Services\BillEditLogService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class CustomerPaymentsController extends Controller
{
    /**
     * Display a listing of customer payments.
     */
    public function index(): View
    {
        $payments = CustomerPayment::with(['customer', 'customerBill'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('customer-payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(): View
    {
        $customers = Customer::where('is_active', true)
            ->where('balance', '>', 0)
            ->orderBy('name')
            ->get();
        
        $customerBills = CustomerBill::whereIn('payment_status', ['pending', 'partial'])
            ->with('customer')
            ->orderBy('bill_date', 'desc')
            ->get();
        
        $paymentMethods = CustomerPayment::getPaymentMethods();
        
        return view('customer-payments.create', compact('customers', 'customerBills', 'paymentMethods'));
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'customer_bill_id' => 'nullable|exists:customer_bills,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,upi,card,other',
            'notes' => 'nullable|string',
            'payment_date' => 'required|date',
        ]);

        // Set default values
        $validated['reference_number'] = null;
        $validated['status'] = 'completed'; // All payments are completed by default

        DB::transaction(function () use ($validated) {
            // Create the payment
            $payment = CustomerPayment::create($validated);

            // Update customer balance if payment is completed
            if ($validated['status'] === 'completed') {
                $customer = Customer::find($validated['customer_id']);
                $customer->updateBalance($validated['amount'], 'payment');

                // Update customer bill payment status if linked
                if ($validated['customer_bill_id']) {
                    $customerBill = CustomerBill::find($validated['customer_bill_id']);
                    $customerBill->updatePaymentStatus();
                }
            }

            // Log the payment creation
            BillEditLogService::logCustomerPaymentCreated($payment->id, [
                'customer_id' => $payment->customer_id,
                'customer_bill_id' => $payment->customer_bill_id,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'payment_date' => $payment->payment_date->format('Y-m-d'),
                'status' => $payment->status,
            ]);
        });

        return redirect()->route('customer-payments.index')
            ->with('success', 'Payment recorded successfully!');
    }

    /**
     * Display the specified payment.
     */
    public function show(CustomerPayment $customerPayment): View
    {
        $customerPayment->load(['customer', 'customerBill']);
        return view('customer-payments.show', compact('customerPayment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(CustomerPayment $customerPayment): View
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $customerBills = CustomerBill::with('customer')->orderBy('bill_date', 'desc')->get();
        $paymentMethods = CustomerPayment::getPaymentMethods();
        $statusOptions = CustomerPayment::getStatusOptions();
        
        return view('customer-payments.edit', compact('customerPayment', 'customers', 'customerBills', 'paymentMethods', 'statusOptions'));
    }

    /**
     * Update the specified payment.
     */
    public function update(Request $request, CustomerPayment $customerPayment): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'customer_bill_id' => 'nullable|exists:customer_bills,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,upi,card,other',
            'notes' => 'nullable|string',
            'payment_date' => 'required|date',
        ]);

        // Set default values
        $validated['reference_number'] = null;
        $validated['status'] = 'completed'; // All payments are completed by default

        DB::transaction(function () use ($validated, $customerPayment) {
            // Store old data for logging
            $oldData = $customerPayment->toArray();

            // Update the payment
            $customerPayment->update($validated);

            // Handle balance updates based on status changes
            $customer = Customer::find($validated['customer_id']);
            
            if ($oldData['status'] === 'completed' && $validated['status'] !== 'completed') {
                // Revert the old payment
                $customer->updateBalance($oldData['amount'], 'payment');
            } elseif ($oldData['status'] !== 'completed' && $validated['status'] === 'completed') {
                // Apply the new payment
                $customer->updateBalance($validated['amount'], 'payment');
            } elseif ($oldData['status'] === 'completed' && $validated['status'] === 'completed' && $oldData['amount'] != $validated['amount']) {
                // Adjust the difference
                $difference = $validated['amount'] - $oldData['amount'];
                $customer->updateBalance($difference, 'payment');
            }

            // Update customer bill payment status if linked
            if ($validated['customer_bill_id']) {
                $customerBill = CustomerBill::find($validated['customer_bill_id']);
                $customerBill->updatePaymentStatus();
            }

            // Log the update
            $changesSummary = BillEditLogService::generateChangesSummary($oldData, $customerPayment->toArray());
            BillEditLogService::logCustomerPaymentUpdated($customerPayment->id, $oldData, $customerPayment->toArray(), $changesSummary);
        });

        return redirect()->route('customer-payments.index')
            ->with('success', 'Payment updated successfully!');
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(CustomerPayment $customerPayment): RedirectResponse
    {
        DB::transaction(function () use ($customerPayment) {
            // Store data for logging before deletion
            $paymentData = $customerPayment->toArray();

            // Revert customer balance if payment was completed
            if ($customerPayment->status === 'completed') {
                $customer = $customerPayment->customer;
                $customer->updateBalance($customerPayment->amount, 'payment');
            }

            // Update customer bill payment status if linked
            if ($customerPayment->customer_bill_id) {
                $customerBill = CustomerBill::find($customerPayment->customer_bill_id);
                $customerBill->updatePaymentStatus();
            }

            $customerPayment->delete();

            // Log the deletion
            BillEditLogService::logCustomerPaymentDeleted($customerPayment->id, $paymentData);
        });

        return redirect()->route('customer-payments.index')
            ->with('success', 'Payment deleted successfully!');
    }

    /**
     * Get customer bills for AJAX request.
     */
    public function getCustomerBills(Request $request)
    {
        $customerId = $request->input('customer_id');
        
        if (!$customerId) {
            return response()->json(['error' => 'Customer ID is required'], 400);
        }
        
        $bills = CustomerBill::where('customer_id', $customerId)
            ->whereIn('payment_status', ['pending', 'partial'])
            ->orderBy('bill_date', 'desc')
            ->get()
            ->map(function ($bill) {
                return [
                    'id' => $bill->id,
                    'bill_number' => '#' . $bill->id,
                    'bill_date' => $bill->bill_date->format('Y-m-d'),
                    'total_amount' => $bill->total_amount,
                    'paid_amount' => $bill->paid_amount,
                    'remaining_amount' => $bill->getRemainingAmount(),
                    'payment_status' => $bill->payment_status,
                ];
            });

        return response()->json([
            'success' => true,
            'bills' => $bills
        ]);
    }

    /**
     * Get fresh customer balance for AJAX request.
     */
    public function getCustomerBalance($customerId)
    {
        $customer = Customer::find($customerId);
        
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }
        
        // Refresh customer data from database
        $customer->refresh();
        
        return response()->json([
            'success' => true,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'balance' => $customer->balance,
                'total_purchased' => $customer->total_purchased,
                'total_paid' => $customer->total_paid,
            ]
        ]);
    }
}
