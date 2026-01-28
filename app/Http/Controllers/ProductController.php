<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Style;
use App\Models\Valoration;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index(Request $request) {
        $query = Product::with('style', 'valorations');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('style') && $request->style != '') {
            $query->where('id_style', $request->style);
        }

        $products = $query->latest()->paginate(8);
        $styles = Style::all();

        return view('products.index', ['products' => $products, 'styles' => $styles]);
    }

    public function create() {
        $this->authorize('create', Product::class);
        $styles = Style::all();
        return view('products.create', ['styles' => $styles]);
    }

		public function getApiProductsByCategory(Request $request) {
        $this->authorize('create', Product::class);
        
        $styleId = (int) $request->query('style_id');

        // Mapeo de IDs de estilos a IDs de categorías
        $categoryMap = [
            1 => 1,  // Clothes
            2 => 2,  // Electronics
            3 => 3,  // Furniture
            4 => 4,  // Shoes
            5 => 5,  // Miscellaneous
        ];

        if (!isset($categoryMap[$styleId])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid category selected',
                'products' => []
            ]);
        }

        $categoryId = $categoryMap[$styleId];

        try {
            // Obtener productos filtrados por categoría
            $response = Http::timeout(15)
                ->get("https://api.escuelajs.co/api/v1/products", [
                    'categoryId' => $categoryId,
                    'offset' => 0,
                    'limit' => 20 // Limitar a 20 productos
                ]);

            if ($response->successful()) {
                $products = $response->json();

                // Verificar que sea un array y tenga productos
                if (is_array($products) && count($products) > 0) {
                    // Formatear productos para el frontend
                    $formattedProducts = array_map(function($product) {
                        return [
                            'id' => $product['id'] ?? 0,
                            'title' => $product['title'] ?? 'No title',
                            'price' => $product['price'] ?? 0,
                            'description' => $product['description'] ?? 'No description',
                            // Platzi API usa un array de imágenes, tomamos la primera
                            'image' => !empty($product['images']) && is_array($product['images']) 
                                ? $product['images'][0] 
                                : 'https://placehold.co/400x400',
                            'category' => $product['category']['name'] ?? 'Unknown'
                        ];
                    }, $products);

                    return response()->json([
                        'success' => true,
                        'products' => $formattedProducts,
                        'total' => count($formattedProducts)
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'No products found for this category',
                    'products' => []
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error connecting to Platzi API - Status: ' . $response->status(),
                'products' => []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'products' => []
            ]);
        }
    }




    public function store(Request $request) {
        $this->authorize('create', Product::class);

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'id_style' => 'required|exists:styles,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'api_image_url' => 'nullable|url'
        ]);

        // Prioridad: imagen subida > URL de API
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->filled('api_image_url')) {
            $validated['image'] = $request->input('api_image_url');
        }
        
        // Eliminar api_image_url antes de crear el producto
        unset($validated['api_image_url']);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function show(Product $product) {
        $product->load(['style', 'valorations.user']);
        return view('products.show', ['product' => $product]);
    }

    public function edit(Product $product) {
        $this->authorize('update', $product);
        $styles = Style::all();
        return view('products.edit', ['product' => $product, 'styles' => $styles]);
    }

    public function update(Request $request, Product $product) {
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
            // Eliminar imagen anterior solo si es local
            if ($product->image && !str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product) {
        $this->authorize('delete', $product);

        // Eliminar imagen solo si es local
        if ($product->image && !str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    public function buy(Product $product) {
        if (auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Administrators cannot purchase products!');
        }

        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Product out of stock!');
        }

        // Decrementar el stock
        $product->decrement('stock');

        // Registrar la compra (solo si tienes modelo Purchase)
        if (class_exists(\App\Models\Purchase::class)) {
            \App\Models\Purchase::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'price' => $product->price
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Purchase successful! Product: ' . $product->name);
    }

    public function addValoration(Request $request, Product $product) {
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

