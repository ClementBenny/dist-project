@extends('layouts.staff')

@section('page-title', 'Orders')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Active Orders</h1>

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

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Order</th>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Customer</th>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Status</th>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Placed</th>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Address</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 font-mono text-gray-500">
                    #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="px-6 py-4 font-medium text-gray-800">{{ $order->user->name }}</td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColours[$order->status] }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $order->created_at->diffForHumans() }}</td>
                <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $order->delivery_address }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('staff.orders.show', $order) }}"
                       class="text-green-600 hover:text-green-800 font-medium">Open →</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-gray-400 py-12">No active orders.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection