@extends('layouts.shop')

@section('page-title', 'Order Detail')

@section('content')

@php
    $statusColours = [
        'pending'   => 'bg-yellow-100 text-yellow-700',
        'confirmed' => 'bg-blue-100 text-blue-700',
        'picking'   => 'bg-purple-100 text-purple-700',
        'packed'    => 'bg-indigo-100 text-indigo-700',
        'delivered' => 'bg-green-100 text-green-700',
        'cancelled' => 'bg-red-100 text-red-700',
    ];
@endphp

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="mb-6">
    <a href="{{ route('shop.orders') }}" class="text-green-600 hover:text-green-800 text-sm">← My Orders</a>
    <div class="flex items-center gap-3 mt-1">
        <h1 class="text-2xl font-bold text-gray-800">Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h1>
        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColours[$order->status] }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>
    <p class="text-sm text-gray-400 mt-0.5">Placed {{ $order->created_at->diffForHumans() }}</p>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-4">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Product</th>
                <th class="text-right px-6 py-3 font-medium text-gray-500">Unit Price</th>
                <th class="text-right px-6 py-3 font-medium text-gray-500">Qty</th>
                <th class="text-right px-6 py-3 font-medium text-gray-500">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($order->items as $item)
            <tr>
                <td class="px-6 py-3 font-medium text-gray-800">{{ $item->product->name }}</td>
                <td class="px-6 py-3 text-right text-gray-600">₹{{ number_format($item->unit_price, 2) }}</td>
                <td class="px-6 py-3 text-right text-gray-600">{{ $item->quantity }}</td>
                <td class="px-6 py-3 text-right font-medium">₹{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="border-t-2 border-gray-200">
            <tr>
                <td colspan="3" class="px-6 py-4 text-right font-semibold text-gray-700">Total</td>
                <td class="px-6 py-4 text-right font-bold text-gray-900">₹{{ number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-sm">
    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Delivery Address</p>
    <p class="text-gray-700">{{ $order->delivery_address }}</p>
    @if($order->notes)
        <p class="text-xs text-gray-400 uppercase tracking-wide mt-3 mb-1">Notes</p>
        <p class="text-gray-700">{{ $order->notes }}</p>
    @endif
</div>

@endsection