@extends('layouts.wholesale')

@section('title', 'Checkout')

@section('content')
    @include('partials.flash')

    <h1 class="text-xl font-bold text-gray-800 mb-6">Checkout</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- Order Summary --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Order Summary</h2>
            <ul class="divide-y divide-gray-100 text-sm">
                @foreach ($cart as $productId => $quantity)
                    @if ($products->has($productId))
                        @php $product = $products[$productId]; @endphp
                        <li class="py-2 flex justify-between">
                            <span class="text-gray-700">{{ $product->name }} × {{ $quantity }}</span>
                            <span class="text-gray-800 font-medium">₹{{ number_format($product->bulk_price * $quantity, 2) }}</span>
                        </li>
                    @endif
                @endforeach
            </ul>
            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between font-bold text-gray-800">
                <span>Total</span>
                <span class="text-green-700">₹{{ number_format($total, 2) }}</span>
            </div>
        </div>

        {{-- Delivery Details --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Delivery Details</h2>
            <form method="POST" action="{{ route('wholesale.checkout.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm text-gray-600 mb-1" for="delivery_address">Delivery Address</label>
                    <textarea id="delivery_address" name="delivery_address" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('delivery_address') border-red-500 @enderror">{{ old('delivery_address') }}</textarea>
                    @error('delivery_address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label class="block text-sm text-gray-600 mb-1" for="notes">Notes <span class="text-gray-400">(optional)</span></label>
                    <textarea id="notes" name="notes" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('notes') }}</textarea>
                </div>
                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2.5 rounded-lg">
                    Place Order
                </button>
            </form>
        </div>

    </div>
@endsection