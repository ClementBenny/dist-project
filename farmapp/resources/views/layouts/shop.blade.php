<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Farm Direct') — Farm Direct</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Top nav --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-5xl mx-auto px-4 flex items-center justify-between h-14">
            <a href="{{ route('shop.index') }}" class="font-bold text-green-700 text-lg">🌿 Farm Direct</a>
            <div class="flex items-center gap-5 text-sm">
                <a href="{{ route('shop.index') }}"
                   class="{{ request()->routeIs('shop.index') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700' }}">
                    Shop
                </a>
                <a href="{{ route('shop.orders') }}"
                   class="{{ request()->routeIs('shop.orders*') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700' }}">
                    My Orders
                </a>
                <a href="{{ route('shop.cart') }}"
                   class="{{ request()->routeIs('shop.cart') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700' }} relative">
                    🛒 Cart
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-1.5 -right-3 bg-green-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="text-gray-400 hover:text-gray-600">Sign out</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    <div class="max-w-5xl mx-auto px-4 pt-4">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <main class="max-w-5xl mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>
</html>