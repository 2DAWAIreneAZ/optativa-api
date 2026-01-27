<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create New Product</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf

                        {{-- PASO 1: SELECCIONAR CATEGORÍA --}}
                        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500">
                            <p class="font-semibold text-blue-800">📦 Step 1: Select a category to see API products</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Style / Category *</label>
                            <select name="id_style" 
                                    id="productStyle"
                                    class="shadow border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('id_style') border-red-500 @enderror" 
                                    required>
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

                        {{-- PASO 2: SELECCIONAR PRODUCTO DE LA API (aparece después de elegir categoría) --}}
                        <div id="apiProductsSection" class="mb-6 hidden">
                            <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500">
                                <p class="font-semibold text-green-800">✅ Step 2: Choose a product from API (optional)</p>
                                <p class="text-sm text-green-700 mt-1">Select a product to auto-fill data, or leave empty to enter manually</p>
                            </div>

                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Products from API 
                                <span class="text-xs font-normal text-gray-500">(Optional)</span>
                            </label>
                            <select id="apiProductSelect" 
                                    class="shadow border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">-- Select a product from API or enter manually --</option>
                            </select>
                            
                            <div id="loadingProducts" class="hidden mt-2 text-blue-600 text-sm">
                                <svg class="animate-spin inline h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading products from API...
                            </div>
                        </div>

                        {{-- PASO 3: DATOS DEL PRODUCTO --}}
                        <div class="mb-6 p-4 bg-purple-50 border-l-4 border-purple-500">
                            <p class="font-semibold text-purple-800">✏️ Step 3: Product details</p>
                            <p class="text-sm text-purple-700 mt-1">Edit any field - your values have priority!</p>
                        </div>

                        <input type="hidden" name="api_image_url" id="apiImageUrl">

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Product Name *</label>
                            <input type="text" 
                                   name="name" 
                                   id="productName"
                                   value="{{ old('name') }}" 
                                   class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" 
                                   placeholder="Enter product name" 
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Price ($) *</label>
                                <input type="number" 
                                       step="0.01" 
                                       name="price" 
                                       id="productPrice"
                                       value="{{ old('price') }}" 
                                       class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror" 
                                       placeholder="0.00" 
                                       required>
                                @error('price')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Stock *</label>
                                <input type="number" 
                                       name="stock" 
                                       id="productStock"
                                       value="{{ old('stock', 10) }}" 
                                       class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('stock') border-red-500 @enderror" 
                                       placeholder="10" 
                                       required>
                                @error('stock')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Description *</label>
                            <textarea name="description" 
                                      id="productDescription"
                                      rows="5" 
                                      class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" 
                                      placeholder="Enter product description..." 
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Product Image (Optional)</label>
                            <input type="file" 
                                   name="image" 
                                   id="imageInput"
                                   accept="image/*" 
                                   class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('image') border-red-500 @enderror"
                                   onchange="previewImage(event)">
                            <p class="text-gray-600 text-xs mt-1">
                                Upload your own image (overrides API image) - JPEG, PNG, GIF, max 2MB
                            </p>
                            @error('image')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                            
                            <div id="imagePreview" class="mt-3 hidden">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Image Preview:</p>
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
        const styleSelect = document.getElementById('productStyle');
        const apiProductsSection = document.getElementById('apiProductsSection');
        const apiProductSelect = document.getElementById('apiProductSelect');
        const loadingProducts = document.getElementById('loadingProducts');

        let apiProductsData = [];

        // Cuando cambia la categoría, cargar productos de la API
        styleSelect.addEventListener('change', async function() {
            const styleId = this.value;
            
            if (!styleId) {
                apiProductsSection.classList.add('hidden');
                return;
            }

            // Mostrar sección y loading
            apiProductsSection.classList.remove('hidden');
            loadingProducts.classList.remove('hidden');
            apiProductSelect.innerHTML = '<option value="">-- Loading products... --</option>';

            try {
                const response = await fetch(`{{ route('products.getApiProducts') }}?style_id=${styleId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                loadingProducts.classList.add('hidden');

                if (result.success && result.products.length > 0) {
                    apiProductsData = result.products;
                    
                    // Llenar el select con los productos
                    apiProductSelect.innerHTML = '<option value="">-- Select a product from API or enter manually --</option>';
                    
                    result.products.forEach((product, index) => {
                        const option = document.createElement('option');
                        option.value = index;
                        option.textContent = `${product.title} - $${product.price}`;
                        apiProductSelect.appendChild(option);
                    });
                } else {
                    apiProductSelect.innerHTML = '<option value="">-- No API products available for this category --</option>';
                    apiProductsData = [];
                }
            } catch (error) {
                loadingProducts.classList.add('hidden');
                apiProductSelect.innerHTML = '<option value="">-- Error loading products --</option>';
                console.error('Error:', error);
            }
        });

        // Cuando selecciona un producto de la API, autocompletar
        apiProductSelect.addEventListener('change', function() {
            const selectedIndex = this.value;
            
            if (selectedIndex === '' || !apiProductsData[selectedIndex]) {
                return;
            }

            const product = apiProductsData[selectedIndex];
            
            // Autocompletar campos
            document.getElementById('productName').value = product.title;
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productDescription').value = product.description;
            document.getElementById('apiImageUrl').value = product.image;
            
            // Mostrar preview de la imagen
            if (product.image) {
                showApiImagePreview(product.image);
            }
        });

        function showApiImagePreview(imageUrl) {
            const preview = document.getElementById('preview');
            const previewDiv = document.getElementById('imagePreview');
            
            preview.src = imageUrl;
            previewDiv.classList.remove('hidden');
        }

        function previewImage(event) {
            const preview = document.getElementById('preview');
            const previewDiv = document.getElementById('imagePreview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewDiv.classList.remove('hidden');
                    // Limpiar URL de API si sube imagen propia
                    document.getElementById('apiImageUrl').value = '';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>