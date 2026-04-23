@extends('layouts.shop')

@section('page-title', 'Checkout')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Checkout</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Delivery form --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="font-semibold text-gray-700 mb-4">Delivery Details</h2>

            <form action="{{ route('shop.checkout.store') }}" method="POST" novalidate>
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Address</label>
                    <textarea name="delivery_address" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('delivery_address') border-red-400 @enderror"
                              placeholder="Your full delivery address">{{ old('delivery_address') }}</textarea>
                    @error('delivery_address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea name="notes" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                              placeholder="Leave at gate, ring doorbell, etc.">{{ old('notes') }}</textarea>
                </div>

                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition-colors">
                    Place Order — ₹{{ number_format($total, 2) }}
                </button>
            </form>
        </div>
    </div>

    {{-- Order summary --}}
    <div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h2 class="font-semibold text-gray-700 mb-3">Order Summary</h2>
            <div class="space-y-2 text-sm">
                @foreach($products as $product)
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ $product->name }} × {{ $cart[$product->id] }}</span>
                    <span class="font-medium">₹{{ number_format($product->price * $cart[$product->id], 2) }}</span>
                </div>
                @endforeach
            </div>
            <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between font-bold">
                <span>Total</span>
                <span>₹{{ number_format($total, 2) }}</span>
            </div>
        </div>
    </div>

</div>

@endsection