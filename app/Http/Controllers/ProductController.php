<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Style;
use App\Models\Valoration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Product::with('style', 'valorations');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('style') && $request->style != '') {
            $query->where('id_style', $request->style);
        }

        $products = $query->latest()->paginate(12);
        $styles = Style::all();

        return view('products.index', compact('products', 'styles'));
    }

    public function create()
    {
        $this->authorize('create', Product::class);
        $styles = Style::all();
        return view('products.create', compact('styles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'id_style' => 'required|exists:styles,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $product->load(['style', 'valorations.user']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $styles = Style::all();
        return view('products.edit', compact('product', 'styles'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'id_style' => 'required|exists:styles,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && !str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->image && !str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function buy(Product $product)
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Administrators cannot purchase products!');
        }

        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Product out of stock!');
        }

        // Decrementar el stock
        $product->decrement('stock');

        // Registrar la compra
        \App\Models\Purchase::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'price' => $product->price
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Purchase successful! Product: ' . $product->name);
    }

    public function addValoration(Request $request, Product $product)
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Administrators cannot add reviews!');
        }

        $validated = $request->validate([
            'puntuation' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        $validated['id_product'] = $product->id;
        $validated['user_id'] = auth()->id();

        Valoration::updateOrCreate(
            [
                'id_product' => $product->id,
                'user_id' => auth()->id()
            ],
            $validated
        );

        return redirect()->back()->with('success', 'Review added successfully!');
    }
}

