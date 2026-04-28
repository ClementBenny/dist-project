@extends('layouts.shop')

@section('page-title', isset($category) ? $category->name : 'Shop')

@section('content')

<div class="flex gap-6 lg:gap-8 items-start">

    {{-- Sidebar --}}
    <aside class="w-56 lg:w-64 flex-shrink-0 sticky top-6">
        {{-- Updated: Added rounded-3xl and overflow-hidden for consistent pill-like curvature --}}
        <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 p-5">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-1">Categories</h2>

            <nav class="space-y-1.5">
                <a href="{{ route('shop.index') }}"
                   class="group flex items-center justify-between px-3 py-2.5 rounded-full text-sm font-medium
                   {{ !isset($category) ? 'bg-green-50 text-green-800 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>All Products</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-gray-100">
                        {{ $categories->sum('products_count') }}
                    </span>
                </a>

                @foreach($categories as $cat)
                    @if($cat->products_count > 0)
                        <a href="{{ route('shop.category', $cat) }}"
                           class="group flex items-center justify-between px-3 py-2.5 rounded-full text-sm font-medium
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
        {{-- Updated: Added rounded-3xl to enforce the pill-box shape --}}
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

            <div class="text-center py-20 bg-white rounded-3xl border border-gray-100">
                <div class="text-5xl mb-4">🌱</div>
                <h3 class="text-xl font-bold mb-2">No products found</h3>
                <a href="{{ route('shop.index') }}" class="px-6 py-3 bg-gray-900 text-white rounded-full">
                    Browse All
                </a>
            </div>

        @else

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach($products as $product)

                <div class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden">

                    {{-- IMAGE --}}
                   <div class="relative h-76 w-full overflow-hidden rounded-t-3xl bg-white">

                        @if($product->image)
                           <img src="{{ Storage::url($product->image) }}"
                                class="block w-full h-full object-contain object-center transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="text-6xl">🥬</div>
                        @endif

                    </div>

                    {{-- CONTENT --}}
                    <div class="p-5 flex flex-col flex-1">

                        <h2 class="font-bold text-gray-900 group-hover:text-green-600 transition-colors duration-200">
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
                            <span class="text-xs font-semibold px-3 py-1 rounded-full
                                        bg-white/80 backdrop-blur-md
                                        text-green-800 border border-green-100
                                        shadow-md whitespace-nowrap ring-1 ring-green-200/50">
                                {{ $product->category->name }}
                            </span>
                            @endif
                            
                        </div>

                        {{-- ADD TO CART --}}
                        @if($product->stock <= 0)
                            <div class="mt-4">
                                <span class="w-full block text-center bg-gray-100 text-gray-400 rounded-full py-1.5 text-sm font-medium">
                                    Out of Stock
                                </span>
                            </div>
                        @else
                            <div class="mt-4 flex gap-2">
                                <input type="number" 
                                    id="qty-{{ $product->id }}" 
                                    value="1" 
                                    min="1" 
                                    max="{{ $product->stock }}"
                                    class="w-16 text-center border border-gray-200 rounded-full px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-green-500">

                                <button onclick="addToCart({{ $product->id }})"
                                        class="flex-1 bg-green-600 text-white rounded-full py-1.5 font-medium hover:bg-green-700 transition-colors duration-200">
                                    Add
                                </button>
                            </div>

                            @if($product->stock <= 5)
                                <p class="text-xs text-amber-500 mt-1.5">Only {{ $product->stock }} left</p>
                            @endif
                        @endif
                    </div>

                </div>

                @endforeach

            </div>

        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
function addToCart(productId) {
    const quantity = document.getElementById('qty-' + productId).value;

    fetch('{{ route('shop.cart.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity: parseInt(quantity) }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById('cart-count-badge');
            badge.textContent = data.cart_count;
            badge.classList.remove('hidden');
            showToast('Added to cart!');
        }
    });
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.className = 'fixed bottom-5 right-5 bg-green-600 text-white text-sm px-4 py-2 rounded-full shadow-lg z-50';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endpush