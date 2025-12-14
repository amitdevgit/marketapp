<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $merchants = Merchant::orderBy('created_at', 'desc')->get();
        return view('merchants.index', compact('merchants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('merchants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gst_number' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        // Only include fields that are provided, let database defaults handle the rest
        $dataToCreate = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            // Provide empty strings for required fields (address, business_name) that are not in the form
            // so they don't get set to null
            'address' => '',
            'business_name' => '',
        ];

        // Only add gst_number if provided
        if (isset($validated['gst_number']) && !empty($validated['gst_number'])) {
            $dataToCreate['gst_number'] = $validated['gst_number'];
        }

        // Only add notes if provided
        if (isset($validated['notes']) && !empty($validated['notes'])) {
            $dataToCreate['notes'] = $validated['notes'];
        }

        // is_active will use database default (true) if not provided
        $dataToCreate['is_active'] = $request->has('is_active');

        // Other fields (email) will remain null if not provided (it's nullable in DB)

        Merchant::create($dataToCreate);

        return redirect()->route('merchants.index')
            ->with('success', 'Merchant added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Merchant $merchant): View
    {
        return view('merchants.show', compact('merchant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Merchant $merchant): View
    {
        return view('merchants.edit', compact('merchant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Merchant $merchant): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:merchants,email,' . $merchant->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'business_name' => 'required|string|max:255',
            'gst_number' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $merchant->update($validated);

        return redirect()->route('merchants.index')
            ->with('success', 'Merchant updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Merchant $merchant): RedirectResponse
    {
        $merchant->delete();

        return redirect()->route('merchants.index')
            ->with('success', 'Merchant deleted successfully!');
    }
}