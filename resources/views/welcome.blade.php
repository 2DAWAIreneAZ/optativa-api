<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Store</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-800">Product Store</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Log in</a>
                            <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1 flex items-center justify-center">
            <div class="text-center">
                <h2 class="text-5xl font-bold text-gray-800 mb-4">Welcome to Product Store</h2>
                <p class="text-xl text-gray-600 mb-8">Discover amazing products at great prices</p>
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-lg">
                        Shop Now
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-lg">
                        Get Started
                    </a>
                @endauth
            </div>
        </main>
    </div>
</body>
</html>

{{-- resources/views/dashboard.blade.php --}}
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
                    <h3 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()?->name }}!</h3>
                    
                    @if(Auth::user()?->isAdmin())
                        <div class="grid md:grid-cols-2 gap-6">
                            <a href="{{ route('admin.products.index') }}" 
                               class="block p-6 bg-blue-100 rounded-lg hover:bg-blue-200 transition">
                                <h4 class="text-xl font-bold mb-2">Manage Products</h4>
                                <p class="text-gray-700">Add, edit, or delete products from the store</p>
                            </a>
                            <a href="{{ route('dashboard') }}" 
                               class="block p-6 bg-green-100 rounded-lg hover:bg-green-200 transition">
                                <h4 class="text-xl font-bold mb-2">View Store</h4>
                                <p class="text-gray-700">Browse products as a customer</p>
                            </a>
                        </div>
                    @else
                        <div class="grid md:grid-cols-1 gap-6">
                            <a href="{{ route('dashboard') }}" 
                               class="block p-6 bg-green-100 rounded-lg hover:bg-green-200 transition">
                                <h4 class="text-xl font-bold mb-2">Shop Now</h4>
                                <p class="text-gray-700">Browse and purchase amazing products</p>
                            </a>
                        </div>
                    @endif

                    <div class="mt-8 p-4 bg-gray-100 rounded">
                        <p class="text-sm text-gray-600">
                            <strong>User Type:</strong> {{ Auth::user()?->isAdmin() ? 'Administrator' : 'Customer' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>