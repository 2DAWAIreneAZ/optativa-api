<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create New Style</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('styles.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Style Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                   class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" 
                                   placeholder="e.g., Electronics, Jewelry, Clothing..." required>
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Difficulty Level *</label>
                            <select name="difficulty" class="shadow border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('difficulty') border-red-500 @enderror" required>
                                <option value="">-- Select Difficulty --</option>
                                <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                            <p class="text-gray-600 text-xs mt-1">Difficulty refers to the complexity of managing products in this category</p>
                            @error('difficulty')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline transition">
                                Create Style
                            </button>
                            <a href="{{ route('styles.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>