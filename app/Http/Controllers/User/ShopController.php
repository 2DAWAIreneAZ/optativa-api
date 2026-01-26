<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Style;
use App\Models\Valoration;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('style')->where('stock', '>', 0);

        if ($request->has('style') && $request->style != '') {
            $query->where('id_style', $request->style);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12);
        $styles = Style::all();

        return view('shop.index', compact('products', 'styles'));
    }

    public function show(Product $product)
    {
        $product->load(['style', 'valorations.user']);
        return view('shop.show', compact('product'));
    }

    public function addValoration(Request $request, Product $product)
    {
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

    public function buy(Product $product)
    {
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Product out of stock!');
        }

        $product->decrement('stock');

        return redirect()->route('shop.index')
            ->with('success', 'Purchase successful! Product: ' . $product->name);
    }
}