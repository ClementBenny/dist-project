@extends('layouts.wholesale')

@section('title', isset($category) ? $category->name : 'All Products')

@section('content')
    @include('partials.flash')

    <div class="flex gap-8">

        {{-- Category Sidebar --}}
        <aside class="w-48 shrink-0">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Categories</h2>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('wholesale.index') }}"
                       class="block text-sm px-3 py-1.5 rounded-md {{ !isset($category) ? 'bg-green-100 text-green-800 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        All Products
                    </a>
                </li>
                @foreach ($categories as $cat)
                    <li>
                        <a href="{{ route('wholesale.category', $cat) }}"
                           class="block text-sm px-3 py-1.5 rounded-md {{ isset($category) && $category->id === $cat->id ? 'bg-green-100 text-green-800 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            {{ $cat->name }}
                            <span class="text-xs text-gray-400">({{ $cat->products_count }})</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        {{-- Product Grid --}}
        <div class="flex-1">
            @forelse ($products as $product)
                @if ($loop->first)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @endif

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col">
                    <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if ($product->image)
                            <img src="{{ Storage::url($product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover object-center">
                        @else
                            <span class="text-5xl">🥬</span>
                        @endif
                    </div>
                    <div class="p-3 flex flex-col flex-1">
                        <p class="text-xs text-gray-400 mb-0.5">{{ $product->category?->name }}</p>
                        <h3 class="text-sm font-semibold text-gray-800">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1 flex-1">{{ $product->description }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <div>
                                <span class="text-base font-bold text-green-700">
                                    ₹{{ number_format($product->bulk_price, 2) }}
                                </span>
                                <span class="text-xs text-gray-400">/ {{ $product->unit }}</span>
                            </div>
                            @if ($product->stock > 0)
                                <button
                                    onclick="addToCart({{ $product->id }})"
                                    class="text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg">
                                    Add
                                </button>
                            @else
                                <span class="text-xs text-red-500 font-medium">Out of stock</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($loop->last)
                    </div>
                @endif
            @empty
                <p class="text-gray-500 text-sm">No products available.</p>
            @endforelse
        </div>

    </div>
@endsection

@push('scripts')
<script>
function addToCart(productId) {
    fetch('{{ route('wholesale.cart.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity: 1 }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById('cart-badge');
            badge.textContent = data.cart_count;
            badge.classList.remove('hidden');
            showToast('Item added to cart!');
        }
    });
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.className = 'fixed bottom-5 right-5 bg-green-600 text-white text-sm px-4 py-2 rounded-lg shadow-lg z-50';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endpush