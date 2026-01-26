<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Style</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('styles.update', $style) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Style Name *</label>
                            <input type="text" name="name" value="{{ old('name', $style->name) }}" 
                                   class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700" required>
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Difficulty Level *</label>
                            <select name="difficulty" class="shadow border rounded w-full py-3 px-4 text-gray-700" required>
                                <option value="easy" {{ $style->difficulty == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ $style->difficulty == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ $style->difficulty == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                            @error('difficulty')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                                Update Style
                            </button>
                            <a href="{{ route('styles.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>