<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}! 👋</h3>
                    
                    @if(Auth::user()->isAdmin())
                        <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                            <p class="text-purple-800 font-semibold">🔧 Administrator Mode</p>
                            <p class="text-purple-600 text-sm">You have full access to manage products, styles, and all system features.</p>
                        </div>
                        
                        <div class="grid md:grid-cols-3 gap-6">
                            <a href="{{ route('products.index') }}" 
                               class="block p-6 bg-blue-100 rounded-lg hover:bg-blue-200 transition">
                                <h4 class="text-xl font-bold mb-2">📦 Manage Products</h4>
                                <p class="text-gray-700">View, create, edit, and delete products</p>
                            </a>
                            
                            <a href="{{ route('styles.index') }}" 
                               class="block p-6 bg-green-100 rounded-lg hover:bg-green-200 transition">
                                <h4 class="text-xl font-bold mb-2">🎨 Manage Styles</h4>
                                <p class="text-gray-700">Organize products into categories</p>
                            </a>
                            
                            <a href="{{ route('profile.show') }}" 
                               class="block p-6 bg-purple-100 rounded-lg hover:bg-purple-200 transition">
                                <h4 class="text-xl font-bold mb-2">👤 My Profile</h4>
                                <p class="text-gray-700">View your products and statistics</p>
                            </a>
                        </div>
                    @else
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-blue-800 font-semibold">🛍️ Customer Account</p>
                            <p class="text-blue-600 text-sm">Browse and purchase amazing products!</p>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <a href="{{ route('products.index') }}" 
                               class="block p-6 bg-green-100 rounded-lg hover:bg-green-200 transition">
                                <h4 class="text-xl font-bold mb-2">🛒 Shop Products</h4>
                                <p class="text-gray-700">Browse our catalog and make purchases</p>
                            </a>
                            
                            <a href="{{ route('profile.show') }}" 
                               class="block p-6 bg-blue-100 rounded-lg hover:bg-blue-200 transition">
                                <h4 class="text-xl font-bold mb-2">👤 My Profile</h4>
                                <p class="text-gray-700">View your purchases and reviews</p>
                            </a>
                        </div>
                    @endif

                    <div class="mt-8 p-4 bg-gray-100 rounded-lg">
                        <p class="text-sm text-gray-600">
                            <strong>Account Type:</strong> 
                            <span class="ml-2 px-3 py-1 rounded-full text-xs font-semibold
                                {{ Auth::user()->isAdmin() ? 'bg-purple-200 text-purple-800' : 'bg-blue-200 text-blue-800' }}">
                                {{ Auth::user()->isAdmin() ? 'Administrator' : 'Customer' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
