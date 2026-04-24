@extends('layouts.staff')

@section('page-title')Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}@endsection

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

    // What status comes next — staff advance orders forward
    $nextStatus = [
        'pending'   => 'confirmed',
        'confirmed' => 'picking',
        'picking'   => 'packed',
        'packed'    => 'delivered',
    ];

    $nextLabels = [
        'pending'   => '✅ Confirm Order',
        'confirmed' => '🧺 Start Picking',
        'picking'   => '📦 Mark as Packed',
        'packed'    => '🚚 Mark as Delivered',
    ];
@endphp

@if(session('success'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button @click="show = false" class="text-green-600 hover:text-green-800 ml-4 text-lg leading-none">×</button>
    </div>
@endif

<div class="mb-6">
    <a href="{{ route('staff.orders') }}" class="text-green-600 hover:text-green-800 text-sm">← Back to Orders</a>
    <div class="flex items-center gap-3 mt-1">
        <h1 class="text-2xl font-bold text-gray-800">
            Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
        </h1>
        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColours[$order->status] }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>
    <p class="text-sm text-gray-400 mt-0.5">
        {{ $order->user->name }} · Placed {{ $order->created_at->diffForHumans() }}
    </p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Pick list --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Pick List</h2>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Product</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Category</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Qty</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Unit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    <tr class="{{ $order->status === 'picking' ? 'hover:bg-yellow-50' : '' }} transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $item->product->name }}
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs">
                            {{ $item->product->category?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800 text-base">
                            {{ $item->quantity }}
                        </td>
                        <td class="px-6 py-4 text-right text-gray-500">
                            {{ $item->product->unit }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-gray-200 bg-gray-50">
                    <tr>
                        <td colspan="2" class="px-6 py-3 text-right font-semibold text-gray-700">Order Total</td>
                        <td colspan="2" class="px-6 py-3 text-right font-bold text-gray-900">
                            ₹{{ number_format($order->total, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">

        {{-- Delivery info --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h2 class="font-semibold text-gray-700 mb-3">Delivery Info</h2>
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Address</p>
            <p class="text-sm text-gray-700 mb-3">{{ $order->delivery_address }}</p>
            @if($order->notes)
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Notes</p>
                <p class="text-sm text-gray-700 bg-yellow-50 border border-yellow-100 rounded-lg px-3 py-2">
                    {{ $order->notes }}
                </p>
            @endif
        </div>

        {{-- Advance status --}}
        @if(isset($nextStatus[$order->status]))
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h2 class="font-semibold text-gray-700 mb-3">Advance Order</h2>
            <form action="{{ route('staff.orders.status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $nextStatus[$order->status] }}">
                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-medium transition-colors">
                    {{ $nextLabels[$order->status] }}
                </button>
            </form>
        </div>
        @endif



    </div>
</div>

@endsection