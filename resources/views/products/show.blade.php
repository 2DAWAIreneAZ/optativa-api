<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product Details</h2>
            <a href="{{ route('products.index') }}" class="text-blue-500 hover:text-blue-700">← Back to Products</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/500' }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full rounded-lg shadow-lg">
												</div>

                        <div>
                            <span class="inline-block text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded mb-2">
                                {{ $product->style->name }} - {{ ucfirst($product->style->difficulty) }}
                            </span>
                            <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
                            <p class="text-gray-700 mb-6 leading-relaxed">{{ $product->description }}</p>
                            
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                                <div class="mt-2">
                                    <span class="text-lg {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                        {{ $product->stock > 0 ? "In Stock: {$product->stock} units" : 'Out of Stock' }}
                                    </span>
                                </div>
                            </div>

                            @php
                                $avgRating = $product->averageRating();
                            @endphp
                            @if($avgRating)
                                <div class="flex items-center mb-6 p-4 bg-yellow-50 rounded">
                                    <span class="text-yellow-400 text-3xl">★</span>
                                    <span class="ml-2 text-2xl font-bold">{{ number_format($avgRating, 1) }}</span>
                                    <span class="ml-2 text-gray-600">({{ $product->valorations->count() }} reviews)</span>
                                </div>
                            @endif

                            <div class="space-y-3">
                                @if(!Auth::user()->isAdmin())
                                    <form action="{{ route('products.buy', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg transition"
                                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                            {{ $product->stock > 0 ? 'Buy Now' : 'Out of Stock' }}
                                        </button>
                                    </form>
                                @endif

                                @if(Auth::user()->isAdmin())
                                    <div class="flex gap-2">
                                        <a href="{{ route('products.edit', $product) }}" 
                                           class="flex-1 bg-yellow-500 font-bold py-3 px-6 rounded-lg text-center" style="background-color:yellow">
                                            Edit Product
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full bg-red-500 font-bold py-3 px-6 rounded-lg" style="background-color:red"
                                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(!Auth::user()->isAdmin())
                        <div class="mt-8 border-t pt-8">
                            <h2 class="text-2xl font-bold mb-4">Add Your Review</h2>
                            <form action="{{ route('products.valoration', $product) }}" method="POST" class="bg-gray-50 p-6 rounded-lg">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Rating</label>
                                    <select name="puntuation" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                        <option value="5">★★★★★ Excellent (5)</option>
                                        <option value="4">★★★★☆ Very Good (4)</option>
                                        <option value="3">★★★☆☆ Good (3)</option>
                                        <option value="2">★★☆☆☆ Meh (2)</option>
                                        <option value="1">★☆☆☆☆ Poor (1)</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Comment</label>
                                    <textarea name="comment" rows="3" 
                                              class="shadow border rounded w-full py-2 px-3 text-gray-700"
                                              placeholder="Share your experience with this product..."></textarea>
                                </div>
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($product->valorations->count() > 0)
                        <div class="mt-8 border-t pt-8">
                            <h2 class="text-2xl font-bold mb-4">Customer Reviews ({{ $product->valorations->count() }})</h2>
                            <div class="space-y-4">
                                @foreach($product->valorations as $valoration)
                                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center">
                                                <span class="font-bold text-gray-800">{{ $valoration->user->name }}</span>
                                                <span class="ml-4 text-yellow-400 text-lg">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        {{ $i <= $valoration->puntuation ? '★' : '☆' }}
                                                    @endfor
                                                </span>
                                            </div>
                                            @if(Auth::user()->isAdmin() || Auth::id() == $valoration->user_id)
                                                <form action="{{ route('valorations.destroy', $valoration) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm"
                                                            onclick="return confirm('Delete this review?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        @if($valoration->comment)
                                            <p class="text-gray-700 mb-2">{{ $valoration->comment }}</p>
                                        @endif
                                        <span class="text-sm text-gray-500">{{ $valoration->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>