<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product Styles</h2>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('styles.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Style
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($styles as $style)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-2xl font-bold text-gray-800">{{ $style->name }}</h3>
                                <span class="text-3xl font-bold text-blue-600">{{ $style->products_count }}</span>
                            </div>
                            
                            <div class="mb-4">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                    @if($style->difficulty === 'easy') bg-green-100 text-green-800
                                    @elseif($style->difficulty === 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($style->difficulty) }}
                                </span>
                            </div>

                            <p class="text-gray-600 mb-4">
                                {{ $style->products_count }} {{ $style->products_count == 1 ? 'product' : 'products' }} in this category
                            </p>

                            <div class="flex gap-2">
                                <a href="{{ route('products.index', ['style' => $style->id]) }}" 
                                   class="flex-1 bg-blue-500 hover:bg-blue-700 text-white text-center py-2 rounded font-semibold">
                                    View Products
                                </a>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('styles.edit', $style) }}" 
                                       class="bg-yellow-500 hover:bg-yellow-700 text-white px-4 py-2 rounded">
                                        Edit
                                    </a>
                                    <form action="{{ route('styles.destroy', $style) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded"
                                                onclick="return confirm('Delete this style? All associated products will be deleted too!')">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($styles->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No styles available yet.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>