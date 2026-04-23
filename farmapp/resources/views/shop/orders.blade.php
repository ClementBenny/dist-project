@extends('layouts.shop')

@section('page-title', 'Order Detail')

@section('content')

@php
    $steps = ['pending', 'confirmed', 'picking', 'packed', 'delivered'];
    $currentIndex = array_search($order->status, $steps);
    $cancelled = $order->status === 'cancelled';

    $stepLabels = [
        'pending'   => 'Order Placed',
        'confirmed' => 'Confirmed',
        'picking'   => 'Being Picked',
        'packed'    => 'Packed',
        'delivered' => 'Delivered',
    ];

    $stepIcons = [
        'pending'   => '🧾',
        'confirmed' => '✅',
        'picking'   => '🧺',
        'packed'    => '📦',
        'delivered' => '🚚',
    ];
@endphp

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
@endif

<div class="mb-6">
    <a href="{{ route('shop.orders') }}" class="text-green-600 hover:text-green-800 text-sm">← My Orders</a>
    <div class="flex items-center justify-between mt-1">
        <h1 class="text-2xl font-bold text-gray-800">
            Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
        </h1>
        {{-- Cancel button — only show if not yet packed and not already cancelled --}}
        @if(!$cancelled && $currentIndex !== false && $currentIndex < 3)
            <form action="{{ route('shop.orders.cancel', $order) }}" method="POST"
                  onsubmit="return confirm('Cancel this order?')">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="text-sm text-red-500 hover:text-red-700 border border-red-300 hover:border-red-500 px-3 py-1.5 rounded-lg transition-colors">
                    Cancel Order
                </button>
            </form>
        @endif
    </div>
    <p class="text-sm text-gray-400 mt-0.5">Placed {{ $order->created_at->diffForHumans() }}</p>
</div>

{{-- Status Progress Bar --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
    @if($cancelled)
        <div class="flex items-center gap-3 text-red-600">
            <span class="text-2xl">❌</span>
            <div>
                <p class="font-semibold">Order Cancelled</p>
                <p class="text-sm text-red-400">This order was cancelled and will not be processed.</p>
            </div>
        </div>
    @else
        {{-- Step dots + connecting bar --}}
        <div class="relative flex items-start justify-between">

            {{-- Background bar --}}
            <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 z-0"></div>

            {{-- Filled progress bar --}}
            @php
                $progressPercent = $currentIndex !== false && count($steps) > 1
                    ? ($currentIndex / (count($steps) - 1)) * 100
                    : 0;
            @endphp
            <div class="absolute top-5 left-0 h-1 bg-green-500 z-0 transition-all duration-500"
                 style="width: {{ $progressPercent }}%"></div>

            {{-- Steps --}}
            @foreach($steps as $i => $step)
            @php
                $isDone    = $currentIndex !== false && $i < $currentIndex;
                $isCurrent = $currentIndex !== false && $i === $currentIndex;
            @endphp
            <div class="relative z-10 flex flex-col items-center text-center"
                 style="width: {{ 100 / count($steps) }}%">

                {{-- Circle --}}
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg mb-2
                    {{ $isDone    ? 'bg-green-500 text-white' : '' }}
                    {{ $isCurrent ? 'bg-green-600 text-white ring-4 ring-green-100' : '' }}
                    {{ !$isDone && !$isCurrent ? 'bg-gray-100 text-gray-400' : '' }}">
                    @if($isDone)
                        ✓
                    @else
                        {{ $stepIcons[$step] }}
                    @endif
                </div>

                {{-- Label --}}
                <p class="text-xs font-medium leading-tight
                    {{ $isCurrent ? 'text-green-700' : ($isDone ? 'text-green-600' : 'text-gray-400') }}">
                    {{ $stepLabels[$step] }}
                </p>
            </div>
            @endforeach

        </div>
    @endif
</div>

{{-- Items table --}}
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