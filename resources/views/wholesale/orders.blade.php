@extends('layouts.wholesale')

@section('title', 'My Orders')

@section('content')

    <h1 class="text-xl font-bold text-gray-800 mb-6">My Orders</h1>

    @forelse ($orders as $order)
        <div class="bg-white rounded-xl border border-gray-200 p-5 mb-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <span class="text-sm font-semibold text-gray-800">
                        Order #{{ strtoupper(substr(md5($order->id . $order->created_at), 0, 8)) }}
                    </span>
                    <span class="text-xs text-gray-400 ml-2">{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <span class="text-base font-bold text-green-700">₹{{ number_format($order->total, 2) }}</span>
            </div>

            {{-- Status Progress Bar --}}
            @php
                $statuses = ['pending', 'confirmed', 'picking', 'packed', 'delivered'];
                $currentIndex = array_search($order->status, $statuses);
            @endphp

            @if ($order->status !== 'cancelled')
                <div class="flex items-center gap-1 mb-3">
                    @foreach ($statuses as $i => $status)
                        <div class="flex-1 h-1.5 rounded-full {{ $i <= $currentIndex ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 capitalize mb-3">Status: <span class="font-medium text-gray-700">{{ $order->status }}</span></p>
            @else
                <p class="text-xs text-red-500 font-medium mb-3">Cancelled</p>
            @endif

            <a href="{{ route('wholesale.orders.show', $order) }}"
               class="text-xs text-green-700 hover:underline">View details →</a>
        </div>
    @empty
        <p class="text-gray-500 text-sm">You have no orders yet. <a href="{{ route('wholesale.index') }}" class="text-green-700 underline">Start shopping</a></p>
    @endforelse
@endsection