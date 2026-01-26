<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create New Product</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Product Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                   class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" 
                                   placeholder="Enter product name" required>
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Style / Category *</label>
                            <select name="id_style" class="shadow border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('id_style') border-red-500 @enderror" required>
                                <option value="">-- Select a Style --</option>
                                @foreach($styles as $style)
                                    <option value="{{ $style->id }}" {{ old('id_style') == $style->id ? 'selected' : '' }}>
                                        {{ $style->name }} ({{ ucfirst($style->difficulty) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_style')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Price ($) *</label>
                                <input type="number" step="0.01" name="price" value="{{ old('price') }}" 
                                       class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror" 
                                       placeholder="0.00" required>
                                @error('price')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Stock *</label>
                                <input type="number" name="stock" value="{{ old('stock', 10) }}" 
                                       class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('stock') border-red-500 @enderror" 
                                       placeholder="0" required>
                                @error('stock')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Description *</label>
                            <textarea name="description" rows="5" 
                                      class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" 
                                      placeholder="Enter product description..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Product Image</label>
                            <input type="file" name="image" accept="image/*" 
                                   class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('image') border-red-500 @enderror"
                                   onchange="previewImage(event)">
                            <p class="text-gray-600 text-xs mt-1">Optional: Upload an image (JPEG, PNG, GIF, max 2MB)</p>
                            @error('image')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                            <div id="imagePreview" class="mt-3 hidden">
                                <img id="preview" class="rounded-lg shadow-md max-h-64">
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline transition">
                                Create Product
                            </button>
                            <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const previewDiv = document.getElementById('imagePreview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewDiv.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>