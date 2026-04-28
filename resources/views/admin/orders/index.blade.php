@extends('layouts.admin')

@section('page-title', 'Orders')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Orders</h1>
    <span class="text-sm text-gray-500">{{ $orders->count() }} total</span>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif

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

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Order</th>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Customer</th>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Status</th>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Total</th>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Date</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 font-mono text-gray-500">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td class="px-6 py-4">
                    <p class="font-medium text-gray-800">{{ $order->user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColours[$order->status] }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 font-medium text-gray-800">₹{{ number_format($order->total, 2) }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.orders.show', $order) }}"
                       class="text-green-600 hover:text-green-800 font-medium">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-gray-400 py-12">No orders yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection