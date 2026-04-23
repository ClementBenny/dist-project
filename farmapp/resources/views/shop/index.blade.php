@extends('layouts.shop')

@section('page-title', 'Shop')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Fresh from the Farm</h1>

@if($products->isEmpty())
    <p class="text-gray-400 text-center py-16">No products available right now. Check back soon!</p>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($products as $product)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <div class="bg-green-50 h-36 flex items-center justify-center text-5xl">🥬</div>
            <div class="p-4 flex flex-col flex-1">
                <h2 class="font-semibold text-gray-800">{{ $product->name }}</h2>
                @if($product->description)
                    <p class="text-sm text-gray-500 mt-1 flex-1">{{ $product->description }}</p>
                @endif
                <div class="mt-4 flex items-center justify-between">
                    <span class="font-bold text-green-700">₹{{ number_format($product->price, 2) }}
                        <span class="text-xs font-normal text-gray-400">/ {{ $product->unit ?? 'unit' }}</span>
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

@endsection