<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Product</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Product Name *</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                                   class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700" required>
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Style / Category *</label>
                            <select name="id_style" class="shadow border rounded w-full py-3 px-4 text-gray-700" required>
                                @foreach($styles as $style)
                                    <option value="{{ $style->id }}" {{ $product->id_style == $style->id ? 'selected' : '' }}>
                                        {{ $style->name }} ({{ ucfirst($style->difficulty) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_style')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Price ($) *</label>
                                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" 
                                       class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700" required>
                                @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Stock *</label>
                                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" 
                                       class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700" required>
                                @error('stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Description *</label>
                            <textarea name="description" rows="5" class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Product Image</label>
                            @if($product->image)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                    <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" 
                                         class="rounded-lg shadow-md max-h-48">
                                </div>
                            @endif
                            <input type="file" name="image" accept="image/*" class="shadow border rounded w-full py-3 px-4 text-gray-700">
                            <p class="text-gray-600 text-xs mt-1">Leave empty to keep current image</p>
                            @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                                Update Product
                            </button>
                            <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>