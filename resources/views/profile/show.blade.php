<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Profile</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- INFORMACIÓN DEL USUARIO --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4">Account Information</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600 text-sm">Name</p>
                            <p class="font-semibold text-lg">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Email</p>
                            <p class="font-semibold text-lg">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Account Type</p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $user->isAdmin() ? 'Administrator' : 'Customer' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Member Since</p>
                            <p class="font-semibold text-lg">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PRODUCTOS COMPRADOS (Solo para usuarios normales) --}}
            @if(!$user->isAdmin())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold mb-4">My Purchases</h3>
                        @if($purchases->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purchase Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($purchases as $purchase)
                                            <tr>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <img src="{{ $purchase->product->image ? (str_starts_with($purchase->product->image, 'http') ? $purchase->product->image : asset('storage/' . $purchase->product->image)) : 'https://via.placeholder.com/50' }}" 
                                                             class="h-12 w-12 rounded object-cover mr-3">
                                                        <span class="font-medium">{{ Str::limit($purchase->product->name, 40) }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">${{ number_format($purchase->price, 2) }}</td>
                                                <td class="px-6 py-4">{{ $purchase->created_at->format('M d, Y') }}</td>
                                                <td class="px-6 py-4">
                                                    <a href="{{ route('products.show', $purchase->product) }}" 
                                                       class="text-blue-600 hover:text-blue-900">View Product</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-gray-600">
                                <p class="font-semibold">Total Purchases: {{ $purchases->count() }}</p>
                                <p class="font-semibold">Total Spent: ${{ number_format($purchases->sum('price'), 2) }}</p>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 mb-4">You haven't made any purchases yet.</p>
                                <a href="{{ route('products.index') }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Start Shopping
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- PRODUCTOS EN VENTA (Solo para administradores) --}}
            @if($user->isAdmin())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-2xl font-bold">Products for Sale</h3>
                            <a href="{{ route('products.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add New Product
                            </a>
                        </div>
                        
                        @if($productsForSale->count() > 0)
                            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($productsForSale as $product)
                                    <div class="border rounded-lg p-4 hover:shadow-lg transition">
                                        <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/200' }}" 
                                             class="w-full h-32 object-cover rounded mb-3">
                                        <h4 class="font-bold mb-2">{{ Str::limit($product->name, 30) }}</h4>
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-lg font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                                            <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                Stock: {{ $product->stock }}
                                            </span>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('products.show', $product) }}" 
                                               class="flex-1 bg-gray-200 hover:bg-gray-300 text-center py-1 rounded text-sm">
                                                View
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" 
                                               class="flex-1 bg-yellow-500 hover:bg-yellow-700 text-white text-center py-1 rounded text-sm">
                                                Edit
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-gray-600">
                                <p class="font-semibold">Total Products: {{ $productsForSale->count() }}</p>
                                <p class="font-semibold">Total Inventory Value: ${{ number_format($productsForSale->sum(function($p) { return $p->price * $p->stock; }), 2) }}</p>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 mb-4">You don't have any products for sale yet.</p>
                                <a href="{{ route('products.create') }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Create First Product
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- MIS VALORACIONES --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4">My Reviews</h3>
                    @if($valorations->count() > 0)
                        <div class="space-y-4">
                            @foreach($valorations as $valoration)
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <a href="{{ route('products.show', $valoration->product) }}" 
                                               class="font-bold text-blue-600 hover:text-blue-800">
                                                {{ $valoration->product->name }}
                                            </a>
                                            <div class="text-yellow-400 text-lg">
                                                @for($i = 1; $i <= 5; $i++)
                                                    {{ $i <= $valoration->puntuation ? '★' : '☆' }}
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $valoration->created_at->format('M d, Y') }}</span>
                                    </div>
                                    @if($valoration->comment)
                                        <p class="text-gray-700 mb-2">{{ $valoration->comment }}</p>
                                    @endif
                                    <form action="{{ route('valorations.destroy', $valoration) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm"
                                                onclick="return confirm('Delete this review?')">
                                            Delete Review
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <p class="text-gray-600 font-semibold">Total Reviews: {{ $valorations->count() }}</p>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">You haven't written any reviews yet.</p>
                            <a href="{{ route('products.index') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Browse Products
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>