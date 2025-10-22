<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required|string|in:vegetables,fruits,other',
            'unit' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required|string|in:vegetables,fruits,other',
            'unit' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }
}