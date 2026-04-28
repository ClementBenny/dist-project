@extends('layouts.wholesale')

@section('title')Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}@endsection

@section('content')

    <div class="mb-4">
        <a href="{{ route('wholesale.orders') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back to Orders</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h1 class="text-lg font-bold text-gray-800">
                    Order #{{ strtoupper(substr(md5($order->id . $order->created_at), 0, 8)) }}
                </h1>
                <p class="text-xs text-gray-400 mt-0.5">Placed on {{ $order->created_at->format('d M Y, g:i A') }}</p>
            </div>
            <span class="text-xl font-bold text-green-700">₹{{ number_format($order->total, 2) }}</span>
        </div>

        {{-- Status Progress Bar --}}
        @php
            $statuses = ['pending', 'confirmed', 'picking', 'packed', 'delivered'];
            $currentIndex = array_search($order->status, $statuses);
        @endphp

        @if ($order->status !== 'cancelled')
            <div class="mb-2">
                <div class="flex justify-between text-xs text-gray-400 mb-1">
                    @foreach ($statuses as $status)
                        <span class="capitalize">{{ $status }}</span>
                    @endforeach
                </div>
                <div class="flex items-center gap-1">
                    @foreach ($statuses as $i => $status)
                        <div class="flex-1 h-2 rounded-full {{ $i <= $currentIndex ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                    @endforeach
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2 capitalize">
                Current status: <span class="font-semibold text-gray-700">{{ $order->status }}</span>
            </p>
        @else
            <p class="text-sm text-red-500 font-medium">This order was cancelled.</p>
        @endif

        <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Delivery Address</p>
                <p class="text-gray-700">{{ $order->delivery_address }}</p>
            </div>
            @if ($order->notes)
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Notes</p>
                    <p class="text-gray-700">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="text-left px-4 py-3">Product</th>
                    <th class="text-left px-4 py-3">Unit Price</th>
                    <th class="text-left px-4 py-3">Qty</th>
                    <th class="text-left px-4 py-3">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($order->items as $item)
                    <tr>
                        <td class="px-4 py-3 text-gray-800 font-medium">{{ $item->product->name }}</td>
                        <td class="px-4 py-3 text-gray-600">₹{{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-gray-800">₹{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="3" class="px-4 py-3 text-sm font-semibold text-gray-700 text-right">Total</td>
                    <td class="px-4 py-3 text-base font-bold text-green-700">₹{{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Cancel Button --}}
    @if ($order->status !== 'cancelled' && $currentIndex < 3)
        <form method="POST" action="{{ route('wholesale.orders.cancel', $order) }}">
            @csrf
            @method('PATCH')
            <button type="submit"
                    onclick="return confirm('Are you sure you want to cancel this order?')"
                    class="text-sm text-red-600 border border-red-300 hover:bg-red-50 px-4 py-2 rounded-lg">
                Cancel Order
            </button>
        </form>
    @endif
@endsection