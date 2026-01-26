<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Product Store
        </h2>
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
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                        <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/300' }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-48 object-cover">
                        
                        <div class="p-4">
                            <span class="text-xs text-gray-500">{{ $product->style->name }}</span>
                            <h3 class="font-bold text-lg mb-2">{{ Str::limit($product->name, 40) }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 80) }}</p>
                            
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-2xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                            </div>

                            @php
                                $avgRating = $product->averageRating();
                            @endphp
                            @if($avgRating)
                                <div class="flex items-center mb-3">
                                    <span class="text-yellow-400">★</span>
                                    <span class="ml-1 text-sm">{{ number_format($avgRating, 1) }}</span>
                                </div>
                            @endif

                            <div class="flex gap-2">
                                <a href="{{ route('shop.show', $product) }}" 
                                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-center py-2 rounded">
                                    View
                                </a>
                                <form action="{{ route('shop.buy', $product) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full bg-blue-500 hover:bg-blue-700 text-white py-2 rounded"
                                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                        Buy
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

{{-- resources/views/shop/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Product Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/500' }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full rounded-lg">
                        </div>

                        <div>
                            <span class="text-sm text-gray-500">{{ $product->style->name }} - {{ $product->style->difficulty }}</span>
                            <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
                            <p class="text-gray-700 mb-4">{{ $product->description }}</p>
                            
                            <div class="mb-4">
                                <span class="text-4xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                                <span class="ml-4 text-gray-600">Stock: {{ $product->stock }}</span>
                            </div>

                            @php
                                $avgRating = $product->averageRating();
                            @endphp
                            @if($avgRating)
                                <div class="flex items-center mb-6">
                                    <span class="text-yellow-400 text-2xl">★</span>
                                    <span class="ml-2 text-xl">{{ number_format($avgRating, 1) }}</span>
                                    <span class="ml-2 text-gray-500">({{ $product->valorations->count() }} reviews)</span>
                                </div>
                            @endif

                            <form action="{{ route('shop.buy', $product) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded text-lg"
                                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    {{ $product->stock > 0 ? 'Buy Now' : 'Out of Stock' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-8 border-t pt-8">
                        <h2 class="text-2xl font-bold mb-4">Add Your Review</h2>
                        <form action="{{ route('shop.valoration', $product) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Rating</label>
                                <select name="puntuation" class="shadow border rounded w-full py-2 px-3" required>
                                    <option value="5">★★★★★ (5)</option>
                                    <option value="4">★★★★☆ (4)</option>
                                    <option value="3">★★★☆☆ (3)</option>
                                    <option value="2">★★☆☆☆ (2)</option>
                                    <option value="1">★☆☆☆☆ (1)</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Comment</label>
                                <textarea name="comment" rows="3" 
                                          class="shadow border rounded w-full py-2 px-3"></textarea>
                            </div>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Submit Review
                            </button>
                        </form>
                    </div>

                    @if($product->valorations->count() > 0)
                        <div class="mt-8 border-t pt-8">
                            <h2 class="text-2xl font-bold mb-4">Customer Reviews</h2>
                            @foreach($product->valorations as $valoration)
                                <div class="mb-4 p-4 bg-gray-50 rounded">
                                    <div class="flex items-center mb-2">
                                        <span class="font-bold">{{ $valoration->user->name }}</span>
                                        <span class="ml-4 text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                {{ $i <= $valoration->puntuation ? '★' : '☆' }}
                                            @endfor
                                        </span>
                                    </div>
                                    @if($valoration->comment)
                                        <p class="text-gray-700">{{ $valoration->comment }}</p>
                                    @endif
                                    <span class="text-sm text-gray-500">{{ $valoration->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>