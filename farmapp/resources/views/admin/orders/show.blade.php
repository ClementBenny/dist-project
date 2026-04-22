@extends('layouts.admin')

@section('page-title', 'Order #{{ str_pad($order->id, 4, "0", STR_PAD_LEFT) }}')

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

<div class="mb-6">
    <a href="{{ route('admin.orders.index') }}" class="text-green-600 hover:text-green-800 text-sm">← Back to Orders</a>
    <div class="flex items-center gap-3 mt-1">
        <h1 class="text-2xl font-bold text-gray-800">
            Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
        </h1>
        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColours[$order->status] }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>
    <p class="text-sm text-gray-400 mt-0.5">Placed {{ $order->created_at->diffForHumans() }}</p>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Order Items --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Items</h2>
            </div>
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
                        <td class="px-6 py-3 text-right font-medium text-gray-800">
                            ₹{{ number_format($item->unit_price * $item->quantity, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-gray-200">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right font-semibold text-gray-700">Total</td>
                        <td class="px-6 py-3 text-right font-bold text-gray-900">₹{{ number_format($order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Sidebar: Customer + Status --}}
    <div class="space-y-4">

        {{-- Customer info --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h2 class="font-semibold text-gray-700 mb-3">Customer</h2>
            <p class="text-sm font-medium text-gray-800">{{ $order->user->name }}</p>
            <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Delivery Address</p>
                <p class="text-sm text-gray-700">{{ $order->delivery_address }}</p>
            </div>
            @if($order->notes)
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Notes</p>
                <p class="text-sm text-gray-700">{{ $order->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Update Status --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h2 class="font-semibold text-gray-700 mb-3">Update Status</h2>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <select name="status"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @foreach(['pending', 'confirmed', 'picking', 'packed', 'delivered', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-sm font-medium transition-colors">
                    Save Status
                </button>
            </form>
        </div>

    </div>
</div>

@endsection