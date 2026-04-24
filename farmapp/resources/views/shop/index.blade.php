@extends('layouts.shop')

@section('page-title', isset($category) ? $category->name : 'Shop')

@section('content')

<div class="flex gap-6 lg:gap-8 items-start">

    {{-- Sidebar: categories --}}
    <aside class="w-56 lg:w-64 flex-shrink-0 sticky top-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-1">Categories</h2>
            <nav class="space-y-1.5">
                <a href="{{ route('shop.index') }}"
                   class="group flex items-center justify-between px-3 py-2.5 rounded-2xl text-sm font-medium transition-all duration-200 {{ !isset($category) ? 'bg-green-50 text-green-800 font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ !isset($category) ? 'text-green-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012-2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012-2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        All Products
                    </span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ !isset($category) ? 'bg-green-200/60 text-green-800' : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200 group-hover:text-gray-700' }}">
                        {{ $categories->sum('products_count') }}
                    </span>
                </a>
                @foreach($categories as $cat)
                    @if($cat->products_count > 0)
                    <a href="{{ route('shop.category', $cat) }}"
                       class="group flex items-center justify-between px-3 py-2.5 rounded-2xl text-sm font-medium transition-all duration-200 {{ isset($category) && $category->id === $cat->id ? 'bg-green-50 text-green-800 font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ isset($category) && $category->id === $cat->id ? 'text-green-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
                            {{ $cat->name }}
                        </span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ isset($category) && $category->id === $cat->id ? 'bg-green-200/60 text-green-800' : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200 group-hover:text-gray-700' }}">
                            {{ $cat->products_count }}
                        </span>
                    </a>
                    @endif
                @endforeach
            </nav>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 min-w-0">

        {{-- Heading --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6 bg-white p-6 sm:px-8 sm:py-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-gradient-to-br from-green-100 to-emerald-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
            <div class="relative">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    {{ isset($category) ? $category->name : 'Discover Products' }}
                </h1>
                <p class="text-sm font-medium text-gray-500 mt-2 flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                    </span>
                    Showing {{ $products->count() }} {{ Str::plural('product', $products->count()) }}
                </p>
            </div>
            @if(isset($category))
                <a href="{{ route('shop.index') }}" class="relative inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 px-4 py-2.5 rounded-2xl transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    Clear filter
                </a>
            @endif
        </div>

        {{-- Product grid --}}
        @if($products->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 px-4 bg-white rounded-3xl shadow-sm border border-gray-100 text-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6 shadow-inner">
                    <span class="text-4xl">🌱</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No products found</h3>
                <p class="text-gray-500 max-w-sm mb-8 text-sm leading-relaxed">We couldn't find any products in this category right now. Check back later or explore other sections.</p>
                <a href="{{ route('shop.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                    Browse All Products
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                <div class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col relative transform hover:-translate-y-1">
                    {{-- Image Container --}}
                    <div class="relative h-52 bg-gray-50/50 p-6 overflow-hidden flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}"
                                alt="{{ $product->name }}"
                                class="max-w-full max-h-full object-contain group-hover:scale-110 transition-transform duration-500 drop-shadow-sm">
                        @else
                            <div class="text-6xl group-hover:scale-110 transition-transform duration-500 opacity-80 filter drop-shadow-sm">🥬</div>
                        @endif
                        
                        @if($product->category)
                            <div class="absolute top-4 left-4 z-10">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-white/90 backdrop-blur-md text-gray-800 shadow-sm border border-white/20">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                        @endif
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    </div>

                    <div class="p-5 flex flex-col flex-1 border-t border-gray-50/80">
                        <h2 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-green-600 transition-colors line-clamp-1" title="{{ $product->name }}">{{ $product->name }}</h2>
                        
                        @if($product->description)
                            <p class="text-sm text-gray-500 mt-1 flex-1 line-clamp-2 leading-relaxed" title="{{ $product->description }}">{{ $product->description }}</p>
                        @else
                            <div class="flex-1"></div>
                        @endif
                        
                        <div class="mt-5 flex items-baseline gap-1">
                            <span class="text-l font-extrabold text-gray-800 tracking-tight">₹{{ number_format($product->price, 2) }}</span>
                            <span class="text-xs font-medium text-gray-400">/ {{ $product->unit }}</span>
                        </div>
                        
                        <form class="mt-5 flex gap-2 add-to-cart-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="relative w-16 flex-shrink-0">
                                <input type="number" name="quantity" value="1" min="1" max="99"
                                    class="w-full h-full border border-gray-200 rounded-2xl px-2 py-2 text-sm font-semibold text-center text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 bg-gray-50 hover:bg-gray-100 transition-colors shadow-inner appearance-none"
                                    style="-moz-appearance: textfield;">
                            </div>
                            
                            <button type="submit"
                                    class="flex-1 relative overflow-hidden group/btn bg-green-600 text-white text-sm font-semibold py-2.5 rounded-2xl transition-all duration-300 hover:bg-green-700 hover:shadow-lg hover:shadow-green-600/20 active:scale-[0.98] flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 transition-transform group-hover/btn:-translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <span class="btn-text">Add to Cart</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
/* Remove spinner from number input for a cleaner look */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
}
</style>



@endsection