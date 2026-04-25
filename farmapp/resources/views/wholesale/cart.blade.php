@extends('layouts.wholesale')

@section('title', 'Cart')

@section('content')
    @include('partials.flash')

    <h1 class="text-xl font-bold text-gray-800 mb-6">Your Cart</h1>

    @if (empty($cart))
        <p class="text-gray-500 text-sm">Your cart is empty. <a href="{{ route('wholesale.index') }}" class="text-green-700 underline">Browse products</a></p>
    @else
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-4 py-3">Product</th>
                        <th class="text-left px-4 py-3">Bulk Price</th>
                        <th class="text-left px-4 py-3">Quantity</th>
                        <th class="text-left px-4 py-3">Subtotal</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($cart as $productId => $quantity)
                        @if ($products->has($productId))
                            @php $product = $products[$productId]; @endphp
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                                <td class="px-4 py-3 text-gray-600">₹{{ number_format($product->bulk_price, 2) }} / {{ $product->unit }}</td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('wholesale.cart.update') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $productId }}">
                                        <input type="number" name="quantity" value="{{ $quantity }}" min="1"
                                               onchange="this.form.submit()"
                                               class="w-16 border border-gray-300 rounded px-2 py-1 text-sm">
                                    </form>
                                </td>
                                <td class="px-4 py-3 text-gray-800">₹{{ number_format($product->bulk_price * $quantity, 2) }}</td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('wholesale.cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $productId }}">
                                        <button class="text-red-500 hover:text-red-700 text-xs">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-sm font-semibold text-gray-700 text-right">Total</td>
                        <td class="px-4 py-3 text-base font-bold text-green-700">₹{{ number_format($total, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-6 flex justify-between items-center">
            <a href="{{ route('wholesale.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Continue Shopping</a>
            <a href="{{ route('wholesale.checkout') }}"
               class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg">
                Proceed to Checkout
            </a>
        </div>
    @endif
@endsection