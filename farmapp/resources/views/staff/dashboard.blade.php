@extends('layouts.staff')

@section('page-title', 'Staff Dashboard')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">
    Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
    {{ auth()->user()->name }} 👋
</h1>

{{-- Status count cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Pending</p>
        <p class="text-3xl font-bold text-yellow-500">{{ $statusCounts['pending'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Confirmed</p>
        <p class="text-3xl font-bold text-blue-500">{{ $statusCounts['confirmed'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Picking</p>
        <p class="text-3xl font-bold text-purple-500">{{ $statusCounts['picking'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Packed</p>
        <p class="text-3xl font-bold text-indigo-500">{{ $statusCounts['packed'] }}</p>
    </div>
</div>

{{-- Active orders list --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-700">Active Orders</h2>
        <a href="{{ route('staff.orders') }}" class="text-sm text-green-600 hover:text-green-800">
            View all →
        </a>
    </div>

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

    @forelse($activeOrders as $order)
    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between hover:bg-gray-50 transition-colors">
        <div>
            <p class="font-medium text-gray-800">
                Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                <span class="text-gray-400 font-normal">· {{ $order->user->name }}</span>
            </p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->diffForHumans() }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColours[$order->status] }}">
                {{ ucfirst($order->status) }}
            </span>
            <a href="{{ route('staff.orders.show', $order) }}"
               class="text-green-600 hover:text-green-800 text-sm font-medium">
                Open →
            </a>
        </div>
    </div>
    @empty
    <div class="text-center py-12 text-gray-400">
        <p class="text-3xl mb-2">✅</p>
        <p>All caught up — no active orders.</p>
    </div>
    @endforelse
</div>

@endsection