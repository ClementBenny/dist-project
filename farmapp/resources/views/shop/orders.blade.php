@extends('layouts.shop')

@section('page-title', 'My Orders')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">My Orders</h1>

@php
    $statusColours = [
        'pending'   => 'bg-yellow-100 text-yellow-700',
        'confirmed' => 'bg-blue-100 text-blue-700',
        'picking'   => 'bg-purple-100 text-purple-700',
        'packed'    => 'bg-indigo-100 text-indigo-700',
        'delivered' => 'bg-green-100 text-green-700',
        'cancelled' => 'bg-red-100 text-red-700',
    ];

    $stepLabels = [
        'pending'   => 'Order Placed',
        'confirmed' => 'Confirmed',
        'picking'   => 'Being Picked',
        'packed'    => 'Packed',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ];
@endphp

@forelse($orders as $order)
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-4">

    {{-- Top row: order number + status badge + view link --}}
    <div class="flex items-start justify-between mb-4">
        <div>
            <p class="font-semibold text-gray-800">Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
            <p class="text-sm text-gray-500 mt-0.5">
                {{ $order->created_at->format('d M Y') }} · ₹{{ number_format($order->total, 2) }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColours[$order->status] }}">
                {{ ucfirst($order->status) }}
            </span>
            <a href="{{ route('shop.orders.show', $order) }}"
               class="text-green-600 hover:text-green-800 text-sm font-medium">
                View →
            </a>
        </div>
    </div>

    {{-- Progress bar (only for non-cancelled orders) --}}
    @if($order->status !== 'cancelled')
        @php
            $steps = ['pending', 'confirmed', 'picking', 'packed', 'delivered'];
            $currentIndex = array_search($order->status, $steps);
            $progressPercent = $currentIndex !== false && count($steps) > 1
                ? ($currentIndex / (count($steps) - 1)) * 100
                : 0;
        @endphp

        <div class="relative flex items-start justify-between mt-2">

            {{-- Grey track --}}
            <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-200 z-0"></div>

            {{-- Green filled track --}}
            <div class="absolute top-4 left-0 h-0.5 bg-green-500 z-0 transition-all duration-500"
                 style="width: {{ $progressPercent }}%"></div>

            {{-- Step dots --}}
            @foreach($steps as $i => $step)
                @php
                    $isDone    = $currentIndex !== false && $i < $currentIndex;
                    $isCurrent = $currentIndex !== false && $i === $currentIndex;
                @endphp
                <div class="relative z-10 flex flex-col items-center text-center"
                     style="width: {{ 100 / count($steps) }}%">

                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm mb-1
                        {{ $isDone    ? 'bg-green-500 text-white' : '' }}
                        {{ $isCurrent ? 'bg-green-600 text-white ring-4 ring-green-100' : '' }}
                        {{ !$isDone && !$isCurrent ? 'bg-gray-100 text-gray-400' : '' }}">
                        @if($isDone) ✓ @else {{ ['🧾','✅','🧺','📦','🚚'][$i] }} @endif
                    </div>

                    <p class="text-xs leading-tight
                        {{ $isCurrent ? 'text-green-700 font-semibold' : ($isDone ? 'text-green-600' : 'text-gray-400') }}">
                        {{ $stepLabels[$step] }}
                    </p>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
            <span>❌</span>
            <span>This order was cancelled.</span>
        </div>
    @endif

</div>
@empty
<div class="text-center py-16">
    <p class="text-gray-400 mb-4">You haven't placed any orders yet.</p>
    <a href="{{ route('shop.index') }}" class="text-green-600 hover:text-green-800 font-medium">
        Start shopping →
    </a>
</div>
@endforelse

@endsection