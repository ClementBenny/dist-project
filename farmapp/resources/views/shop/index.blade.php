@extends('layouts.shop')

@section('page-title', isset($category) ? $category->name : 'Shop')

@section('content')

<div class="flex gap-6">

    {{-- Sidebar: categories --}}
    <aside class="w-48 flex-shrink-0">
        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Categories</h2>
        <nav class="space-y-1">
            <a href="{{ route('shop.index') }}"
               class="{{ !isset($category) ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-600 hover:text-green-700 hover:bg-gray-50' }} flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors">
                <span>All Products</span>
                <span class="text-xs text-gray-400">{{ $categories->sum('products_count') }}</span>
            </a>
            @foreach($categories as $cat)
                @if($cat->products_count > 0)
                <a href="{{ route('shop.category', $cat) }}"
                   class="{{ isset($category) && $category->id === $cat->id ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-600 hover:text-green-700 hover:bg-gray-50' }} flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors">
                    <span>{{ $cat->name }}</span>
                    <span class="text-xs text-gray-400">{{ $cat->products_count }}</span>
                </a>
                @endif
            @endforeach
        </nav>
    </aside>

    {{-- Main content --}}
    <div class="flex-1">

        {{-- Heading --}}
        <div class="flex items-center justify-between mb-5">
            <div>
                <h1 class="text-xl font-bold text-gray-800">
                    {{ isset($category) ? $category->name : 'All Products' }}
                </h1>
                <p class="text-sm text-gray-400 mt-0.5">{{ $products->count() }} {{ Str::plural('product', $products->count()) }}</p>
            </div>
            @if(isset($category))
                <a href="{{ route('shop.index') }}" class="text-sm text-green-600 hover:text-green-800">
                    ✕ Clear filter
                </a>
            @endif
        </div>

        {{-- Product grid --}}
        @if($products->isEmpty())
            <div class="text-center py-16 text-gray-400">
                <p class="text-4xl mb-3">🌱</p>
                <p>No products in this category yet.</p>
                <a href="{{ route('shop.index') }}" class="text-green-600 hover:text-green-800 text-sm mt-2 inline-block">
                    View all products
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($products as $product)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-48 object-contain p-3 bg-white">
                    @else
                        <div class="bg-green-50 h-36 flex items-center justify-center text-5xl">🥬</div>
                    @endif

                    <div class="p-4 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <h2 class="font-semibold text-gray-800">{{ $product->name }}</h2>
                            @if($product->category)
                                <span class="text-xs text-black-400 bg-red-100 px-2 py-0.5 rounded-full whitespace-nowrap">
                                    {{ $product->category->name }}
                                </span>
                            @endif
                        </div>
                        @if($product->description)
                            <p class="text-sm text-gray-500 mt-1 flex-1">{{ $product->description }}</p>
                        @endif
                        <div class="mt-4 flex items-center justify-between">
                            <span class="font-bold text-green-700">
                                ₹{{ number_format($product->price, 2) }}
                                <span class="text-xs font-normal text-gray-400">/ {{ $product->unit }}</span>
                            </span>
                        </div>
                        <form action="{{ route('shop.cart.add') }}" method="POST" class="mt-3 flex gap-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="number" name="quantity" value="1" min="1" max="99"
                                   class="w-16 border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center focus:outline-none focus:ring-2 focus:ring-green-500">
                            <button type="submit"
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-1.5 rounded-lg transition-colors">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection