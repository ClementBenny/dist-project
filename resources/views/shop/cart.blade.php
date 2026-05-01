@extends('layouts.public')

@section('page-title', 'Cart')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Your Cart</h1>

@if(empty($cart))
    <div class="text-center py-16">
        <p class="text-gray-400 mb-4">Your cart is empty.</p>
        <a href="{{ route('shop.index') }}" class="text-green-600 hover:text-green-800 font-medium">← Back to shop</a>
    </div>
@else
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3 font-medium text-gray-500">Product</th>
                    <th class="text-right px-6 py-3 font-medium text-gray-500">Price</th>
                    <th class="text-center px-6 py-3 font-medium text-gray-500">Qty</th>
                    <th class="text-right px-6 py-3 font-medium text-gray-500">Subtotal</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($products as $product)
                <tr>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $product->name }}</td>
                    <td class="px-6 py-4 text-right text-gray-600">₹{{ number_format($product->price, 2) }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('shop.cart.update') }}" method="POST" class="flex justify-center">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="number" name="quantity" value="{{ $cart[$product->id] }}"
                                   min="1" max="99" onchange="this.form.submit()"
                                   class="w-16 border border-gray-300 rounded-lg px-2 py-1 text-sm text-center focus:outline-none focus:ring-2 focus:ring-green-500">
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right font-medium text-gray-800">
                        ₹{{ number_format($product->price * $cart[$product->id], 2) }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('shop.cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="text-red-400 hover:text-red-600 text-xs">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t-2 border-gray-200">
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right font-semibold text-gray-700">Total</td>
                    <td class="px-6 py-4 text-right font-bold text-gray-900 text-base">₹{{ number_format($total, 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="flex justify-between items-center">
        <a href="{{ route('shop.index') }}" class="text-green-600 hover:text-green-800 text-sm">← Continue shopping</a>
        <a href="{{ route('shop.checkout') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
            Proceed to Checkout →
        </a>
    </div>
@endif

@endsection