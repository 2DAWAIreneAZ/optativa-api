<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Products</h2>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Product
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 flex gap-4">
                <form method="GET" class="flex gap-4 w-full">
                    <input type="text" name="search" placeholder="Search products..." 
                           value="{{ request('search') }}"
                           class="flex-1 rounded-md border-gray-300 shadow-sm">
                    
                    <select name="style" class="rounded-md border-gray-300 shadow-sm">
                        <option value="">All Styles</option>
                        @foreach($styles as $style)
                            <option value="{{ $style->id }}" {{ request('style') == $style->id ? 'selected' : '' }}>
                                {{ $style->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded">
                        Filter
                    </button>
                    <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-6 py-2 rounded">
                        Clear
                    </a>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                        <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/300' }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-48 object-cover">
                        
                        <div class="p-4">
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $product->style->name }}</span>
                            <h3 class="font-bold text-lg mb-2 mt-2">{{ Str::limit($product->name, 40) }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 80) }}</p>
                            
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-2xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Stock: {{ $product->stock }}
                                </span>
                            </div>

                            @php
                                $avgRating = $product->averageRating();
                            @endphp
                            @if($avgRating)
                                <div class="flex items-center mb-3">
                                    <span class="text-yellow-400">★</span>
                                    <span class="ml-1 text-sm">{{ number_format($avgRating, 1) }}</span>
                                    <span class="text-xs text-gray-500 ml-1">({{ $product->valorations->count() }})</span>
                                </div>
                            @endif

                            <div class="flex gap-2">
                                <a href="{{ route('products.show', $product) }}" 
                                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-center py-2 rounded font-semibold">
                                    View Details
                                </a>
                                @if(!Auth::user()->isAdmin())
                                    <form action="{{ route('products.buy', $product) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-blue-500 hover:bg-blue-700 text-white py-2 rounded font-semibold"
                                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                            {{ $product->stock > 0 ? 'Buy' : 'Out' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No products found.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>