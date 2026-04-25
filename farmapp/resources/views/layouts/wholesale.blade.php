<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Wholesale') — Farm Direct</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('wholesale.index') }}" class="text-lg font-bold text-green-700">
                Farm Direct <span class="text-sm font-normal text-gray-500">Wholesale</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="{{ route('wholesale.index') }}" class="text-sm text-gray-600 hover:text-green-700">Products</a>
                <a href="{{ route('wholesale.orders') }}" class="text-sm text-gray-600 hover:text-green-700">My Orders</a>
                <a href="{{ route('wholesale.cart') }}" class="text-sm text-gray-600 hover:text-green-700 relative">
                    Cart
                    <span id="cart-badge"
                        class="ml-1 bg-green-600 text-white text-xs rounded-full px-1.5 py-0.5 {{ array_sum(session('wholesale_cart', [])) === 0 ? 'hidden' : '' }}">
                        {{ array_sum(session('wholesale_cart', [])) }}
                    </span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-gray-500 hover:text-red-600">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-6">
        @include('partials.flash')
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>