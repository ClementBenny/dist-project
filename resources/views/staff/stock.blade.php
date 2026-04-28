@extends('layouts.staff')

@section('page-title', 'Stock Levels')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Stock Levels</h1>

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

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Product</th>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Category</th>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Unit</th>
                <th class="text-right px-6 py-3 font-medium text-gray-500">Current Stock</th>
                <th class="text-right px-6 py-3 font-medium text-gray-500">Update</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <p class="font-medium text-gray-800">{{ $product->name }}</p>
                    @if(!$product->is_active)
                        <span class="text-xs text-gray-400">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $product->category?->name ?? '—' }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $product->unit }}</td>
                <td class="px-6 py-4 text-right">
                    <span class="font-bold text-lg
                        {{ $product->stock === 0 ? 'text-red-500' : ($product->stock < 5 ? 'text-amber-500' : 'text-gray-800') }}">
                        {{ $product->stock }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <form action="{{ route('staff.stock.update', $product) }}" method="POST"
                          class="flex items-center justify-end gap-2">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="stock" value="{{ $product->stock }}"
                               min="0" class="w-20 border border-gray-300 rounded-lg px-2 py-1 text-sm text-center focus:outline-none focus:ring-2 focus:ring-green-500">
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                            Save
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-gray-400 py-12">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<p class="text-xs text-gray-400 mt-3">
    Products sorted by stock level — lowest first. 
    <span class="text-red-500 font-medium">Red = out of stock.</span>
    <span class="text-amber-500 font-medium">Amber = low stock (under 5).</span>
</p>

@endsection