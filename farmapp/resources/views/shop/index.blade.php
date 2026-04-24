@extends('layouts.shop')

@section('page-title', isset($category) ? $category->name : 'Shop')

@section('content')

<div class="flex gap-6 lg:gap-8 items-start">

    {{-- Sidebar --}}
    <aside class="w-56 lg:w-64 flex-shrink-0 sticky top-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-1">Categories</h2>

            <nav class="space-y-1.5">
                <a href="{{ route('shop.index') }}"
                   class="group flex items-center justify-between px-3 py-2.5 rounded-2xl text-sm font-medium
                   {{ !isset($category) ? 'bg-green-50 text-green-800 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>All Products</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-gray-100">
                        {{ $categories->sum('products_count') }}
                    </span>
                </a>

                @foreach($categories as $cat)
                    @if($cat->products_count > 0)
                        <a href="{{ route('shop.category', $cat) }}"
                           class="group flex items-center justify-between px-3 py-2.5 rounded-2xl text-sm font-medium
                           {{ isset($category) && $category->id === $cat->id ? 'bg-green-50 text-green-800 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-gray-100">
                                {{ $cat->products_count }}
                            </span>
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1">

        {{-- Header --}}
        <div class="mb-6 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h1 class="text-3xl font-extrabold text-gray-900">
                {{ isset($category) ? $category->name : 'Discover Products' }}
            </h1>

            <p class="text-sm text-gray-500 mt-2">
                Showing {{ $products->count() }} {{ Str::plural('product', $products->count()) }}
            </p>
        </div>

        {{-- Products --}}
        @if($products->isEmpty())

            <div class="text-center py-20 bg-white rounded-3xl border">
                <div class="text-5xl mb-4">🌱</div>
                <h3 class="text-xl font-bold mb-2">No products found</h3>
                <a href="{{ route('shop.index') }}" class="px-6 py-3 bg-gray-900 text-white rounded-xl">
                    Browse All
                </a>
            </div>

        @else

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                @foreach($products as $product)

                <div class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition overflow-hidden flex flex-col">

                    {{-- IMAGE --}}
                    <div class="relative h-56 flex items-center justify-center overflow-hidden
                                bg-gradient-to-br from-gray-50 to-gray-100">

                        {{-- Soft light --}}
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.7),transparent_70%)]"></div>

                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}"
                                 class="relative z-10 w-[75%] h-[75%] object-contain
                                        transition-transform duration-500 group-hover:scale-110
                                        drop-shadow-[0_10px_20px_rgba(0,0,0,0.15)]">
                        @else
                            <div class="text-6xl">🥬</div>
                        @endif


                    </div>

                    {{-- CONTENT --}}
                    <div class="p-5 flex flex-col flex-1">

                        <h2 class="font-bold text-gray-900 group-hover:text-green-600">
                            {{ $product->name }}
                        </h2>

                        <p class="text-sm text-gray-500 mt-1 flex-1">
                            {{ $product->description }}
                        </p>

                        <div class="mt-4 flex items-center justify-between gap-2">

                            {{-- Price --}}
                            <div class="flex items-baseline gap-1">
                                <span class="text-lg font-bold text-gray-900">
                                    ₹{{ number_format($product->price, 2) }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    / {{ $product->unit }}
                                </span>
                            </div>

                            {{-- Category --}}
                            @if($product->category)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                        bg-white/60 backdrop-blur-sm
                                        text-gray-800 border border-white/40
                                        shadow-sm whitespace-nowrap">
                                {{ $product->category->name }}
                            </span>
                            @endif

                        </div>

                        {{-- ADD TO CART --}}
                        <form method="POST" action="{{ route('shop.cart.add') }}" class="mt-4 flex gap-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <input type="number" name="quantity" value="1" min="1"
                                   class="w-16 text-center border rounded-xl">

                            <button class="flex-1 bg-green-600 text-white rounded-xl hover:bg-green-700">
                                Add
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